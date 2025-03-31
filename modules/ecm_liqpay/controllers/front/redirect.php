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
class ecm_liqpayRedirectModuleFrontController extends ModuleFrontController
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
            $myCart = new Cart($id_cart);
            if (!Validate::isLoadedObject($myCart)) {
                $myCart = $this->context->cart;
            }

        } else {
            $myCart = $this->context->cart;
        }
        $carriers_from_bd = Configuration::get('liqpay_shipping');
        $carriers = json_decode(Configuration::get('liqpay_shipping'),true);
        $currency = new Currency($myCart->id_currency);
        $carrier = new Carrier($myCart->id_carrier);
        if (is_array($carriers) && in_array($carrier->id_reference, $carriers)) {
            $amount = $myCart->getOrderTotal(true, Cart::BOTH);
        } else {
            $amount = $myCart->getOrderTotal(true, Cart::BOTH)-$myCart->getOrderTotal(true, Cart::ONLY_SHIPPING);
            //$amount = $myCart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
        }

        if (Configuration::get('liqpay_wrapping')) {
            $amount += $myCart->getOrderTotal(true, Cart::ONLY_WRAPPING);
        }
        $amount = number_format($amount, 2, '.', '');
        $currency = $currency->iso_code == 'RUR' ? 'RUB' : $currency->iso_code;
        $id_cart = $myCart->id;
		$customer = new Customer($myCart->id_customer);
        $details = $this->trans('Оплата замовлення № ', array(), 'Modules.Liqpay.Admin') . $id_cart. $this->trans(' на ', array(), 'Modules.Liqpay.Admin') . $_SERVER['HTTP_HOST'].$this->trans(', замовник: ', array(), 'Modules.Liqpay.Admin').$customer->firstname.' '.@$customer->secondname.' '.$customer->lastname;
        if ($postvalidate = Configuration::get('liqpay_postvalidate')) {
            $order_number = $myCart->id;
        } else {
            if (!($order_number = Order::getOrderByCartId($myCart->id))) {
                $this->module->validateOrder((int) $myCart->id, Configuration::get('PS_OS_LP_Created'), $amount, $this->module->displayName, null, array(), null, false, $myCart->secure_key);
                $order_number = $this->module->currentOrder;
                $details = $this->trans('Оплата замовлення № ', array(), 'Modules.Liqpay.Admin') . $order_number. $this->trans(' на ', array(), 'Modules.Liqpay.Admin') . $_SERVER['HTTP_HOST'].$this->trans(', замовник: ', array(), 'Modules.Liqpay.Admin').$customer->firstname.' '.@$customer->secondname.' '.$customer->lastname;
            }
        }
        $ssl_enable = Configuration::get('PS_SSL_ENABLED');
        $base = (($ssl_enable) ? 'https://' : 'http://');
        $server_url = $base . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__ . 'modules/ecm_liqpay/validation.php?id='.$order_number;
        
		//$result_url = $this->context->link->getModuleLink('ecm_liqpay', 'result', array('order_id' => $order_number), true);
        //$callback_url = $this->context->link->getModuleLink('ecm_liqpay', 'callback', array('order_id' => $order_number), true);
        $liqpay_order_id = $order_number . '-' . uniqid();
		$type = 'buy';
        $version = '3';
        $language = $this->context->language->iso_code;

        $data = base64_encode(
            json_encode(
                array('version' => $version,
                    'public_key' => Tools::getValue('liqpay_id', $this->module->liqpay_merchant_id),
                    'amount' => $amount,
                    'currency' => $currency,
                    'description' => $details,
                    'order_id' => $liqpay_order_id,
                    'type' => $type,
                    'language' => $language,
                    //'server_url' => $server_url, //$callback_url, //
                    //'result_url' => $server_url, //$result_url,
                    'server_url' => $this->context->link->getModuleLink('ecm_liqpay', 'validation', ["id" =>$order_number], true),
                    'result_url' => $this->context->link->getModuleLink('ecm_liqpay', 'success', ["id" =>$order_number, "order_id"=>$liqpay_order_id], true),
                )
            )
        );
        $signature = base64_encode(sha1($this->module->liqpay_merchant_pass . $data . $this->module->liqpay_merchant_pass, 1));
        $this->context->smarty->assign(compact('data', 'signature'));
        $this->setTemplate('module:ecm_liqpay/views/templates/front/redirect.tpl');
    }
}