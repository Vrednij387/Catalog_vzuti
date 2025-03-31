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
class Meta extends MetaCore
{
    public static function getProductMetas($id_product, $id_lang, $page_name)
    {
        $result = parent::getProductMetas($id_product, $id_lang, $page_name);

        if (Module::isEnabled('fsmetagenerator')) {
            $fsmg = Module::getInstanceByName('fsmetagenerator');
            $result = $fsmg->metaGetProductMetas($id_product, $id_lang, $page_name, $result);
        }

        return $result;
    }

    public static function getCategoryMetas($id_category, $id_lang, $page_name, $title = '')
    {
        $result = parent::getCategoryMetas($id_category, $id_lang, $page_name, $title);

        if (Module::isEnabled('fsmetagenerator')) {
            $fsmg = Module::getInstanceByName('fsmetagenerator');
            $result = $fsmg->metaGetCategoryMetas($id_category, $id_lang, $page_name, $title, $result);
        }

        return $result;
    }

    public static function getManufacturerMetas($id_manufacturer, $id_lang, $page_name)
    {
        $result = parent::getManufacturerMetas($id_manufacturer, $id_lang, $page_name);

        if (Module::isEnabled('fsmetagenerator')) {
            $fsmg = Module::getInstanceByName('fsmetagenerator');
            $result = $fsmg->metaGetManufacturerMetas($id_manufacturer, $id_lang, $page_name, $result);
        }

        return $result;
    }

    public static function getSupplierMetas($id_supplier, $id_lang, $page_name)
    {
        $result = parent::getSupplierMetas($id_supplier, $id_lang, $page_name);

        if (Module::isEnabled('fsmetagenerator')) {
            $fsmg = Module::getInstanceByName('fsmetagenerator');
            $result = $fsmg->metaGetSupplierMetas($id_supplier, $id_lang, $page_name, $result);
        }

        return $result;
    }

    public static function getCmsMetas($id_cms, $id_lang, $page_name)
    {
        $result = parent::getCmsMetas($id_cms, $id_lang, $page_name);

        if (Module::isEnabled('fsmetagenerator')) {
            $fsmg = Module::getInstanceByName('fsmetagenerator');
            $result = $fsmg->metaGetCmsMetas($id_cms, $id_lang, $page_name, $result);
        }

        return $result;
    }

    public static function getCmsCategoryMetas($id_cms_category, $id_lang, $page_name)
    {
        $result = parent::getCmsCategoryMetas($id_cms_category, $id_lang, $page_name);

        if (Module::isEnabled('fsmetagenerator')) {
            $fsmg = Module::getInstanceByName('fsmetagenerator');
            $result = $fsmg->metaGetCmsCategoryMetas($id_cms_category, $id_lang, $page_name, $result);
        }

        return $result;
    }
}
