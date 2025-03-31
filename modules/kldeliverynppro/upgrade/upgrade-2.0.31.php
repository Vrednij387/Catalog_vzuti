<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_31(KlDeliveryNPPro $module)
{
    if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
        return $module->registerHook('displayAdminOrderMain');
    }
    return true;
}
