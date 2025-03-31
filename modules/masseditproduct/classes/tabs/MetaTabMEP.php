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
class MetaTabMEP extends BaseTabMEP
{
    public function applyChangeBoth($products, $combinations)
    {
    }

    public function applyChangeForProducts($products)
    {
        ${'_POST'}['products'] = false;
        $meta_title = Tools::getValue('meta_title');
        $meta_description = Tools::getValue('meta_description');
        $meta_keywords = Tools::getValue('meta_keywords');
        $meta_chpu = Tools::getValue('meta_chpu');
        $tags = trim(Tools::getValue('tags'));
        $language = (int) Tools::getValue('language');
        $edit_tags = (int) Tools::getValue('edit_tags');
        $meta_redirect = Tools::getValue('meta_redirect');
        $category_redirect = Tools::getValue('category_redirect');
        $product_redirect = Tools::getValue('product_redirect');

        if ($this->checkAccessField('tags') && $edit_tags == 2) {
            $array_tags = explode(',', $tags);
            $data_for_tag = '';
            foreach ($products as $id_product) {
                foreach ($array_tags as $tag) {
                    if (Tools::substr($tag, 0, 1) == '{' && Tools::substr($tag, -1) == '}') {
                        $data_for_tag = $data_for_tag . '"' . MassEditTools::renderMetaTag(
                            $tag,
                            (int) $id_product,
                            $language
                        ) . '",';
                    } else {
                        $data_for_tag = $data_for_tag . '"' . $tag . '",';
                    }
                }
            }
            $tags = $tags_sql = Tools::substr($data_for_tag, 0, -1);

            if ($tags) {
                $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'tag` WHERE `name` IN (' . $tags . ')';
                $res = Db::getInstance()->executeS($sql);
            }

            $where = 'id_product IN (' . pSQL(implode(',', $products)) . ')';

            $tag_list = [];

            if (isset($res) && is_array($res)) {
                $in = '';
                foreach ($res as $tag) {
                    $in .= $tag['id_tag'] . ', ';
                }
                $in = rtrim($in, ', ');

                if (strpos($in, ',')) {
                    $where .= ' AND id_tag IN (' . pSQL($in) . ')';
                } else {
                    if ($in != 0) {
                        $where .= ' AND id_tag="' . (int) $in . '"';
                    }
                }

                foreach ($res as $tag_removed) {
                    $tag_list[] = $tag_removed['id_tag'];
                }
            } else {
                $tags_removed = Db::getInstance()->executeS(
                    'SELECT id_tag FROM ' . _DB_PREFIX_ . 'product_tag WHERE ' . pSQL($where)
                );
                $tag_list = [];
                foreach ($tags_removed as $tag_removed) {
                    $tag_list[] = $tag_removed['id_tag'];
                }
            }

            Db::getInstance()->delete('product_tag', $where);
            Db::getInstance()->delete(
                'tag',
                'NOT EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'product_tag
				WHERE ' . _DB_PREFIX_ . 'product_tag.id_tag = ' . _DB_PREFIX_ . 'tag.id_tag)'
            );

            if ($tag_list != []) {
                Tag::updateTagCount($tag_list);
            }
        }

        foreach ($products as $id_product) {
            $data_for_update = [];

            if ($this->checkAccessField('meta_title')) {
                $meta_title_result = MassEditTools::renderMetaTag($meta_title, (int) $id_product, $language);
                $data_for_update['meta_title'] = $meta_title_result;
            }

            if ($this->checkAccessField('meta_redirect') && $meta_redirect != '--') {
                $red = tools::getvalue('meta_redirect');

                if ($red == '301-product' || $red == '302-product') {
                    $red_type = $product_redirect;
                }
                if ($red == '301-category' || $red == '302-category') {
                    $red_type = $category_redirect;
                }
                if ($red == '404') {
                    $red_type = 0;
                }
                Db::getInstance()->execute('
                   UPDATE `' . _DB_PREFIX_ . 'product_shop` p 
                   SET p.redirect_type = "' . $red . '",  p.id_type_redirected = ' . (int) $red_type . ' 
                   WHERE p.id_product = ' . (int) $id_product . '');
            }

            if ($this->checkAccessField('meta_description')) {
                $meta_description_result = MassEditTools::renderMetaTag(
                    $meta_description,
                    (int) $id_product,
                    $language
                );
                $data_for_update['meta_description'] = addslashes($meta_description_result);
            }

            if ($this->checkAccessField('meta_keywords')) {
                $meta_keywords_result = MassEditTools::renderMetaTag($meta_keywords, (int) $id_product, $language);
                $data_for_update['meta_keywords'] = $meta_keywords_result;
            }

            if ($this->checkAccessField('meta_chpu')) {
                $meta_chpu_result = MassEditTools::renderMetaTag($meta_chpu, (int) $id_product, $language);
                $meta_chpu_result = preg_replace('/\s+/', '-', $meta_chpu_result);
                $meta_chpu_result = str_replace("'", '-', $meta_chpu_result);
                if (Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL') == ''
                    || Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL') == 0) {
                    $meta_chpu_result = Seochar::convertseo($meta_chpu_result);
                }
                $data_for_update['link_rewrite'] = $meta_chpu_result;
            }

            if ($this->checkAccessField('tags') && $edit_tags != 2) {
                if ($edit_tags == 0 || $edit_tags == 1) {
                    if ($edit_tags == 1) {
                        Tag::deleteTagsForProduct((int) $id_product);
                    }
                    if ($edit_tags == 0 || $edit_tags == 1) {
                        $data_for_tag = '';
                        $tags2 = trim(Tools::getValue('tags'));
                        $array_tags = explode(',', $tags2);
                        foreach ($array_tags as $tag) {
                            if (Tools::substr($tag, 0, 1) == '{' && Tools::substr($tag, -1) == '}') {
                                $data_for_tag = $data_for_tag . '"' . MassEditTools::renderMetaTag(
                                    $tag,
                                    (int) $id_product,
                                    $language
                                ) . '",';
                            } else {
                                $data_for_tag = $data_for_tag . '"' . $tag . '",';
                            }
                        }
                        $tags_sql = Tools::substr($data_for_tag, 0, -1);
                        $tags = str_replace('"', '', $tags_sql);
                    }

                    if ($tags) {
                        foreach (Language::getLanguages(false) as $lang) {
                            Tag::addTags((int) $lang['id_lang'], (int) $id_product, $tags);
                        }
                    }
                }
            }

            if (count($data_for_update)) {
                Db::getInstance()->update(
                    'product_lang',
                    $data_for_update,
                    ' id_product = ' . (int) $id_product
                    . ($language ? ' AND id_lang = ' . (int) $language : '')
                    . ' ' . (Shop::isFeatureActive() && $this->sql_shop ? ' AND id_shop ' . $this->sql_shop : '')
                );
            }
        }

        return [];
    }

    public function applyChangeForCombinations($products)
    {
    }

    public function getTitle()
    {
        return $this->l('Meta');
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
