<?php
/**
 * 2012-2022 PrestaShop
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
 * @author    SeoSA <885588@bk.ru>
 * @copyright 2012-2019 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class ProductFinderMEP
{
    public $categories;
    public $low_on;
    public $search_only_default_category;
    public $search_only_explicit_category;
    public $search_query;
    public $type_search;
    public $manufacturers;
    public $suppliers;
    public $carrier;
    public $features;
    public $attributes;
    public $no_feature_value;
    public $how_many_show;
    public $active;
    public $disable;
    public $no_image;
    public $yes_image;
    public $no_discount;
    public $yes_discount;
    public $log_on;
    public $page;
    public $exclude_ids;
    public $product_name_type_search;
    public $qty_from;
    public $qty_to;
    public $date_from;
    public $date_to;
    public $custom_feature;
    public $price_from;
    public $price_to;
    public $type_visible;
    public $orderby;
    public $orderway;
    public $mode_or;
    public $mode_or_at;
    public $carrier_mode_or;
    public $carrier_pre;
    public $percent_discout;
    public $value_discout;
    public $search_product;
    public $new_on;
    public $date_period;

    const SEARCH_TYPE_NAME = 0;
    const SEARCH_TYPE_ID = 1;
    const SEARCH_TYPE_REFERENCE = 2;
    const SEARCH_TYPE_EAN13 = 3;
    const SEARCH_TYPE_UPC = 4;
    const SEARCH_TYPE_DESCRIPTION = 5;
    const SEARCH_TYPE_DESCRIPTION_SHORT = 6;
    protected static $search_type_fields = [
        self::SEARCH_TYPE_NAME => 'pl.`name`',
        self::SEARCH_TYPE_ID => 'p.`id_product`',
        self::SEARCH_TYPE_REFERENCE => 'p.`reference`',
        self::SEARCH_TYPE_EAN13 => 'p.`ean13`',
        self::SEARCH_TYPE_UPC => 'p.`upc`',
        self::SEARCH_TYPE_DESCRIPTION => 'pl.`description`',
        self::SEARCH_TYPE_DESCRIPTION_SHORT => 'pl.`description_short`',
    ];

    const PRODUCT_NAME_TYPE_SEARCH_OCCURRENCE = 'occurrence';
    const PRODUCT_NAME_TYPE_SEARCH_EXACT_MATCH = 'exact_match';

    protected $context;

    protected function __construct()
    {
        $this->initRequestParams();
        $this->context = Context::getContext();
    }

    protected static $instance = null;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function initRequestParams()
    {
        $categories = Tools::getValue('categories');
        $this->categories = (is_array($categories) ? $categories : []);
        $this->low_on = Tools::getValue('low_on');
        $this->search_only_explicit_category = Tools::getValue('search_only_explicit_category');
        $this->search_query = Tools::getValue('search_query');
        $this->type_search = (int) Tools::getValue('type_search', 0);
        $this->search_product = (int) Tools::getValue('search_product', 0);

        $manufacturers = Tools::getValue('manufacturers');
        $this->manufacturers = ($manufacturers ? array_map('intval', $manufacturers) : []);

        $suppliers = Tools::getValue('suppliers');
        $this->suppliers = ($suppliers ? array_map('intval', $suppliers) : []);

        $carriers = Tools::getValue('carriers');
        $this->carriers = ($carriers ? array_map('intval', $carriers) : []);
        $this->features = Tools::getValue('features');
        $this->attributes = Tools::getValue('attributes');
        $this->id_attribute = Tools::getValue('id_attribute');
        $this->id_feature = Tools::getValue('id_feature');
        $this->no_feature_value = Tools::getValue('no_feature_value');
        $this->how_many_show = (int) Tools::getValue('how_many_show', 20);
        $this->active = (int) Tools::getValue('active', 0);
        $this->disable = (int) Tools::getValue('disable', 0);
        $this->no_image = (int) Tools::getValue('no_image', 0);
        $this->yes_image = (int) Tools::getValue('yes_image', 0);
        $this->no_discount = (int) Tools::getValue('no_discount', 0);
        $this->yes_discount = (int) Tools::getValue('yes_discount', 0);
        $this->percent_discout = Tools::getValue('percent_discout', 0);
        $this->value_discout = Tools::getValue('value_discout', 0);
        $this->log_on = (int) Tools::getValue('log_on', 0);
        $this->page = (int) Tools::getValue('page', 1);
        $this->mode_or = (int) Tools::getValue('mode_or', 0);
        $this->carrier_mode_or = (int) Tools::getValue('carrier_mode_or', 0);
        $this->carrier_pre = (int) Tools::getValue('carrier_pre', 0);
        $this->mode_or_at = (int) Tools::getValue('mode_or_at', 0);
        $exclude_ids = Tools::getValue('exclude_ids', []);
        $exclude_ids = $exclude_ids ? array_map('intval', $exclude_ids) : [];
        $this->exclude_ids = $exclude_ids;
        $this->product_name_type_search = Tools::getValue('product_name_type_search');
        $this->qty_from = Tools::getValue('qty_from');
        $this->qty_to = Tools::getValue('qty_to');
        $this->date_from = Tools::getValue('date_from');
        $this->date_to = Tools::getValue('date_to');
        $this->custom_feature = Tools::getValue('custom_feature');
        $this->type_price = Tools::getValue('type_price');
        $this->price_from = Tools::getValue('price_from');
        $this->price_to = Tools::getValue('price_to');
        $this->orderby = Tools::getValue('orderby');
        $this->orderway = Tools::getValue('orderway');
        $this->type_visible = Tools::getValue('type_visible');
        $this->new_on = Tools::getValue('new_on');
        $this->date_period = Tools::getValue('date_period');
    }

    public function updateFinalPrice()
    {
        $result = Db::getInstance()->executeS('SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop`');
        $nothing = null;
        foreach ($result as $row) {
            $final_price = Product::getPriceStatic(
                $row['id_product'],
                true,
                null,
                (int) Configuration::get('PS_PRICE_DISPLAY_PRECISION'),
                null,
                false,
                true,
                1,
                true,
                null,
                null,
                null,
                $nothing
            );

            Db::getInstance()->update('product_shop', ['final_price' => $final_price], 'id_product = ' . $row['id_product']);
        }
    }

    public function findProducts()
    {
        if ($this->type_price == 1) {
            $this->updateFinalPrice();
        }
        $products = Db::getInstance()->executeS($this->buildSql());

        $country = new Country(Configuration::get('PS_COUNTRY_DEFAULT'));
        $address = new Address();
        $address->id_country = $country->id;

        foreach ($products as &$product) {
            $nothing = null;
            $advanced_stock_management = (bool) Db::getInstance()->getValue(
                'SELECT `advanced_stock_management`
					FROM ' . _DB_PREFIX_ . 'product_shop
					WHERE id_product=' . (int) $product['id_product'] . Shop::addSqlRestriction()
            );

            $log_date = Db::getInstance()->executeS(
                'SELECT `date_upd`, `message`, `id_employee`
                     FROM ' . _DB_PREFIX_ . 'log
                     WHERE object_id = ' . (int) $product['id_product'] . ' AND object_type = \'Product\''
            );
            $count_last = count($log_date) - 1;

            if ($count_last < 0) {
                $date_upd_last = '';
                $message_last = '';
                $lastname = '';
                $firstname = '';
            } else {
                $date_upd_last = $log_date[$count_last]['date_upd'];
                $message_last = $log_date[$count_last]['message'];
                $id_employee = $log_date[$count_last]['id_employee'];
                $name_employee = Db::getInstance()->getRow(
                    'SELECT `lastname`, `firstname`
                     FROM ' . _DB_PREFIX_ . 'employee
                     WHERE id_employee = ' . (int) $id_employee
                );
                $lastname = $name_employee['lastname'];
                $firstname = $name_employee['firstname'];
            }

            $product['price_final'] = Product::getPriceStatic(
                $product['id_product'],
                true,
                null,
                (int) Configuration::get('PS_PRICE_DISPLAY_PRECISION'),
                null,
                false,
                true,
                1,
                true,
                null,
                null,
                null,
                $nothing
            );

            $product['advanced_stock_management'] = ((bool) StockAvailable::dependsOnStock(
                (int) $product['id_product']
            ) && $advanced_stock_management);
            $product['image'] = ImageManager::thumbnail(
                _PS_PROD_IMG_DIR_ . Image::getImgFolderStatic($product['cover']) . $product['cover'] . '.jpg',
                'product_mini_' . $product['id_product'] . '_' . $product['cover'] . '.jpg',
                45
            );
            $id_image = Product::getCover($product['id_product']);
            $path = Image::getImgFolderStatic($id_image['id_image']);
            if (_PS_VERSION_ >= 1.7) {
                $full_patch = '../img/p/' . $path . $id_image['id_image'] . '-' .
                    ImageType::getFormattedName('small') . '.jpg';
            } else {
                $full_patch = '../img/p/' . $path . $id_image['id_image'] . '-small_default.jpg';
            }
            $product['image_small'] = $full_patch;

            if ($this->log_on) {
                $product['log_on'] = $this->log_on;
                $product['date_upd'] = $date_upd_last;
                $product['message'] = $message_last;
                $product['employee'] = $lastname . ' ' . $firstname;
            }

            $product['url_product'] = $this->context->link->getAdminLink(
                'AdminProducts',
                true,
                ['id_product' => $product['id_product']]
            ) . '&id_product=' . $product['id_product'] . '&updateproduct';
        }
        $tmp_products = [];
        foreach ($products as $prod) {
            $tmp_products[$prod['id_product']] = $prod;
        }
        return $tmp_products;
    }

    public function getTotal()
    {
        return (int) Db::getInstance()->getValue($this->buildSql(true));
    }

    public function buildSql($get_total = false)
    {
        $sql_category = false;
        if (is_array($this->categories) && count($this->categories)) {
            $ids_categories = [];
            foreach ($this->categories as $category) {
                $ids_categories[] = (int) $category['id'];
            }
            $sql_category = implode(',', $ids_categories);
        }

        $qty_query = [];

        if ($this->qty_from != '') {
            $qty_query[] = 'sa.`quantity` >= ' . (int) $this->qty_from;
        }

        if ($this->qty_to != '') {
            $qty_query[] = 'sa.`quantity` <= ' . (int) $this->qty_to;
        }

        $date_query = [];

        if ($this->date_from != '' || $this->new_on == 1) {
            if ($this->new_on == 1) {
                $current_date = date('Y-m-d H:i:s');
                $this->date_from = $current_date;
                $date_query[] = 'p.`date_add` <= "' . pSQL($this->date_from) . '"';
            } else {
                $date_query[] = 'p.`date_add` >= "' . pSQL($this->date_from) . '"';
            }
        }

        if ($this->date_to != '' || $this->new_on == 1) {
            if ($this->new_on == 1) {
                $this->date_to = date('Y-m-d H:i:s', strtotime($current_date . ' - ' . pSQL($this->date_period) . 'days'));
                $date_query[] = 'p.`date_add` >= "' . pSQL($this->date_to) . '"';
            } else {
                $date_query[] = 'p.`date_add` <= "' . pSQL($this->date_to) . '"';
            }
        }

        if ($this->custom_feature != '') {
            $date_query[] = 'fvl.`value` = "' . pSQL($this->custom_feature) . '"';
        }

        $price_query = [];

        if ($this->price_from != '') {
            if ($this->type_price == 1) {
                $price_query[] = 'pss.`final_price` >= ' . (float) $this->price_from;
            } else {
                $price_query[] = 'pss.`price` >= ' . (float) $this->price_from;
            }
        }

        if ($this->price_to != '') {
            if ($this->type_price == 1) {
                $price_query[] = 'pss.`final_price` <= ' . (float) $this->price_to;
            } else {
                $price_query[] = 'pss.`price` <= ' . (float) $this->price_to;
            }
        }

        $sql_manufactures = false;
        if (is_array($this->manufacturers) && count($this->manufacturers)) {
            $sql_manufactures = implode(',', $this->manufacturers);
        }

        $sql_suppliers = false;
        if (is_array($this->suppliers) && count($this->suppliers)) {
            $sql_suppliers = implode(',', $this->suppliers);
        }

        $sql_carriers = false;
        $sql_carriers_count = count($this->carriers);
        if (is_array($this->carriers) && count($this->carriers)) {
            $sql_carriers = implode(',', $this->carriers);
        }

        $sql_search_query = false;
        if ($this->search_query) {
            $hash = [];
            switch ($this->type_search) {
                case self::SEARCH_TYPE_ID:
                    $ids = explode(' ', $this->search_query);
                    $ids = array_map('intval', $ids);
                    $sql_search_query = '(' . implode(',', $ids) . ')';
                    $sql_type_search = 'p.`id_product` IN';
                    $hash[] = 'type_search-1';
                    break;
                case self::SEARCH_TYPE_NAME:
                case self::SEARCH_TYPE_REFERENCE:
                    if ($this->type_search == 2 && stristr($this->search_query, '^^') != false) {
                        $ids = explode('^^', $this->search_query);
                        $ids = array_map('strval', $ids);
                        foreach ($ids as $key => $elem) {
                            $ids[$key] = "'" . $elem . "'";
                        }
                        $sql_search_query = '(' . implode(',', $ids) . ')';
                        $sql_type_search = 'p.`reference` IN';
                        $hash[] = 'type_search-1';
                        break;
                    }
                    // no break
                case self::SEARCH_TYPE_EAN13:
                case self::SEARCH_TYPE_UPC:
                case self::SEARCH_TYPE_DESCRIPTION:
                case self::SEARCH_TYPE_DESCRIPTION_SHORT:
                    if ($this->product_name_type_search == self::PRODUCT_NAME_TYPE_SEARCH_EXACT_MATCH) {
                        $sql_search_query = '"' . pSQL($this->search_query) . '"';
                    } elseif ($this->product_name_type_search == self::PRODUCT_NAME_TYPE_SEARCH_OCCURRENCE) {
                        if (stripos($this->search_query, '^&&^')) {
                            $pos = stripos($this->search_query, '^&&^');
                            $name = Tools::substr($this->search_query, 0, $pos);
                            $sql_search_query = pSQL($name);
                            $no_like_pos = $pos + 4;
                            $no_like = Tools::substr($this->search_query, $no_like_pos);
                            $sql_search_query = '"%' . pSQL($sql_search_query) . '%"';
                            $sql_search_query .= ' AND ' . self::$search_type_fields[$this->type_search] . ' NOT LIKE "%' . pSQL($no_like) . '%"';
                        } else {
                            $sql_search_query = '"%' . pSQL($this->search_query) . '%"';
                        }
                    }
                    $sql_type_search = self::$search_type_fields[$this->type_search] . ' LIKE ';
                    break;
                default:
                    throw new LogicException('Unknown search type');
            }
            $hash[] = 'search_query-' . urlencode($this->search_query);
        }

        $id_shop = MassEditTools::getIdShopSql();
        $order_by = $this->orderby && $this->orderway ? ' ORDER BY ' . $this->orderby . ' ' . $this->orderway : '';
        if ($order_by == '') {
            $order_by = ' ORDER BY id_product ASC';
        }

        $sql_carriers_no = 1;
        if ($sql_carriers == '-1') {
            $sql_carriers_no = 0;
        }
        if ($this->search_product == 1 && $this->type_search == 3) {
            if ($this->product_name_type_search == 'exact_match') {
                $sql_search_query = '(' . $sql_search_query . ')';
                $sql_type_search = 'pa.`ean13` IN';
            } else {
                $sql_search_query = 'LIKE ' . $sql_search_query;
                $sql_type_search = 'pa.`ean13`';
            }
        }
        if ($this->search_product == 1 && $this->type_search == 2) {
            if ($this->product_name_type_search == 'exact_match') {
                $sql_search_query = '(' . $sql_search_query . ')';
                $sql_type_search = 'pa.`reference` IN';
            } else {
                $sql_search_query = 'LIKE ' . $sql_search_query;
                $sql_type_search = 'pa.`reference`';
            }
        }
        if ($this->search_product == 1 && $this->type_search == 4) {
            if ($this->product_name_type_search == 'exact_match') {
                $sql_search_query = '(' . $sql_search_query . ')';
                $sql_type_search = 'pa.`upc` IN';
            } else {
                $sql_search_query = 'LIKE ' . $sql_search_query;
                $sql_type_search = 'pa.`upc`';
            }
        }
        $select = 'p.`id_product`,
                p.reference,
                pss.`active`,
                pss.`price`,
                pl.`name`, pl.`link_rewrite`,
                sa.`quantity`,
                cl.`name` as category,
                m.`name` as manufacturer,
                s.`name` as supplier,' . (($sql_carriers !== false) ?
               'c.`name` as carrier,' : '') .
            '(SELECT i.`id_image` FROM ' . _DB_PREFIX_ . 'image i
			 WHERE i.`id_product` = p.`id_product`
			  ORDER BY i.`cover` DESC LIMIT 0,1) cover';

        if ($get_total) {
            $select = 'COUNT(p.`id_product`)';
        }

        $sql = 'SELECT
                ' . $select . '
            FROM ' . _DB_PREFIX_ . 'product p
            JOIN `' . _DB_PREFIX_ . 'product_shop` pss ON (p.`id_product` = pss.`id_product` 
            AND pss.id_shop = ' . pSQL($id_shop) . ')
            LEFT JOIN ' . _DB_PREFIX_ . 'tax_rules_group trg ON trg.`id_tax_rules_group` = p.`id_tax_rules_group`
            LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer m ON m.`id_manufacturer` = p.`id_manufacturer`
            LEFT JOIN ' . _DB_PREFIX_ . 'supplier s ON s.`id_supplier` = p.`id_supplier`';
        if ($sql_carriers !== false) {
            $sql .= 'LEFT JOIN ' . _DB_PREFIX_ . 'product_carrier pc ON pc.`id_product` = p.`id_product`
            LEFT JOIN ' . _DB_PREFIX_ . 'carrier c on c.`id_reference` = pc.`id_carrier_reference`';
        }
        if ($this->no_image == 1 || $this->yes_image == 1) {
            $sql .= 'LEFT JOIN ' . _DB_PREFIX_ . 'image pi ON pi.`id_product` = p.`id_product`';
        }
        if ($this->no_discount == 1 || $this->yes_discount == 1) {
            if (strpos($this->percent_discout, '-') || strpos($this->value_discout, '-')) {
                $sql .= ' LEFT JOIN ' . _DB_PREFIX_ . 'specific_price sp ON sp.`id_product` = p.`id_product`';
                if ($this->percent_discout != '' && strpos($this->percent_discout, '-')) {
                        $range = explode('-', $this->percent_discout);
                        $sql .= ' AND ((sp.`reduction` >= ' . $range[0] / 100 . ' AND sp.`reduction` <= ' . $range[1] / 100 . ') AND sp.`reduction_type` = "percentage")';
                } elseif ($this->value_discout != '' && strpos($this->value_discout, '-')) {
                    $range_v = explode('-', $this->value_discout);
                    $sql .= ' AND ((sp.`reduction` >= ' . $range_v[0] . ' AND  sp.`reduction` <= ' . $range_v[1] . ')  AND sp.`reduction_type` = "amount")';
                }
            } else {
                if ($this->percent_discout > 0 || $this->value_discout > 0) {
                    $sql .= ' LEFT JOIN ' . _DB_PREFIX_ . 'specific_price sp ON sp.`id_product` = p.`id_product`';
                    if ($this->percent_discout > 0 && $this->value_discout > 0) {
                        $sql .= ' AND (sp.`reduction`=' . $this->percent_discout / 100 . ' 
                        AND sp.reduction_type = "percentage") 
                                     OR  (sp.`reduction` = ' . $this->value_discout . ' 
                                     AND sp.reduction_type = "amount")  ';
                    } elseif ($this->percent_discout > 0 && $this->value_discout == 0) {
                        $sql .= ' AND sp.`reduction`=' . $this->percent_discout / 100 . ' 
                        AND sp.reduction_type = "percentage"';
                    } elseif ($this->value_discout > 0 && $this->percent_discout == 0) {
                        $sql .= ' AND  sp.`reduction` = ' . $this->value_discout . ' 
                        AND sp.reduction_type = "amount"';
                    } else {
                        $sql .= 'LEFT JOIN ' . _DB_PREFIX_ . 'specific_price sp ON sp.`id_product` != p.`id_product`';
                    }
                } else {
                    $sql .= 'LEFT JOIN ' . _DB_PREFIX_ . 'specific_price sp ON sp.`id_product` = p.`id_product` ';
                }
            }
        }

        if ($this->custom_feature != '') {
            $sql .= 'LEFT JOIN `' . _DB_PREFIX_ . 'feature_product` fp ON fp.`id_product` = p.`id_product` ';
            $sql .= 'LEFT JOIN `' . _DB_PREFIX_ . 'feature_value_lang` fvl 
            ON fp.`id_feature_value` = fvl.`id_feature_value` ';
        }

        if (!empty($this->features) && $this->mode_or == 0) {
            $count_features = count(array_unique($this->features));
        }
        if (!empty($this->attributes) && $this->mode_or_at == 0) {
            $count_attributes = count(array_unique($this->attributes));
        }
        $sql .= 'LEFT JOIN ' . _DB_PREFIX_ . 'tax t ON t.`id_tax` = p.`id_tax_rules_group`
            LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl ON p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $this->context->language->id . ' AND pl.`id_shop` = ' . pSQL($id_shop) . '
            LEFT JOIN ' . _DB_PREFIX_ . 'category_lang cl ON cl.`id_category` = pss.`id_category_default`
             AND cl.`id_lang` = ' . (int) $this->context->language->id . ' AND cl.`id_shop` = ' . pSQL($id_shop) . '
            LEFT JOIN ' . _DB_PREFIX_ . 'stock_available sa ON (sa.`id_product` = p.`id_product`
             AND sa.`id_product_attribute` = 0    
           
            ' . StockAvailable::addSqlShopRestriction(null, null, 'sa') . ') 
            
            ' . ($this->search_product == 1 && in_array($this->type_search, [3, 4, 2]) ? '
            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`)' : '') . '
            WHERE 1
             ' . ($this->no_image ? 'AND pi.cover = 1' : '') . '
             ' . ($this->yes_image ? 'AND cover is null and pi.id_product is null' : '') . '             
             ' . ($this->yes_discount && !$this->no_discount ? 'AND sp.id_product = p.id_product' : '') . '
             ' . ($this->no_discount && !$this->yes_discount ? 'AND sp.id_product is null' : '') . '           
             ' . ($this->type_visible != -1 ? 'AND p.`visibility` = "' . $this->type_visible . '"' : '') . '                         
             ' . ($sql_search_query ? 'AND ' . $sql_type_search . ' ' . $sql_search_query . ' ' : '') . '
             ' . ($sql_category ? ('AND ' . ($this->search_only_default_category ? 'p.`id_category_default` IN(' . pSQL($sql_category) . ')'
                    : ($this->search_only_explicit_category ? '0 = (SELECT COUNT(cp.`id_category`)
                    FROM ' . _DB_PREFIX_ . 'category_product cp
                     WHERE cp.`id_product` = p.`id_product` AND cp.`id_category` NOT IN(' . pSQL($sql_category) . ')) 
                     AND ' . count($ids_categories).' = ' : '') . '(SELECT COUNT(cp.`id_category`)
                    FROM ' . _DB_PREFIX_ . 'category_product cp
                     WHERE cp.`id_product` = p.`id_product` AND cp.`id_category` IN(' . pSQL($sql_category) . '))'
                )) : '') . '
            ' . ($sql_manufactures !== false ? 'AND p.`id_manufacturer` IN(' . pSQL($sql_manufactures) . ')' : '') . '
            ' . ($sql_suppliers !== false ? 'AND p.`id_supplier` IN(' . pSQL($sql_suppliers) . ')' : '') . '
            ' . ($sql_carriers !== false && $sql_carriers_no == 1 && $this->carrier_mode_or == 1 ? 'AND c.`id_carrier` IN(' . pSQL($sql_carriers) . ')' : '') . '
            ' . ($sql_carriers !== false && $sql_carriers_no == 1 && $this->carrier_mode_or == 0 ? 'AND c.`id_carrier` IN(' . pSQL($sql_carriers) . ') 
                group by p.`id_product` having count(distinct c.`id_carrier`) = ' . $sql_carriers_count : '') . '
            ' . ($sql_carriers !== false && $sql_carriers_no == 0 ? 'AND c.`name` IS NULL ' : '') . '
            ' . ($sql_carriers !== false && $this->carrier_pre == 0 ? 'AND (select count(*) from ' . _DB_PREFIX_ . 'product_carrier cv where cv.`id_product` = p.`id_product`) = '. $sql_carriers_count : '') . '
            ' . ($this->active && !$this->disable ? ' AND pss.`active` = 1 ' : '') . '
            ' . ($this->disable && !$this->active ? ' AND pss.`active` = 0 ' : '') . '
            ' . (is_array($this->features) && count($this->features) && $this->mode_or == 1 ?
                'AND (SELECT fp.`id_feature` FROM ' . _DB_PREFIX_ . 'feature_product fp WHERE fp.`id_product` = p.`id_product`
                AND fp.`id_feature_value` IN (' . implode(',', array_map('intval', $this->features)) . ') LIMIT 1) ' : '') . ' ' . (is_array($this->features) && count($this->features) && $this->mode_or == 0 ?
                ' AND (SELECT fp.`id_feature`
                FROM ' . _DB_PREFIX_ . 'feature_product fp WHERE fp.`id_product` = p.`id_product`
                AND fp.`id_feature_value` IN (' . implode(',', array_map('intval', $this->features)) . ')
                GROUP BY fp.`id_product` having count(distinct fp.`id_feature_value`) = ' . $count_features . ' ) ' : '') . '                
                  ' . (is_array($this->attributes) && count($this->attributes) && $this->mode_or_at == 1 ?
                ' AND (SELECT DISTINCT pa.`id_product` FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac 
                ON pac.`id_product_attribute` = pa.`id_product_attribute`
                WHERE pa.`id_product` = p.`id_product` AND        
                pac.`id_attribute` IN (' . implode(',', array_map('intval', $this->attributes)) . ')                 
                LIMIT 1) ' : '') . '                
                  ' . (is_array($this->attributes) && count($this->attributes) && $this->mode_or_at == 0 ?
                'AND (SELECT DISTINCT pa.`id_product` FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac 
                ON pac.`id_product_attribute` = pa.`id_product_attribute`
                WHERE pa.`id_product` = p.`id_product` AND        
                pac.`id_attribute` IN (' . implode(',', array_map('intval', $this->attributes)) . ') 
                GROUP BY pa.`id_product` having count(distinct pac.`id_attribute`) = ' . $count_attributes . ' ) ' : '') . '               
                ' . ($this->low_on == 0 ? 'AND p.`low_stock_threshold` >= sa.`quantity`': '') . '
                ' . (is_array($this->no_feature_value) && count($this->no_feature_value) ?
                'AND NOT (SELECT COUNT(p2.`id_product`)
                FROM ' . _DB_PREFIX_ . 'product p2 LEFT JOIN ' . _DB_PREFIX_ . 'feature_product fp2 
                ON p2.`id_product`=fp2.`id_product`
                WHERE fp2.`id_product` = p.`id_product` AND fp2.id_feature
                IN(' . implode(',', array_map('intval', $this->no_feature_value)) . ')) ' : '') . '
            ' . (count($qty_query) ? ' AND ' . implode(' AND ', $qty_query) : '') . '
            ' . (count($date_query) ? ' AND ' . implode(' AND ', $date_query) : '') . '
            ' . (count($price_query) ? ' AND ' . implode(' AND ', $price_query) : '') . '
            ' . (is_array($this->exclude_ids) && count($this->exclude_ids) ? ' AND pss.`id_product` NOT IN(' . pSQL(implode(',', $this->exclude_ids)) . ')' : '') . '
            ' . (!$get_total ? $order_by . ' LIMIT ' . (((int) $this->page - 1) * (int) $this->how_many_show) . ',' . (int) $this->how_many_show : ' ');

        return $sql;
    }

    public function getHash()
    {
        $hash = [];
        if (is_array($this->categories) && count($this->categories)) {
            $ids_categories = [];
            foreach ($this->categories as $category) {
                $ids_categories[] = (int) $category['id'];
            }
            $hash[] = 'categories-' . implode('_', $ids_categories);
        }

        if ($this->qty_from != '') {
            $hash[] = 'qty_from-' . (int) $this->qty_from;
        }

        if ($this->qty_to != '') {
            $hash[] = 'qty_to-' . (int) $this->qty_to;
        }

        if ($this->price_from != '') {
            $hash[] = 'price_from-' . str_replace('.', '_', (string) $this->price_from);
        }

        if ($this->price_to != '') {
            $hash[] = 'price_to-' . str_replace('.', '_', (string) $this->price_to);
        }

        if (is_array($this->manufacturers) && count($this->manufacturers)) {
            $hash[] = 'manufacturers-' . implode('_', $this->manufacturers);
        }

        if (is_array($this->suppliers) && count($this->suppliers)) {
            $hash[] = 'suppliers-' . implode('_', $this->suppliers);
        }

        if (is_array($this->carriers) && count($this->carriers)) {
            $hash[] = 'carriers-' . implode('_', $this->carriers);
        }

        if ($this->search_query) {
            switch ($this->type_search) {
                case self::SEARCH_TYPE_ID:
                    $hash[] = 'type_search-1';
                    break;
                case self::SEARCH_TYPE_NAME:
                    $hash[] = 'type_search-0';
                    break;
                case self::SEARCH_TYPE_REFERENCE:
                    $hash[] = 'type_search-2';
                    break;
                case self::SEARCH_TYPE_EAN13:
                case self::SEARCH_TYPE_UPC:
                case self::SEARCH_TYPE_DESCRIPTION:
                case self::SEARCH_TYPE_DESCRIPTION_SHORT:
                    $hash[] = 'type_search-' . $this->type_search;
                    break;
                default:
                    throw new LogicException('Unknown search type');
            }
            $hash[] = 'search_query-' . urlencode($this->search_query);
        }
        if ($this->product_name_type_search == '') {
            $this->product_name_type_search = 'exact_match';
        }

        $hash[] = 'product_name_type_search-' . $this->product_name_type_search;

        if ($this->active) {
            $hash[] = 'active-1';
        }
        if ($this->disable) {
            $hash[] = 'disable-1';
        }
        if ($this->no_image) {
            $hash[] = 'no_image-1';
        }

        if ($this->yes_image) {
            $hash[] = 'yes_image-1';
        }
        if ($this->no_discount) {
            $hash[] = 'no_discount-1';
        }

        if ($this->yes_discount) {
            $hash[] = 'yes_discount-1';
        }

        if ($this->page > 1) {
            $hash[] = 'page-' . $this->page;
        }

        if ($this->how_many_show > 20) {
            $hash[] = 'how_many_show-' . $this->how_many_show;
        }
        return $hash;
    }

    public function getRequestParam($name, $default = null)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        return $default;
    }
}
