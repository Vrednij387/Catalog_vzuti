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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputTextValue;
use Configuration;
use Db;
use DbQuery;

class CustomFieldsInputTextService
{
    /**
     * Returns an existing TotCustomFieldsInputTextValue from the parameters, or a new object filled with their values if none was found
     *
     * @param int $idInput
     * @param int $idObject
     * @param int|null $idLang if null, return non-translatable value
     *
     * @return CustomFieldsInputTextValue
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getInputValue($idInput, $idObject, $idLang)
    {
        $query = new DbQuery();
        $query->select('id_input_value');
        $query->from('totcustomfields_input_text_value');
        $query->where('id_input = ' . (int) $idInput);
        $query->where('id_object = ' . (int) $idObject);
        $query->where('id_lang ' . (is_null($idLang) ? 'IS NULL' : '= ' . (int) $idLang));

        $res = Db::getInstance()->getValue($query);

        if ($res) {
            return new CustomFieldsInputTextValue((int) $res);
        }

        $newInputValue = new CustomFieldsInputTextValue();
        $newInputValue->id_input = (int) $idInput;
        $newInputValue->id_object = (int) $idObject;
        $newInputValue->id_lang = ($idLang === null ? null : (int) $idLang);

        return $newInputValue;
    }

    /**
     * Returns all translatable TotCustomFieldsInputTextValue
     *
     * @param int $idInput
     * @param int $idObject
     *
     * @return array
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getInputTranslatableValues($idInput, $idObject)
    {
        $query = new DbQuery();
        $query->select('id_input_value');
        $query->from('totcustomfields_input_text_value');
        $query->where('id_input = ' . (int) $idInput);
        $query->where('id_object = ' . (int) $idObject);
        $query->where('id_lang IS NOT NULL');

        $res = Db::getInstance()->executeS($query);

        if (!$res) {
            return [];
        }

        $inputValues = [];
        foreach ($res as $row) {
            $inputValues[] = new CustomFieldsInputTextValue((int) $row['id_input_value']);
        }

        return $inputValues;
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
        $query->from('totcustomfields_input_text_value');
        $query->where('id_input = ' . (int) $idInput);

        $res = Db::getInstance()->executeS($query);

        if (!$res) {
            return [];
        }

        $inputValues = [];
        foreach ($res as $row) {
            $inputValues[] = new CustomFieldsInputTextValue((int) $row['id_input_value']);
        }

        return $inputValues;
    }

    /**
     * Copies ALL untranslated values to default language values, if they don't yet exist.
     *
     * @param int $idInput
     *
     * @return bool
     */
    public function copyUntranslatedValuesToDefaultLanguage($idInput)
    {
        $idLangDefault = (int) Configuration::get('PS_LANG_DEFAULT');

        $sqlQuery = '
                    INSERT INTO `' . _DB_PREFIX_ . 'totcustomfields_input_text_value' . '` (id_input, id_object, id_lang, value)
                    SELECT id_input, id_object, ' . (int) $idLangDefault . ', value
                    FROM `' . _DB_PREFIX_ . 'totcustomfields_input_text_value' . '`
                    WHERE id_input = ' . (int) $idInput . '
                    AND id_lang IS NULL
                    AND NOT EXISTS (
                        SELECT id_input FROM `' . _DB_PREFIX_ . 'totcustomfields_input_text_value' . '`
                        WHERE id_input = ' . (int) $idInput . '
                        AND id_lang = ' . (int) $idLangDefault . '
                    )'
        ;

        return Db::getInstance()->execute($sqlQuery);
    }
}
