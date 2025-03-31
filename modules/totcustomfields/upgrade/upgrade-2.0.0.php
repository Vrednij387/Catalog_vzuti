<?php
/**
 * Copyright since 2022 totcustomfields
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tech@202-ecommerce.com so we can send you a copy immediately.
 *
 * @author    202 ecommerce <tech@202-ecommerce.com>
 * @copyright 2022 totcustomfields
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * @param TotCustomFields $module
 *
 * @return mixed
 *
 * @throws PrestaShopDatabaseException
 * @throws PrestaShopException
 */
function upgrade_module_2_0_0($module)
{
    $result = true;
    // 1. register all available hooks
    if (!empty($module->hooks)) {
        $module->registerHook($module->hooks);
    }

    // 2. Remove all previous tabs
    $tabs = Tab::getCollectionFromModule('totcustomfields');

    /** @var Tab $tab */
    foreach ($tabs as $tab) {
        $tab->delete();
    }

    // 3. install extension
    $extensions = $module->extensions;
    foreach ($extensions as $extension) {
        $extensionObject = new $extension($module);
        $extensionInstaller = new \TotcustomfieldsClasslib\Install\ExtensionInstaller($module, $extensionObject);
        $extensionInstaller->installAdminControllers();
        $extensionInstaller->installObjectModels();
    }

    $columnAdminExists = Db::getInstance()->executeS(
        "SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = '" . _DB_NAME_ . "'
              AND TABLE_NAME = '" . _DB_PREFIX_ . "totcustomfields_display_input'
              and COLUMN_NAME = 'code_admin_display';"
    );

    if (empty($columnAdminExists)) {
        $addCodeDisplay = '
                ALTER TABLE ' . _DB_PREFIX_ . 'totcustomfields_display_input
                ADD COLUMN code_admin_display VARCHAR(255) NOT NULL AFTER code_display;';
        $result &= Db::getInstance()->execute($addCodeDisplay);
    }

    $tables = [
        _DB_PREFIX_ . 'totcustomfields_display_input',
        _DB_PREFIX_ . 'totcustomfields_display_input_native_hooks',
        _DB_PREFIX_ . 'totcustomfields_input',
        _DB_PREFIX_ . 'totcustomfields_input_checkbox_value',
        _DB_PREFIX_ . 'totcustomfields_input_image_value',
        _DB_PREFIX_ . 'totcustomfields_input_select_value',
        _DB_PREFIX_ . 'totcustomfields_input_text_value',
        _DB_PREFIX_ . 'totcustomfields_input_textarea_value',
        _DB_PREFIX_ . 'totcustomfields_input_video_value',
    ];

    foreach ($tables as $table) {
        $indexes = getTableIndexes($table);
        if (!empty($indexes)) {
            foreach ($indexes as $index) {
                try {
                    Db::getInstance()->execute(
                        'DROP INDEX ' . $index['INDEX_NAME'] . '
                         ON ' . $table . ';'
                    );
                } catch (\Exception $e) {
                }
            }
        }
    }

    $sqlIndexCreation = [
        'CREATE INDEX idInput ON ' . _DB_PREFIX_ . 'totcustomfields_display_input (id_input)',
        'CREATE INDEX codeDisplay_active ON ' . _DB_PREFIX_ . 'totcustomfields_display_input (code_display, active)',
        'CREATE INDEX hook_position ON ' . _DB_PREFIX_ . 'totcustomfields_display_input_native_hooks (hook, position)',
        'CREATE INDEX code_object ON ' . _DB_PREFIX_ . 'totcustomfields_input (code_object, active)',
        'CREATE INDEX id_input ON '
        . _DB_PREFIX_ . 'totcustomfields_input_text_value (id_input, id_object, id_lang)',
        'CREATE INDEX id_input ON '
        . _DB_PREFIX_ . 'totcustomfields_input_textarea_value (id_input, id_object, id_lang)',
        'CREATE INDEX id_input ON '
        . _DB_PREFIX_ . 'totcustomfields_input_video_value (id_input, id_object, id_lang)',
        'CREATE INDEX id_input ON '
        . _DB_PREFIX_ . 'totcustomfields_input_image_value (id_input, id_object, id_lang)',
        'CREATE INDEX id_input ON '
        . _DB_PREFIX_ . 'totcustomfields_input_checkbox_value (id_input, id_object, id_lang)',
        'CREATE INDEX id_input ON '
        . _DB_PREFIX_ . 'totcustomfields_input_select_value (id_input, id_object, id_lang)',
    ];

    foreach ($sqlIndexCreation as $sqlReq) {
        try {
            if (!Db::getInstance()->execute($sqlReq)) {
                return false;
            }
        } catch (\Exception $exception) {
        }
    }

    $fillTdiData = '
            UPDATE ' . _DB_PREFIX_ . 'totcustomfields_display_input tdi
            INNER JOIN ' . _DB_PREFIX_ . 'totcustomfields_input ti on tdi.id_input = ti.id_input
            SET code_admin_display = CASE
                                 WHEN ti.code_object = \'product\' THEN \'displayAdminProductsExtra\'
                                 WHEN ti.code_object = \'order\' THEN \'displayAdminOrder\'
                                 WHEN ti.code_object = \'category\' THEN \'displayBackOfficeCategory\'
                                 ELSE \'\'
            END
            WHERE 1;';

    $result &= Db::getInstance()->execute($fillTdiData);

    return $result;
}

function getTableIndexes($table)
{
    return Db::getInstance()->executeS(
        "
        SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS
        WHERE TABLE_NAME = '" . $table . "'
        AND INDEX_NAME != 'PRIMARY'
        AND NON_UNIQUE = 1
        AND INDEX_SCHEMA = '" . _DB_NAME_ . "';"
    );
}
