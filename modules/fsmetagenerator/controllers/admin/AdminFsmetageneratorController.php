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
class AdminFsMetaGeneratorController extends ModuleAdminController
{
    /**
     * @var FsMetaGenerator
     */
    public $module;

    public function ajaxProcessGeneratemeta()
    {
        $this->json = (bool) Tools::getValue('json');

        $response = [
            'has_more' => false,
            'progress_bar_percent' => 0,
            'processed_count' => 0,
            'total_count' => 0,
            'progress_bar_message' => '',
            'alert_title' => '',
        ];

        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $process_step = 2000;
        $error = false;
        $fsmg = new FsMetaGenerator();

        $type = Tools::getValue('fsmg_type', false);

        if (!$this->hasAccess('edit')) {
            $error = true;
            $this->errors[] = $fsmg->l('Access denied');
        }

        $fsmg_secret = Configuration::get('FSMG_SECRET');
        $fsmg_request_token = Tools::getValue('fsmg_request_token');
        $fsmg_request_time = Tools::getValue('fsmg_request_time');
        $fsmg_params = Tools::getValue('fsmg_params');

        if ($fsmg_request_token != sha1($fsmg_secret . $fsmg_request_time)) {
            $error = true;
            $this->errors[] = $fsmg->l('Bad request token, please refresh the browser');
        }

        $offset = Tools::getValue('fsmg_offset');
        $response['processed_count'] = $offset;

        if (!$error && $type == 'product_link_rewrite') {
            $link_rewrite_schema_lang = FsMetaGenerator::getMultilangualConfiguration('FSMG_SCH_PROD_LINK_REWRITE');
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                if (!isset($link_rewrite_schema_lang[$lang['id_lang']])
                    || empty($link_rewrite_schema_lang[$lang['id_lang']])) {
                    $error = true;
                    $this->errors[] = $fsmg->l(
                        sprintf('Please fill the Friendly URL schema in %s language', $lang['name'])
                    );
                }
            }

            if (!$error) {
                $products = Db::getInstance()->executeS(
                    'SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop` WHERE `id_shop` = ' . (int) $id_shop
                );
                $response['total_count'] = count($products);
                $products = Db::getInstance()->executeS(
                    'SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop` WHERE `id_shop` = ' . (int) $id_shop .
                    ' ORDER BY `id_product` ASC LIMIT ' . (int) $offset . ', ' . (int) $process_step
                );

                foreach ($products as $product) {
                    $p = new FsMetaGeneratorProduct((int) $product['id_product']);

                    foreach ($languages as $lang) {
                        $p_lang = new Product((int) $product['id_product'], false, $lang['id_lang'], $id_shop);
                        $p_link_rewrite = trim($link_rewrite_schema_lang[$lang['id_lang']]);

                        $params = [];
                        // Product params
                        $params['product_name'] = FsMetaGeneratorTools::slugify($p_lang->name);
                        $params['product_meta_title'] = FsMetaGeneratorTools::slugify($p_lang->meta_title);
                        // $params['product_meta_keywords'] = FsMetaGeneratorTools::slugify($p_lang->meta_keywords);
                        $params['product_ean13'] = FsMetaGeneratorTools::slugify($p_lang->ean13);
                        $params['product_upc'] = FsMetaGeneratorTools::slugify($p_lang->upc);
                        $params['product_reference'] = FsMetaGeneratorTools::slugify($p_lang->reference);
                        $params['product_price'] = FsMetaGeneratorTools::slugify(
                            Product::getPriceStatic(
                                $p_lang->id,
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
                                $p_lang->specificPrice
                            )
                        );
                        $params['product_tags'] = FsMetaGeneratorTools::slugify($p_lang->getTags($lang['id_lang']));

                        // Category params
                        $c = new Category($p_lang->id_category_default, $lang['id_lang'], $id_shop);
                        $params['default_category_name'] = FsMetaGeneratorTools::slugify($c->name);
                        $params['default_category_meta_title'] = FsMetaGeneratorTools::slugify($c->meta_title);
                        $params['default_category_link_rewrite'] = FsMetaGeneratorTools::slugify($c->link_rewrite);

                        // Manufacturer params
                        $m = new Manufacturer($p_lang->id_manufacturer, $lang['id_lang']);
                        $params['manufacturer_name'] = FsMetaGeneratorTools::slugify($m->name);
                        $params['manufacturer_meta_title'] = FsMetaGeneratorTools::slugify($m->meta_title);

                        // Supplier params
                        $s = new Supplier($p_lang->id_supplier, $lang['id_lang']);
                        $params['supplier_name'] = FsMetaGeneratorTools::slugify($s->name);
                        $params['supplier_meta_title'] = FsMetaGeneratorTools::slugify($s->meta_title);

                        // Feature params
                        $features = Feature::getFeatures($lang['id_lang']);
                        foreach ($features as $feature) {
                            $f = new Feature($feature['id_feature'], $lang['id_lang']);
                            $params['feature_' . str_replace('-', '_', FsMetaGeneratorTools::slugify($f->name))] = '';
                        }

                        foreach ($p->getFeatures() as $feature) {
                            $f = new Feature($feature['id_feature'], $lang['id_lang']);
                            $fv = new FeatureValue($feature['id_feature_value'], $lang['id_lang']);
                            $params['feature_' . str_replace('-', '_', FsMetaGeneratorTools::slugify($f->name))] =
                                FsMetaGeneratorTools::slugify($fv->value);
                        }

                        // Replace the params
                        foreach ($params as $keyword => $value) {
                            $p_link_rewrite = str_replace('{' . $keyword . '}', $value, $p_link_rewrite);
                        }
                        $p_link_rewrite = preg_replace('/{[^}]+}/', '', $p_link_rewrite);

                        while (preg_match('(--)', $p_link_rewrite)) {
                            $p_link_rewrite = str_replace('--', '-', $p_link_rewrite);
                        }

                        if (Tools::strlen($p_link_rewrite) > 128) {
                            $p_link_rewrite = Tools::substr($p_link_rewrite, 0, 128);
                        }

                        $p->link_rewrite[$lang['id_lang']] = $p_link_rewrite;
                    }

                    $p->save();

                    ++$response['processed_count'];
                }

                $response = $this->generateLoopParams($response);

                $response = $this->generateProgressBarText(
                    $response,
                    $fsmg->l('No item processed'),
                    $fsmg->l('product url generated'),
                    $fsmg->l('product urls generated')
                );

                $response['alert_title'] = $fsmg->l('DONE!');

                $this->confirmations[] = $fsmg->l('Products url generation completed.');

                $this->content = $response;
            }
        }

        if (!$error && $type == 'product_meta') {
            $overwrite = false;
            if (isset($fsmg_params['overwrite']) && $fsmg_params['overwrite'] == 'true') {
                $overwrite = true;
            }

            $products = Db::getInstance()->executeS(
                'SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop` WHERE `id_shop` = ' . (int) $id_shop
            );
            $response['total_count'] = count($products);
            $products = Db::getInstance()->executeS(
                'SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop` WHERE `id_shop` = ' . (int) $id_shop .
                ' ORDER BY `id_product` ASC LIMIT ' . (int) $offset . ', ' . (int) $process_step
            );

            foreach ($products as $product) {
                $this->module->updateProductMetas((int) $product['id_product'], $overwrite, $id_shop);
                ++$response['processed_count'];
            }

            $response = $this->generateLoopParams($response);

            $response = $this->generateProgressBarText(
                $response,
                $fsmg->l('No item processed'),
                $fsmg->l('product meta generated'),
                $fsmg->l('product metas generated')
            );

            $response['alert_title'] = $fsmg->l('DONE!');
            $this->confirmations[] = $fsmg->l('Products Meta generation completed.');
            $this->content = $response;
        }

        if (!$error && $type == 'category_meta') {
            $overwrite = false;
            if (isset($fsmg_params['overwrite']) && $fsmg_params['overwrite'] == 'true') {
                $overwrite = true;
            }

            $categories = Db::getInstance()->executeS(
                'SELECT `id_category` as `id` FROM `' . _DB_PREFIX_ . 'category_shop` WHERE `id_shop` = ' . (int) $id_shop
            );
            $response['total_count'] = count($categories);
            $categories = Db::getInstance()->executeS(
                'SELECT `id_category` FROM `' . _DB_PREFIX_ . 'category_shop` WHERE `id_shop` = ' . (int) $id_shop .
                ' ORDER BY `id_category` ASC LIMIT ' . (int) $offset . ', ' . (int) $process_step
            );

            foreach ($categories as $category) {
                $this->module->updateCategoryMetas((int) $category['id_category'], $overwrite, $id_shop);
                ++$response['processed_count'];
            }

            $response = $this->generateLoopParams($response);

            $response = $this->generateProgressBarText(
                $response,
                $fsmg->l('No item processed'),
                $fsmg->l('category meta generated'),
                $fsmg->l('category metas generated')
            );

            $response['alert_title'] = $fsmg->l('DONE!');

            $this->confirmations[] = $fsmg->l('Category Meta generation completed.');

            $this->content = $response;
        }

        if (!$error && $type == 'manufacturer_meta') {
            $overwrite = false;
            if (isset($fsmg_params['overwrite']) && $fsmg_params['overwrite'] == 'true') {
                $overwrite = true;
            }

            $manufacturers = Db::getInstance()->executeS(
                'SELECT `id_manufacturer` as `id` FROM `' . _DB_PREFIX_ . 'manufacturer_shop` WHERE `id_shop` = ' . (int) $id_shop
            );
            $response['total_count'] = count($manufacturers);
            $manufacturers = Db::getInstance()->executeS(
                'SELECT `id_manufacturer` FROM `' . _DB_PREFIX_ . 'manufacturer_shop` WHERE `id_shop` = ' . (int) $id_shop .
                ' ORDER BY `id_manufacturer` ASC LIMIT ' . (int) $offset . ', ' . (int) $process_step
            );

            foreach ($manufacturers as $manufacturer) {
                $this->module->updateManufacturerMetas((int) $manufacturer['id_manufacturer'], $overwrite);
                ++$response['processed_count'];
            }

            $response = $this->generateLoopParams($response);

            $response = $this->generateProgressBarText(
                $response,
                $fsmg->l('No item processed'),
                $fsmg->l('manufacturer meta generated'),
                $fsmg->l('manufacturer metas generated')
            );

            $response['alert_title'] = $fsmg->l('DONE!');

            $this->confirmations[] = $fsmg->l('Manufacturer Meta generation completed.');

            $this->content = $response;
        }

        if (!$error && $type == 'supplier_meta') {
            $overwrite = false;
            if (isset($fsmg_params['overwrite']) && $fsmg_params['overwrite'] == 'true') {
                $overwrite = true;
            }

            $suppliers = Db::getInstance()->executeS(
                'SELECT `id_supplier` as `id` FROM `' . _DB_PREFIX_ . 'supplier_shop` WHERE `id_shop` = ' . (int) $id_shop
            );
            $response['total_count'] = count($suppliers);
            $suppliers = Db::getInstance()->executeS(
                'SELECT `id_supplier` FROM `' . _DB_PREFIX_ . 'supplier_shop` WHERE `id_shop` = ' . (int) $id_shop .
                ' ORDER BY `id_supplier` ASC LIMIT ' . (int) $offset . ', ' . (int) $process_step
            );

            foreach ($suppliers as $supplier) {
                $this->module->updateSupplierMetas((int) $supplier['id_supplier'], $overwrite);
                ++$response['processed_count'];
            }

            $response = $this->generateLoopParams($response);

            $response = $this->generateProgressBarText(
                $response,
                $fsmg->l('No item processed'),
                $fsmg->l('supplier meta generated'),
                $fsmg->l('supplier metas generated')
            );

            $response['alert_title'] = $fsmg->l('DONE!');

            $this->confirmations[] = $fsmg->l('Supplier Meta generation completed.');

            $this->content = $response;
        }

        $this->status = 'ok';
    }

    public function ajaxProcessGenerateclearqueue()
    {
        $this->json = (bool) Tools::getValue('json');

        $context = Context::getContext();
        $fsmg = new FsMetaGenerator();
        $error = false;

        if (!$this->hasAccess('edit')) {
            $error = true;
            $this->errors[] = $fsmg->l('Access denied');
        }

        $fsmg_secret = Configuration::get('FSMG_SECRET');
        $fsmg_request_token = Tools::getValue('fsmg_request_token');
        $fsmg_request_time = Tools::getValue('fsmg_request_time');

        if ($fsmg_request_token != sha1($fsmg_secret . $fsmg_request_time)) {
            $error = true;
            $this->errors[] = $fsmg->l('Bad request token, please refresh the browser');
        }

        $id_lang = Tools::getValue('fsmg_id_lang');
        if (!$error && !$id_lang) {
            $error = true;
            $this->errors[] = $fsmg->l('Please select a language');
        }

        if (!$error) {
            $content_type = Tools::getValue('fsmg_content_type', 'all');
            $meta_field = Tools::getValue('fsmg_meta_field', 'all');

            $meta_fields_by_type = $fsmg->meta_fields_by_type;

            foreach ($meta_fields_by_type as $type_key => $types) {
                if ($content_type != 'all' && $type_key != $content_type) {
                    unset($meta_fields_by_type[$type_key]);
                    continue;
                }

                if ($meta_field != 'all') {
                    foreach (array_keys($types) as $format_key) {
                        if ($format_key != $meta_field) {
                            unset($meta_fields_by_type[$type_key][$format_key]);
                        }
                    }
                }

                if (!count($meta_fields_by_type[$type_key])) {
                    unset($meta_fields_by_type[$type_key]);
                }
            }

            $context->smarty->assign([
                'meta_fields_by_type' => $meta_fields_by_type,
                'content_types' => $fsmg->content_types,
                'selected_id_lang' => $id_lang,
            ]);

            $this->content = $fsmg->display(
                $fsmg->getModuleFile(),
                'views/templates/admin/maintenance_clear_queue.tpl'
            );
        }

        $this->status = 'ok';
    }

    public function ajaxProcessClearmetafield()
    {
        $this->json = (bool) Tools::getValue('json');

        $response = [
            'has_more' => false,
            'progress_bar_percent' => 0,
            'processed_count' => 0,
            'total_count' => 0,
            'progress_bar_message' => '',
            'alert_title' => '',
        ];

        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $process_step = 5;
        $error = false;
        $fsmg = new FsMetaGenerator();

        $id_lang = Tools::getValue('fsmg_id_lang', false);
        $content_type = Tools::getValue('fsmg_content_type', false);
        $meta_field = Tools::getValue('fsmg_meta_field', false);

        if (!$this->hasAccess('edit')) {
            $error = true;
            $this->errors[] = $fsmg->l('Access denied');
        }

        $fsmg_secret = Configuration::get('FSMG_SECRET');
        $fsmg_request_token = Tools::getValue('fsmg_request_token');
        $fsmg_request_time = Tools::getValue('fsmg_request_time');

        if (!$error && $fsmg_request_token != sha1($fsmg_secret . $fsmg_request_time) || (!$content_type || !$id_lang || !$meta_field)) {
            $error = true;
            $this->errors[] = $fsmg->l('Bad request token, please refresh the browser');
        }

        if (!$error) {
            $offset = Tools::getValue('fsmg_offset');
            $response['processed_count'] = $offset;

            $class_names = [
                'product' => 'FsMetaGeneratorProduct',
                'category' => 'FsMetaGeneratorCategory',
                'manufacturer' => 'FsMetaGeneratorManufacturer',
                'supplier' => 'FsMetaGeneratorSupplier',
                'cms' => 'FsMetaGeneratorCMS',
                'cms_category' => 'FsMetaGeneratorCMSCategory',
            ];
            $class_name = $class_names[$content_type];

            $sqls = [
                'product' => 'SELECT `id_product` as `id` FROM `' .
                    _DB_PREFIX_ . 'product_shop` WHERE `id_shop` = ' . (int) $id_shop,
                'category' => 'SELECT `id_category` as `id` FROM `' .
                    _DB_PREFIX_ . 'category_shop` WHERE `id_shop` = ' . (int) $id_shop,
                'manufacturer' => 'SELECT `id_manufacturer` as `id` FROM `' .
                    _DB_PREFIX_ . 'manufacturer_shop` WHERE `id_shop` = ' . (int) $id_shop,
                'supplier' => 'SELECT `id_supplier` as `id` FROM `' .
                    _DB_PREFIX_ . 'supplier_shop` WHERE `id_shop` = ' . (int) $id_shop,
                'cms' => 'SELECT `id_cms` as `id` FROM `' .
                    _DB_PREFIX_ . 'cms_shop` WHERE `id_shop` = ' . (int) $id_shop,
                'cms_category' => 'SELECT `id_cms_category` as `id` FROM `' .
                    _DB_PREFIX_ . 'cms_category_shop` WHERE `id_shop` = ' . (int) $id_shop,
            ];
            $sql = $sqls[$content_type];

            $items = Db::getInstance()->executeS($sql);
            $response['total_count'] = count($items);
            $items = Db::getInstance()->executeS($sql . ' ORDER BY `id` ASC LIMIT ' . (int) $offset . ', ' . (int) $process_step);

            foreach ($items as $item) {
                $o = new $class_name((int) $item['id']);

                if ($meta_field == 'meta_title') {
                    $o->meta_title[$id_lang] = '';
                }

                if ($meta_field == 'meta_description') {
                    $o->meta_description[$id_lang] = '';
                }

                if ($meta_field == 'meta_keywords') {
                    $o->meta_keywords[$id_lang] = '';
                }

                $o->save();

                ++$response['processed_count'];
            }

            $response = $this->generateLoopParams($response);

            $response = $this->generateProgressBarText(
                $response,
                $fsmg->l('No item processed'),
                $fsmg->l('meta field cleared'),
                $fsmg->l('meta fields cleared')
            );

            $response['alert_title'] = $fsmg->l('DONE!');
            $this->confirmations[] = $fsmg->l('Meta tag clearing completed.');

            $this->content = $response;
        }

        $this->status = 'ok';
    }

    private function hasAccess($type)
    {
        $tabAccess = Profile::getProfileAccesses(Context::getContext()->employee->id_profile, 'class_name');

        if (isset($tabAccess['AdminFsMetaGenerator'][$type])) {
            if ($tabAccess['AdminFsMetaGenerator'][$type] === '1') {
                return true;
            }
        }

        return false;
    }

    private function generateLoopParams($response)
    {
        if (!$response['total_count']) {
            $response['progress_bar_percent'] = 100;
        } else {
            $response['progress_bar_percent'] = round($response['processed_count'] / $response['total_count'] * 100, 0);
        }
        if ($response['processed_count'] < $response['total_count']) {
            $response['has_more'] = true;
        }

        return $response;
    }

    private function generateProgressBarText($response, $no_item, $singular, $plural)
    {
        if ($response['processed_count'] < 1) {
            $response['progress_bar_message'] = $no_item;
        } elseif ($response['processed_count'] > 1) {
            $response['progress_bar_message'] = $response['processed_count'] . ' ' . $plural;
        } else {
            $response['progress_bar_message'] = $response['processed_count'] . ' ' . $singular;
        }

        return $response;
    }
}
