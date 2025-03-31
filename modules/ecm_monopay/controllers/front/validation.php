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

class ecm_monopayValidationModuleFrontController extends ModuleFrontController
{

    //public $postvalidate;
    public $moduleSettings;
    public function init()
    {
        if (_PS_VERSION_ > "1.7.6") {
            global $kernel;
            if (!$kernel) {
                require_once _PS_ROOT_DIR_ . '/app/AppKernel.php';
                $kernel = new \AppKernel('prod', false);
                $kernel->boot();
            }
        }
        $this->moduleSettings = $this->module->getConfigFieldsValues();
        $monoClient = new \MonoPay\Client($this->module->monopay_merchant_token);
        //отримання публічного ключа (бажано закешувати)
        $publicKey = $this->getPublicKey($monoClient);
        try {
            sleep(5);
            $monoWebhook = new \MonoPay\Webhook($monoClient, $publicKey, $_SERVER['HTTP_X_SIGN']);
            //отримуємо вхідні дані
            $body = file_get_contents('php://input');
            if ($monoWebhook->verify($body)) {
                $this->processMonoCallback($monoClient, $body);
            } else {
                Configuration::deleteByName('monopay_public_api_key');
                $publicKey = $this->getPublicKey($monoClient);
                if ($monoWebhook->verify($body)) {
                    $this->processMonoCallback($monoClient, $body);
                }else{
                    if ($this->moduleSettings['monopay_answer_log']) {
                        $this->module->logger('ValidationCallbackVerifyError', "Verify signature data failed");
                    }
                }
            }
        } catch (Exception $e) {
            if ($this->moduleSettings['monopay_answer_log']) {
                $this->module->logger('ValidationCallbackError', $e->getMessage());
            }
        }
        die;
    }
    public function getPublicKey($monoClient)
    {
        $publicKey = Configuration::get('monopay_public_api_key');
        if (!$publicKey) {
            $publicKey = $monoClient->getPublicKey();
            Configuration::updateValue('monopay_public_api_key', $publicKey);
        }
        return $publicKey;
    }
    public function processMonoCallback($monoClient, $body)
    {
        $data = json_decode($body, true);
        if ($this->moduleSettings['monopay_answer_log']) {
            $this->module->logger('Validation', $body);
        }
        $monoPayment = new \MonoPay\Payment($monoClient);
        $id = $data['reference'];

        if ($data['status'] == ecm_monopay::MONO_PAY_REVERSED) {
            //Возврат
            $id_order = ($this->moduleSettings['monopay_postvalidate'] == 1) ? Order::getOrderByCartId($id) : $id;
            $order = new Order((int) $id_order);
            $order->total_paid_real = 0;
            $order->update();
            $this->module->changeIdOrderState(_PS_OS_REFUND_, $id_order);

        } elseif ($data['status'] == ecm_monopay::MONO_PAY_HOLD || $data['status'] == ecm_monopay::MONO_PAY_SUCCESS) {

            $this->module->processOrderComplete($monoPayment, $data, $id);

        }
    }

}