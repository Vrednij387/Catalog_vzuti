<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL 202 ecommerce
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL 202 ecommerce is strictly forbidden.
 * In order to obtain a license, please contact us: tech@202-ecommerce.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe 202 ecommerce
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL 202 ecommerce est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter 202-ecommerce <tech@202-ecommerce.com>
 * ...........................................................................
 *
 * @author    202-ecommerce <tech@202-ecommerce.com>
 * @copyright Copyright (c) 202-ecommerce
 * @license   Commercial license
 */

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Services;

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use Configuration;
use Context;
use Module;
use Smarty;

/**
 * This class should be used to access Custom Fields from outside the module.
 * It is registered as a service in the Symfony container with
 * config/services.yml, but can only be used in a Symfony context.
 * It is also registered with Smarty (see TotCustomFields::__construct)
 */
class CustomFieldsService
{
    /**
     * The function registered by Smarty. The id_lang is optional.
     *
     * @see https://www.smarty.net/docs/en/api.register.plugin.tpl
     * @see TotCustomFields::__construct()
     *
     * @usage {customField id_object=202 input_code='my_custom_field' id_lang=1}
     *
     * @param array $params the function parameters (see Smarty documentation
     * @param Smarty $smarty
     *
     * @return mixed The value of the input
     */
    public static function getValueFromSmarty($params, $smarty)
    {
        if (isset($params['id_object']) && isset($params['input_code'])) {
            return static::getValue($params['id_object'], $params['input_code']);
        }

        return null;
    }

    /**
     * Generic function that may be used anywhere. No cache though, so careful !
     *
     * @param int $idObject the object's id
     * @param string $inputCode the input's unique code
     * @param bool $idLang
     *
     * @return mixed The value of the input
     */
    public static function getValue($idObject, $inputCode, $idLang = false)
    {
        // Load the module in case it's not done already
        Module::getInstanceByName('totcustomfields');

        $customFieldsInputService = new CustomFieldsInputService();

        /** @var CustomFieldsInput|bool $input */
        $input = $customFieldsInputService->getByCode($inputCode);
        if ($input) {
            if (!$input->is_translatable) {
                $value = $input->getValue((int) $idObject, null);
            } else {
                if (!$idLang) {
                    $idLang = (int) Context::getContext()->language->id;
                }

                $value = $input->getValue((int) $idObject, $idLang);

                // If we don't have a value for a translatable field,
                // attempt to get it with the default language
                if (!$value) {
                    $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
                    if ($id_lang_default != (int) $idLang) {
                        $value = $input->getValue((int) $idObject, $id_lang_default);
                    }
                }
            }

            if (!$value) {
                $value = $input->default_value;
            }

            return $value;
        }

        return null;
    }
}
