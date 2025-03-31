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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * @param TotCustomFields $module
 *
 * @return mixed
 */
function upgrade_module_2_2_0($module)
{
    $allInputs = getAllInputs();

    $extensions = $module->extensions;
    foreach ($extensions as $extension) {
        $extensionObject = new $extension($module);
        $extensionInstaller = new \TotcustomfieldsClasslib\Install\ExtensionInstaller($module, $extensionObject);
        $extensionInstaller->installObjectModels();
    }

    foreach ($allInputs as $input) {
        if (!isset($input['default_value'])) {
            continue;
        }
        Db::getInstance()->update(
            CustomFieldsInput::$definition['table'] . '_lang',
            [
                'default_value' => $input['default_value'],
            ],
            'id_input = ' . (int) $input['id_input']
        );
    }

    return true;
}

function getAllInputs()
{
    $query = new DbQuery();
    $query->select('*');
    $query->from(CustomFieldsInput::$definition['table']);

    return Db::getInstance()->executeS($query);
}
