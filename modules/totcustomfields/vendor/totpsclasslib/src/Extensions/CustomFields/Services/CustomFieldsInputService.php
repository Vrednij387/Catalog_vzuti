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
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders\InputEntityLoader;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsObjectService;
use Db;
use DbQuery;
use Hook;
use Shop;

class CustomFieldsInputService
{
    protected $objectService;

    /**
     * @var InputEntityLoader
     */
    protected $entityLoader;

    /**
     * CustomFieldsInputService constructor.
     *
     * @throws \PrestaShopException
     */
    public function __construct()
    {
        $this->objectService = new CustomFieldsObjectService();
        $this->entityLoader = new InputEntityLoader();
        $this->entityLoader->loadEntities();
    }

    /**
     * Installs SQL for every input type
     *
     * @return bool
     */
    public function installAllSQL()
    {
        foreach ($this->entityLoader->getEntities() as $type) {
            $className = $type['className'];
            /** @var CustomFieldsInput $instance */
            $instance = new $className();

            if (!$instance->installSQL()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets an input by id with the right class
     *
     * @param int $id
     *
     * @return CustomFieldsInput|bool
     */
    public function getById($id)
    {
        $query = new DbQuery();
        $query->select('code_input_type');
        $query->from('totcustomfields_input', 'tcf_input');
        $query->where('tcf_input.id_input = ' . (int) $id);
        $query->from('totcustomfields_input_shop', 'tcf_input_shop');
        // Multishop; we have to remove the 'AND' from PS's query...
        $query->where(str_replace('AND', '', Shop::addSqlRestriction(false, 'tcf_input_shop')));
        $query->groupBy('tcf_input.id_input');

        $codeInputType = Db::getInstance()->getValue($query);
        if (!$codeInputType) {
            return false;
        }

        $inputTypes = $this->entityLoader->getEntities();
        foreach ($inputTypes as $type) {
            if ($type['code'] == $codeInputType) {
                return new $type['className']($id);
            }
        }

        return false;
    }

    /**
     * Gets an input by code with the right class
     *
     * @param string $code
     *
     * @return CustomFieldsInput|bool
     */
    public function getByCode($code)
    {
        $query = new DbQuery();
        $query->select('tcf_input.id_input, tcf_input.code_input_type');
        $query->from('totcustomfields_input', 'tcf_input');
        $query->where("tcf_input.code = '" . pSQL($code) . "'");

        // Multishop; we have to remove the 'AND' from PS's query...
        $query->from('totcustomfields_input_shop', 'tcf_input_shop');
        $query->where(str_replace('AND', '', Shop::addSqlRestriction(false, 'tcf_input_shop')));
        $query->groupBy('tcf_input.id_input');

        $row = Db::getInstance()->getRow($query);
        if (empty($row)) {
            return false;
        }

        $inputTypes = $this->entityLoader->getEntities();
        foreach ($inputTypes as $type) {
            if ($type['code'] == $row['code_input_type']) {
                return new $type['className']((int) $row['id_input']);
            }
        }

        return false;
    }

    /**
     * Gets a new input object, by type
     *
     * @param string $codeType
     *
     * @return CustomFieldsInput|false
     */
    public function getByType($codeType)
    {
        foreach ($this->entityLoader->getEntities() as $type) {
            if ($codeType == $type['code']) {
                return new $type['className']();
            }
        }

        return false;
    }

    public function isValidTypeCode($codeType)
    {
        foreach ($this->entityLoader->getEntities() as $type) {
            if ($codeType === $type['code']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if an input code is unique among inputs
     *
     * @param string $code
     * @param int $excludedId an id_input to exclude, as we may be trying to update it
     *
     * @return bool
     *
     * @throws \PrestaShopDatabaseException
     */
    public function isUniqueInputCode($code, $excludedId = null)
    {
        $query = new DbQuery();
        $query->select('1');
        $query->from('totcustomfields_input');
        $query->where("code = '" . pSQL($code) . "'");

        if ($excludedId) {
            $query->where('id_input <> ' . (int) $excludedId);
        }
        $res = Db::getInstance()->executeS($query);

        return empty($res);
    }

    /**
     * Returns the number of inputs related to a code_object
     *
     * @param string $codeObject
     *
     * @return int
     */
    public function countInputsFromCodeObject($codeObject)
    {
        if (!$this->objectService->objectExists($codeObject)) {
            return 0;
        }

        $query = new DbQuery();
        $query->select('COUNT(DISTINCT tcf_input.id_input)');
        $query->from('totcustomfields_input', 'tcf_input');
        $query->where("code_object = '" . pSQL($codeObject) . "'");

        // Multishop; we have to remove the 'AND' from PS's query...
        $query->innerJoin('totcustomfields_input_shop', 'tcf_input_shop', 'tcf_input_shop.id_input = tcf_input.id_input');
        $query->where(str_replace('AND', '', Shop::addSqlRestriction(false, 'tcf_input_shop')));

        return (int) Db::getInstance()->getValue($query);
    }

    /**
     * Gets all input related to a code_object
     *
     * @param string $codeObject
     *
     * @return array
     */
    public function getAllInputsFromCodeObject($codeObject)
    {
        if (!$this->objectService->objectExists($codeObject)) {
            return [];
        }

        $query = new DbQuery();
        $query->select('tcf_input.id_input, code_input_type');
        $query->from('totcustomfields_input', 'tcf_input');
        $query->where("code_object = '" . pSQL($codeObject) . "'");

        // Multishop; we have to remove the 'AND' from PS's query...
        $query->innerJoin('totcustomfields_input_shop', 'tcf_input_shop', 'tcf_input_shop.id_input = tcf_input.id_input');
        $query->where(str_replace('AND', '', Shop::addSqlRestriction(false, 'tcf_input_shop')));
        $query->groupBy('tcf_input.id_input');

        $sqlResult = Db::getInstance()->executeS($query);
        if (!$sqlResult) {
            return [];
        }

        $input_types = [];
        foreach ($this->entityLoader->getEntities() as $type) {
            $input_types[$type['code']] = $type;
        }

        $inputs = [];
        foreach ($sqlResult as $row) {
            if (isset($input_types[$row['code_input_type']])) {
                $className = $input_types[$row['code_input_type']]['className'];
                $inputs[] = new $className($row['id_input']);
            }
        }

        return $inputs;
    }

    /**
     * Formats a value from any input type to display it in an AdminController summary
     *
     * @param string $codeInputType the input type associated to the value
     * @param int $idInput the input id
     * @param string $value the value to format
     *
     * @return string
     */
    public function formatInputValueForAdminSummary($codeInputType, $idInput, $value)
    {
        foreach ($this->entityLoader->getEntities() as $type) {
            if ($codeInputType == $type['code']) {
                $typeInstance = new $type['className']($idInput);

                return $typeInstance->formatValueForAdminSummary($idInput, $value);
            }
        }

        return '';
    }

    public function getInputTypes()
    {
        return $this->entityLoader->getEntities();
    }
}
