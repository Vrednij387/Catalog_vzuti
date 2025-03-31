<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2019 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

class KlDeliveryNPProgetWarehousesModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->ajax = true;

        parent::initContent();

        $encrypt_f = Tools::encrypt('kldeliverynppro/index');
        if (Tools::getValue('nptoken') != Tools::substr($encrypt_f, 0, 10)) {
            die($this->module->l('Invalid token'));
        }

        if (Tools::getValue('city-js')) {
            $this->module->actionGetWarehouses();
        }
    }
}
