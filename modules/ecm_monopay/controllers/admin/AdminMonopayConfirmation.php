<?php
/**
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

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/../../autoload.php';

class AdminMonopayConfirmationController extends ModuleAdminController
{
    const ORDER_REFUND = 'refund';
    const ORDER_CAPTUREHOLD = 'capture';
    public function setCookie($result, $action, $amount, $currency_sign = null)
    {

        if ($result) {
            if (isset($result['errCode']) && $result['errCode']) {
                $this->context->cookie->__set('redirect_errors', $this->l('err_code: ') . $result['errCode'] . ' ' . @$result['errText']);
            }
            if ($action == self::ORDER_CAPTUREHOLD) {
                if (isset($result['status']) && $result['status'] == 'success') {
                    $this->context->cookie->__set('redirect_success', $this->l('payment in the amount of ') . ' ' . $amount . $currency_sign . ' ' . $this->l(' was successfully confirmed'));
                }
            }
            if ($action == self::ORDER_REFUND) {
                if (isset($result['status']) && $result['status'] == 'success')
                    $this->context->cookie->__set('redirect_success', $this->l('payment was successfully returned'));
                if (isset($result['status']) && $result['status'] == 'processing')
                    $this->context->cookie->__set('redirect_success', $this->l('payment return in process'));
                if (isset($result['status']) && $result['status'] == 'failure')
                    $this->context->cookie->__set('redirect_errors', $this->l('refund is falure. Contact with support Monobank'));
            }
            $this->context->cookie->write();
        }
    }
    public function postProcess()
    {

        $moduleSettings = $this->module->getConfigFieldsValues();
        $monoClient = new \MonoPay\Client($this->module->monopay_merchant_token);
        $monoPayment = new \MonoPay\Payment($monoClient);
        $invoiceId = Tools::getValue('ecmmonopayhold_invoiceid_monopay');
        $id_order = Tools::getValue('ecmmonopayhold_id_order');
        $amount = Tools::getValue('ecmmonopayhold_paid');
        $currency_sign = Tools::getValue('ecmmonopayhold_currency_sign');

        $link = 'index.php?controller=AdminOrders&vieworder&id_order=' . $id_order . '&token=' . Tools::getAdminTokenLite('AdminOrders');
        if (((bool) Tools::getValue('submitMonopayRefund')) == true && Tools::getValue('token') == Tools::getAdminTokenLite('AdminMonopayConfirmation')) {
            $result = $monoPayment->refund($invoiceId);
            if ($moduleSettings['monopay_answer_log']) {
                $this->module->logger('ConfirmationRefund', $result);
            }
            $this->setCookie($result, self::ORDER_REFUND, $amount);
            return Tools::redirectAdmin($link);
        }
        if (((bool) Tools::isSubmit('submitMonopayHoldCompletion')) == true && Tools::getValue('token') == Tools::getAdminTokenLite('AdminMonopayConfirmation')) {
            $result = $monoPayment->captureHold($invoiceId, $amount * 100);
            if ($moduleSettings['monopay_answer_log']) {
                $this->module->logger('ConfirmationCaptureHold', $result);
            }
            $this->setCookie($result, self::ORDER_CAPTUREHOLD, $amount, $currency_sign);
            return Tools::redirectAdmin($link);
        }
        if (((bool) Tools::isSubmit('submitMonopayCreareInvoice')) == true && Tools::getValue('token') == Tools::getAdminTokenLite('AdminMonopayConfirmation')) {
            $id_cart = Tools::getValue('ecmmonopayhold_id_cart');
            $order = new Order($id_order);
            $order->payment = $this->module->displayName;
            $order->module = $this->module->name;
            $order->update();
            $this->module->createInvoice($id_cart, $moduleSettings, 'admin_conf');
            $this->module->changeIdOrderState($this->module->status_created, $id_order);
            return Tools::redirectAdmin($link);
        }
        parent::postProcess();

    }

}