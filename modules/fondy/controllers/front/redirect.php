<?php
/**
 * 2014-2019 Fondy
 *
 * @author DM
 * @copyright  2014-2019 Fondy
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @version    1.0.0
 */

require_once(dirname(__FILE__) . '../../../fondy.php');
require_once(dirname(__FILE__) . '../../../fondy.cls.php');

if (!defined('_PS_VERSION_')) {
    exit;
}

class FondyRedirectModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();

        $cookie = $this->context->cookie;
        $link = $this->context->link;

        $language = Language::getIsoById((int)$cookie->id_lang);
        $language = (!in_array($language, array('uk', 'en', 'ru', 'lv', 'fr'))) ? '' : $language;

        $payCurrency = Context::getContext()->currency;
        $cart = $this->context->cart;
		$products = $cart->getProducts();

        $fondy = $this->module;
        $total = $cart->getOrderTotal();

        $fondy->validateOrder((int)$cart->id, _PS_OS_PREPARATION_, $total, $fondy->displayName);

        $order_product_fondy = "";
        foreach($products as $key => $prod) {
            $order_product_fondy .= $prod['name'] . " (".$prod['attributes'].") x" . $prod['quantity'];
            //var_dump($prod);
            if ($key != array_key_last($products)) {
                $order_product_fondy .= ", ";
            }
        }
        //var_dump($order_product_fondy);

        $fields = array(
            'order_id' => $fondy->currentOrder . FondyCls::ORDER_SEPARATOR . time(),
            'merchant_id' => $fondy->getOption('merchant'),
            'order_desc' => $this->l('#') . $fondy->currentOrder . " Взуття " . $order_product_fondy,
            'amount' => round($total * 100),
            'currency' => $payCurrency->iso_code,
            'server_callback_url' => $link->getModuleLink('fondy', 'callback'),
            'response_url' => $link->getModuleLink('fondy', 'result'),
            'sender_email' => $this->context->customer->email
        );

        //var_dump($fields);

        if ($language !== '') {
            $fields['lang'] = Tools::strtolower($language);
        }
        $fields['signature'] = FondyCls::getSignature($fields, $fondy->getOption('secret_key'));
        $fields['fondy_url'] = FondyCls::URL;
		
		$extra_fields = array(
			'id_order' => $fondy->currentOrder,
			'currency_iso_code' => $payCurrency->iso_code,
			'total_products' => round($total, 0),
			'payment' => $fondy->displayName,
			'products' => $products,
        );

        $this->context->smarty->assign(array_merge($fields, $extra_fields));
		
		 if (!empty($this->context->cookie->id_cart) && $this->context->cookie->id_cart == $cart->id) 
		 {
            if ($cart->orderExists()) 
			{
                unset($this->context->cookie->id_cart);
				unset($this->context->cookie->supercheckout_perm_address_delivery);
				unset($this->context->cookie->supercheckout_perm_address_invoice);
				unset($this->context->cookie->supercheckout_temp_address_delivery);
				unset($this->context->cookie->supercheckout_temp_address_invoice);
            }
        }

        $this->setTemplate('module:' . $this->module->name . '/views/templates/front/redirect.tpl');
    }
}

