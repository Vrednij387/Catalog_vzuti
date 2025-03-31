<?php
/**
 * 2007-2018 PrestaShop
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
 * @copyright 2012-2023 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class SupplierTabMEP extends BaseTabMEP
{
    public $supplier;
    public $id_supplier_default;
    public $object_supplier;

    public function __construct()
    {
        parent::__construct();
        $this->supplier = Tools::getValue('supplier');
        $this->id_supplier_default = (int) Tools::getValue('id_supplier_default');
        $this->object_supplier = new Supplier(
            $this->id_supplier_default,
            $this->context->language->id
        );
    }

    public function applyChangeBoth($products, $combinations)
    {
    }

    public function applyChangeForProducts($products)
    {
        if ($this->checkAccessField('supplier')) {
            foreach ($products as $product) {
                $this->addToReIndexSearch((int) $product);
                $product = new Product((int) $product);
                if (Validate::isLoadedObject($product)) {
                    $product->deleteFromSupplier();
                    foreach ($this->supplier as $sup) {
                        $product->addSupplierReference($sup, 0);
                    }
                    $this->updateDateUpdProduct($product->id);
                }
            }
        }

        $return_products = [];

        if ($this->checkAccessField('id_supplier_default')) {
            foreach ($products as $product) {
                $sql = 'SELECT `id_supplier` FROM '
                    . _DB_PREFIX_ . 'product_supplier WHERE id_product = ' . (int) $product . ' GROUP BY `id_supplier`';
                $id_supliers = DB::getInstance()->executeS($sql);
                if (!empty($id_supliers)) {
                    foreach ($id_supliers as $supplier) {
                        if ($supplier['id_supplier'] == $this->object_supplier->id) {
                            DB::getInstance()->execute(
                                'UPDATE '
                                . _DB_PREFIX_ . 'product  SET `id_supplier` = ' . (int) $this->object_supplier->id . ' WHERE `id_product` = ' . $product
                            );
                            $return_products[(int) $product] = $this->object_supplier->name;
                        }
                    }
                }
            }
        }

        if ($this->checkAccessField('supplier_reference')) {
            $associated_suppliers = Tools::getValue('suppliers_sr');
            $combinations = Tools::getValue('combinations');

            if (!is_array($associated_suppliers) || !count($associated_suppliers)) {
                LoggerMEP::getInstance()->error($this->l('No suppliers'));
            }

            $reference = pSQL(Tools::getValue('supplier_reference', ''));
            $id_currency = (int) Tools::getValue('product_price_currency');

            $price = null;

            if (!empty($associated_suppliers[0])) {
                foreach ($products as $product) {
                    $product = new Product((int) $product);

                    foreach ($associated_suppliers as $sup) {
                        $product->addSupplierReference($sup, 0, $reference, $price, $id_currency);
                    }

                    if ($combinations !== false) {
                        $comb = preg_grep('/^(' . $product->id . '_)+/', $combinations);
                        if (is_array($comb) && !empty($combinations)) {
                            foreach ($comb as $val_comb) {
                                $id_product_attribute = Tools::substr($val_comb, Tools::strpos($val_comb, '_') + 1);
                                foreach ($associated_suppliers as $supplier) {
                                    $product->addSupplierReference(
                                        $supplier,
                                        (int) $id_product_attribute,
                                        $reference,
                                        $price,
                                        $id_currency
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($this->checkAccessField('supplier_price')) {
            $associated_suppliers = Tools::getValue('suppliers_sr_price');
            $combinations = Tools::getValue('combinations');

            if (!is_array($associated_suppliers) || !count($associated_suppliers)) {
                LoggerMEP::getInstance()->error($this->l('No suppliers'));
            }

            $reference = pSQL(Tools::getValue('supplier_reference', ''));
            $id_currency = (int) Tools::getValue('product_price_currency');
            $price = (float) str_replace(
                [' ', ','],
                ['', '.'],
                Tools::getValue('product_price', 0)
            );

            if (!empty($associated_suppliers[0])) {
                foreach ($products as $product) {
                    $product = new Product((int) $product);
                    foreach ($associated_suppliers as $sup) {
                        $product->addSupplierReference($sup, 0, $reference, $price, $id_currency);
                    }
                    if ($combinations !== false) {
                        $comb = preg_grep('/^(' . $product->id . '_)+/', $combinations);
                        if (is_array($comb) && !empty($combinations)) {
                            foreach ($comb as $val_comb) {
                                $id_product_attribute = Tools::substr($val_comb, Tools::strpos($val_comb, '_') + 1);
                                foreach ($associated_suppliers as $supplier) {
                                    $id_product_supplier = $this->getIdByProductAndSupplier($product->id, (int) $id_product_attribute, $supplier);
                                    $product_supplier = new ProductSupplier($id_product_supplier);
                                    $product->addSupplierReference(
                                        $supplier,
                                        (int) $id_product_attribute,
                                        $product_supplier->product_supplier_reference,
                                        $price,
                                        $id_currency
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        return ['products' => $return_products];
    }

    public function getIdByProductAndSupplier($idProduct, $idProductAttribute, $idSupplier)
    {
        // build query
        $query = new DbQuery();
        $query->select('ps.id_product_supplier');
        $query->from('product_supplier', 'ps');
        $query->where(
            'ps.id_product = ' . (int) $idProduct . '
			AND ps.id_product_attribute = ' . (int) $idProductAttribute . '
			AND ps.id_supplier = ' . (int) $idSupplier
        );

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function applyChangeForCombinations($products)
    {
    }

    public function checkBeforeChange()
    {
        if ($this->checkAccessField('supplier')) {
            if (!is_array($this->supplier) || !count($this->supplier)) {
                LoggerMEP::getInstance()->error($this->l('No suppliers'));
            }
        }

        if ($this->checkAccessField('id_supplier_default')) {
            if (!$this->id_supplier_default) {
                LoggerMEP::getInstance()->error($this->l('Supplier default no selected'));
            }

            if (!Validate::isLoadedObject($this->object_supplier) && $this->id_supplier_default) {
                LoggerMEP::getInstance()->error($this->l('Supplier not exists'));
            }
        }

        if (LoggerMEP::getInstance()->hasError()) {
            return false;
        }
        return true;
    }

    public function getTitle()
    {
        return $this->l('Supplier');
    }

    public function assignVariables()
    {
        $variables = parent::assignVariables();
        $variables['suppliers'] = Supplier::getSuppliers(
            false,
            0,
            false
        );
        $variables['currencies'] = Currency::getCurrencies(false, true);
        $variables['id_default_currency'] = Configuration::get('PS_CURRENCY_DEFAULT');
        return $variables;
    }
}
