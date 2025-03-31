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
include dirname(__FILE__) . '/../../classes/liqpay.php';
class ecm_liqpaysuccessModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();

        $liqpay = new LiqPay($this->module->liqpay_merchant_id, $this->module->liqpay_merchant_pass);
        $res = $liqpay->api("request", array(
            'action' => 'status',
            'version' => '3',
            'order_id' => Tools::getValue('order_id'),
        )
        );
        $postvalidate = Configuration::get('liqpay_postvalidate');
        if ($res->result == 'error' && $res->err_code == 'payment_not_found') {
            Tools::redirect('index.php?controller=order&step=1');
        } elseif ($res->result == 'ok' && $res->status == 'success') {
            $rest_amount = floatval($res->amount);
            $currency = new Currency(Currency::getIdByIsoCode($res->currency));
            if ($postvalidate == 1) {
                $id_cart_ = explode("-", Tools::getValue('order_id'));
                $cart = new Cart((int) $id_cart_[0]);
                $ordernumber = Order::getOrderByCartId($cart->id);
                if (!$ordernumber) {
                    $this->module->validateOrder($cart->id, Configuration::get('PS_OS_LP_Created'), $rest_amount, $this->module->displayName, null, array(), null, false, $cart->secure_key);
                }

                $ordernumber = Order::getOrderByCartId($cart->id);
                $order = new Order((int) $ordernumber);
                $currency_order = new Currency($order->id_currency);
                $history = new OrderHistory();
                $history->id_order = $ordernumber;
                if ($order->current_state != Configuration::get('PS_OS_LP_Completed')) {
                    $payment = new OrderPayment();
                    $payment->order_reference = $order->reference;
                    $payment->id_currency = $currency->id;
                    $payment->amount = $rest_amount;
                    $payment->conversion_rate = $currency_order->conversion_rate / $currency->conversion_rate;
                    $payment->payment_method = $this->module->displayName;
                    $payment->card_number = $res->sender_card_mask2;
                    $payment->card_brand = $res->sender_card_type . ' ' . $res->sender_card_bank;
                    $payment->card_holder = $res->sender_first_name . ' ' . $res->sender_last_name;
                    $payment->transaction_id = $res->liqpay_order_id;
                    $payment->add();

                    $history->changeIdOrderState(Configuration::get('PS_OS_LP_Completed'), $ordernumber);
                    $history->addWithemail(true);
                }
            } else {
                $ordernumber_ = explode("-", Tools::getValue('order_id'));
                $ordernumber = (int) $ordernumber_[0];
                $order = new Order((int) $ordernumber);
                if (!Validate::isLoadedObject($order)) {
                    ecm_liqpay::validateAnsver($this->module->l('Order does not exist'));
                }

                $currency_order = new Currency($order->id_currency);
                if ($order->current_state != Configuration::get('PS_OS_LP_Completed')) {
                    $order->total_paid_real = $rest_amount;
                    $order->current_state = Configuration::get('PS_OS_LP_Completed');
                    $order->update();
                    $payment = new OrderPayment();
                    $payment->order_reference = $order->reference;
                    $payment->id_currency = $currency->id;
                    $payment->amount = $rest_amount;
                    $payment->conversion_rate = $currency_order->conversion_rate / $currency->conversion_rate;
                    $payment->payment_method = $this->module->displayName;
                    $payment->card_number = $res->sender_card_mask2;
                    $payment->card_brand = $res->sender_card_type . ' ' . $res->sender_card_bank;
                    $payment->card_holder = $res->sender_first_name . ' ' . $res->sender_last_name;
                    $payment->transaction_id = $res->liqpay_order_id;
                    $payment->add();

                    $history = new OrderHistory();
                    $history->id_order = $ordernumber;
                    $history->changeIdOrderState(Configuration::get('PS_OS_LP_Completed'), $ordernumber);
                    $history->addWithemail(true);
                }

            }
            $customer = new Customer((int) $order->id_customer);
            if ($order->current_state == Configuration::get('PS_OS_LP_Completed')) {

                $url = 'index.php?controller=order-confirmation?key=' . $customer->secure_key . '&id_cart=' . $order->id_cart .
                    '&id_module=' . $this->module->id . '&id_order=' . $order->id;
                Tools::redirectLink($url);
            }
            $this->context->smarty->assign('ordernumber', Tools::getValue('order_id'));
            $this->setTemplate('module:ecm_liqpay/views/templates/front/waitingPayment.tpl');

        } else {
            if ($postvalidate == 1) {
                Tools::redirect('index.php?controller=order&step=3');
            } else {
                $ordernumber = Tools::getValue('order_id');
                $this->context->smarty->assign('ordernumber', $ordernumber);
                $this->setTemplate('module:ecm_liqpay/views/templates/front/waitingPayment.tpl');
            }
        }
    }
}