<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2019 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

class Ccn extends ObjectModel
{
    public $id_order;

    public $id_cn;

    public $city;
    
    public $warehouse;

    public $cod;

    public $name;

    public $surname;
    
    public $phone;

    public $price;

    public $weight;

    public $volume;

    public $count;
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'kl_delivery_nova_poshta_cn',
        'primary' => 'id_order',
        'fields' => array(
            'id_order' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_cn' =>       array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'name' =>        array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'surname' =>     array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'phone' =>       array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'city' =>        array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'warehouse' =>   array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'cod' =>         array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'price' =>       array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'weight' =>      array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'volume' =>      array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
            'count' =>      array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
        )
    );
}
