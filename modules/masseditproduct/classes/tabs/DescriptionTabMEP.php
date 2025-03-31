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
class DescriptionTabMEP extends BaseTabMEP
{
    public function applyChangeBoth($products, $combinations)
    {
    }

    public function applyChangeForProducts($products)
    {
        $description = Tools::getValue('description');
        $description_short = Tools::getValue('description_short');
        $replace_description_short = Tools::getValue('replace_description_short');
        $replace_description = Tools::getValue('replace_description');
        $language = (int) Tools::getValue('language');
        $product_name = Tools::getValue('product_name');
        $location_description_short = Tools::getValue('location_description_short');
        $location_description = Tools::getValue('location_description');
        $location_name = Tools::getValue('location_name');

        foreach ($products as $id_product) {
            if (!$language) {
                $languages = Language::getLanguages(true);
            } else {
                $languages = [['id_lang' => $language]];
            }

            foreach ($languages as $lang) {
                $data_for_update = [];
                $product = new Product($id_product);

                if ($this->checkAccessField('description')) {
                    $this->addToReIndexSearch((int) $id_product);
                    $description_update = MassEditTools::renderMetaTag(
                        $description,
                        (int) $id_product,
                        $lang['id_lang']
                    );

                    $description_update = addslashes($description_update);
                    $product_desc = addslashes($product->description[$lang['id_lang']]);

                    switch ($location_description) {
                        case 1:
                            $data_for_update['description'] = $description_update . $product_desc;
                            break;
                        case 2:
                            $data_for_update['description'] = $product_desc . $description_update;
                            break;
                        case 3:
                            $replace_d = $this->clean($replace_description);
                            $text = $this->clean($product->description[$lang['id_lang']]);
                            $temp_desc = str_replace($replace_d, $description_update, $text);
                            $data_for_update['description'] = addslashes($temp_desc);
                            break;
                        default:
                            $data_for_update['description'] = $description_update;
                    }
                }
                if ($this->checkAccessField('description_short')) {
                    $this->addToReIndexSearch((int) $id_product);
                    $description_short_update2 = MassEditTools::renderMetaTag(
                        $description_short,
                        (int) $id_product,
                        $lang['id_lang']
                    );
                    $description_short_update = addslashes($description_short_update2);
                    $product_short_desc = addslashes($product->description_short[$lang['id_lang']]);

                    switch ($location_description_short) {
                        case 1:
                            $data_for_update['description_short'] =
                                $description_short_update . $product_short_desc;
                            break;
                        case 2:
                            $data_for_update['description_short'] =
                                $product_short_desc . $description_short_update;
                            break;
                        case 3:
                            $replace_d = $this->clean($replace_description_short);
                            $text = $this->clean($product->description_short[$lang['id_lang']]);
                            $temp_desc = str_replace($replace_d, $description_short_update, $text);
                            $data_for_update['description_short'] = addslashes($temp_desc);
                            break;
                        default:
                            $data_for_update['description_short'] = $description_short_update;
                    }
                }
                if ($this->checkAccessField('product_name')) {
                    $data_for_update2 = [];
                    $this->addToReIndexSearch((int) $id_product);
                    $product_name_update = MassEditTools::renderMetaTag(
                        $product_name,
                        (int) $id_product,
                        $lang['id_lang']
                    );
                    $name_product = addslashes($product->name[$lang['id_lang']]);
                    switch ($location_name) {
                        case 1:
                            $data_for_update2['name'] = $product_name_update . $name_product;
                            break;
                        case 2:
                            $data_for_update2['name'] = $name_product . $product_name_update;
                            break;
                        default:
                            $data_for_update2['name'] = $product_name_update;
                    }

                    $data_for_update['name'] = addslashes($data_for_update2['name']);
                }

                if (count($data_for_update)) {
                    Db::getInstance()->update(
                        'product_lang',
                        $data_for_update,
                        ' id_product = ' . (int) $id_product
                        . ($lang['id_lang'] ? ' AND id_lang = ' . (int) $lang['id_lang'] : '')
                        . ' ' . (Shop::isFeatureActive() && $this->sql_shop ? ' AND id_shop ' . $this->sql_shop : '')
                    );
                }
            }
        }

        return [];
    }

    public function clean($text)
    {
        return preg_replace('~\R~', "\r\n", $text);
    }

    public function applyChangeForCombinations($products)
    {
    }

    public function getTitle()
    {
        return $this->l('Description');
    }

    public function assignVariables()
    {
        $variables = parent::assignVariables();
        $variables['static_for_name'] = [
            '{title}' => $this->l('title'),
            '{name}' => $this->l('name product'),
            '{price}' => $this->l('price final'),
            '{manufacturer}' => $this->l('manufacturer'),
            '{category}' => $this->l('default category'),
            '{reference}' => $this->l('product reference'),
        ];
        return $variables;
    }
}
