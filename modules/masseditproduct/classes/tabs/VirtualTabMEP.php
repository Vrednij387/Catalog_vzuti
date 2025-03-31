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
 * @copyright 2012-2018 SeoSA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class VirtualTabMEP extends BaseTabMEP
{
    public $object_virtual;

    public function __construct()
    {
        parent::__construct();
        if (Tools::getValue('action_virtual') == 'false') {
            $this->object_virtual = new ProductDownload();
            $this->object_virtualdisplay_filename = Tools::getValue('name_file');
            $this->object_virtual->date_expiration = Tools::getValue('expiration_date');
            $this->object_virtual->nb_days_accessible = Tools::getValue('amount_of_days');
            $this->object_virtual->nb_downloadable = Tools::getValue('number_downloads');
        }
    }

    public function applyChangeBoth($products, $combinations)
    {
    }

    public function applyChangeForProducts($products)
    {
        $products2 = explode(',', $products);
        $data = [];
        $this->object_virtual = new ProductDownload();
        $data['name'] = Tools::getValue('name_file');
        $data['expiration_date'] = Tools::getValue('expiration_date') ? Tools::getValue('expiration_date') : '';
        $data['nb_days'] = Tools::getValue('amount_of_days');
        $data['nb_downloadable'] = Tools::getValue('number_downloads');
        $data['is_virtual_file'] = 1;
        $data['date_add'] = date('Y-m-d H:i:s');
        $data['file'] = !empty($_FILES['file']['tmp_name']) ? $_FILES['file']['tmp_name'] : '';
        $data['action_for_virtual'] = Tools::getValue('action_for_virtual');
        $action_virtual = Tools::getValue('action_virtual');

        foreach ($products2 as $id_product) {
            $test_Product = new Product($id_product);
            if ($test_Product->getType() == 2 && $action_virtual == 'false') {
                $yes_record = $this->object_virtual->getIdFromIdProduct((int) $id_product);
                $fileName = ProductDownload::getNewFilename();
                $this->object_virtual->id_product = (int) $id_product;
                $this->object_virtual->display_filename = $data['name'];
                $this->object_virtual->filename = $fileName ? $fileName : $this->object_virtual->filename;
                $this->object_virtual->date_expiration = $data['expiration_date'] ? $data['expiration_date'] : '';
                $this->object_virtual->nb_days_accessible = (int) $data['nb_days'];
                $this->object_virtual->nb_downloadable = (int) $data['nb_downloadable'];
                $this->object_virtual->date_add = $data['date_add'];
                $this->object_virtual->active = 1;
                $this->object_virtual->is_shareable = 0;
                $file_old = $this->object_virtual->getFilenameFromIdProduct($id_product);

                if ($data['expiration_date'] == '' || $data['expiration_date'] == '0000-00-00') {
                    $message = Context::getContext()->getTranslator()->trans('No date expiration');
                    LoggerMEP::getInstance()->error($message);
                    exit(json_encode([
                        'hasError' => true,
                        'log' => LoggerMEP::getInstance()->getMessages(),
                    ]));
                }
                if ($this->object_virtual->display_filename == '') {
                    $message = Context::getContext()->getTranslator()->trans('This value should not be blank.');
                    LoggerMEP::getInstance()->error($message);
                    exit(json_encode([
                        'hasError' => true,
                        'log' => LoggerMEP::getInstance()->getMessages(),
                    ]));
                }

                if (!is_numeric($data['nb_days']) || !is_numeric($data['nb_downloadable'])) {
                    $message = Context::getContext()->getTranslator()->trans('This value is not valid');
                    LoggerMEP::getInstance()->error($message);
                    exit(json_encode([
                        'hasError' => true,
                        'log' => LoggerMEP::getInstance()->getMessages(),
                    ]));
                }
                $yes_file = 0;
                foreach (scandir(_PS_DOWNLOAD_DIR_) as $name_file) {
                    if ($name_file == $file_old) {
                        $yes_file = 1;
                        break;
                    }
                }

                if ($file_old != false && $yes_file == 1) {
                    unlink(_PS_DOWNLOAD_DIR_ . $file_old);
                }
                Db::getInstance()->delete('product_download', 'id_product_download = ' . (int) $yes_record);
                $this->object_virtual->add((int) $id_product);
                if ($data['file'] != '') {
                    if (!copy($data['file'], _PS_DOWNLOAD_DIR_ . $fileName)) {
                        $message = 'No file save!';
                        LoggerMEP::getInstance()->error($message);
                        exit(json_encode([
                            'hasError' => true,
                            'log' => LoggerMEP::getInstance()->getMessages(),
                        ]));
                    }
                }
            }
            if ($action_virtual == 'true') {
                if ($data['action_for_virtual'] == 2) {
                    $test_Product->is_virtual = 1;
                    if ($test_Product->getDefaultAttribute($id_product) == 0) {
                        $test_Product->save();
                    }
                } elseif ($data['action_for_virtual'] == 0) {
                    $test_Product->is_virtual = 0;
                    if ($test_Product->getDefaultAttribute($id_product) == 0) {
                        $test_Product->save();
                    }
                }
            }
        }

        exit(json_encode([
            'hasError' => false,
        ]));
    }

    public function applyChangeForCombinations($products)
    {
    }

    public function getTitle()
    {
        return $this->l('Virtual');
    }
}
