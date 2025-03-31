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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsDisplay;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsObject;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders\DisplayEntityLoader;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsObjectService;
use Db;
use DbQuery;
use Hook;

class CustomFieldsDisplayService
{
    /**
     * @var CustomFieldsObjectService
     */
    protected $objectService;

    /**
     * @var DisplayEntityLoader
     */
    protected $entityLoader;

    /**
     * CustomFieldsDisplayService constructor.
     *
     * @throws \PrestaShopException
     */
    public function __construct()
    {
        $this->objectService = new CustomFieldsObjectService();
        $this->entityLoader = new DisplayEntityLoader();
        $this->entityLoader->loadEntities();
    }

    public function displayExists($code)
    {
        return isset($this->entityLoader->getEntities()[$code]);
    }

    /**
     * @param string $code
     *
     * @return bool|CustomFieldsDisplay
     */
    public function getInstanceFromCode($code)
    {
        if (isset($this->entityLoader->getEntities()[$code])) {
            return $this->entityLoader->getEntities()[$code]['instance'];
        }

        return false;
    }

    /**
     * Deletes all display methods for an input
     *
     * @param int $idInput
     *
     * @throws \PrestaShopDatabaseException
     */
    public function deleteAllInputDisplaysMethods($idInput)
    {
        // Retrieve all display methods
        $query = new DbQuery();
        $query->select('code_display');
        $query->from(CustomFieldsDisplay::DISPLAY_INPUT_TABLE);
        $query->where('id_input = ' . (int) $idInput);
        $query->groupBy('code_display');
        $code_display_array = Db::getInstance()->executeS($query);

        foreach ($code_display_array as $row) {
            /** @var CustomFieldsDisplay|bool $display */
            $display = $this->getInstanceFromCode($row['code_display']);
            if ($display) {
                $display->deleteInputDisplay($idInput);
            }
        }
    }

    /**
     * Saves a a row in the display_input table, and returns the id
     *
     * @param int $idInput
     * @param string $codeDisplay
     * @param string $codeAdminDisplay
     * @param bool $active
     *
     * @return int|false
     *
     * @throws \PrestaShopDatabaseException
     */
    public function insertDisplayInput($idInput, $codeDisplay, $codeAdminDisplay, $active = false)
    {
        $result = Db::getInstance()->insert(CustomFieldsDisplay::DISPLAY_INPUT_TABLE, [
            'id_input' => (int) $idInput,
            'code_display' => pSQL($codeDisplay),
            'active' => $active ? 1 : 0,
            'code_admin_display' => pSQL($codeAdminDisplay),
        ]);

        if ($result) {
            return Db::getInstance()->Insert_ID();
        }

        return false;
    }

    /**
     * Installs SQL for every display
     *
     * @return bool
     */
    public function installAllSQL()
    {
        $sqlDisplay = '
         CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . CustomFieldsDisplay::DISPLAY_INPUT_TABLE . '` (
         `id_display_input` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
         `id_input` INT(11) NOT NULL,
         `code_display` VARCHAR(255) NOT NULL,
         `code_admin_display` VARCHAR(255) NOT NULL,
         `active` TINYINT(1) NOT NULL,
         INDEX `idInput` (`id_input`),
         INDEX `codeDisplay_active` (`code_display`, `active`)
         ) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;
        ';
        if (!Db::getInstance()->execute($sqlDisplay)) {
            return false;
        }

        foreach ($this->entityLoader->getEntities() as $display) {
            /** @var CustomFieldsDisplay $displayInstance */
            $displayInstance = $display['instance'];
            if (!$displayInstance->installSQL()) {
                return false;
            }
        }

        return true;
    }

    public function getAdminDisplayHooksByCodeObject($codeObject)
    {
        $object = $this->objectService->getInstanceFromCode($codeObject);

        return $object->getAvailableAdminDisplayHooks();
    }

    public function isAdminHookAvailable($codeObject, $hook)
    {
        $object = $this->objectService->getInstanceFromCode($codeObject);

        return in_array($hook, array_keys($object->getAvailableAdminDisplayHooks()));
    }

    public function getAdminDisplayHooksTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/displays/objectAdminHooks.tpl';
    }

    public function getAdminDisplayHookByInputId($idInput)
    {
        $query = new DbQuery();
        $query->select('code_admin_display');
        $query->from(CustomFieldsDisplay::DISPLAY_INPUT_TABLE);
        $query->where('id_input = ' . (int) $idInput);
        $query->where('`active` <> 0');

        $codeAdminDisplay = Db::getInstance()->getValue($query);

        return $codeAdminDisplay;
    }

    public function getDisplayMethods()
    {
        return $this->entityLoader->getEntities();
    }
}
