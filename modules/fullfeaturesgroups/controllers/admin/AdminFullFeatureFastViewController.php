<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class AdminFullFeatureFastViewController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->display = 'view';
        $this->table = 'fullfeaturesgroups';

        parent::__construct();
        $this->meta_title = $this->l('Full Features Groups product Info');

        if (Tools::isSubmit('submitFilterfullfeaturefast') && Tools::getValue('submitFilterfullfeaturefast') == 0) {
            $this->action = 'reset_filters';
        }

        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        Media::addJsDefL('fullfeaturefastview_url', AdminController::$currentIndex);
        Media::addJsDefL('fullfeaturefastview_token', Tools::getAdminTokenLite('AdminFullFeatureFastView'));

        $this->addJS(_PS_MODULE_DIR_ . $this->module->name . '/views/js/backend.js');
    }

    public function initToolBarTitle()
    {
        $this->toolbar_title[] = $this->l('Administration');
        $this->toolbar_title[] = $this->l('Full Features Groups product Info');
    }

    public function initContent()
    {
        parent::initContent();
    }

    public function renderView()
    {
        $default_lang = (int)Context::getContext()->language->id;
        $this->name = $this->module->name;
        $fields_list = array(
            'id_product' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
                'class' => 'fixed-width-xs',
                'search' => true
            ),
            'image' => array(
                'title' => $this->l('Image'),
                'type' => 'image',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false
            ),
            'name' => array(
                'title' => $this->l('Category name / Product name'),
                'type' => 'product_name',
                'search' => true
            ),
            'reference' => array(
                'title' => $this->l('Reference'),
                'type' => 'text',
                'search' => true
            ),
            'group_category' => array(
                'title' => $this->l('Features'),
                'type' => 'feature_fast_edit',
                'default_lang' => (int)$default_lang,
                'search' => false,
                'orderby' => false
            )
        );

        $helper = new HelperList();
        $this->list_id = 'fullfeaturefast';
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->table = 'fullfeaturefast';
        $helper->listTotal = $this->getFeatureComList($default_lang, 0, 0);
        $helper->actions = array();
        $helper->identifier = 'id_product';
        $helper->show_toolbar = true;
        $helper->module = $this->module;
        $helper->title = $this->l('Full Features Groups product Info');
        $helper->token = Tools::getAdminTokenLite('AdminFullFeatureFastView');
        $helper->currentIndex = AdminController::$currentIndex;
        $helper->override_folder = 'full_feature_fast_view/';
        $page_num = (isset($this->context->cookie->fullfeaturefast_pagination) && is_numeric($this->context->cookie->fullfeaturefast_pagination)
            && $this->context->cookie->fullfeaturefast_pagination > 0) ? (int)$this->context->cookie->fullfeaturefast_pagination : 50;
        $lists_data = $this->getFeatureComList($default_lang, 0, ((!empty($page_num) && is_numeric($page_num)) ? (int)$page_num : 50));

        return $helper->generateList($lists_data, $fields_list);
    }

    public function displayViewLink($token = null, $id = '', $name = null)
    {
        if (isset($this->tabAccess['view']) && $this->tabAccess['view'] == 1) {
            $tpl = $this->createTemplate('helpers/list/list_action_edit.tpl');
            if (!array_key_exists('View', self::$cache_lang)) {
                self::$cache_lang['View'] = $this->l('View', 'Helper');
            }

            $tpl->assign(array(
                'href' => $this->context->link->getAdminLink('AdminProducts', true, array('id_product' => (int)$id)),
                'action' => self::$cache_lang['View'],
                'id' => $id
            ));

            return $tpl->fetch();
        } else {
            return '';
        }
    }

    protected function getFeatureComList($id_lang, $start, $limit)
    {
        if (!$this->context) {
            $this->context = Context::getContext();
        }

        $filterWhere = array();
        if (Tools::getValue('fullfeaturefastFilter_id_product', false) && is_numeric(Tools::getValue('fullfeaturefastFilter_id_product')) && Tools::getValue('fullfeaturefastFilter_id_product') > 0) {
            $filterWhere['id_product'] = (int)Tools::getValue('fullfeaturefastFilter_id_product');
        }
        if (Tools::getValue('fullfeaturefastFilter_name', false) && !empty(Tools::getValue('fullfeaturefastFilter_name'))) {
            $filterWhere['name'] = Tools::getValue('fullfeaturefastFilter_name');
        }
        if (Tools::getValue('fullfeaturefastFilter_default_category', false) && !empty(Tools::getValue('fullfeaturefastFilter_default_category'))) {
            $filterWhere['default_category'] = Tools::getValue('fullfeaturefastFilter_default_category');
        }
        if (Tools::getValue('fullfeaturefastFilter_group_category', false) && !empty(Tools::getValue('fullfeaturefastFilter_group_category'))) {
            $filterWhere['group_category'] = Tools::getValue('fullfeaturefastFilter_group_category');
        }
        if (Tools::getValue('fullfeaturefastFilter_reference', false) && !empty(Tools::getValue('fullfeaturefastFilter_reference'))) {
            $filterWhere['product_reference'] = Tools::getValue('fullfeaturefastFilter_reference');
        }

        if (empty($limit)) {
            $sql = 'SELECT p.`id_product`, cl.`name`
            FROM `' . _DB_PREFIX_ . 'product` p ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')' . '
                LEFT JOIN `' . _DB_PREFIX_ . 'feature_category` fc ON (fc.`id_category` = p.`id_category_default`)
                LEFT JOIN `' . _DB_PREFIX_ . 'feature_group_link` fglk ON (fc.`id_group` = fglk.`id_group`)
                LEFT JOIN `' . _DB_PREFIX_ . 'feature_product` fpgl ON (p.`id_product` = fpgl.`id_product` AND fpgl.`id_feature` = fglk.`id_feature`)
                LEFT JOIN `' . _DB_PREFIX_ . 'feature_group_lang` fgl ON (fgl.`id_group` = fglk.`id_group` AND fgl.`id_lang`=' . (int)$id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.id_lang=' . (int)$id_lang . ')
                WHERE pl.`id_lang` = ' . (int)$id_lang
                . (isset($filterWhere['id_product']) && !empty($filterWhere['id_product']) ? ' AND p.`id_product` = ' . (int)$filterWhere['id_product'] : '') .
                (isset($filterWhere['name']) && !empty($filterWhere['name']) ? ' AND (pl.`name` LIKE "%' . pSQL($filterWhere['name']) . '%" OR cl.`name` LIKE "%' . pSQL($filterWhere['name']) . '%")' : '') .
                (isset($filterWhere['default_category']) && !empty($filterWhere['default_category']) ? ' AND cl.`name` LIKE "%' . pSQL($filterWhere['default_category']) . '%"' : '') .
                (isset($filterWhere['group_category']) && !empty($filterWhere['group_category']) ? ' AND fgl.`name` LIKE "%' . pSQL($filterWhere['group_category']) . '%"' : '') .
                (isset($filterWhere['product_reference']) && !empty($filterWhere['product_reference']) ? ' AND p.`reference` LIKE "%' . pSQL($filterWhere['product_reference']) . '%"' : '') . '
                GROUP BY p.id_product';

            $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

            return count($rq);
        }

        $order_by = Tools::getValue('fullfeaturefastOrderby', 'id_product');
        $order_way = Tools::getValue('fullfeaturefastOrderway', 'ASC');

        if (!Tools::getValue('fullfeaturefastOrderby', false)) {
            if (isset($this->context->cookie->fullfeaturefastviewfullfeaturesgroupsOrderway) && !empty($this->context->cookie->fullfeaturefastviewfullfeaturesgroupsOrderway)) {
                $order_way = $this->context->cookie->fullfeaturefastviewfullfeaturesgroupsOrderway;
            }
            if (isset($this->context->cookie->fullfeaturefastviewfullfeaturesgroupsOrderby) && !empty($this->context->cookie->fullfeaturefastviewfullfeaturesgroupsOrderby)) {
                $order_by = $this->context->cookie->fullfeaturefastviewfullfeaturesgroupsOrderby;
            }
        } else {
            $this->context->cookie->fullfeaturefastviewfullfeaturesgroupsOrderway = $order_way;
            $this->context->cookie->fullfeaturefastviewfullfeaturesgroupsOrderby = $order_by;
        }

        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }
        if ($order_by == 'id_product') {
            $order_by_prefix = 'p';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        } elseif ($order_by == 'default_category') {
            $order_by_prefix = 'cl';
            $order_by = 'name';
        } elseif ($order_by == 'group_category') {
            $order_by_prefix = 'fgl';
            $order_by = 'name';
        } elseif ($order_by == 'complete') {
            $order_by = 'flall_count';
        } elseif ($order_by == 'status') {
            $order_by = 'fstatus';
        } else {
            $order_by_prefix = 'p';
            $order_by = 'id_product';
        }

        /* Determine offset from current page */
        if (!empty(Tools::getValue('submitFilter' . $this->list_id)) && is_numeric(Tools::getValue('submitFilter' . $this->list_id))
            && Tools::getValue('submitFilter' . $this->list_id) > 1
        ) {
            $start = ((int)Tools::getValue('submitFilter' . $this->list_id) - 1) * $limit;
        }

        $sql = 'SELECT p.`id_product`, p.`reference`, p.`id_category_default`, pl.`name`, pl.`link_rewrite`, cl.`name` AS cat_name,
                fgl.name AS gr_name, COUNT(fpgl.`id_feature`) AS fcount,
                (SELECT COUNT(fgl.id_feature) FROM `' . _DB_PREFIX_ . 'feature_category` AS fc
                      LEFT JOIN `' . _DB_PREFIX_ . 'feature_group_link` fgl ON (fc.`id_group` = fgl.`id_group`)
                        WHERE fc.id_category = p.`id_category_default`) AS flall_count,
                        IF((SELECT COUNT(fgl.id_feature) FROM `' . _DB_PREFIX_ . 'feature_category` AS fc
                      LEFT JOIN `' . _DB_PREFIX_ . 'feature_group_link` fgl ON (fc.`id_group` = fgl.`id_group`)
                        WHERE fc.id_category = p.`id_category_default`) <> COUNT(fpgl.`id_feature`) OR COUNT(fpgl.`id_feature`) = 0, 0, 1) AS fstatus
            FROM `' . _DB_PREFIX_ . 'product` p ' . Shop::addSqlAssociation('product', 'p') . '
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` ' . Shop::addSqlRestrictionOnLang('pl') . ')' . '
            LEFT JOIN `' . _DB_PREFIX_ . 'feature_category` fc ON (fc.`id_category` = p.`id_category_default`)
            LEFT JOIN `' . _DB_PREFIX_ . 'feature_group_link` fglk ON (fc.`id_group` = fglk.`id_group`)
            LEFT JOIN `' . _DB_PREFIX_ . 'feature_product` fpgl ON (p.`id_product` = fpgl.`id_product` AND fpgl.`id_feature` = fglk.`id_feature`)
            LEFT JOIN `' . _DB_PREFIX_ . 'feature_group_lang` fgl ON (fgl.`id_group` = fglk.`id_group` AND fgl.`id_lang`=' . (int)$id_lang . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.id_lang=' . (int)$id_lang . ')
            WHERE pl.`id_lang` = ' . (int)$id_lang .
            (isset($filterWhere['id_product']) && !empty($filterWhere['id_product']) ? ' AND p.`id_product` = ' . (int)$filterWhere['id_product'] : '') .
            (isset($filterWhere['name']) && !empty($filterWhere['name']) ? ' AND (pl.`name` LIKE "%' . pSQL($filterWhere['name']) . '%" OR cl.`name` LIKE "%' . pSQL($filterWhere['name']) . '%")' : '') .
            (isset($filterWhere['default_category']) && !empty($filterWhere['default_category']) ? ' AND cl.`name` LIKE "%' . pSQL($filterWhere['default_category']) . '%"' : '') .
            (isset($filterWhere['group_category']) && !empty($filterWhere['group_category']) ? ' AND fgl.`name` LIKE "%' . pSQL($filterWhere['group_category']) . '%"' : '') .
            (isset($filterWhere['product_reference']) && !empty($filterWhere['product_reference']) ? ' AND p.`reference` LIKE "%' . pSQL($filterWhere['product_reference']) . '%"' : '') . '
            GROUP BY p.id_product
                ORDER BY ' . (isset($order_by_prefix) ? pSQL($order_by_prefix) . '.' : '') . '`' . pSQL($order_by) . '` ' . pSQL($order_way)
            . ($limit > 0 ? ' LIMIT ' . (int)$start . ',' . (int)$limit : '');

        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if ($rq && count($rq) > 0) {
            foreach ($rq as &$featVal) {
                $featVal = $this->getFeatureByCatProductId($featVal, $featVal['id_category_default']);
            }
        }

        $products = array();
        $image_type_small = ImageType::getFormattedName('small');
        foreach ($rq as $result) {
            $cover_image = Product::getCover($result['id_product']);
            $image_url = (isset($cover_image['id_image']) && !empty($cover_image['id_image'])) ? $this->context->link->getImageLink($result['link_rewrite'], $cover_image['id_image'], $image_type_small) : '';
            $products[] = array(
                'id_product' => (int)$result['id_product'],
                'reference' => $result['reference'],
                'image' => $image_url,
                'default_category' => $result['cat_name'],
                'group_category' => ((!empty($result['gr_name'])) ? $result['gr_name'] : ''),
                'name' => $result['name'],
                'complete' => $result['fcount'] . '/' . $result['flall_count'],
                'status' => ($result['fcount'] == $result['flall_count'] && $result['flall_count'] > 0) ? 1 : 0,
                'features' => (isset($result['features']) && count($result['features'])) ? $result['features'] : array()
            );
        }

        return ($products);
    }

    public function getDefaultCategoryByIdProduct($id_product)
    {
        $default_category = Db::getInstance()->getValue('
			SELECT product_shop.`id_category_default`
			FROM `' . _DB_PREFIX_ . 'product` p
			' . Shop::addSqlAssociation('product', 'p') . '
			WHERE p.`id_product` = ' . (int)$id_product);

        if (!$default_category) {
            return array('id_category_default' => Context::getContext()->shop->id_category);
        } else {
            return $default_category;
        }
    }

    public function getFeatureByCatProductId($product, $id_category_default)
    {
        $features = $this->getFeaturesGroup((int)$this->context->language->id, (int)$id_category_default);
        if (!$features || empty($features) || count($features) == 0) {
            return $product;
        }

        if (count($features) > 0) {
            foreach ($features as $k => $tab_features) {
                $features[$k]['current_item'] = array();
                $features[$k]['val'] = array();
                $custom = true;

                $id_product = (is_array($product) && isset($product['id_product'])) ? (int)$product['id_product'] : (int)$product;
                foreach (Product::getFeaturesStatic((int)$id_product) as $tab_products) {
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

                if ($custom && isset($features[$k]['current_item'][0])
                    && Validate::isInt($features[$k]['current_item'][0])
                    && $features[$k]['current_item'][0] > 0) {
                    $feature_values_lang = FeatureValue::getFeatureValueLang((int)$features[$k]['current_item'][0]);
                    foreach ($feature_values_lang as $feature_value) {
                        $features[$k]['val'][$feature_value['id_lang']] = $feature_value;
                    }
                }
            }
        }

        if (!is_array($product) && is_numeric($product)) {
            return $features;
        }

        $product['features'] = $features;

        return $product;
    }

    public function getFeaturesGroup($id_lang, $uid)
    {
        return Db::getInstance()->executeS(' SELECT DISTINCT f.id_feature,f.*, flang.*
 											FROM ' . _DB_PREFIX_ . 'feature_category cat
											LEFT JOIN ' . _DB_PREFIX_ . 'feature_group_link catlink ON catlink.id_group = cat.id_group
											LEFT JOIN ' . _DB_PREFIX_ . 'feature_group catlink_group ON catlink_group.id_group = cat.id_group
											LEFT JOIN ' . _DB_PREFIX_ . 'feature f ON f.id_feature = catlink.id_feature
											LEFT JOIN ' . _DB_PREFIX_ . 'feature_lang flang ON flang.id_feature = f.id_feature
											WHERE flang.id_lang = ' . (int)$id_lang . ' AND cat.id_category = ' . (int)$uid . '
											    ORDER BY catlink_group.`position` ASC, catlink.`position` ASC, f.`position` ASC');
    }


    public function ajaxProcessGetFeatureEditForm()
    {
        $form = $features = array();
        $id_product = (int)Tools::getValue('id_product', 0);
        $id_feature = (int)Tools::getValue('id_feature', 0);
        if (Validate::isInt($id_product) && Validate::isInt($id_feature)
            && $id_product > 0 && $id_feature > 0) {
            $obj = new Product($id_product, $this->context->language->id);
            if ($obj->id) {
                $data = $this->createTemplate('features.tpl');
                $data->assign('default_form_language', $this->default_form_language);

                if (isset($obj->id_category_default) && $obj->id_category_default > 0) {
                    $feature = Feature::getFeature($this->context->language->id, $id_feature);
                    $features['name'] = $feature['name'];
                    $features['id_feature'] = $id_feature;
                    $features['id_product'] = $id_product;
                    $features['current_item'] = array();
                    $features['val'] = array();
                    $custom = true;
                    foreach ($obj->getFeatures() as $tab_products) {
                        if ($tab_products['id_feature'] == $id_feature) {
                            $features['current_item'][] = $tab_products['id_feature_value'];
                        }
                    }

                    $features['featureValues'] = FeatureValue::getFeatureValuesWithLang($this->context->language->id, (int)$id_feature);

                    if (count($features['featureValues'])) {
                        foreach ($features['featureValues'] as $value) {
                            if (in_array($value['id_feature_value'], $features['current_item'])) {
                                $custom = false;
                            }
                        }
                    }

                    if ($custom && isset($features['current_item'][0])) {
                        $feature_values_lang = FeatureValue::getFeatureValueLang($features['current_item'][0]);
                        foreach ($feature_values_lang as $feature_value) {
                            $features['val'][$feature_value['id_lang']] = $feature_value;
                        }
                    }

                    $data->assign('available_feature', $features);
                }

                $data->assign('available_features', $features);
                $data->assign('product', $obj);
                $data->assign('link', $this->context->link);
                $data->assign('languages', Language::getLanguages());
                $data->assign('default_form_language', $this->context->language->id);
                $custom_form = $data->fetch();
            }
        }

        $form['post'] = $_POST;
        $form['form'] = (isset($custom_form) && !empty($custom_form) ? $custom_form : 'no generated form');

        die(json_encode($form));
    }

    public function ajaxProcessSetFeatureEditForm()
    {

        $result = array();
        $result['confirmations'] = '';
        $result['error'] = '';

        $id_feature = (int)Tools::getValue('id_feature');
        $id_product = (int)Tools::getValue('id_product');

        if (Validate::isLoadedObject($product = new Product($id_product))) {
            $this->deleteFeaturesById($id_product, $id_feature);
            $languages = Language::getLanguages(false);
            foreach ($_POST as $key => $val) {
                if (preg_match('/^feature_([0-9]+)_value/i', $key, $match)) {
                    if ($val && is_array($val) && (count($val) > 1) || (isset($val[0]) && $val[0] != 0)) {
                        if (is_array($val)) {
                            foreach ($val as $elt) {
                                $product->addFeaturesToDB($match[1], $elt);
                            }
                        } else {
                            $product->addFeaturesToDB($match[1], $val);
                        }
                    } else {
                        if ($default_value = $this->checkFeatures($languages, $match[1])) {
                            $id_value = $product->addFeaturesToDB($match[1], 0, 1);
                            foreach ($languages as $language) {
                                if ($cust = Tools::getValue('custom_' . $match[1] . '_' . (int)$language['id_lang'])) {
                                    $product->addFeaturesCustomToDB($id_value, (int)$language['id_lang'], $cust);
                                } else {
                                    $product->addFeaturesCustomToDB($id_value, (int)$language['id_lang'], $default_value);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (count($this->errors) == 0) {
            $result['confirmations'] = $this->l('Successfully updated!');
        } else {
            $result['error'] = join(' ', $this->errors);
        }

        $id_category = $this->getDefaultCategoryByIdProduct($id_product);
        $featureData = $this->getFeatureByCatProductId($id_product, $id_category);

        $result['features'] = $featureData;
        $result['id_product'] = $id_product;

        $data = $this->createTemplate('features_item.tpl');
        $data->assign('tr', $result);
        $data->assign('default_lang', (int)$this->context->language->id);
        $form = $data->fetch();

        $result['form'] = $form;

        die(json_encode($result));
    }

    /* Checking customs feature */
    protected function checkFeatures($languages, $feature_id)
    {
        $rules = call_user_func(array('FeatureValue', 'getValidationRules'), 'FeatureValue');
        $feature = Feature::getFeature((int)Configuration::get('PS_LANG_DEFAULT'), $feature_id);

        foreach ($languages as $language) {
            if ($val = Tools::getValue('custom_' . $feature_id . '_' . $language['id_lang'])) {
                $current_language = new Language($language['id_lang']);
                if (Tools::strlen($val) > $rules['sizeLang']['value']) {
                    $this->errors[] = sprintf(
                        'The name for feature %1$s is too long in %2$s.',
                        '<b>' . $feature['name'] . '</b>',
                        $current_language->name
                    );
                } elseif (!call_user_func(array('Validate', $rules['validateLang']['value']), $val)) {
                    $this->errors[] = sprintf(
                        'A valid name required for feature. %1$s in %2$s.',
                        ' <b>' . $feature['name'] . '</b>',
                        $current_language->name
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

    public function deleteFeaturesById($id_product, $id_feature)
    {
        // List products features
        $features = Db::getInstance()->executeS('SELECT p.*, f.*
            FROM `' . _DB_PREFIX_ . 'feature_product` as p
            LEFT JOIN `' . _DB_PREFIX_ . 'feature_value` as f ON (f.`id_feature_value` = p.`id_feature_value`)
            WHERE `id_product` = ' . (int)$id_product . ' 
            AND (p.id_feature = ' . (int)$id_feature . ' OR f.id_feature = ' . (int)$id_feature . ')');

        foreach ($features as $tab) {
            // Delete product custom features
            if ($tab['custom']) {
                Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'feature_value`
				WHERE `id_feature_value` = ' . (int)$tab['id_feature_value']);

                Db::getInstance()->execute('
				DELETE FROM `' . _DB_PREFIX_ . 'feature_value_lang`
				WHERE `id_feature_value` = ' . (int)$tab['id_feature_value']);
            }
        }
        // Delete product features
        $result = Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'feature_product`
            WHERE `id_product` = ' . (int)$id_product . ' AND id_feature=' . (int)$id_feature);

        SpecificPriceRule::applyAllRules(array((int)$id_product));

        return ($result);
    }
}
