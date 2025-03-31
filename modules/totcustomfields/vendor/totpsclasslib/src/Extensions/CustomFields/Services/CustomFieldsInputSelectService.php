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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputSelect;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputSelectValue;
use Configuration;
use Db;
use DbQuery;

class CustomFieldsInputSelectService
{
    /**
     * Returns an existing CustomFieldsInputSelectValue from the parameters, or a new object filled with their values if none was found
     *
     * @param int $idInput
     * @param int $idObject
     * @param int|null $idLang if null, return non-translatable value
     *
     * @return CustomFieldsInputSelectValue
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getInputValue($idInput, $idObject, $idLang)
    {
        $query = new DbQuery();
        $query->select('id_input_value');
        $query->from('totcustomfields_input_select_value');
        $query->where('id_input = ' . (int) $idInput);
        $query->where('id_object = ' . (int) $idObject);
        $query->where('id_lang ' . (is_null($idLang) ? 'IS NULL' : '= ' . (int) $idLang));

        $res = Db::getInstance()->getValue($query);

        if ($res) {
            return new CustomFieldsInputSelectValue((int) $res);
        }

        $newInputValue = new CustomFieldsInputSelectValue();
        $newInputValue->id_input = (int) $idInput;
        $newInputValue->id_object = (int) $idObject;
        $newInputValue->id_lang = ($idLang === null ? null : (int) $idLang);

        return $newInputValue;
    }

    /**
     * Gets all values for an input
     *
     * @param int $idInput
     *
     * @return array an array of TotCustomFieldsInputTextValue
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getAllInputValues($idInput)
    {
        $query = new DbQuery();
        $query->select('id_input_value');
        $query->from('totcustomfields_input_select_value');
        $query->where('id_input = ' . (int) $idInput);

        $res = Db::getInstance()->executeS($query);

        if (!$res) {
            return [];
        }

        $inputValues = [];
        foreach ($res as $row) {
            $inputValues[] = new CustomFieldsInputSelect((int) $row['id_input_value']);
        }

        return $inputValues;
    }
}
