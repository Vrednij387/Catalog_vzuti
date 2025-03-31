<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2019 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

include_once _PS_MODULE_DIR_.'kldeliverynppro/classes/NovaPoshtaCartClass.php';

use NovaPoshta\Pro\NovaPoshtaCartClass;

class KlDeliveryNPProsaveCartNovaPoshtaModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->ajax = true;

        parent::initContent();

        $encrypt_f = Tools::encrypt('kldeliverynppro/index');
        if (Tools::getValue('nptoken') != Tools::substr($encrypt_f, 0, 10)) {
            die($this->module->l('Invalid token'));
        }

        $this->saveCart();
    }

    public function saveCart()
    {
        if (Tools::getValue('city-js') && Tools::getValue('warehouse-js')) {
            $cartNp = new NovaPoshtaCartClass((int)$this->context->cart->id);
            $cartNp->id_cart = $this->context->cart->id;
            $cartNp->id_customer = $this->context->cart->id_customer;
            $cartNp->city = Tools::getValue('city-js');
            $cartNp->warehouse = Tools::getValue('warehouse-js');

            $cartNp->save();
        }
    }
}
