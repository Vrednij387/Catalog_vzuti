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
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

class ecm_liqpay extends PaymentModule
{
    private $_html = '';
    private $post_errors = [];
    public function __construct()
    {
        $this->name = 'ecm_liqpay';
        $this->tab = 'payments_gateways';
        $this->version = '1.3.1';
        $this->author = 'Elcommerce';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->controllers = array('payment', 'validation');
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        $config = Configuration::getMultiple(array('liqpay_id', 'liqpay_pass'));
        if (isset($config['liqpay_pass'])) {
            $this->liqpay_merchant_pass = $config['liqpay_pass'];
        }
        if (isset($config['liqpay_id'])) {
            $this->liqpay_merchant_id = $config['liqpay_id'];
        }
        parent::__construct();
        $this->displayName = $this->l('Liqpay');
        $this->description = $this->l('Payments with liqpay');
        if (!isset($this->liqpay_merchant_pass) or !isset($this->liqpay_merchant_id)) {
            $this->warning = $this->l('Your liqpay account must be set correctly (specify a password and a unique id merchant');
        }
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

    }

    public function install()
    {
        return (parent::install()
            && $this->registerHook('paymentOptions')
            && $this->_addOS()
        );
    }

    public function uninstall()
    {
        return (parent::uninstall()
            && Configuration::deleteByName('liqpay_id')
            && Configuration::deleteByName('liqpay_pass')
            && Configuration::deleteByName('liqpay_postvalidate')
        );
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitliqpay')) {
            $this->postValidation();
            if (!$this->post_errors) {
                $this->postProcess();
            } else {
                foreach ($this->post_errors as $err) {
                    $this->_html .= $this->displayError($err);
                }
            }

        }
        $this->_html .= $this->renderForm();
        $this->_displayabout();
        return $this->_html;
    }

    public function renderForm() {
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cog',

            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Public key'),
                    'desc' => $this->l('Public key in Liqpay'),
                    'name' => 'liqpay_id',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Private key'),
                    'desc' => $this->l('Private key in Liqpay'),
                    'name' => 'liqpay_pass',
                ),
                 array(
                    'type' => 'switch',
                    'label' => $this->l('Order after payment'),
                    'name' => 'liqpay_postvalidate',
                    'desc' => $this->l('Create order after receive payment notification'),
                    'values' => array(
                        array(
                            'id' => 'liqpay_postvalidate_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'liqpay_postvalidate_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                ),
                array(
                    'type'   => 'select',
                    'multiple' => true,
                    'label'  => $this->l('Shipping included'),
                    'name' => 'liqpay_shipping[]',
                    'selected'=> 'liqpay_shipping',
                    'options' => array(
                        'query' => $query = $this->getCarriers(),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'hint' => $this->l('Payment includes shipping'),
                    'desc' => $this->l('For selected carriers payment includes shipping. (use Ctrl-click)'),
                    'size' => count($query),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Order total with gift wrapping cost'),
                    'name' => 'liqpay_wrapping',
                    'desc' => $this->l('Send order total with gift wrapping cost'),
                    'values' => array(
                        array(
                            'id' => 'liqpay_wrapping_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'liqpay_wrapping_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Answer log'),
                    'name' => 'liqpay_answer_log',
                    'values' => array(
                        array(
                            'id' => 'liqpay_answer_log_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ),
                        array(
                            'id' => 'liqpay_answer_log_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ),
                    ),
                ),
            ),

            'submit' => array(
                'name' => 'submitliqpay',
                'title' => $this->l('Save'),
            ),
        );
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitliqpay';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name .
        '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        return $helper->generateForm($this->fields_form);
    }

    public function getConfigFieldsValues()
    {
        $fields_values = array();
        $fields_values['liqpay_id'] = Configuration::get('liqpay_id');
        $fields_values['liqpay_pass'] = Configuration::get('liqpay_pass');
        $fields_values['liqpay_postvalidate'] = Configuration::get('liqpay_postvalidate');
        $fields_values['liqpay_delivery'] = Configuration::get('liqpay_delivery');
        $fields_values['liqpay_wrapping'] = Configuration::get('liqpay_wrapping');
        $fields_values['liqpay_answer_log'] = Configuration::get('liqpay_answer_log');
        $fields_values['liqpay_shipping[]'] = json_decode(Configuration::get('liqpay_shipping'),true);
        return $fields_values;
    }

    private function postValidation()
    {
        if (Tools::getValue('liqpay_id') && (!Validate::isString(Tools::getValue('liqpay_id')))) {
            $this->post_errors[] = $this->l('Invalid') . ' ' . $this->l('Public key');
        }

        if (Tools::getValue('liqpay_pass') && (!Validate::isString(Tools::getValue('liqpay_pass')))) {
            $this->post_errors[] = $this->l('Invalid') . ' ' . $this->l('Private key');
        }

    }

    private function postProcess()
    {
        Configuration::updateValue('liqpay_id', Tools::getValue('liqpay_id'));
        Configuration::updateValue('liqpay_pass', Tools::getValue('liqpay_pass'));
        Configuration::updateValue('liqpay_postvalidate', Tools::getValue('liqpay_postvalidate'));
        Configuration::updateValue('liqpay_delivery', Tools::getValue('liqpay_delivery'));
        Configuration::updateValue('liqpay_wrapping', Tools::getValue('liqpay_wrapping'));
        Configuration::updateValue('liqpay_answer_log', Tools::getValue('liqpay_answer_log'));
        Configuration::updateValue('liqpay_shipping', json_encode(Tools::getValue('liqpay_shipping')));
        $this->_html .= $this->displayConfirmation($this->l('Settings updated.'));
    }
    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->checkCurrency($params['cart'])) {
            return;
        }
        $this->smarty->assign(array(
            'id_cart' => $params['cart']->id,
            'this_path' => $this->_path,
            'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/',
        ));
        $newOption = new PaymentOption();
        $newOption->setCallToActionText($this->l('Pay with Liqpay'))
            ->setAction($this->context->link->getModuleLink($this->name, 'redirect', array(), true))
            ->setAdditionalInformation($this->fetch('module:ecm_liqpay/views/templates/hook/payment.tpl'))
            ->setModuleName($this->name)
        ;

        $payment_options = [
            $newOption,
        ];

        return $payment_options;
    }

    public function checkCurrency($cart)
    {

        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);
        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }
    public static function validateAnsver($message)
    {
        Logger::addLog('liqpay: ' . $message);
        die($message);
    }

   private function _addStatus($status_name, $name, $template = false, $logable = false, $payd = false) {
        $id_status = Configuration::get($status_name);
        $status = new OrderState($id_status);
        $status->send_email = ($template ? 1 : 0);
        $status->invoice = ($template ? 1 : 0);
        $status->logable = $logable;
        $status->delivery = 0;
        $status->payd = $payd;
        $status->hidden = 0;
        $status->unremovable = true;
        if (!$id_status) {
            $color = sprintf('#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255));
            $status->color = $color;
        }
        $lngs = Language::getLanguages();
        foreach ($lngs as $lng) {
            $status->name[$lng['id_lang']] = $name[$lng['iso_code']];
            if ($template) {
                $status->template[$lng['id_lang']] = $template;
            }
        }
        if (!$id_status) {
            if ($status->add()) {
                Configuration::updateValue($status_name, $status->id);
                return true;
            }
        } else {
            $status->update();
            return true;
        }
        return false;
    }

    private function _addOS() {
        
        $prefix = 'PS_OS_LP_';
        $this->_addStatus($prefix.'Created', [
            'en'=>'LP Payment created',
            'ru'=>'LP Платеж создан',
            'uk'=>'LP Платіж створено',
        ], null, null, false);
        
        $this->_addStatus($prefix.'Waitpayment', [
            'en'=>'LP Waiting payment',
            'ru'=>'LP Платеж ожидает',
            'uk'=>'LP Платіж очікує',
        ], null, null, false);
        
        $this->_addStatus($prefix.'Completed', [
            'en'=>'LP The payment has been completed',
            'ru'=>'LP Платіж завершен',
            'uk'=>'LP Платіж завершено',
        ], null, null, true);
        
        return true;
    }

    private function _displayabout()
    {

        $this->_html .= '
		<div class="panel">
		<div class="panel-heading">
			<i class="icon-envelope"></i> ' . $this->l('Информация') . '
		</div>
		<div id="dev_div">
		<span><b>' . $this->l('Версия') . ':</b> ' . $this->version . '</span><br>
		<span><b>' . $this->l('Разработчик') . ':</b> <a class="link" href="mailto:support@elcommerce.com.ua" target="_blank">Savvato</a>
		<span><b>' . $this->l('Описание') . ':</b> <a class="link" href="http://elcommerce.com.ua" target="_blank">http://elcommerce.com.ua</a><br><br>
		<p style="text-align:center"><a href="http://elcommerce.com.ua/"><img src="http://elcommerce.com.ua/img/m/logo.png" alt="Электронный учет коммерческой деятельности" /></a>
		</div>
		</div>
		';
    }
    
    public function getCarriers()
    {
        $carriers = Carrier::getCarriers($this->context->language->id, true, false, false, null, Carrier::ALL_CARRIERS);
        $cs = array();
        foreach ($carriers as $carrier) {
            $cs[] = ['id'=>$carrier['id_reference'], 'name'=>$carrier['name']];
        }
        return $cs;
    }

}