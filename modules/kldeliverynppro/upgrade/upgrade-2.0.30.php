<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_30()
{
    return Configuration::updateValue('PAYERTYPE_BACKWARD_NP', 'Sender');
}
