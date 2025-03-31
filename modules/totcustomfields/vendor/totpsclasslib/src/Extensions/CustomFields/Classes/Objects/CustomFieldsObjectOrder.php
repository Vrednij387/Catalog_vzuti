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
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Hook\HookAdminOrder;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Hook\HookFrontOrder;
use Context;
use Db;
use DbQuery;
use Shop;
use Tools;

class CustomFieldsObjectOrder extends CustomFieldsObject
{
    public $list_position = 3;

    /**
     * {@inheritdoc}
     */
    public function getObjectCode()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function getAdminControllerName()
    {
        return 'AdminTotcustomfieldsCustomFieldsOrder';
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectLocationName()
    {
        return $this->l('Order page', 'CustomFieldsObjectOrder');
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectTabName()
    {
        return $this->l('My orders fields', 'CustomFieldsObjectOrder');
    }

    /*TODO
     * @inheritDoc
     * @throws \SmartyException
     * @throws \PrestaShopException
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

        $tpl = Context::getContext()->smarty->createTemplate($this->getFormTemplatePath());
        $tpl->caching = 0;
        $tpl->assign([
            'totCustomFields_inputs' => $inputs,
            'id_object' => $params['id_order'],
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
        return $this->getAvailableAdminDisplayHooks();
    }

    /**
     * {@inheritdoc}
     */
    public function getFrontOfficeHooks()
    {
        return HookFrontOrder::AVAILABLE_HOOKS;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdObject()
    {
        return Tools::getValue('id_order');
    }

    /**
     * {@inheritdoc}
     */
    public function isObject()
    {
        return Context::getContext()->controller->php_self == 'order-detail';
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeTemplate()
    {
        return 'templates/customer/order-detail.tpl';
    }

    public function getFormTemplatePath()
    {
        $path = 'totcustomfields/views/templates/admin/custom_fields/objects/form/orderForm.tpl';
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
        $hooks = HookAdminOrder::AVAILABLE_HOOKS;
        $hookValues = [];
        foreach ($hooks as $hook) {
            $hookValues[$hook] = $hook;
        }

        return $hookValues;
    }
}
