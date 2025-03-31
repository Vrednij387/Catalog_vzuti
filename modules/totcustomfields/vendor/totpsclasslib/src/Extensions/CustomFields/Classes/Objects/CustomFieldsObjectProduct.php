<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL 202 ecommerce
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL 202 ecommerce is strictly forbidden.
 * In order to obtain a license, please contact us: tech@202-ecommerce.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe 202 ecommerce
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL 202 ecommerce est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter 202-ecommerce <tech@202-ecommerce.com>
 * ...........................................................................
 *
 * @author    202-ecommerce <tech@202-ecommerce.com>
 * @copyright Copyright (c) 202-ecommerce
 * @license   Commercial license
 */

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Objects;

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsObject;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Hook\HookAdminProduct;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Hook\HookFrontProduct;
use Context;
use Db;
use DbQuery;
use Shop;
use Tools;

class CustomFieldsObjectProduct extends CustomFieldsObject
{
    const OBJECT_CODE = 'product';

    public $list_position = 1;

    /**
     * {@inheritdoc}
     */
    public function getObjectCode()
    {
        return self::OBJECT_CODE;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminControllerName()
    {
        return 'AdminTotcustomfieldsCustomFieldsProduct';
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectLocationName()
    {
        return $this->l('Product page', 'CustomFieldsObjectProduct');
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectTabName()
    {
        return $this->l('My products fields', 'CustomFieldsObjectProduct');
    }

    /*TODO
     * @inheritdoc
     * @param $params
     * @return string
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function displayBackOfficeInputs($params, $hookName)
    {
        $query = new DbQuery();
        $query->select('tcf_input.id_input');
        $query->from('totcustomfields_input', 'tcf_input');
        $query->where("code_object = '" . pSQL($this->getObjectCode()) . "'");
        $query->where('tcf_input.active = 1');

        $query->innerJoin('totcustomfields_display_input', 'tcfdi', 'tcf_input.id_input = tcfdi.id_input');
        $query->where('tcfdi.code_admin_display = \'' . pSQL($hookName) . '\'');

        // Multishop; we have to remove the 'AND' from PS's query...
        $query->innerJoin('totcustomfields_input_shop', 'tcf_input_shop', 'tcf_input_shop.id_input = tcf_input.id_input');
        $query->where(str_replace('AND', '', Shop::addSqlRestriction(false, 'tcf_input_shop')));
        $query->groupBy('tcf_input.id_input');

        $sqlResult = Db::getInstance()->executeS($query);
        if (!$sqlResult) {
            return '';
        }

        $inputs = [];
        foreach ($sqlResult as $row) {
            $input = $this->inputService->getById($row['id_input']);
            if ($input) {
                $inputs[] = $input;
            }
        }

        $idObject = null;
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $idObject = $params['id_product'];
        } else {
            $idObject = $this->getIdObject();
        }

        $tpl = Context::getContext()->smarty->createTemplate($this->getFormTemplatePath());
        $tpl->caching = 0;
        $tpl->assign([
            'totCustomFields_title' => $this->l('Custom fields', 'CustomFieldsObjectProduct'),
            'totCustomFields_inputs' => $inputs,
            'id_object' => $idObject,
            'cancel_link' => Context::getContext()->link->getAdminLink('AdminProducts', true),
            'code_object' => $this->getObjectCode(),
            'ajax_url' => Context::getContext()->link->getAdminLink('AdminTotcustomfieldsCustomFieldsConfiguration', true),
        ]);

        return $tpl->fetch();
    }

    /**
     * {@inheritdoc}
     */
    public function getBackOfficeHooks()
    {
        // Data are processed with AJAX on PS17
        return $this->getAvailableAdminDisplayHooks();
    }

    /**
     * {@inheritdoc}
     */
    public function getFrontOfficeHooks()
    {
        return array_filter(array_merge(HookFrontProduct::AVAILABLE_HOOKS, ['displayFooter']), function ($hook) {
            if (version_compare(_PS_VERSION_, '1.7.6', '<') && $hook == 'displayProductActions') {
                return false;
            }

            return true;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getIdObject()
    {
        return Tools::getValue('id_product');
    }

    /**
     * {@inheritdoc}
     */
    public function isObject()
    {
        return Context::getContext()->controller->php_self == 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeTemplate()
    {
        return 'templates/catalog/product.tpl';
    }

    public function getFormTemplatePath()
    {
        $path = 'totcustomfields/views/templates/admin/custom_fields/objects/form/productForm.tpl';
        $overrideFile = _PS_THEME_DIR_ . 'modules/' . $path;
        if (file_exists($overrideFile)) {
            return $overrideFile;
        }

        return _PS_MODULE_DIR_ . $path;
    }

    /**
     * @return array
     */
    public function getAvailableAdminDisplayHooks()
    {
        $hooks = HookAdminProduct::AVAILABLE_HOOKS;
        $hookValues = [];
        foreach ($hooks as $hook) {
            $hookValue = $hook;
            if ($hook == 'displayAdminProductsExtra') {
                $hookValue = $hook . $this->l(' (recommended)', 'CustomFieldsObjectProduct');
            }

            $hookValues[$hook] = $hookValue;
        }

        return $hookValues;
    }
}
