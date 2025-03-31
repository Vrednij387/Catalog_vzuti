<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_28()
{
    $result = true;
    $result &= Configuration::updateValue('COUNT_NP', 1);
    $sql = 'ALTER TABLE `'._DB_PREFIX_.'kl_delivery_nova_poshta_cn` ADD `count` INT NOT NULL AFTER `volume`;';
    $result &= Db::getInstance()->execute($sql);
    return $result;
}
