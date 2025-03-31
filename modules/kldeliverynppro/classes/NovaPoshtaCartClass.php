<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2019 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

namespace NovaPoshta\Pro;

class NovaPoshtaCartClass extends \ObjectModel
{
    public $id_cart;

    public $id_customer;

    public $city;
    
    public $warehouse;
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'kl_delivery_nova_poshta_cart',
        'primary' => 'id_cart',
        'fields' => array(
            'id_cart' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'city' =>        array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'warehouse' =>   array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            
        )
    );
}
