<?php
/*
 * We offer the best and most useful modules PrestаShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @author    Elcommerce <support@elcommece.com.ua>
 * @copyright 2010-2019 Elcommerce TM
 * @license   Comercial
 * @category  PrestaShop
 * @category  Module
 */
require_once __DIR__ . '/../../autoload.php';
class ecm_monopayRedirectModuleFrontController extends ModuleFrontController
{
    public $display_header = true;
    public $display_column_left = true;
    public $display_column_right = true;
    public $display_footer = true;
    public $ssl = true;
    public function initContent()
    {
        parent::initContent();
        if ($id_cart = Tools::getValue('id_cart')) {
            $cart = new Cart($id_cart);
            if (!Validate::isLoadedObject($cart)) {
                $cart = $this->context->cart;
            }
        } else {
            $cart = $this->context->cart;
        }
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        $moduleSettings = $this->module->getConfigFieldsValues();
        $invoice = $this->module->getMonoInvoice((int) $cart->id);
        if ($invoice && isset($invoice['invoice']) && isset($invoice['invoice_date_add']) && $this->module->isWithinInterval($invoice['invoice_date_add'], date('Y-m-d H:i:s'), $moduleSettings['monopay_ilt'])) {
            Tools::redirectLink(ecm_monopay::MONO_PAY_URL . $invoice['invoice']);
        } else {
            $invoice = $this->module->createInvoice($cart->id, $moduleSettings, 'redirect');
            if (isset($invoice['pageUrl']) && isset($invoice['invoiceId'])) {
                Tools::redirectLink($invoice['pageUrl']);
            }
        }
        exit();
    }
    protected function l($string, $specific = false, $class = null, $addslashes = false, $htmlentities = true)
    {
        if (_PS_VERSION_ >= '1.7') {
            return Context::getContext()->getTranslator()->trans($string);
        } else {
            return parent::l($string, $class, $addslashes, $htmlentities);
        }
    }
}