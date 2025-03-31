<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2019 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once _PS_MODULE_DIR_.'kldeliverynppro/classes/NovaPoshtaCartClass.php';
include_once _PS_MODULE_DIR_.'kldeliverynppro/classes/Ccn.php';
include_once _PS_MODULE_DIR_.'kldeliverynppro/src/NovaPoshtaApi2.php';

use NovaPoshta\Pro\NovaPoshtaCartClass;
use NovaPoshta\Pro\NovaPoshtaApi2;

class KlDeliveryNPPro extends CarrierModule
{
    private $templateFile;
    private $payerType;
    private $paymentMethod;

    const PREFIX = 'kldeliverynppro_';
    const SKIP_TYPES = array('95dc212d-479c-4ffb-a8ab-8c1b9073d0bc', 'f9316480-5f2d-425d-bc2c-ac7cd29decf0');

    public function __construct()
    {
        $this->name = 'kldeliverynppro';
        $this->tab = 'shipping_logistics';
        $this->version = '2.0.41';
        $this->author = 'Klookva Antikov';
        $this->bootstrap = true;

        parent::__construct();

        $sql = 'SELECT id_carrier 
                FROM `'._DB_PREFIX_.'carrier`
                WHERE deleted = 0 AND id_reference = '.(int)Configuration::get(self::PREFIX . 'novaposhta');
        $this->id_carrier = Db::getInstance()->getValue($sql);
        $this->payerType = array(
            array('id' => 'Sender', 'name' => $this->l('Sender')),
            array('id' => 'Recipient', 'name' => $this->l('Recipient')),
            array('id' => 'ThirdPerson', 'name' => $this->l('Third person'))
        );
        $this->paymentMethod = array(
            array('id' => 'Cash', 'name' => $this->l('Cash')),
            array('id' => 'NonCash', 'name' => $this->l('Non cash'))
        );
        $this->displayName = $this->l('Nova Poshta Shipping - Pro');
        $this->description = $this->l('Shipping - Nova Post - pro version.');
        $this->templateFile = 'module:kldeliverynppro/views/templates/hook/kldeliverynppro.tpl';
        $this->module_key = 'b1220798c42e3fef18f3c3f74b58a182';
    }

    public function install()
    {
        parent::install();
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            $this->registerHook('displayCarrierExtraContent');
        } else {
            $this->registerHook('displayCarrierList');
        }
        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
            $this->registerHook('displayAdminOrderMain');
        } else {
            $this->registerHook('displayAdminOrderTabOrder');
        }
        $p = 'AdminKlookvaNovaPoshtaParant';
        return $this->installDB()
            && $this->installModuleTab($p, 'Nova Poshta - Pro')
            && $this->installModuleTab('AdminKlookvaNovaPoshta', 'Nova Poshta - Pro', $p)
            && $this->registerHook('actionOrderStatusPostUpdate')
            && $this->registerHook('displayNPTracker')
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('header')
            && $this->registerHook('actionValidateOrder')
            && Configuration::updateValue('VOLUME_NP', 0.002)
            && Configuration::updateValue('COUNT_NP', 1)
            && Configuration::updateValue('MIN_PRICE_FREE_NP', 10000)
            && Configuration::updateValue('MAX_PRICE_FREE_NP', 50000)
            && Configuration::updateValue('CHANGE_REQUIRED_NP', 1)
            && Configuration::updateValue('AFTERPAYMENT_ON_GOODS_COST_NP', 0)
            && Configuration::updateValue('PAYERTYPE_NP', 'Sender')
            && Configuration::updateValue('PAYERTYPE_BACKWARD_NP', 'Sender')
            && Configuration::updateValue('PAYMENTMETHOD_NP', 'Cash')
            && $this->createCarriers()
        ;
    }

    public function actionGetWarehouses()
    {
        if (Tools::getValue('city-js') && Tools::getValue('id_lang')) {
            $city = Tools::getValue('city-js');
            $id_lang = Tools::getValue('id_lang');
            $count_click = Tools::getValue('count_click');

            $warehouses = $this->getWarehouses($id_lang, $city);

            if (empty($warehouses)) {
                $warehouses = $this->getWarehouses(false, $city);
            }
            
            $this->buildSelectWithOptions($warehouses);

            if ($count_click === 0) {
                $this->processPostAddress();
            }
        }
    }

    public function buildSelectWithOptions(array $warehouses)
    {
        $this->context->smarty->assign('warehouses', $warehouses);

        echo $this->context->smarty->fetch(_PS_MODULE_DIR_.'kldeliverynppro/views/templates/front/warehouseSelect.tpl');
    }

    protected function installModuleTab($className, $tabName, $tabParentName = false)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }
        if ($tabParentName) {
            $tab->id_parent = (int)Tab::getIdFromClassName($tabParentName);
        } else {
            $tab->id_parent = 0;
        }
        $tab->module = $this->name;

        return $tab->add();
    }

    protected function uninstallModuleTab($tabClass)
    {
        $id_tab = (int) Tab::getIdFromClassName($tabClass);

        if ($id_tab) {
            $tab = new Tab($id_tab);
            if ($tab->delete()) {
                return true;
            }
        }
        return false;
    }

    public function uninstall()
    {
        return $this->uninstallDB() &&
            $this->uninstallModuleTab('AdminKlookvaNovaPoshtaParant') &&
            $this->uninstallModuleTab('AdminKlookvaNovaPoshta') &&
            Configuration::deleteByName('API_KEY_NP') &&
            Configuration::deleteByName('CHANGE_REQUIRED_NP') &&
            Configuration::deleteByName('AFTERPAYMENT_ON_GOODS_COST_NP') &&
            Configuration::deleteByName('STATUSES_NP') &&
            Configuration::deleteByName('FIRSTNAME_ADMIN_NP') &&
            Configuration::deleteByName('MIDDLENAME_ADMIN_NP') &&
            Configuration::deleteByName('LASTNAME_ADMIN_NP') &&
            Configuration::deleteByName('PHONE_ADMIN_NP') &&
            Configuration::deleteByName('CITY_ADMIN_NP') &&
            Configuration::deleteByName('REGION_ADMIN_NP') &&
            Configuration::deleteByName('WAREHOUSE_ADMIN_NP') &&
            Configuration::deleteByName('VOLUME_NP') &&
            Configuration::deleteByName('COUNT_NP') &&
            Configuration::deleteByName('PAYERTYPE_NP') &&
            Configuration::deleteByName('PAYERTYPE_BACKWARD_NP') &&
            Configuration::deleteByName('PAYMENTMETHOD_NP') &&
            Configuration::deleteByName('MIN_PRICE_FREE_NP') &&
            Configuration::deleteByName('MAX_PRICE_FREE_NP') &&
            $this->deleteCarriers() &&
            parent::uninstall();
    }

    protected function createCarriers()
    {

        $carrier = new Carrier();
        $carrier->name = $this->l('Nova Poshta Shipping - Pro');
        $carrier->active = true;
        $carrier->deleted = 0;
        $carrier->shipping_handling = false;
        $carrier->range_behavior = 0;
        $carrier->delay[Configuration::get('PS_LANG_DEFAULT')] = $this->l('Nova Poshta Shipping - Pro');
        $carrier->shipping_external = true;
        $carrier->is_module = true;
        $carrier->external_module_name = $this->name;
        $carrier->need_range = true;
        $carrier->is_free = false;
        $carrier->shipping_handling = true;
        $carrier->shipping_method = 2;
        if ($carrier->add()) {
            $groupArr = new Group();
            $groups = $groupArr->getGroups((int)Configuration::get('PS_LANG_DEFAULT'));
            
            foreach ($groups as $group) {
                Db::getInstance()->insert('carrier_group', array(
                    'id_carrier' => (int) $carrier->id,
                    'id_group' => (int) $group['id_group']
                ));
            }

            Db::getInstance()->insert('range_price', array(
                'id_carrier' => (int) $carrier->id,
                'delimiter1' => (float) Configuration::get('MIN_PRICE_FREE_NP'),
                'delimiter2' => (float) Configuration::get('MAX_PRICE_FREE_NP')
            ));
            $range_id = Db::getInstance()->Insert_ID();

            $zones = Zone::getZones(true);
            foreach ($zones as $z) {
                $carrier->addZone($z['id_zone']);
                Db::getInstance()->insert('delivery', array(
                    'id_carrier' => (int) $carrier->id,
                    'id_zone' => (int) $z['id_zone'],
                    'id_range_price' => (int) $range_id,
                    'id_range_weight' => 0,
                    'price' => 0
                ));
            }

            copy(dirname(__FILE__) . '/views/img/np.png', _PS_SHIP_IMG_DIR_ . '/' . (int) $carrier->id . '.jpg');

            Configuration::updateValue(self::PREFIX . 'novaposhta', $carrier->id);
            Configuration::updateValue(self::PREFIX . 'novaposhta' . '_reference', $carrier->id);
        }
        

        return true;
    }

    protected function deleteCarriers()
    {
        $tmp_carrier_id = Configuration::get(self::PREFIX . 'novaposhta');
        $sql = 'SELECT id_carrier 
                FROM `'._DB_PREFIX_.'carrier`
                WHERE id_reference = '.(int)$tmp_carrier_id;
        $id_carriers = Db::getInstance()->executeS($sql);
        foreach ($id_carriers as $id_carrier) {
            $carrier = new Carrier((int)$id_carrier['id_carrier']);
            $zones = Zone::getZones(true);
            foreach ($zones as $z) {
                $carrier->deleteZone($z['id_zone']);
            }
            Db::getInstance()->delete('delivery', 'id_carrier = '.(int) $carrier->id);
            Db::getInstance()->delete('range_price', 'id_carrier = '.(int) $carrier->id);
            Db::getInstance()->delete('carrier_group', 'id_carrier = '.(int) $carrier->id);
            Db::getInstance()->delete('carrier_tax_rules_group_shop', 'id_carrier = '.(int) $carrier->id);
            $carrier->delete();
        }

        return true;
    }

    public function installDB()
    {
        $return = true;
        $return &= Db::getInstance()->execute(
            '
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_cities` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `city_id` INT(10) UNSIGNED NOT NULL,
                `region_id` varchar(255) NOT NULL,
                `ref` varchar(255) NOT NULL,
                `id_lang` INT(10) UNSIGNED NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
        );

        $return &= Db::getInstance()->execute(
            '
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `ref` varchar(255) NOT NULL,
                `id_lang` INT(10) UNSIGNED NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                `city` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
        );

        $return &= Db::getInstance()->execute(
            '
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses_tmp` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `ref` varchar(255) NOT NULL,
                `id_lang` INT(10) UNSIGNED NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                `city` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
        );

        $return &= Db::getInstance()->execute(
            '
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_cart` (
                `id_cart` INT(10) UNSIGNED NOT NULL,
                `id_customer` INT(10) UNSIGNED NOT NULL,
                `city` VARCHAR(255) NOT NULL,
                `warehouse` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id_cart`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
        );

        $return &= Db::getInstance()->execute(
            '
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_cn` (
                `id_order` INT(10) UNSIGNED NOT NULL,
                `id_cn` varchar(255) DEFAULT NULL,
                `name` varchar(255) NOT NULL,
                `surname` varchar(255) NOT NULL,
                `phone` varchar(255) NOT NULL,
                `city` varchar(255) NOT NULL,
                `warehouse` varchar(255) NOT NULL,
                `cod` tinyint(1) NOT NULL,
                `price` float NOT NULL,
                `weight` float NOT NULL,
                `volume` float NOT NULL,
                `count` int NOT NULL DEFAULT \'1\',
                PRIMARY KEY (`id_order`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;'
        );

        return $return;
    }

    public function uninstallDB()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_cities`') &&
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses`') &&
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses_tmp`') &&
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_cart`') &&
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'kl_delivery_nova_poshta_cn`');
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
        return 0;
        $total = $params->getOrderTotal(true, Cart::ONLY_PRODUCTS);
        if ((
                $total >= Configuration::get('MIN_PRICE_FREE_NP') &&
                $total <= Configuration::get('MAX_PRICE_FREE_NP')
            ) ||
            (
                Configuration::get('PAYERTYPE_NP') == 'Sender' &&
                Configuration::get('PAYERTYPE_BACKWARD_NP') == 'Sender'
            )
        ) {
            return 0;
        }
        $cartNp = new NovaPoshtaCartClass((int)$params->id);
        if ($cartNp->id_cart) {
            $city = $cartNp->city;
        } else {
            $address = new Address($params->id_address_delivery);
            $city = $address->city;
        }
        if (!$city) {
            return $shipping_cost;
        }
        $weight = $params->getTotalWeight();
        if ($weight < 0.1) {
            $weight = 0.1;
        }
        $np = $this->getNPApi2();
        $sender = $np->getCity(Configuration::get('CITY_ADMIN_NP'));
        if (empty($sender) || !$sender['success'] || empty($sender['data']) || !isset($sender['data'][0]['Ref'])) {
            return $shipping_cost;
        }
        $recept = $np->getCity($city);
        if (empty($recept) || !$recept['success'] || empty($recept['data']) || !isset($recept['data'][0]['Ref'])) {
            return $shipping_cost;
        }
        $cost = $np->getDocumentPrice(
            $sender['data'][0]['Ref'],
            $recept['data'][0]['Ref'],
            'WarehouseWarehouse',
            $weight,
            (int)$total,
            true
        );
        if (empty($cost) || !$cost['success'] || empty($cost['data']) || !isset($cost['data'][0]['Cost'])) {
            return $shipping_cost;
        }
        $all_cost = 0;
        if (Configuration::get('PAYERTYPE_NP') != 'Sender') {
            $all_cost += $cost['data'][0]['Cost'];
        }
        if (Configuration::get('PAYERTYPE_BACKWARD_NP') != 'Sender') {
            $all_cost += $cost['data'][0]['CostRedelivery'];
        }
        return $all_cost;
    }

    public function getOrderShippingCostExternal($params = array())
    {
        return $this->getOrderShippingCost($params, 0);
    }

    public function getContent()
    {
        $html = '';
        $name = $this->l('The command to add to the cron city updates:');
        $html .= $this->adminDisplayInformation($name.'php '.dirname(__FILE__).'/src/NovaPoshtaCli.php city');
        $name = $this->l('The command to add to the cron warehouse updates:');
        $html .= $this->adminDisplayInformation($name.'php '.dirname(__FILE__).'/src/NovaPoshtaCli.php warehouse');
        if (Tools::getValue('error_message')) {
            $html .= $this->displayError($this->l(json_decode(Tools::getValue('error_message'))));
        }
        $redirect_url = AdminController::$currentIndex.'&configure='.$this->name.'&token=';
        $redirect_url .= Tools::getAdminTokenLite('AdminModules');

        if (Tools::getValue('updateCities')) {
            if (!$this->updateCities()) {
                $np = $this->getNPApi2();
                $cities = $np->getCities();
                $html = $this->displayError(implode(',', $cities['errors']));
            } else {
                $html = $this->displayConfirmation($this->l('Cities updated'));
            }
        }
        if (Tools::getValue('updateWarehouses')) {
            if (!$this->updateWarehouses()) {
                $np = $this->getNPApi2();
                $warehouses = $np->getAllWarehouses();
                $html = $this->displayError(implode(',', $warehouses['errors']));
            } else {
                $html = $this->displayConfirmation($this->l('Warehouses updated'));
            }
        }
        
        if (Tools::isSubmit('savekldeliverynppro')) {
            $np = $this->getNPApi2();
            $area = false;
            if (Tools::getValue('city-js')) {
                $area_ref = $np->getCity(Tools::getValue('city-js'))['data'][0]['Area'];
                $area = $np->getArea('', $area_ref)['data'][0]['Area'];
            }
            Configuration::updateValue('API_KEY_NP', Tools::getValue('apikey'));
            if (Tools::getValue('statuses')) {
                Configuration::updateValue('STATUSES_NP', implode(',', Tools::getValue('statuses')));
            } else {
                Configuration::updateValue('STATUSES_NP', false);
            }
            $count = (int)Tools::getValue('count');
            if ($count < 1) {
                $count = 1;
            }
            Configuration::updateValue('FIRSTNAME_ADMIN_NP', Tools::getValue('firstname'));
            Configuration::updateValue('MIDDLENAME_ADMIN_NP', Tools::getValue('middlename'));
            Configuration::updateValue('LASTNAME_ADMIN_NP', Tools::getValue('lastname'));
            Configuration::updateValue('PHONE_ADMIN_NP', Tools::getValue('phone'));
            Configuration::updateValue('CITY_ADMIN_NP', Tools::getValue('city-js'));
            Configuration::updateValue('REGION_ADMIN_NP', $area);
            Configuration::updateValue('WAREHOUSE_ADMIN_NP', Tools::getValue('warehouse-js'));
            Configuration::updateValue('VOLUME_NP', Tools::getValue('volume'));
            Configuration::updateValue('COUNT_NP', $count);
            Configuration::updateValue('CHANGE_REQUIRED_NP', Tools::getValue('change_required'));
            Configuration::updateValue('AFTERPAYMENT_ON_GOODS_COST_NP', Tools::getValue('afterpayment_on_goods_cost'));
            Configuration::updateValue('PAYERTYPE_NP', Tools::getValue('payer-type'));
            Configuration::updateValue('PAYERTYPE_BACKWARD_NP', Tools::getValue('payer-type-backward'));
            Configuration::updateValue('PAYMENTMETHOD_NP', Tools::getValue('payment-method'));
            Configuration::updateValue('MIN_PRICE_FREE_NP', Tools::getValue('min-price'));
            Configuration::updateValue('MAX_PRICE_FREE_NP', Tools::getValue('max-price'));
            
            $html = $this->displayConfirmation($this->l('Saved settings'));
        }

        if (Configuration::get('FIRSTNAME_ADMIN_NP') &&
            Configuration::get('MIDDLENAME_ADMIN_NP') &&
            Configuration::get('LASTNAME_ADMIN_NP') &&
            Configuration::get('CITY_ADMIN_NP') &&
            Configuration::get('REGION_ADMIN_NP')
        ) {
            $names = $this->getAllAvailibleCounterpartyIfDontMatchYou(
                Configuration::get('CITY_ADMIN_NP'),
                Configuration::get('REGION_ADMIN_NP'),
                Configuration::get('FIRSTNAME_ADMIN_NP'),
                Configuration::get('LASTNAME_ADMIN_NP'),
                Configuration::get('MIDDLENAME_ADMIN_NP')
            );
            if (!empty($names)) {
                $text = $this->l('There is no such counterparty').
                "<br>".$this->l('List of available counterparties:')."<br>";
                $html .= $this->displayError($text.implode("<br><br>", $names));
            }
        }

        return $html . $this->renderForm();
    }

    protected function renderForm()
    {
        $default_lang = (int)$this->context->language->id;
        $c_a = Configuration::get('CITY_ADMIN_NP');
        $warehouse_arr = ($c_a ? $c_a : $this->l('Default'));
        if ($this->isAfterpaymentOnGoodsCostAvailable()){
            $ap_on_goods_cost = array(
                'type' => 'switch',
                'label' => $this->l('Add Control of payment option'),
                'name' => 'afterpayment_on_goods_cost',
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'afterpayment_on_goods_cost_on',
                        'value' => 1,
                        'label' => $this->trans('Enabled')
                    ),
                    array(
                        'id' => 'afterpayment_on_goods_cost_off',
                        'value' => 0,
                        'label' => $this->trans('Disabled')
                    )
                )
            );
        } else{
            $ap_on_goods_cost = array(
                'type' => 'switch',
                'label' => $this->l('Add "Control of payment" option'),
                'name' => 'afterpayment_on_goods_cost',
                'is_bool' => true,
                'disabled' => true,
                'desc' => $this->l('Not available for you Nova Poshta Account - please sign agreement with NP'),
                'values' => array(
                    array(
                        'id' => 'afterpayment_on_goods_cost_on',
                        'value' => 1,
                        'label' => $this->trans('Enabled')
                    ),
                    array(
                        'id' => 'afterpayment_on_goods_cost_off',
                        'value' => 0,
                        'label' => $this->trans('Disabled')
                    )
                )
            );
        }
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Klookva Nova Poshta - Pro'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Nova Poshta API Key'),
                    'name' => 'apikey',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Sender Firstname'),
                    'name' => 'firstname',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Sender Middlename'),
                    'name' => 'middlename',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Sender Lastname'),
                    'name' => 'lastname',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Sender Phone'),
                    'name' => 'phone',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('City for CCN'),
                    'desc' => $this->l('sender’s warehouse data'),
                    'name' => 'city-js',
                    'id' => 'js-cities',
                    'class' => 'input-lg',
                    'onchange' => 'getWarehouses(this)',
                    'options' => array(
                        'query' => $this->getCities($this->context->language->id),
                        'id' => 'name',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Warehouse for CCN'),
                    'desc' => $this->l('sender’s warehouse data'),
                    'name' => 'warehouse-js',
                    'id' => 'js-warehouses',
                    'class' => 'input-lg',
                    'options' => array(
                        'query' => $this->getWarehouses(
                            $this->context->language->id,
                            $warehouse_arr
                        ),
                        'id' => 'name',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Status on CCN creation'),
                    'name' => 'statuses[]',
                    'multiple' => true,
                    'class' => 'input-lg',
                    'options' => array(
                        'query' => OrderState::getOrderStates($this->context->language->id),
                        'id' => 'id_order_state',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Payer delivery'),
                    'name' => 'payer-type',
                    'class' => 'input-lg',
                    'options' => array(
                        'query' => $this->payerType,
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Payer backward delivery'),
                    'name' => 'payer-type-backward',
                    'class' => 'input-lg',
                    'options' => array(
                        'query' => $this->payerType,
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Payment method'),
                    'name' => 'payment-method',
                    'class' => 'input-lg',
                    'options' => array(
                        'query' => $this->paymentMethod,
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                $ap_on_goods_cost,
                array(
                    'type' => 'text',
                    'label' => $this->l('Volume'),
                    'desc' => $this->l('default value'),
                    'name' => 'volume',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Seats amount'),
                    'desc' => $this->l('default value'),
                    'name' => 'count',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Min price'),
                    'desc' => $this->l('for free shipping'),
                    'name' => 'min-price',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Max price'),
                    'desc' => $this->l('for free shipping'),
                    'name' => 'max-price',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Make city and warehouse entry fields required?'),
                    'name' => 'change_required',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'change_required_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled')
                        ),
                        array(
                            'id' => 'change_required_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled')
                        )
                    )
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );
        $url = AdminController::$currentIndex.'&configure='.$this->name.'&token=';
        $this->fields_form[0]['form']['buttons'] = array(
            array(
                'href' => $url.Tools::getAdminTokenLite('AdminModules').'&updateCities=1',
                'title' => $this->l('Update city'),
                'icon' => 'process-icon-refresh'
            ),
            array(
                'href' => $url.Tools::getAdminTokenLite('AdminModules').'&updateWarehouses=1',
                'title' => $this->l('Update warehouse'),
                'icon' => 'process-icon-refresh'
            )
        );
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'kldeliverynppro';
        $helper->identifier = $this->identifier;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'savekldeliverynppro';
        $helper->fields_value['apikey'] = Configuration::get('API_KEY_NP');
        $helper->fields_value['firstname'] = Configuration::get('FIRSTNAME_ADMIN_NP');
        $helper->fields_value['middlename'] = Configuration::get('MIDDLENAME_ADMIN_NP');
        $helper->fields_value['lastname'] = Configuration::get('LASTNAME_ADMIN_NP');
        $helper->fields_value['phone'] = Configuration::get('PHONE_ADMIN_NP');
        $helper->fields_value['city-js'] = Configuration::get('CITY_ADMIN_NP');
        $helper->fields_value['warehouse-js'] = Configuration::get('WAREHOUSE_ADMIN_NP');
        $helper->fields_value['statuses[]'] = explode(',', Configuration::get('STATUSES_NP'));
        $helper->fields_value['volume'] = Configuration::get('VOLUME_NP');
        $helper->fields_value['count'] = Configuration::get('COUNT_NP');
        $helper->fields_value['change_required'] = Configuration::get('CHANGE_REQUIRED_NP');
        $helper->fields_value['afterpayment_on_goods_cost'] = Configuration::get('AFTERPAYMENT_ON_GOODS_COST_NP');
        $helper->fields_value['payer-type'] = Configuration::get('PAYERTYPE_NP');
        $helper->fields_value['payer-type-backward'] = Configuration::get('PAYERTYPE_BACKWARD_NP');
        $helper->fields_value['payment-method'] = Configuration::get('PAYMENTMETHOD_NP');
        $helper->fields_value['min-price'] = Configuration::get('MIN_PRICE_FREE_NP');
        $helper->fields_value['max-price'] = Configuration::get('MAX_PRICE_FREE_NP');
        
        return $helper->generateForm($this->fields_form);
    }

    public function hookDisplayNPTracker($params)
    {
        $cart = Order::getCartIdStatic($params['id_order']);
        $cartNp = new NovaPoshtaCartClass((int)$cart);
        if ($cart == $cartNp->id_cart) {
            return $this->display(__FILE__, 'views/templates/hook/button.tpl');
        } else {
            return '';
        }
    }

    public function hookActionValidateOrder($params)
    {
        $order = $params['order'];
		$carrier = new Carrier($order->id_carrier);
		$cartNp = new NovaPoshtaCartClass((int)$order->id_cart);
		if($carrier->external_module_name === $this->name)
		{
			if ($order->id_cart == $cartNp->id_cart) {
				$address = new Address($order->id_address_delivery);
				//$address->id_country = Customer::getCurrentCountry($this->context->customer->id);
				$address->alias = $this->l('Delivery address for Nova Poshta');
				//$address->firstname = $this->context->customer->firstname;
				//$address->lastname = $this->context->customer->lastname;
				$address->address1 = $this->getWarehouseByRef($cartNp->warehouse, $order->id_lang);
				$address->city = $this->getCityByRef($cartNp->city, $order->id_lang);
				//$address->city = $cartNp->city;
				//$address->other = $cartNp->warehouse;
				//$address->phone = $this->getPhoneByAddress($order->id_address_delivery, $order->id_address_invoice);
				//$address->phone_mobile = $address->phone;
				if ($address->save()) {
					//$order->id_address_delivery = $address->id;
					//$order->save();
				}
			}
		}
		else
		{
			//$cartNp->delete();
		}
    }

    public function getPhoneByAddress($id_address_delivery, $id_address_invoice = false)
    {
        $temp_a = new Address($id_address_delivery);
        $p = (isset($temp_a->phone) && $temp_a->phone != '' ? $temp_a->phone : $temp_a->phone_mobile);
        if ($id_address_invoice && (!isset($p) || $p == '')) {
            $temp_a = new Address($id_address_invoice);
            $p = (isset($temp_a->phone) && $temp_a->phone != '' ? $temp_a->phone : $temp_a->phone_mobile);
        }

        return $p;
    }

    public function hookDisplayAdminOrderTabOrder($params)
    {
        return $this->viewOrderSettings((int)Tools::getValue('id_order'));
    }

    public function hookDisplayAdminOrderMain($params)
    {
        return $this->viewOrderSettings((int)Tools::getValue('id_order'));
    }

    protected function viewOrderSettings($id_order)
    {
        $order = new Order($id_order);
        $cart = Order::getCartIdStatic($id_order);

        $cartNp = new NovaPoshtaCartClass((int)$cart);

        if ($cart == $cartNp->id_cart) {
            $ccn = new Ccn($id_order);

            if (!isset($ccn->id_cn)) {
                $address = new Address($order->id_address_delivery);
                if (!$ccn->name || $ccn->name == '') {
                    $ccn->name = $address->firstname;
                }
                if (!$ccn->id_order) {
                    $ccn->id_order = $id_order;
                }
                if (!$ccn->surname || $ccn->surname == '') {
                    $ccn->surname = $address->lastname;
                }
                if (!$ccn->phone || $ccn->phone == '') {
                    $ccn->phone = $this->getPhoneByAddress($order->id_address_delivery, $order->id_address_invoice);
                }
                if (!$ccn->city || $ccn->city == '') {
                    $ccn->city = $this->getCityByIdCart($cart);
                }
                if (!$ccn->warehouse || $ccn->warehouse == '') {
                    $ccn->warehouse = $this->getWarehouseByIdCart($cart);
                }
                $ccn->cod = 1;
                if (!$ccn->price || $ccn->price == '') {
                    $ccn->price = (float)$order->total_paid;
                }
                if (!$ccn->weight || $ccn->weight == '') {
                    $ccn->weight = (float)$order->getTotalWeight();
                }
                if (!$ccn->volume || $ccn->volume == '') {
                    $ccn->volume = (float)Configuration::get('VOLUME_NP');
                }
                if (!$ccn->count || $ccn->count == '') {
                    $ccn->count = (float)Configuration::get('COUNT_NP');
                }
            }
            $encrypt_f = Tools::encrypt('kldeliverynppro/index');
            $t = Tools::substr($encrypt_f, 0, 10);
            if ($this->context->controller->controller_type === 'admin') {
                $this->context->smarty->assign(
                    array(
                        'CCNUrl' => $this->context->link->getAdminLink('AdminKlookvaNovaPoshta').'&nptoken='.$t,
                        'ccn' => (array)$ccn,
                        'ajax_error' => $this->displayError($this->l('Error form send, check inputs'))
                    )
                );
            } else {
                $this->context->smarty->assign(
                    array(
                        'CCNUrl' => $this->context->link->getModuleLink(
                            'kldeliverynppro',
                            'ccnNP',
                            array('nptoken' => $t)
                        ),
                        'ccn' => (array)$ccn,
                        'ajax_error' => $this->displayError($this->l('Error form send, check inputs'))
                    )
                );
            }
            return $this->display(__FILE__, 'views/templates/hook/adminForm.tpl');
        } else {
            $city = $this->getCityByIdCart($cart);
            $encrypt_f = Tools::encrypt('kldeliverynppro/index');
            $t = Tools::substr($encrypt_f, 0, 10);
            if ($this->context->controller->controller_type === 'admin') {
                $this->context->smarty->assign(
                    array(
                        'CCNUrl' => $this->context->link->getAdminLink('AdminKlookvaNovaPoshta').'&nptoken='.$t,
                        'city' => $city,
                        'id_order' => $id_order,
                        'ajax_error' => $this->displayError($this->l('Error form send, check inputs'))
                    )
                );
            } else {
                $this->context->smarty->assign(
                    array(
                        'CCNUrl' => $this->context->link->getModuleLink(
                            'kldeliverynppro',
                            'ccnNP',
                            array('nptoken' => $t)
                        ),
                        'city' => $city,
                        'id_order' => $id_order,
                        'ajax_error' => $this->displayError($this->l('Error form send, check inputs'))
                    )
                );
            }
            return $this->display(__FILE__, 'views/templates/hook/adminFormNonNP.tpl');
        }
    }

    protected function truncateTable($table)
    {
        return Db::getInstance()->execute('TRUNCATE TABLE `'._DB_PREFIX_. pSQL($table).'`');
    }

    protected function insertCity($id_lang, $name, $city_id, $region_id, $ref)
    {
        return Db::getInstance()->insert('kl_delivery_nova_poshta_cities', array(
            'id_lang' => (int)$id_lang,
            'name' => pSQL($name),
            'city_id' => (int)$city_id,
            'region_id' => pSQL($region_id),
            'ref' => pSQL($ref)
        ));
    }

    protected function insertWarehouses($id_lang, $name, $city, $ref, $tmp = false, $number = null)
    {
        $table = 'kl_delivery_nova_poshta_warehouses';
        if ($tmp) {
            $table = 'kl_delivery_nova_poshta_warehouses_tmp';
        }

        //var_dump($number);

        return Db::getInstance()->insert($table, array(
            'id_lang' => (int)$id_lang,
            'name' => pSQL($name),
            'city' => pSQL($city),
            'ref' => pSQL($ref),
            'number' => (int)($number)
        ));
    }

    protected function getCities($id_lang)
    {
        return  Db::getInstance()->executeS('SELECT `ref`, `name` FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_cities` 
            WHERE `id_lang` = '.(int)$id_lang.' ORDER by `name` COLLATE utf8_unicode_ci ASC');
    }

    protected function getWarehouses($id_lang = false, $city = false)
    {
        $sql = 'SELECT `ref`, `name` FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses`';
        if ($city || $id_lang) {
            $sql .= ' WHERE';
        }
        if ($id_lang) {
            $sql .= ' `id_lang` = '.(int)$id_lang;
        }
        if ($city) {
            if ($id_lang) {
                $sql .= ' AND';
            }
            $sql .= ' `city` = \''.pSQL($city).'\'';
        }
        $sql .= ' ORDER by `number` ASC';
        return  Db::getInstance()->executeS($sql);
    }

    protected function getCity($id_lang)
    {
        $res = Db::getInstance()->getValue('SELECT `ref`, `name` FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_cities` 
            WHERE `id_lang` = '.(int)$id_lang.' ');

        usort($res, function ($a, $b) {
            if ($a['name'] == $b['name']) {
                return 0;
            }
            return ($a['name'] > $b['name'] ? 1 : -1);
        });
        return $res;
    }

    protected function getWarehouse($id_lang)
    {
        return  Db::getInstance()->getValue('SELECT `ref`,`name` FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses` 
            WHERE `id_lang` = '.(int)$id_lang.' ORDER by `name` ASC');
    }
	
	public function getCityByRef($ref, $id_lang)
    {
        return  Db::getInstance()->getValue('SELECT `name` FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_cities` 
            WHERE `ref` = "'.$ref.'" AND `id_lang` = '.(int)$id_lang.' ORDER by `name` ASC');
    }
	
	public function getWarehouseByRef($ref, $id_lang)
    {
        return  Db::getInstance()->getValue('SELECT `name` FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses` 
            WHERE `ref` = "'.$ref.'" AND `id_lang` = '.(int)$id_lang.' ORDER by `name` ASC');
    }

    public function getNPApi2()
    {
        foreach (Language::getLanguages(true) as $lang) {
            if ($lang['id_lang'] == $this->context->language->id &&
                ($lang['iso_code'] == 'ru' || $lang['iso_code'] == 'ua' || $lang['iso_code'] == 'en')) {
                $np = new NovaPoshtaApi2(
                    Configuration::get('API_KEY_NP'),
                    $lang['iso_code'],
                    false,
                    'curl'
                );
                return $np;
            } else {
                $np = new NovaPoshtaApi2(
                    Configuration::get('API_KEY_NP'),
                    'en',
                    false,
                    'curl'
                );
                return $np;
            }
        }
    }

    public function updateCities()
    {
        $np = $this->getNPApi2();
        $cities = $np->getCities();
        if (!is_array($cities) ||
            !array_key_exists('data', $cities) ||
            count($cities) <= 0 ||
            !$cities['success'] ||
            count($cities['data']) <= 0
        ) {
            return false;
        }
        $this->truncateTable('kl_delivery_nova_poshta_cities');
        foreach ($cities["data"] as $city) {
            if (!$city['DescriptionRu'] ||
                !$city['Description'] ||
                $city['DescriptionRu'] == '' ||
                $city['Description'] == ''
            ) {
                continue;
            }
            foreach (Language::getLanguages(true) as $lang) {
                if ($lang['iso_code'] == 'ru') {
                    $this->insertCity(
                        $lang['id_lang'],
                        $city['DescriptionRu'],
                        $city['CityID'],
                        $city['Area'],
                        $city['Ref']
                    );
                } else {
                    $this->insertCity(
                        $lang['id_lang'],
                        $city['Description'],
                        $city['CityID'],
                        $city['Area'],
                        $city['Ref']
                    );
                }
            }
        }
        return true;
    }

    public function getWarehouseByIdCart($id_cart)
    {
        $np = new NovaPoshtaCartClass($id_cart);
        return $np->warehouse;
    }

    public function getCityByIdCart($id_cart)
    {
        $np = new NovaPoshtaCartClass($id_cart);
        return $np->city;
    }

    public function updateWarehouses()
    {
        $np = $this->getNPApi2();
        $warehouses = $np->getAllWarehouses();
		//if($_SERVER['REMOTE_ADDR'] == '134.249.84.233') { echo '<pre>'; var_dump($np, $warehouses); echo '</pre>';  exit;}
        if (!is_array($warehouses) ||
            !array_key_exists('data', $warehouses) ||
            count($warehouses) <= 0 ||
            !$warehouses['success'] ||
            count($warehouses['data']) <= 0
        ) {
            return false;
        }

        $this->truncateTable('kl_delivery_nova_poshta_warehouses_tmp');

        $count = 0;
        foreach ($warehouses["data"] as $warehouse) {
            $count ++;
            echo("<pre>");
            unset($warehouse['SendingLimitationsOnDimensions']);
            unset($warehouse['ReceivingLimitationsOnDimensions']);
            unset($warehouse['Reception']);
            unset($warehouse['Delivery']);
            unset($warehouse['Schedule']);
            //unset($warehouse['']);
            //var_dump($warehouse);
            //if($count > 50000) exit;

            /*if (in_array($warehouse['TypeOfWarehouse'], self::SKIP_TYPES) ||
                !$warehouse['DescriptionRu'] ||
                !$warehouse['Description'] ||
                $warehouse['DescriptionRu'] == '' ||
                $warehouse['Description'] == '' ||
                !$warehouse['CityDescriptionRu'] ||
                !$warehouse['CityDescription'] ||
                $warehouse['CityDescriptionRu'] == '' ||
                $warehouse['CityDescription'] == ''
            ) {
                continue;
            }*/

            $warehouse['Description'] = str_replace(":"," ", $warehouse['Description']);
            $warehouse['Description'] = str_replace("№ ","№", $warehouse['Description']);
            $warehouse['Description'] = str_replace(" - ","-", $warehouse['Description']);
            $warehouse['Description'] = str_replace("  "," ", $warehouse['Description']);
            $warehouse['Description'] = str_replace("Пункт","Поштомат", $warehouse['Description']);
            //var_dump($warehouse['Description']);
            //var_dump($warehouse['Number']);

            foreach (Language::getLanguages(true) as $lang) {
                if ($lang['iso_code'] == 'ru') {
                    $this->insertWarehouses(
                        $lang['id_lang'],
                        $warehouse['DescriptionRu'],
                        $warehouse['CityDescriptionRu'],
                        $warehouse['Ref'],
                        true,
                        $warehouse['Number']
                    );
                } else {
                    $this->insertWarehouses(
                        $lang['id_lang'],
                        $warehouse['Description'],
                        $warehouse['CityDescription'],
                        $warehouse['Ref'],
                        true,
                        $warehouse['Number']
                    );
                }
            }
        }

        $this->truncateTable('kl_delivery_nova_poshta_warehouses');
        $sql = 'INSERT INTO `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses`
            SELECT * FROM `'._DB_PREFIX_.'kl_delivery_nova_poshta_warehouses_tmp` ORDER by `number`;';
        Db::getInstance()->execute($sql);

        $this->truncateTable('kl_delivery_nova_poshta_warehouses_tmp');
        return true;
    }


    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('controller') == 'AdminOrders' ||
            (Tools::getValue('controller') == 'AdminModules' && Tools::getValue('configure') == 'kldeliverynppro')
        ) {
            $this->addCssAndJs();
            return $this->display(__FILE__, 'views/templates/hook/jsdef.tpl');
        }
    }

    public function addCssAndJs()
    {
        $this->context->controller->addJquery();
        $this->context->controller->addJS($this->_path . 'views/js/kldeliverynppro.js', 'all');
        if (strpos(implode(';', $this->context->controller->js_files), 'select2') === false) {
            $this->context->controller->addCSS($this->_path . 'views/css/select2.min.css', 'all');
        }
        if (strpos(implode(';', $this->context->controller->js_files), 'select2') === false) {
            $this->context->controller->addJS($this->_path . 'views/js/select2.min.js', 'all');
        }
        if (strpos(implode(';', $this->context->controller->js_files), 'p-loading') === false) {
            $this->context->controller->addJS($this->_path . 'views/js/p-loading.min.js', 'all');
        }
        if (strpos(implode(';', $this->context->controller->css_files), 'p-loading') === false) {
            $this->context->controller->addCSS($this->_path . 'views/css/p-loading.min.css', 'all');
        }

        $this->context->controller->addCSS($this->_path . 'views/css/np-pro.css', 'all');

        $encrypt_f = Tools::encrypt('kldeliverynppro/index');
        $t = Tools::substr($encrypt_f, 0, 10);

        if ($this->context->controller->controller_type === 'admin') {
            $wt = '&getWarehouse=1&nptoken='.$t;
            $this->context->smarty->assign(
                array(
                    'np_id_carrier' => $this->id_carrier,
                    'change_required' => Configuration::get('CHANGE_REQUIRED_NP'),
                    'ajaxurlget' => $this->context->link->getAdminLink('AdminKlookvaNovaPoshta').$wt,
                    'saveCartUrl' => $this->context->link->getModuleLink(
                        'kldeliverynppro',
                        'saveCartNovaPoshta',
                        array('nptoken' => $t)
                    ),
                    'CCNUrl' => $this->context->link->getAdminLink('AdminKlookvaNovaPoshta').'&nptoken='.$t
                )
            );
        } else {
            $this->context->smarty->assign(
                array(
                    'np_id_carrier' => $this->id_carrier,
                    'change_required' => Configuration::get('CHANGE_REQUIRED_NP'),
                    'ajaxurlget' => $this->context->link->getModuleLink(
                        'kldeliverynppro',
                        'getWarehouses',
                        array('nptoken' => $t)
                    ),
                    'saveCartUrl' => $this->context->link->getModuleLink(
                        'kldeliverynppro',
                        'saveCartNovaPoshta',
                        array('nptoken' => $t)
                    ),
                    'CCNUrl' => $this->context->link->getModuleLink(
                        'kldeliverynppro',
                        'ccnNP',
                        array('nptoken' => $t)
                    )
                )
            );
        }
    }

    public function hookDisplayHeader()
    {
        $this->addCssAndJs();
        return $this->display(__FILE__, 'views/templates/hook/jsdef.tpl');
    }

    public function hookDisplayCarrierList($params)
    {
        $this->context->smarty->assign($this->getWidgetVariables($params));
        return $this->display(__FILE__, 'views/templates/hook/kldeliverynppro.tpl');
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        $id_cart = Order::getCartIdStatic($params['id_order']);
        $cartNp = new NovaPoshtaCartClass((int)$id_cart);
        $statuses = explode(',', Configuration::get('STATUSES_NP'));
        if ($id_cart == $cartNp->id_cart && (empty($statuses) || in_array($params['newOrderStatus']->id, $statuses))) {
            $ccn = new Ccn($params['id_order']);
            if (!isset($ccn->id_cn)) {
                $order = new Order($params['id_order']);
                $cart = Order::getCartIdStatic($params['id_order']);
                $address = new Address($order->id_address_delivery);
                if (!$ccn->name || $ccn->name == '') {
                    $ccn->name = $address->firstname;
                }
                if (!$ccn->id_order) {
                    $ccn->id_order = $params['id_order'];
                }
                if (!$ccn->surname || $ccn->surname == '') {
                    $ccn->surname = $address->lastname;
                }
                if (!$ccn->phone || $ccn->phone == '') {
                    $ccn->phone = $this->getPhoneByAddress($order->id_address_delivery, $order->id_address_invoice);
                }
                if (!$ccn->city || $ccn->city == '') {
                    $ccn->city = $this->getCityByIdCart($cart);
                }
                if (!$ccn->warehouse || $ccn->warehouse == '') {
                    $ccn->warehouse = $this->getWarehouseByIdCart($cart);
                }
                $ccn->cod = 1;
                if (!$ccn->price || $ccn->price == '') {
                    $ccn->price = (float)$order->total_paid;
                }
                if (!$ccn->weight || $ccn->weight == '') {
                    $ccn->weight = (float)$order->getTotalWeight();
                }
                if (!$ccn->volume || $ccn->volume == '') {
                    $ccn->volume = (float)Configuration::get('VOLUME_NP');
                }
                if (!$ccn->count || $ccn->count == '') {
                    $ccn->count = (float)Configuration::get('COUNT_NP');
                }
            }
            $this->createCCN(
                $ccn->name,
                $ccn->surname,
                $ccn->phone,
                $ccn->city,
                trim($ccn->warehouse),
                $ccn->cod,
                $params['id_order'],
                $ccn->price,
                $ccn->weight,
                $ccn->volume,
                $ccn->count
            );
        }
    }

    public function createCCN(
        $name,
        $surname,
        $phone,
        $city,
        $warehouse,
        $cod,
        $id_order,
        $price,
        $weight,
        $volume,
        $count
    ) {
        $compact = compact(
            'name',
            'surname',
            'phone',
            'city',
            'warehouse',
            'id_order',
            'price',
            'weight',
            'volume',
            'count'
        );
        $empty_values = $this->validateCCN($compact);
        if (!empty($empty_values)) {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayError(implode(', ', $empty_values))
                )
            );
            return;
        }
        $np = $this->getNPApi2();
        $sender = array(
            'FirstName' => Configuration::get('FIRSTNAME_ADMIN_NP'),
            'MiddleName' => Configuration::get('MIDDLENAME_ADMIN_NP'),
            'LastName' => Configuration::get('LASTNAME_ADMIN_NP'),
            'Phone' => Configuration::get('PHONE_ADMIN_NP'),
            'City' => Configuration::get('CITY_ADMIN_NP'),
            'Region' => Configuration::get('REGION_ADMIN_NP'),
            'Warehouse' => Configuration::get('WAREHOUSE_ADMIN_NP')
        );
        $area_ref = $np->getCity($city)['data'][0]['Area'];
        $area = $np->getArea('', $area_ref)['data'][0]['Area'];
        $sub_surname = explode(' ', $surname);
        $recipient = array(
            'FirstName' => $name,
            'LastName' => $sub_surname[0],
            'MiddleName' => (array_key_exists(1, $sub_surname) ? $sub_surname[1] : ''),
            'Phone' => $phone,
            'City' => $city,
            'Region' => $area,
            'Warehouse' => $warehouse
        );
        $ccn = new Ccn($id_order);
        $ccn->id_order = $id_order;
        $ccn->name = $name;
        $ccn->surname = $surname;
        $ccn->phone = $phone;
        $ccn->city = $city;
        $ccn->warehouse = $warehouse;
        $ccn->cod = $cod;
        $ccn->price = $price;
        $ccn->weight = $weight;
        $ccn->volume = $volume;
        $ccn->count = $count;
        $order = new Order($id_order);
        $products = $order->getProducts();
        $category = new Category(current($products)['id_category_default'], $this->context->language->id);
        
        $payer = Configuration::get('PAYERTYPE_NP');
        $payer_backward = Configuration::get('PAYERTYPE_BACKWARD_NP');
        if ($price >= Configuration::get('MIN_PRICE_FREE_NP') &&
            $price <= Configuration::get('MAX_PRICE_FREE_NP')
        ) {
            $payer = 'Sender';
            $payer_backward = 'Sender';
        }

        $params = array(
            'Description' => $category->name,
            'Weight' => (float)$weight,
            'Cost' => (float)$price,
            'VolumeGeneral' => (float)$volume,
            'SeatsAmount' => (int)$count,
            'CargoType' => 'Parcel',
            'PaymentMethod' => Configuration::get('PAYMENTMETHOD_NP'),
            'PayerType' => $payer,
            'InfoRegClientBarcodes' => $id_order
        );

        if ($cod) {
            $params['BackwardDeliveryData'] = array(
                array(
                    'PayerType' => $payer_backward,
                    'CargoType' => 'Money',
                    'RedeliveryString' => (float)$price
                )
            );
        }
        $afterpaymentOnGoods = Configuration::get('AFTERPAYMENT_ON_GOODS_COST_NP');
        $afterpaymentOnGoodsCost = false;
        if($afterpaymentOnGoods == 1){
            $afterpaymentOnGoodsCost = (float)$price;
        }
        
        if ($ccn->id_cn) {
            $result = $np->updateInternetDocument($ccn->id_cn, $sender, $recipient, $params, false, $afterpaymentOnGoodsCost);
        } else {
            $result = $np->newInternetDocument($sender, $recipient, $params, false, $afterpaymentOnGoodsCost);
        }
        if (!empty($result) && !empty($result['data'])) {
            $ccn->id_cn = $result['data'][0]['Ref'];
            $ccn->save();
            $address = new Address($order->id_address_delivery);
            $address->firstname = $ccn->name;
            $address->lastname = $ccn->surname;
            $address->phone = $ccn->phone;
            $address->city = $ccn->city;
            $address->other = $ccn->warehouse;
            $address->save();
            $order->setWsShippingNumber($result['data'][0]['IntDocNumber']);
            $order->shipping_number = $result['data'][0]['IntDocNumber'];
            $order->save();
            echo json_encode(
                array(
                    'error' => 0,
                    'message' => $this->displayConfirmation(
                        $this->l('CNN number').' '.$result['data'][0]['Ref'].' '.$this->l('success created')
                    )
                )
            );
        } else {
            if (array_key_exists('errors', $result) && !empty($result['errors'])) {
                echo json_encode(
                    array(
                        'error' => 1,
                        'message' => $this->displayError(
                            implode(', ', $result['errors'])
                        )
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'error' => 1,
                        'message' => $this->displayWarning(
                            $this->l('No data')
                        )
                    )
                );
            }
        }
    }

    public function hookDisplayCarrierExtraContent($params)
    {
        $this->context->smarty->assign($this->getWidgetVariables($params));
        return $this->display(__FILE__, 'views/templates/hook/kldeliverynppro.tpl');
    }

    public function getLastCustomerData($id_customer, $type)
    {
        $carts = Cart::getCustomerCarts($id_customer, true);
        $func = 'get'.Tools::ucfirst($type).'ByIdCart';
        foreach ($carts as $cart) {
            $name = $this->{$func}($cart['id_cart']);
            if ($name && $name != '') {
                return $name;
            }
        }
        return false;
    }

    public function getWidgetVariables($params)
    {
        $customer = $this->context->customer;
        $id_cart = $this->context->cart->id;
        if (Tools::getValue('id_order')) {
            $order = new Order(Tools::getValue('id_order'));
            $customer = new Customer($order->id_customer);
            $id_cart = $order->id_cart;
        }
        $current_id_lang = $this->context->language->id;
        $cities = $this->getCities($current_id_lang);
        $current_city = array_key_exists('city_np', $params) ? $params['city_np'] : $this->getCityByIdCart(
            $id_cart
        );
        $current_warehouse = $this->getWarehouseByIdCart($id_cart);
        if ($customer && is_numeric($customer->id)) {
            if (!$current_city || $current_city == '') {
                $current_city = $this->getLastCustomerData($customer->id, 'city');
            }
            if (!$current_warehouse || $current_warehouse == '') {
                $current_warehouse = $this->getLastCustomerData($customer->id, 'warehouse');
            }
        }

        $id_carriers = array();
        $id_carriers[$this->l('Nova Poshta Shipping - Pro')] = $this->id_carrier;
        return array(
            'cities' => $cities,
            'id_lang' => $current_id_lang,
            'current_city' => trim((string)$current_city),
            'current_warehouse' => trim((string)$current_warehouse),
            'carriers' => $id_carriers
        );
    }

    public function printCCN($id_order)
    {
        $np = $this->getNPApi2();
        $ccn = new Ccn($id_order);
        if ($ccn->id_cn) {
            $result = $np->printDocument($ccn->id_cn, 'pdf_link');
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayWarning(
                        $this->l('No ccn', 'ccnNP')
                    )
                )
            );
            return;
        }
        if (!empty($result) && !empty($result['data'])) {
            echo json_encode(
                array(
                    'error' => 0,
                    'message' => $this->displayConfirmation(
                        $this->l(
                            'Upload :',
                            'ccnNP'
                        ).' <a href="'.$result['data'][0].'" target="_blank">'.$this->l('pdf ccn').'</a>'
                    )
                )
            );
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayWarning(
                        $this->l('No data', 'ccnNP')
                    )
                )
            );
        }
    }

    public function printMarkings($id_order)
    {
        $np = $this->getNPApi2();
        $ccn = new Ccn($id_order);
        if ($ccn->id_cn) {
            $result = $np->printMarkings($ccn->id_cn, 'pdf_link');
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayWarning(
                        $this->l('No ccn', 'ccnNP')
                    )
                )
            );
            return;
        }
        if (!empty($result) && !empty($result['data'])) {
            echo json_encode(
                array(
                    'error' => 0,
                    'message' => $this->displayConfirmation(
                        $this->l(
                            'Upload :',
                            'ccnNP'
                        ).' <a href="'.$result['data'][0].'" target="_blank">'.$this->l('pdf markings').'</a>'
                    )
                )
            );
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayWarning(
                        $this->l('No data', 'ccnNP')
                    )
                )
            );
        }
    }

    public function deleteCCN($id_order)
    {
        $np = $this->getNPApi2();
        $ccn = new Ccn($id_order);
        if ($ccn->id_cn) {
            $ccn_ref = $ccn->id_cn;
            $result = $np->deleteInternetDocument($ccn->id_cn);
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayWarning(
                        $this->l('No ccn', 'ccnNP')
                    )
                )
            );
            return;
        }
        if ((!empty($result) && !empty($result['data'])) ||
            (!empty($result) &&
                !empty($result['errors']) &&
                array_key_exists($ccn->id_cn, $result['errors']) &&
                strpos($result['errors'][$ccn->id_cn], 'Document already deleted') !== false
            )
        ) {
            $ccn->delete();
            echo json_encode(
                array(
                    'error' => 0,
                    'message' => $this->displayConfirmation(
                        $this->l(
                            'CNN number',
                            'ccnNP'
                        ).' '.$ccn_ref.' '.$this->l('success deleted', 'ccnNP')
                    )
                )
            );
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayWarning(
                        $this->l('No data', 'ccnNP')
                    )
                )
            );
        }
    }

    public function trackCCN($id_order)
    {
        $np = $this->getNPApi2();
        $order = new Order($id_order);
        $result = $np->documentsTracking($order->getWsShippingNumber());
        if (!empty($result) && !empty($result['data'])) {
            echo json_encode(
                array(
                    'error' => 0,
                    'message' => $this->displayConfirmation(
                        $this->l(
                            'Tracking number',
                            'ccnNP'
                        ).' '.$result['data'][0]['Number'].' - '.$result['data'][0]['Status']
                    )
                )
            );
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayWarning(
                        $this->l('Number of tracking error or empty', 'ccnNP')
                    )
                )
            );
        }
    }

    public function saveCCN(
        $id_order,
        $city,
        $warehouse,
        $name,
        $surname,
        $phone,
        $cod,
        $price,
        $weight,
        $volume,
        $count
    ) {
        $compact = compact(
            'id_order',
            'city',
            'warehouse',
            'name',
            'surname',
            'phone',
            'price',
            'weight',
            'volume',
            'count'
        );
        $empty_values = $this->validateCCN($compact);
        if (!empty($empty_values)) {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayError(implode(', ', $empty_values))
                )
            );
            return;
        }
        $cart = Order::getCartIdStatic($id_order);
        $cartNp = new NovaPoshtaCartClass((int)$cart);
        $ccn = new Ccn($id_order);
        if ($cart == $cartNp->id_cart) {
            $cartNp->city = $city;
            $cartNp->warehouse = $warehouse;
            $ccn->id_order = $id_order;
            $ccn->name = $name;
            $ccn->surname = $surname;
            $ccn->phone = $phone;
            $ccn->city = $city;
            $ccn->warehouse = $warehouse;
            $ccn->cod = $cod;
            $ccn->price = (float)$price;
            $ccn->weight = (float)$weight;
            $ccn->volume = (float)$volume;
            $ccn->count = (int)$count;
            if ($cartNp->save() && $ccn->save()) {
                echo json_encode(
                    array(
                        'error' => 0,
                        'message' => $this->displayConfirmation(
                            $this->l('Success save data')
                        )
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'error' => 1,
                        'message' => $this->displayError(
                            $this->l('Cant save data')
                        )
                    )
                );
            }
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayError(
                        $this->l('No cart match')
                    )
                )
            );
        }
    }

    public function saveCartNP($id_order, $city, $warehouse)
    {
        $res = true;
        $order = new Order($id_order);
        $cartNp = new NovaPoshtaCartClass((int)$order->id_cart);

        $cartNp->id_cart = $order->id_cart;
        $cartNp->id_customer = $order->id_customer;
        $cartNp->city = $city;
        $cartNp->warehouse = $warehouse;
        $res &= $cartNp->save();

        $customer = new Customer($order->id_customer);
        $address = new Address();
        $address->id_country = Customer::getCurrentCountry($order->id_customer);
        $address->alias = $this->l('Delivery address for Nova Poshta');
        $address->firstname = $customer->firstname;
        $address->lastname = $customer->lastname;
        $address->address1 = ' ';
        $address->city = $cartNp->city;
        $address->other = $cartNp->warehouse;
        $address->phone = $this->getPhoneByAddress($order->id_address_delivery, $order->id_address_invoice);
        $res &= $address->save();

        $order->id_address_delivery = $address->id;
        $res &= $order->save();

        $order_carrier = new OrderCarrier($order->getIdOrderCarrier());
        $order_carrier->id_carrier = $this->id_carrier;
        $res &= $order_carrier->save();
        if ($res) {
            echo json_encode(
                array(
                    'error' => 0,
                    'message' => $this->displayConfirmation(
                        $this->l('Success save data')
                    )
                )
            );
        } else {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->displayError(
                        $this->l('Cant save data')
                    )
                )
            );
        }
    }

    protected function getAllAvailibleCounterpartyIfDontMatchYou(
        $city,
        $region,
        $firstname,
        $lastname,
        $middlename
    ) {
        $np = $this->getNPApi2();
        $senderCity = $np->getCity($city, $region);

        $description = $lastname.' '.$firstname.' '.$middlename;
        $senderCounterpartyExisting = $np->getCounterparties(
            'Sender',
            1,
            $description,
            $senderCity['data'][0]['Ref']
        );
        if (empty($senderCounterpartyExisting['data']) || !$senderCounterpartyExisting['data'][0]['Ref']) {
            $senderCounterpartyExisting = $np->getCounterparties(
                'Sender',
                1
            );
        }
        $contactSender = $np->getCounterpartyContactPersons($senderCounterpartyExisting['data'][0]['Ref']);
        $names_array = array();

        foreach ($contactSender['data'] as $k => $data) {
            $names_array[$k] = $this->l('Firstname').': '.$data['FirstName']."<br>";
            $names_array[$k] .= $this->l('LastName').': '.$data['LastName']."<br>";
            $names_array[$k] .= $this->l('MiddleName').': '.$data['MiddleName']."<br>";
            if ($data['LastName'] == $lastname &&
                $data['FirstName'] == $firstname &&
                $data['MiddleName'] == $middlename
            ) {
                $names_array = array();
                break;
            }
        }
        
        return $names_array;
    }

    public function validateCCN($arguments)
    {
        $empty_values = array();
        foreach ($arguments as $key => $argument) {
            if (!$argument || $argument == '') {
                $k = $this->l(Tools::ucfirst($key));
                $empty_values[] = $this->l('Value').' `'.$k.'` '.$this->l('are empty.');
            }
        }
        
        return $empty_values;
    }

    public function isAfterpaymentOnGoodsCostAvailable(){
        $np = $this->getNPApi2();
        $senderCounterpartyExisting = $np->getCounterparties(
            'Sender',
            1
        );
        foreach ($senderCounterpartyExisting['data'] as $counterparty){
            $cp_options = $np->getCounterpartyOptions($counterparty['Ref']);
            if ($cp_options['success'] && $cp_options['data'][0]['CanAfterpaymentOnGoodsCost'] == true){
                return true;
            }
            return false;
        }
    }
}
