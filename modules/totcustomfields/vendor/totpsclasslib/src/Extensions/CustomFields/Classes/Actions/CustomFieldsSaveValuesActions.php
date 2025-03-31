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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Actions;

use TotcustomfieldsClasslib\Actions\DefaultActions;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputService;
use TotcustomfieldsClasslib\Utils\Translate\TranslateTrait;
use Validate;

/**
 * Validates and saves some field values
 */
class CustomFieldsSaveValuesActions extends DefaultActions
{
    use TranslateTrait;

    protected $inputService;

    public function __construct()
    {
        $this->inputService = new CustomFieldsInputService();
    }

    public function validateValues()
    {
        $this->conveyor['validation_errors'] = [];

        foreach ($this->conveyor['inputs_values'] as $idInput => $value) {
            $input = $this->inputService->getById($idInput);
            if (!Validate::isLoadedObject($input)) {
                $this->conveyor['validation_errors'][] = sprintf(
                    $this->l('Custom Fields : field with id %s could not be found.', 'CustomFieldsSaveValuesActions'),
                    $idInput
                );

                continue;
            }

            if ($input->code_object != $this->conveyor['code_object']) {
                $this->conveyor['validation_errors'][] = sprintf(
                    $this->l('Custom Fields : field with id %s does not match object "%s".', 'CustomFieldsSaveValuesActions'),
                    $idInput,
                    $this->conveyor['code_object']
                );

                continue;
            }

            $ret = $input->isValidValue($value, $this->conveyor['id_object']);
            if (is_array($ret)) {
                $this->conveyor['validation_errors'] = array_merge($this->conveyor['validation_errors'], $ret);
            } else {
                $this->conveyor['valid_values'][$idInput] = $value;
            }
        }

        if ($this->conveyor['validation_errors']) {
            return false;
        }

        return true;
    }

    public function saveValues()
    {
        $errors = [];

        foreach ($this->conveyor['valid_values'] as $id_input => $value) {
            $input = $this->inputService->getById($id_input);
            if (!Validate::isLoadedObject($input)) {
                $this->conveyor['validation_errors'][] = sprintf(
                    $this->l('Custom Fields : field with id %s could not be found.', 'CustomFieldsSaveValuesActions'),
                    $id_input
                );

                continue;
            }

            $ret = $input->setValue($value, $this->conveyor['id_object']);
            if (is_array($ret)) {
                $errors = array_merge($errors, $ret);
            }
        }

        if ($errors) {
            $this->conveyor['save_errors'] = $errors;
        }

        return true;
    }
}
