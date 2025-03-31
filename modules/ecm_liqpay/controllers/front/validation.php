<?php
/**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

//include dirname(__FILE__) . '/ecm_payparts.php';
//include dirname(__FILE__) . '/../PayParts.php';

class ecm_liqpayValidationModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::postProcess()
     */
    public function init()
    {
        parent::init();
    }
    public function postProcess()
    {

        $merchant_pass = $this->module->liqpay_merchant_pass;
        $postvalidate = Configuration::get('liqpay_postvalidate');
        $response = @$_POST['data'];
        $signature = base64_encode(sha1($merchant_pass . $response . $merchant_pass, 1));
        $output = json_decode(base64_decode($response), true);
        if (Configuration::get('liqpay_answer_log')) {
            $logfile = dirname(__FILE__) . '/log.txt';
            $logger = new FileLogger(0); //0 == debug level, logDebug() won’t work without this.
            $logger->setFilename($logfile);
            $logger->logDebug('');
            $logger->logDebug('REQUEST');
            $logger->logDebug($_REQUEST);
            $logger->logDebug('output');
            $logger->logDebug($output);
            $logger->logDebug('signature');
            $logger->logDebug(Tools::getValue('signature'));
            $logger->logDebug('data');
            $logger->logDebug(Tools::getValue('data'));
        }
        if (@$_POST['signature'] == $signature) {

            $output = json_decode(base64_decode($response), true);
            $errors = '';

            if ($output['status'] == 'success' || $output['status'] == 'sandbox') {

                $rest_amount = floatval($output['amount']);
                $currency = new Currency(Currency::getIdByIsoCode($output['currency']));
                if ($postvalidate == 1) {
                    $id_cart_ = explode("-", $output['order_id']);
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
                        if(!OrderPayment::getByOrderReference($order->reference)){
                        $payment = new OrderPayment();
                        $payment->order_reference = $order->reference;
                        $payment->id_currency = $currency->id;
                        $payment->amount = $rest_amount;
                        $payment->conversion_rate = $currency_order->conversion_rate/$currency->conversion_rate;
                        $payment->payment_method = $this->module->displayName;
                        $payment->card_number = $output['sender_card_mask2'];
                        $payment->card_brand = $output['sender_card_type'].' '.$output['sender_card_bank'];
                        $payment->card_holder = $output['sender_first_name'].' '.$output['sender_last_name'];
                        $payment->transaction_id = $output['liqpay_order_id'];
                        $payment->add();
                    }
                        $history->changeIdOrderState(Configuration::get('PS_OS_LP_Completed'), $ordernumber);
                        $history->addWithemail(true);
                    }
                } else {
                    $ordernumber_ = explode("-", $output['order_id']);
                    $ordernumber = (int) $ordernumber_[0];
                    $order = new Order((int) $ordernumber);
                    if (!Validate::isLoadedObject($order)) {
                        ecm_liqpay::validateAnsver($this->module->l('Order does not exist'));
                    }
                    $currency_order = new Currency($order->id_currency);
                    
                    $carrier = new Carrier($order->id_carrier);
                    $amount = $rest_amount;

                    if ($order->current_state != Configuration::get('PS_OS_LP_Completed')) {
                        if(!OrderPayment::getByOrderReference($order->reference)){
                        $payment = new OrderPayment();
                        $payment->order_reference = $order->reference;
                        $payment->id_currency = $currency->id;
                        $payment->amount = $rest_amount;
                        $payment->conversion_rate = $currency_order->conversion_rate/$currency->conversion_rate;
                        $payment->payment_method = $this->module->displayName;
                        $payment->card_number = $output['sender_card_mask2'];
                        $payment->card_brand = $output['sender_card_type'].' '.$output['sender_card_bank'];
                        $payment->card_holder = $output['sender_first_name'].' '.$output['sender_last_name'];
                        $payment->transaction_id = $output['liqpay_order_id'];
                        $payment->add();
                        }
                        $history = new OrderHistory();
                        $history->id_order = $ordernumber;
                        $history->changeIdOrderState(Configuration::get('PS_OS_LP_Completed'), $ordernumber);
                        $history->addWithemail(true);
                    }

                }

            } elseif ($output['state'] == 'failure') {
                $this->module->validateOrder($id_cart, _PS_OS_ERROR_, 0, $this->module->displayName, $errors . '<br />');
            }
        }
        die();
    }
}
