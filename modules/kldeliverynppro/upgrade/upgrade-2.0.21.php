<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_21(KlDeliveryNPPro $module)
{
    $result = true;
    $result &= Configuration::updateValue('MIN_PRICE_FREE_NP', 10000) &&
        Configuration::updateValue('MAX_PRICE_FREE_NP', 50000);
    $carrier = new Carrier($module->id_carrier);
    $carrier->is_free = false;
    $carrier->shipping_handling = true;
    $carrier->shipping_method = 2;
    $result &= $carrier->save();
    $result &= Db::getInstance()->insert('range_price', array(
        'id_carrier' => (int) $carrier->id,
        'delimiter1' => (float) Configuration::get('MIN_PRICE_FREE_NP'),
        'delimiter2' => (float) Configuration::get('MAX_PRICE_FREE_NP')
    ));
    $range_id = Db::getInstance()->Insert_ID();
    $result &= Db::getInstance()->update('delivery', array(
        'id_range_price' => (int) $range_id,
        'id_range_weight' => 0
    ), 'id_carrier = '. (int) $carrier->id);
    return $result;
}
