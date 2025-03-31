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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputImageValue;
use Db;
use DbQuery;

class CustomFieldsInputImageService
{
    /**
     * Returns an existing CustomFieldsInputImageValue from the parameters, or a new object filled with their values if none was found
     *
     * @param int $idInput
     * @param int $idObject
     * @param int|null $idLang if null, return non-translatable value
     *
     * @return CustomFieldsInputImageValue
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getInputValue($idInput, $idObject, $idLang)
    {
        $query = new DbQuery();
        $query->select('id_input_value');
        $query->from('totcustomfields_input_image_value');
        $query->where('id_input = ' . (int) $idInput);
        $query->where('id_object = ' . (int) $idObject);
        $query->where('id_lang ' . (is_null($idLang) ? 'IS NULL' : '= ' . (int) $idLang));

        $res = Db::getInstance()->getValue($query);

        if ($res) {
            return new CustomFieldsInputImageValue((int) $res);
        }

        $newInputValue = new CustomFieldsInputImageValue();
        $newInputValue->id_input = (int) $idInput;
        $newInputValue->id_object = (int) $idObject;
        $newInputValue->id_lang = ($idLang === null ? null : (int) $idLang);

        return $newInputValue;
    }

    /**
     * TODO to the service ?
     * Returns all translatable TotCustomFieldsInputImageValue
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
        $query->from('totcustomfields_input_image_value');
        $query->where('id_input = ' . (int) $idInput);
        $query->where('id_object = ' . (int) $idObject);
        $query->where('id_lang IS NOT NULL');

        $res = Db::getInstance()->executeS($query);

        if (!$res) {
            return [];
        }

        $inputValues = [];
        foreach ($res as $row) {
            $inputValues[] = new CustomFieldsInputImageValue($row['id_input_value']);
        }

        return $inputValues;
    }

    /**
     * TODO to the service ?
     * Gets all values for an input
     *
     * @param int $idInput
     *
     * @return array an array of TotCustomFieldsInputImageValue
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getAllInputValues($idInput)
    {
        $query = new DbQuery();
        $query->select('id_input_value');
        $query->from('totcustomfields_input_image_value');
        $query->where('id_input = ' . (int) $idInput);

        $res = Db::getInstance()->executeS($query);

        if (!$res) {
            return [];
        }

        $inputValues = [];
        foreach ($res as $row) {
            $inputValues[] = new CustomFieldsInputImageValue((int) $row['id_input_value']);
        }

        return $inputValues;
    }

    /**
     * Returns all values for an input and a language
     *
     * @param int $idInput
     * @param int|null $idLang if null, return non-translatable value
     *
     * @return array an array of TotCustomFieldsInputImageValue
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getAllValuesWithLang($idInput, $idLang)
    {
        $query = new DbQuery();
        $query->select('id_input_value');
        $query->from('totcustomfields_input_image_value');
        $query->where('id_input = ' . (int) $idInput);
        $query->where('id_lang ' . (is_null($idLang) ? 'IS NULL' : '= ' . (int) $idLang));

        $res = Db::getInstance()->executeS($query);

        if (!$res) {
            return [];
        }

        $inputValues = [];
        foreach ($res as $row) {
            $inputValues[] = new CustomFieldsInputImageValue((int) $row['id_input_value']);
        }

        return $inputValues;
    }
}
