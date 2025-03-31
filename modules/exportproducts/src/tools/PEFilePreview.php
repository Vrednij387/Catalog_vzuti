<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licensed under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the license agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    MyPrestaModules
 * @copyright 2013-2020 MyPrestaModules
 * @license LICENSE.txt
 */
if (!defined('_PS_VERSION_')) {
  exit;
}
require_once _PS_MODULE_DIR_ . 'exportproducts/src/product/PEProductDataProvider.php';
require_once _PS_MODULE_DIR_ . 'exportproducts/src/product/PEProductFilter.php';
require_once _PS_MODULE_DIR_ . 'exportproducts/src/tools/PEFieldSplitter.php';
require_once _PS_MODULE_DIR_ . 'exportproducts/src/tools/PESpreadsheetManager.php';

class PEFilePreview
{
    private $configuration;
    private $product_filter;
    private $saveProductToFile;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;

        if (!isset($this->configuration['filters'])) {
            $this->configuration['filters'] = [];
        }

        if (!isset($this->configuration['fields'])) {
            $this->configuration['fields'] = [];
        }

        $this->configuration['id_configuration'] = 0;

        $this->product_filter = new PEProductFilter($this->configuration);
        $field_splitter = new PEFieldSplitter($this->configuration, $this->product_filter);

        $this->configuration['fields'] = $field_splitter->splitFieldsForExportInSeparateColumns();
    }

    public function getTemplate()
    {
        $sheet_grid = [];
        $fields_names = [];
        $num_of_columns = 10;

        $merge_cells_enable = $this->configuration['merge_cells'] && $this->configuration['format_file'] == 'xlsx' && $this->configuration['separate'];

        if (!empty($this->configuration['fields'])) {
            $fields = $this->configuration['fields'];
            $num_of_columns = count($fields);
            foreach ($fields as $field) {
                $fields_names[] = $field['name'];
            }
        }

        $demo_product_ids = $this->product_filter->getExportProductIds(0, 11);
        $demo_products = [];

        foreach ($demo_product_ids as $demo_product_id) {
            $id_product = $demo_product_id['id_product'];
            $id_product_attribute = isset($demo_product_id['id_product_attribute']) ? $demo_product_id['id_product_attribute'] : 0;

            $product_data_provider = new PEProductDataProvider($id_product, $this->configuration, $id_product_attribute);
            $product = $product_data_provider->getProductDataForExport();

            foreach ($product as $property => $value) {
                $product[$property] = [];
                if ($property == 'image_cover') {
                    $product[$property]['val'] = $product_data_provider->getImageCoverUrl();
                }
                if ($merge_cells_enable) {
                    $product[$property]['notNeedMergeCells'] = $this->isProductCombinationField($property);
                }
                $product[$property]['val'] = $value;
            }

            if ($merge_cells_enable) {
                $mergeCells = $this->needMergeCells($id_product);
                $product['rowspan'] = $mergeCells['countProductCombinations'];
            }
            $product['idProduct']  = $id_product;

            $demo_products[] = $product;
        }

        if (!empty($demo_products)) {
            $num_of_columns = count($demo_products[0]);
        }

        for ($i = 0; $i < $num_of_columns; $i++) {
            $sheet_grid[$i] = PESpreadsheetManager::getColumnLetterByNumber($i + 1);
        }

        $tpl = \Context::getContext()->smarty->createTemplate(_PS_MODULE_DIR_ . 'exportproducts/views/templates/admin/form_preview_file.tpl');

        $tpl->assign([
            'fields_names'          => $fields_names,
            'sheet_grid'            => $sheet_grid,
            'demo_products'         => $demo_products,
            'num_of_columns'        => $num_of_columns,
            'merge_cells_enable'    => $merge_cells_enable,
            'display_header'        => $this->configuration['display_header']
        ]);

        return $tpl->fetch();
    }

    private function isProductCombinationField($field)
    {
        $product_combination_fields = PEConfigurationField::getFieldOptions()[5]['fields'];
        foreach ($product_combination_fields as $val) {
            if ($val['id'] == $field) {
                return true;
            }
        }
        return false;
    }

    private function needMergeCells($id_product)
    {
        $mergeCells = false;
        if (!$id_product) {
            return false;
        }
        $product_combination_fields = $this->getProductCombinationFields();
        if (!$product_combination_fields) {
            return false;
        }
        $product_filter = new PEProductFilter($this->configuration, false);
        $count_product_combinations = $product_filter->getTotalNumberOfProductsForExport($id_product);
        if ($this->saveProductToFile !== $id_product && $count_product_combinations > 1) {
            $mergeCells = true;
        }

        return [
            'mergeCells'                => $mergeCells,
            'countProductCombinations'  => $count_product_combinations,
        ];
    }

    private function getProductCombinationFields()
    {
        $fields = $this->configuration['fields'];
        foreach ($fields as $field) {
            if ($field['tab'] == 'exportTabCombinations') {
                return true;
            }
        }
        return false;
    }

}