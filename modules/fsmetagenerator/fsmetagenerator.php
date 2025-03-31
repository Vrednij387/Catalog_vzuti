<?php
/**
 * Copyright 2023 ModuleFactory
 *
 * @author    ModuleFactory
 * @copyright ModuleFactory all rights reserved.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/vendor/autoload.php';

class FsMetaGenerator extends Module
{
    public $contact_us_url;
    public $generator_keywords;
    public $content_types;
    public $meta_fields;
    public $meta_fields_by_type;
    private $tab_section;
    private static $global_options;
    private static $smarty_registered = false;

    public function __construct()
    {
        $this->name = 'fsmetagenerator';
        $this->tab = 'seo';
        $this->version = '1.4.1';
        $this->author = 'ModuleFactory';
        $this->need_instance = 0;
        $this->ps_versions_compliancy['min'] = '1.7';
        $this->module_key = 'e9aefde4c1971bc60beb3b9948bf685e';
        $this->contact_us_url = 'https://addons.prestashop.com/en/contact-us?id_product=20441';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Automatic Meta Tag Generator');
        $this->description = $this->l('Easy and convenient way to generate automatically meta tags for better SEO.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->tab_section = Tools::getValue('tab_section', 'fsmg_general_tab');

        $context = Context::getContext();
        $id_lang = $context->language->id;

        $generator_keywords_product_features = [];
        $features = Feature::getFeatures($id_lang);
        foreach ($features as $feature) {
            $f = new Feature($feature['id_feature'], $id_lang);
            $generator_keywords_product_features[] = 'feature_' . str_replace('-', '_', FsMetaGeneratorTools::slugify($f->name));
        }

        $this->generator_keywords['general']['page'] = [
            'ps_page_number',
        ];

        $this->generator_keywords['product']['meta_title'] = [
            'product_name',
            // 'product_meta_title',
            'product_short_description',
            'product_meta_description',
            // 'product_meta_keywords',
            'product_ean13',
            'product_upc',
            'product_reference',
            'product_price',
            'product_price_formatted',
            'product_price_wt',
            'product_price_wt_formatted',
            'product_tags',
            'product_link_rewrite',
            'default_category_name',
            'default_category_meta_title',
            'default_category_description',
            'default_category_meta_description',
            'default_category_meta_keywords',
            'default_category_link_rewrite',
            'default_category_parent_categories',
            'default_category_parent_category',
            'manufacturer_name',
            'manufacturer_meta_title',
            'manufacturer_short_description',
            'manufacturer_meta_description',
            'manufacturer_meta_keywords',
            'supplier_name',
            'supplier_meta_title',
            'supplier_description',
            'supplier_meta_description',
            'supplier_meta_keywords',
            'ps_shop_name',
            'features',
            'features_with_name',
        ];

        $this->generator_keywords['product']['meta_title'] =
            array_merge($this->generator_keywords['product']['meta_title'], $generator_keywords_product_features);

        $this->generator_keywords['product']['meta_keywords'] = [
            'product_name',
            'product_meta_title',
            'product_short_description',
            'product_meta_description',
            // 'product_meta_keywords',
            'product_ean13',
            'product_upc',
            'product_reference',
            'product_price',
            'product_price_formatted',
            'product_price_wt',
            'product_price_wt_formatted',
            'product_tags',
            'product_link_rewrite',
            'default_category_name',
            'default_category_meta_title',
            'default_category_description',
            'default_category_meta_description',
            'default_category_meta_keywords',
            'default_category_link_rewrite',
            'default_category_parent_categories',
            'default_category_parent_category',
            'manufacturer_name',
            'manufacturer_meta_title',
            'manufacturer_short_description',
            'manufacturer_meta_description',
            'manufacturer_meta_keywords',
            'supplier_name',
            'supplier_meta_title',
            'supplier_description',
            'supplier_meta_description',
            'supplier_meta_keywords',
            'ps_shop_name',
            'features',
            'features_with_name',
        ];

        $this->generator_keywords['product']['meta_keywords'] =
            array_merge($this->generator_keywords['product']['meta_keywords'], $generator_keywords_product_features);

        $this->generator_keywords['product']['meta_description'] = [
            'product_name',
            'product_meta_title',
            'product_short_description',
            // 'product_meta_description',
            // 'product_meta_keywords',
            'product_ean13',
            'product_upc',
            'product_reference',
            'product_price',
            'product_price_formatted',
            'product_price_wt',
            'product_price_wt_formatted',
            'product_tags',
            'product_link_rewrite',
            'default_category_name',
            'default_category_meta_title',
            'default_category_description',
            'default_category_meta_description',
            'default_category_meta_keywords',
            'default_category_link_rewrite',
            'default_category_parent_categories',
            'default_category_parent_category',
            'manufacturer_name',
            'manufacturer_meta_title',
            'manufacturer_short_description',
            'manufacturer_meta_description',
            'manufacturer_meta_keywords',
            'supplier_name',
            'supplier_meta_title',
            'supplier_description',
            'supplier_meta_description',
            'supplier_meta_keywords',
            'ps_shop_name',
            'features',
            'features_with_name',
        ];

        $this->generator_keywords['product']['meta_description'] =
            array_merge($this->generator_keywords['product']['meta_description'], $generator_keywords_product_features);

        $this->generator_keywords['product']['link_rewrite'] = [
            'product_name',
            'product_meta_title',
            // 'product_meta_keywords',
            'product_ean13',
            'product_upc',
            'product_reference',
            'product_price',
            'product_tags',
            'default_category_name',
            'default_category_meta_title',
            'default_category_link_rewrite',
            'manufacturer_name',
            'manufacturer_meta_title',
            'supplier_name',
            'supplier_meta_title',
        ];

        $this->generator_keywords['product']['link_rewrite'] =
            array_merge($this->generator_keywords['product']['link_rewrite'], $generator_keywords_product_features);

        $this->generator_keywords['category']['meta_title'] = [
            'category_name',
            // 'category_meta_title',
            'category_description',
            'category_meta_description',
            'category_meta_keywords',
            'category_link_rewrite',
            'category_parent_categories',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['category']['meta_keywords'] = [
            'category_name',
            'category_meta_title',
            'category_description',
            'category_meta_description',
            // 'category_meta_keywords',
            'category_link_rewrite',
            'category_parent_categories',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['category']['meta_description'] = [
            'category_name',
            'category_meta_title',
            'category_description',
            // 'category_meta_description',
            'category_meta_keywords',
            'category_link_rewrite',
            'category_parent_categories',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['manufacturer']['meta_title'] = [
            'manufacturer_name',
            // 'manufacturer_meta_title',
            'manufacturer_short_description',
            'manufacturer_meta_description',
            'manufacturer_meta_keywords',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['manufacturer']['meta_keywords'] = [
            'manufacturer_name',
            'manufacturer_meta_title',
            'manufacturer_short_description',
            'manufacturer_meta_description',
            // 'manufacturer_meta_keywords',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['manufacturer']['meta_description'] = [
            'manufacturer_name',
            'manufacturer_meta_title',
            'manufacturer_short_description',
            // 'manufacturer_meta_description',
            'manufacturer_meta_keywords',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['supplier']['meta_title'] = [
            'supplier_name',
            // 'supplier_meta_title',
            'supplier_description',
            'supplier_meta_description',
            'supplier_meta_keywords',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['supplier']['meta_keywords'] = [
            'supplier_name',
            'supplier_meta_title',
            'supplier_description',
            'supplier_meta_description',
            // 'supplier_meta_keywords',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['supplier']['meta_description'] = [
            'supplier_name',
            'supplier_meta_title',
            'supplier_description',
            // 'supplier_meta_description',
            'supplier_meta_keywords',
            'ps_shop_name',
            'ps_page_number',
            'ps_page_display',
        ];

        $this->generator_keywords['cms']['meta_title'] = [
            'cms_name',
            // 'cms_meta_title',
            'cms_meta_description',
            'cms_meta_keywords',
            'cms_link_rewrite',
            'cms_category_name',
            'cms_category_meta_title',
            'cms_category_description',
            'cms_category_meta_description',
            'cms_category_meta_keywords',
            'cms_category_link_rewrite',
            'cms_category_parent_categories',
            'ps_shop_name',
        ];

        $this->generator_keywords['cms']['meta_keywords'] = [
            'cms_name',
            'cms_meta_title',
            'cms_meta_description',
            // 'cms_meta_keywords',
            'cms_link_rewrite',
            'cms_category_name',
            'cms_category_meta_title',
            'cms_category_description',
            'cms_category_meta_description',
            'cms_category_meta_keywords',
            'cms_category_link_rewrite',
            'cms_category_parent_categories',
            'ps_shop_name',
        ];

        $this->generator_keywords['cms']['meta_description'] = [
            'cms_name',
            'cms_meta_title',
            // 'cms_meta_description',
            'cms_meta_keywords',
            'cms_link_rewrite',
            'cms_category_name',
            'cms_category_meta_title',
            'cms_category_description',
            'cms_category_meta_description',
            'cms_category_meta_keywords',
            'cms_category_link_rewrite',
            'cms_category_parent_categories',
            'ps_shop_name',
        ];

        $this->generator_keywords['cms_category']['meta_title'] = [
            'cms_category_name',
            // 'cms_category_meta_title',
            'cms_category_description',
            'cms_category_meta_description',
            'cms_category_meta_keywords',
            'cms_category_link_rewrite',
            'cms_category_parent_categories',
            'ps_shop_name',
        ];

        $this->generator_keywords['cms_category']['meta_keywords'] = [
            'cms_category_name',
            'cms_category_meta_title',
            'cms_category_description',
            'cms_category_meta_description',
            // 'cms_category_meta_keywords',
            'cms_category_link_rewrite',
            'cms_category_parent_categories',
            'ps_shop_name',
        ];

        $this->generator_keywords['cms_category']['meta_description'] = [
            'cms_category_name',
            'cms_category_meta_title',
            'cms_category_description',
            // 'cms_category_meta_description',
            'cms_category_meta_keywords',
            'cms_category_link_rewrite',
            'cms_category_parent_categories',
            'ps_shop_name',
        ];

        $this->content_types = [
            'product' => $this->l('Product'),
            'category' => $this->l('Category'),
            'manufacturer' => $this->l('Manufacturer'),
            'supplier' => $this->l('Supplier'),
            'cms' => $this->l('CMS'),
            'cms_category' => $this->l('CMS Category'),
        ];

        $this->meta_fields = [
            'meta_title' => $this->l('Meta Title'),
            'meta_description' => $this->l('Meta Description'),
            'meta_keywords' => $this->l('Meta Keywords'),
        ];

        $this->meta_fields_by_type = [];
        foreach (array_keys($this->content_types) as $i) {
            $this->meta_fields_by_type[$i] = $this->meta_fields;
        }
        unset($this->meta_fields_by_type['cms']['meta_title']);

        if (!self::$smarty_registered) {
            smartyRegisterFunction(
                $this->context->smarty,
                'modifier',
                'fsmgCorrectTheMess',
                ['FsMetaGeneratorTools', 'unescapeSmarty'],
                false
            );
            smartyRegisterFunction(
                $this->context->smarty,
                'block',
                'fsmgMinifyCss',
                ['FsMetaGeneratorTools', 'minifyCss'],
                false
            );
            self::$smarty_registered = true;
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $return = parent::install();
        $return = $return && $this->registerHook('displayHeader');
        $return = $return && $this->registerHook('actionObjectProductUpdateAfter');
        $return = $return && $this->registerHook('actionObjectCategoryUpdateAfter');
        $return = $return && $this->registerHook('actionObjectManufacturerUpdateAfter');
        $return = $return && $this->registerHook('actionObjectSupplierUpdateAfter');
        $return = $return && $this->installDB();

        $tab = Tab::getInstanceFromClassName('AdminFsMetaGenerator', Configuration::get('PS_LANG_DEFAULT'));
        if (!$tab->module) {
            $tab = new Tab();
            $tab->id_parent = 0;
            $tab->position = 0;
            $tab->module = $this->name;
            $tab->class_name = 'AdminFsMetaGenerator';
            $tab->active = 1;
            $tab->name = $this->generateMultilangualFields($this->displayName);
            $tab->save();
        }

        return $return;
    }

    public function installDB()
    {
        $return = true;

        $default_options = $this->getDefaultOptions();
        foreach ($default_options as $default_option_key => $default_option_value) {
            $return = $return && Configuration::updateValue($default_option_key, $default_option_value, true);
        }

        $return = $return && Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fsmg_product_meta` (
                `id_fsmg_product_meta` int unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                `categories` text NOT NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_fsmg_product_meta`),
                KEY `id_shop` (`id_shop`),
                KEY `active` (`active`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;');

        $return = $return && Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'fsmg_product_meta_lang` (
                `id_fsmg_product_meta` int unsigned NOT NULL AUTO_INCREMENT,
                `id_lang` int(10) unsigned NOT NULL ,
                `meta_title_schema` varchar(255) NOT NULL,
                `meta_description_schema` varchar(255) NOT NULL,
                `meta_keywords_schema` varchar(255) NOT NULL,
                PRIMARY KEY (`id_fsmg_product_meta`, `id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;');

        return $return;
    }

    public function uninstall()
    {
        $return = parent::uninstall();
        $return = $return && $this->uninstallDB();

        $tab = Tab::getInstanceFromClassName('AdminFsMetaGenerator', Configuration::get('PS_LANG_DEFAULT'));
        if ($tab->module) {
            $tab->delete();
        }

        return $return;
    }

    public function uninstallDB()
    {
        $return = true;
        $option_keys = $this->getOptionKeys();
        foreach ($option_keys as $option_key) {
            $return = $return && Configuration::deleteByName($option_key);
        }
        $return = $return && Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'fsmg_product_meta`');
        $return = $return && Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'fsmg_product_meta_lang`');

        return $return;
    }

    // #################### OPTIONS ####################

    public function getDefaultOptions()
    {
        $default_options = [
            'FSMG_SECRET' => FsMetaGeneratorTools::rnd(40),
            'FSMG_GEN_PAGE_SCHEMA' => self::generateMultilangualFieldsStatic('- ({ps_page_number})'),
            'FSMG_GEN_APPEND_PAGE_DISPLAY' => 1,
            'FSMG_GEN_APPEND_SHOP_NAME' => 1,
            'FSMG_GEN_LENGTH_META_TITLE' => 0,
            'FSMG_GEN_LENGTH_META_DESC' => 0,
            'FSMG_GEN_LENGTH_META_KEYWORDS' => 0,

            // Product
            'FSMG_SCH_PROD_META_TITLE' => self::generateMultilangualFieldsStatic(
                '{product_name} - {default_category_name} - {manufacturer_name}'
            ),
            'FSMG_SCH_PROD_META_DESC' => self::generateMultilangualFieldsStatic(
                '{product_short_description} {default_category_meta_description}'
            ),
            'FSMG_SCH_PROD_META_KEYWORDS' => self::generateMultilangualFieldsStatic(
                '{product_name} {default_category_parent_categories} {manufacturer_name}'
            ),
            'FSMG_SCH_PROD_LINK_REWRITE' => self::generateMultilangualFieldsStatic(
                ''
            ),
            'FSMG_GEN_PROD_META_UPDATE' => true,
            'FSMG_GEN_PROD_META_OVERWRITE' => true,

            // Category
            'FSMG_SCH_CAT_META_TITLE' => self::generateMultilangualFieldsStatic(
                '{category_name} {ps_page_display} - {ps_shop_name}'
            ),
            'FSMG_SCH_CAT_META_DESC' => self::generateMultilangualFieldsStatic(
                '{category_description}'
            ),
            'FSMG_SCH_CAT_META_KEYWORDS' => self::generateMultilangualFieldsStatic(
                '{category_name} {category_parent_categories}'
            ),
            'FSMG_GEN_CAT_META_UPDATE' => false,
            'FSMG_GEN_CAT_META_OVERWRITE' => true,

            // Manufacturer
            'FSMG_SCH_MANU_META_TITLE' => self::generateMultilangualFieldsStatic(
                '{manufacturer_name} {ps_page_display} - {ps_shop_name}'
            ),
            'FSMG_SCH_MANU_META_DESC' => self::generateMultilangualFieldsStatic(
                'The best products from {manufacturer_name}.'
            ),
            'FSMG_SCH_MANU_META_KEYWORDS' => self::generateMultilangualFieldsStatic(
                '{manufacturer_name} {manufacturer_short_description}'
            ),
            'FSMG_GEN_MANU_META_UPDATE' => false,
            'FSMG_GEN_MANU_META_OVERWRITE' => true,

            // Supplier
            'FSMG_SCH_SUPPLIER_META_TITLE' => self::generateMultilangualFieldsStatic(
                '{supplier_name} {ps_page_display} - {ps_shop_name}'
            ),
            'FSMG_SCH_SUPPLIER_META_DESC' => self::generateMultilangualFieldsStatic(
                'The best products from our {supplier_name} supplier.'
            ),
            'FSMG_SCH_SUPPLIER_META_KEYWORDS' => self::generateMultilangualFieldsStatic(
                '{supplier_name} {supplier_description}'
            ),
            'FSMG_GEN_SUPPLIER_META_UPDATE' => false,
            'FSMG_GEN_SUPPLIER_META_OVERWRITE' => true,

            // CMS
            'FSMG_SCH_CMS_META_TITLE' => self::generateMultilangualFieldsStatic(
                '{cms_name} - {cms_category_parent_categories} - {ps_shop_name}'
            ),
            'FSMG_SCH_CMS_META_DESC' => self::generateMultilangualFieldsStatic(
                'Useful information about {cms_name}.'
            ),
            'FSMG_SCH_CMS_META_KEYWORDS' => self::generateMultilangualFieldsStatic(
                '{cms_name} {cms_category_name} {cms_category_meta_keywords} {cms_category_parent_categories}'
            ),

            // CMS Category
            'FSMG_SCH_CMS_CAT_META_TITLE' => self::generateMultilangualFieldsStatic(
                '{cms_category_parent_categories} - {ps_shop_name}'
            ),
            'FSMG_SCH_CMS_CAT_META_DESC' => self::generateMultilangualFieldsStatic(
                'Useful information about {cms_category_name}.'
            ),
            'FSMG_SCH_CMS_CAT_META_KEYWORDS' => self::generateMultilangualFieldsStatic(
                '{cms_category_name} {cms_category_parent_categories}'
            ),
        ];

        return $default_options;
    }

    public function getOptionKeys()
    {
        return array_keys($this->getDefaultOptions());
    }

    public function getMultilangualOptionKeys()
    {
        $multilangual_option_keys = [];
        foreach ($this->getDefaultOptions() as $key => $value) {
            if (is_array($value)) {
                $multilangual_option_keys[] = $key;
            }
        }

        return $multilangual_option_keys;
    }

    public function getGlobalOptions()
    {
        if (self::$global_options === null) {
            if (Shop::isFeatureActive()) {
                $id_shop = $this->context->shop->id;
                $id_shop_group = $this->context->shop->id_shop_group;
                $id_language = $this->context->language->id;
                self::$global_options = Configuration::getMultiple(
                    $this->getOptionKeys(),
                    null,
                    $id_shop_group,
                    $id_shop
                );
                if ($this->getMultilangualOptionKeys()) {
                    foreach ($this->getMultilangualOptionKeys() as $multilang_option_key) {
                        self::$global_options[$multilang_option_key] = Configuration::get(
                            $multilang_option_key,
                            $id_language,
                            $id_shop_group,
                            $id_shop
                        );
                    }
                }
            } else {
                self::$global_options = Configuration::getMultiple($this->getOptionKeys());
                if ($this->getMultilangualOptionKeys()) {
                    foreach ($this->getMultilangualOptionKeys() as $multilang_option_key) {
                        self::$global_options[$multilang_option_key] = Configuration::get(
                            $multilang_option_key,
                            $this->context->language->id
                        );
                    }
                }
            }
        }

        return self::$global_options;
    }

    // #################### ADMIN ####################

    public function getContent()
    {
        $context = Context::getContext();
        $option_keys = $this->getOptionKeys();
        $multilangual_option_keys = $this->getMultilangualOptionKeys();
        $context->controller->addCSS($this->_path . 'views/css/fsmg-font-awesome.min.css', 'all');
        $context->controller->addCSS($this->_path . 'views/css/admin.css', 'all');
        $context->controller->addCSS($this->_path . 'views/css/sweetalert.css', 'all');
        $context->controller->addJS($this->_path . 'views/js/admin.js');
        $context->controller->addJS($this->_path . 'views/js/sweetalert.min.js');

        $html = $this->getCssAndJs();
        $html .= FsMetaGeneratorMessenger::getMessagesHtml();

        if (Tools::isSubmit('save_' . $this->name)) {
            $form_values = [];
            foreach ($option_keys as $option_key) {
                if (Tools::isSubmit($option_key)) {
                    $form_values[$option_key] = Tools::getValue($option_key);
                }
            }

            if ($multilangual_option_keys) {
                foreach ($multilangual_option_keys as $multilang_option_key) {
                    if (FsMetaGeneratorTools::isSubmitMultilang($multilang_option_key)) {
                        $form_values[$multilang_option_key] = self::getMultilangualValue($multilang_option_key);
                    }
                }
            }

            // If MultiShop enabled and not in the All Context we check that the field approved for save
            $form_values = FsMetaGeneratorHelperFormMultiShop::handleMultiShop($form_values);

            $valid = true;

            if ($valid) {
                foreach ($form_values as $option_key => $form_value) {
                    Configuration::updateValue($option_key, $form_value, true);
                }

                FsMetaGeneratorMessenger::addSuccessMessage($this->l('Update successful'));
            }

            FsMetaGeneratorTools::redirect($this->url() . '&tab_section=' . $this->tab_section);
        } elseif (Tools::isSubmit('save_fsmg_product_meta_' . $this->name)) {
            $is_new = false;
            $error = false;
            $id_fsmg_product_meta = Tools::getValue('id_fsmg_product_meta', null);

            if ($id_fsmg_product_meta) {
                $fsmg_product_meta = new FsMetaGeneratorProductMetaModel($id_fsmg_product_meta);
            } else {
                $fsmg_product_meta = new FsMetaGeneratorProductMetaModel();
                $is_new = true;
            }

            $fsmg_product_meta->copyFromPost();
            $fsmg_product_meta->id_shop = $context->shop->id;

            $selected_categories = json_decode($fsmg_product_meta->categories, true);
            if ($selected_categories) {
                foreach ($selected_categories as $id_category) {
                    if (FsMetaGeneratorProductMetaModel::isCategoryUsedByOtherSchema(
                        $id_category,
                        $id_fsmg_product_meta
                    )) {
                        $error = true;
                        $c = new Category($id_category, $context->language->id);
                        $error_message = $this->l(sprintf('Selected category "%s" used by another template', $c->name));
                        FsMetaGeneratorMessenger::addErrorMessage($error_message);
                    }
                }
            } else {
                $error = true;
            }

            if (!$error && $fsmg_product_meta->validateFields(false) && $fsmg_product_meta->validateFieldsLang(false)) {
                $fsmg_product_meta->save();
                if ($is_new) {
                    FsMetaGeneratorMessenger::addSuccessMessage($this->l('Creation successful'));
                    FsMetaGeneratorTools::redirect($this->url() . '&tab_section=fsmg_product_tab');
                } else {
                    FsMetaGeneratorMessenger::addSuccessMessage($this->l('Update successful'));
                    FsMetaGeneratorTools::redirectBack($this->url());
                }
            } else {
                FsMetaGeneratorDataTransfer::setData($_REQUEST);
                $error_message = $this->l('Please select at least one category');
                FsMetaGeneratorMessenger::addErrorMessage($error_message);
                FsMetaGeneratorTools::redirectBack($this->url());
            }
        } elseif (Tools::isSubmit('statusfsmg_product_meta')) {
            $id_fsmg_product_meta = Tools::getValue('id_fsmg_product_meta');
            if ($id_fsmg_product_meta) {
                $fsmg_product_meta = new FsMetaGeneratorProductMetaModel((int) $id_fsmg_product_meta);
                if ($fsmg_product_meta->id) {
                    $fsmg_product_meta->toggleStatus();
                }
            }

            FsMetaGeneratorMessenger::addSuccessMessage($this->l('The status has been updated successfully.'));
            FsMetaGeneratorTools::redirectBack($this->url());
        } elseif (Tools::isSubmit('deletefsmg_product_meta')) {
            $id_fsmg_product_meta = Tools::getValue('id_fsmg_product_meta');
            if ($id_fsmg_product_meta) {
                $fsmg_product_meta = new FsMetaGeneratorProductMetaModel((int) $id_fsmg_product_meta);
                if ($fsmg_product_meta->id) {
                    $fsmg_product_meta->delete();
                }
            }

            FsMetaGeneratorMessenger::addSuccessMessage($this->l('Deletion successful'));
            FsMetaGeneratorTools::redirectBack($this->url());
        } elseif (Tools::isSubmit('updatefsmg_product_meta')) {
            $id_fsmg_product_meta = Tools::getValue('id_fsmg_product_meta', null);
            $html .= $this->renderProductCategorySchemaForm($id_fsmg_product_meta);
        } elseif (Tools::isSubmit('add_fsmg_product_meta_' . $this->name)) {
            $html .= $this->renderProductCategorySchemaForm();
        } else {
            $forms_fields_value = Configuration::getMultiple($option_keys);
            if ($multilangual_option_keys) {
                foreach ($multilangual_option_keys as $multilang_option_key) {
                    $forms_fields_value[$multilang_option_key] =
                        self::getMultilangualConfiguration($multilang_option_key);
                }
            }

            $tab_content = [];

            // General tab
            $tab_content_general = $this->renderGeneralSettingsForm($forms_fields_value);
            $tab_content[] = [
                'id' => 'fsmg_general_tab',
                'title' => $this->l('General Settings'),
                'content' => $tab_content_general,
            ];

            // Product tab
            $tab_content_product = $this->renderProductSchemaForm($forms_fields_value);
            $tab_content_product .= $this->renderProductCategorySchemaList();
            $tab_content_product .= $this->renderProductMetaGeneratorForm($forms_fields_value);
            $tab_content_product .= $this->renderProductLinkRewriteGeneratorForm($forms_fields_value);

            $tab_content[] = [
                'id' => 'fsmg_product_tab',
                'title' => $this->l('Product Settings'),
                'content' => $tab_content_product,
            ];

            // Category tab
            $tab_content_category = $this->renderCategorySchemaForm($forms_fields_value);
            $tab_content_category .= $this->renderCategoryMetaGeneratorForm($forms_fields_value);

            $tab_content[] = [
                'id' => 'fsmg_category_tab',
                'title' => $this->l('Category Settings'),
                'content' => $tab_content_category,
            ];

            // Manufacturer tab
            $tab_content_manufacturer = $this->renderManufacturerSchemaForm($forms_fields_value);
            $tab_content_manufacturer .= $this->renderManufacturerMetaGeneratorForm($forms_fields_value);

            $tab_content[] = [
                'id' => 'fsmg_manufacturer_tab',
                'title' => $this->l('Manufacturer Settings'),
                'content' => $tab_content_manufacturer,
            ];

            // Supplier tab
            $tab_content_supplier = $this->renderSupplierSchemaForm($forms_fields_value);
            $tab_content_supplier .= $this->renderSupplierMetaGeneratorForm($forms_fields_value);

            $tab_content[] = [
                'id' => 'fsmg_supplier_tab',
                'title' => $this->l('Supplier Settings'),
                'content' => $tab_content_supplier,
            ];

            // CMS tab
            $tab_content_cms = $this->renderCmsSchemaForm($forms_fields_value);

            $tab_content[] = [
                'id' => 'fsmg_cms_tab',
                'title' => $this->l('CMS Settings'),
                'content' => $tab_content_cms,
            ];

            // CMS tab
            $tab_content_cms_category = $this->renderCmsCategorySchemaForm($forms_fields_value);

            $tab_content[] = [
                'id' => 'fsmg_cms_category_tab',
                'title' => $this->l('CMS Category Settings'),
                'content' => $tab_content_cms_category,
            ];

            // Maintenance tab
            $context->smarty->assign([
                'content_types' => $this->content_types,
                'meta_fields' => $this->meta_fields,
                'content_langs' => Language::getLanguages(false),
            ]);

            $tab_content_maintenance = $this->smartyFetch('admin/maintenance_clear.tpl');

            if (Shop::isFeatureActive() && (Shop::getContext() != Shop::CONTEXT_SHOP)) {
                $msg = $this->l('Please select a shop to able to start maintenance process!');

                $tab_content_maintenance = $msg;
                if (is_callable([$this, 'displayWarning'])) {
                    $tab_content_maintenance = $this->displayWarning($msg);
                } elseif (is_callable([$this, 'displayError'])) {
                    $tab_content_maintenance = $this->displayError($msg);
                }
            }

            $tab_content[] = [
                'id' => 'fsmg_maintenance_tab',
                'title' => $this->l('Maintenance'),
                'content' => $tab_content_maintenance,
            ];

            // Help tab
            $context->smarty->assign([
                'fsmg_contact_us_url' => $this->contact_us_url,
                'module_base_url' => $this->getModuleBaseUrl(),
            ]);

            $tab_content_help = $this->smartyFetch('admin/help.tpl');

            $tab_content[] = [
                'id' => 'fsmg_help_tab',
                'title' => $this->l('Help'),
                'content' => $tab_content_help,
            ];

            $html .= $this->renderTabLayout($tab_content, $this->tab_section);
        }

        return $html;
    }

    public function getCssAndJs()
    {
        $context = Context::getContext();
        $global_options = $this->getGlobalOptions();
        $request_time = time();

        $fsmg_js = [
            'request_token' => sha1($global_options['FSMG_SECRET'] . $request_time),
            'request_time' => $request_time,
            'meta_fields_by_type' => json_encode($this->meta_fields_by_type),
            'generate_meta_url' => $this->adminAjaxUrl(
                'AdminFsMetaGenerator',
                ['ajax' => '1', 'action' => 'generatemeta']
            ),
            'generate_clear_queue_url' => $this->adminAjaxUrl(
                'AdminFsMetaGenerator',
                ['ajax' => '1', 'action' => 'generateclearqueue']
            ),
            'clear_meta_field_url' => $this->adminAjaxUrl(
                'AdminFsMetaGenerator',
                ['ajax' => '1', 'action' => 'clearmetafield']
            ),
        ];

        $context->smarty->assign([
            'fsmg_js' => $fsmg_js,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/css_js.tpl');
    }

    public function generateAvailableKeywordsMultilang($keywords, $input_id)
    {
        $context = Context::getContext();
        $js_function = 'FSMG.addKeywordToInput';
        if (count(Language::getLanguages(false)) > 1) {
            $js_function = 'FSMG.addKeywordToInputMultilang';
        }

        $context->smarty->assign([
            'fsmg_keywords' => $keywords,
            'fsmg_input_id' => $input_id,
            'fsmg_js_function' => $js_function,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/available_keywords.tpl');
    }

    public function renderMetaKeywords($meta_keywords)
    {
        $meta_keywords_array = explode(' ', $meta_keywords);
        if ($meta_keywords_array) {
            foreach ($meta_keywords_array as $key => $keyword) {
                $keyword = trim($keyword);
                if (Tools::strlen($keyword) < 3) {
                    unset($meta_keywords_array[$key]);
                }
            }
            $meta_keywords_array = array_unique($meta_keywords_array);
        }

        $meta_keywords = implode(',', $meta_keywords_array);
        $meta_keywords = str_replace([',,'], [','], $meta_keywords);

        return $meta_keywords;
    }

    public function renderMetaTagAutoAdd($meta_tag, $params)
    {
        if (Configuration::get('FSMG_GEN_APPEND_PAGE_DISPLAY') && isset($params['ps_page_display'])) {
            $meta_tag .= ' ' . $params['ps_page_display'];
        }

        if (Configuration::get('FSMG_GEN_APPEND_SHOP_NAME') && isset($params['ps_shop_name'])) {
            $meta_tag .= ' - ' . $params['ps_shop_name'];
        }

        while (preg_match('(  )', $meta_tag)) {
            $meta_tag = str_replace('  ', ' ', $meta_tag);
        }

        return $meta_tag;
    }

    public function renderMetaTag($schema, $params)
    {
        $meta_tag = $schema;
        if ($params) {
            foreach ($params as $keyword => $value) {
                $meta_tag = str_replace('{' . $keyword . '}', $value, $meta_tag);
            }
            $meta_tag = preg_replace('/{[^}]+}/', '', $meta_tag);
        }

        while (preg_match('(  )', $meta_tag)) {
            $meta_tag = str_replace('  ', ' ', $meta_tag);
        }

        $meta_tag = str_replace('- -', '-', $meta_tag);

        return $meta_tag;
    }

    public function renderPageDisplayVariable($schema, $params)
    {
        $ps_page_display = $this->renderMetaTag($schema, $params);
        if ($params['ps_page_number'] > 1) {
            return $ps_page_display;
        }

        return '';
    }

    public function getPsVariables($id_lang)
    {
        $ps_params = [
            'ps_shop_name' => Configuration::get('PS_SHOP_NAME'),
            'ps_page_number' => (int) Tools::getValue('p', 1),
        ];

        $ps_params['ps_page_display'] = $this->renderPageDisplayVariable(
            Configuration::get('FSMG_GEN_PAGE_SCHEMA', $id_lang),
            $ps_params
        );

        return $ps_params;
    }

    public function executeMaxLength($meta, $field)
    {
        if ($field == 'title') {
            $length = Configuration::get('FSMG_GEN_LENGTH_META_TITLE');
            if ($length && ($length < Tools::strlen($meta))) {
                $meta = FsMetaGeneratorTools::cutTextWholeWords($meta, $length);
                if (Tools::substr($meta, -1) == '-') {
                    $meta = trim(Tools::substr($meta, 0, -1));
                }
            }
        }

        if ($field == 'description') {
            $length = Configuration::get('FSMG_GEN_LENGTH_META_DESC');
            if ($length && ($length < Tools::strlen($meta))) {
                $meta = FsMetaGeneratorTools::cutTextWholeWords($meta, $length);
                if (Tools::substr($meta, -1) == '-') {
                    $meta = trim(Tools::substr($meta, 0, -1));
                }
            }
        }

        if ($field == 'keywords') {
            $count = Configuration::get('FSMG_GEN_LENGTH_META_KEYWORDS');
            if ($count) {
                $meta_array = explode(',', $meta);
                if (count($meta_array) > $count) {
                    $meta_array = array_slice($meta_array, 0, $count);
                }
                $meta = implode(',', $meta_array);
            }
        }

        return $meta;
    }

    // #################### META ####################

    public function getProductParams($id_product, $id_lang, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }

        $p = new Product($id_product, false, $id_lang, $id_shop);
        if (!Validate::isLoadedObject($p)) {
            return false;
        }

        $params = [];
        $params['product_name'] = $p->name;
        $params['product_meta_title'] = $p->meta_title;
        // $params['product_meta_keywords'] = implode(' ', explode(',', $p->meta_keywords));
        $params['product_short_description'] =
            FsMetaGeneratorTools::removeLineBreaks(strip_tags($p->description_short));
        $params['product_meta_description'] = $p->meta_description;
        $params['product_ean13'] = $p->ean13;
        $params['product_upc'] = $p->upc;
        $params['product_reference'] = $p->reference;
        $params['product_price'] = Product::getPriceStatic(
            $p->id,
            false,
            null,
            6,
            null,
            false,
            true,
            1,
            false,
            null,
            null,
            null,
            $p->specificPrice
        );
        $params['product_price_formatted'] = FsMetaGeneratorTools::formatPrice($params['product_price']);
        $params['product_price_wt'] = Product::getPriceStatic(
            $p->id,
            true,
            null,
            6,
            null,
            false,
            true,
            1,
            false,
            null,
            null,
            null,
            $p->specificPrice
        );
        $params['product_price_wt_formatted'] = FsMetaGeneratorTools::formatPrice($params['product_price_wt']);
        $params['product_tags'] = $p->getTags($id_lang);
        $params['product_link_rewrite'] = $p->link_rewrite;

        // Category params
        $c = new Category($p->id_category_default, $id_lang, $id_shop);
        $c_names = [];
        $parent_categories = $c->getParentsCategories($id_lang);
        foreach ($parent_categories as $parent_c) {
            if ($parent_c && !$parent_c['is_root_category']) {
                $c_names[] = $parent_c['name'];
            }
        }
        $params['default_category_name'] = $c->name;
        $params['default_category_meta_title'] = $c->meta_title;
        $params['default_category_description'] = FsMetaGeneratorTools::removeLineBreaks(strip_tags($c->description));
        $params['default_category_meta_description'] = $c->meta_description;
        $params['default_category_meta_keywords'] = implode(' ', explode(',', $c->meta_keywords));
        $params['default_category_link_rewrite'] = $c->link_rewrite;
        $params['default_category_parent_categories'] = implode(' - ', $c_names);
        $counter = 0;
        $c_names2 = [];
        foreach ($parent_categories as $parent_c) {
            if ($parent_c && !$parent_c['is_root_category']) {
                ++$counter;
                if ($counter <= 2) {
                    $c_names2[] = $parent_c['name'];
                }
            }
        }
        $params['default_category_parent_category'] = implode(' - ', $c_names2);

        // Manufacturer params
        $m = new Manufacturer($p->id_manufacturer, $id_lang);
        $params['manufacturer_name'] = $m->name;
        $params['manufacturer_meta_title'] = $m->meta_title;
        $params['manufacturer_short_description'] =
            FsMetaGeneratorTools::removeLineBreaks(strip_tags($m->short_description));
        $params['manufacturer_meta_description'] = $m->meta_description;
        $params['manufacturer_meta_keywords'] = implode(' ', explode(',', $m->meta_keywords));

        // Supplier params
        $s = new Supplier($p->id_supplier, $id_lang);
        $params['supplier_name'] = $s->name;
        $params['supplier_meta_title'] = $s->meta_title;
        $params['supplier_description'] = FsMetaGeneratorTools::removeLineBreaks(strip_tags($s->description));
        $params['supplier_meta_description'] = $s->meta_description;
        $params['supplier_meta_keywords'] = implode(' ', explode(',', $s->meta_keywords));

        // Feature params
        $features = Feature::getFeatures($id_lang);
        foreach ($features as $feature) {
            $f = new Feature($feature['id_feature'], $id_lang);
            $params['feature_' . str_replace('-', '_', FsMetaGeneratorTools::slugify($f->name))] = '';
        }

        $features_value = [];
        $features_value_with_name = [];
        foreach ($p->getFeatures() as $feature) {
            $f = new Feature($feature['id_feature'], $id_lang);
            $fv = new FeatureValue($feature['id_feature_value'], $id_lang);
            $params['feature_' . str_replace('-', '_', FsMetaGeneratorTools::slugify($f->name))] = $fv->value;
            $features_value[] = $fv->value;
            $features_value_with_name[] = $f->name . ': ' . $fv->value;
        }

        if ($features_value) {
            $params['features'] = implode(', ', $features_value);
        }

        if ($features_value_with_name) {
            $params['features_with_name'] = implode(', ', $features_value_with_name);
        }

        return $params;
    }

    public function getProductSchemas($id_product, $id_lang, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }

        $p = new Product($id_product, false, $id_lang, $id_shop);
        if (!Validate::isLoadedObject($p)) {
            return false;
        }

        $schemas = Configuration::getMultiple([
            'FSMG_SCH_PROD_META_TITLE',
            'FSMG_SCH_PROD_META_DESC',
            'FSMG_SCH_PROD_META_KEYWORDS',
        ], $id_lang);

        // Get product schema by category
        $fsmg_product_meta = FsMetaGeneratorProductMetaModel::getByIdCategory($p->id_category_default, $id_lang);
        if (Validate::isLoadedObject($fsmg_product_meta)) {
            if ($fsmg_product_meta->meta_title_schema) {
                $schemas['FSMG_SCH_PROD_META_TITLE'] = $fsmg_product_meta->meta_title_schema;
            }
            if ($fsmg_product_meta->meta_description_schema) {
                $schemas['FSMG_SCH_PROD_META_DESC'] = $fsmg_product_meta->meta_description_schema;
            }
            if ($fsmg_product_meta->meta_keywords_schema) {
                $schemas['FSMG_SCH_PROD_META_KEYWORDS'] = $fsmg_product_meta->meta_keywords_schema;
            }
        }

        return $schemas;
    }

    public function metaGetProductMetas($id_product, $id_lang, $page_name, $parent_result)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $params = $this->getProductParams($id_product, $id_lang, $id_shop);
        $schemas = $this->getProductSchemas($id_product, $id_lang, $id_shop);
        if (!($params && $schemas)) {
            return $parent_result;
        }

        // PS params
        $params = array_merge($params, $this->getPsVariables($id_lang));
        $params['ps_page_name'] = $page_name;

        // Generate Meta Title
        if (!$params['product_meta_title'] && $schemas['FSMG_SCH_PROD_META_TITLE']) {
            $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_PROD_META_TITLE'], $params);
            $meta_title = $this->executeMaxLength($meta_title, 'title');
            $parent_result['meta_title'] = $meta_title;
        } else {
            if ((bool) $params['product_meta_title']) {
                $parent_result['meta_title'] = $this->renderMetaTagAutoAdd($params['product_meta_title'], $params);
            }
        }

        // Generate Meta Description
        if (!$params['product_meta_description'] && $schemas['FSMG_SCH_PROD_META_DESC']) {
            $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_PROD_META_DESC'], $params);
            $meta_description = $this->executeMaxLength($meta_description, 'description');
            $parent_result['meta_description'] = $meta_description;
        }

        // Generate Meta Keywords
        if ($schemas['FSMG_SCH_PROD_META_KEYWORDS']) {
            $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_PROD_META_KEYWORDS'], $params);
            $meta_keywords = $this->renderMetaKeywords($meta_keywords);
            $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
            $parent_result['meta_keywords'] = $meta_keywords;
        }

        return $parent_result;
    }

    public function updateProductMetas($id_product, $overwrite = false, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }

        $languages = Language::getLanguages(false);
        $p = new FsMetaGeneratorProduct($id_product);

        foreach ($languages as $lang) {
            $params = $this->getProductParams($id_product, $lang['id_lang'], $id_shop);
            $schemas = $this->getProductSchemas($id_product, $lang['id_lang'], $id_shop);
            if (!($params && $schemas)) {
                continue;
            }

            // PS params
            $params = array_merge($params, $this->getPsVariables($lang['id_lang']));
            $params['ps_page_name'] = 'product';

            if (!$p->meta_title[$lang['id_lang']] || $overwrite) {
                $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_PROD_META_TITLE'], $params);
                $meta_title = $this->executeMaxLength($meta_title, 'title');
                $p->meta_title[$lang['id_lang']] = $meta_title;
            }

            if (!$p->meta_description[$lang['id_lang']] || $overwrite) {
                $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_PROD_META_DESC'], $params);
                $meta_description = $this->executeMaxLength($meta_description, 'description');
                $p->meta_description[$lang['id_lang']] = $meta_description;
            }

            /*if (!$p->meta_keywords[$lang['id_lang']] || $overwrite) {
                $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_PROD_META_KEYWORDS'], $params);
                $meta_keywords = $this->renderMetaKeywords($meta_keywords);
                $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
                $p->meta_keywords[$lang['id_lang']] = $meta_keywords;
            }*/
        }

        $p->save();
    }

    public function getCategoryParams($id_category, $id_lang, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }

        $c = new Category($id_category, $id_lang, $id_shop);
        if (!Validate::isLoadedObject($c)) {
            return false;
        }

        $params = [];
        $c_names = [];
        foreach ($c->getParentsCategories($id_lang) as $parent_c) {
            if ($parent_c && !$parent_c['is_root_category']) {
                $c_names[] = $parent_c['name'];
            }
        }
        $params['category_name'] = $c->name;
        $params['category_meta_title'] = $c->meta_title;
        $params['category_description'] = FsMetaGeneratorTools::removeLineBreaks(strip_tags($c->description));
        $params['category_meta_description'] = $c->meta_description;
        $params['category_meta_keywords'] = implode(' ', explode(',', $c->meta_keywords));
        $params['category_link_rewrite'] = $c->link_rewrite;
        $params['category_parent_categories'] = implode(' - ', $c_names);

        return $params;
    }

    public function getCategorySchemas($id_lang)
    {
        return Configuration::getMultiple([
            'FSMG_SCH_CAT_META_TITLE',
            'FSMG_SCH_CAT_META_DESC',
            'FSMG_SCH_CAT_META_KEYWORDS',
        ], $id_lang);
    }

    public function metaGetCategoryMetas($id_category, $id_lang, $page_name, $title, $parent_result)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;

        $params = $this->getCategoryParams($id_category, $id_lang, $id_shop);
        $schemas = $this->getCategorySchemas($id_lang);
        if (!($params && $schemas)) {
            return $parent_result;
        }

        // PS params
        $params = array_merge($params, $this->getPsVariables($id_lang));
        $params['ps_page_name'] = $page_name;
        $params['ps_param_title'] = $title;

        // Generate Meta Title
        if (!$params['category_meta_title'] && $schemas['FSMG_SCH_CAT_META_TITLE']) {
            $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_CAT_META_TITLE'], $params);
            $meta_title = $this->executeMaxLength($meta_title, 'title');
            $parent_result['meta_title'] = $meta_title;
        } else {
            if ((bool) $params['category_meta_title']) {
                $parent_result['meta_title'] = $this->renderMetaTagAutoAdd($params['category_meta_title'], $params);
            }
        }

        // Generate Meta Description
        if (!$params['category_meta_description'] && $schemas['FSMG_SCH_CAT_META_DESC']) {
            $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_CAT_META_DESC'], $params);
            $meta_description = $this->executeMaxLength($meta_description, 'description');
            $parent_result['meta_description'] = $meta_description;
        }

        // Generate Meta Keywords
        if (!$params['category_meta_keywords'] && $schemas['FSMG_SCH_CAT_META_KEYWORDS']) {
            $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_CAT_META_KEYWORDS'], $params);
            $meta_keywords = $this->renderMetaKeywords($meta_keywords);
            $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
            $parent_result['meta_keywords'] = $meta_keywords;
        }

        return $parent_result;
    }

    public function updateCategoryMetas($id_category, $overwrite = false, $id_shop = null)
    {
        if (!$id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }

        $languages = Language::getLanguages(false);
        $c = new FsMetaGeneratorCategory($id_category);

        foreach ($languages as $lang) {
            $params = $this->getCategoryParams($id_category, $lang['id_lang'], $id_shop);
            $schemas = $this->getCategorySchemas($lang['id_lang']);
            if (!($params && $schemas)) {
                continue;
            }

            // PS params
            $params = array_merge($params, $this->getPsVariables($lang['id_lang']));
            $params['ps_page_name'] = 'category';

            if (!$c->meta_title[$lang['id_lang']] || $overwrite) {
                $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_CAT_META_TITLE'], $params);
                $meta_title = $this->executeMaxLength($meta_title, 'title');
                $c->meta_title[$lang['id_lang']] = $meta_title;
            }

            if (!$c->meta_description[$lang['id_lang']] || $overwrite) {
                $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_CAT_META_DESC'], $params);
                $meta_description = $this->executeMaxLength($meta_description, 'description');
                $c->meta_description[$lang['id_lang']] = $meta_description;
            }

            if (!$c->meta_keywords[$lang['id_lang']] || $overwrite) {
                $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_CAT_META_KEYWORDS'], $params);
                $meta_keywords = $this->renderMetaKeywords($meta_keywords);
                $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
                $c->meta_keywords[$lang['id_lang']] = $meta_keywords;
            }
        }

        $c->save();
    }

    public function getManufacturerParams($id_manufacturer, $id_lang)
    {
        $m = new Manufacturer($id_manufacturer, $id_lang);
        if (!Validate::isLoadedObject($m)) {
            return false;
        }

        $params = [];
        $params['manufacturer_name'] = $m->name;
        $params['manufacturer_meta_title'] = $m->meta_title;
        $params['manufacturer_short_description'] =
            FsMetaGeneratorTools::removeLineBreaks(strip_tags($m->short_description));
        $params['manufacturer_meta_description'] = $m->meta_description;
        $params['manufacturer_meta_keywords'] = implode(' ', explode(',', $m->meta_keywords));

        return $params;
    }

    public function getManufacturerSchemas($id_lang)
    {
        return Configuration::getMultiple([
            'FSMG_SCH_MANU_META_TITLE',
            'FSMG_SCH_MANU_META_DESC',
            'FSMG_SCH_MANU_META_KEYWORDS',
        ], $id_lang);
    }

    public function metaGetManufacturerMetas($id_manufacturer, $id_lang, $page_name, $parent_result)
    {
        $params = $this->getManufacturerParams($id_manufacturer, $id_lang);
        $schemas = $this->getManufacturerSchemas($id_lang);
        if (!($params && $schemas)) {
            return $parent_result;
        }

        // PS params
        $params = array_merge($params, $this->getPsVariables($id_lang));
        $params['ps_page_name'] = $page_name;

        // Generate Meta Title
        if (!$params['manufacturer_meta_title'] && $schemas['FSMG_SCH_MANU_META_TITLE']) {
            $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_MANU_META_TITLE'], $params);
            $meta_title = $this->executeMaxLength($meta_title, 'title');
            $parent_result['meta_title'] = $meta_title;
        } else {
            if ((bool) $params['manufacturer_meta_title']) {
                $parent_result['meta_title'] = $this->renderMetaTagAutoAdd($params['manufacturer_meta_title'], $params);
            }
        }

        // Generate Meta Description
        if (!$params['manufacturer_meta_description'] && $schemas['FSMG_SCH_MANU_META_DESC']) {
            $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_MANU_META_DESC'], $params);
            $meta_description = $this->executeMaxLength($meta_description, 'description');
            $parent_result['meta_description'] = $meta_description;
        }

        // Generate Meta Keywords
        if (!$params['manufacturer_meta_keywords'] && $schemas['FSMG_SCH_MANU_META_KEYWORDS']) {
            $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_MANU_META_KEYWORDS'], $params);
            $meta_keywords = $this->renderMetaKeywords($meta_keywords);
            $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
            $parent_result['meta_keywords'] = $meta_keywords;
        }

        return $parent_result;
    }

    public function updateManufacturerMetas($id_manufacturer, $overwrite = false)
    {
        $languages = Language::getLanguages(false);
        $m = new FsMetaGeneratorManufacturer($id_manufacturer);

        foreach ($languages as $lang) {
            $params = $this->getManufacturerParams($id_manufacturer, $lang['id_lang']);
            $schemas = $this->getManufacturerSchemas($lang['id_lang']);
            if (!($params && $schemas)) {
                continue;
            }

            // PS params
            $params = array_merge($params, $this->getPsVariables($lang['id_lang']));
            $params['ps_page_name'] = 'manufacturer';

            if (!$m->meta_title[$lang['id_lang']] || $overwrite) {
                $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_MANU_META_TITLE'], $params);
                $meta_title = $this->executeMaxLength($meta_title, 'title');
                $m->meta_title[$lang['id_lang']] = $meta_title;
            }

            if (!$m->meta_description[$lang['id_lang']] || $overwrite) {
                $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_MANU_META_DESC'], $params);
                $meta_description = $this->executeMaxLength($meta_description, 'description');
                $m->meta_description[$lang['id_lang']] = $meta_description;
            }

            if (!$m->meta_keywords[$lang['id_lang']] || $overwrite) {
                $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_MANU_META_KEYWORDS'], $params);
                $meta_keywords = $this->renderMetaKeywords($meta_keywords);
                $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
                $m->meta_keywords[$lang['id_lang']] = $meta_keywords;
            }
        }

        $m->save();
    }

    public function getSupplierParams($id_supplier, $id_lang)
    {
        $s = new Supplier($id_supplier, $id_lang);
        if (!Validate::isLoadedObject($s)) {
            return false;
        }

        $params = [];
        $params['supplier_name'] = $s->name;
        $params['supplier_meta_title'] = $s->meta_title;
        $params['supplier_description'] = FsMetaGeneratorTools::removeLineBreaks(strip_tags($s->description));
        $params['supplier_meta_description'] = $s->meta_description;
        $params['supplier_meta_keywords'] = implode(' ', explode(',', $s->meta_keywords));

        return $params;
    }

    public function getSupplierSchemas($id_lang)
    {
        return Configuration::getMultiple([
            'FSMG_SCH_SUPPLIER_META_TITLE',
            'FSMG_SCH_SUPPLIER_META_DESC',
            'FSMG_SCH_SUPPLIER_META_KEYWORDS',
        ], $id_lang);
    }

    public function metaGetSupplierMetas($id_supplier, $id_lang, $page_name, $parent_result)
    {
        $params = $this->getSupplierParams($id_supplier, $id_lang);
        $schemas = $this->getSupplierSchemas($id_lang);
        if (!($params && $schemas)) {
            return $parent_result;
        }

        // PS params
        $params = array_merge($params, $this->getPsVariables($id_lang));
        $params['ps_page_name'] = $page_name;

        // Generate Meta Title
        if (!$params['supplier_meta_title'] && $schemas['FSMG_SCH_SUPPLIER_META_TITLE']) {
            $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_SUPPLIER_META_TITLE'], $params);
            $meta_title = $this->executeMaxLength($meta_title, 'title');
            $parent_result['meta_title'] = $meta_title;
        } else {
            if ((bool) $params['supplier_meta_title']) {
                $parent_result['meta_title'] = $this->renderMetaTagAutoAdd($params['supplier_meta_title'], $params);
            }
        }

        // Generate Meta Description
        if (!$params['supplier_meta_description'] && $schemas['FSMG_SCH_SUPPLIER_META_DESC']) {
            $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_SUPPLIER_META_DESC'], $params);
            $meta_description = $this->executeMaxLength($meta_description, 'description');
            $parent_result['meta_description'] = $meta_description;
        }

        // Generate Meta Keywords
        if (!$params['supplier_meta_keywords'] && $schemas['FSMG_SCH_SUPPLIER_META_KEYWORDS']) {
            $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_SUPPLIER_META_KEYWORDS'], $params);
            $meta_keywords = $this->renderMetaKeywords($meta_keywords);
            $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
            $parent_result['meta_keywords'] = $meta_keywords;
        }

        return $parent_result;
    }

    public function updateSupplierMetas($id_supplier, $overwrite = false)
    {
        $languages = Language::getLanguages(false);
        $s = new FsMetaGeneratorSupplier($id_supplier);

        foreach ($languages as $lang) {
            $params = $this->getSupplierParams($id_supplier, $lang['id_lang']);
            $schemas = $this->getSupplierSchemas($lang['id_lang']);
            if (!($params && $schemas)) {
                continue;
            }

            // PS params
            $params = array_merge($params, $this->getPsVariables($lang['id_lang']));
            $params['ps_page_name'] = 'supplier';

            if (!$s->meta_title[$lang['id_lang']] || $overwrite) {
                $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_SUPPLIER_META_TITLE'], $params);
                $meta_title = $this->executeMaxLength($meta_title, 'title');
                $s->meta_title[$lang['id_lang']] = $meta_title;
            }

            if (!$s->meta_description[$lang['id_lang']] || $overwrite) {
                $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_SUPPLIER_META_DESC'], $params);
                $meta_description = $this->executeMaxLength($meta_description, 'description');
                $s->meta_description[$lang['id_lang']] = $meta_description;
            }

            if (!$s->meta_keywords[$lang['id_lang']] || $overwrite) {
                $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_SUPPLIER_META_KEYWORDS'], $params);
                $meta_keywords = $this->renderMetaKeywords($meta_keywords);
                $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
                $s->meta_keywords[$lang['id_lang']] = $meta_keywords;
            }
        }

        $s->save();
    }

    public function metaGetCmsMetas($id_cms, $id_lang, $page_name, $parent_result)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $c = new CMS($id_cms, $id_lang, $id_shop);
        if (!$c->id) {
            return $parent_result;
        }

        $params = [];
        $params['cms_name'] = $c->meta_title;
        $params['cms_meta_title'] = '';
        $params['cms_meta_description'] = $c->meta_description;
        $params['cms_meta_keywords'] = implode(' ', explode(',', $c->meta_keywords));
        $params['cms_link_rewrite'] = $c->link_rewrite;

        // CMS Category Params
        $cc = new CMSCategory($c->id_cms_category, $id_lang, $id_shop);
        if ($cc->id_parent) {
            $params['cms_category_name'] = $cc->name;
            $params['cms_category_meta_title'] = $cc->meta_title;
            $params['cms_category_description'] = FsMetaGeneratorTools::removeLineBreaks(strip_tags($cc->description));
            $params['cms_category_meta_description'] = $cc->meta_description;
            $params['cms_category_meta_keywords'] = $cc->meta_keywords;
            $params['cms_category_link_rewrite'] = $cc->link_rewrite;
            $cc_names = [];
            foreach ($cc->getParentsCategories($id_lang) as $parent_cc) {
                $cc_names[] = $parent_cc['name'];
            }
            $params['cms_category_parent_categories'] = implode(' - ', $cc_names);
        }

        // PS params
        $params = array_merge($params, $this->getPsVariables($id_lang));
        $params['ps_page_name'] = $page_name;

        $schemas = Configuration::getMultiple([
            'FSMG_SCH_CMS_META_TITLE', 'FSMG_SCH_CMS_META_DESC',
            'FSMG_SCH_CMS_META_KEYWORDS',
        ], $id_lang);

        // Generate Meta Title
        if (!$params['cms_meta_title'] && $schemas['FSMG_SCH_CMS_META_TITLE']) {
            $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_CMS_META_TITLE'], $params);
            $meta_title = $this->executeMaxLength($meta_title, 'title');
            $parent_result['meta_title'] = $meta_title;
        } else {
            $parent_result['meta_title'] = $this->renderMetaTagAutoAdd($params['cms_name'], $params);
        }

        // Generate Meta Description
        if (!$params['cms_meta_description'] && $schemas['FSMG_SCH_CMS_META_DESC']) {
            $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_CMS_META_DESC'], $params);
            $meta_description = $this->executeMaxLength($meta_description, 'description');
            $parent_result['meta_description'] = $meta_description;
        }

        // Generate Meta Keywords
        if (!$params['cms_meta_keywords'] && $schemas['FSMG_SCH_CMS_META_KEYWORDS']) {
            $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_CMS_META_KEYWORDS'], $params);
            $meta_keywords = $this->renderMetaKeywords($meta_keywords);
            $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
            $parent_result['meta_keywords'] = $meta_keywords;
        }

        return $parent_result;
    }

    public function metaGetCmsCategoryMetas($id_cms_category, $id_lang, $page_name, $parent_result)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $cc = new CMSCategory($id_cms_category, $id_lang, $id_shop);
        if (!$cc->id) {
            return $parent_result;
        }

        $params = [];
        $params['cms_category_name'] = $cc->name;
        $params['cms_category_meta_title'] = $cc->meta_title;
        $params['cms_category_description'] = FsMetaGeneratorTools::removeLineBreaks(strip_tags($cc->description));
        $params['cms_category_meta_description'] = $cc->meta_description;
        $params['cms_category_meta_keywords'] = $cc->meta_keywords;
        $params['cms_category_link_rewrite'] = $cc->link_rewrite;
        $params['cms_category_parent_categories'] = '';

        if ($cc->id_parent > 0) {
            $cc_names = [];
            foreach ($cc->getParentsCategories($id_lang) as $parent_cc) {
                $cc_names[] = $parent_cc['name'];
            }
            $params['cms_category_parent_categories'] = implode(' - ', $cc_names);
        }

        // PS params
        $params = array_merge($params, $this->getPsVariables($id_lang));
        $params['ps_page_name'] = $page_name;

        $schemas = Configuration::getMultiple([
            'FSMG_SCH_CMS_CAT_META_TITLE', 'FSMG_SCH_CMS_CAT_META_DESC',
            'FSMG_SCH_CMS_CAT_META_KEYWORDS',
        ], $id_lang);

        // Generate Meta Title
        if (!$params['cms_category_meta_title'] && $schemas['FSMG_SCH_CMS_CAT_META_TITLE']) {
            $meta_title = $this->renderMetaTag($schemas['FSMG_SCH_CMS_CAT_META_TITLE'], $params);
            $meta_title = $this->executeMaxLength($meta_title, 'title');
            $parent_result['meta_title'] = $meta_title;
        } else {
            if ((bool) $params['cms_category_meta_title']) {
                $parent_result['meta_title'] = $this->renderMetaTagAutoAdd($params['cms_category_meta_title'], $params);
            }
        }

        // Generate Meta Description
        if (!$params['cms_category_meta_description'] && $schemas['FSMG_SCH_CMS_CAT_META_DESC']) {
            $meta_description = $this->renderMetaTag($schemas['FSMG_SCH_CMS_CAT_META_DESC'], $params);
            $meta_description = $this->executeMaxLength($meta_description, 'description');
            $parent_result['meta_description'] = $meta_description;
        }

        // Generate Meta Keywords
        if (!$params['cms_category_meta_keywords'] && $schemas['FSMG_SCH_CMS_CAT_META_KEYWORDS']) {
            $meta_keywords = $this->renderMetaTag($schemas['FSMG_SCH_CMS_CAT_META_KEYWORDS'], $params);
            $meta_keywords = $this->renderMetaKeywords($meta_keywords);
            $meta_keywords = $this->executeMaxLength($meta_keywords, 'keywords');
            $parent_result['meta_keywords'] = $meta_keywords;
        }

        return $parent_result;
    }

    // ################### FORMS ####################

    protected function renderGeneralSettingsForm($fields_value)
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $page_display_desc = $this->l('Generates the page part into');
        $page_display_desc .= ' <strong>{ps_page_display}</strong> ';
        $page_display_desc .= $this->l('variable if the page number is larger than 1') . '<br />';
        $page_display_desc .= $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['general']['page'],
            'FSMG_GEN_PAGE_SCHEMA_' . $default_lang
        );

        $fields_form = [];
        $input_fields = [];
        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Page display schema:'),
            'lang' => true,
            'name' => 'FSMG_GEN_PAGE_SCHEMA',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $page_display_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Append page display to manually written meta titles:'),
            'name' => 'FSMG_GEN_APPEND_PAGE_DISPLAY',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_APPEND_PAGE_DISPLAY_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_APPEND_PAGE_DISPLAY_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Automatically append the generated page display to end of the manually written meta title'
            ),
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Append shop name to manually written meta titles:'),
            'name' => 'FSMG_GEN_APPEND_SHOP_NAME',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_APPEND_SHOP_NAME_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_APPEND_SHOP_NAME_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l('Automatically append the shop name to end of the manually written meta title'),
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Maximum meta title length:'),
            'lang' => false,
            'name' => 'FSMG_GEN_LENGTH_META_TITLE',
            'size' => 70,
            'required' => false,
            'desc' => $this->l('0 means unlimited length.') . ' ' . $this->l('Google displays about 55 character'),
            'class' => 'fixed-width-md',
            'suffix' => $this->l('character'),
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Maximum meta description length:'),
            'lang' => false,
            'name' => 'FSMG_GEN_LENGTH_META_DESC',
            'size' => 70,
            'required' => false,
            'desc' => $this->l('0 means unlimited length.') . ' ' . $this->l('Google displays about 115 character'),
            'class' => 'fixed-width-md',
            'suffix' => $this->l('character'),
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Maximum meta keywords count:'),
            'lang' => false,
            'name' => 'FSMG_GEN_LENGTH_META_KEYWORDS',
            'size' => 70,
            'required' => false,
            'desc' => $this->l('0 means unlimited piece.'),
            'class' => 'fixed-width-md',
            'suffix' => $this->l('piece'),
            'is_multishop' => true,
        ];

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('General Settings'),
            ],
            'input' => $input_fields,
        ];

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_general_settings');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_general_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderProductSchemaForm($fields_value)
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $meta_title_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['product']['meta_title'],
            'FSMG_SCH_PROD_META_TITLE_' . $default_lang
        );

        $meta_description_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['product']['meta_description'],
            'FSMG_SCH_PROD_META_DESC_' . $default_lang
        );

        $meta_keywords_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['product']['meta_keywords'],
            'FSMG_SCH_PROD_META_KEYWORDS_' . $default_lang
        );

        $fields_form = [];
        $input_fields = [];
        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta title generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_PROD_META_TITLE',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_title_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta description generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_PROD_META_DESC',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_description_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta keywords generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_PROD_META_KEYWORDS',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_keywords_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Keep meta information updated:'),
            'name' => 'FSMG_GEN_PROD_META_UPDATE',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_PROD_META_UPDATE_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_PROD_META_UPDATE_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Every time a product is updated, the meta information will be updated based on the schema'
            ),
            'is_multishop' => true,
        ];

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Product Meta Default Settings'),
            ],
            'input' => $input_fields,
        ];

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_product_default_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_product_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderProductCategorySchemaList()
    {
        // Display shop warning
        if (Shop::isFeatureActive() && (Shop::getContext() != Shop::CONTEXT_SHOP)) {
            $msg = $this->l('Please select a shop to add category specific product meta schema!');

            if (is_callable([$this, 'displayWarning'])) {
                return $this->displayWarning($msg);
            } elseif (is_callable([$this, 'displayError'])) {
                return $this->displayError($msg);
            }

            return $msg;
        }

        $context = Context::getContext();

        $fields_list = [
            'id_fsmg_product_meta' => [
                'title' => $this->l('ID'),
                'width' => 50,
            ],
            'categories' => [
                'title' => $this->l('Categories'),
            ],
            'meta_title_schema' => [
                'title' => $this->l('Has Title Schema'),
                'width' => 20,
                'active' => '',
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-sm',
            ],
            'meta_description_schema' => [
                'title' => $this->l('Has Description Schema'),
                'width' => 20,
                'active' => '',
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-sm',
            ],
            'meta_keywords_schema' => [
                'title' => $this->l('Has Keywords Schema'),
                'width' => 20,
                'active' => '',
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-sm',
            ],
            'active' => [
                'title' => $this->l('Status'),
                'width' => 20,
                'type' => 'bool',
                'active' => 'status',
                'align' => 'center',
                'class' => 'fixed-width-sm',
            ],
        ];

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_fsmg_product_meta';
        $helper->actions = ['edit', 'delete'];
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] = [
            'href' => $this->url() . '&add_fsmg_product_meta_' . $this->name,
            'desc' => $this->l('Add new'),
        ];
        $helper->title = $this->l('Product Meta Settings by Category');
        $helper->table = 'fsmg_product_meta';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $filter_fields = [
            'id_fsmg_product_meta',
            'categories',
            'meta_title_schema',
            'meta_description_schema',
            'meta_keywords_schema',
            'active',
        ];

        $filter = $this->setFilterToCookie(
            $helper->table,
            $filter_fields,
            'id_fsmg_product_meta',
            'fsmg_product_tab'
        );

        $list_content = FsMetaGeneratorProductMetaModel::getListContent($context->language->id, $filter);

        if ($list_content) {
            foreach ($list_content as $key => $row) {
                $categories = json_decode($row['categories']);
                $categories_str = '';
                if ($categories) {
                    foreach ($categories as $id_category) {
                        $c = new Category($id_category, $context->language->id);
                        $categories_str .= $c->name . ', ';
                    }
                    $categories_str = Tools::substr($categories_str, 0, -2);
                }

                if (Tools::strlen($categories_str) > 100) {
                    $categories_str = Tools::substr($categories_str, 0, 100) . '...';
                }

                $list_content[$key]['categories'] = $categories_str;
            }

            $helper->listTotal = FsMetaGeneratorProductMetaModel::getListCount();
        }

        return $helper->generateList($list_content, $fields_list);
    }

    protected function renderProductCategorySchemaForm($id_fsmg_product_meta = null)
    {
        // Display shop warning
        if (Shop::isFeatureActive() && (Shop::getContext() != Shop::CONTEXT_SHOP)) {
            $msg = $this->l('Please select a shop to add category specific product meta schema!');

            if (is_callable([$this, 'displayWarning'])) {
                return $this->displayWarning($msg);
            } elseif (is_callable([$this, 'displayError'])) {
                return $this->displayError($msg);
            }

            return $msg;
        }

        $fields_form = [];
        $context = Context::getContext();
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $meta_title_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['product']['meta_title'],
            'meta_title_schema_' . $default_lang
        );

        $meta_description_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['product']['meta_description'],
            'meta_description_schema_' . $default_lang
        );

        $meta_keywords_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['product']['meta_keywords'],
            'meta_keywords_schema_' . $default_lang
        );

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Product Meta Settings by Category'),
            ],
            'input' => [
                [
                    'type' => 'hidden',
                    'name' => 'id_fsmg_product_meta',
                ],
                [
                    'type' => 'hidden',
                    'name' => 'tab_section',
                ],
                [
                    'type' => 'free',
                    'label' => $this->l('Where the default category is:'),
                    'lang' => false,
                    'name' => 'categories',
                    'required' => false,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Meta title generator schema:'),
                    'lang' => true,
                    'name' => 'meta_title_schema',
                    'size' => 70,
                    'required' => false,
                    'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
                    'desc' => $meta_title_desc,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Meta description generator schema:'),
                    'lang' => true,
                    'name' => 'meta_description_schema',
                    'size' => 70,
                    'required' => false,
                    'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
                    'desc' => $meta_description_desc,
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Meta keywords generator schema:'),
                    'lang' => true,
                    'name' => 'meta_keywords_schema',
                    'size' => 70,
                    'required' => false,
                    'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
                    'desc' => $meta_keywords_desc,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Active:'),
                    'name' => 'active',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = 'fsmg_product_category_meta';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = [
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
            ];
        }
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->show_cancel_button = false;
        $helper->title = $this->l('Product Meta Settings by Category');
        $helper->submit_action = 'save_fsmg_product_meta_' . $this->name;
        $helper->toolbar_btn = [
            'back' => [
                    'href' => $this->url() . '&tab_section=fsmg_product_tab',
                    'desc' => $this->l('Back'),
                ],
        ];

        $fields_form[0]['form']['submit'] = ['title' => $this->l('Save')];
        $fields_form[0]['form']['buttons'][] = [
            'title' => '<i class="process-icon-back"></i>' . $this->l('Back'),
            'href' => $this->url() . '&tab_section=fsmg_product_tab',
        ];

        $data_transfer = FsMetaGeneratorDataTransfer::getData();

        if ($data_transfer) {
            $helper->fields_value['id_fsmg_product_meta'] =
                FsMetaGeneratorTools::getValue('id_fsmg_product_meta', null, $data_transfer);
            $helper->fields_value['meta_title_schema'] =
                FsMetaGeneratorTools::getValue('meta_title_schema', null, $data_transfer);
            $helper->fields_value['meta_description_schema'] =
                FsMetaGeneratorTools::getValue('meta_description_schema', null, $data_transfer);
            $helper->fields_value['meta_keywords_schema'] =
                FsMetaGeneratorTools::getValue('meta_keywords_schema', null, $data_transfer);
            $helper->fields_value['active'] =
                FsMetaGeneratorTools::getValue('active', null, $data_transfer);

            $categories = FsMetaGeneratorTools::getValue('categories', [], $data_transfer);
            if (!is_array($categories)) {
                $categories = [];
            }

            $category_tree = new HelperTreeCategories('categories_tree');
            $category_tree->setInputName('categories');
            $category_tree->setUseCheckBox(true);
            $category_tree->setUseSearch(true);
            $category_tree->setSelectedCategories($categories);
            $category_tree->setRootCategory($context->shop->id_category);
            $helper->fields_value['categories'] = $category_tree->render();
        } else {
            $fsmg_product_meta = new FsMetaGeneratorProductMetaModel($id_fsmg_product_meta);
            $helper->fields_value['id_fsmg_product_meta'] = $fsmg_product_meta->id;
            $helper->fields_value['meta_title_schema'] = $fsmg_product_meta->meta_title_schema;
            $helper->fields_value['meta_description_schema'] = $fsmg_product_meta->meta_description_schema;
            $helper->fields_value['meta_keywords_schema'] = $fsmg_product_meta->meta_keywords_schema;
            $helper->fields_value['active'] = $fsmg_product_meta->active;

            $categories = json_decode($fsmg_product_meta->categories, true);
            if (!is_array($categories)) {
                $categories = [];
            }

            $category_tree = new HelperTreeCategories('categories_tree');
            $category_tree->setInputName('categories');
            $category_tree->setUseCheckBox(true);
            $category_tree->setUseSearch(true);
            $category_tree->setSelectedCategories($categories);
            $category_tree->setRootCategory($context->shop->id_category);
            $helper->fields_value['categories'] = $category_tree->render();
        }

        $helper->fields_value['tab_section'] = 'fsmg_product_tab';

        return $helper->generateForm($fields_form);
    }

    protected function renderProductLinkRewriteGeneratorForm($fields_value)
    {
        $display_shop_warning = false;
        if (Shop::isFeatureActive() && (Shop::getContext() != Shop::CONTEXT_SHOP)) {
            $display_shop_warning = true;
        }

        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $link_rewrite_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['product']['link_rewrite'],
            'FSMG_SCH_PROD_LINK_REWRITE_' . $default_lang
        );

        $this->smartyAssign(['fsmg_progress_bar_id' => 'fsmg_product_link_rewrite_progress_bar']);
        $fields_value['product_link_rewrite_progress_bar'] = $this->smartyFetch('admin/progress_bar.tpl');

        $fields_form = [];
        $input_fields = [];
        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Friendly URL schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_PROD_LINK_REWRITE',
            'size' => 70,
            'required' => false,
            'desc' => $link_rewrite_desc,
            'is_multishop' => true,
        ];

        if (!$display_shop_warning) {
            $input_fields[] = [
                'type' => 'free',
                'label' => $this->l('Status:'),
                'lang' => false,
                'name' => 'product_link_rewrite_progress_bar',
                'required' => false,
            ];
        }

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Product Friendly URL Generator'),
            ],
            'input' => $input_fields,
        ];

        if ($display_shop_warning) {
            $msg = $this->l('Please select a shop to able to start product friendly URL generation!') . ' ';
            $msg .= $this->l('Do NOT change until the end of the generation process!') . ' ';
            $msg .= $this->l('Do NOT change in other browser tab too!');
            $fields_form[0]['form']['warning'] = $msg;
        } else {
            $fields_form[0]['form']['buttons'][] = [
                'title' => '<i class="process-icon-update"></i>' . $this->l('Generate'),
                'href' => 'javascript:;',
                'icon' => 'update',
                'js' => 'FSMG.generateProductLinkRewrite();',
            ];
        }

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_product_link_rewrite');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_product_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderProductMetaGeneratorForm($fields_value)
    {
        $display_shop_warning = false;
        if (Shop::isFeatureActive() && (Shop::getContext() != Shop::CONTEXT_SHOP)) {
            $display_shop_warning = true;
        }

        $this->smartyAssign(['fsmg_progress_bar_id' => 'fsmg_product_meta_progress_bar']);
        $fields_value['product_meta_progress_bar'] = $this->smartyFetch('admin/progress_bar.tpl');

        $fields_form = [];
        $input_fields = [];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Overwrite exist meta:'),
            'name' => 'FSMG_GEN_PROD_META_OVERWRITE',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_PROD_META_OVERWRITE_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_PROD_META_OVERWRITE_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Overwrite the exist meta information with the newly generated'
            ),
            'is_multishop' => true,
        ];

        if (!$display_shop_warning) {
            $input_fields[] = [
                'type' => 'free',
                'label' => $this->l('Status:'),
                'lang' => false,
                'name' => 'product_meta_progress_bar',
                'required' => false,
            ];
        }

        $desc = $this->l('The module automatically generates the meta information based on the schema.') . ' ';
        $desc .= $this->l('If no meta info saved, the generated info going to be used.') . ' ';
        $desc .= $this->l('With the generator you can save the generated meta info based on the schema.') . ' ';

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Product Meta Generator'),
            ],
            'description' => $desc,
            'input' => $input_fields,
        ];

        if ($display_shop_warning) {
            $msg = $this->l('Please select a shop to able to start product meta generation!') . ' ';
            $msg .= $this->l('Do NOT change until the end of the generation process!') . ' ';
            $msg .= $this->l('Do NOT change in other browser tab too!');
            $fields_form[0]['form']['warning'] = $msg;
        } else {
            $fields_form[0]['form']['buttons'][] = [
                'title' => '<i class="process-icon-update"></i>' . $this->l('Generate'),
                'href' => 'javascript:;',
                'icon' => 'update',
                'js' => 'FSMG.generateProductMeta();',
            ];
        }

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_product_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_product_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderCategorySchemaForm($fields_value)
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $meta_title_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['category']['meta_title'],
            'FSMG_SCH_CAT_META_TITLE_' . $default_lang
        );

        $meta_description_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['category']['meta_description'],
            'FSMG_SCH_CAT_META_DESC_' . $default_lang
        );

        $meta_keywords_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['category']['meta_keywords'],
            'FSMG_SCH_CAT_META_KEYWORDS_' . $default_lang
        );

        $fields_form = [];
        $input_fields = [];
        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta title generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CAT_META_TITLE',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_title_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta description generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CAT_META_DESC',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_description_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta keywords generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CAT_META_KEYWORDS',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_keywords_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Keep meta information updated:'),
            'name' => 'FSMG_GEN_CAT_META_UPDATE',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_CAT_META_UPDATE_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_CAT_META_UPDATE_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Every time a category is updated, the meta information will be updated based on the schema'
            ),
            'is_multishop' => true,
        ];

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Category Meta Settings'),
            ],
            'input' => $input_fields,
        ];

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_category_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_category_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderCategoryMetaGeneratorForm($fields_value)
    {
        $display_shop_warning = false;
        if (Shop::isFeatureActive() && (Shop::getContext() != Shop::CONTEXT_SHOP)) {
            $display_shop_warning = true;
        }

        $this->smartyAssign(['fsmg_progress_bar_id' => 'fsmg_category_meta_progress_bar']);
        $fields_value['category_meta_progress_bar'] = $this->smartyFetch('admin/progress_bar.tpl');

        $fields_form = [];
        $input_fields = [];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Overwrite exist meta:'),
            'name' => 'FSMG_GEN_CAT_META_OVERWRITE',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_CAT_META_OVERWRITE_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_CAT_META_OVERWRITE_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Overwrite the exist meta information with the newly generated'
            ),
            'is_multishop' => true,
        ];

        if (!$display_shop_warning) {
            $input_fields[] = [
                'type' => 'free',
                'label' => $this->l('Status:'),
                'lang' => false,
                'name' => 'category_meta_progress_bar',
                'required' => false,
            ];
        }

        $desc = $this->l('The module automatically generates the meta information based on the schema.') . ' ';
        $desc .= $this->l('If no meta info saved, the generated info going to be used.') . ' ';
        $desc .= $this->l('With the generator you can save the generated meta info based on the schema.') . ' ';

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Category Meta Generator'),
            ],
            'description' => $desc,
            'input' => $input_fields,
        ];

        if ($display_shop_warning) {
            $msg = $this->l('Please select a shop to able to start product meta generation!') . ' ';
            $msg .= $this->l('Do NOT change until the end of the generation process!') . ' ';
            $msg .= $this->l('Do NOT change in other browser tab too!');
            $fields_form[0]['form']['warning'] = $msg;
        } else {
            $fields_form[0]['form']['buttons'][] = [
                'title' => '<i class="process-icon-update"></i>' . $this->l('Generate'),
                'href' => 'javascript:;',
                'icon' => 'update',
                'js' => 'FSMG.generateCategoryMeta();',
            ];
        }

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_category_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_category_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderManufacturerSchemaForm($fields_value)
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $meta_title_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['manufacturer']['meta_title'],
            'FSMG_SCH_MANU_META_TITLE_' . $default_lang
        );

        $meta_description_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['manufacturer']['meta_description'],
            'FSMG_SCH_MANU_META_DESC_' . $default_lang
        );

        $meta_keywords_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['manufacturer']['meta_keywords'],
            'FSMG_SCH_MANU_META_KEYWORDS_' . $default_lang
        );

        $fields_form = [];
        $input_fields = [];
        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta title generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_MANU_META_TITLE',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_title_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta description generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_MANU_META_DESC',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_description_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta keywords generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_MANU_META_KEYWORDS',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_keywords_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Keep meta information updated:'),
            'name' => 'FSMG_GEN_MANU_META_UPDATE',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_MANU_META_UPDATE_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_MANU_META_UPDATE_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Every time a manufacturer is updated, the meta information will be updated based on the schema'
            ),
            'is_multishop' => true,
        ];

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Manufacturer Meta Settings'),
            ],
            'input' => $input_fields,
        ];

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_manufacturer_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_manufacturer_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderManufacturerMetaGeneratorForm($fields_value)
    {
        $display_shop_warning = false;
        if (Shop::isFeatureActive() && (Shop::getContext() != Shop::CONTEXT_SHOP)) {
            $display_shop_warning = true;
        }

        $this->smartyAssign(['fsmg_progress_bar_id' => 'fsmg_manufacturer_meta_progress_bar']);
        $fields_value['manufacturer_meta_progress_bar'] = $this->smartyFetch('admin/progress_bar.tpl');

        $fields_form = [];
        $input_fields = [];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Overwrite exist meta:'),
            'name' => 'FSMG_GEN_MANU_META_OVERWRITE',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_MANU_META_OVERWRITE_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_MANU_META_OVERWRITE_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Overwrite the exist meta information with the newly generated'
            ),
            'is_multishop' => true,
        ];

        if (!$display_shop_warning) {
            $input_fields[] = [
                'type' => 'free',
                'label' => $this->l('Status:'),
                'lang' => false,
                'name' => 'manufacturer_meta_progress_bar',
                'required' => false,
            ];
        }

        $desc = $this->l('The module automatically generates the meta information based on the schema.') . ' ';
        $desc .= $this->l('If no meta info saved, the generated info going to be used.') . ' ';
        $desc .= $this->l('With the generator you can save the generated meta info based on the schema.') . ' ';

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Manufacturer Meta Generator'),
            ],
            'description' => $desc,
            'input' => $input_fields,
        ];

        if ($display_shop_warning) {
            $msg = $this->l('Please select a shop to able to start product meta generation!') . ' ';
            $msg .= $this->l('Do NOT change until the end of the generation process!') . ' ';
            $msg .= $this->l('Do NOT change in other browser tab too!');
            $fields_form[0]['form']['warning'] = $msg;
        } else {
            $fields_form[0]['form']['buttons'][] = [
                'title' => '<i class="process-icon-update"></i>' . $this->l('Generate'),
                'href' => 'javascript:;',
                'icon' => 'update',
                'js' => 'FSMG.generateManufacturerMeta();',
            ];
        }

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_manufacturer_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_manufacturer_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderSupplierSchemaForm($fields_value)
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $meta_title_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['supplier']['meta_title'],
            'FSMG_SCH_SUPPLIER_META_TITLE_' . $default_lang
        );

        $meta_description_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['supplier']['meta_description'],
            'FSMG_SCH_SUPPLIER_META_DESC_' . $default_lang
        );

        $meta_keywords_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['supplier']['meta_keywords'],
            'FSMG_SCH_SUPPLIER_META_KEYWORDS_' . $default_lang
        );

        $fields_form = [];
        $input_fields = [];
        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta title generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_SUPPLIER_META_TITLE',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_title_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta description generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_SUPPLIER_META_DESC',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_description_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta keywords generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_SUPPLIER_META_KEYWORDS',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_keywords_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Keep meta information updated:'),
            'name' => 'FSMG_GEN_SUPPLIER_META_UPDATE',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_SUPPLIER_META_UPDATE_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_SUPPLIER_META_UPDATE_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Every time a supplier is updated, the meta information will be updated based on the schema'
            ),
            'is_multishop' => true,
        ];

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Supplier Meta Settings'),
            ],
            'input' => $input_fields,
        ];

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_supplier_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_supplier_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderSupplierMetaGeneratorForm($fields_value)
    {
        $display_shop_warning = false;
        if (Shop::isFeatureActive() && (Shop::getContext() != Shop::CONTEXT_SHOP)) {
            $display_shop_warning = true;
        }

        $this->smartyAssign(['fsmg_progress_bar_id' => 'fsmg_supplier_meta_progress_bar']);
        $fields_value['supplier_meta_progress_bar'] = $this->smartyFetch('admin/progress_bar.tpl');

        $fields_form = [];
        $input_fields = [];

        $input_fields[] = [
            'type' => 'switch',
            'label' => $this->l('Overwrite exist meta:'),
            'name' => 'FSMG_GEN_SUPPLIER_META_OVERWRITE',
            'class' => 't',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'FSMG_GEN_SUPPLIER_META_OVERWRITE_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'FSMG_GEN_SUPPLIER_META_OVERWRITE_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l(
                'Overwrite the exist meta information with the newly generated'
            ),
            'is_multishop' => true,
        ];

        if (!$display_shop_warning) {
            $input_fields[] = [
                'type' => 'free',
                'label' => $this->l('Status:'),
                'lang' => false,
                'name' => 'supplier_meta_progress_bar',
                'required' => false,
            ];
        }

        $desc = $this->l('The module automatically generates the meta information based on the schema.') . ' ';
        $desc .= $this->l('If no meta info saved, the generated info going to be used.') . ' ';
        $desc .= $this->l('With the generator you can save the generated meta info based on the schema.') . ' ';

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Supplier Meta Generator'),
            ],
            'description' => $desc,
            'input' => $input_fields,
        ];

        if ($display_shop_warning) {
            $msg = $this->l('Please select a shop to able to start product meta generation!') . ' ';
            $msg .= $this->l('Do NOT change until the end of the generation process!') . ' ';
            $msg .= $this->l('Do NOT change in other browser tab too!');
            $fields_form[0]['form']['warning'] = $msg;
        } else {
            $fields_form[0]['form']['buttons'][] = [
                'title' => '<i class="process-icon-update"></i>' . $this->l('Generate'),
                'href' => 'javascript:;',
                'icon' => 'update',
                'js' => 'FSMG.generateSupplierMeta();',
            ];
        }

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_supplier_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_supplier_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderCmsSchemaForm($fields_value)
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $meta_title_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['cms']['meta_title'],
            'FSMG_SCH_CMS_META_TITLE_' . $default_lang
        );

        $meta_description_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['cms']['meta_description'],
            'FSMG_SCH_CMS_META_DESC_' . $default_lang
        );

        $meta_keywords_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['cms']['meta_keywords'],
            'FSMG_SCH_CMS_META_KEYWORDS_' . $default_lang
        );

        $fields_form = [];
        $input_fields = [];
        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta title generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CMS_META_TITLE',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_title_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta description generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CMS_META_DESC',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_description_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta keywords generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CMS_META_KEYWORDS',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_keywords_desc,
            'is_multishop' => true,
        ];

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('CMS Meta Settings'),
            ],
            'input' => $input_fields,
        ];

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_cms_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_cms_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderCmsCategorySchemaForm($fields_value)
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $meta_title_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['cms_category']['meta_title'],
            'FSMG_SCH_CMS_CAT_META_TITLE_' . $default_lang
        );

        $meta_description_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['cms_category']['meta_description'],
            'FSMG_SCH_CMS_CAT_META_DESC_' . $default_lang
        );

        $meta_keywords_desc = $this->generateAvailableKeywordsMultilang(
            $this->generator_keywords['cms_category']['meta_keywords'],
            'FSMG_SCH_CMS_CAT_META_KEYWORDS_' . $default_lang
        );

        $fields_form = [];
        $input_fields = [];
        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta title generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CMS_CAT_META_TITLE',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_title_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta description generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CMS_CAT_META_DESC',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_description_desc,
            'is_multishop' => true,
        ];

        $input_fields[] = [
            'type' => 'text',
            'label' => $this->l('Meta keywords generator schema:'),
            'lang' => true,
            'name' => 'FSMG_SCH_CMS_CAT_META_KEYWORDS',
            'size' => 70,
            'required' => false,
            'hint' => $this->l('Forbidden chars:') . '<br /><>;=#',
            'desc' => $meta_keywords_desc,
            'is_multishop' => true,
        ];

        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('CMS Category Meta Settings'),
            ],
            'input' => $input_fields,
        ];

        $helper = new FsMetaGeneratorHelperFormMultiShop($this);
        $helper->setIdentifier('fsmg_cms_category_meta');
        $helper->setSubmitAction('save_' . $this->name);
        $helper->setTabSection('fsmg_cms_category_tab');
        $helper->setFieldsValue($fields_value);

        return $helper->generateForm($fields_form);
    }

    protected function renderTabLayout($layout, $active_tab)
    {
        $context = Context::getContext();
        $context->smarty->assign([
            'fsmg_tab_layout' => $layout,
            'fsmg_active_tab' => $active_tab,
        ]);

        return $this->smartyFetch('admin/tab_layout.tpl');
    }

    // ################### HOOKS ####################

    public function hookDisplayHeader($params)
    {
        $context = Context::getContext();
        $id_lang = $context->language->id;
        $id_category = Tools::getValue('id_category', false);

        if ($id_category) {
            $metas = Meta::getMetaTags($id_lang, 'category');

            $context->smarty->assign('meta_title', $metas['meta_title']);
            $context->smarty->assign('meta_description', $metas['meta_description']);
            $context->smarty->assign('meta_keywords', $metas['meta_keywords']);
            $context->smarty->assign('fsmg_id_lang', $params['cookie']->id_lang);
        }
    }

    public function hookActionObjectProductUpdateAfter($params)
    {
        if (Configuration::get('FSMG_GEN_PROD_META_UPDATE')) {
            $this->updateProductMetas($params['object']->id, true);
        }
    }

    public function hookActionObjectCategoryUpdateAfter($params)
    {
        if (Configuration::get('FSMG_GEN_CAT_META_UPDATE')) {
            $this->updateCategoryMetas($params['object']->id, true);
        }
    }

    public function hookActionObjectManufacturerUpdateAfter($params)
    {
        if (Configuration::get('FSMG_GEN_MANU_META_UPDATE')) {
            $this->updateManufacturerMetas($params['object']->id, true);
        }
    }

    public function hookActionObjectSupplierUpdateAfter($params)
    {
        if (Configuration::get('FSMG_GEN_SUPPLIER_META_UPDATE')) {
            $this->updateSupplierMetas($params['object']->id, true);
        }
    }

    // ################### FUNCTIONS ####################

    public function url()
    {
        return $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name;
    }

    public function adminAjaxUrl($controller, $params = [])
    {
        $context = Context::getContext();
        $params_string = '';
        if ($params) {
            $params_string .= '&' . http_build_query($params);
        }

        return $context->link->getAdminLink($controller) . $params_string;
    }

    public function getModuleBaseUrl()
    {
        $context = Context::getContext();

        return $context->shop->getBaseURL() . 'modules/' . $this->name . '/';
    }

    public function getModuleFile()
    {
        return __FILE__;
    }

    public function generateMultilangualFields($default_value = '')
    {
        return self::generateMultilangualFieldsStatic($default_value);
    }

    public static function generateMultilangualFieldsStatic($default_value = '')
    {
        $multilangual_fields = [];
        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            $multilangual_fields[$language['id_lang']] = $default_value;
        }

        return $multilangual_fields;
    }

    public function setFilterToCookie($id, $filter_fields, $default_order_by, $tab_section = '')
    {
        $context = Context::getContext();
        $pagination_default = 50;
        $filter = [
            'page' => Tools::getValue('submitFilter' . $id, 1),
            'limit' => Tools::getValue($id . '_pagination', $pagination_default),
            'order_by' => Tools::getValue($id . 'Orderby', $default_order_by),
            'order_way' => Tools::strtoupper(Tools::getValue($id . 'Orderway', 'DESC')),
        ];

        foreach ($filter_fields as $filter_field) {
            $filter[$filter_field] = Tools::getValue($id . 'Filter_' . $filter_field, '');
        }

        if (!$filter['page']) {
            $filter['page'] = 1;
        }

        if (Tools::isSubmit('submitReset' . $id)) {
            foreach ($filter_fields as $filter_field) {
                $filter[$filter_field] = '';
            }
            $filter['page'] = 1;
            $filter['limit'] = Tools::getValue($id . '_pagination', $pagination_default);
        }

        foreach ($filter_fields as $filter_field) {
            $cookie_field_name = $id . 'Filter_' . $filter_field;
            $context->cookie->$cookie_field_name = $filter[$filter_field];
        }

        $cookie_field_name = $id . 'Orderby';
        $context->cookie->$cookie_field_name = $filter['order_by'];
        $cookie_field_name = $id . 'Orderway';
        $context->cookie->$cookie_field_name = $filter['order_way'];

        if (Tools::isSubmit('submitReset' . $id)) {
            FsMetaGeneratorTools::redirect($this->url() . '&tab_section=' . $tab_section);
        }

        if (Tools::isSubmit('submitFilter' . $id)) {
            $this->tab_section = $tab_section;
        }

        return $filter;
    }

    public static function getMultilangualConfiguration($key, $id_shop_group = null, $id_shop = null)
    {
        $languages = Language::getLanguages(false);
        $results_array = [];
        foreach ($languages as $language) {
            $results_array[$language['id_lang']] = Configuration::get(
                $key,
                $language['id_lang'],
                $id_shop_group,
                $id_shop
            );
        }

        return $results_array;
    }

    public static function getMultilangualValue($key, $default = '')
    {
        $languages = Language::getLanguages(false);
        $results_array = [];
        foreach ($languages as $language) {
            $results_array[$language['id_lang']] = Tools::getValue($key . '_' . $language['id_lang'], $default);
        }

        return $results_array;
    }

    public function smartyFetch($template_path, $new_fetcher = false)
    {
        $this->smartyAssign([
            'module_base_url' => $this->getModuleBaseUrl(),
            'fsmg_module_base_url' => $this->getModuleBaseUrl(),
        ]);

        if ($new_fetcher) {
            return $this->fetch('module:' . $this->name . '/views/templates/' . $template_path);
        }

        return $this->context->smarty->fetch($this->local_path . '/views/templates/' . $template_path);
    }

    public function smartyAssign($var)
    {
        $this->context->smarty->assign($var);
    }
}
