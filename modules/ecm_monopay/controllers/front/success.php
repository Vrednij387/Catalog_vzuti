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
class ecm_monopaysuccessModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $postvalidate;
    const STATUS_FAILURE = 'failure';
    const STATUS_EXPIRED = 'expired';
    private function redirectIfErrors($failureReason)
    {
        if ($this->postvalidate == 1) {
            Tools::redirect('index.php?controller=order&step=3');
        } else {
            $ordernumber = Tools::getValue('id');
            $this->context->smarty->assign([
                'ordernumber'=>$ordernumber,
                'failure_reason'=>$failureReason,
            ]);
            $this->setTemplate('module:ecm_monopay/views/templates/front/waitingPayment.tpl');
        }
    }
    public function initContent()
    {
        parent::initContent();

        $moduleSettings = $this->module->getConfigFieldsValues();
        $id = Tools::getValue('id');
        $this->postvalidate = $moduleSettings['monopay_postvalidate'];
        $entity = ($moduleSettings['monopay_postvalidate']) ? 'cart' : 'order';
        $invoice = $this->module->getMonoInvoice($id, $entity);
        if ($invoice) {
            $monoClient = new \MonoPay\Client($this->module->monopay_merchant_token);
            $monoPayment = new \MonoPay\Payment($monoClient);
            $result = $monoPayment->info($invoice['invoice']);
            if ($moduleSettings['monopay_answer_log']) {
                $this->module->logger('success', $result);
            }
            if ($result['status'] == self::STATUS_FAILURE || $result['status'] == self::STATUS_EXPIRED) {
                $this->redirectIfErrors($result['failureReason']);
            } elseif ($result['status'] == ecm_monopay::MONO_PAY_HOLD || $result['status'] == ecm_monopay::MONO_PAY_SUCCESS) {

                $orderData = $this->module->processOrderComplete($monoPayment, $result, $id);

                if ($result['status'] == 'hold' || $result['status'] == 'success') {
                    Tools::redirect(
                        $this->context->link->getPageLink(
                            'order-confirmation',
                            true,
                            (int) $this->context->language->id,
                            [
                                'id_cart' => (int) $orderData['id_cart'],
                                'id_module' => (int) $orderData['id_module'],
                                'id_order' => (int) $orderData['id_order'],
                                'key' => $orderData['secure_key'],
                            ]
                        )
                    );
                }
            }
        } else {
            $this->redirectIfErrors($this->module->l('No Invoice created', 'success'));
        }
    }
}