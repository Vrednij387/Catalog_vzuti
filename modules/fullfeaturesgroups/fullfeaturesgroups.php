<?php
/**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author PrestaShop SA <contact@prestashop.com>
 * @copyright  2007-2017 PrestaShop SA
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class FullFeaturesGroups extends Module implements WidgetInterface
{
    const FFG_JSON_CACHE_KEY = 'FFG_JSON_CACHE_KEY';

    public $html = '';
    protected static $_features_product_list = array();
    protected static $_cacheFeatures = array();
    protected $submitted_tabs;
    protected $errors = array();

    public function __construct()
    {
        $this->name = 'fullfeaturesgroups';
        $this->tab = 'front_office_features';
        $this->version = '3.5.5';
        $this->author = 'Terranet';
        $this->need_instance = 1;
        $this->bootstrap = true;

        parent::__construct();

        $this->id_product = 21368;
        $this->displayName = $this->trans('Full Features Groups', array(), 'Modules.FullFeaturesGroups.Admin');
        $this->description = $this->trans('Features Groups Module.', array(), 'Modules.FullFeaturesGroups.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->module_key = '25a1d939cccf400392377c23aa2c3287';
    }

    public function getTabs()
    {
        return array(
            array(
                'class_name' => 'AdminFullFeatureFastView',
                'name' => $this->trans('FFG product Info', array(), 'Modules.FullFeaturesGroups.Admin'),
                'ParentClassName' => 'IMPROVE',
                'visible' => true,
                'icon' => ''
            )
        );
    }

    public function install($delete_params = true)
    {
        Configuration::updateValue('FFG_VALUE_NEW_LINE', 0);
        Configuration::updateValue('FFG_VALUE_NEW_LINE_CUSTOM', 0);
        Configuration::updateValue('FFG_FEATURE_FRONT', 1);
        Configuration::updateValue('FFG_FEATURE_VALUES_ORDERED', 'custom');
        Configuration::updateValue('FFG_FEATURE_COLUMN_QTY', 'one');

        if (!parent::install() ||
            !$this->installDb() ||
            !$this->registerHook('updateproduct') ||
            !$this->registerHook('deleteproduct') ||
            !$this->registerHook('actionObjectProductUpdateAfter') ||
//            !$this->registerHook('displayAdminProductsExtra') ||
            !$this->registerHook('displayAdminProductsMainStepLeftColumnMiddle') ||
            !$this->registerHook('header') ||
            !$this->registerHook('backOfficeHeader') ||
            !$this->registerHook('displayFFGFeatures') ||
            !$this->registerHook('displayFooterProduct') ||
            !$this->registerHook('displayProductExtraContent')
        ) {
            return false;
        }

        return true;
    }

    public function installDb()
    {
        $return = true;
        $return &= Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'feature_group` 
                                                    (   `id_group` INT UNSIGNED NOT NULL AUTO_INCREMENT, 
                                                        `all_list` TINYINT NOT NULL DEFAULT \'1\', 
                                                        `description` VARCHAR(255),
                                                        `position` int(10) unsigned DEFAULT NULL, 
                                                        PRIMARY KEY (`id_group`) 
                                                    ) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;')
            && Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'feature_group_lang` 
                                                    (   `id_group` INT UNSIGNED NOT NULL, 
                                                        `id_lang` int(10) unsigned NOT NULL , 
                                                        `name` varchar(255) NOT NULL, 
                                                        PRIMARY KEY (`id_group`, `id_lang`) 
                                                    ) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;')
            && Db::getInstance()->execute('	CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'feature_category` 
                                                    (   `id_group` INT UNSIGNED NOT NULL, 
                                                        `id_category` int unsigned NOT NULL , 
                                                        PRIMARY KEY (`id_group`, `id_category`) 
                                                    ) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;')
            && Db::getInstance()->execute('	CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'feature_group_link` 
                                                    (   `id_feature` INT UNSIGNED NOT NULL, 
                                                        `id_group` int(10) unsigned NOT NULL, 
                                                        `position` int(10) unsigned NOT NULL, 
                                                        PRIMARY KEY (`id_feature`, `id_group`) 
                                                    ) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;')

            && Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'feature_product` DROP PRIMARY KEY, ADD PRIMARY KEY (`id_product`, `id_feature_value`, `id_feature`)')
            && Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'feature_product` ADD `position` INT NULL DEFAULT 0');

        return $return;
    }

    public function uninstall($delete_params = true)
    {
        if (!parent::uninstall()) {
            return false;
        }

        $this->_clearCacheId();

        if ($delete_params) {
            if (!$this->uninstallDB()) {
                return false;
            }
        }

        return true;
    }

    protected function uninstallDb()
    {
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'feature_group`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'feature_group_lang`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'feature_category`');
        Db::getInstance()->execute('DROP TABLE `' . _DB_PREFIX_ . 'feature_group_link`');
        Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'feature_product` DROP COLUMN `position`');

        return true;
    }

    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }

    /**
     * Unregister and after Register hook
     */
    public function resetHooks()
    {
        if (Tools::isSubmit('resetHooks')) {
            foreach (['updateproduct', 'deleteproduct', 'actionObjectProductUpdateAfter',
                         'displayAdminProductsMainStepLeftColumnMiddle', 'header', 'backOfficeHeader',
                         'displayFFGFeatures', 'displayFooterProduct', 'displayProductExtraContent'
                     ] as $hook) {
                $this->unregisterHook($hook);
                $this->registerHook($hook);
            }

            $this->html .= $this->displayConfirmation($this->l('The hook positions have been updated.', 'renderform'));
        }
    }

    public function getContent()
    {
        $this->ajaxProcessUpdatePositions();
        $this->resetHooks();

        if (Tools::isSubmit('verifyTables')) {
            $resUpdate = $this->verifyTableIntegrity();
            if (is_bool($resUpdate) && $resUpdate) {
                $this->html .= $this->displayConfirmation($this->l('The settings have been updated.'));
            } elseif (is_array($resUpdate) && isset($resUpdate['error']) && $resUpdate['error'] && isset($resUpdate['msg'])) {
                $this->html .= $this->displayError($resUpdate['msg']);
            }
        }

        if (Tools::isSubmit('savefeaturefrontparam')) {
            $updateToVal = (int)Tools::getValue('FFG_FEATURE_FRONT', 0);
            Configuration::updateValue('FFG_FEATURE_FRONT', $updateToVal);

            $updateFNLPToVal = (int)Tools::getValue('FFG_VALUE_NEW_LINE', 0);
            Configuration::updateValue('FFG_VALUE_NEW_LINE', $updateFNLPToVal);

            $updateFNLPToVal = (int)Tools::getValue('FFG_VALUE_NEW_LINE_CUSTOM', 0);
            Configuration::updateValue('FFG_VALUE_NEW_LINE_CUSTOM', $updateFNLPToVal);

            Configuration::updateValue('FFG_FEATURE_VALUES_ORDERED', Tools::getValue('FFG_FEATURE_VALUES_ORDERED', 'fvl.id_feature_value_ASC'));

            Configuration::updateValue('FFG_FEATURE_COLUMN_QTY', Tools::getValue('FFG_FEATURE_COLUMN_QTY', 'one'));
        }

        if (Tools::isSubmit('savefullfeaturesgroups')) {
            if (!Tools::getValue('text_' . (int)Context::getContext()->language->id, false)) {
                return $this->html . $this->displayError($this->trans('You must fill in all fields.', array(), 'Modules.FullFeaturesGroups.Admin')) . $this->renderForm();
            } elseif ($this->processSaveFeatureGroup()) {
                return $this->html
                    . $this->renderList()
                    . $this->featchProductGroupForm()
                    . $this->renderSetOverrideFeatureFrontParam()
                    . $this->renderCustomHookHelp()
                    . $this->getVerifyBtn()
                    . $this->renderDiscoverModules();
            } else {
                return $this->html . $this->renderForm();
            }
        } elseif (Tools::isSubmit('updatefullfeaturesgroups') || Tools::isSubmit('addfullfeaturesgroups')) {
            $this->html .= $this->renderForm();
            $this->_clearCacheId();
            return $this->html;
        } else if (Tools::isSubmit('deletefullfeaturesgroups')) {
            Db::getInstance()->delete('feature_group', '`id_group` = ' . (int)Tools::getValue('id_group') . '', 0, true);
            Db::getInstance()->delete('feature_group_lang', '`id_group` = ' . (int)Tools::getValue('id_group') . '', 0, true);
            Db::getInstance()->delete('feature_group_link', '`id_group` = ' . (int)Tools::getValue('id_group') . '', 0, true);
            $this->refreshPositionGroup();
            $this->_clearCacheId();
            Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'));
        } else if (Tools::isSubmit('submitBulkdeletefullfeaturesgroups')) {
            $data_items = Tools::getValue('fullfeaturesgroupsBox');
            if (isset($data_items) && is_array($data_items) && count($data_items) > 0) {
                foreach ($data_items as $item_var) {
                    if (is_numeric($item_var) && $item_var > 0) {
                        Db::getInstance()->delete('feature_group', '`id_group` = ' . (int)$item_var . '', 0, true);
                        Db::getInstance()->delete('feature_group_lang', '`id_group` = ' . (int)$item_var . '', 0, true);
                        Db::getInstance()->delete('feature_group_link', '`id_group` = ' . (int)$item_var . '', 0, true);
                    }
                }
            }
            $this->refreshPositionGroup();
            $this->_clearCacheId();
            Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('addcategoryfullfeaturesgroups')) {
            $this->getCategoryDataAssign();
            $this->getAllFeatureGroupAssign();
            $this->getCategoryFeatureSelected();
            $this->context->smarty->assign('form_action', AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&addcategoryfeaturegroupval=1');
            $this->context->smarty->assign('back_action', AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'));
            return $this->display(__FILE__, 'views/templates/admin/addcategoryfeaturegroup.tpl');
        } elseif (Tools::isSubmit('addcategoryfeaturegroupval')) {
            $id_category = Tools::getValue('category');
            if (isset($id_category) && !empty($id_category)) {
                $uid_cat = explode('_', $id_category);
                if (isset($uid_cat['1']) && is_numeric($uid_cat['1']) && $uid_cat['1'] > 0) {
                    Db::getInstance()->delete('feature_category', '`id_category` = ' . $uid_cat['1'] . '', 0, true);
                    (Tools::getValue('categoryid') && is_numeric(Tools::getValue('categoryid')) && Tools::getValue('categoryid') > 0) ? Db::getInstance()->delete('feature_category', '`id_category` = ' . (int)Tools::getValue('categoryid') . '', 0, true) : '';
                    $id_items_feature = Tools::getValue('items');
                    if (is_array($id_items_feature) && count($id_items_feature) > 0) {
                        foreach ($id_items_feature as $val) {
                            if (is_numeric($val) && $val > 0) {
                                Db::getInstance()->execute('INSERT INTO ' . _DB_PREFIX_ . 'feature_category (`id_category`, `id_group`) VALUES (' . (int)$uid_cat['1'] . ', ' . (int)$val . ')');
                            }
                        }
                    }
                }
            }
            $this->_clearCacheId();
            Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('updatefullfeaturesgroups_category')) {
            $this->getCategoryDataAssign();
            $this->getAllFeatureGroupAssign();
            $this->getCategoryFeatureSelected();
            $this->context->smarty->assign('form_action', AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'));
            $this->context->smarty->assign('back_action', AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'));
            $this->context->smarty->assign('categoryid', Tools::getValue('category'));
            return $this->display(__FILE__, 'views/templates/admin/updatecategoryfeaturegroup.tpl');
        } elseif (Tools::isSubmit('deletefullfeaturesgroups_category')) {
            $id_category = (int)Tools::getValue('category');
            if (isset($id_category) && !empty($id_category) && is_numeric($id_category) && $id_category > 0) {
                Db::getInstance()->delete('feature_category', '`id_category` = ' . (int)$id_category . '', 0, true);
            }
            Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('submitBulkdeletefullfeaturesgroups_category')) {
            $data_items = Tools::getValue('fullfeaturesgroups_categoryBox');
            if (isset($data_items) && is_array($data_items) && count($data_items) > 0) {
                foreach ($data_items as $item_var) {
                    if (is_numeric($item_var) && $item_var > 0) {
                        Db::getInstance()->delete('feature_category', '`id_category` = ' . (int)$item_var . '', 0, true);
                    }
                }
            }
            Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'));
        } else {
            if (Tools::isSubmit('submitResetfullfeaturesgroups') || Tools::isSubmit('submitResetfullfeaturesgroups_category')) {
                $_POST = array();
            }

            $this->html .= $this->renderList();
            $this->html .= $this->featchProductGroupForm();
            $this->html .= $this->renderSetOverrideFeatureFrontParam();

            return $this->html . $this->renderCustomHookHelp() . $this->getVerifyBtn() . $this->renderDiscoverModules();
        }
    }

    public function renderCustomHookHelp()
    {
        return $this->display($this->name, 'custom_hook_help.tpl');
    }

    public function getVerifyBtn()
    {
        $linkToUpdateTables = AdminController::$currentIndex . '&configure=' . urlencode($this->name)
            . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&verifyTables';

        $linkToResetHooks = AdminController::$currentIndex . '&configure=' . urlencode($this->name)
            . '&token=' . Tools::getAdminTokenLite('AdminModules') . '&resetHooks';

        $this->smarty->assign(array(
            'linkToUpdate' => $linkToUpdateTables,
            'linkToResetHooks' => $linkToResetHooks
        ));

        return $this->display($this->name, 'tableVerifyTablesBtn.tpl');
    }

    public function renderSetOverrideFeatureFrontParam()
    {
        $default_lang = (int)Context::getContext()->language->id;
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Disabled standard properties (features) for the front/back page', array(), 'Modules.FullFeaturesGroups.Admin'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Disabled standard features for the front/back page', array(), 'Modules.FullFeaturesGroups.Admin'),
                        'name' => 'FFG_FEATURE_FRONT',
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        )
                    ),


                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Replace comma to new line for features values in the standard module hooks', array(), 'Modules.FullFeaturesGroups.Admin'),
                        'name' => 'FFG_VALUE_NEW_LINE',
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        )
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->trans('Replace comma to new line for features values in the custom hooks', array(), 'Modules.FullFeaturesGroups.Admin'),
                        'name' => 'FFG_VALUE_NEW_LINE_CUSTOM',
                        'values' => array(
                            array(
                                'id' => 'type_switch_on',
                                'value' => 1
                            ),
                            array(
                                'id' => 'type_switch_off',
                                'value' => 0
                            )
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans('Sorting features value by:', array(), 'Modules.FullFeaturesGroups.Admin'),
                        'name' => 'FFG_FEATURE_VALUES_ORDERED',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_ordered' => 'custom',
                                    'name' => 'Manually'
                                ),
                                array(
                                    'id_ordered' => 'fvl.id_feature_value_ASC',
                                    'name' => 'By values id ASC'
                                ),
                                array(
                                    'id_ordered' => 'fvl.id_feature_value_DESC',
                                    'name' => 'By values id DESC'
                                ),
                                array(
                                    'id_ordered' => 'fvl.value_ASC',
                                    'name' => 'By name values ASC'
                                ),
                                array(
                                    'id_ordered' => 'fvl.value_DESC',
                                    'name' => 'By name values DESC'
                                )
                            ),
                            'id' => 'id_ordered',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->trans('Number of columns with product specifications:', array(), 'Modules.FullFeaturesGroups.Admin'),
                        'name' => 'FFG_FEATURE_COLUMN_QTY',
                        'options' => array(
                            'query' => array(
                                array(
                                    'column_slug' => 'default',
                                    'name' => $this->trans('Default', array(), 'Modules.FullFeaturesGroups.Admin'),
                                ),
                                array(
                                    'column_slug' => 'one',
                                    'name' => $this->trans('One column', array(), 'Modules.FullFeaturesGroups.Admin')
                                ),
                                array(
                                    'column_slug' => 'two',
                                    'name' => $this->trans('Two column', array(), 'Modules.FullFeaturesGroups.Admin')
                                ),
                                array(
                                    'column_slug' => 'three',
                                    'name' => $this->trans('Three column', array(), 'Modules.FullFeaturesGroups.Admin')
                                )
                            ),
                            'id' => 'column_slug',
                            'name' => 'name'
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Modules.FullFeaturesGroups.Admin'),
                )
            ),
        );
        $helper = new HelperForm();
        $helper->name_controller = $this->name;
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . urlencode($this->name);
        $helper->module = $this;
        $helper->name_controller = 'fullfeaturesgroups';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'savefeaturefrontparam';
        $helper->tpl_vars = array(
            'fields_value' => array(
                'FFG_VALUE_NEW_LINE' => Configuration::get('FFG_VALUE_NEW_LINE'),
                'FFG_VALUE_NEW_LINE_CUSTOM' => Configuration::get('FFG_VALUE_NEW_LINE_CUSTOM'),
                'FFG_FEATURE_FRONT' => Configuration::get('FFG_FEATURE_FRONT'),
                'FFG_FEATURE_VALUES_ORDERED' => Configuration::get('FFG_FEATURE_VALUES_ORDERED'),
                'FFG_FEATURE_COLUMN_QTY' => Configuration::get('FFG_FEATURE_COLUMN_QTY')
            ),
            'languages' => $helper->languages,
            'id_language' => $default_lang,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function ajaxProcessUpdatePositions()
    {
        if (Tools::getValue('updatePositions')) {
            $numeric = 0;
            foreach (Tools::getValue('module-fullfeaturesgroups') as $val_sort) {
                $sort_var = explode('_', $val_sort);
                $sql = 'UPDATE `' . _DB_PREFIX_ . 'feature_group` SET `position` = ' . (int)$numeric++ . ' WHERE id_group = ' . (int)$sort_var['2'] . ' ';
                Db::getInstance()->execute($sql);
            }
            $this->_clearCacheId();
        }
    }

    public function getCategoriesInGroup($id_group)
    {
        $sql = 'SELECT id_category
					FROM `' . _DB_PREFIX_ . 'feature_category` WHERE id_group = ' . (int)$id_group;
        $content = Db::getInstance()->executeS($sql);
        return ($content && is_array($content) && count($content)) ? array_map(function ($data) {
            return (int)$data['id_category'];
        }, $content) : array();
    }

    public function renderForm()
    {
        $default_lang = (int)Context::getContext()->language->id;

        $categories_ids = array();
        $id_group = (int)Tools::getValue('id_group');
        if ($id_group > 0) {
            $categories_ids = $this->getCategoriesInGroup($id_group);
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Created group features', array(), 'Modules.FullFeaturesGroups.Admin'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    'id_feature' => array(
                        'type' => 'hidden',
                        'name' => 'id_group',
                    ),
                    array(
                        'col' => 5,
                        'type' => 'text',
                        'label' => $this->trans('Name of group', array(), 'Modules.FullFeaturesGroups.Admin'),
                        'name' => 'text',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'col' => 4,
                        'label' => $this->trans('Admin note', array(), 'Modules.FullFeaturesGroups.Admin'),
                        'name' => 'description',
                        'required' => false,
                        'lang' => false,
                    ),
                    array(
                        'type' => 'link_choice',
                        'label' => '',
                        'name' => 'link',
                        'lang' => true,
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->l('Exist in categories:'),
                        'name' => 'selected_categories',
                        'tree' => array(
                            'id' => 'categories-tree',
                            'selected_categories' => $categories_ids,
                            'root_category' => (int)Category::getRootCategory()->id_category,
                            'use_search' => true,
                            'use_checkbox' => true
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Modules.FullFeaturesGroups.Admin'),
                ),
                'buttons' => array(
                    array(
                        'href' => AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->trans('Back to list', array(), 'Modules.FullFeaturesGroups.Admin'),
                        'icon' => 'process-icon-back'
                    )
                )
            ),
        );

        $helper = new HelperForm();
        $helper->name_controller = $this->name;
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . urlencode($this->name);
        $helper->module = $this;
        $helper->name_controller = 'fullfeaturesgroups';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'savefullfeaturesgroups';
        $helper->tpl_vars = array(
            'fields_value' => $this->getFormValues(),
            'languages' => $helper->languages,
            'id_language' => $default_lang,
            'choices' => $this->renderChoicesSelect(),
            'choices_all' => $this->renderChoicesSelect('true'),
            'selected_links' => $this->makeMenuOption(),
        );

        return $helper->generateForm(array($fields_form));
    }

    protected function renderList()
    {
        $this->fields_list = array();
        $this->fields_list['id_group'] = array('title' => $this->trans('Id', array(), 'Modules.FullFeaturesGroups.Admin'),
            'type' => 'text',
            'search' => true,
            'orderby' => true,
            'class' => 'fixed-width-xs',);
        $this->fields_list['name'] = array('title' => $this->trans('Group name', array(), 'Modules.FullFeaturesGroups.Admin'),
            'type' => 'text',
            'search' => true,
            'orderby' => true,);
        $this->fields_list['description'] = array('title' => $this->trans('Admin note', array(), 'Modules.FullFeaturesGroups.Admin'),
            'type' => 'text',
            'search' => true,
            'orderby' => true,);
        $this->fields_list['items'] = array('title' => $this->trans('Items', array(), 'Modules.FullFeaturesGroups.Admin'),
            'type' => 'text',
            'search' => false,
            'orderby' => false,);
        $this->fields_list['position'] = array('title' => $this->trans('Position', array(), 'Modules.FullFeaturesGroups.Admin'),
            'filter_key' => 'featurecat!position',
            'position' => 'position',
            'class' => 'fixed-width-xs',
            'align' => 'center',
            'search' => false,
            'orderby' => false,);

        $valueList = $this->getListContent();
        $helper = new HelperList();
        $helper->module = $this;
        $helper->shopLinkType = '';
        $helper->position_identifier = 'position';
        $helper->simple_header = false;
        $helper->identifier = 'id_group';
        $helper->actions = array('edit', 'delete');
        $helper->bulk_actions = array('delete' => array(
            'text' => $this->trans('Delete selected', array(), 'Modules.FullFeaturesGroups.Admin'),
            'icon' => 'icon-trash',
            'confirm' => $this->trans('Delete selected items?', array(), 'Modules.FullFeaturesGroups.Admin')
        ));
        $helper->orderBy = 'position';
        $helper->orderWay = 'ASC';
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->title = $this->displayName;
        $helper->listTotal = count($valueList);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->toolbar_btn['new'] = array('href' => AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&add' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->trans('Add new', array(), 'Modules.FullFeaturesGroups.Admin'));
        $helper->table = 'fullfeaturesgroups';
        $helper->table_id = 'module-fullfeaturesgroups';
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules');
        return $helper->generateList($valueList, $this->fields_list);
    }

    public function processSaveFeatureGroup()
    {
        $id_info = (int)Tools::getValue('id_group');
        $temp_last_id = $id_info;
        $id_lang = (int)Context::getContext()->language->id;
        $languages = Language::getLanguages(false);
        $all_list = (Tools::getValue('all_list') && Tools::getValue('all_list') == 1) ? 1 : 0;
        $description = Tools::getValue('description');
        if (is_array(Tools::getValue('items')) && count(Tools::getValue('items')) > 0) {
            $itemsClear = array_unique(Tools::getValue('items'));
            if ($id_info) {
                foreach ($languages as $lang) {
                    Db::getInstance()->update('feature_group_lang', array(
                        'name' => pSQL(Tools::getValue('text_' . (int)$lang['id_lang']))
                    ), '`id_group` = ' . $id_info . ' AND id_lang = ' . (int)$lang['id_lang'], 0, true);
                }

                Db::getInstance()->delete('feature_group_link', '`id_group` = ' . (int)$id_info . ' ', 0, true);
                Db::getInstance()->update('feature_group', array('all_list' => (int)$all_list, 'description' => pSQL($description)), '`id_group` = ' . (int)$id_info);

                foreach ($itemsClear as $key => $val) {
                    Db::getInstance()->execute('INSERT INTO ' . _DB_PREFIX_ . 'feature_group_link (`id_feature`, `id_group`, `position`) VALUES (' . (int)$val . ', ' . (int)$id_info . ', ' . (int)$key . ' )');
                }
            } else {
                $content_max = Db::getInstance()->getRow('SELECT max(position) AS pos, count(position) as tables  FROM `' . _DB_PREFIX_ . 'feature_group`;');
                $position = ($content_max['tables'] == 0) ? 0 : ++$content_max['pos'];

                Db::getInstance()->execute('INSERT INTO ' . _DB_PREFIX_ . 'feature_group (`position`, `all_list`, `description`) VALUES (' . (int)$position . ', ' . (int)$all_list . ', "' . pSQL($description) . '")');
                $temp_last_id = Db::getInstance()->Insert_ID();
                foreach ($languages as $lang) {
                    Db::getInstance()->execute('INSERT INTO ' . _DB_PREFIX_ . 'feature_group_lang (`id_group`, `id_lang`, `name`) VALUES (' . (int)$temp_last_id . ', ' . (int)$lang['id_lang'] . ', \' ' . (!Tools::isEmpty(Tools::getValue('text_' . $lang['id_lang'])) ? pSQL(Tools::getValue('text_' . $lang['id_lang'])) : pSQL(Tools::getValue('text_' . $id_lang))) . ' \' )');
                }
                if (is_array($itemsClear) && count($itemsClear) > 0) {
                    Db::getInstance()->delete('feature_group_link', '`id_feature` IN (' . join(',', $itemsClear) . ') AND id_group = ' . (int)$temp_last_id, 0, true);
                }
                foreach ($itemsClear as $key => $val) {
                    Db::getInstance()->execute('INSERT INTO ' . _DB_PREFIX_ . 'feature_group_link (`id_feature`, `id_group`, `position`) VALUES (' . (int)$val . ', ' . (int)$temp_last_id . ', ' . (int)$key . ' )');
                }
            }
            $this->_clearCacheId();
        }

        $selected_categories = Tools::getValue('selected_categories');
        $insert_or_update_to = ($id_info > 0) ? $id_info : $temp_last_id;
        DB::getInstance()->delete('feature_category', 'id_group = ' . (int)$insert_or_update_to);
        if (is_array($selected_categories) && count($selected_categories)) {
            foreach ($selected_categories as $selected_category) {
                DB::getInstance()->insert('feature_category', array('id_group' => (int)$insert_or_update_to, 'id_category' => (int)$selected_category));
            }
        }

        return true;
    }

    public function featchProductGroupForm()
    {
        $this->fields_list = array(
            'category' => array(
                'title' => $this->trans('Id', array(), 'Modules.FullFeaturesGroups.Admin'),
                'width' => 40,
                'type' => 'text',
                'search' => true,
                'class' => 'fixed-width-xs',
            ),
            'cat_name' => array(
                'title' => $this->trans('Category', array(), 'Modules.FullFeaturesGroups.Admin'),
                'type' => 'text',
                'search' => true,
            ),
            'name' => array(
                'title' => $this->trans('Items', array(), 'Modules.FullFeaturesGroups.Admin'),
                'type' => 'text_html',
                'search' => false,
            ),
        );

        $id_lang = (int)Context::getContext()->language->id;
        $featureFormCategory = array();
        $featureList = $this->getFeatureCategory();

        if (is_array($featureList) && count($featureList) > 0) {
            foreach ($featureList as $fValue) {
                $featureFormCategory[$fValue['id_category']]['category'] = $fValue['id_category'];
                if ($id_lang == $fValue['id_lang']) {
                    $featureFormCategory[$fValue['id_category']]['id_lang'] = $fValue['id_lang'];
                    if (!isset($featureFormCategory[$fValue['id_category']]['name'])
                        || isset($fValue['name'])
                        && !in_array($fValue['name'], $featureFormCategory[$fValue['id_category']]['name'])
                    ) {
                        $featureFormCategory[$fValue['id_category']]['name'][] = '<b>' . $fValue['name'] . '</b>' . (!empty($fValue['description']) ? '(' . $fValue['description'] . ')' : '');
                    }
                    $featureFormCategory[$fValue['id_category']]['cat_name'] = $fValue['cat_name'];
                }
            }
        }

        foreach ($featureFormCategory as $key => $val) {
            $featureFormCategory[$key]['name'] = (isset($val['name']) && is_array($val['name']) && count($val['name']) > 0) ? join(', ', $val['name']) : '';
        }

        $helper = new HelperList();
        $helper->module = $this;
        $helper->shopLinkType = '';
        $helper->position_identifier = 'category';
        $helper->simple_header = false;
        $helper->identifier = 'category';
        $helper->actions = array('edit', 'delete');
        $helper->listTotal = count($featureFormCategory);
        $helper->bulk_actions = array(
            'delete' => array(
                'text' => $this->trans('Delete selected', array(), 'Modules.FullFeaturesGroups.Admin'),
                'icon' => 'icon-trash',
                'confirm' => $this->trans('Delete selected items?', array(), 'Modules.FullFeaturesGroups.Admin')
            ));
        $helper->show_toolbar = true;
        $helper->title = $this->trans('Feature Cetegory', array(), 'Modules.FullFeaturesGroups.Admin');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&addcategory' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->trans('Add new', array(), 'Modules.FullFeaturesGroups.Admin'));
        $helper->table = 'fullfeaturesgroups_category';
        $helper->table_id = 'module-fullfeaturesgroups';
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . urlencode($this->name) . '&token=' . Tools::getAdminTokenLite('AdminModules');

        return $helper->generateList($featureFormCategory, $this->fields_list);
    }

    public function refreshPositionGroup()
    {
        $sql = 'SELECT `position`, `id_group` FROM `' . _DB_PREFIX_ . 'feature_group` ORDER BY `position` ASC;';
        $content = Db::getInstance()->executeS($sql);
        $numeric = 0;
        if (isset($content) && count($content) > 0) {
            foreach ($content as $value) {
                $sql = 'UPDATE `' . _DB_PREFIX_ . 'feature_group` SET `position` = ' . (int)$numeric++ . ' WHERE id_group = ' . (int)$value['id_group'] . ' ';
                Db::getInstance()->execute($sql);
            }
        }
    }

    public function getCategoryDataAssign()
    {
        $content = Category::getNestedCategories(null, (int)Context::getContext()->language->id);
        $html_category = $this->nested2ul($content, $this->getCategoryFeatureMode());
        return $this->context->smarty->assign('category_menu', $html_category);
    }

    public function getAllFeatureGroupAssign()
    {
        $select_feature = array();
        $id_lang = (int)Context::getContext()->language->id;
        $sql = 'SELECT feature.`id_group`, feature.`description`, feature.`position`, flang.`id_lang`, flang.`name`
					FROM `' . _DB_PREFIX_ . 'feature_group` feature
					LEFT JOIN `' . _DB_PREFIX_ . 'feature_group_lang` flang ON feature.`id_group` = flang.`id_group`
					WHERE flang.id_lang =' . (int)$id_lang . ' ORDER BY feature.`position` ASC, flang.`name` ASC;';
        $content_return = array();
        $content = Db::getInstance()->executeS($sql);
        $ExistFeature = $this->getCategoryFeatureIdCat((int)Tools::getValue('category'));
        foreach ($content as $key => $value) {
            if (isset($ExistFeature) && is_array($ExistFeature) && in_array($value['id_group'], $ExistFeature)) {
                continue;
            }
            $content_return[$value['id_group']]['id_group'] = $value['id_group'];
            $content_return[$value['id_group']]['name'] = $value['name'];
            $content_return[$value['id_group']]['description'] = $value['description'];
        }

        foreach ($content_return as $key) {
            $select_feature[] = array('value' => (int)$key['id_group'], 'name' => $key['name'], 'description' => $key['description']);
        }

        return $this->context->smarty->assign('select_feature', $select_feature);
    }

    public function getCategoryFeatureSelected()
    {
        $selected_feature = array();

        if ((int)Tools::getValue('category')) {
            $ExistFeature = $this->getCategoryFeatureValue((int)Tools::getValue('category'));

            if (isset($ExistFeature) && is_array($ExistFeature) && count($ExistFeature) > 0) {
                foreach ($ExistFeature as $key) {
                    $selected_feature[] = array('value' => (int)$key['id_group'], 'name' => $key['name'], 'description' => $key['description']);
                }
            }
        }

        return $this->context->smarty->assign('selected_feature', $selected_feature);
    }

    protected function getListContent()
    {
        $id_lang = (int)Context::getContext()->language->id;
        $query_filter = '';

        if (Tools::getValue('fullfeaturesgroupsFilter_id_group')) {
            $query_filter = ' AND featurecat.`id_group` LIKE \'%' . pSQL(Tools::getValue('fullfeaturesgroupsFilter_id_group')) . '%\'';
        }

        if (Tools::getValue('fullfeaturesgroupsFilter_name')) {
            $query_filter = ' AND flang.`name` LIKE \'%' . pSQL(Tools::getValue('fullfeaturesgroupsFilter_name')) . '%\'';
            if (Tools::getValue('fullfeaturesgroupsFilter_id_group')) {
                $query_filter .= ' AND featurecat.`id_group` LIKE \'%' . pSQL(Tools::getValue('fullfeaturesgroupsFilter_id_group')) . '%\'';
            }
        }

        if (Tools::getValue('fullfeaturesgroupsFilter_description')) {
            $query_filter .= ' AND featurecat.`description` LIKE \'%' . pSQL(Tools::getValue('fullfeaturesgroupsFilter_description')) . '%\'';
        }

        $Orderby = 'ORDER BY featurecat.position ASC';
        if (Tools::getValue('fullfeaturesgroupsOrderby')) {
            $side = (Tools::getValue('fullfeaturesgroupsOrderway') && Tools::getValue('fullfeaturesgroupsOrderway') == 'desc') ? 'DESC' : 'ASC';
            switch (Tools::getValue('fullfeaturesgroupsOrderby')) {
                case "id_group":
                    $Orderby = 'ORDER BY featurecat.id_group ' . $side;
                    break;
                case "name":
                    $Orderby = 'ORDER BY flang.name ' . $side;
                    break;
                case "description":
                    $Orderby = 'ORDER BY featurecat.description ' . $side;
                    break;
            }
        }

        $sql = 'SELECT featurecat.`id_group`, featurecat.`description`, featurecat.`position`, flang.`id_lang`, flang.`name`
					FROM `' . _DB_PREFIX_ . 'feature_group` featurecat
					LEFT OUTER JOIN `' . _DB_PREFIX_ . 'feature_group_lang` flang ON featurecat.`id_group` = flang.`id_group`
					WHERE flang.id_lang = ' . (int)$id_lang . '
					' . $query_filter . ' ' . $Orderby . ';';
        $content_return = array();
        $content = Db::getInstance()->executeS($sql);
        foreach ($content as $value) {
            $content_return[$value['id_group']]['text'] = Tools::substr(strip_tags($value['name']), 0, 200);
            $content_return[$value['id_group']]['id_group'] = $value['id_group'];
            $content_return[$value['id_group']]['position'] = $value['position'];
            $content_return[$value['id_group']]['description'] = $value['description'];
            $content_return[$value['id_group']]['name'] = $value['name'];
            $content_return[$value['id_group']]['items'] = $this->makeMenuOptionText((int)$value['id_group']);
        }
        return $content_return;
    }

    protected function getListContentLangGroup($group)
    {
        $sql = 'SELECT featurecat.`id_group`, featurecat.`description`, featurecat.`all_list`, flang.`id_lang`, flang.`name`
					FROM `' . _DB_PREFIX_ . 'feature_group` featurecat
					LEFT OUTER JOIN `' . _DB_PREFIX_ . 'feature_group_lang` flang ON featurecat.`id_group` = flang.`id_group`
					WHERE featurecat.id_group = ' . (int)$group . ' ;';
        $content_return = array();
        $content = Db::getInstance()->executeS($sql);
        foreach ($content as $value) {
            $content_return[$value['id_group']]['lang'][$value['id_lang']] = $value['name'];
            $content_return[$value['id_group']]['all_list'] = $value['all_list'];
            $content_return[$value['id_group']]['description'] = $value['description'];
            $content_return[$value['id_group']]['items'] = $this->makeMenuOptionText((int)$value['id_group']);
        }
        return $content_return;
    }

    public function getFeatureCategory()
    {
        $id_lang = (int)Context::getContext()->language->id;
        $query_filter = '';
        if (Tools::getValue('fullfeaturesgroups_categoryFilter_category')) {
            $query_filter = ' WHERE featurecat.`id_category` LIKE \'%' . pSQL(Tools::getValue('fullfeaturesgroups_categoryFilter_category')) . '%\'';
        }
        if (Tools::getValue('fullfeaturesgroups_categoryFilter_cat_name')) {
            $query_filter = ' WHERE cat.`name` LIKE \'%' . pSQL(Tools::getValue('fullfeaturesgroups_categoryFilter_cat_name')) . '%\'';
            if (Tools::getValue('fullfeaturesgroups_categoryFilter_category')) {
                $query_filter .= ' AND featurecat.`id_category` LIKE \'%' . pSQL(Tools::getValue('fullfeaturesgroups_categoryFilter_category')) . '%\'';
            }
        }
        $Orderby = 'ORDER BY featureitems.position ASC';
        if (Tools::getValue('fullfeaturesgroups_categoryOrderby')) {
            $side = (Tools::getValue('fullfeaturesgroups_categoryOrderway') && Tools::getValue('fullfeaturesgroups_categoryOrderway') == 'desc') ? 'DESC' : 'ASC';
            switch (Tools::getValue('fullfeaturesgroups_categoryOrderby')) {
                case "category":
                    $Orderby = 'ORDER BY featurecat.id_category ' . $side;
                    break;
                case "cat_name":
                    $Orderby = 'ORDER BY cat.name ' . $side;
                    break;
                case "name":
                    $Orderby = 'ORDER BY featureln.name ' . $side;
                    break;
                default:
                    $Orderby = 'ORDER BY featureitems.position ASC';
            }
        }

        $sql = 'SELECT featurecat.id_group, featurecat.id_category, featureitems.`description`, featureln.name, featureln.id_lang, cat.name AS cat_name
					FROM `' . _DB_PREFIX_ . 'feature_category` featurecat
						LEFT OUTER JOIN `' . _DB_PREFIX_ . 'category_lang` cat ON cat.id_category = featurecat.id_category AND cat.id_lang = ' . (int)$id_lang . '
						LEFT JOIN `' . _DB_PREFIX_ . 'feature_group` featureitems ON featureitems.id_group = featurecat.id_group
						LEFT OUTER JOIN `' . _DB_PREFIX_ . 'feature_group_lang` featureln ON featureln.id_group=featureitems.id_group AND cat.id_lang = ' . (int)$id_lang
            . $query_filter . ' ' . $Orderby;

        return Db::getInstance()->executeS($sql);
    }

    public function getFormValues()
    {
        $fields_value = array();
        $fields_value['id_group'] = (int)Tools::getValue('id_group');
        if ($fields_value['id_group'] > 0) { //update
            $ContentData = $this->getListContentLangGroup($fields_value['id_group']);

            $fields_value['all_list'] = $ContentData[$fields_value['id_group']]['all_list'];
            $fields_value['description'] = $ContentData[$fields_value['id_group']]['description'];
            foreach (Language::getLanguages(false) as $lang) {
                $fields_value['text'][(int)$lang['id_lang']] = $ContentData[$fields_value['id_group']]['lang'][(int)$lang['id_lang']];
            }
        } else {
            foreach (Language::getLanguages(false) as $lang) {
                $fields_value['text'][(int)$lang['id_lang']] = '';
            }
            $fields_value['all_list'] = 0;
            $fields_value['description'] = '';
        }

        return $fields_value;
    }

    public function renderChoicesSelect($all = false)
    {
        $return_form = array();

        $id_lang = (int)Context::getContext()->language->id;

        if ($all) {
            $sql = 'SELECT feature.`id_feature`, feature.`position`, flang.`id_lang`, flang.`name`
					FROM `' . _DB_PREFIX_ . 'feature` feature
					LEFT JOIN `' . _DB_PREFIX_ . 'feature_lang` flang ON feature.`id_feature` = flang.`id_feature`
                    WHERE flang.id_lang = ' . (int)$id_lang . ' ORDER BY flang.`name` ASC;';
        } else {
            $sql = 'SELECT feature.`id_feature`, feature.`position`, flang.`id_lang`, flang.`name`
					FROM `' . _DB_PREFIX_ . 'feature` feature
					LEFT JOIN `' . _DB_PREFIX_ . 'feature_lang` flang ON feature.`id_feature` = flang.`id_feature`
                    WHERE feature.`id_feature` NOT IN (SELECT id_feature FROM ' . _DB_PREFIX_ . 'feature_group_link)
                        AND flang.id_lang = ' . (int)$id_lang . '
                        ORDER BY flang.`name` ASC;';
        }

        $content_return = array();
        $content = Db::getInstance()->executeS($sql);

        foreach ($content as $key => $value) {
            $content_return[$value['id_feature']]['text'] = Tools::substr(strip_tags($value['name']), 0, 200);
            $content_return[$value['id_feature']]['id_feature'] = $value['id_feature'];
            $content_return[$value['id_feature']]['position'] = $value['position'];
            $content_return[$value['id_feature']]['id_lang'] = $value['id_lang'];
            $content_return[$value['id_feature']]['name'] = $value['name'];
        }

        foreach ($content_return as $key) {
            $return_form[] = array('def' => ((!$all) ? 1 : 0), 'value' => (int)$key['id_feature'], 'name' => $key['name']);
        }

        return $return_form;
    }

    protected function makeMenuOption()
    {
        $id_group = (int)Tools::getValue('id_group');
        $id_lang = (int)Context::getContext()->language->id;
        $return_menu = array();
        if ($id_group) {
            $sql = 'SELECT feature.`id_feature`, feature.`position`, flang.`id_lang`, flang.`name`
						FROM `' . _DB_PREFIX_ . 'feature_group_link` feature
						LEFT JOIN `' . _DB_PREFIX_ . 'feature_lang` flang ON feature.`id_feature` = flang.`id_feature`
						WHERE feature.id_group = ' . (int)$id_group . ' AND flang.id_lang=' . (int)$id_lang . ';';
            $content_return = array();
            $content = Db::getInstance()->executeS($sql);
            foreach ($content as $key => $value) {
                $content_return[$value['position']]['id_feature'] = $value['id_feature'];
                $content_return[$value['position']]['name'] = $value['name'];
            }
            ksort($content_return);
            foreach ($content_return as $key) {
                $return_menu[] = array('value' => (int)$key['id_feature'], 'name' => $key['name']);
            }
        }
        return $return_menu;
    }

    protected function makeMenuOptionText($uid = 0)
    {
        $id_group = $uid;
        $id_lang = (int)Context::getContext()->language->id;
        if ($id_group) {
            $sql = 'SELECT feature.`id_feature`, feature.`position`, flang.`id_lang`, flang.`name`
						FROM `' . _DB_PREFIX_ . 'feature_group_link` feature
						LEFT JOIN `' . _DB_PREFIX_ . 'feature_lang` flang ON feature.`id_feature` = flang.`id_feature`
						WHERE feature.id_group = ' . (int)$id_group . ' AND flang.id_lang = ' . (int)$id_lang . ';';
            $content_return = array();
            $content = Db::getInstance()->executeS($sql);
            foreach ($content as $value) {
                $content_return[$value['position']]['text'] = Tools::substr(strip_tags($value['name']), 0, 200);
                $content_return[$value['position']]['id_feature'] = $value['id_feature'];
                $content_return[$value['position']]['position'] = $value['position'];
                $content_return[$value['position']]['id_lang'] = $value['id_lang'];
                $content_return[$value['position']]['name'] = $value['name'];
            }
            ksort($content_return);
            $names = array();
            foreach ($content_return as $val) {
                $names[] = $val['name'];
            }
            $html = join(', ', $names);
        }

        return $html;
    }

    public function nested2ul($data, $catalog = array())
    {
        $page_active = (int)Tools::getValue('category');
        $result = array();
        if (sizeof($data) > 0) {
            $result[] = '<ul>';
            foreach ($data as $entry) {
                $html = '<li>';
                $disable = in_array($entry['id_category'], $catalog) && $entry['id_category'] != (int)$page_active ? ' disabled="disabled"' : '';
                $active = ($entry['id_category'] == (int)$page_active) ? 'checked=checked' : '';
                $html .= '<input' . $disable . ' ' . $active . ' type="radio" id="id_cat_' . $entry['id_category'] . '" value="cat_' . $entry['id_category'] . '" name="category"><label for="id_cat_' . $entry['id_category'] . '" class="itemlabel">' . $entry['name'] . '</label>';
                if (isset($entry['children'])) {
                    $result[] = $html . $this->nested2ul($entry['children'], $catalog) . '</li>';
                } else {
                    $result[] = $html . '</li>';
                }
            }
            $result[] = '</ul>';
        }
        return implode($result);
    }

    public function getCategoryFeatureMode()
    {
        $CatReturn = array();
        $CategoryList = Db::getInstance()->executeS('SELECT id_category FROM `' . _DB_PREFIX_ . 'feature_category` GROUP BY id_category');
        if (isset($CategoryList) && is_array($CategoryList) && count($CategoryList) > 0) {
            foreach ($CategoryList as $CatItems) {
                $CatReturn[] = $CatItems['id_category'];
            }
        }
        return $CatReturn;
    }

    public function getCategoryFeatureIdCat($Cat = '')
    {
        if (!isset($Cat) || empty($Cat) || !is_numeric($Cat)) {
            return '';
        }
        $CatReturn = array();
        $CategoryList = Db::getInstance()->executeS('SELECT `id_group` FROM `' . _DB_PREFIX_ . 'feature_category` WHERE id_category=' . (int)$Cat . ' GROUP BY id_group');
        if (isset($CategoryList) && is_array($CategoryList) && count($CategoryList) > 0) {
            foreach ($CategoryList as $CatItems) {
                $CatReturn[] = (int)$CatItems['id_group'];
            }
        }
        return $CatReturn;
    }

    public function getCategoryFeatureValue($Cat = '')
    {
        if (!isset($Cat) || empty($Cat) || !is_numeric($Cat)) {
            return '';
        }
        $id_lang = (int)Context::getContext()->language->id;
        $CategoryList = Db::getInstance()->executeS('SELECT featurecat.id_group, featuregroup.description, featuregroup_lang.name
															FROM `' . _DB_PREFIX_ . 'feature_category` featurecat
															INNER JOIN `' . _DB_PREFIX_ . 'feature_group` featuregroup ON featuregroup.id_group = featurecat.id_group
															LEFT JOIN `' . _DB_PREFIX_ . 'feature_group_lang` featuregroup_lang ON featuregroup_lang.id_group = featuregroup.id_group
															 WHERE featurecat.id_category=' . (int)$Cat . ' AND featuregroup_lang.id_lang = ' . (int)$id_lang . '
															 GROUP BY featuregroup.id_group ORDER BY featuregroup.position ASC');

        return $CategoryList;
    }

    public function _clearCacheId($cache_id = '')
    {
        parent::_clearCache('module:fullfeaturesgroups/views/templates/hook/column_divided.tpl', (!empty($cache_id)) ? 'fullfeaturesgroups' . $cache_id : '');
        parent::_clearCache('module:fullfeaturesgroups/views/templates/hook/custom_fullfeaturesgroups.tpl', (!empty($cache_id)) ? 'fullfeaturesgroups' . $cache_id : '');
        parent::_clearCache('module:fullfeaturesgroups/views/templates/hook/fullfeaturesgroups.tpl', (!empty($cache_id)) ? 'fullfeaturesgroups' . $cache_id : '');
        parent::_clearCache('module:fullfeaturesgroups/views/templates/hook/fullfeaturesgroups_content.tpl', (!empty($cache_id)) ? 'fullfeaturesgroups_content' . $cache_id : '');
    }

    protected function getFeaturesDataOld($id_product = 0, $default_cat = 0)
    {
        $return_feature = '';
        if ($id_product) {
            $id_lang = $this->context->language->id;
            $featuredata = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
								SELECT fgroup.id_group AS id_group, group_lang.name AS name_group, fl.name AS name_feature, fvl.value
									FROM ' . _DB_PREFIX_ . 'feature_group fgroup
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_group_lang group_lang ON fgroup.id_group = group_lang.id_group
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_group_link group_link ON group_link.id_group = fgroup.id_group
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_product pf ON pf.id_product = ' . (int)$id_product . ' AND pf.id_feature = group_link.id_feature
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_lang fl ON (fl.id_feature = pf.id_feature)
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_value_lang fvl ON (fvl.id_feature_value = pf.id_feature_value)
										LEFT JOIN ' . _DB_PREFIX_ . 'feature f ON (f.id_feature = pf.id_feature)
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_category fcat ON fcat.id_group = fgroup.id_group
										' . Shop::addSqlAssociation('feature', 'f') . '
										WHERE fl.id_lang = ' . (int)$id_lang . ' AND fvl.id_lang = ' . (int)$id_lang . ' AND group_lang.id_lang = ' . (int)$id_lang . '
										AND fcat.id_category = ' . (int)$default_cat . '
										ORDER BY fgroup.position ASC, group_link.position ASC');
            $return_feature = array();
            if (count($featuredata) > 0) {
                foreach ($featuredata as $featureitem) {
                    $return_feature[$featureitem['id_group']]['name'] = $featureitem['name_group'];
                    $return_feature[$featureitem['id_group']]['items'][] = array('name_group' => $featureitem['name_group'],
                        'name_feature' => $featureitem['name_feature'],
                        'value' => $featureitem['value']);
                }
            }
        }

        return $return_feature;
    }

    protected function getFeaturesData($id_product = 0, $default_cat = 0)
    {
        $return_feature = '';
        if ($id_product) {
            $id_lang = $this->context->language->id;
            $orderBy = $this->getFeatureValueOrdering();
            $featuredata = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
								SELECT fgroup.id_group AS id_group, group_lang.name AS name_group, fl.name AS name_feature, fvl.value, f.id_feature, fvl.id_feature_value
									FROM ' . _DB_PREFIX_ . 'feature_group fgroup
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_group_lang group_lang ON fgroup.id_group = group_lang.id_group
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_group_link group_link ON group_link.id_group = fgroup.id_group
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_product pf ON pf.id_product = ' . (int)$id_product . ' AND pf.id_feature = group_link.id_feature
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_lang fl ON (fl.id_feature = pf.id_feature)
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_value_lang fvl ON (fvl.id_feature_value = pf.id_feature_value)
										LEFT JOIN ' . _DB_PREFIX_ . 'feature f ON (f.id_feature = pf.id_feature)
										LEFT JOIN ' . _DB_PREFIX_ . 'feature_category fcat ON fcat.id_group = fgroup.id_group
										' . Shop::addSqlAssociation('feature', 'f') . '
										WHERE fl.id_lang = ' . (int)$id_lang . ' 
										AND fvl.id_lang = ' . (int)$id_lang . ' 
										AND group_lang.id_lang = ' . (int)$id_lang . '
										AND fcat.id_category = ' . (int)$default_cat . '
										ORDER BY fgroup.position ASC, group_link.position ASC, 
										    f.position ASC, ' . (($orderBy != 'custom') ? pSQL($orderBy) : 'pf.position ASC'));

            $return_feature = array();
            if (count($featuredata) > 0) {
                foreach ($featuredata as $featureitem) {
                    $return_feature[$featureitem['id_group']]['name'] = $featureitem['name_group'];
                    $return_feature[$featureitem['id_group']]['items'][$featureitem['id_feature']]['name_group'] = $featureitem['name_group'];
                    $return_feature[$featureitem['id_group']]['items'][$featureitem['id_feature']]['name_feature'] = $featureitem['name_feature'];
                    $dataExplodedVal = explode(',', $featureitem['value']);
                    foreach ($dataExplodedVal as $itemExploded) {
                        $return_feature[$featureitem['id_group']]['items'][$featureitem['id_feature']]['value'][] = $itemExploded; //;$featureitem['value'];
                    }
                }
            }
        }

        return $return_feature;
    }

    /**
     * @return string
     */
    protected function getFeatureValueOrdering()
    {
        switch (Configuration::get('FFG_FEATURE_VALUES_ORDERED')) {
            case 'fvl.value_DESC':
                return 'fvl.value DESC';
            case 'fvl.value_ASC':
                return 'fvl.value ASC';
            case 'fvl.id_feature_value_DESC':
                return 'fvl.id_feature_value DESC';
            case 'fvl.id_feature_value_ASC':
                return 'fvl.id_feature_value ASC';
            case 'custom':
                return 'custom';
            default:
                return 'fvl.value ASC';
                break;
        }
    }

    /**
     * @param null $hookName
     * @param array $configuration
     * @return array|string
     */
    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
        return $this->getFeaturesData($configuration['product']->id, $configuration['product']->id_category_default);
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/fullfeaturesgroups.css', 'all');
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function hookDisplayFFGFeatures($params = array())
    {
        return $this->hookDisplayFooterProduct($params, 1);
    }

    private $customHookTemplateName = 'custom_fullfeaturesgroups.tpl';

    protected function getTemplateSrc($isCustomEvent = false)
    {
        $generalPath = 'module:fullfeaturesgroups/views/templates/hook/';

//        if ($isCustomEvent) {
//            return $generalPath . $this->customHookTemplateName;
//        }

        $columnViewSelected = $this->getColumnClassPrefix();
        $this->smarty->assign(array(
            'column_class_prefix' => $columnViewSelected
        ));

        return $generalPath . (($columnViewSelected != 'default') ? 'column_divided.tpl' : 'fullfeaturesgroups.tpl');
    }

    /**
     * @return false|string
     */
    protected function getColumnClassPrefix()
    {
        $column = Configuration::get('FFG_FEATURE_COLUMN_QTY');

        if (in_array($column, ['one', 'two', 'three', 'default'])) {
            return $column;
        }

        return 'one';
    }

    /**
     * @param $params
     * @param bool $isCustomEvent
     * @return mixed
     */
    public function hookDisplayFooterProduct($params, $isCustomEvent = false)
    {
        $id_product = (isset($params['product']['id']) && Validate::isInt($params['product']['id'])) ? (int)$params['product']['id'] : 0;
        $templateSrc = $this->getTemplateSrc($isCustomEvent);

        $cacheId = 'fullfeaturesgroups' . $id_product . (($isCustomEvent) ? 'custom' : '');
        if (!$this->isCached($templateSrc, $this->getCacheId($cacheId))) {
            $this->smarty->assign(array(
                'data_feature' => $this->getFeaturesData($id_product, (int)$params['product']['id_category_default']),
                'new_line_properties' => ($isCustomEvent)
                    ? Configuration::get('FFG_VALUE_NEW_LINE_CUSTOM')
                    : Configuration::get('FFG_VALUE_NEW_LINE')
            ));
        }

        return $this->fetch($templateSrc, $this->getCacheId($cacheId));
    }

    protected function getCacheId($name = '')
    {
        if (empty($name)) {
            $name = $this->name;
        }
        return parent::getCacheId($name);
    }

    public function removeHtmlComments($content = '')
    {
        return preg_replace('/<!--(.|\s)*?-->/', '', $content);
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        $result_data = array();
        $template_src = 'module:fullfeaturesgroups/views/templates/hook/fullfeaturesgroups_content.tpl';
        if ($hookName == 'displayProductExtraContent') {
            $product = (array)$configuration['product'];
            $id_product = (isset($product['id']) && Validate::isInt($product['id'])) ? (int)$product['id'] : 0;

            $cacheKey = 'fullfeaturesgroups_content' . $id_product;
            if (!$this->isCached($template_src, $this->getCacheId($cacheKey))) {
                $dataFeature = $this->getFeaturesData($id_product, (int)$product['id_category_default']);
                $this->smarty->assign(array(
                    'data_feature' => $dataFeature,
                    'new_line_properties' => Configuration::get('FFG_VALUE_NEW_LINE')
                ));
            }

            $result_data = array();
            $box = $this->fetch($template_src, $this->getCacheId($cacheKey));
            $container_html = $this->removeHtmlComments($box);
            $container_html = trim(preg_replace('/\s\s+/', ' ', $container_html));
            if (!empty($container_html)) {
                $result_data[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
                    ->setTitle($this->l('Feature tab'))
                    ->setContent($container_html);
            }
        }

        return $result_data;
    }

    public static function getFeatureValueLang($id_feature_value, $id_lang)
    {
        return Db::getInstance()->getRow('
			SELECT `id_feature_value` AS id_feature, `value`
			FROM `' . _DB_PREFIX_ . 'feature_value_lang`
			WHERE `id_feature_value` = ' . (int)$id_feature_value . ' AND id_lang = ' . (int)$id_lang . '
			ORDER BY `id_lang`
		');
    }

    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        if (isset($params['id_product']) && Validate::isInt($params['id_product']) && (int)$params['id_product'] > 0) {
            $product = new Product((int)$params['id_product'], true, (int)$this->context->language->id);
            $this->initFormFeaturesData($product);
        }

        $this->context->smarty->assign('disabled_def_feature', Configuration::get('FFG_FEATURE_FRONT'));

        return $this->display(__FILE__, 'views/templates/admin/features.tpl');
    }

    public function initFormFeaturesData($obj)
    {
        $this->context->smarty->assign('default_form_language', $this->context->language->id);
        $this->context->smarty->assign('languages', Language::getLanguages());
        if ($obj->id) {
            if (isset($obj->id_category_default) && $obj->id_category_default > 0) {
                $features = $this->getFeaturesGroup((int)$this->context->language->id, (int)$obj->id_category_default);
                if (count($features) > 0) {
                    foreach ($features as $k => $tab_features) {
                        $features[$k]['current_item'] = array();
                        $features[$k]['val'] = array();
                        $custom = true;
                        foreach ($obj->getFeatures() as $tab_products) {
                            if ($tab_products['id_feature'] == $tab_features['id_feature']) {
                                $features[$k]['current_item'][] = $tab_products['id_feature_value'];
                            }
                        }
                        $features[$k]['featureValues'] = FeatureValue::getFeatureValuesWithLang($this->context->language->id, (int)$tab_features['id_feature']);
                        if (count($features[$k]['featureValues'])) {
                            foreach ($features[$k]['featureValues'] as $value) {
                                if (in_array($value['id_feature_value'], $features[$k]['current_item'])) {
                                    $custom = false;
                                }
                            }
                        }
                        if ($custom) {
                            if (!empty($features[$k]['current_item'])) {
                                $prepare_fvl = array();
                                $data_fvl = FeatureValue::getFeatureValueLang($features[$k]['current_item'][0]);
                                if ($data_fvl && count($data_fvl) > 0) {
                                    foreach ($data_fvl as $item) {
                                        $prepare_fvl[$item['id_lang']] = $item;
                                    }
                                }
                                $features[$k]['val'] = $prepare_fvl;
                            } else {
                                $features[$k]['val'] = false;
                            }
                        }
                    }

                    $features = $this->prepareFeatureValuesSortable($features);
                    $this->context->smarty->assign('available_features', $features);
                }
            }

            $this->context->smarty->assign('product', $obj);
            $this->context->smarty->assign('link', $this->context->link);
            $this->context->smarty->assign('default_form_language', $this->context->language->id);
        } else {
            $this->context->smarty->displayWarning($this->l('You must save this product before adding features.'));
        }
    }

    private function prepareFeatureValuesSortable($featureValues = array())
    {
        if (is_array($featureValues) && count($featureValues)) {
            foreach ($featureValues as &$featureValue) {
                if (isset($featureValue['current_item']) && count($featureValue['current_item'])) {
                    $newFeatureValuesArray = array();
                    foreach ($featureValue['current_item'] as $item) {
                        foreach ($featureValue['featureValues'] as $featureValueItem) {
                            if ($item == $featureValueItem['id_feature_value']) {
                                $newFeatureValuesArray[] = $featureValueItem;
                                break;
                            }
                        }
                    }
                    foreach ($featureValue['featureValues'] as $featureValueItem) {
                        if (!in_array($featureValueItem['id_feature_value'], $featureValue['current_item'])) {
                            $newFeatureValuesArray[] = $featureValueItem;
                        }
                    }
                    $featureValue['featureValues'] = $newFeatureValuesArray;
                }
            }
        }

        return $featureValues;
    }

    public function getFeaturesGroup($id_lang, $uid)
    {
        return Db::getInstance()->executeS('SELECT DISTINCT f.id_feature,f.*, flang.*
 											FROM ' . _DB_PREFIX_ . 'feature_category cat
											LEFT JOIN ' . _DB_PREFIX_ . 'feature_group_link catlink ON catlink.id_group = cat.id_group
											LEFT JOIN ' . _DB_PREFIX_ . 'feature_group catlink_group ON catlink_group.id_group = cat.id_group
											LEFT JOIN ' . _DB_PREFIX_ . 'feature f ON f.id_feature = catlink.id_feature
											LEFT JOIN ' . _DB_PREFIX_ . 'feature_lang flang ON flang.id_feature = f.id_feature
											WHERE flang.id_lang = ' . (int)$id_lang . ' AND cat.id_category = ' . (int)$uid . '
											    ORDER BY catlink_group.`position` ASC, catlink.`position` ASC, f.`position` ASC');
    }

    /**
     * @param $params
     */
    public function hookActionObjectProductUpdateAfter($params)
    {
        if (Tools::isSubmit('submitted_ffg_tabs') && Tools::getValue('submitted_ffg_tabs') == 'Features') {
            $this->processFeatures((int)Tools::getValue('id_product', 0));
            $this->_clearCacheId();
        }
    }

    /**
     * @param $params
     */
    public function hookActionProductUpdate($params)
    {
        if (Tools::isSubmit('submitted_ffg_tabs') && Tools::getValue('submitted_ffg_tabs') == 'Features') {
            $this->processFeatures((int)Tools::getValue('id_product', 0));
            $this->_clearCacheId();
        }
    }

    /**
     * @param $params
     */
    public function hookUpdateProduct($params)
    {
        if (isset($params['id_product']) && $params['id_product'] > 0) {
            $this->processFeatures((int)$params['id_product']);
            $this->_clearCacheId();
        }
    }

    /**
     * @param $params
     */
    public function hookDeleteProduct($params)
    {
        if (isset($params['id_product']) && $params['id_product'] > 0) {
            $this->_clearCacheId();
        }
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function processFeatures()
    {
        if (!Feature::isFeatureActive()) {
            return;
        }

        $id_product = (int)Tools::getValue('id_product');
        if (Validate::isLoadedObject($product = new Product($id_product))) {
            $product->deleteFeatures();
            $languages = Language::getLanguages(false);
            $customExisted = array();
            foreach ($_POST as $key => $val) {
                if (preg_match('/^custom_gr_([0-9]+)_([0-9]+)/i', $key, $match)) {
                    if (in_array($match[1], $customExisted)) {
                        continue;
                    }

                    if ($defaultValue = $this->checkFeatures($languages, $match[1])) {
                        $idValue = $product->addFeaturesToDB($match[1], 0, 1);
                        foreach ($languages as $language) {
                            $custom = Tools::getValue('custom_gr_' . $match[1] . '_' . (int)$language['id_lang']);
                            $valueToAdd = (!empty($custom))
                                ? $custom
                                : $defaultValue;
                            $product->addFeaturesCustomToDB($idValue, (int)$language['id_lang'], $valueToAdd);

                            $customExisted[] = $match[1];
                        }
                    }
                }
            }

            foreach ($_POST as $key => $val) {
                if (preg_match('/^feature_gr_([0-9]+)_value/i', $key, $match)) {
                    if (!empty($val) && !is_array($val)
                        || is_array($val) && count($val) > 1
                        || is_array($val) && count($val) == 1 && $val[0] != 0) {
                        if (in_array($match[1], $customExisted)) {
                            continue;
                        }

                        if (is_array($val)) {
                            $position = 0;
                            foreach ($val as $elt) {
                                $product->addFeaturesToDB($match[1], $elt);
                                $this->updateSortable($id_product, $elt, $position++);
                            }
                        } else {
                            $product->addFeaturesToDB($match[1], $val);
                            $this->updateSortable($id_product, $val, 0);
                        }
                    }
                }
            }
        } else {
            $this->errors[] = Tools::displayError('A product must be created before adding features.');
        }
    }

    /**
     * @param $id_product
     * @param $id_feature
     * @param $sort
     */
    protected function updateSortable($id_product, $id_feature, $sort)
    {
        Db::getInstance()->update('feature_product', ['position' => (int)$sort], 'id_product=' . $id_product . ' AND id_feature_value=' . (int)$id_feature);
    }

    /**
     * @param $languages
     * @param $feature_id
     * @return false|int|mixed
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function checkFeatures($languages, $feature_id)
    {
        $rules = call_user_func(array('FeatureValue', 'getValidationRules'), 'FeatureValue');
        $feature = Feature::getFeature((int)Configuration::get('PS_LANG_DEFAULT'), $feature_id);

        foreach ($languages as $language) {
            if ($val = Tools::getValue('custom_gr_' . $feature_id . '_' . $language['id_lang'])) {
                $current_language = new Language($language['id_lang']);
                if (Tools::strlen($val) > $rules['sizeLang']['value']) {
                    $this->errors[] = $this->trans(
                        'The name for feature %1$s is too long in %2$s.',
                        array(
                            ' <b>' . $feature['name'] . '</b>',
                            $current_language->name
                        ),
                        'Admin.Catalog.Notification'
                    );
                } elseif (!call_user_func(array('Validate', $rules['validateLang']['value']), $val)) {
                    $this->errors[] = $this->trans(
                        'A valid name required for feature. %1$s in %2$s.',
                        array(' <b>' . $feature['name'] . '</b>', $current_language->name),
                        'Admin.Catalog.Notification'
                    );
                }

                if (count($this->errors)) {
                    return 0;
                }
                // Getting default language
                if ($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT')) {
                    return $val;
                }
            }
        }

        return 0;
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure' == 'fullfeaturesgroups')
            || isset($this->context->controller->controller_name)
            && ($this->context->controller->controller_name == 'AdminProducts')
            || $this->context->controller->controller_name == 'AdminFullFeatureFastView') {
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
            if (method_exists($this->context->controller, 'addJquery')) {
                $this->context->controller->addJquery();
            }
            $this->context->controller->addJs($this->_path . 'views/js/widget/Sortable.min.js');
            $this->context->controller->addJs($this->_path . 'views/js/back-product.js');
        }
    }

    public function verifyTableIntegrity()
    {
        $tables = array(
            array(
                'name' => 'feature_product',
                'fields' => array(
                    'position' => 'int.default.0'
                )
            )
        );

        foreach ($tables as $tableKey => $table) {
            $result = Db::getInstance()->executeS("SHOW TABLES LIKE '" . _DB_PREFIX_ . pSQL($table['name']) . "'");
            if ($result) {
                $tableColumns = Db::getInstance()->executeS('SHOW COLUMNS FROM ' . _DB_PREFIX_ . pSQL($table['name']));
                if ($tableColumns && is_array($tableColumns) && count($tableColumns)) {
                    foreach ($tableColumns as $tableColumn) {
                        if (isset($tables[$tableKey]['fields'][$tableColumn['Field']])) {
                            unset($tables[$tableKey]['fields'][$tableColumn['Field']]);
                        }
                    }
                }
            }
        }

        foreach ($tables as $itemTable) {
            if (count($itemTable['fields'])) {
                $result = Db::getInstance()->executeS("SHOW TABLES LIKE '" . _DB_PREFIX_ . pSQL($table['name']) . "'");
                if (!$result) {
                    $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . pSQL($table['name']) . '` (';
                    $primary = '';
                    $sql_inner_fields = array();
                    foreach ($table['fields'] as $fieldKey => $field) {
                        $sql_inner_fields[] = '`' . pSQL($fieldKey) . '` ' . $this->getSqlByFieldType($field);
                        if ($field == 'int-autoincrement') {
                            $primary = ', PRIMARY KEY  (`' . pSQL($fieldKey) . '`)';
                        }
                    }
                    $sql .= join(', ', $sql_inner_fields) . $primary . ') ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
                    DB::getInstance()->execute($sql);
                } else {
                    foreach ($itemTable['fields'] as $fieldName => $fieldType) {
                        $tableType = '';
                        switch ($fieldType) {
                            case 'int-autoincrement':
                                $tableType = 'int(11) NOT NULL AUTO_INCREMENT';
                                break;
                            case 'int':
                                $tableType = 'INT(11)';
                                break;
                            case 'tinyint':
                                $tableType = 'TINYINT NOT NULL DEFAULT 0';
                                break;
                            case 'varchar':
                                $tableType = 'VARCHAR(256) NOT NULL';
                                break;
                            case 'text':
                                $tableType = 'TEXT DEFAULT NULL';
                                break;
                            case 'date':
                                $tableType = 'DATETIME NULL';
                                break;
                            case 'int.default.0':
                                $tableType = 'INT NULL DEFAULT 0';
                                break;
                        }
                        try {
                            Db::getInstance()->execute('
                            ALTER TABLE `' . _DB_PREFIX_ . pSQL($itemTable['name']) . '`
                            ADD COLUMN `' . pSQL($fieldName) . '` ' . pSQL($tableType));
                        } catch (Exception $e) {
                            return array(
                                'error' => true,
                                'msg' => $e->getMessage()
                            );
                        }
                    }
                }
            }
        }

        return true;
    }

    public function renderDiscoverModules()
    {
        $modules = array();
        $lang_code = $this->context->language->iso_code;

        $modules_file = _PS_CACHEFS_DIRECTORY_ . 'terranetpro-modules.xml';
        if (!Tools::file_exists_no_cache($modules_file) || (filemtime($modules_file) < (time() - 86400))) {
            $contents = @Tools::file_get_contents('http://terranetpro.com/modules.xml');
            if ($contents) {
                file_put_contents($modules_file, $contents);
            } else {
                $modules_file = $this->getLocalPath() . '/config/modules.xml';
            }
        }

        if (Tools::file_exists_no_cache($modules_file)) {
            $modules_list = simplexml_load_file($modules_file);
            foreach ($modules_list->children() as $module) {
                $id_product = (string)$module['id_product'];
                if ($this->id_product == $id_product) {
                    continue;
                }

                if (empty($module->$lang_code)) {
                    $lang_code = 'en';
                }

                $product_mod_name = '';
                if (isset($module->{$lang_code})) {
                    $name_tmp = $module->{$lang_code};
                    $product_mod_name = (isset($name_tmp['name']) && !empty($name_tmp['name'])) ? $name_tmp['name'] : '';
                }

                $modules[] = array(
                    'id_product' => $id_product,
                    'rate' => (string)$module['rate'],
                    'lang_code' => $lang_code,
                    'name' => (string)$product_mod_name,
                    'description' => (string)$module->{$lang_code}
                );
            }
        }

        $this->smarty->assign(array(
            'this_module' => $this,
            'modules' => array_slice($modules, 0, 3),
            'labels' => array(
                'like' => $this->l('Do you like the [1]%s[/1] module?'),
                'yes' => $this->l('Yes'),
                'no' => $this->l('No'),
                'title' => $this->l('Promote your products'),
                'discover' => $this->l('Discover')
            )
        ));

        return $this->display($this->name, 'modules.tpl');
    }
}
