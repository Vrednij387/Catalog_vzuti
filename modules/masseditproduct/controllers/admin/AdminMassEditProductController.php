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
require_once dirname(__FILE__) . '/../../classes/tools/config.php';
class AdminMassEditProductController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'configuration';
        $this->identifier = 'id_configuration';
        $this->className = 'Configuration';
        $this->bootstrap = true;
        $this->display = 'edit';
        parent::__construct();
        SmartyMEP::registerSmartyFunctions();
    }

    public function setMedia($isNewTheme = false)
    {
        $ps_ver = str_replace('.', '', _PS_VERSION_);
        $ps_ver = Tools::substr($ps_ver, 0, 3);
        if ($ps_ver >= 174) {
            parent::setMedia();
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/new-theme.css');
            if ($ps_ver >= 178) {
                $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/new-theme-for_178.css');
            }
            ToolsModuleMEP::autoloadCSS($this->module->getPathUri() . 'views/css/autoload/');
            $this->context->controller->addJqueryUI('ui.widget');
            $this->context->controller->addJqueryUI('ui.datepicker');
            $this->context->controller->addJqueryUI('ui.mouse');
            $this->context->controller->addJqueryUI('ui.slider');
            $this->context->controller->addJqueryPlugin('tagify');
            $this->context->controller->addJqueryPlugin('fancybox');
            $this->context->controller->addJqueryPlugin('autosize');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/jquery-ui-timepicker-addon.css');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/autoload/admin-theme.css');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/autoload/font-awesome.css');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/autoload/selector_container.css');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/autoload/message_viewer.css');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/autoload/modulePreloader.css');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/autoload/redactor.css');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/autoload/search_products.css');
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/autoload/admin.css');

            $this->context->controller->addJS([
                $this->module->getPathUri() . 'views/js/jquery.insertAtCaret.js',
                $this->module->getPathUri() . 'views/js/redactor/redactor.js',
                $this->module->getPathUri() . 'views/js/redactor/plugins/table.js',
                $this->module->getPathUri() . 'views/js/redactor/plugins/video.js',
                $this->module->getPathUri() . 'views/js/tree_custom.js',
                $this->module->getPathUri() . 'views/js/jquery.finderSelect.js',
                $this->module->getPathUri() . 'views/js/search_product.js',
                $this->module->getPathUri() . 'views/js/selector_container.js',
                $this->module->getPathUri() . 'views/js/vendor/select2.min.js',
                $this->module->getPathUri() . '/views/js/vendor/i18n/ru.js',
                $this->module->getPathUri() . 'views/js/langField.jquery.js',
                $this->module->getPathUri() . 'views/js/tabsMEP.js',
                $this->module->getPathUri() . 'views/js/Translator.js',
                $this->module->getPathUri() . 'views/js/modulePreloader.js',
                $this->module->getPathUri() . 'views/js/jquery.fn.js',
                $this->module->getPathUri() . 'views/js/tabContainer.js',
                $this->module->getPathUri() . 'views/js/popupForm.js',
                $this->module->getPathUri() . 'views/js/jquery.liTranslit.js',
                $this->module->getPathUri() . 'views/js/jquery-confirm.js',
                $this->module->getPathUri() . 'views/js/admin-on.js',
                $this->module->getPathUri() . 'views/js/bootstrap-dropdown.js',
                'https://seosaps.com/ru/module/seosamanager/manager?ajax=1&action=script&iso_code=' . Context::getContext()->language->iso_code,
            ]);
        } elseif (_PS_VERSION_ >= 1.6) {
            parent::setMedia();

            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/new-theme-for_16.css');
            ToolsModuleMEP::autoloadCSS($this->module->getPathUri() . 'views/css/autoload/');
            $this->context->controller->addJqueryUI('ui.widget');
            $this->context->controller->addJqueryPlugin('tagify');

            if (_PS_VERSION_ < 1.6) {
                $this->context->controller->addJqueryUI('ui.slider');
                $this->context->controller->addJqueryUI('ui.datepicker');
                $this->context->controller->addCSS($this->module->getPathUri() .
                    'views/css/jquery-ui-timepicker-addon.css');
                $this->context->controller->addJS($this->module->getPathUri() .
                    'views/js/jquery-ui-timepicker-addon.js');
            } else {
                $this->context->controller->addJqueryPlugin('timepicker');
            }

            $this->context->controller->addJS([
                $this->module->getPathUri() . 'views/js/jquery.insertAtCaret.js',
                $this->module->getPathUri() . 'views/js/redactor/redactor.js',
                $this->module->getPathUri() . 'views/js/redactor/plugins/table.js',
                $this->module->getPathUri() . 'views/js/redactor/plugins/video.js',
                $this->module->getPathUri() . 'views/js/tree_custom.js',
                $this->module->getPathUri() . 'views/js/jquery.finderSelect.js',
                $this->module->getPathUri() . 'views/js/search_product.js',
                $this->module->getPathUri() . 'views/js/selector_container.js',
                $this->module->getPathUri() . 'views/js/vendor/select2.min.js',
                $this->module->getPathUri() . 'views/js/langField.jquery.js',
                $this->module->getPathUri() . 'views/js/tabsMEP.js',
                $this->module->getPathUri() . 'views/js/Translator.js',
                $this->module->getPathUri() . 'views/js/modulePreloader.js',
                $this->module->getPathUri() . 'views/js/jquery.fn.js',
                $this->module->getPathUri() . 'views/js/tabContainer.js',
                $this->module->getPathUri() . 'views/js/popupForm.js',
                $this->module->getPathUri() . 'views/js/jquery.liTranslit.js',
                $this->module->getPathUri() . 'views/js/jquery-confirm.js',
                $this->module->getPathUri() . 'views/js/admin.js',
                $this->module->getPathUri() . 'views/js/bootstrap-dropdown.js',
                'https://seosaps.com/ru/module/seosamanager/manager?ajax=1&action=script&iso_code='
                . Context::getContext()->language->iso_code,
            ]);
        } else {
            parent::setMedia();
            $this->context->controller->addCSS($this->module->getPathUri() . 'views/css/new-theme-for_16.css');
            ToolsModuleMEP::autoloadCSS($this->module->getPathUri() . 'views/css/autoload/');
            $this->context->controller->addJqueryUI('ui.widget');
            $this->context->controller->addJqueryPlugin('tagify');

            if (_PS_VERSION_ < 1.6) {
                $this->context->controller->addJqueryUI('ui.slider');
                $this->context->controller->addJqueryUI('ui.datepicker');
                $this->context->controller->addCSS($this->module->getPathUri() .
                    'views/css/jquery-ui-timepicker-addon.css');
                $this->context->controller->addJS($this->module->getPathUri() .
                    'views/js/jquery-ui-timepicker-addon.js');
            } else {
                $this->context->controller->addJqueryPlugin('timepicker');
            }

            $this->context->controller->addJS([
                $this->module->getPathUri() . 'views/js/jquery.insertAtCaret.js',
                $this->module->getPathUri() . 'views/js/redactor/redactor.js',
                $this->module->getPathUri() . 'views/js/redactor/plugins/table.js',
                $this->module->getPathUri() . 'views/js/redactor/plugins/video.js',
                $this->module->getPathUri() . 'views/js/tree_custom.js',
                $this->module->getPathUri() . 'views/js/jquery.finderSelect.js',
                $this->module->getPathUri() . 'views/js/search_product.js',
                $this->module->getPathUri() . 'views/js/selector_container.js',
                $this->module->getPathUri() . 'views/js/vendor/select2.min.js',
                $this->module->getPathUri() . 'views/js/langField.jquery.js',
                $this->module->getPathUri() . 'views/js/tabsMEP.js',
                $this->module->getPathUri() . 'views/js/Translator.js',
                $this->module->getPathUri() . 'views/js/modulePreloader.js',
                $this->module->getPathUri() . 'views/js/jquery.fn.js',
                $this->module->getPathUri() . 'views/js/tabContainer.js',
                $this->module->getPathUri() . 'views/js/popupForm.js',
                $this->module->getPathUri() . 'views/js/jquery.liTranslit.js',
                $this->module->getPathUri() . 'views/js/jquery-confirm.js',
                $this->module->getPathUri() . 'views/js/admin.js',
                $this->module->getPathUri() . 'views/js/bootstrap-dropdown.js',
                'https://seosaps.com/ru/module/seosamanager/manager?ajax=1&action=script&iso_code='
                . Context::getContext()->language->iso_code,
            ]);
        }
    }

    public function renderForm()
    {
        $features = MassEditTools::getFeatures($this->context->language->id, true, 1, true);
        $input_product_name_type_search = [
            'name' => 'product_name_type_search',
            'values' => [
                [
                    'id' => 'exact_match',
                    'text' => $this->l('Exact match'),
                ],
                [
                    'id' => 'occurrence',
                    'text' => $this->l('Search for occurrence'),
                ],
            ],
            'default_id' => 'exact_match',
        ];

        $attribute_groups = AttributeGroup::getAttributesGroups($this->context->language->id);
        if (is_array($attribute_groups) && count($attribute_groups)) {
            foreach ($attribute_groups as &$attribute_group) {
                $attribute_group['attributes'] = AttributeGroup::getAttributes(
                    $this->context->language->id,
                    (int) $attribute_group['id_attribute_group']
                );
            }
        }

        $tpl_vars = [
            'categories' => Category::getCategories($this->context->language->id, false),
            'manufacturers' => Manufacturer::getManufacturers(
                false,
                0,
                true,
                false,
                false,
                false,
                true
            ),
            'suppliers' => Supplier::getSuppliers(
                false,
                0,
                false,
                false,
                false,
                false
            ),
            'carriers' => Carrier::getCarriers(
                false,
                0,
                false,
                false,
                false,
                false
            ),
            'features' => $features,
            'languages' => ToolsModuleMEP::getLanguages(false),
            'default_form_language' => $this->context->language->id,
            'input_product_name_type_search' => $input_product_name_type_search,
            'upload_file_dir' => _MODULE_DIR_ . $this->module->name . '/lib/redactor/file_upload.php',
            'upload_image_dir' => _MODULE_DIR_ . $this->module->name . '/lib/redactor/image_upload.php',
            'link_on_tab_module' => HelperModuleMEP::getModuleTabAdminLink(),
            'templates_products' => Db::getInstance()->executeS(TemplateProductsMEP::getAllQuery()->build()),
            'tabs' => $this->getTabs(),
            'attribures_groups' => $attribute_groups,
            'config_new_date' => (int) Configuration::get('PS_NB_DAYS_NEW_PRODUCT'),
        ];

        $this->tpl_form_vars = array_merge($this->tpl_form_vars, $tpl_vars);
        $this->fields_form = [
            'legend' => [
                'title' => 'tree_custom.tpl',
            ],
        ];

        $this->context->controller->addCSS($this->module->getPathURI() . 'views/css/jquery-confirm.css');

        if (version_compare(_PS_VERSION_, '1.6.0', '<')) {
            $this->context->controller->addCSS($this->module->getPathURI() . 'views/css/admin-theme.css');
        }

        if (_PS_VERSION_ > 1.6) {
            $this->context->controller->addCSS($this->module->getPathURI() . 'views/css/admin-theme1_7.css');
        }

        return $this->minify_html(parent::renderForm());
//        return parent::renderForm();
    }

    public function minify_html($html)
    {
        $search = [
            "/(\n|^)(\x20+|\t)/",
            "/(\n|^)\/\/(.*?)(\n|$)/",
            "/\n/",
            "/\<\!--.*?-->/",
            "/(\x20+|\t)/",
//            '/\>\s+\</',
            "/(\"|\')\s+\>/",
            "/=\s+(\"|\')/",
        ];

        $replace = [
            "\n",
            "\n",
            ' ',
            '',
            ' ',
//            "><",
            '$1>',
            '=$1',
        ];

        $html = preg_replace($search, $replace, $html);
        return $html;
    }

    public function getTabs($parentId = 0, $level = 0)
    {
        $files = glob(_PS_MODULE_DIR_ . $this->module->name . '/classes/tabs/*TabMEP.php');
        $tabs = [];
        if (is_array($files) && count($files)) {
            foreach ($files as $file) {
                $class = str_replace('.php', '', basename($file));
                $tab = new $class();
                $tabs[] = $tab;
            }
        }
        usort($tabs, [$this, 'sortTabsByPosition']);

        return $tabs;
    }

    /**
     * @param BaseTabMEP $a
     * @param BaseTabMEP $b
     * @return int
     */
    public function sortTabsByPosition($a, $b)
    {
        if ($a->getPosition() == $b->getPosition()) {
            return 0;
        }
        return $a->getPosition() > $b->getPosition() ? 1 : -1;
    }

    public function postProcess()
    {
        parent::postProcess();

        if (Tools::getValue('action') == 'getMaxPositionForImageCaption') {
            $ids = Tools::getValue('products');
            $ids = is_array($ids) ? array_map('intval', $ids) : [];

            $count = count($ids) ? (int) Db::getInstance()->getValue(
                'SELECT MAX(position) FROM `' . _DB_PREFIX_ . 'image`
                 WHERE `id_product` IN(' . pSQL(implode(',', $ids)) . ')'
            ) : 0;

            $string = $this->l('Position');

            $option = '';
            for ($i = 1; $i <= $count; $i++) {
                $option .= '<option value="' . $i . '">' . $string . ' ' . $i . '</option>';
            }

            exit(json_encode(['option' => $option]));
        }
    }

    public function ajaxProcessSearchProducts()
    {
        $products = ProductFinderMEP::getInstance()->findProducts();
        $nb_products = ProductFinderMEP::getInstance()->getTotal();
        $hash = ProductFinderMEP::getInstance()->getHash();
        $pages_nb = ceil($nb_products / ProductFinderMEP::getInstance()->getRequestParam('how_many_show'));
        $page = ProductFinderMEP::getInstance()->getRequestParam('page');
        $range = 5;
        $start = ($page - $range);
        if ($start < 1) {
            $start = 1;
        }
        $stop = ($page + $range);
        if ($stop > $pages_nb) {
            $stop = (int) $pages_nb;
        }

        $currency = Currency::getCurrency(Configuration::get('PS_CURRENCY_DEFAULT'));
        $currency = $currency['id_currency'];
        $attribures_groups = MassEditTools::getAttributeGroups();

        exit(
            json_encode(
                [
                    'products' => ToolsModuleMEP::fetchTemplate(
                        'admin/mass_edit_product/helpers/form/products.tpl',
                        [
                            'currency' => $currency,
                            'products' => $products,
                            'link' => $this->context->link,
                            'nb_products' => $nb_products,
                            'products_per_page' => $pages_nb,
                            'pages_nb' => $pages_nb,
                            'p' => $page,
                            'n' => $pages_nb,
                            'range' => $range,
                            'start' => $start,
                            'stop' => $stop,
                            'attribures_groups' => $attribures_groups,
                        ]
                    ),
                    'hash' => implode('&', $hash),
                    'count_result' => $nb_products,
                ]
            )
        );
    }

    public function ajaxProcessSetAllProduct()
    {
        $tab = Tools::getValue('tab_name');
        $class_name = ToolsModuleMEP::toCamelCase($tab, true) . 'TabMEP';
        if (class_exists($class_name)) {
            /**
             * @var BaseTabMEP $object
             */
            $object = new $class_name();
            $products_id = Tools::getValue('products');
            if (!empty($products_id)) {
                foreach ($products_id as $product) {
                    PrestaShopLogger::addLog(
                        sprintf('%s modification', 'Module masseditproduct '),
                        1,
                        null,
                        'Product',
                        (int) $product['id'],
                        true,
                        (int) $this->context->employee->id
                    );
                }
            }
            exit(json_encode([
                'hasError' => false,
                'result' => $object->apply(),
            ]));
        } else {
            LoggerMEP::getInstance()->error(sprintf($this->l('Class Tab %s not exists'), $class_name));
            return [];
        }
    }

    public function ajaxProcessApi()
    {
        HelperModuleMEP::createAjaxApiCall($this);
    }

    public function ajaxProcessCopyFieldDescription()
    {
        $id_product = Tools::getValue('id_product');
        $id_lang = Tools::getValue('id_lang');
        $iso_code = Language::getIsoById($id_lang);
        if (!$iso_code) {
            $id_lang = $this->context->language->id;
        }

        $product = new Product($id_product, false, $id_lang);
        $description = false;
        if (Validate::isLoadedObject($product)) {
            $description = $product->description;
        }
        exit(json_encode([
                    'response' => $description,
                ]));
    }

    public function ajaxProcessCopyFieldDescriptionShort()
    {
        $id_product = Tools::getValue('id_product');
        $id_lang = Tools::getValue('id_lang');
        $iso_code = Language::getIsoById($id_lang);
        if (!$iso_code) {
            $id_lang = $this->context->language->id;
        }

        $product = new Product($id_product, false, $id_lang);
        $description = false;
        if (Validate::isLoadedObject($product)) {
            $description = $product->description_short;
        }
        exit(json_encode(['response' => $description]));
    }

    public function ajaxProcessRowCopySearchProduct()
    {
        $query = Tools::getValue('query');
        $rows = Db::getInstance()->executeS(
            'SELECT p.`id_product`, CONCAT(p.`id_product`, " - ", pl.`name`) as name
        FROM ' . _DB_PREFIX_ . 'product p
		LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl ON p.`id_product` = pl.`id_product`
		 AND pl.`id_lang` = ' . (int) $this->context->language->id . '
		WHERE ' .
            MassEditTools::buildSQLSearchWhereFromQuery(
                $query,
                false,
                'CONCAT(p.`id_product`, " - ", pl.`name`)'
            )
        );
        exit(json_encode(is_array($rows) && count($rows) ? $rows : []));
    }

    public function ajaxProcessDownloadAttachment()
    {
        if ($this->tabAccess['edit'] === '0') {
            return exit(json_encode(['error' => $this->l('You do not have the right permission')]));
        }

        $filename = [];
        $description = [];
        foreach (ToolsModuleMEP::getLanguages(false) as $lang) {
            $filename_lang = Tools::getValue('filename_' . $lang['id_lang']);
            $description_lang = Tools::getValue('description_' . $lang['id_lang']);
            $filename[$lang['id_lang']] = ($filename_lang ? $filename_lang : Tools::getValue(
                'filename_' . $this->context->language->id
            ));
            $description[$lang['id_lang']] = ($description_lang ? $description_lang : Tools::getValue(
                'description_' . $this->context->language->id
            ));
        }

        $file = $_FILES['file'];

        if (isset($file)) {
            if ((int) $file['error'] === 1) {
                $file['error'] = [];

                $max_upload = (int) ini_get('upload_max_filesize');
                $max_post = (int) ini_get('post_max_size');
                $upload_mb = min($max_upload, $max_post);
                $file['error'][] = sprintf(
                    $this->l('File %1$s exceeds the size allowed by the server. The limit is set to %2$d MB.'),
                    '<b>' . $file['name'] . '</b> ',
                    '<b>' . $upload_mb . '</b>'
                );
            }

            $file['error'] = [];

            $is_attachment_name_valid = false;

            if (array_key_exists($this->context->language->id, $filename) && $filename[$this->context->language->id]) {
                $is_attachment_name_valid = true;
            }

            if (!$is_attachment_name_valid) {
                $file['error'][] = $this->l('An attachment name is required.');
            }

            if (empty($file['error'])) {
                if (is_uploaded_file($file['tmp_name'])) {
                    if ($file['size'] > (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024)) {
                        $file['error'][] = sprintf(
                            $this->l(
                                'The file is too large. Maximum size allowed is: %1$d kB. 
                                The file you are trying to upload is %2$d kB.'
                            ),
                            Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024,
                            number_format($file['size'] / 1024, 2, '.', '')
                        );
                    } else {
                        do {
                            $uniqid = sha1(microtime());
                        } while (file_exists(_PS_DOWNLOAD_DIR_ . $uniqid));
                        if (!copy($file['tmp_name'], _PS_DOWNLOAD_DIR_ . $uniqid)) {
                            $file['error'][] = $this->l('File copy failed');
                        }

                        @unlink($file['tmp_name']);
                    }
                } else {
                    $file['error'][] = $this->l('The file is missing.');
                }

                if (empty($file['error']) && isset($uniqid)) {
                    $attachment = new Attachment();

                    $attachment->name = $filename;
                    $attachment->description = $description;

                    $attachment->file = $uniqid;
                    $attachment->mime = $file['type'];
                    $attachment->file_name = $file['name'];

                    if (empty($attachment->mime) || Tools::strlen($attachment->mime) > 128) {
                        $file['error'][] = $this->l('Invalid file extension');
                    }

                    if (!Validate::isGenericName($attachment->file_name)) {
                        $file['error'][] = $this->l('Invalid file name');
                    }

                    if (Tools::strlen($attachment->file_name) > 128) {
                        $file['error'][] = $this->l('The file name is too long.');
                    }

                    if (empty($this->errors)) {
                        $res = $attachment->add();
                        if (!$res) {
                            $file['error'][] = $this->l('This attachment was unable to be loaded into the database.');
                        } else {
                            $file['id_attachment'] = $attachment->id;
                            $file['filename'] = $attachment->name[$this->context->employee->id_lang];
                            if (!$res) {
                                $file['error'][] = $this->l(
                                    'We were unable to associate this attachment to a product.'
                                );
                            }
                        }
                    } else {
                        $file['error'][] = $this->l('Invalid file');
                    }
                }
            }

            exit(json_encode($file));
        }
    }

    public function ajaxProcessVirtual()
    {
        $object = new VirtualTabMEP();

        return $object->applyChangeForProducts(Tools::getValue('ids'));
    }

    public function ajaxProcessLoadFeatures()
    {
        $p = (int) Tools::getValue('p', 1);

        $features = MassEditTools::getFeatures($this->context->language->id, true, $p);

        foreach ($features as &$feature) {
            $feature['values'] = FeatureValue::getFeatureValuesWithLang(
                $this->context->language->id,
                $feature['id_feature']
            );
        }
        $features_list = '';

        foreach ($features as $f) {
            $this->context->smarty->assign(
                [
                    'languages' => ToolsModuleMEP::getLanguages(false),
                    'feature' => $f,
                ]
            );
            $features_list .= ToolsModuleMEP::fetchTemplate(
                'admin/mass_edit_product/helpers/form/row_feature.tpl'
            );
        }

        exit(json_encode([
                    'hasError' => false,
                    'features_list' => $features_list,
                ]));
    }

    public function ajaxProcessUploadImages()
    {
        MassEditTools::clearTmpFolder();
        $images = MassEditTools::getImages('image');
        $response_images = [];
        $error = [];
        if (is_array($images) && count($images)) {
            foreach ($images as $key => $image) {
                if (MassEditTools::checkImage('image', $key)) {
                    $response_images[$key] = [];
                    $this->uploadImageProduct($image, MassEditTools::getPath() . $key . '_original.jpg');
                    $response_images[$key]['original'] = $key . '_original.jpg';
                    $types = ImageType::getImagesTypes('products');
                    foreach ($types as $type) {
                        $this->uploadImageProduct(
                            $image,
                            MassEditTools::getPath() . $key . '_original_' . $type['name'] . '.jpg',
                            $type['width'],
                            $type['height']
                        );
                        $response_images[$key][$type['name']] = $key . '_original_' . $type['name'] . '.jpg';
                    }
                } else {
                    $number = $key + 1;
                    $error[] = 'file № ' . $number . ' ' . Context::getContext()->getTranslator()->trans('Image format not recognized, allowed formats are: .gif, .jpg, .png');
                }
            }
        }
        exit(json_encode([
                    'responseImages' => $response_images,
                    'error' => $error,
                ]));
    }

    public function ajaxProcessGetProducts()
    {
        $query = Tools::getValue('query');
        $select_products = Tools::getValue('select_products');
        $search_by = Tools::getValue('search_by');
        if (!is_array($select_products) || !count($select_products)) {
            $select_products = [];
        }

        $search_by_query = 'pl.`name`';
        if ($search_by == 0) {
            $search_by_query = 'ps.`reference`';
        }

        $result = Db::getInstance()->executeS(
            'SELECT pl.`id_product`, CONCAT(pl.`id_product`, " - ", pl.`name`) as `name` 
             FROM ' . _DB_PREFIX_ . 'product_shop p
		     LEFT JOIN ' . _DB_PREFIX_ . 'product ps ON ps.`id_product` = p.`id_product`
		     LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl ON p.`id_product` = pl.`id_product` 
		     AND pl.`id_lang` = ' . (int) $this->context->language->id .
            ' WHERE ' . $search_by_query . ' LIKE "%' . pSQL($query) . '%" 
            AND p.`id_shop` = ' . (int) $this->context->shop->id
            . ' AND pl.`id_shop` = ' . (int) $this->context->shop->id .
            (count($select_products) ?
                ' AND p.id_product NOT 
                IN(' . pSQL(implode(',', array_map('intval', $select_products))) . ') '
                : '')
        );

        if (!$result) {
            $result = [];
        }
        exit(json_encode($result));
    }

    public function ajaxProcessGetCombinationsByAttributes()
    {
        $attributes = [];
        foreach ((array) Tools::getValue('data') as $data) {
            $attributes[] = $data['value'];
        }
        $attributes = array_unique($attributes);

        $combinations = Db::getInstance()->executeS(
            'SELECT `id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute_combination`
		WHERE `id_attribute` IN (' . (count($attributes) ? implode($attributes, ', ') : '0') . ') 
		GROUP BY 1 HAVING COUNT(*) = ' . (int) count($attributes)
        );

        exit(json_encode([
                    'hasError' => $combinations ? false : true,
                    'error' => $this->module->l('No combinations with these attributes'),
                    'data' => $combinations,
                ]));
    }

    public function ajaxProcessGetAttributesByGroup()
    {
        $attributes = MassEditTools::getAttributes($this->context->language->id);

        exit(json_encode([
                'hasError' => $attributes ? false : true,
                'error' => $this->module->l('No combinations with these attributes'),
                'data' => $attributes,
            ]));
    }

    public function ajaxProcessRenderFeatureValues()
    {
        $res = [];
        $id_feature = Tools::getValue('ids_feature');
        $res[] = [
            'id_feature' => $id_feature,
            'html' => ToolsModuleMEP::fetchTemplate(
                'admin/feature_values.tpl',
                [
                    'values' => FeatureValue::getFeatureValuesWithLang(
                        $this->context->language->id,
                        (int) $id_feature
                    ),
                    'id_feature' => $id_feature,
                ]
            ),
        ];
        exit(json_encode([
                'return' => $res,
            ]));
    }
    public function ajaxProcessFeature()
    {
        $features = Feature::getFeatures($this->context->language->id);
        foreach ($features as $key => $group) {
            $features[$key]['count_feature_value'] = count(Feature::getFeature($this->context->language->id,
                (int) $group['id_feature']));
        }
        $res = [
            'html' => ToolsModuleMEP::fetchTemplate('admin/feature.tpl',
                [
                    'values' => $features,
                ]
            ),
        ];

        exit(json_encode([
                'return' => $res,
            ]));
    }

    public function ajaxProcessRenderAttributeValues()
    {
        $res = [];
        $id_attribute = Tools::getValue('ids_attribute');
        $tab = Tools::getValue('tabs');

        if ($tab == 'yes') {
            $res[] = [
                'id_attribute' => $id_attribute,
                'html' => ToolsModuleMEP::fetchTemplate(
                    'admin/tab_option_value.tpl',
                    [
                        'values' => AttributeGroup::getAttributes(
                            $this->context->language->id,
                            (int) $id_attribute
                        ),
                        'id_attribute' => $id_attribute,
                    ]
                ),
            ];
        } else {
            $res[] = [
                'id_attribute' => $id_attribute,
                'html' => ToolsModuleMEP::fetchTemplate(
                    'admin/attribute_values.tpl',
                    [
                        'values' => AttributeGroup::getAttributes(
                            $this->context->language->id,
                            (int) $id_attribute
                        ),
                        'id_attribute' => $id_attribute,
                    ]
                ),
            ];
            $res[] = [
                'id_attribute' => $id_attribute,
                'html_sel' => ToolsModuleMEP::fetchTemplate(
                    'admin/attribute_values_sel.tpl',
                    [
                        'values' => AttributeGroup::getAttributes(
                            $this->context->language->id,
                            (int) $id_attribute
                        ),
                        'id_attribute' => $id_attribute,
                    ]
                ),
            ];
        }

        exit(json_encode([
                'return' => $res,
                ]));
    }
    public function ajaxProcessAttributeGroup()
    {
        $attributes_group = AttributeGroup::getAttributesGroups($this->context->language->id);
        foreach ($attributes_group as $key => $group) {
            $attributes_group[$key]['count_attribute_value'] = count(AttributeGroup::getAttributes($this->context->language->id,
                (int) $group['id_attribute_group']));
        }
        exit(json_encode(['return' => ['html' => ToolsModuleMEP::fetchTemplate('admin/attribute_group.tpl', ['values' => $attributes_group])]]));
    }

    public function ajaxProcessLoadCombinations()
    {
        $id_product = (int) Tools::getValue('id_product');
        exit(json_encode(['combinations' => MassEditTools::renderCombinationsProduct($id_product)]));
    }

    /**
     * instead ajaxProcessLoadCombinations() for one ajax request
     */
    public function ajaxProcessLoadCombinationsOneRequest()
    {
        $ids_product = Tools::getValue('ids_product');
        exit(json_encode(MassEditTools::renderCombinationsProduct($ids_product)));
    }

    public function ajaxProcessAddCustomizationField()
    {
        $type = Tools::getValue('type');
        $counter = Tools::getValue('counter');
        $languages = ToolsModuleMEP::getLanguages(false);

        exit(json_encode([
                    'html' => ToolsModuleMEP::fetchTemplate(
                        'admin/mass_edit_product/helpers/form/customization_field.tpl',
                        [
                            'type' => $type,
                            'counter' => $counter,
                            'languages' => $languages,
                        ]
                    ),
                ]));
    }

    public function ajaxProcessSaveTemplateProduct()
    {
        $products = Tools::getValue('products');
        $name = Tools::getValue('name');

        if (!is_array($products) || !count($products)) {
            $this->errors[] = $this->l('Not products!');
        }
        if (!$name) {
            $this->errors[] = $this->l('Name empty');
        }

        if (!count($this->errors)) {
            $template_products = new TemplateProductsMEP();
            $template_products->name = $name;
            foreach ($products as $product) {
                $template_products->products[] = ['id_product' => $product['id']];
            }

            if (!$template_products->save()) {
                $this->errors[] = $this->l('Template save successfuly!');
            }
        }

        exit(json_encode([
                    'hasError' => (count($this->errors) ? true : false),
                    'errors' => $this->errors,
                    'templates_products' => TemplateProductsMEP::getAll(true),
                ]));
    }

    public function ajaxProcessDeleteTemplateProduct()
    {
        $id = Tools::getValue('id');
        $template_products = new TemplateProductsMEP($id);

        if (Validate::isLoadedObject($template_products)) {
            $template_products->delete();
        }

        exit(json_encode([]));
    }

    public function ajaxProcessGetTemplateProduct()
    {
        $id = Tools::getValue('id');
        $template_products = new TemplateProductsMEP($id);

        $currency = Currency::getCurrency(Configuration::get('PS_CURRENCY_DEFAULT'));
        $currency = $currency['id_currency'];

        $popup_list = '';
        $list = '';
        $products = [];
        foreach ($template_products->products as $product) {
            $popup_list .= ToolsModuleMEP::fetchTemplate(
                'admin/mass_edit_product/helpers/form/popup_product_line.tpl',
                [
                    'product' => $product,
                ]
            );
            $list .= ToolsModuleMEP::fetchTemplate(
                'admin/mass_edit_product/helpers/form/product_line.tpl',
                [
                    'product' => $product,
                    'currency' => $currency,
                ]
            );
            $products[$product['id_product']] = [
                'id' => $product['id_product'],
                'name' => $product['name'],
            ];
        }

        exit(json_encode([
                    'popup_list' => $popup_list,
                    'list' => $list,
                    'products' => $products,
                ]));
    }

    public function ajaxProcessLoadTab()
    {
        $tab_name = Tools::getValue('tab_name');
        $class_name = ToolsModuleMEP::toCamelCase($tab_name, true) . 'TabMEP';
        if (class_exists($class_name)) {
            ob_start();
            /**
             * @var BaseTabMEP $object
             */
            $object = new $class_name();

            echo $object->renderTabForm();
            $form = ob_get_contents();
            ob_clean();

            exit(json_encode([
                'hasError' => false,
                'html' => $form,
            ]));
        } else {
            exit(json_encode([
                'hasError' => true,
                'error' => sprintf($this->l('Class tab: %s not exists'), $class_name),
            ]));
        }
    }

    public function uploadImageProduct($tmp_image, $image_to, $width = null, $height = null)
    {
        ImageManager::resize($tmp_image, $image_to, $width, $height);
    }

    public function ajaxProcessProductsList()
    {
        $query = Tools::getValue('q', false);

        if (empty($query)) {
            return;
        }

        /*
         * In the SQL request the "q" param is used entirely to match result in database.
         * In this way if string:"(ref : #ref_pattern#)" is displayed on the return list,
         * they are no return values just because string:"(ref : #ref_pattern#)"
         * is not write in the name field of the product.
         * So the ref pattern will be cut for the search request.
         */
        if ($pos = strpos($query, ' (ref:')) {
            $query = Tools::substr($query, 0, $pos);
        }

        $excludeIds = Tools::getValue('excludeIds', false);
        if ($excludeIds && $excludeIds != 'NaN') {
            $excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
        } else {
            $excludeIds = false;
        }

        // Excluding downloadable products from packs because download from pack is not supported
        $forceJson = Tools::getValue('forceJson', false);
        $disableCombination = Tools::getValue('disableCombination', false);
        $excludeVirtuals = (bool) Tools::getValue('excludeVirtuals', false);
        $exclude_packs = (bool) Tools::getValue('exclude_packs', false);

        $context = Context::getContext();

        $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image_shop.`id_image` id_image, 
                      il.`legend`, p.`cache_default_attribute`
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl 
                ON (pl.id_product = p.id_product 
                AND pl.id_lang = ' . (int) $context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` 
                    AND image_shop.cover=1 
                    AND image_shop.id_shop=' . (int) $context->shop->id . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il 
                ON (image_shop.`id_image` = il.`id_image` 
                AND il.`id_lang` = ' . (int) $context->language->id . ')
                WHERE (pl.name LIKE \'%' . pSQL($query) . '%\' OR p.reference LIKE \'%' . pSQL($query) . '%\')' .
            ($excludeIds ? ' AND p.id_product NOT IN (' . $excludeIds . ') ' : ' ') .
            ($excludeVirtuals ? 'AND NOT EXISTS (SELECT 1 FROM `
            ' . _DB_PREFIX_ . 'product_download` pd WHERE (pd.id_product = p.id_product))' : '') .
            ($exclude_packs ? 'AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '') .
            ' GROUP BY p.id_product';

        $items = Db::getInstance()->executeS($sql);

        if ($items && ($disableCombination || $excludeIds)) {
            $results = [];
            foreach ($items as $item) {
                if (!$forceJson) {
                    $item['name'] = str_replace('|', '&#124;', $item['name']);
                    $results[] = trim($item['name']) . (!empty($item['reference']) ? ' (ref: ' . $item['reference'] . ')' : '') . '|' . (int) $item['id_product'];
                } else {
                    $results[] = [
                        'id' => $item['id_product'],
                        'name' => $item['name'] . (!empty($item['reference'])
                                ? ' (ref: ' . $item['reference'] . ')' : ''),
                        'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                        'image' => str_replace(
                            'http://',
                            Tools::getShopProtocol(),
                            $context->link->getImageLink($item['link_rewrite'], $item['id_image'], 'home_default')
                        ),
                    ];
                }
            }

            if (!$forceJson) {
                exit(implode(PHP_EOL, $results));
            }

            exit(json_encode($results));
        }

        if ($items) {
            // packs
            $results = [];
            foreach ($items as $l => $item) {
                // check if product have combination
                if (Combination::isFeatureActive() && $item['cache_default_attribute']) {
                    $sql = 'SELECT pa.`id_product_attribute`, pa.`reference`, ag.`id_attribute_group`, 
                                   pai.`id_image`, agl.`name` AS group_name, al.`name` AS attribute_name,
                                a.`id_attribute`
                            FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                            ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac 
                            ON pac.`id_product_attribute` = pa.`id_product_attribute`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag 
                            ON ag.`id_attribute_group` = a.`id_attribute_group`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al 
                            ON (a.`id_attribute` = al.`id_attribute` 
                            AND al.`id_lang` = ' . (int) $context->language->id . ')
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl 
                            ON (ag.`id_attribute_group` = agl.`id_attribute_group` 
                            AND agl.`id_lang` = ' . (int) $context->language->id . ')
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_image` pai 
                            ON pai.`id_product_attribute` = pa.`id_product_attribute`
                            WHERE pa.`id_product` = ' . (int) $item['id_product'] . '
                            GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
                            ORDER BY pa.`id_product_attribute`';

                    $combinations = Db::getInstance()->executeS($sql);
                    if (false) {
                        foreach ($combinations as $k => $combination) {
                            $results[$l]['id'] = $item['id_product'];
                            $results[$l]['id_product_attribute'] = $combination['id_product_attribute'];

                            !empty($results[$l]['name']) ?
                                $results[$l]['name'] .= ' '
                                    . $combination['group_name'] . '-' . $combination['attribute_name']
                                : $results[$l]['name'] = $item['name'] . ' '
                                . $combination['group_name'] . '-' . $combination['attribute_name'];
                            if (!empty($combination['reference'])) {
                                $results[$l]['ref'] = $combination['reference'];
                            } else {
                                $results[$l]['ref'] = !empty($item['reference']) ?
                                    $item['reference'] : '';
                            }
                            if (empty($results[$l]['image'])) {
                                $results[$l]['image'] = str_replace(
                                    'http://',
                                    Tools::getShopProtocol(),
                                    $context->link->getImageLink(
                                        $item['link_rewrite'],
                                        $combination['id_image'],
                                        'home_default'
                                    )
                                );
                            }
                            $results[$l]['text'] = $results[$l]['name'];
                        }
                    } else {
                        $results[$l] = [
                            'id' => $item['id_product'],
                            'name' => $item['name'],
                            'text' => $item['name'],
                            'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                            'image' => str_replace(
                                'http://',
                                Tools::getShopProtocol(),
                                $context->link->getImageLink($item['link_rewrite'], $item['id_image'], 'home_default')
                            ),
                        ];
                    }
                } else {
                    $results[$l] = [
                        'id' => $item['id_product'],
                        'name' => $item['name'],
                        'text' => $item['name'],
                        'ref' => (!empty($item['reference']) ? $item['reference'] : ''),
                        'image' => str_replace(
                            'http://',
                            Tools::getShopProtocol(),
                            $context->link->getImageLink(
                                $item['link_rewrite'],
                                $item['id_image'],
                                'home_default'
                            )
                        ),
                    ];
                }
            }

            exit(json_encode(['results' => $results]));
        }

        exit(json_encode(['results' => []]));
    }

    public function ajaxProcessCategoryList()
    {
        $query = Tools::getValue('q', false);

        if (empty($query)) {
            return;
        }

        $results = Db::getInstance()->executeS('SELECT c.`id_category` as id, cl.`name`
		FROM `' . _DB_PREFIX_ . 'category` c
		LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl 
		ON (c.`id_category` = cl.`id_category`' . Shop::addSqlRestrictionOnLang('cl') . ')
		WHERE cl.`id_lang` = ' . (int) $this->context->language->id . ' AND c.`level_depth` <> 0
		AND cl.`name` LIKE \'%' . pSQL($query) . '%\'
		GROUP BY c.id_category
		ORDER BY c.`position`');

        if ($results) {
            foreach ($results as &$result) {
                $result['text'] = $result['id'] . ' - ' . $result['name'];
            }
        }

        exit(json_encode(['results' => $results]));
    }
}
