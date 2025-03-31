<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_36()
{
    $sql_cities = 'SELECT `ref`,`name` FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_cities`';
    $result = Db::getInstance()->executeS($sql_cities);

    $cities = array();
    foreach ($result as $row) {
        $cities[$row['name']] = $row['ref'];
    }

    $sql_warehouses = 'SELECT ref,name FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses`';
    $result = Db::getInstance()->executeS($sql_warehouses);

    $warehouses = array();
    foreach ($result as $row) {
        $warehouses[$row['name']] = $row['ref'];
    }

    $sql = 'SELECT * FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_cart`';
    $result = Db::getInstance()->executeS($sql);

    $success = true;
    foreach ($result as $row) {
        if (isset($cities[$row['city']])) {
            $sql = 'UPDATE `'._DB_PREFIX_.'kl_delivery_nova_poshta_cart` SET `city` = "'.$cities[$row['city']].'" WHERE `city` = "'.$row['city'].'"';
            $result = Db::getInstance()->execute($sql);
            if (!$result) {
                $success = false;
            }
        }
        if (isset($warehouses[$row['warehouse']])) {
            $sql = 'UPDATE `'._DB_PREFIX_.'kl_delivery_nova_poshta_cart` SET `warehouse` = "'.$warehouses[$row['warehouse']].'" WHERE `warehouse` = "'.$row['warehouse'].'"';
            $result = Db::getInstance()->execute($sql);
            if (!$result) {
                $success = false;
            }
        }
    }

    return $success;
}
