<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2020 Knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 *
 * Description
 *
 */

/*
 * v6.0.5 Changes are mentioned below:
 * Added Abandoned cart checkout statistics functionality (Search using "Feature:Abcart Stats (Jan 2020)" keyword to find the changes in the code)
 * Added Checkout behavior functionality (Search using "Feature: Checkout Behavior (Jan 2020)" keyword to find the changes in the code)
 * Added validation option to check DNI/CIF/NIF for Spain (Search using "Feature:Spain DNI Check (Jan 2020)" keyword to find the changes in the code)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__) . '/classes/supercheckout_configuration.php';

class Supercheckout extends Module
{
    private $supercheckout_settings = array();
    public $submit_action = 'submit';
    private $custom_errors = array();
    const PARENT_TAB_CLASS = 'AdminkbsupercheckoutConfigure';
    const SELL_CLASS_NAME = 'SELL';

    public function __construct()
    {
        $this->name = 'supercheckout';
        $this->tab = 'checkout';
        $this->version = '8.0.3';
        $this->author = 'Knowband';
        $this->need_instance = 0;
	$this->module_key = '68a34cdd0bc05f6305874ea844eefa05';
        $this->author_address = '0x2C366b113bd378672D4Ee91B75dC727E857A54A6';
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' <= _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('SuperCheckout');
        $this->description = $this->l('One page checkout');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!class_exists('KbMailChimp')) {
            include_once dirname(__FILE__) . '/libraries/mailchimpl library.php';
        }
        
        /*
         * Added by Anshul for adding the library for SendInBlue
         */
        if (!class_exists('KbSuperMailin')) {
            include_once(dirname(__FILE__) . '/libraries/sendinBlue/Mailin.php');
        }
    }

    public function getErrors()
    {
        return $this->custom_errors;
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        // <editor-fold defaultstate="collapsed" desc="GDPR change">
        // changes by rishabh jain
        if (!parent::install()
            || !$this->registerHook('displayOrderConfirmation')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayContentWrapperTop')
            || !$this->registerHook('displayAdminOrderContentShip')
            || !$this->registerHook('displayAdminOrderTabShip')
            || !$this->registerHook('actionValidateOrder')
            || !$this->registerHook('displayOrderDetail')
            || !$this->registerHook('displayPDFInvoice')
            || !$this->registerHook('displayAdminOrderTabLink')
            || !$this->registerHook('displayAdminOrderTabContent')
            || !$this->registerHook('customSuperCheckoutGDPRHook')
            || !$this->registerHook('actionEmailAddAfterContent')
            || !$this->registerHook('actionDeleteGDPRCustomer')
//            || !$this->registerHook('displayCartExtraProductActions')
        ) {
            return false;
        }
        // </editor-fold>

        /*Start: Added by Anshul on 10-Feb-2020 for adding the mails folder for sending the welcome mail with password if registered guest setting is enabled*/
        if (!Configuration::get('VELSOF_SC_MAIL_CHECK')) {
            //Tools::chmodr(_PS_MODULE_DIR_ . 'abandonedcart/mails', 0755);
            $mail_dir = dirname(__FILE__) . '/mails/en';
            foreach (Language::getLanguages(false) as $lang) {
                if ($lang['iso_code'] != 'en') {
                    $new_dir = dirname(__FILE__) . '/mails/' . $lang['iso_code'];
                    if (!file_exists($new_dir)) {
                        $this->copyfolder($mail_dir, $new_dir);
                    }
                }
            }
            Configuration::updateGlobalValue('VELSOF_SC_MAIL_CHECK', 1);
        }
        /*End: Added by Anshul on 10-Feb-2020 for adding the mails folder for sending the welcome mail with password if registered guest setting is enabled*/
        

        $table_custom_fields = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields` (
				`id_velsof_supercheckout_custom_fields` int(10) NOT NULL AUTO_INCREMENT,
				`type` enum("textbox","selectbox","textarea","radio","checkbox", "date", "file") NOT NULL,
				`position` varchar(50) NOT NULL,
				`required` tinyint(1) NOT NULL,
				`active` tinyint(1) NOT NULL,
                                `show_invoice` tinyint(1) NOT NULL DEFAULT 0,
				`default_value` varchar(1000) NOT NULL,
				`validation_type` varchar(50) NOT NULL,
				PRIMARY KEY (`id_velsof_supercheckout_custom_fields`)
				)  CHARACTER SET utf8 COLLATE utf8_general_ci';
        
        $table_custom_fields_lang = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang` (
				`id_velsof_supercheckout_custom_fields_lang` int(10) NOT NULL AUTO_INCREMENT,
				`id_velsof_supercheckout_custom_fields` int(10) NOT NULL,
				`id_lang` int(10) NOT NULL,
				`field_label` varchar(250) NOT NULL,
				`field_help_text` varchar(1000) NOT NULL,
				PRIMARY KEY (`id_velsof_supercheckout_custom_fields_lang`)
				)  CHARACTER SET utf8 COLLATE utf8_general_ci';

        $table_custom_fields_options = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_custom_field_options_lang` (
				`id_velsof_supercheckout_custom_field_options_lang` int(10) NOT NULL AUTO_INCREMENT,
				`id_velsof_supercheckout_custom_fields` int(10) NOT NULL,
				`id_lang` int(10) NOT NULL,
				`option_value` varchar(100) NOT NULL,
				`option_label` varchar(1000) NOT NULL,
				PRIMARY KEY (`id_velsof_supercheckout_custom_field_options_lang`)
			       )  CHARACTER SET utf8 COLLATE utf8_general_ci';

        $table_custom_fields_data = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_fields_data` (
				`id_velsof_supercheckout_fields_data` int(10) NOT NULL AUTO_INCREMENT,
				`id_velsof_supercheckout_custom_fields` int(10) NOT NULL,
				`id_order` int(10) NOT NULL,
				`id_cart` int(10) NOT NULL,
				`id_lang` int(10) NOT NULL,
				`field_value` varchar(1000) NOT NULL,
				PRIMARY KEY (`id_velsof_supercheckout_fields_data`)
			       )  CHARACTER SET utf8 COLLATE utf8_general_ci';
        // changes by rishabh jain
        $table_gift_message = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'kb_supercheckout_gift_message` (
                                `id_gift_message` int(11) NOT NULL AUTO_INCREMENT,
                                `id_cart` int(11) NOT NULL,
                                `id_order` int(11) DEFAULT NULL,
                                `kb_sender` text NOT NULL,
                                `kb_receiver` text NOT NULL,
                                `kb_message` longtext NOT NULL,
                                `time_updated` datetime DEFAULT NULL,
                                `time_added` datetime DEFAULT NULL,
                                PRIMARY KEY (`id_gift_message`)
                              ) CHARACTER SET utf8 COLLATE utf8_general_ci';
        
        //Start:Changes done by Anshul Mittal on 09/01/2020 for Checkout Behaviour enhancement (Feature: Checkout Behavior (Jan 2020))
        $stats_table = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'kb_checkout_behaviour_stats` (
                        `id_kb_checkout_behaviour_stats` int(10) NOT NULL AUTO_INCREMENT,
                        `id_cart` int(10) NOT NULL,
                        `email` int(1) DEFAULT 0 NOT NULL,
                        `firstname` int(1) DEFAULT 0 NOT NULL,
                        `lastname` int(1) DEFAULT 0 NOT NULL,
                        `company` int(1) DEFAULT 0 NOT NULL,
                        `address1` int(1) DEFAULT 0 NOT NULL,
                        `address2` int(1) DEFAULT 0 NOT NULL,
                        `city` int(1) DEFAULT 0 NOT NULL,
                        `id_country` int(1) DEFAULT 0 NOT NULL,
                        `id_state` int(1) DEFAULT 0 NOT NULL,
                        `postcode` int(1) DEFAULT 0 NOT NULL,
                        `phone` int(1) DEFAULT 0 NOT NULL,
                        `phone_mobile` int(1) DEFAULT 0 NOT NULL,
                        `vat_number` int(1) DEFAULT 0 NOT NULL,
                        `dni` int(1) DEFAULT 0 NOT NULL,
                        `other` int(1) DEFAULT 0 NOT NULL,
                        `alias` int(1) DEFAULT 0 NOT NULL,
                        `shipping_method` int(1) DEFAULT 0 NOT NULL,
                        `payment_method` int(1) DEFAULT 0 NOT NULL,
                        `use_for_invoice` int(1) DEFAULT 0 NOT NULL,
                        `firstname_invoice` int(1) DEFAULT 0 NOT NULL,
                        `lastname_invoice` int(1) DEFAULT 0 NOT NULL,
                        `company_invoice` int(1) DEFAULT 0 NOT NULL,
                        `address1_invoice` int(1) DEFAULT 0 NOT NULL,
                        `address2_invoice` int(1) DEFAULT 0 NOT NULL,
                        `city_invoice` int(1) DEFAULT 0 NOT NULL,
                        `id_country_invoice` int(1) DEFAULT 0 NOT NULL,
                        `id_state_invoice` int(1) DEFAULT 0 NOT NULL,
                        `postcode_invoice` int(1) DEFAULT 0 NOT NULL,
                        `phone_invoice` int(1) DEFAULT 0 NOT NULL,
                        `phone_mobile_invoice` int(1) DEFAULT 0 NOT NULL,
                        `vat_number_invoice` int(1) DEFAULT 0 NOT NULL,
                        `dni_invoice` int(1) DEFAULT 0 NOT NULL,
                        `other_invoice` int(1) DEFAULT 0 NOT NULL,
                        `alias_invoice` int(1) DEFAULT 0 NOT NULL,
                        `date_add` datetime DEFAULT NULL,
                        `date_upd` datetime DEFAULT NULL,
                        PRIMARY KEY (`id_kb_checkout_behaviour_stats`)
                        )  CHARACTER SET utf8 COLLATE utf8_general_ci';
        Db::getInstance()->execute($stats_table);
        //End:Changes done by Anshul Mittal on 09/01/2020 for Checkout Behaviour enhancement (Feature: Checkout Behavior (Jan 2020))
        
        //Start: Table created for inserting the cart amount on filtering the data (Feature:Abcart Stats (Jan 2020))
        $sql = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'abandoned_cart_amount (`id_cart` int(10) NOT NULL, `total_amount` decimal(20,6) NOT NULL default 0.0)';
        Db::getInstance()->execute($sql);
        //End: Table created for inserting the cart amount on filtering the data (Feature:Abcart Stats (Jan 2020))
        
        //create table for mapping customer profile with saved address start
        $table_profile_mapping = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'kb_supercheckout_profile_mapping` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `id_profile` int(11) NOT NULL,
                                `id_address` int(11) DEFAULT NULL,
                                `time_updated` datetime DEFAULT NULL,
                                `time_added` datetime DEFAULT NULL,
                                PRIMARY KEY (`id`)
                              ) CHARACTER SET utf8 COLLATE utf8_general_ci';
        
        //create table for mapping customer profile with saved adress end
        
        Db::getInstance()->execute($table_custom_fields);
        Db::getInstance()->execute($table_custom_fields_lang);
        Db::getInstance()->execute($table_custom_fields_options);
        Db::getInstance()->execute($table_custom_fields_data);
        Db::getInstance()->execute($table_gift_message);
        Db::getInstance()->execute($table_profile_mapping);

        /*
         * Start: Added by Anshul for adding the new enum values for custom fields
         */
        $query = "ALTER TABLE " . _DB_PREFIX_ . "velsof_supercheckout_custom_fields CHANGE type type ENUM('textbox','selectbox','textarea','radio','checkbox', 'date', 'file') NOT NULL";
        Db::getInstance()->execute($query);
        /*
         * End: Added by Anshul for adding the new enum values for custom fields
         */
        
        /*Start Code Added By Priyanshu on 11-Feb-2021 to add new column in the velsof_supercheckout_custom_fields table to implement the functionailty to show custom field details in invoice*/
        $check_col_sql = 'SELECT count(*) FROM information_schema.COLUMNS
                              WHERE COLUMN_NAME = "show_invoice"
                              AND TABLE_NAME = "' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields"
                              AND TABLE_SCHEMA = "' . _DB_NAME_ . '"';
        $check_col = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($check_col_sql);
        if ((int) $check_col == 0) {
            $query = 'ALTER TABLE `' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields` ADD `show_invoice` tinyint(1) NOT NULL DEFAULT 0 AFTER `active`';
            Db::getInstance()->execute($query);
        }
        /*End Code Added By Priyanshu on 11-Feb-2021 to add new column in the velsof_supercheckout_custom_fields table to implement the functionailty to show custom field details in invoice*/
        
        // <editor-fold defaultstate="collapsed" desc="GDPR Change">
        $table_customer_consent ='CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_customer_consent` (
                                `id_velsof_supercheckout_customer_consent` int(11) NOT NULL AUTO_INCREMENT,
                                `id_customer` int(11) DEFAULT NULL,
                                `id_order` int(11) DEFAULT NULL,
                                `order_reference` varchar(15) DEFAULT NULL,
                                `id_lang` int(11) NOT NULL,
                                `accepted_consent` varchar(8000) DEFAULT NULL,
                                PRIMARY KEY (`id_velsof_supercheckout_customer_consent`)
                               ) CHARACTER SET utf8 COLLATE utf8_general_ci';
        $table_sup_policies = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_policies` (
                                `policy_id` int(11) NOT NULL AUTO_INCREMENT,
                                `url` varchar(1000) DEFAULT NULL,
                                `is_manadatory` tinyint(4) NOT NULL,
                                `status` tinyint(4) NOT NULL,
                                PRIMARY KEY (`policy_id`)
                               ) CHARACTER SET utf8 COLLATE utf8_general_ci';
        $table_policy_lang = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_policy_lang` (
                                `policy_lang_id` int(11) NOT NULL AUTO_INCREMENT,
                                `policy_id` int(11) NOT NULL,
                                `lang_id` tinyint(4) NOT NULL,
                                `description` varchar(1000) NOT NULL,
                                PRIMARY KEY (`policy_lang_id`)
                               ) CHARACTER SET utf8 COLLATE utf8_general_ci';

        Db::getInstance()->execute($table_customer_consent);
        Db::getInstance()->execute($table_sup_policies);
        Db::getInstance()->execute($table_policy_lang);
        // </editor-fold>

        if (Configuration::get('VELOCITY_SUPERCHECKOUT')) {
            Configuration::deleteByName('VELOCITY_SUPERCHECKOUT');
        }

        if (Configuration::get('VELOCITY_SUPERCHECKOUT_HEADFOOTHTML')) {
            $data = json_decode((Configuration::get('VELOCITY_SUPERCHECKOUT_HEADFOOTHTML')), true);
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_HFHTML', json_encode($data));
            Configuration::deleteByName('VELOCITY_SUPERCHECKOUT_HEADFOOTHTML');
        }

        /*
         * Added by Anshul
         */
        if (Configuration::get('VELOCITY_SUPERCHECKOUT_BUTTON')) {
            Configuration::deleteByName('VELOCITY_SUPERCHECKOUT_BUTTON');
        }
        /*
         * Added by Anshul
         */

        if (Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMBUTTON')) {
            $data = json_decode((Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMBUTTON')), true);
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_BUTTON', json_encode($data));
            Configuration::deleteByName('VELOCITY_SUPERCHECKOUT_CUSTOMBUTTON');
        }

        if (Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMCSS')) {
            $data = json_decode((Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMCSS')), true);
            $data = urlencode($data);
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_CSS', json_encode($data));
            Configuration::deleteByName('VELOCITY_SUPERCHECKOUT_CUSTOMCSS');
        }

        if (Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMJS')) {
            $data = json_decode((Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMJS')), true);
            $data = urlencode($data);
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_JS', json_encode($data));
            Configuration::deleteByName('VELOCITY_SUPERCHECKOUT_CUSTOMJS');
        }
        Configuration::updateGlobalValue('PS_CART_RULE_FEATURE_ACTIVE', '1');
        Configuration::updateGlobalValue('VELOCITY_SUPERCHECKOUT_DEMO', 0);
        //Following code added by Anshul to install the tabs while installing the modules (Jan 2020)
        $this->installKbTabs();
        return true;
    }
    
    /**
     *
     * @param String $source
     * @param String $destination
     * Function defined by Anshul to copy a folder from EN language to other language folders (Jan 2020)
     */
    protected function copyfolder($source, $destination)
    {
        $directory = opendir($source);
        mkdir($destination);
        while (($file = readdir($directory)) != false) {
            Tools::copy($source . '/' . $file, $destination . '/' . $file);
        }
        closedir($directory);
    }

    public function uninstall()
    {
        // <editor-fold defaultstate="collapsed" desc="GDPR Change">
        // changes by rishabh jains
        if (!parent::uninstall()
            || !Configuration::deleteByName('VELOCITY_SUPERCHECKOUT')
            || !$this->unregisterHook('displayOrderConfirmation')
            || !$this->unregisterHook('displayHeader')
            || !$this->unregisterHook('displayContentWrapperTop')
            || !$this->unregisterHook('displayAdminOrderContentShip')
            || !$this->unregisterHook('displayAdminOrderTabShip')
            || !$this->unregisterHook('actionValidateOrder')
            || !$this->unregisterHook('displayOrderDetail')
            || !$this->unregisterHook('displayPDFInvoice')
            || !$this->unregisterHook('displayAdminOrderTabLink')
            || !$this->unregisterHook('displayAdminOrderTabContent')
            || !$this->unregisterHook('customSuperCheckoutGDPRHook')
            || !$this->unregisterHook('actionEmailAddAfterContent')
            || !$this->unregisterHook('actionDeleteGDPRCustomer')
        ) {
            return false;
        }
        // </editor-fold>
        //Following code added by Anshul to un-install the tabs while un-installing the modules (Jan 2020)
        $this->unInstallKbTabs();
        return true;
    }
    
    /**
     *
     * @return boolean
     * Function defined by Anshul to install the configuration & statistics tab (Jan 2020)
     */
    public function installKbTabs()
    {
        $parentTab = new Tab();
        $parentTab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $parentTab->name[$lang['id_lang']] = $this->l('Knowband Supercheckout');
        }

        $parentTab->class_name = self::PARENT_TAB_CLASS;
        $parentTab->module = $this->name;
        $parentTab->active = 1;
        $parentTab->id_parent = Tab::getIdFromClassName(self::SELL_CLASS_NAME);
        $parentTab->icon = 'bookmark';
//        Tools::dieObject($parentTab, true);
        $parentTab->add();

        $id_parent_tab = (int) Tab::getIdFromClassName(self::PARENT_TAB_CLASS);
        $admin_menus = $this->adminSubMenus();

        foreach ($admin_menus as $menu) {
            $tab = new Tab();
            foreach (Language::getLanguages(true) as $lang) {
                if ($this->getModuleTranslationByLanguage($this->name, $menu['name'], $this->name, $lang['iso_code']) != '') {
                    $tab->name[$lang['id_lang']] = $this->getModuleTranslationByLanguage($this->name, $menu['name'], $this->name, $lang['iso_code']);
                } else {
                    $tab->name[$lang['id_lang']] = $menu['name'];
                }
            }
            $tab->class_name = $menu['class_name'];
            $tab->module = $this->name;
            $tab->active = $menu['active'];
            $tab->id_parent = $id_parent_tab;
            $tab->add($this->id);
        }
        return true;
    }
    
    /**
     *
     * @param Module $module
     * @param String $string
     * @param String $source
     * @param String $language
     * @param String $sprintf
     * @param String $js
     * Function added by Anshul to insert the tabs in all available languages by reading the translations from translation files (Jan 2020)
     */
    public function getModuleTranslationByLanguage($module, $string, $source, $language, $sprintf = null, $js = false)
    {
        $modules = array();
        $langadm = array();
        $translations_merged = array();
        $name = $module instanceof Module ? $module->name : $module;
        
        if (!isset($translations_merged[$name]) && isset(Context::getContext()->language)) {
            $files_by_priority = array(
                _PS_MODULE_DIR_ . $name . '/translations/' . $language . '.php'
            );
            foreach ($files_by_priority as $file) {
                if (file_exists($file)) {
                    include($file);
                    /* No need to define $_MODULE as it is defined in the above included file. */
                    $modules = $_MODULE;
                    $translations_merged[$name] = true;
                }
            }
        }

        $string = preg_replace("/\\\*'/", "\'", $string);
        $key = md5($string);
        if ($modules == null) {
            if ($sprintf !== null) {
                $string = Translate::checkAndReplaceArgs($string, $sprintf);
            }

            return str_replace('"', '&quot;', $string);
        }
        $current_key = Tools::strtolower('<{' . $name . '}' . _THEME_NAME_ . '>' . $source) . '_' . $key;
        $default_key = Tools::strtolower('<{' . $name . '}prestashop>' . $source) . '_' . $key;
        if ('controller' == Tools::substr($source, -10, 10)) {
            $file = Tools::substr($source, 0, -10);
            $current_key_file = Tools::strtolower('<{' . $name . '}' . _THEME_NAME_ . '>' . $file) . '_' . $key;
            $default_key_file = Tools::strtolower('<{' . $name . '}prestashop>' . $file) . '_' . $key;
        }

        if (isset($current_key_file) && !empty($modules[$current_key_file])) {
            $ret = Tools::stripslashes($modules[$current_key_file]);
        } elseif (isset($default_key_file) && !empty($modules[$default_key_file])) {
            $ret = Tools::stripslashes($modules[$default_key_file]);
        } elseif (!empty($modules[$current_key])) {
            $ret = Tools::stripslashes($modules[$current_key]);
        } elseif (!empty($modules[$default_key])) {
            $ret = Tools::stripslashes($modules[$default_key]);
        // if translation was not found in module, look for it in AdminController or Helpers
        } elseif (!empty($langadm)) {
            $ret = Tools::stripslashes(Translate::getGenericAdminTranslation($string, $key, $langadm));
        } else {
            $ret = Tools::stripslashes($string);
        }

        if ($sprintf !== null) {
            $ret = Translate::checkAndReplaceArgs($ret, $sprintf);
        }

        if ($js) {
            $ret = addslashes($ret);
        } else {
            $ret = htmlspecialchars($ret, ENT_COMPAT, 'UTF-8');
        }
        return $ret;
    }
    
    /*
     * Added by Anshul to uninstall the admin tabs on 31/01/2020
     */
    protected function unInstallKbTabs()
    {
        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            $idTab = Tab::getIdFromClassName(self::PARENT_TAB_CLASS);
            if ($idTab != 0) {
                $tab = new Tab($idTab);
                if ($tab->delete()) {
                    $subMenuList = $this->adminSubMenus();
                    if (isset($subMenuList)) {
                        foreach ($subMenuList as $subList) {
                            $idTab = Tab::getIdFromClassName($subList['class_name']);
                            if ($idTab != 0) {
                                $tab = new Tab($idTab);
                                $tab->delete();
                            }
                        }
                    }
                }
            }
        } else {
            $parentTab = new Tab(Tab::getIdFromClassName(self::PARENT_TAB_CLASS));
            $parentTab->delete();

            $admin_menus = $this->adminSubMenus();

            foreach ($admin_menus as $menu) {
                $sql = 'SELECT id_tab FROM `' . _DB_PREFIX_ . 'tab` WHERE class_name = "' . pSQL($menu['class_name']) . '" 
                    AND module = "' . pSQL($this->name) . '"';
                $id_tab = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                $tab = new Tab($id_tab);
                $tab->delete();
            }
        }
        return true;
    }
    
    /**
     *
     * @return array
     */
    public function adminSubMenus()
    {
        $subMenu = array(
            array(
                'class_name' => 'AdminSuperSetting',
                'name' => $this->l('General Settings'),
                'active' => 1,
            ),
            array(
                'class_name' => 'AdminAbandonedCheckout',
                'name' => $this->l('Abandoned Checkout Statistics'),
                'active' => 1,
            ),
            array(
                'class_name' => 'AdminCheckoutBehavior',
                'name' => $this->l('Checkout behaviour Report'),
                'active' => 1,
            )
            );

        return $subMenu;
    }

    public function getContent()
    {
        if (Tools::isSubmit('downloadFile')) {
            $this->downloadFile(Tools::getValue('id_field'));
        }
        if (!class_exists('KbMailChimp')) {
            include_once _PS_MODULE_DIR_ . 'supercheckout/libraries/mailchimpl library.php';
        }
        ini_set('max_input_vars', 2000);
        if (Tools::isSubmit('ajax')) {
            if (Tools::isSubmit('method')) {
                switch (Tools::getValue('method')) {
                    case 'getMailChimpList':
                        $this->getMailchimpLists(trim(Tools::getValue('key')));
                        break;
                    case 'getSendinBlueList':
                        $this->getSendinBlueList(trim(Tools::getValue('key')));
                        break;
                    case 'getklaviyoList':
                        $this->getKlaviyoList(trim(Tools::getValue('key')));
                        break;
                    //Called getMailchimpLists
                    case 'removeFile':
                        $this->removeFile(trim(Tools::getValue('id')));
                        break;
                }
            } elseif (Tools::isSubmit('custom_fields_action')) {
                $json = array();
                switch (Tools::getValue('custom_fields_action')) {
                    case 'deleteCustomFieldRow':
                        $id_velsof_supercheckout_custom_fields = Tools::getValue('id_velsof_supercheckout_custom_fields');
                        $this->deleteWholeRowData($id_velsof_supercheckout_custom_fields);
                        //Called deleteWholeRowData
                        // no break
                    case 'addCustomFieldForm':
                        $custom_field_form_values = Tools::getValue('custom_fields');
                        $id_velsof_supercheckout_custom_fields = $this->addNewCustomField($custom_field_form_values);
                        $result_custom_fields_details = $this->getRowDataCurrentLang($id_velsof_supercheckout_custom_fields);
                        $json['response'] = $result_custom_fields_details[0];
                        break;
                    case 'editCustomFieldForm':
                        $custom_field_form_values = Tools::getValue('edit_custom_fields');
                        $id_velsof_supercheckout_custom_fields = $this->editCustomField($custom_field_form_values);
                        $result_custom_fields_details = $this->getRowDataCurrentLang($id_velsof_supercheckout_custom_fields);
                        $json['response'] = $result_custom_fields_details[0];
                        break;
                    case 'displayEditCustomFieldForm':
                        $id_velsof_supercheckout_custom_fields = Tools::getValue('id');
                        $show_option_field = 0;
                        $result_custom_fields_details_basic = $this->getFieldDetailsBasic($id_velsof_supercheckout_custom_fields);

                        // Setting variable value so that the options field can be showed or hidden by default
                        if ($result_custom_fields_details_basic[0]['type'] == 'selectbox' || $result_custom_fields_details_basic[0]['type'] == 'radio' || $result_custom_fields_details_basic[0]['type'] == 'checkbox') {
                            $show_option_field = 1;
                        }

                        $array_fields_lang = $this->getFieldLangs($id_velsof_supercheckout_custom_fields);
                        $array_fields_options = $this->getFieldOptions($id_velsof_supercheckout_custom_fields);

                        $this->context->smarty->assign('id_velsof_supercheckout_custom_fields', $id_velsof_supercheckout_custom_fields);
                        $this->context->smarty->assign('custom_field_basic_details', $result_custom_fields_details_basic[0]);
                        $this->context->smarty->assign('custom_field_lang_details', $array_fields_lang);
                        $this->context->smarty->assign('custom_field_option_details', $array_fields_options);
                        $this->context->smarty->assign('language_current', $this->context->language->id);
                        $this->context->smarty->assign('languages', Language::getLanguages(false));
                        $this->context->smarty->assign('show_option_field', $show_option_field);
                        $this->context->smarty->assign('module_dir_url', _MODULE_DIR_);
                        $json['response'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'supercheckout/views/templates/admin/edit_form_custom_fields.tpl');
                        break;
                }
                echo json_encode($json);
                die;
            } elseif (Tools::isSubmit('gdpr_privacy_action')) {
                // <editor-fold defaultstate="collapsed" desc="GDPR change">
                $json = array();
                switch (Tools::getValue('gdpr_privacy_action')) {
                    case 'addNewPrivacyPolicy':
                        $privacy_form_values = Tools::getValue('gdpr_policy_fields');
                        $id_velsof_supercheckout_gdpr_policy_fields = $this->addNewGDPRPolicy($privacy_form_values);
                        $result_gdpr_policy_details = $this->getPolicyRowDataCurrentLang($id_velsof_supercheckout_gdpr_policy_fields);
                        $json['response'] = $result_gdpr_policy_details[0];
                        break;
                    case 'deletePrivacyPolicyRow':
                        $policy_id = Tools::getValue('policy_id');
                        $this->deletePolicyRowData($policy_id);
                        break;
                    case 'displayEditGDPRPolicyForm':
                        $policy_id = Tools::getValue('id');
                        $show_option_field = 0;
                        $result_custom_fields_details_basic = $this->getPolicyRowDataCurrentLang($policy_id);
                        $array_fields_lang = $this->getPolicyLangs($policy_id);
                        $this->context->smarty->assign('policy_id', $policy_id);
                        $this->context->smarty->assign('gdpr_policy_basic_details', $result_custom_fields_details_basic[0]);
                        $this->context->smarty->assign('gdpr_policy_lang_details', $array_fields_lang);
                        $this->context->smarty->assign('language_current', $this->context->language->id);
                        $this->context->smarty->assign('languages', Language::getLanguages(false));
                        $this->context->smarty->assign('module_dir_url', _MODULE_DIR_);
                        /* Start - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field */
                        $available_cms = array();
                        $available_cms = CMS::listCms($this->context->language->id);
                        $this->context->smarty->assign('available_cms', $available_cms);
                        /* End - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field */
                        $json['response'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'supercheckout/views/templates/admin/edit_form_gdpr_policy.tpl');
                        break;
                    case 'editPrivacyPolicyForm':
                        $policy_form_values = Tools::getValue('edit_gdpr_policy');
                        $id_velsof_supercheckout_gdpr_policy_fields = $this->updatePolicyDetails($policy_form_values);
                        $result_gdpr_policy_details = $this->getPolicyRowDataCurrentLang($id_velsof_supercheckout_gdpr_policy_fields);
                        $json['response'] = $result_gdpr_policy_details[0];
                        break;
                    case 'displayFilteredGDPRCustomerData':
                        $search_data = Tools::getValue('searchData');
                        $orders_consent = $this->getGDPRFilteredCustomerData($search_data);
                        $this->context->smarty->assign('customer_controller', $this->context->link->getAdminLink('AdminCustomers'));
                        $this->context->smarty->assign('order_controller', $this->context->link->getAdminLink('AdminOrders'));
                        $this->context->smarty->assign('orders_consent', $orders_consent);
                        $json['response'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'supercheckout/views/templates/admin/gdpr_filter_customer_data.tpl');
                        break;
                }
                echo json_encode($json);
                die;
                // </editor-fold>
            } elseif (Tools::isSubmit('customer_profile_action')) {
                $json = array();
                switch (Tools::getValue('customer_profile_action')) {
                    case 'addCustomerProfileForm':
                        $post_data = $this->processCustomerProfileData(Tools::getValue('customer_profiles'));
                        if (isset($post_data['id_profile']) && $post_data['id_profile'] == 0) {
                            $profile_data = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
                            if (empty($profile_data)) {
                                $post_data['id_profile'] = 1;
                            } else {
                                $post_data['id_profile'] = count($profile_data) + 1;
                            }
                            $profile_data[] = $post_data;
                        }
                        $json['response'] = Configuration::updateValue('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE', json_encode($profile_data));
                        if ($json['response']) {
                            $json['id_profile'] = $post_data['id_profile'];
                            $json['active'] = $post_data['active'];
                            $json['field_label'] = $post_data['field_label'][$this->context->language->id];
                        }
                        break;
                    case 'deleteCustomerProfileRow':
                        $id_profile = (int) Tools::getValue('id_profile');
                        $existing_profile_datas = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
                        if (isset($existing_profile_datas) && !empty($existing_profile_datas)) {
                            foreach ($existing_profile_datas as $key => $data) {
                                if ($data['id_profile'] == $id_profile) {
                                    unset($existing_profile_datas[$key]);
                                }
                            }
                        }
                        $json['response'] = Configuration::updateValue('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE', json_encode($existing_profile_datas));
                        break;
                    case 'displayEditCustomerProfileForm':
                        $id_profile = (int) Tools::getValue('id_profile');
                        $existing_profile_datas = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
                        $edit_profile_data = array();
                        if (isset($existing_profile_datas) && !empty($existing_profile_datas)) {
                            foreach ($existing_profile_datas as $key => $data) {
                                if ($data['id_profile'] == $id_profile) {
                                    $edit_profile_data = $existing_profile_datas[$key];
                                    break;
                                }
                            }
                        }
                        $query = 'SELECT id_velsof_supercheckout_custom_fields, field_label FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
                        $query = $query . 'WHERE id_lang = ' . (int) $this->context->language->id;

                        $result_custom_fields_details = Db::getInstance()->executeS($query);
                        $this->context->smarty->assign('profile_data', $edit_profile_data);
                        $this->context->smarty->assign('custom_fields_details', $result_custom_fields_details);
                        $this->context->smarty->assign('language_current', $this->context->language->id);
                        $this->context->smarty->assign('languages', Language::getLanguages(false));
                        $this->context->smarty->assign('module_dir_url', _MODULE_DIR_);
                        $json['response'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'supercheckout/views/templates/admin/edit_form_customer_profile_fields.tpl');
                        break;
                    case 'editCustomerProfileForm':
                        $post_data = $this->processCustomerProfileData(Tools::getValue('edit_customer_profiles'));
                        $existing_profile_datas = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
                        if (isset($existing_profile_datas) && !empty($existing_profile_datas)) {
                            foreach ($existing_profile_datas as $key => $data) {
                                if ($data['id_profile'] == $post_data['id_profile']) {
                                    $existing_profile_datas[$key] = $post_data;
                                    break;
                                }
                            }
                        }
                        $json['response'] = Configuration::updateValue('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE', json_encode($existing_profile_datas));
                        if ($json['response']) {
                            $json['id_profile'] = $post_data['id_profile'];
                            $json['active'] = $post_data['active'];
                            $json['field_label'] = $post_data['field_label'][$this->context->language->id];
                        }
                        break;
                }
                echo json_encode($json);
                die;
            }
        }
        // <editor-fold defaultstate="collapsed" desc="GDPR Change">
        if (Tools::isSubmit('velocity_kbgdpr_install')) {
            $this->installGDPRtableNdHook();
        }
        // </editor-fold>
        $this->addBackOfficeMedia();

        $browser = ($_SERVER['HTTP_USER_AGENT']);
        $is_ie7 = false;
        if (preg_match('/(?i)msie [1-7]/', $browser)) {
            $is_ie7 = true;
        }

        $output = null;

        $supercheckout_config = new SupercheckoutConfiguration();

        if (Tools::isSubmit($this->submit_action . $this->name)) {
            $post_data = $supercheckout_config->processPostData(Tools::getValue('velocity_supercheckout'));
            $temp_default = $this->getDefaultSettings();
            $post_data['plugin_id'] = $temp_default['plugin_id'];
            $post_data['version'] = $temp_default['version'];

            $post_data['fb_login']['app_id'] = trim($post_data['fb_login']['app_id']);
            $post_data['fb_login']['app_secret'] = trim($post_data['fb_login']['app_secret']);

            $post_data['google_login']['client_id'] = trim($post_data['google_login']['client_id']);
            $post_data['google_login']['app_secret'] = trim($post_data['google_login']['app_secret']);
            $post_data['paypal_login']['client_id'] = trim($post_data['paypal_login']['client_id']);
            $post_data['paypal_login']['client_secret'] = trim($post_data['paypal_login']['client_secret']);
            $post_data['google_auto_address']['api_key'] = trim($post_data['google_auto_address']['api_key']);
            $key_persist_setting = array(
                'fb_login' => array(
                    'app_id' => $post_data['fb_login']['app_id'],
                    'app_secret' => $post_data['fb_login']['app_secret']
                ),
                'google_login' => array(
                    'client_id' => $post_data['google_login']['client_id'],
                    'app_secret' => $post_data['google_login']['app_secret'],
                ),
                'paypal_login' => array(
                    'client_id' => $post_data['paypal_login']['client_id'],
                    'client_secret' => $post_data['paypal_login']['client_secret'],
                ),
                'mailchimp' => array(
                    'api' => $post_data['mailchimp']['api'],
                    'list' => $post_data['mailchimp']['list'],
                ),
                'google_auto_address' => array(
                    'api_key' => $post_data['google_auto_address']['api_key'],
                )
            );

            if (isset($post_data['enable_guest_checkout']) && $post_data['enable_guest_checkout'] == 1) {
                Configuration::updateGlobalValue('PS_GUEST_CHECKOUT_ENABLED', '1');
            }

            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_KEYS', json_encode($key_persist_setting));
            $post_data['custom_css'] = urlencode($post_data['custom_css']);
            $post_data['custom_js'] = urlencode($post_data['custom_js']);
//            print_r($post_data);
//            die();
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT', json_encode($post_data));
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_CSS', json_encode($post_data['custom_css']));
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_JS', json_encode($post_data['custom_js']));
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_BUTTON', json_encode($post_data['customizer']));
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_HFHTML', json_encode($post_data['html_value']));
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_EXTRAHTML', json_encode($post_data['design']['html']));
            if (count($this->custom_errors) > 0) {
                $output .= $this->displayError(implode('<br>', $this->custom_errors));
            } else {
                $output .= $this->displayConfirmation($this->l('Settings has been updated successfully'));
            }
            $payment_post_data = (Tools::getValue('velocity_supercheckout_payment'));

            $payment_error = '';
            foreach (PaymentModule::getInstalledPaymentModules() as $paymethod) {
                $id = $paymethod['id_module'];
                if (isset($_FILES['velocity_supercheckout_payment']['size']['payment_method'][$id]['logo']['name']) && $_FILES['velocity_supercheckout_payment']['size']['payment_method'][$id]['logo']['name'] == 0) {
                    $payment_post_data['payment_method'][$id]['logo']['title'] == '';
                } else {
                    if (isset($_FILES['velocity_supercheckout_payment']['size']['payment_method'][$id]['logo']['name'])) {
                        $method_file = $_FILES['velocity_supercheckout_payment'];
                        $allowed_exts = array('gif', 'jpeg', 'jpg', 'png', 'JPG', 'PNG', 'GIF', 'JPEG');
                        $extension = explode('.', $method_file['name']['payment_method'][$id]['logo']['name']);
                        $extension = end($extension);
                        $extension = trim($extension);
                        $img_size = $method_file['size']['payment_method'][$id]['logo']['name'];
                        if (($img_size < 300000) && in_array($extension, $allowed_exts)) {
                            $error = $method_file['error']['payment_method'][$id]['logo']['name'];
                            if ($error > 0) {
                                $image_error = $this->l('Error in image of');
                                $payment_error .= '*'." " . $image_error. " " . $paymethod['name'] . '<br/>';
                            } else {
                                $mask = _PS_MODULE_DIR_ . 'supercheckout/views/img/admin/uploads/paymethod'
                                    . trim($id) . '.*';
                                $matches = glob($mask);
                                $dest = _PS_MODULE_DIR_ . 'supercheckout/views/img/admin/uploads/paymethod'
                                    . trim($id) . '.' . $extension;
                                if (count($matches) > 0) {
                                    array_map('unlink', $matches);
                                }
                                if (move_uploaded_file(
                                    $method_file['tmp_name']['payment_method'][$id]['logo']['name'],
                                    $dest
                                )
                                ) {
                                    $payment_post_data['payment_method'][$id]['logo']['title'] = 'paymethod'
                                        . trim($id) . '.' . $extension;
                                } else {
                                    $image_error = $this->l('Error in uploading the image of');
                                    $payment_error .= '*'." " . $image_error. " " . $paymethod['name'] . '<br/>';
                                }
                                if (!version_compare(_PS_VERSION_, '1.6.0.1', '<')) {
                                    Tools::chmodr(_PS_MODULE_DIR_ . 'supercheckout/views/img/uploads', 0755);
                                }
                            }
                        } else {
                            $image_error = $this->l('Error Uploaded file is not a  image');
                            $payment_error .= '*'." " . $image_error. " " . $paymethod['name'] . '<br/>';
                        }
                    }
                }
            }

            $carriers = Carrier::getCarriers(
                $this->context->language->id,
                true,
                false,
                false,
                null,
                Carrier::ALL_CARRIERS
            );
            foreach ($carriers as $deliverymethod) {
                $id = $deliverymethod['id_carrier'];
                $method_file = $_FILES['velocity_supercheckout_payment'];
                if ($method_file['size']['delivery_method'][$id]['logo']['name'] == 0) {
                    $payment_post_data['delivery_method'][$id]['logo']['title'] == '';
                } else {
                    $allowed_exts = array('gif', 'jpeg', 'jpg', 'png', 'JPG', 'PNG', 'GIF', 'JPEG');
                    $extension = explode(
                        '.',
                        $_FILES['velocity_supercheckout_payment']['name']['delivery_method'][$id]['logo']['name']
                    );
                    $extension = end($extension);
                    $extension = trim($extension);
                    if (($method_file['size']['delivery_method'][$id]['logo']['name'] < 300000)
                        && in_array($extension, $allowed_exts)
                    ) {
                        if ($method_file['error']['delivery_method'][$id]['logo']['name'] > 0) {
                            $payment_error .= '* Error in image of ' . $deliverymethod['name'] . '<br/>';
                        } else {
                            $mask = _PS_MODULE_DIR_ . 'supercheckout/views/img/admin/uploads/deliverymethod'
                                . trim($id) . '.*';
                            $matches = glob($mask);
                            if (count($matches) > 0) {
                                array_map('unlink', $matches);
                            }
                            $dest = _PS_MODULE_DIR_ . 'supercheckout/views/img/admin/uploads/deliverymethod'
                                . trim($id) . '.' . $extension;
                            if (move_uploaded_file(
                                $method_file['tmp_name']['delivery_method'][$id]['logo']['name'],
                                $dest
                            )
                            ) {
                                $payment_post_data['delivery_method'][$id]['logo']['title'] = 'deliverymethod'
                                    . trim($id) . '.' . $extension;
                            } else {
                                $payment_error .= '* Error in uploading the image of '
                                    . $deliverymethod['name'] . '<br/>';
                            }
                            if (!version_compare(_PS_VERSION_, '1.6.0.1', '<')) {
                                Tools::chmodr(_PS_MODULE_DIR_ . 'supercheckout/views/img/uploads', 0755);
                            }
                        }
                    } else {
                        $file_error_msg = $this->l('Error Uploaded file is not an image');
                        $payment_error .= '*'. " " .$file_error_msg ." ". $deliverymethod['name']
                            . '<br/>';
                    }
                }
            }
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_DATA', json_encode($payment_post_data));
            if ($payment_error != '') {
                $output .= $this->displayError($payment_error);
            }
        }

        if (!Configuration::get('VELOCITY_SUPERCHECKOUT') || Configuration::get('VELOCITY_SUPERCHECKOUT') == '') {
            $this->supercheckout_settings = $this->getDefaultSettings();
        } else {
            $this->supercheckout_settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
        }

        if (Configuration::get('VELOCITY_SUPERCHECKOUT_CSS')
            || Configuration::get('VELOCITY_SUPERCHECKOUT_CSS') != ''
        ) {
            $this->supercheckout_settings['custom_css'] = json_decode(
                Configuration::get('VELOCITY_SUPERCHECKOUT_CSS'), true
            );
            $this->supercheckout_settings['custom_css'] = urldecode($this->supercheckout_settings['custom_css']);
        }

        if (Configuration::get('VELOCITY_SUPERCHECKOUT_JS') || Configuration::get('VELOCITY_SUPERCHECKOUT_JS') != '') {
            $this->supercheckout_settings['custom_js'] = json_decode(
                Configuration::get('VELOCITY_SUPERCHECKOUT_JS'), true
            );
            $this->supercheckout_settings['custom_js'] = urldecode($this->supercheckout_settings['custom_js']);
        }
        if (Configuration::get('VELOCITY_SUPERCHECKOUT_KEYS')
            || Configuration::get('VELOCITY_SUPERCHECKOUT_KEYS') != ''
        ) {
            $key_details = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_KEYS'), true);
            $this->supercheckout_settings['fb_login']['app_id'] = $key_details['fb_login']['app_id'];
            $this->supercheckout_settings['fb_login']['app_secret'] = $key_details['fb_login']['app_secret'];
            $this->supercheckout_settings['google_login']['client_id'] = $key_details['google_login']['client_id'];
            $this->supercheckout_settings['google_login']['app_secret'] = $key_details['google_login']['app_secret'];
            $this->supercheckout_settings['paypal_login']['client_id'] = $key_details['paypal_login']['client_id'];
            $this->supercheckout_settings['paypal_login']['client_secret'] = $key_details['paypal_login']['client_secret'];
            $this->supercheckout_settings['mailchimp']['api'] = $key_details['mailchimp']['api'];
            $this->supercheckout_settings['mailchimp']['list'] = $key_details['mailchimp']['list'];
            $this->supercheckout_settings['google_auto_address']['api_key'] = $key_details['google_auto_address']['api_key'];
        } else {
            $key_settings = array(
                'fb_login' => array(
                    'app_id' => '',
                    'app_secret' => ''
                ),
                'google_login' => array(
                    'client_id' => '',
                    'app_secret' => ''
                ),
                'paypal_login' => array(
                    'client_id' => '',
                    'client_secret' => ''
                ),
                'mailchimp' => array(
                    'api' => '',
                    'key' => '',
                    'list' => ''
                ),
                'google_auto_address' => array(
                    'api_key' => ''
                )
            );
            Configuration::updateValue('VELOCITY_SUPERCHECKOUT_KEYS', json_encode($key_settings));
        }

        if (!Configuration::get('VELOCITY_SUPERCHECKOUT_DATA')
            || Configuration::get('VELOCITY_SUPERCHECKOUT_DATA') == ''
        ) {
            $paymentdata = array();
        } else {
            $paymentdata = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_DATA'), true);
        }
        $custombutton = array(
                'button_color' => '5CB85C',
                'button_border_color' => '5CB85C',
                'button_text_color' => 'F9F9F9',
                'border_bottom_color' => '5CB85C',
                'my_account_button_color' => '4862A3',
                'logout_button_color' => 'D9534F',
                'update_address_button_color' => 'F0AD4E',
                'remove_address_button_color' => 'D9534F',
                'progressbar_button_color' => '5CB85C',
                'theme_common_color' => '286090',
                'product_name_color' => 'FF6600',
            );
        $this->supercheckout_settings['customizer']['button_border_color'] = $custombutton['button_border_color'];
        $this->supercheckout_settings['customizer']['button_color'] = $custombutton['button_color'];
        $this->supercheckout_settings['customizer']['button_text_color'] = $custombutton['button_text_color'];
        $this->supercheckout_settings['customizer']['border_bottom_color'] = $custombutton['border_bottom_color'];
        $this->supercheckout_settings['customizer']['my_account_button_color'] = $custombutton['my_account_button_color'];
        $this->supercheckout_settings['customizer']['logout_button_color'] = $custombutton['logout_button_color'];
        $this->supercheckout_settings['customizer']['update_address_button_color'] = $custombutton['update_address_button_color'];
        $this->supercheckout_settings['customizer']['remove_address_button_color'] = $custombutton['remove_address_button_color'];
        $this->supercheckout_settings['customizer']['progressbar_button_color'] = $custombutton['progressbar_button_color'];
        $this->supercheckout_settings['customizer']['theme_common_color'] = $custombutton['theme_common_color'];
        $this->supercheckout_settings['customizer']['product_name_color'] = $custombutton['product_name_color'];
        
        
        
        
        
        if (!Configuration::get('VELOCITY_SUPERCHECKOUT_HFHTML')
            || Configuration::get('VELOCITY_SUPERCHECKOUT_HFHTML') == ''
        ) {
            $headerfooterhtml = array('header' => '', 'footer' => '');
        } else {
            $headerfooterhtml = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_HFHTML'), true);
        }

        $this->supercheckout_settings['html_value']['header'] = $headerfooterhtml['header'];
        $this->supercheckout_settings['html_value']['footer'] = $headerfooterhtml['footer'];

        //Decode Extra Html
        $this->supercheckout_settings['html_value']['header'] = html_entity_decode(
            $this->supercheckout_settings['html_value']['header']
        );
        $this->supercheckout_settings['html_value']['footer'] = html_entity_decode(
            $this->supercheckout_settings['html_value']['footer']
        );

        if (!Configuration::get('VELOCITY_SUPERCHECKOUT_EXTRAHTML')
            || Configuration::get('VELOCITY_SUPERCHECKOUT_EXTRAHTML') == ''
        ) {
            $extrahtml = array(
                '0_0' => array(
                    '1_column' => array('column' => 0, 'row' => 7, 'column-inside' => 1),
                    '2_column' => array('column' => 2, 'row' => 1, 'column-inside' => 4),
                    '3_column' => array('column' => 3, 'row' => 4, 'column-inside' => 1),
                    'value' => ''
                )
            );
        } else {
            $extrahtml = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_EXTRAHTML'), true);
        }

        foreach ($extrahtml as $key => $value) {
            $extrahtml_value = $extrahtml[$key]['value'];
            if (isset($this->supercheckout_settings['design']['html'][$key])) {
                $this->supercheckout_settings['design']['html'][$key]['value'] = $extrahtml_value;
            } else {
                $this->supercheckout_settings['design']['html'][$key]['1_column'] = $extrahtml[$key]['1_column'];
                $this->supercheckout_settings['design']['html'][$key]['2_column'] = $extrahtml[$key]['2_column'];
                $this->supercheckout_settings['design']['html'][$key]['3_column'] = $extrahtml[$key]['3_column'];
                $this->supercheckout_settings['design']['html'][$key]['value'] = $extrahtml[$key]['value'];
            }
        }

        foreach ($this->supercheckout_settings['design']['html'] as $key => $value) {
            $tmp = $value;
            $html_value = $this->supercheckout_settings['design']['html'][$key]['value'];
            $this->supercheckout_settings['design']['html'][$key]['value'] = html_entity_decode($html_value);
            unset($tmp);
        }

        if (isset($_REQUEST['velsof_layout']) && in_array($_REQUEST['velsof_layout'], array(1, 2, 3))) {
            $layout = $_REQUEST['velsof_layout'];
        } else {
            $layout = $this->supercheckout_settings['layout'];
        }

        $payments = array();
        foreach (PaymentModule::getInstalledPaymentModules() as $pay_method) {
            if (file_exists(_PS_MODULE_DIR_ . $pay_method['name'] . '/' . $pay_method['name'] . '.php')) {
                require_once(_PS_MODULE_DIR_ . $pay_method['name'] . '/' . $pay_method['name'] . '.php');
                if (class_exists($pay_method['name'], false)) {
                    $temp = array();
                    $temp['id_module'] = $pay_method['id_module'];
                    $temp['name'] = $pay_method['name'];
                    $pay_temp = new $pay_method['name'];
                    $temp['display_name'] = $pay_temp->displayName;
                    $payments[] = $temp;
                }
            }
        }
        if ($this->checkMobileLoginModuleActive()) {
            $this->supercheckout_settings['mobile_login_active'] = true;
        }
        if (_PS_VERSION_ < '1.6.0') {
            $lang_img_dir = _PS_IMG_DIR_ . 'l/';
        } else {
            $lang_img_dir = _PS_LANG_IMG_DIR_;
        }
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            $ps_base_url = _PS_BASE_URL_SSL_;
            $manual_dir = _PS_BASE_URL_SSL_ . __PS_BASE_URI__;
        } else {
            $ps_base_url = _PS_BASE_URL_;
            $manual_dir = _PS_BASE_URL_ . __PS_BASE_URI__;
        }
        
        $this->_clearCache('supercheckout.tpl');
        $admin_action_url = 'index.php?controller=AdminModules&token='
            . Tools::getAdminTokenLite('AdminModules') . '&configure=' . $this->name;
        $highlighted_fields = array(
            'company',
            'address2',
            'postcode',
            'other',
            'phone',
            'phone_mobile',
            'vat_number',
            'dni'
        );
        if (Configuration::get('VELOCITY_SUPERCHECKOUT_BUTTON')
            || Configuration::get('VELOCITY_SUPERCHECKOUT_BUTTON') != ''
        ) {
            $data = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
            $this->supercheckout_settings['customizer']['button_color'] = $data['customizer']['button_color'];
            $this->supercheckout_settings['customizer']['button_border_color'] = $data['customizer']['button_border_color'];
            $this->supercheckout_settings['customizer']['button_text_color'] = $data['customizer']['button_text_color'];
            $this->supercheckout_settings['customizer']['border_bottom_color'] = $data['customizer']['border_bottom_color'];
            $this->supercheckout_settings['customizer']['my_account_button_color'] = $data['customizer']['my_account_button_color'];
            $this->supercheckout_settings['customizer']['logout_button_color'] = $data['customizer']['logout_button_color'];
            $this->supercheckout_settings['customizer']['update_address_button_color'] = $data['customizer']['update_address_button_color'];
            $this->supercheckout_settings['customizer']['remove_address_button_color'] = $data['customizer']['remove_address_button_color'];
            $this->supercheckout_settings['customizer']['progressbar_button_color'] = $data['customizer']['progressbar_button_color'];
            $this->supercheckout_settings['customizer']['theme_common_color'] = $data['customizer']['theme_common_color'];
            $this->supercheckout_settings['customizer']['product_name_color'] = $data['customizer']['product_name_color'];
        }
        
        /* Start Code Added By Priyanshu on 11-Feb-2021 to implement the Total Price Display functionality */
        $total_price_method_arr = array(
            array(
                'id_method' => 0,
                'display_name' => 'Default Prestshop Price Setting'),
            array(
                'id_method' => 1,
                'display_name' => 'Tax Inclusive Price'),
            array(
                'id_method' => 2,
                'display_name' => 'Tax Exclusive Price'),
            array(
                'id_method' => 3,
                'display_name' => 'Both Inclusive and Exclusive Prive')
        );
        /* End Code Added By Priyanshu on 11-Feb-2021 to implement the Total Price Display functionality */
        $default = $this->getDefaultSettings();
        if (!isset($this->supercheckout_settings['column_width'])) {
            $this->supercheckout_settings['layout'] = 3;
            $this->supercheckout_settings['column_width'] = $default['column_width']; 
        }
        $this->smarty->assign(array(
            'root_path' => $this->_path,
            'action' => $admin_action_url,
            'cancel_action' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            'velocity_supercheckout' => $this->supercheckout_settings,
            'highlighted_fields' => $highlighted_fields,
            'layout' => $layout,
            'manual_dir' => $manual_dir,
            'domain' => $_SERVER['HTTP_HOST'],
            'payment_methods' => $payments,
            'total_price_method_arr' => $total_price_method_arr,
            'carriers' => Carrier::getCarriers(
                $this->context->language->id,
                true,
                false,
                false,
                null,
                Carrier::ALL_CARRIERS
            ),
            'submit_action' => $this->submit_action . $this->name,
            'IE7' => $is_ie7,
            'guest_is_enable_from_system' => Configuration::get('PS_GUEST_CHECKOUT_ENABLED'),
            'velocity_supercheckout_payment' => $paymentdata,
            'root_dir' => _PS_ROOT_DIR_,
            'languages' => Language::getLanguages(false),
            'img_lang_dir' => $ps_base_url . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', $lang_img_dir),
            'module_url' => $this->context->link->getModuleLink(
                'supercheckout',
                'supercheckout',
                array(),
                (bool) Configuration::get('PS_SSL_ENABLED')
            ),
            'front_root_url' => $this->getRootUrl()
        ));

        //Added to assign current version of prestashop in a new variable
        if (version_compare(_PS_VERSION_, '1.6.0.1', '<')) {
            $this->smarty->assign('ps_version', 15);
        } else {
            $this->smarty->assign('ps_version', 16);
        }

        // Assigning the variables used for Custom Fields functionality

        $current_language_id = $this->context->language->id;

        // Getting the details of custom fields
        // SELECT * FROM velsof_supercheckout_custom_fields cf
        // JOIN velsof_supercheckout_custom_fields_lang cfl
        // ON cf.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields
        // WHERE id_lang = 1
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields cf ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
        $query = $query . 'ON cf.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields ';
        /* Start - Code modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team */
        $query = $query . 'WHERE id_lang = '.(int)$current_language_id;
        /* End - Code modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team */

        $result_custom_fields_details = Db::getInstance()->executeS($query);
        /* Start - Code Modified by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field' */
        foreach ($result_custom_fields_details as $key => $field_details) {
            $position_text = ucwords(str_replace("_", " ", $field_details['position']));
            $result_custom_fields_details[$key]['position'] = $this->l($position_text);
            $result_custom_fields_details[$key]['type'] = $this->getCustomFieldsTypeTranslatedText($field_details['type']);
        }
        /* End - Code Modified by Raghu on 22-Aug-2017 for fixing 'Custom Fields Blocks Translations issues while editing/adding a custom field' */
        $this->smarty->assign('language_current', $current_language_id);
        $this->smarty->assign('custom_fields_details', $result_custom_fields_details);
        $this->context->smarty->assign('module_dir_url', _MODULE_DIR_);
        /*
         * Added a new variable for current domain name to be used in the google authorized javascript origin URL
         * @author Prvind Panday
         * @date 30-01-2023
         * @commenter Prvind Panday
         */
        if ($this->checkSecureUrl()) {
            $current_domain = _PS_BASE_URL_SSL_;
        } else {
            $current_domain = _PS_BASE_URL_;
        }
        $this->smarty->assign('current_domain', $current_domain);
        //customer profile data start
        $existing_profile_datas = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
        $customer_profile_data = array();
        if (isset($existing_profile_datas) && !empty($existing_profile_datas)) {
            foreach ($existing_profile_datas as $key => $data) {
                $profile_data = array();
                $profile_data['id_profile'] = $data['id_profile'];
                $profile_data['active'] = $data['active'];
                $profile_data['field_label'] = $data['field_label'][$this->context->language->id];
                $customer_profile_data[] = $profile_data;
            }
        }
        $this->smarty->assign('customer_profile_datas', $customer_profile_data);
        //customer profile data end
        
        /* Start - Code Modified by Priyanshu on 14-June-2019 to fix the issue of Privacy Policy URL field */
        
        $available_cms = array();
        $available_cms = CMS::listCms($this->context->language->id);
        
        $this->context->smarty->assign('available_cms', $available_cms);

        /* End - Code Modified by Priyanshu on 14-June-2019  to fix the issue of Privacy Policy URL field */

        // <editor-fold defaultstate="collapsed" desc="GDPR Change">
        $gdpr_policy_details = $this->getAllGDPRPolicyDetails();
        $this->smarty->assign('gdpr_policy_details', $gdpr_policy_details);
        $this->context->smarty->assign('gdpr_tpl_dir', _PS_MODULE_DIR_ .'supercheckout/views/templates/admin/admin_gdpr_policy.tpl');
        // </editor-fold>
        // Changes done by kanishka kannoujia to show countries for free shipping banner
        $banner_countries = array();
        if (isset($this->supercheckout_settings['banner_country'])) {
            $banner_countries = $this->supercheckout_settings['banner_country'];
        }
        
        $country = new Country();
        $active_countries = $country->getCountries($this->context->language->id, true, false, false);
        $this->context->smarty->assign('active_countries', $active_countries);
        $this->context->smarty->assign('banner_countries', $banner_countries);
        // Changes done by kanishka kannoujia to show countries for free shipping banner

        $output .= $this->display(__FILE__, 'views/templates/admin/supercheckout.tpl');
        return $output;
    }
    
    public function checkMobileLoginModuleActive()
    {
        if (Module::isInstalled('kbmobilelogin') && Module::isEnabled('kbmobilelogin')) {
            $mobileLoginSettings = json_decode(Configuration::get('KB_MOBILE_LOGIN'), true);
            if ($mobileLoginSettings['enable']) {
                return true;
            }
        }
        return false;
    }

    /*
     * Function Added by Raghu on 22-Aug-2017 for fixing 'Custom Fields type translations' issue
     */
    private function getCustomFieldsTypeTranslatedText($type_value)
    {
        $final_txt = '';
        switch ($type_value) {
            case 'textbox':
                $final_txt = $this->l('Text Box');
                break;
            case 'selectbox':
                $final_txt = $this->l('Select Box');
                break;
            case 'textarea':
                $final_txt = $this->l('Text Area');
                break;
            case 'radio':
                $final_txt = $this->l('Radio Buttons');
                break;
            case 'checkbox':
                $final_txt = $this->l('Check Boxes');
                break;
            case 'file':
                $final_txt = $this->l('File');
                break;
            case 'date':
                $final_txt = $this->l('Date');
                break;
        }
        return $final_txt;
    }

    /*
     * Add css and javascript
     */

    protected function addBackOfficeMedia()
    {
        //CSS files
        $this->context->controller->addCSS($this->_path . 'views/css/supercheckout.css');
        $this->context->controller->addCSS($this->_path . 'views/css/bootstrap.css');
        $this->context->controller->addCSS($this->_path . 'views/css/responsive.css');
        $this->context->controller->addCSS($this->_path . 'views/css/jquery-ui/jquery-ui.min.css');
        $this->context->controller->addCSS($this->_path . 'views/css/fonts/glyphicons/glyphicons_regular.css');
        $this->context->controller->addCSS($this->_path . 'views/css/fonts/font-awesome/font-awesome.min.css');
        $this->context->controller->addCSS($this->_path . 'views/css/pixelmatrix-uniform/uniform.default.css');
        $this->context->controller->addCSS($this->_path . 'views/css/bootstrap-switch/bootstrap-switch.css');
        $this->context->controller->addCSS($this->_path . 'views/css/select2/select2.css');
        $this->context->controller->addCSS($this->_path . 'views/css/style-light.css');
        $this->context->controller->addCSS($this->_path . 'views/css/bootstrap-select/bootstrap-select.css');
        $this->context->controller->addCSS($this->_path . 'views/css/jQRangeSlider/iThing.css');
        $this->context->controller->addCSS($this->_path . 'views/css/jquery-miniColors/jquery.miniColors.css');

        $this->context->controller->addJs($this->_path . 'views/js/jquery-ui/jquery-ui.min.js');
        $this->context->controller->addJs($this->_path . 'views/js/bootstrap.min.js');
        $this->context->controller->addJs($this->_path . 'views/js/common.js');
        $this->context->controller->addJs($this->_path . 'views/js/system/less.min.js');
        $this->context->controller->addJs($this->_path . 'views/js/tinysort/jquery.tinysort.min.js');
        $this->context->controller->addJs($this->_path . 'views/js/jquery/jquery.autosize.min.js');
        $this->context->controller->addJs($this->_path . 'views/js/uniform/jquery.uniform.min.js');
        $this->context->controller->addJs($this->_path . 'views/js/tooltip/tooltip.js');
        $this->context->controller->addJs($this->_path . 'views/js/bootbox.js');
        $this->context->controller->addJs($this->_path . 'views/js/bootstrap-select/bootstrap-select.js');
        $this->context->controller->addJs($this->_path . 'views/js/bootstrap-switch/bootstrap-switch.js');
        $this->context->controller->addJs($this->_path . 'views/js/system/jquery.cookie.js');
        $this->context->controller->addJs($this->_path . 'views/js/themer.js');
        $this->context->controller->addJs($this->_path . 'views/js/admin/jscolor.js');
        $this->context->controller->addJs($this->_path . 'views/js/admin/clipboard.min.js');

        $this->context->controller->addJs($this->_path . 'views/js/jquery-miniColors/jquery.miniColors.js');

        $this->context->controller->addJs($this->_path . 'views/js/supercheckout.js');
        $this->context->controller->addJs($this->_path . 'views/js/velovalidation.js');

        if (!version_compare(_PS_VERSION_, '1.6.0.1', '<')) {
            $this->context->controller->addCSS($this->_path . 'views/css/supercheckout_16_admin.css');
        } else {
            $this->context->controller->addCSS($this->_path . 'views/css/supercheckout_15_admin.css');
        }
    }
    
    /*start-MK made changes to display demo block in the frontend*/
    public function hookDisplayContentWrapperTop()
    {
        if (Configuration::get('VELOCITY_SUPERCHECKOUT_DEMO')) {
//            $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'supercheckout/views/css/font-awesome-new-design/css/all.css');
//            die;
            $settings = array();
            if (!Configuration::get('VELOCITY_SUPERCHECKOUT') || Configuration::get('VELOCITY_SUPERCHECKOUT') == '') {
                $settings = $this->getDefaultSettings();
            } else {
                $settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
            }

            if (Dispatcher::getInstance()->getController() != 'supercheckout') {
                unset($this->context->cookie->kb_supercheckout_demo);
            }
            if (!empty($settings) && $settings['enable']) {
                $this->context->smarty->assign(array(
                    'one_column_link' => $this->context->link->getModuleLink($this->name, 'superdemo', array('action' => 'view', 'type' => 1)),
                    'two_column_link' => $this->context->link->getModuleLink($this->name, 'superdemo', array('action' => 'view', 'type' => 2)),
                    'three_column_link' => $this->context->link->getModuleLink($this->name, 'superdemo', array('action' => 'view', 'type' => 3)),
                ));
                return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/hook/demo_layout.tpl');
            }
        }
    }
    
    
    /*
     * Function is used to get user IP
     */
    private function getUserIp()
    {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') > 0) {
                $addr = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    
    /*End-MK made changes to display demo block in the frontend*/

    public function hookDisplayHeader()
    {
        $settings = array();
        if (Configuration::get('VELOCITY_SUPERCHECKOUT_DEMO')) {
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'supercheckout/views/css/font-awesome-new-design/css/all.css');
        }
        if (Module::isInstalled('socialloginizer') && Module::isEnabled('socialloginizer')) {
            $social_login_data = json_decode(Configuration::get('VELOCITY_SOCIAL_LOGINIZER'), true);
            if (isset($social_login_data['order'])) {
                $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'socialloginizer/views/css/icons.css');
                $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'socialloginizer/views/css/font-awesome.min.css');
                $this->context->controller->addJs(_PS_MODULE_DIR_ . 'socialloginizer/views/js/tinysort/jquery.tinysort.min.js');
                $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'socialloginizer/views/css/loginizer_front.css');
                $this->context->controller->addJs(_PS_MODULE_DIR_ . 'socialloginizer/views/js/custom-social-login.js');
            }
        }
        if (!Configuration::get('VELOCITY_SUPERCHECKOUT') || Configuration::get('VELOCITY_SUPERCHECKOUT') == '') {
            $settings = $this->getDefaultSettings();
        } else {
            $settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
        }
        /* Start: Code added by Anshul to make the changes regarding IP */
        $show = true;
        if (isset($settings['ip_addresses']) && !empty($settings['ip_addresses'])) {
            if (strpos($settings['ip_addresses'], ',') !== false) {
                $ip_array = explode(',', $settings['ip_addresses']);
                $current_user_ip = $this->getUserIp();
                if (!in_array($current_user_ip, $ip_array)) {
                    $show = false;
                }
            } else {
                $current_user_ip = $this->getUserIp();
                if ($settings['ip_addresses'] != $current_user_ip) {
                    $show = false;
                }
            }
        }

        if (Tools::getValue('controller') == 'supercheckout' && $show == false) {
            Tools::redirect($this->context->link->getPageLink('order'));
        }
        /* End: Code added by Anshul to make the changes regarding IP */
        if ($show) {
            if (!Tools::getValue('klarna_supercheckout')) {
                if (isset($settings['super_test_mode']) && $settings['super_test_mode'] != 1) {
                    $page_name = $this->context->controller->php_self;
                    if ($page_name == 'order-opc' || $page_name == 'order' || $page_name == 'checkout' || (isset($settings['disable_cart_page']) && $settings['disable_cart_page'] == 1 && $page_name == 'cart' && !isset($_SERVER['HTTP_X_REQUESTED_WITH']))) {
                        if ($settings['enable'] == 1) {
                            $current_page_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                            $query_string = parse_url($current_page_url);
                            $query_params = array();
                            if (isset($query_string['query'])) {
                                parse_str($query_string['query'], $query_params);
                                if (isset($query_params['isPaymentStep'])) {
                                    unset($query_params['isPaymentStep']);
                                }
                            }
                            Tools::redirect(
                                $this->context->link->getModuleLink(
                                    $this->name,
                                    $this->name,
                                    $query_params,
                                    (bool) Configuration::get('PS_SSL_ENABLED')
                                )
                            );
                        }
                    }
                }
            }

            if (Configuration::get('VELOCITY_SUPERCHECKOUT_CSS') || Configuration::get('VELOCITY_SUPERCHECKOUT_CSS') != ''
            ) {
                $settings['custom_css'] = json_decode((Configuration::get('VELOCITY_SUPERCHECKOUT_CSS')), true);
                $settings['custom_css'] = urldecode($settings['custom_css']);
            }

            if (Configuration::get('VELOCITY_SUPERCHECKOUT_JS') || Configuration::get('VELOCITY_SUPERCHECKOUT_JS') != ''
            ) {
                $settings['custom_js'] = json_decode((Configuration::get('VELOCITY_SUPERCHECKOUT_JS')), true);
                $settings['custom_js'] = urldecode($settings['custom_js']);
            }

            if (isset($settings['custom_css'])) {
                $this->smarty->assign($settings['custom_css']);
            }

            if (isset($settings['custom_js'])) {
                $this->smarty->assign($settings['custom_js']);
            }
        }
    }

    public function hookDisplayOrderConfirmation($params = null)
    {
        if (Configuration::get('PACZKAWRUCHU_CARRIER_ID')) {
            $carrier = Configuration::get('PACZKAWRUCHU_CARRIER_ID');
            $order_carrier_id = $params['objOrder']->id_carrier;
            $cart_id = $params['objOrder']->id_cart;
            if ($order_carrier_id != $carrier) {
                $delete_query = 'delete from `' . _DB_PREFIX_ . 'paczkawruchu` WHERE id_cart=' . (int) $cart_id;
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($delete_query);
            }
        }
        unset($params);
        if (isset($this->context->cookie->supercheckout_temp_address_delivery)
            && $this->context->cookie->supercheckout_temp_address_delivery
        ) {
            $temp_address_delivery = $this->context->cookie->supercheckout_temp_address_delivery;
            $perm_address_delivery = $this->context->cookie->supercheckout_perm_address_delivery;
            if ($temp_address_delivery != $perm_address_delivery) {
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('delete from ' . _DB_PREFIX_ . 'address
					where id_address = ' . (int) $this->context->cookie->supercheckout_temp_address_delivery);
            }
            $this->context->cookie->supercheckout_temp_address_delivery = 0;
            $this->context->cookie->__unset($this->context->cookie->supercheckout_temp_address_delivery);
        }
        if (isset($this->context->cookie->supercheckout_temp_address_invoice)
            && $this->context->cookie->supercheckout_temp_address_invoice
        ) {
            $temp_address_invoice = $this->context->cookie->supercheckout_temp_address_invoice;
            $perm_address_invoice = $this->context->cookie->supercheckout_perm_address_invoice;
            if ($temp_address_invoice != $perm_address_invoice) {
                Db::getInstance(_PS_USE_SQL_SLAVE_)->execute('delete from ' . _DB_PREFIX_ . 'address
					where id_address = ' . (int) $this->context->cookie->supercheckout_temp_address_invoice);
            }
            $this->context->cookie->supercheckout_temp_address_invoice = 0;
            $this->context->cookie->__unset($this->context->cookie->supercheckout_temp_address_invoice);
        }
        $this->context->cookie->supercheckout_perm_address_delivery = 0;
        $this->context->cookie->__unset($this->context->cookie->supercheckout_perm_address_delivery);
        $this->context->cookie->supercheckout_perm_address_invoice = 0;
        $this->context->cookie->__unset($this->context->cookie->supercheckout_perm_address_invoice);
    }

    protected function getMailchimpLists($mailchimp_api)
    {
        try {
            $id = $mailchimp_api;
            $mchimp = new KbMailChimp($id);
            $arrchimp = ($mchimp->call('lists/list'));
            $totallists = $arrchimp['total'];
            if ($totallists >= 1) {
                $listchimp = $arrchimp['data'];
                echo json_encode($listchimp);
            } else {
                echo json_encode(array('false'));
            }
        } catch (Exception $e) {
            echo json_encode(array('false'));
        }
        die;
    }

    /*
     * Function added by Anshul for SendinBlue
     */
    protected function getSendinBlueList($SendinBlue_api)
    {
        try {
            $apikey = $SendinBlue_api;
            $response = array(); //defining array to store response
            if (trim($apikey) != '' && $apikey !== null) {
                $mailin = new KbSuperMailin('https://api.sendinblue.com/v2.0', $apikey);

                $folder = $mailin->get_folder(1)['data']; // it'll be modified later as get_lists() is not working to get all list
                foreach ($folder as $value) {
                    $response[] = $value['lists'];
                }
             //start by dharmanshu for the compatiblity for v3 version of API in sendinBlue
                if (empty($response)) {
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/lists?limit=10&offset=0&sort=desc",
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => "",
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 30,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => "GET",
                      CURLOPT_HTTPHEADER => array(
                        "accept: application/json",
                        "api-key: ".$apikey."",
                      ),
                    ));

                    $result_data = curl_exec($curl);
                    $result_data = json_decode($result_data, true);
                    if (isset($result_data['lists'])) {
                        unset($result_data['count']);
                        $folder = $result_data;
                       
                        foreach ($folder as $k => $value) {
                            $response[] = $value;
                        }
                    }
                    curl_close($curl);
                }
                //end by dharmanshu for the compatiblity for v3 version of API in sendinBlue
            }
            if (empty($response)) {
                echo json_encode(array('false'));
            } else {
                echo json_encode($response[0]);
            }
        } catch (Exception $e) {
            echo json_encode(array('false'));
        }
        die;
    }
    
    /*
     * Function added by Anshul for Klaviyo
     */
    protected function getKlaviyoList($Klaviyo_api)
    {
        try {
            $api_key = $Klaviyo_api;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://a.klaviyo.com/api/v1/lists?api_key=' . $api_key);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $output = json_decode(curl_exec($ch));
            curl_close($ch);

            if (property_exists($output, 'status')) {
                $status = $output->status;
                if ($status === 403) {
                    $reason = $this->l('The Private Klaviyo API Key you have set is invalid.');
                } elseif ($status === 401) {
                    $reason = $this->l('The Private Klaviyo API key you have set is no longer valid.');
                } else {
                    $reason = $this->l('Unable to verify Klaviyo Private API Key.');
                }

                $result = array(
                    'success' => false,
                    'reason' => $reason
                );
            } else {
                $static_groups = array_filter($output->data, function ($list) {
                    return $list->list_type === 'list';
                });

                usort($static_groups, function ($a, $b) {
                    return Tools::strtolower($a->name) > Tools::strtolower($b->name) ? 1 : -1;
                });

                $result = array(
                    'success' => true,
                    'lists' => $static_groups
                );
            }
            $options = array();
            if (!$result["success"]) {
                $options["error"][] = array(
                    'value' => "0",
                    'label' => $result["reason"]);
            } else {
                if (!empty($result["lists"])) {
                    foreach ($result["lists"] as $list) {
                        $options["success"][] = array(
                            'value' => $list->id,
                            'label' => $list->name);
                    }
                } else {
                    $options["error"][] = array(
                        'value' => "0",
                        'label' => $this->l("No list found. (Verify Credentials)"));
                }
            }

            echo json_encode($options['success']);
        } catch (Exception $e) {
            echo json_encode(array('false'));
        }
        die;
    }

    protected function removeFile($id)
    {
        $mask = _PS_MODULE_DIR_ . 'supercheckout/views/img/admin/uploads/' . trim($id) . '.*';
        $matches = glob($mask);
        if (count($matches) > 0) {
            array_map('unlink', $matches);
            echo 1;
        }
        die;
    }

    public function addNewCustomField($custom_field_form_values)
    {
        $type = $custom_field_form_values['type'];
        $position = $custom_field_form_values['position'];
        $required = $custom_field_form_values['required'];
        /*Start Code Added By Priyanshu on 11-Feb-2021 to implement the functionality to show Custom Field details in invoice*/
        $show_invoice = $custom_field_form_values['show_invoice'];
        /*End Code Added By Priyanshu on 11-Feb-2021 to implement the functionality to show Custom Field details in invoice*/
        $active = $custom_field_form_values['active'];
        $default_value = $custom_field_form_values['default_value'];
        $validation_type = $custom_field_form_values['validation_type'];

        // Making validation type none
        if ($type == 'selectbox' || $type == 'checkbox' || $type == 'radio') {
            $validation_type = 0;
        // Start: Code Added by Anshul to add the new custom field type
        } elseif ($type == 'date') {
            $validation_type = 'isDate';
        } elseif ($type == 'file') {
            $validation_type = 'isFile';
        }
        // End: Code Added by Anshul to add the new custom field type

        $labels = $custom_field_form_values['field_label'];
        // Calling the function which processes multilang field data
        $labels = $this->processMultilangFieldValues($labels);

        $help_texts = $custom_field_form_values['help_text'];
        // Calling the function which processes multilang field data
        $help_texts = $this->processMultilangFieldValues($help_texts);

        $field_options = $custom_field_form_values['field_options'];
        // Calling the function which processes multilang field data
        $field_options = $this->processMultilangFieldValues($field_options);

        // Save data into velsof_supercheckout_custom_fields table
        /* Start - Code modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team */
        $field_data = array(
            'type' => pSQL($type),
            'position' => pSQL($position),
            'required' => pSQL($required),
            'active' => pSQL($active),
            'show_invoice' => pSQL($show_invoice),
            'default_value' => pSQL($default_value),
            'validation_type' => pSQL($validation_type),
        );
        /* End - Code modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team */
        Db::getInstance()->insert('velsof_supercheckout_custom_fields', $field_data);

        // Getting the last inserted id
        $id_velsof_supercheckout_custom_fields = Db::getInstance()->Insert_ID();

        // Save data into velsof_supercheckout_custom_fields_lang table
        $this->saveFieldLangs($id_velsof_supercheckout_custom_fields, $labels, $help_texts);

        // Saving the data into velsof_supercheckout_custom_field_options_lang table
        $this->saveFieldOptions($id_velsof_supercheckout_custom_fields, $field_options);
        return $id_velsof_supercheckout_custom_fields;
    }

    /**
     * Function which processes all the multilang field values and sets default values in empty indexes
     * @param type $arary_filed_values
     * @return type
     */
    public function processMultilangFieldValues($arary_filed_values)
    {
        $arr_empty_indexes = array();
        $flag_first = 0;
        foreach ($arary_filed_values as $id_lang => $field_value) {
            // If field_value is empty then store the languade id in the array so that we can process it later
            if (empty($field_value)) {
                $arr_empty_indexes[] = $id_lang;
            } else {
                // If first label with some value is found
                if ($flag_first == 0) {
                    $default_label_value = $field_value;
                    $flag_first = 1;
                }
            }
        }

        // Setting the value of first field into all the empty labels
        foreach ($arr_empty_indexes as $id_lang) {
            $arary_filed_values[$id_lang] = $default_label_value;
        }
        return $arary_filed_values;
    }

    /**
     * Function to save the options data into the database
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     * @param type $id_velsof_supercheckout_custom_fields
     * @param type $field_options
     */
    public function saveFieldOptions($id_velsof_supercheckout_custom_fields, $field_options)
    {
        //d($field_options);
        foreach ($field_options as $id_lang => $option_lang_wise) {
            $array_options = explode("\n", $option_lang_wise);
            foreach ($array_options as $option) {
                if (!empty($option)) {
                    // Exploding the option textbox rows using |. On doing this we will get option value on 0th index and option label on 1st index
                    $array_option_data = explode('|', $option);
                    $option_data_lang = array(
                        'id_velsof_supercheckout_custom_fields' => pSQL($id_velsof_supercheckout_custom_fields),
                        'id_lang' => pSQL($id_lang),
                        'option_value' => pSQL($array_option_data[0]),
                        'option_label' => pSQL($array_option_data[1])
                    );
                    Db::getInstance()->insert('velsof_supercheckout_custom_field_options_lang', $option_data_lang);
                }
            }
        }
    }

    /**
     * Function to save the multilangual data into the database
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     * @param type $id_velsof_supercheckout_custom_fields
     * @param type $label
     * @param type $help_texts
     */
    public function saveFieldLangs($id_velsof_supercheckout_custom_fields, $labels, $help_texts)
    {
        foreach ($labels as $id_lang => $label) {
            $field_data_lang = array(
                'id_velsof_supercheckout_custom_fields' => pSQL($id_velsof_supercheckout_custom_fields),
                'id_lang' => pSQL($id_lang),
                'field_label' => pSQL($label),
                'field_help_text' => pSQL($help_texts[$id_lang]),
            );
            Db::getInstance()->insert('velsof_supercheckout_custom_fields_lang', $field_data_lang);
        }
    }

    /**
     * Returns the field basic details
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     * @param type $id_velsof_supercheckout_custom_fields
     * @return type
     */
    public function getFieldDetailsBasic($id_velsof_supercheckout_custom_fields)
    {
        //Getting all values of a custom field to pass it in the edit form tpl file which is randered when edit icon is clicked
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields cf ';
        $query = $query . 'WHERE cf.id_velsof_supercheckout_custom_fields = "'.(int)$id_velsof_supercheckout_custom_fields.'"';
        return Db::getInstance()->executeS($query);
    }

    /**
     * Returns the field language values in suitable format
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     * @return type
     */
    public function getFieldLangs($id_velsof_supercheckout_custom_fields)
    {
        $query_field_lang = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
        $query_field_lang .= 'WHERE cfl.id_velsof_supercheckout_custom_fields = "'.(int)$id_velsof_supercheckout_custom_fields.'"';
        $result_custom_fields_details_field_lang = Db::getInstance()->executeS($query_field_lang);
        //Converting array into suitable format
        $array_fields_lang = array();
        foreach ($result_custom_fields_details_field_lang as $lang_data) {
            $array_fields_lang[$lang_data['id_lang']] = array(
                'field_label' => $lang_data['field_label'],
                'field_help_text' => $lang_data['field_help_text'],
            );
        }
        return $array_fields_lang;
    }
    public function processOnNewOrder($id_cart, $id_order, $order_reference)
    {
        $orders_by_reference = Order::getByReference($order_reference);
        $orders = $orders_by_reference->getResults();
        $is_gift = 0;
        if ($orders && is_array($orders) && count($orders) > 0) {
            foreach ($orders as $order) {
                $sql = 'Select id_gift_message From ' . _DB_PREFIX_ . 'kb_supercheckout_gift_message where id_cart= ' . (int)$id_cart;
                $exists = DB::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                $is_gift = $order->gift;
                if ($exists) {
                    if ($is_gift) {
                        $sql = 'update ' . _DB_PREFIX_ . 'kb_supercheckout_gift_message set 
                                id_order=' . (int)$order->id . ' where id_cart=' . (int) $id_cart;
                        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
                    } else {
                        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'kb_supercheckout_gift_message where id_cart = ' . (int) $id_cart;
                        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
                    }
                }
            }
        }
    }

    /* changes over */
    /**
     * Returns the field options in suitable format
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     * @param type $id_velsof_supercheckout_custom_fields
     * @return type
     */
    public function getFieldOptions($id_velsof_supercheckout_custom_fields)
    {
        $query_field_options = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_field_options_lang cfol ';
        $query_field_options .= 'WHERE cfol.id_velsof_supercheckout_custom_fields = "'.(int)$id_velsof_supercheckout_custom_fields.'"';
        $result_custom_fields_details_field_options = Db::getInstance()->executeS($query_field_options);
        //Converting array into suitable format and converting into raw format again
        $array_fields_options = array();
        foreach ($result_custom_fields_details_field_options as $lang_data) {
            $option_value = $lang_data['option_value'];
            $option_label = $lang_data['option_label'];
            $array_fields_options[$lang_data['id_lang']] .= "$option_value|$option_label";
        }
        return $array_fields_options;
    }

    /*
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     */
    public function editCustomField($custom_field_form_values)
    {
        $id_velsof_supercheckout_custom_fields = $custom_field_form_values['id_velsof_supercheckout_custom_fields'];
        $type = $custom_field_form_values['type'];
        $position = $custom_field_form_values['position'];
        $required = $custom_field_form_values['required'];
        /*Start Code Added By Priyanshu on 11-Feb-2021 to implement the functionality to show Custom Field details in invoice*/
        $show_invoice = $custom_field_form_values['show_invoice'];
        /*End Code Added By Priyanshu on 11-Feb-2021 to implement the functionality to show Custom Field details in invoice*/
        $active = $custom_field_form_values['active'];
        $default_value = $custom_field_form_values['default_value'];
        $validation_type = $custom_field_form_values['validation_type'];

        $labels = $custom_field_form_values['field_label'];
        //Calling the function which processes multilang field data
        $labels = $this->processMultilangFieldValues($labels);

        $help_texts = $custom_field_form_values['help_text'];
        // Calling the function which processes multilang field data
        $help_texts = $this->processMultilangFieldValues($help_texts);

        $field_options = $custom_field_form_values['field_options'];
        // Calling the function which processes multilang field data
        $field_options = $this->processMultilangFieldValues($field_options);

        // Making validation type none
        if ($type == 'selectbox' || $type == 'checkbox' || $type == 'radio') {
            $validation_type = 0;
        // Start: Code Added by Anshul to add the new custom field type
        } elseif ($type == 'date') {
            $validation_type = 'isDate';
        } elseif ($type == 'file') {
            $validation_type = 'isFile';
        }
        // End:Code Added by Anshul to add the new custom field type

        // Updating the value into velsof_supercheckout_custom_fields table
        $update_field_data = array(
            'type' => pSQL($type),
            'position' => pSQL($position),
            'required' => pSQL($required),
            'active' => pSQL($active),
            'show_invoice' => pSQL($show_invoice),
            'default_value' => pSQL($default_value),
            'validation_type' => pSQL($validation_type),
        );
        $where = 'id_velsof_supercheckout_custom_fields = '.(int)$id_velsof_supercheckout_custom_fields;
        Db::getInstance()->update('velsof_supercheckout_custom_fields', $update_field_data, $where);

        // Delete previously saved data from velsof_supercheckout_custom_fields_lang table
        $where_delete = 'id_velsof_supercheckout_custom_fields = ' . (int)$id_velsof_supercheckout_custom_fields;
        Db::getInstance()->delete('velsof_supercheckout_custom_fields_lang', $where_delete);

        // Insert new data into the table
        $this->saveFieldLangs($id_velsof_supercheckout_custom_fields, $labels, $help_texts);

        // Delete the previously saved data from velsof_supercheckout_custom_field_options_lang table
        $where_delete = 'id_velsof_supercheckout_custom_fields = '.(int)$id_velsof_supercheckout_custom_fields;
        Db::getInstance()->delete('velsof_supercheckout_custom_field_options_lang', $where_delete);

        // Insert new data into velsof_supercheckout_custom_field_options_lang table
        $this->saveFieldOptions($id_velsof_supercheckout_custom_fields, $field_options);

        return $id_velsof_supercheckout_custom_fields;
    }

    /**
     * Returns the row data of current selected language from custom fields tables
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     * @param type $id_velsof_supercheckout_custom_fields
     */
    public function getRowDataCurrentLang($id_velsof_supercheckout_custom_fields)
    {
        $current_language_id = $this->context->language->id;
        // Getting details of the row
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields cf ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
        $query = $query . 'ON cf.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields ';
        $query = $query . 'WHERE cf.id_velsof_supercheckout_custom_fields = "'.(int)$id_velsof_supercheckout_custom_fields.'" AND
			id_lang = "'.(int)$current_language_id.'"';
        return Db::getInstance()->executeS($query);
    }

    /**
     * Deletes data from all tables
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     * @param type $id_velsof_supercheckout_custom_fields
     */
    public function deleteWholeRowData($id_velsof_supercheckout_custom_fields)
    {
        $where_delete = 'id_velsof_supercheckout_custom_fields = '.(int)$id_velsof_supercheckout_custom_fields;
        Db::getInstance()->delete('velsof_supercheckout_custom_fields', $where_delete);
        Db::getInstance()->delete('velsof_supercheckout_custom_fields_lang', $where_delete);
        Db::getInstance()->delete('velsof_supercheckout_custom_field_options_lang', $where_delete);
    }

    /**
     * Function returns the data of custom fields stored for given order
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     * @return type
     */
    public function getFieldsDataToDisplay($id_order, $profile_config)
    {
        $id_lang = $this->context->language->id;

        // Query to get all the data of fields according to the order id
        $query = 'SELECT fd.*, cfl.*, cf.type, cf.show_invoice FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_fields_data fd ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields_lang cfl ';
        $query = $query . 'ON fd.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_fields cf ';
        $query = $query . 'ON cf.id_velsof_supercheckout_custom_fields = cfl.id_velsof_supercheckout_custom_fields ';
        $query = $query . 'WHERE id_order = "' . (int)$id_order. '" AND cfl.id_lang = "' . (int)$id_lang . '"';
        $result_fields_data = Db::getInstance()->executeS($query);
        
//        if (!empty($profile_config)) {
//            foreach ($result_fields_data as $key => $value) {
//                foreach ($profile_config['custom_fields'] as $key1 => $value1) {
//                    if ($value['id_velsof_supercheckout_custom_fields'] != $key1) {
//                        unset($result_fields_data[$key]);
//                    }
//                }
//            }
//        }

        // Processing checkboxes data
        foreach ($result_fields_data as $key => $field) {
            if ($field['type'] == 'checkbox') {
                $array_checkbox_values = json_decode($field['field_value'], true);
                // Getting option value labels
                $array_labels = array();
                $option_label = '';
                foreach ($array_checkbox_values as $option_value) {
                    $query = 'SELECT option_label FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_field_options_lang WHERE option_value = "'.pSQL($option_value).'"';
                    $result_label = Db::getInstance()->executeS($query);
                    if (isset($result_label[0])) {
                        $array_labels[] = $result_label[0]['option_label'];
                    }
                }

                // Implode the values. Here we are getting the final string containing all the labels
                $option_label = implode(', ', $array_labels);

                // Replace the serialized string with the newly created string
                $result_fields_data[$key]['field_value'] = $option_label;
            }
            if ($field['type'] == 'selectbox' || $field['type'] == 'radio') {
                $my_option = $field['field_value'];
                $query = 'SELECT option_label FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_custom_field_options_lang WHERE option_value = "' . pSQL($my_option) . '"';
                $result_label = Db::getInstance()->executeS($query);
                if (isset($result_label[0])) {
                    $result_fields_data[$key]['field_value'] = $result_label[0]['option_label'];
                }
            }
        }
        
        return $result_fields_data;
    }

    public function hookDisplayAdminOrderContentShip()
    {
        //display tab content in order(admin) page
        $module_settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);

        if ($module_settings['enable'] == 1) {
            $empty = 0;
            $id_order = Tools::getValue('id_order');
            $order = new Order((int) $id_order);

            $sql = 'SELECT id_profile FROM ' . _DB_PREFIX_ . 'kb_supercheckout_profile_mapping WHERE id_address = ' . (int) $order->id_address_delivery;
            $id_profile = Db::getInstance()->getRow($sql);
            $profile_config = array();
            if (!empty($id_profile)) {
                $existing_profile_datas = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
                if (isset($existing_profile_datas) && !empty($existing_profile_datas)) {
                    foreach ($existing_profile_datas as $key => $data) {
                        if ($data['id_profile'] == $id_profile['id_profile']) {
                            $profile_config = $existing_profile_datas[$key];
                            break;
                        }
                    }
                }
                $this->smarty->assign('customer_profile', $profile_config['field_label'][$this->context->language->id]);
            }

            $result_fields_data = $this->getFieldsDataToDisplay($id_order, $profile_config);

            if (empty($result_fields_data)) {
                $empty = 1;
            }

            $this->smarty->assign('fields_data', $result_fields_data);
            $this->smarty->assign('empty', $empty);
            //Start: Added by Anshul
            $this->smarty->assign('kb_admin_controller', $this->context->link->getAdminLink('AdminModules', true)
            . '&configure=' . urlencode($this->name) . '&tab_module=' . $this->tab
            . '&module_name=' . urlencode($this->name).'&downloadFile=true');
            //End: Added by Anshul
            /* changes by rishabh jain */
            $sql = 'Select * From ' . _DB_PREFIX_ . 'kb_supercheckout_gift_message where id_order= ' . (int)$id_order;
            $gift_msg_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
            $empty_gift_message = 0;
            if (empty($gift_msg_data)) {
                $empty_gift_message = 1;
            } else {
                $this->smarty->assign('sender', $gift_msg_data['kb_sender']);
                $this->smarty->assign('receiver', $gift_msg_data['kb_receiver']);
                $this->smarty->assign('gift_msg', $gift_msg_data['kb_message']);
            }
            $this->smarty->assign('empty_gift_message', $empty_gift_message);
            // changes over
            
            return $this->display(__FILE__, 'custom_fields_data_content.tpl');
        }
    }
    
    public function hookDisplayAdminOrderTabContent($params)
    {
        //display tab content in order(admin) page
        $module_settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);

        if ($module_settings['enable'] == 1) {
            $empty = 0;
            $id_order = $params['id_order'];
            $order = new Order((int) $id_order);

            $sql = 'SELECT id_profile FROM ' . _DB_PREFIX_ . 'kb_supercheckout_profile_mapping WHERE id_address = ' . (int) $order->id_address_delivery;
            $id_profile = Db::getInstance()->getRow($sql);
            $profile_config = array();
            if (!empty($id_profile)) {
                $existing_profile_datas = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
                if (isset($existing_profile_datas) && !empty($existing_profile_datas)) {
                    foreach ($existing_profile_datas as $key => $data) {
                        if ($data['id_profile'] == $id_profile['id_profile']) {
                            $profile_config = $existing_profile_datas[$key];
                            break;
                        }
                    }
                }
                $this->smarty->assign('customer_profile', $profile_config['field_label'][$this->context->language->id]);
            }
            $result_fields_data = $this->getFieldsDataToDisplay($id_order, $profile_config);

            if (empty($result_fields_data)) {
                $empty = 1;
            }

            $this->smarty->assign('fields_data', $result_fields_data);
            $this->smarty->assign('empty', $empty);
            //Start: Added by Anshul
            $this->smarty->assign('kb_admin_controller', $this->context->link->getAdminLink('AdminModules', true)
            . '&configure=' . urlencode($this->name) . '&tab_module=' . $this->tab
            . '&module_name=' . urlencode($this->name).'&downloadFile=true');
            //End: Added by Anshul
            /* changes by rishabh jain */
            $sql = 'Select * From ' . _DB_PREFIX_ . 'kb_supercheckout_gift_message where id_order= ' . (int)$id_order;
            $gift_msg_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
            $empty_gift_message = 0;
            if (empty($gift_msg_data)) {
                $empty_gift_message = 1;
            } else {
                $this->smarty->assign('sender', $gift_msg_data['kb_sender']);
                $this->smarty->assign('receiver', $gift_msg_data['kb_receiver']);
                $this->smarty->assign('gift_msg', $gift_msg_data['kb_message']);
            }
            $this->smarty->assign('empty_gift_message', $empty_gift_message);
            // changes over
            
            return $this->display(__FILE__, 'custom_fields_data_content.tpl');
        }
    }
    
    /*
     * Function to display Custom Field block in Invoice PDF
     * Added By Priyanshu on 11-Feb-2021 to implement the functionality to show Custom Field Data in Invoice
     */
    public function hookDisplayPDFInvoice($params)
    {
        $module_settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
        if ($module_settings['enable'] == 1) {
            $id_order = $params['object']->id_order;
            $order = new Order((int) $id_order);

            $sql = 'SELECT id_profile FROM ' . _DB_PREFIX_ . 'kb_supercheckout_profile_mapping WHERE id_address = ' . (int) $order->id_address_delivery;
            $id_profile = Db::getInstance()->getRow($sql);
            $profile_config = array();
            if (!empty($id_profile)) {
                $existing_profile_datas = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
                if (isset($existing_profile_datas) && !empty($existing_profile_datas)) {
                    foreach ($existing_profile_datas as $key => $data) {
                        if ($data['id_profile'] == $id_profile['id_profile']) {
                            $profile_config = $existing_profile_datas[$key];
                            break;
                        }
                    }
                }
                $this->smarty->assign('customer_profile', $profile_config['field_label'][$this->context->language->id]);
            }
            $result_fields_data = $this->getFieldsDataToDisplay($id_order, $profile_config);

            foreach ($result_fields_data as $key => $value) {
                if ($value['show_invoice'] == 0) {
                    unset($result_fields_data[$key]);
                }
            }

            if (!empty($result_fields_data)) {
                $this->smarty->assign('fields_data', $result_fields_data);
                $this->smarty->assign('kb_front_controller', $this->context->link->getModuleLink('supercheckout', 'supercheckout', array('downloadFile' => true), (bool) Configuration::get('PS_SSL_ENABLED')));
                return $this->display(__FILE__, 'custom_fields_data_content_invoice.tpl');
            }
        }
    }
    
    public function hookActionEmailAddAfterContent(&$params)
    {
        $content = '';
        if ($params['template'] == 'order_conf') { // Let's edit content of Order's Confirmation email
            $cart_id = $this->context->cart->id;
            $order_id = Order::getOrderByCartId((int) ($cart_id));
            $order = new Order($order_id);
            /* changes by rishabh jain */
            $sql = 'Select * From ' . _DB_PREFIX_ . 'kb_supercheckout_gift_message where id_order= ' . (int)$order_id;
            $gift_msg_data = DB::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
            $empty_gift_message = 0;
            if (empty($gift_msg_data)) {
                $empty_gift_message = 1;
            } else {
                $this->smarty->assign('sender', $gift_msg_data['kb_sender']);
                $this->smarty->assign('receiver', $gift_msg_data['kb_receiver']);
                $this->smarty->assign('gift_msg', $gift_msg_data['kb_message']);
            }
            $this->smarty->assign('empty_gift_message', $empty_gift_message);
            $content = $this->display(__FILE__, 'gift_message_detail_on_email.tpl');
            $params['template_html'] = str_replace("{date}", "{date}" . $content, $params['template_html']); // and add text to end of {products} variable
        }
    }
    
    public function hookDisplayAdminOrderTabShip()
    {
        //display tab in order(admin) page
        $module_settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
        if ($module_settings['enable'] == 1) {
            $this->context->controller->addCSS($this->_path . 'views/css/preferred_delivery.css');
            return $this->display(__FILE__, 'custom_fields_data_tab.tpl');
        }
    }
    
    public function hookDisplayAdminOrderTabLink($params)
    {
        //display tab in order(admin) page
        $module_settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
        if ($module_settings['enable'] == 1) {
            $this->context->controller->addCSS($this->_path . 'views/css/preferred_delivery.css');
            $this->context->smarty->assign('kb_version', '1.7.7');
            return $this->display(__FILE__, 'custom_fields_data_tab.tpl');
        }
    }

    /*
     * Function modified by RS for fixing the pSQL() errors reported by PrestaShop Addons team
     */
    public function hookActionValidateOrder($params)
    {
        // This hook is called when an order is created
        $id_cart = $params['cart']->id;
        $id_order = $params['order']->id;

        // Updating the order id in the table
        $data = array(
            'id_order' => pSQL($id_order)
        );
        $where = "id_cart = '".(int)$id_cart."'";
        Db::getInstance()->update('velsof_supercheckout_fields_data', $data, $where);
        // <editor-fold defaultstate="collapsed" desc="GDPR change">
        $accepted_consent = array();
        $default_policy_text = '';
        if (isset($this->context->cookie->supercheckout_accepted_consent)) {
            $accepted_consent = json_decode($this->context->cookie->supercheckout_accepted_consent);
        }
        if (isset($this->context->cookie->supercheckout_default_policy)) {
            $default_policy_text = $this->context->cookie->supercheckout_default_policy;
        }
        $this->insertUserAcceptedConsent($id_order, $accepted_consent, $default_policy_text);
        // </editor-fold>
        // changes by rishabh jain
        $tmp = $params['order'];
        $this->processOnNewOrder($id_cart, $id_order, $tmp->reference);
        // changes over
    }

    public function hookDisplayOrderDetail()
    {
        // Hook to display details in order details page
        $module_settings = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);

        if ($module_settings['enable'] == 1) {
            $empty = 0;
            $id_order = Tools::getValue('id_order');
            /* Start Code Added by Priyanshu to fix the Order id issue*/
            if (empty($id_order) && !empty(Tools::getValue('order_reference'))) {
                $sql = "Select id_order from " . _DB_PREFIX_ . "orders where reference = '" . pSQL(Tools::getValue('order_reference')) . "'";
                $result = Db::getInstance()->executeS($sql);
                $id_order = $result[0]['id_order'];
            }
            /* End Code Added by Priyanshu to fix the Order id issue*/
            $order = new Order((int) $id_order);

            $sql = 'SELECT id_profile FROM ' . _DB_PREFIX_ . 'kb_supercheckout_profile_mapping WHERE id_address = ' . (int) $order->id_address_delivery;
            $id_profile = Db::getInstance()->getRow($sql);
            $profile_config = array();
            if (!empty($id_profile)) {
                $existing_profile_datas = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT_CUSTOMER_PROFILE'), true);
                if (isset($existing_profile_datas) && !empty($existing_profile_datas)) {
                    foreach ($existing_profile_datas as $key => $data) {
                        if ($data['id_profile'] == $id_profile['id_profile']) {
                            $profile_config = $existing_profile_datas[$key];
                            break;
                        }
                    }
                }
                $this->smarty->assign('customer_profile', $profile_config['field_label'][$this->context->language->id]);
            }
            $result_fields_data = $this->getFieldsDataToDisplay($id_order, $profile_config);

            if (empty($result_fields_data)) {
                $empty = 1;
            }
            $this->smarty->assign('kb_front_controller', $this->context->link->getModuleLink('supercheckout', 'supercheckout', array('downloadFile' => true), (bool) Configuration::get('PS_SSL_ENABLED')));

            $this->smarty->assign('fields_data', $result_fields_data);
            $this->smarty->assign('empty', $empty);
            return $this->display(__FILE__, 'custom_fields_data_on_order_history.tpl');
        }
    }
    
    /*
     * Function added by Anshul for download the file in the admin and order history
     */
    public function downloadFile($field_id)
    {
        // clean buffer
        if (ob_get_level() && ob_get_length() > 0) {
            ob_clean();
        }
        
        $sql = 'SELECT * FROM '._DB_PREFIX_.'velsof_supercheckout_fields_data WHERE id_velsof_supercheckout_fields_data = '. (int)$field_id;
        $data = DB::getInstance()->getRow($sql);
        // changes done by kanishka on 23-12-2022 to convert the data from object to array
        if (is_array(json_decode($data['field_value']))) {
            $file = json_decode($data['field_value']);
        } else {
            $file = (array) json_decode($data['field_value']);
        }
        // changes done by kanishka on 23-12-2022 to convert the data from object to array
        if (!empty($file) && is_array($file)) {
            if (isset($file['type'])) {
                $path = $file['relative_path'];
                if (Tools::file_exists_no_cache($file['relative_path'])) {
                    header('Content-type:' . $file['type']);
                    header('Content-Type: application/force-download; charset=UTF-8');
                    header('Cache-Control: no-store, no-cache');
                    header('Content-disposition: attachment; filename=' . time() . '.' . $file['extension']);
                    readfile($path);
                    exit;
                }
            }
        }
    }

    /*
     * return default settings of the supercheckout page
     * Function modified by Raghu on 18-Aug-2017 for fixing translations issues in Address form
     */
    public function getDefaultSettings()
    {
        $settings = array(
            'adv_id' => 0,
            'plugin_id' => 'PS0002',
            'version' => '0.1',
            'enable' => 0,
            'disable_cart_page' => 0,
            'enable_payment_address_name' => 0,
            'enable_guest_checkout' => 1,
            'enable_guest_register' => 0,
            'enable_validation_dni' => 0,   //Feature:Spain DNI Check (Jan 2020)
            'checkout_option' => 0,
            'super_test_mode' => 0,
            'free_shipping_amount'=> '',
            'SendinBlue' => 0,
            'klaviyo' => 0,
            'email_marketing_delete' => 0,
            'super_test_mode' => 0,
            'qty_update_option' => 0,
            'inline_validation' => array('enable' => 0),
            'enable_auto_detect_country' => array('enable' => 0),
            'social_login_popup' => array('enable' => 1),
            'fb_login' => array('enable' => 0, 'app_id' => '', 'app_secret' => ''),
            'mobile_login' => array('enable' => 0),
            'google_auto_address' => array('enable' => 0, 'api_key' => ''),
            'mailchimp' => array('enable' => 0, 'api' => '', 'list' => '', 'default' => 0),
            'google_login' => array('enable' => 0, 'app_id' => '', 'client_id' => '', 'app_secret' => ''),
            'paypal_login' => array('enable' => 0, 'client_id' => '', 'client_secret' => ''),
            'customer_personal' => array(
                'id_gender' => array(
                    'id' => 'id_gender',
                    'title' => 'Title',
                    'sort_order' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'dob' => array(
                    'id' => 'dob',
                    'title' => 'DOB',
                    'sort_order' => 2,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                )
            ),
            'customer_subscription' => array(
                'newsletter' => array(
                    'id' => 'newsletter',
                    'title' => 'Sign up for NewsLetter',
                    'sort_order' => 3,
                    'guest' => array('checked' => 0, 'display' => 1)
                ),
                'optin' => array(
                    'id' => 'optin',
                    'sort_order' => 4,
                    'title' => 'Special Offer',
                    'guest' => array('checked' => 0, 'display' => 1)
                )
            ),
            'hide_delivery_for_virtual' => 0,
            'use_delivery_for_payment_add' => array('guest' => 1, 'logged' => 1),
            'show_use_delivery_for_payment_add' => array('guest' => 1, 'logged' => 1),
            'payment_address' => array(
                'firstname' => array(
                    'id' => 'firstname',
                    'title' => 'First Name',
                    'sort_order' => 1,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'lastname' => array(
                    'id' => 'lastname',
                    'title' => 'Last Name',
                    'sort_order' => 2,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'company' => array(
                    'id' => 'company',
                    'title' => 'Company',
                    'sort_order' => 4,
                    'conditional' => 0,
                    'guest' => array('require' => 0, 'display' => 1),
                    'logged' => array('require' => 0, 'display' => 1)
                ),
                'vat_number' => array(
                    'id' => 'vat_number',
                    'title' => 'Vat Number',
                    'sort_order' => 5,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'address1' => array(
                    'id' => 'address1',
                    'title' => 'Address Line 1',
                    'sort_order' => 6,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'address2' => array(
                    'id' => 'address2',
                    'title' => 'Address Line 2',
                    'sort_order' => 7,
                    'conditional' => 0,
                    'guest' => array('require' => 0, 'display' => 1),
                    'logged' => array('require' => 0, 'display' => 1)
                ),
                'postcode' => array(
                    'id' => 'postcode',
                    'title' => 'Zip/Postal Code',
                    'sort_order' => 8,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'city' => array(
                    'id' => 'city',
                    'title' => 'City',
                    'sort_order' => 9,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'id_country' => array(
                    'id' => 'id_country',
                    'title' => 'Country',
                    'sort_order' => 10,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'id_state' => array(
                    'id' => 'id_state',
                    'title' => 'State',
                    'sort_order' => 11,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'dni' => array(
                    'id' => 'dni',
                    'title' => 'Identification Number',
                    'sort_order' => 12,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'phone' => array(
                    'id' => 'phone',
                    'title' => 'Home Phone',
                    'sort_order' => 13,
                    'conditional' => 0,
                    'guest' => array('require' => 0, 'display' => 1),
                    'logged' => array('require' => 0, 'display' => 1)
                ),
                'phone_mobile' => array(
                    'id' => 'phone_mobile',
                    'title' => 'Mobile Phone',
                    'sort_order' => 14,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'alias' => array(
                    'id' => 'alias',
                    'title' => 'Address Title',
                    'sort_order' => 15,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'other' => array(
                    'id' => 'other',
                    'title' => 'Other Information',
                    'sort_order' => 16,
                    'conditional' => 0,
                    'guest' => array('require' => 0, 'display' => 1),
                    'logged' => array('require' => 0, 'display' => 1)
                ),
            ),
            'shipping_address' => array(
                'firstname' => array(
                    'id' => 'firstname',
                    'title' => 'First Name',
                    'sort_order' => 1,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'lastname' => array(
                    'id' => 'lastname',
                    'title' => 'Last Name',
                    'sort_order' => 2,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'company' => array(
                    'id' => 'company',
                    'title' => 'Company',
                    'sort_order' => 3,
                    'conditional' => 0,
                    'guest' => array('require' => 0, 'display' => 1),
                    'logged' => array('require' => 0, 'display' => 1)
                ),
                'vat_number' => array(
                    'id' => 'vat_number',
                    'title' => 'Vat Number',
                    'sort_order' => 4,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'address1' => array(
                    'id' => 'address1',
                    'title' => 'Address Line 1',
                    'sort_order' => 5,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'address2' => array(
                    'id' => 'address2',
                    'title' => 'Address Line 2',
                    'sort_order' => 6,
                    'conditional' => 0,
                    'guest' => array('require' => 0, 'display' => 1),
                    'logged' => array('require' => 0, 'display' => 1)
                ),
                'postcode' => array(
                    'id' => 'postcode',
                    'title' => 'Zip/Postal Code',
                    'sort_order' => 7,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'city' => array(
                    'id' => 'city',
                    'title' => 'City',
                    'sort_order' => 8,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'id_country' => array(
                    'id' => 'id_country',
                    'title' => 'Country',
                    'sort_order' => 9,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'id_state' => array(
                    'id' => 'id_state',
                    'title' => 'State',
                    'sort_order' => 10,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'dni' => array(
                    'id' => 'dni',
                    'title' => 'Identification Number',
                    'sort_order' => 11,
                    'conditional' => 1,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'phone' => array(
                    'id' => 'phone',
                    'title' => 'Home Phone',
                    'sort_order' => 12,
                    'conditional' => 0,
                    'guest' => array('require' => 0, 'display' => 1),
                    'logged' => array('require' => 0, 'display' => 1)
                ),
                'phone_mobile' => array(
                    'id' => 'phone_mobile',
                    'title' => 'Mobile Phone',
                    'sort_order' => 13,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'alias' => array(
                    'id' => 'alias',
                    'title' => 'Address Title',
                    'sort_order' => 14,
                    'conditional' => 0,
                    'guest' => array('require' => 1, 'display' => 1),
                    'logged' => array('require' => 1, 'display' => 1)
                ),
                'other' => array(
                    'id' => 'other',
                    'title' => 'Other Information',
                    'sort_order' => 15,
                    'conditional' => 0,
                    'guest' => array('require' => 0, 'display' => 1),
                    'logged' => array('require' => 0, 'display' => 1)
                )
            ),
            'payment_method' => array('enable' => 1, 'default' => '', 'display_style' => 0),
            'total_price_method' => array('default' => 0),
            'shipping_method' => array('enable' => 1, 'default' => '', 'display_style' => 0),
            'ship_to_pay' => array(),
            'display_cart' => 1,
            /* changes by rishabh jain */
//            'display_cart_total_tax_incl' => 1,
//            'display_cart_total_tax_excluded' => 1,
//            'display_cart_total_shipping' => 1,
//            'display_cart_total_tax' => 1,
            /* changes over */
            'cart_options' => array(
                'product_image' => array(
                    'id' => 'product_image',
                    'title' => 'Image',
                    'sort_order' => 2,
                    'guest' => array('display' => 1),
                    'logged' => array('display' => 1)
                ),
                'product_name' => array(
                    'id' => 'product_name',
                    'title' => 'Description',
                    'sort_order' => 2,
                    'guest' => array('display' => 1),
                    'logged' => array('display' => 1)
                ),
                'product_availability' => array(
                    'id' => 'product_availability',
                    'title' => 'Availability',
                    'sort_order' => 3,
                    'guest' => array('display' => 1),
                    'logged' => array('display' => 1)
                ),
                'product_model' => array(
                    'id' => 'product_model',
                    'title' => 'Model',
                    'sort_order' => 4,
                    'guest' => array('display' => 1),
                    'logged' => array('display' => 1)
                ),
                'product_qty' => array(
                    'id' => 'product_qty',
                    'title' => 'Quantity',
                    'sort_order' => 5,
                    'guest' => array('display' => 1),
                    'logged' => array('display' => 1)
                ),
                'product_price' => array(
                    'id' => 'product_price',
                    'title' => 'Price',
                    'sort_order' => 6,
                    'guest' => array('display' => 1),
                    'logged' => array('display' => 1)
                ),
                'product_total' => array(
                    'id' => 'product_total',
                    'title' => 'Total',
                    'sort_order' => 7,
                    'guest' => array('display' => 1),
                    'logged' => array('display' => 1)
                )
            ),
            'cart_image_size' => array('name' => 'velsof_supercheckout_image', 'width' => 90, 'height' => 90),
            'order_total_option' => array(
                'product_sub_total' => array('guest' => array('display' => 1), 'logged' => array('display' => 1)),
                'voucher' => array('guest' => array('display' => 1), 'logged' => array('display' => 1)),
                'shipping_price' => array('guest' => array('display' => 1), 'logged' => array('display' => 1)),
                'total' => array('guest' => array('display' => 1), 'logged' => array('display' => 1)),
                // changes by rishabh jain
                'total_tax' => array('guest' => array('display' => 1), 'logged' => array('display' => 1))
                // changes over
            ),
            'confirm' => array(
                //changes by rishabh jain
                'gift_message' => array('guest' => array('display' => 1), 'logged' => array('display' => 1)),
                // changes over
                'order_comment_box' => array('guest' => array('display' => 1), 'logged' => array('display' => 1)),
                'term_condition' => array(
                    'guest' => array('checked' => 1, 'require' => 1, 'display' => 1),
                    'logged' => array('checked' => 1, 'require' => 1, 'display' => 1)
                )
            ),
            'layout' => 3,
            'column_width' => array(
                '1_column' => array(1 => '100', 2 => '0', 3 => '0', 'inside' => array(1 => '0', 2 => '0')),
                '2_column' => array(1 => '30', 2 => '70', 3 => '0', 'inside' => array(1 => '50', 2 => '50')),
                '3_column' => array(1 => '30', 2 => '25', 3 => '45', 'inside' => array(1 => '0', 2 => '0'))
            ),
            'modal_value' => 0,
            'design' => array(
                'login' => array(
                    '1_column' => array('column' => 0, 'row' => 0, 'column-inside' => 0),
                    '2_column' => array('column' => 1, 'row' => 0, 'column-inside' => 1),
                    '3_column' => array('column' => 1, 'row' => 0, 'column-inside' => 0)
                ),
                'shipping_address' => array(
                    '1_column' => array('column' => 0, 'row' => 1, 'column-inside' => 0),
                    '2_column' => array('column' => 1, 'row' => 1, 'column-inside' => 1),
                    '3_column' => array('column' => 1, 'row' => 1, 'column-inside' => 0)
                ),
                'payment_address' => array(
                    '1_column' => array('column' => 0, 'row' => 2, 'column-inside' => 0),
                    '2_column' => array('column' => 1, 'row' => 2, 'column-inside' => 1),
                    '3_column' => array('column' => 1, 'row' => 2, 'column-inside' => 0)
                ),
                'shipping_method' => array(
                    '1_column' => array('column' => 0, 'row' => 3, 'column-inside' => 0),
                    '2_column' => array('column' => 1, 'row' => 0, 'column-inside' => 3),
                    '3_column' => array('column' => 2, 'row' => 0, 'column-inside' => 0)
                ),
                'payment_method' => array(
                    '1_column' => array('column' => 0, 'row' => 4, 'column-inside' => 0),
                    '2_column' => array('column' => 2, 'row' => 0, 'column-inside' => 3),
                    '3_column' => array('column' => 2, 'row' => 1, 'column-inside' => 0)
                ),
                'cart' => array(
                    '1_column' => array('column' => 0, 'row' => 5, 'column-inside' => 0),
                    '2_column' => array('column' => 2, 'row' => 0, 'column-inside' => 2),
                    '3_column' => array('column' => 3, 'row' => 0, 'column-inside' => 0)
                ),
                'confirm' => array(
                    '1_column' => array('column' => 0, 'row' => 6, 'column-inside' => 0),
                    '2_column' => array('column' => 2, 'row' => 1, 'column-inside' => 4),
                    '3_column' => array('column' => 3, 'row' => 1, 'column-inside' => 0)
                ),
                'html' => array(
                    '0_0' => array(
                        '1_column' => array('column' => 0, 'row' => 7, 'column-inside' => 1),
                        '2_column' => array('column' => 2, 'row' => 1, 'column-inside' => 4),
                        '3_column' => array('column' => 3, 'row' => 4, 'column-inside' => 1),
                        'value' => ''
                    )
                )
            )
        );

        return $settings;
    }

    /* Function for getting the URL to PrestaShop Root Directory */
    protected function getRootUrl()
    {
        $root_url = '';
        if ($this->checkSecureUrl()) {
            $root_url = _PS_BASE_URL_SSL_ . __PS_BASE_URI__;
        } else {
            $root_url = _PS_BASE_URL_ . __PS_BASE_URI__;
        }
        return $root_url;
    }

    /* Function for checking SSL  */
    private function checkSecureUrl()
    {
        $custom_ssl_var = 0;
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $custom_ssl_var = 1;
            }
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            return true;
        } else {
            return false;
        }
    }
    // <editor-fold defaultstate="collapsed" desc="GDPR Changes">
    public function addNewGDPRPolicy($privacy_form_values)
    {
        $required = $privacy_form_values['required'];
        $active = $privacy_form_values['active'];
        $privacy_link = $privacy_form_values['privacy_link'];
        $labels = $privacy_form_values['field_label'];
        $labels = $this->processMultilangFieldValues($labels);
        $field_data = array(
            'url' => pSQL($privacy_link),
            'is_manadatory' => (int)$required,
            'status' => (int)$active,
        );
        Db::getInstance()->insert('velsof_supercheckout_policies', $field_data);
        // Getting the last inserted id
        $id_velsof_supercheckout_policy = Db::getInstance()->Insert_ID();
        foreach ($labels as $id_lang => $label) {
            $policy_data_lang = array(
                'policy_id' => (int)$id_velsof_supercheckout_policy,
                'lang_id' => (int)$id_lang,
                'description' => pSQL($label)
            );
            Db::getInstance()->insert('velsof_supercheckout_policy_lang', $policy_data_lang);
        }
        return $id_velsof_supercheckout_policy;
    }

    public function getPolicyRowDataCurrentLang($policy_id)
    {
        $current_language_id = $this->context->language->id;
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_policies cf ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_policy_lang cfl ';
        $query = $query . 'ON cf.policy_id = cfl.policy_id ';
        $query = $query . 'WHERE cf.policy_id = ' . (int)$policy_id . ' AND
			lang_id = ' .(int)$current_language_id;
        return Db::getInstance()->executeS($query);
    }

    public function getAllGDPRPolicyDetails()
    {
        $result_gdpr_policy_details = array();
        $current_language_id = $this->context->language->id;
        $query = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_policies cf ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_policy_lang cfl ';
        $query = $query . 'ON cf.policy_id = cfl.policy_id ';
        $query = $query . "WHERE cfl.lang_id =". (int)$current_language_id;
        $result_gdpr_policy_details = Db::getInstance()->executeS($query);
        return $result_gdpr_policy_details;
    }

    public function deletePolicyRowData($policy_id)
    {
        $where_delete = "policy_id =".(int) $policy_id;
        Db::getInstance()->delete('velsof_supercheckout_policies', $where_delete);
        Db::getInstance()->delete('velsof_supercheckout_policy_lang', $where_delete);
    }

    public function getPolicyLangs($policy_id)
    {
        $query_field_lang = 'SELECT * FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_policy_lang cfl ';
        $query_field_lang .= 'WHERE cfl.policy_id = ' . (int)$policy_id;
        $result_policy_details_lang = Db::getInstance()->executeS($query_field_lang);
        //Converting array into suitable format
        $array_fields_lang = array();
        foreach ($result_policy_details_lang as $lang_data) {
            $array_fields_lang[$lang_data['lang_id']] = array(
                'description' => $lang_data['description']
            );
        }
        return $array_fields_lang;
    }

    public function updatePolicyDetails($policy_form_values)
    {
        $policy_id = $policy_form_values['policy_id'];
        $required = $policy_form_values['required'];
        $active = $policy_form_values['active'];
        $privacy_link = $policy_form_values['privacy_link'];

        $labels = $policy_form_values['field_label'];
        $labels = $this->processMultilangFieldValues($labels);
        $update_field_data = array(
            'url' => pSQL($privacy_link),
            'is_manadatory' => (int)$required,
            'status' => (int)$active
        );
        $where = "policy_id =".(int) $policy_id;
        Db::getInstance()->update('velsof_supercheckout_policies', $update_field_data, $where);

        $where_delete = 'policy_id = ' .(int) $policy_id;
        Db::getInstance()->delete('velsof_supercheckout_policy_lang', $where_delete);
        $policy_data_lang = array();
        foreach ($labels as $id_lang => $label) {
            $policy_data_lang = array(
                'policy_id' => (int)$policy_id,
                'lang_id' => (int)$id_lang,
                'description' => pSQL($label)
            );
            Db::getInstance()->insert('velsof_supercheckout_policy_lang', $policy_data_lang);
        }
        return $policy_id;
    }
    
    /*
     * Function for short code functionality
     * Add this hook before the <div id="placeorderButton"> in frontend SuperCheckout tpl file
     * {hook h='hookCustomSuperCheckoutGDPRHook'}
     */
    public function hookCustomSuperCheckoutGDPRHook($params = array())
    {
        $gdpr_setting = $this->getGDPRPolicySetting();
        $this->context->smarty->assign('supercheckout_gdpr_setting', $gdpr_setting);
        return $this->display(__FILE__, 'gdpr_policy_setting.tpl');
    }

    private function getGDPRPolicySetting()
    {
        $current_language_id = $this->context->language->id;
        $query = 'SELECT cf.policy_id, cf.url, cf.is_manadatory, cfl.description FROM ' . _DB_PREFIX_ . 'velsof_supercheckout_policies cf ';
        $query = $query . 'JOIN ' . _DB_PREFIX_ . 'velsof_supercheckout_policy_lang cfl ';
        $query = $query . 'ON cf.policy_id = cfl.policy_id ';
        $query = $query . 'WHERE cf.status = 1 AND
			lang_id = ' .(int)$current_language_id;
        return Db::getInstance()->executeS($query);
    }

    private function insertUserAcceptedConsent($id_order, $acceptedConsent = array(), $default_policy_text = '')
    {
        $order = new Order($id_order);
        $customer = new Customer($order->id_customer);
        $acceptedConsentText = array();
        if (count($acceptedConsent)) {
            foreach ($acceptedConsent as $key => $value) {
                $consent_text_sql = 'Select description from '._DB_PREFIX_.'velsof_supercheckout_policy_lang where policy_id ='.(int)$value.' and lang_id ='.(int)$order->id_lang;
                $consent_text = Db::getInstance()->getRow($consent_text_sql);
                $acceptedConsentText[$value] = $consent_text['description'];
            }
        }
        if (!Tools::isEmpty($default_policy_text)) {
            $acceptedConsentText[0] = $default_policy_text;
        }
        $accepted_consent = json_encode($acceptedConsentText);
        $policy_consent_data = array(
            'id_customer' => (int)$order->id_customer,
            'id_order' => (int)$id_order,
            'order_reference' => pSQL($order->reference),
            'accepted_consent' => pSQL($accepted_consent),
            'id_lang' => (int)$order->id_lang,
        );
        Db::getInstance()->insert('velsof_supercheckout_customer_consent', $policy_consent_data);
    }

    private function getGDPRFilteredCustomerData($search_data)
    {
        $orders_consent = array();
        $filterSqlQuery = "Select SCC.order_reference, C.id_customer, O.id_order, C.firstname, C.lastname, C.email, SCC.accepted_consent, O.date_add  from "._DB_PREFIX_."velsof_supercheckout_customer_consent SCC JOIN "._DB_PREFIX_."customer C ON SCC.id_customer = C.id_customer JOIN "._DB_PREFIX_."orders O on O.id_order = SCC.id_order"
            . " where SCC.order_reference LIKE '%".pSQL($search_data)."%' OR C.email LIKE '%".pSQL($search_data)."%' order by O.id_order desc";
        $filterData = Db::getInstance()->executeS($filterSqlQuery);
        if (count($filterData)) {
            foreach ($filterData as $key => $value) {
                $order_reference = $value['order_reference'];
                $id_customer = $value['id_customer'];
                $id_order = $value['id_order'];
                $firstname = $value['firstname'];
                $lastname = $value['lastname'];
                $email = $value['email'];
                $accepted_consent = $value['accepted_consent'];
                $date_add = Tools::displayDate($value['date_add'], $this->context->language->id, true);

                $orders_consent[] = array(
                    'reference' => $order_reference,
                    'customer' => $firstname.' '.$lastname,
                    'id_customer' => $id_customer,
                    'id_order' => $id_order,
                    'email' => $email,
                    'date' => $date_add,
                    'consent' => json_decode($accepted_consent, true)
                );
            }
        }
//        d($orders_consent);
//        $orders_consent = array(
//            'consent_data1' => array('reference' =>'XZDDCRDCDFDS1','customer' =>'Velocity Software','email'=>'hkumar@velsof.com', 'date'=>'2018-12-12', 'consent' => array('I this is a new plugin which allows you to create an array inside smarty','e an array you would need to be consistent in weather your arra','can create either one of the two, but not both at the same time mix')),
//            'consent_data2' => array('reference' =>'XZDDCRDCDFDS2','customer' =>'Velocity Software','email'=>'hkumar@velsof.com', 'date'=>'2018-12-12', 'consent' => array('I this is a new plugin which allows you to create an array inside smarty','e an array you would need to be consistent in weather your arra','can create either one of the two, but not both at the same time mix')),
//            'consent_data3' => array('reference' =>'XZDDCRDCDFDS3','customer' =>'Velocity Software','email'=>'hkumar@velsof.com', 'date'=>'2018-12-12', 'consent' => array('I this is a new plugin which allows you to create an array inside smarty','e an array you would need to be consistent in weather your arra','can create either one of the two, but not both at the same time mix')),
//            );
        return $orders_consent;
    }

    private function installGDPRtableNdHook()
    {
        $this->registerHook('customSuperCheckoutGDPRHook');
        $table_customer_consent ='CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_customer_consent` (
                                `id_velsof_supercheckout_customer_consent` int(11) NOT NULL AUTO_INCREMENT,
                                `id_customer` int(11) DEFAULT NULL,
                                `id_order` int(11) DEFAULT NULL,
                                `order_reference` varchar(15) DEFAULT NULL,
                                `id_lang` int(11) NOT NULL,
                                `accepted_consent` varchar(8000) DEFAULT NULL,
                                PRIMARY KEY (`id_velsof_supercheckout_customer_consent`)
                               ) CHARACTER SET utf8 COLLATE utf8_general_ci';
        $table_sup_policies = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_policies` (
                                `policy_id` int(11) NOT NULL AUTO_INCREMENT,
                                `url` varchar(1000) DEFAULT NULL,
                                `is_manadatory` tinyint(4) NOT NULL,
                                `status` tinyint(4) NOT NULL,
                                PRIMARY KEY (`policy_id`)
                               ) CHARACTER SET utf8 COLLATE utf8_general_ci';
        $table_policy_lang = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'velsof_supercheckout_policy_lang` (
                                `policy_lang_id` int(11) NOT NULL AUTO_INCREMENT,
                                `policy_id` int(11) NOT NULL,
                                `lang_id` tinyint(4) NOT NULL,
                                `description` varchar(1000) NOT NULL,
                                PRIMARY KEY (`policy_lang_id`)
                               ) CHARACTER SET utf8 COLLATE utf8_general_ci';

        Db::getInstance()->execute($table_customer_consent);
        Db::getInstance()->execute($table_sup_policies);
        Db::getInstance()->execute($table_policy_lang);
        die('success');
    }

    // </editor-fold>
    
    /*
     * Function added by Anshul to delete the email from Marketing services
     */
    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            if (Module::isInstalled('supercheckout')) {
                $config = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
                if ($config['enable'] == 1 && $config['email_marketing_delete'] == 1) {
                    $this->deleteEmailFromMarketingServices($customer['email']);
                    return json_encode(true);
                }
            }
        }
    }

    /*
     * Function added by Anshul to delete the email from Marketing services
     */
    public function deleteEmailFromMarketingServices($email)
    {
        $config = json_decode(Configuration::get('VELOCITY_SUPERCHECKOUT'), true);
        if ($config['email_marketing_delete'] == 1) {
            try {
                if (!class_exists('KbMailChimp')) {
                    include_once _PS_MODULE_DIR_ . 'supercheckout/libraries/mailchimpl library.php';
                }
                $api_key = $config['mailchimp']['api'];
                $list_id = $config['mailchimp']['list'];
                $Mailchimp = new KbMailChimp($api_key);
                $subscriber_hash = $Mailchimp->subscriberHash(trim($email));
                $result = $Mailchimp->delete("lists/$list_id/members/$subscriber_hash");
            } catch (\Exception $e) {
//                 $e->getMessage();
            }
        }
        if ($config['email_marketing_delete'] == 1) {
            try {
                if (!class_exists('KbSuperMailin')) {
                    include_once(dirname(__FILE__) . '/libraries/sendinBlue/Mailin.php');
                }
                $api_key = $config['SendinBlue']['api'];
                $list_id = $config['SendinBlue']['list'];

                $mailin = new KbSuperMailin('https://api.sendinblue.com/v2.0', $api_key);
                $data_arr = array("email" => $email
                );
                $mailin->delete_user($data_arr); //calling function to add user
                 //start by dharmanshu for the v3 compatiblity of delete mail
                $array = [];
                $curl = curl_init();
                $array['emails'] = [$email];
                $emails = json_encode($array, true);
                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.sendinblue.com/v3/contacts/lists/".$list_id."/contacts/remove",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => $emails,
                  CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "api-key: ".$api_key."",
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: fc4bc949-4ef8-2e93-3b84-6b0331a6f5ba"
                  ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                //end by dharmanshu for the v3 compatiblity of delete mail
            } catch (\Exception $e) {
            }
        }
        if ($config['email_marketing_delete'] == 1) {
            try {
                $api_key = $config['klaviyo']['api'];
                $list_id = $config['klaviyo']['list'];
                $fields = array(
                    'api_key=' . $api_key,
                    'email=' . urlencode($email)
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://a.klaviyo.com/api/v1/list/' . $list_id . '/members/exclude');
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $fields));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($ch);
                curl_close($ch);
            } catch (\Exception $e) {
            }
        }
    }
    
    public function processCustomerProfileData($data = array())
    {
        $configuration = $data;
        
        //Payment Address
        if (!isset($configuration['profile_payment_address']['firstname']['logged']['require'])) {
            $configuration['profile_payment_address']['firstname']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['firstname']['logged']['display'])) {
            $configuration['profile_payment_address']['firstname']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['lastname']['logged']['require'])) {
            $configuration['profile_payment_address']['lastname']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['lastname']['logged']['display'])) {
            $configuration['profile_payment_address']['lastname']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['company']['logged']['require'])) {
            $configuration['profile_payment_address']['company']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['company']['logged']['display'])) {
            $configuration['profile_payment_address']['company']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['vat_number']['logged']['require'])) {
            $configuration['profile_payment_address']['vat_number']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['vat_number']['logged']['display'])) {
            $configuration['profile_payment_address']['vat_number']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['dni']['logged']['require'])) {
            $configuration['profile_payment_address']['dni']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['dni']['logged']['display'])) {
            $configuration['profile_payment_address']['dni']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['address1']['logged']['require'])) {
            $configuration['profile_payment_address']['address1']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['address1']['logged']['display'])) {
            $configuration['profile_payment_address']['address1']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['address1']['logged']['require'])) {
            $configuration['profile_payment_address']['address1']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['address1']['logged']['display'])) {
            $configuration['profile_payment_address']['address1']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['address2']['logged']['require'])) {
            $configuration['profile_payment_address']['address2']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['address2']['logged']['display'])) {
            $configuration['profile_payment_address']['address2']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['postcode']['logged']['require'])) {
            $configuration['profile_payment_address']['postcode']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['postcode']['logged']['display'])) {
            $configuration['profile_payment_address']['postcode']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['city']['logged']['require'])) {
            $configuration['profile_payment_address']['city']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['city']['logged']['display'])) {
            $configuration['profile_payment_address']['city']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['id_state']['logged']['require'])) {
            $configuration['profile_payment_address']['id_state']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['id_state']['logged']['display'])) {
            $configuration['profile_payment_address']['id_state']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['id_country']['logged']['require'])) {
            $configuration['profile_payment_address']['id_country']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['id_country']['logged']['display'])) {
            $configuration['profile_payment_address']['id_country']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['phone']['logged']['require'])) {
            $configuration['profile_payment_address']['phone']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['phone']['logged']['display'])) {
            $configuration['profile_payment_address']['phone']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['phone_mobile']['logged']['require'])) {
            $configuration['profile_payment_address']['phone_mobile']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['phone_mobile']['logged']['display'])) {
            $configuration['profile_payment_address']['phone_mobile']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['alias']['logged']['require'])) {
            $configuration['profile_payment_address']['alias']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['alias']['logged']['display'])) {
            $configuration['profile_payment_address']['alias']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_payment_address']['other']['logged']['require'])) {
            $configuration['profile_payment_address']['other']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_payment_address']['other']['logged']['display'])) {
            $configuration['profile_payment_address']['other']['logged']['display'] = 0;
        }

        //Shipping Address
        if (!isset($configuration['profile_shipping_address']['firstname']['logged']['require'])) {
            $configuration['profile_shipping_address']['firstname']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['firstname']['logged']['display'])) {
            $configuration['profile_shipping_address']['firstname']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['lastname']['logged']['require'])) {
            $configuration['profile_shipping_address']['lastname']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['lastname']['logged']['display'])) {
            $configuration['profile_shipping_address']['lastname']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['company']['logged']['require'])) {
            $configuration['profile_shipping_address']['company']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['company']['logged']['display'])) {
            $configuration['profile_shipping_address']['company']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['vat_number']['logged']['require'])) {
            $configuration['profile_shipping_address']['vat_number']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['vat_number']['logged']['display'])) {
            $configuration['profile_shipping_address']['vat_number']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['dni']['logged']['require'])) {
            $configuration['profile_shipping_address']['dni']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['dni']['logged']['display'])) {
            $configuration['profile_shipping_address']['dni']['logged']['display'] = 0;
        }
        
        if (!isset($configuration['profile_shipping_address']['address1']['logged']['require'])) {
            $configuration['profile_shipping_address']['address1']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['address1']['logged']['display'])) {
            $configuration['profile_shipping_address']['address1']['logged']['display'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['address1']['logged']['require'])) {
            $configuration['profile_shipping_address']['address1']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['address1']['logged']['display'])) {
            $configuration['profile_shipping_address']['address1']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['address2']['logged']['require'])) {
            $configuration['profile_shipping_address']['address2']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['address2']['logged']['display'])) {
            $configuration['profile_shipping_address']['address2']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['postcode']['logged']['require'])) {
            $configuration['profile_shipping_address']['postcode']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['postcode']['logged']['display'])) {
            $configuration['profile_shipping_address']['postcode']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['city']['logged']['require'])) {
            $configuration['profile_shipping_address']['city']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['city']['logged']['display'])) {
            $configuration['profile_shipping_address']['city']['logged']['display'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['id_state']['logged']['require'])) {
            $configuration['profile_shipping_address']['id_state']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['id_state']['logged']['display'])) {
            $configuration['profile_shipping_address']['id_state']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['id_country']['logged']['require'])) {
            $configuration['profile_shipping_address']['id_country']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['id_country']['logged']['display'])) {
            $configuration['profile_shipping_address']['id_country']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['phone']['logged']['require'])) {
            $configuration['profile_shipping_address']['phone']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['phone']['logged']['display'])) {
            $configuration['profile_shipping_address']['phone']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['phone_mobile']['logged']['require'])) {
            $configuration['profile_shipping_address']['phone_mobile']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['phone_mobile']['logged']['display'])) {
            $configuration['profile_shipping_address']['phone_mobile']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['alias']['logged']['require'])) {
            $configuration['profile_shipping_address']['alias']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['alias']['logged']['display'])) {
            $configuration['profile_shipping_address']['alias']['logged']['display'] = 0;
        }

        if (!isset($configuration['profile_shipping_address']['other']['logged']['require'])) {
            $configuration['profile_shipping_address']['other']['logged']['require'] = 0;
        }
        if (!isset($configuration['profile_shipping_address']['other']['logged']['display'])) {
            $configuration['profile_shipping_address']['other']['logged']['display'] = 0;
        }
        
        return $configuration;
    }
}
