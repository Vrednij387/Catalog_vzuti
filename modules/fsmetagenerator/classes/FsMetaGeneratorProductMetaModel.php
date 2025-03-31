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
class FsMetaGeneratorProductMetaModel extends ObjectModel
{
    /** @var int fsmg_product_meta id */
    public $id;

    /** @var int fsmg_product_meta id shop */
    public $id_shop;

    /** @var string fsmg_product_meta categories */
    public $categories = '[]';

    /** @var bool fsmg_product_meta statuts */
    public $active = true;

    /** @var string fsmg_product_meta creation date */
    public $date_add;

    /** @var string fsmg_product_meta last modification date */
    public $date_upd;

    /** @var string fsmg_product_meta meta_title_schema */
    public $meta_title_schema;

    /** @var string fsmg_product_meta meta_description_schema */
    public $meta_description_schema;

    /** @var string fsmg_product_meta meta_keywords_schema */
    public $meta_keywords_schema;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'fsmg_product_meta',
        'primary' => 'id_fsmg_product_meta',
        'multilang' => true,
        'fields' => [
            'id_shop' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'categories' => ['type' => self::TYPE_STRING, 'validate' => 'isAnything', 'required' => true],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDateFormat'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDateFormat'],

            'meta_title_schema' => ['type' => self::TYPE_STRING, 'lang' => true,
                'validate' => 'isAnything', 'size' => 255, ],
            'meta_description_schema' => ['type' => self::TYPE_STRING, 'lang' => true,
                'validate' => 'isAnything', 'size' => 255, ],
            'meta_keywords_schema' => ['type' => self::TYPE_STRING, 'lang' => true,
                'validate' => 'isAnything', 'size' => 255, ],
        ],
    ];

    protected static $order_way_fields = [
        'id_fsmg_product_meta',
        'categories',
        'meta_title_schema',
        'meta_description_schema',
        'meta_keywords_schema',
        'active',
    ];

    public function copyFromPost()
    {
        foreach (self::$definition['fields'] as $key => $properties) {
            if (isset($properties['lang']) && $properties['lang']) {
                $languages = Language::getLanguages(false);
                foreach ($languages as $language) {
                    if (Tools::getIsset($key . '_' . $language['id_lang'])) {
                        $this->{$key}[(int) $language['id_lang']] = Tools::getValue($key . '_' . $language['id_lang'], '');
                    }
                }
            } else {
                if ($key != 'categories') {
                    $this->{$key} = Tools::getValue($key);
                }
            }
        }

        $this->categories = json_encode(Tools::getValue('categories', []));
    }

    public static function getListContent($id_lang, $filter)
    {
        $where = '`id_lang` = ' . (int) $id_lang . ' ' . Shop::addSqlRestrictionOnLang();
        if (isset($filter['id_fsmg_product_meta']) && $filter['id_fsmg_product_meta'] != '') {
            $where .= ' AND fsmg.id_fsmg_product_meta = \'' . pSQL($filter['id_fsmg_product_meta']) . '\'';
        }

        /*if (isset($filter['categories']) && $filter['categories'] != '')
            $where .= ' AND fschl.name LIKE \'%'.pSQL($filter['name']).'%\'';*/

        if (isset($filter['meta_title_schema']) && is_numeric($filter['meta_title_schema'])) {
            if ($filter['meta_title_schema']) {
                $where .= ' AND fsmgl.meta_title_schema != \'\'';
            } else {
                $where .= ' AND fsmgl.meta_title_schema = \'\'';
            }
        }

        if (isset($filter['meta_description_schema']) && is_numeric($filter['meta_description_schema'])) {
            if ($filter['meta_description_schema']) {
                $where .= ' AND fsmgl.meta_description_schema != \'\'';
            } else {
                $where .= ' AND fsmgl.meta_description_schema = \'\'';
            }
        }

        if (isset($filter['meta_keywords_schema']) && is_numeric($filter['meta_keywords_schema'])) {
            if ($filter['meta_keywords_schema']) {
                $where .= ' AND fsmgl.meta_keywords_schema != \'\'';
            } else {
                $where .= ' AND fsmgl.meta_keywords_schema = \'\'';
            }
        }

        if (isset($filter['active']) && $filter['active'] != '') {
            $where .= ' AND fsmg.active = \'' . pSQL($filter['active']) . '\'';
        }

        // Order by sql protection
        if (!in_array($filter['order_by'], self::$order_way_fields)) {
            $filter['order_by'] = 'id_fsmg_product_meta';
        }

        if ($filter['order_by'] == 'id_fsmg_product_meta') {
            $filter['order_by'] = 'fsmg.id_fsmg_product_meta';
        }

        // Order way sql protection
        $order_way = Tools::strtolower($filter['order_way']);
        if (!in_array($order_way, ['desc', 'asc'])) {
            $order_way = 'desc';
        }
        $filter['order_way'] = Tools::strtoupper($order_way);

        return Db::getInstance()->executeS(
            'SELECT *
            FROM `' . _DB_PREFIX_ . 'fsmg_product_meta` fsmg
            LEFT JOIN `' . _DB_PREFIX_ . 'fsmg_product_meta_lang` fsmgl ON
            (fsmg.`id_fsmg_product_meta` = fsmgl.`id_fsmg_product_meta`)
            WHERE ' . $where . ' ORDER BY ' . $filter['order_by'] . ' ' . $filter['order_way'] .
            ' LIMIT ' . (int) (($filter['page'] - 1) * $filter['limit']) . ', ' . (int) $filter['limit']
        );
    }

    public static function getListCount()
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $result = Db::getInstance()->executeS(
            'SELECT `id_fsmg_product_meta` FROM `' . _DB_PREFIX_ .
            'fsmg_product_meta` WHERE `id_shop` = ' . (int) $id_shop
        );

        return count($result);
    }

    public static function isCategoryUsedByOtherSchema($id_category, $id_fsmg_product_meta)
    {
        $sql = 'SELECT `id_fsmg_product_meta` FROM `' . _DB_PREFIX_ . 'fsmg_product_meta`';
        $sql .= ' WHERE categories LIKE \'%"' . (int) $id_category;
        $sql .= '"%\' AND id_fsmg_product_meta != ' . (int) $id_fsmg_product_meta;

        return (bool) Db::getInstance()->getRow($sql);
    }

    public static function getAllCategoryUsedByOtherSchema($id_fsmg_product_meta)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $used_categories = [];
        $result = Db::getInstance()->executeS(
            'SELECT `categories` FROM `' . _DB_PREFIX_ . 'fsmg_product_meta` WHERE `id_shop` = ' . (int) $id_shop .
            ' AND id_fsmg_product_meta != ' . (int) $id_fsmg_product_meta
        );
        if ($result) {
            foreach ($result as $row) {
                $categories = json_decode($row['categories'], true);
                if (!is_array($categories)) {
                    $categories = [];
                }
                $used_categories = array_merge($used_categories, $categories);
            }
        }

        return $used_categories;
    }

    public static function getByIdCategory($id_category, $id_lang)
    {
        $context = Context::getContext();
        $id_shop = $context->shop->id;
        $sql = 'SELECT `id_fsmg_product_meta` FROM `' . _DB_PREFIX_ . 'fsmg_product_meta`';
        $sql .= ' WHERE categories LIKE \'%"' . (int) $id_category . '"%\' AND `id_shop` = ' . (int) $id_shop;

        return new FsMetaGeneratorProductMetaModel((int) Db::getInstance()->getValue($sql), $id_lang);
    }
}
