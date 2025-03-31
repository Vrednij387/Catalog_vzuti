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
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Hook\HookAdminCategory;
use Context;
use Db;
use DbQuery;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use Shop;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Tools;

class CustomFieldsObjectCategory extends CustomFieldsObject
{
    public $list_position = 2;

    /**
     * {@inheritdoc}
     */
    public function getObjectCode()
    {
        return 'category';
    }

    /**
     * @return mixed
     */
    public function getAdminControllerName()
    {
        return 'AdminTotcustomfieldsCustomFieldsCategory';
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectLocationName()
    {
        return $this->l('Category page', 'CustomFieldsObjectCategory');
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectTabName()
    {
        return $this->l('My categories fields', 'CustomFieldsObjectCategory');
    }

    /*TODO
     * @inheritdoc
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

        if (!defined('ADMIN_LEGACY_CONTEXT') && defined('_PS_ADMIN_DIR_')) {
            /** @var RequestStack $requestStack */
            $requestStack = SymfonyContainer::getInstance()->get('request_stack');
            $idObject = (int) $requestStack->getCurrentRequest()->get('categoryId');
        } else {
            $idObject = (int) Tools::getValue('id_category');
        }

        $tpl->assign([
            'totCustomFields_inputs' => $inputs,
            'id_object' => $idObject,
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
        return [
            'displayLeftColumn',
            'displayRightColumn',
            'displayFooter',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdObject()
    {
        return Tools::getValue('id_category');
    }

    /**
     * {@inheritdoc}
     */
    public function isObject()
    {
        return Context::getContext()->controller->php_self == 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function getThemeTemplate()
    {
        return 'templates/catalog/listing/category.tpl';
    }

    public function getFormTemplatePath()
    {
        $path = 'totcustomfields/views/templates/admin/custom_fields/objects/form/categoryForm.tpl';
        $overrideFile = _PS_THEME_DIR_ . 'modules/' . $path;
        if (file_exists($overrideFile)) {
            return $overrideFile;
        }

        return _PS_MODULE_DIR_ . $path;
    }

    public function getAvailableAdminDisplayHooks()
    {
        $hooks = HookAdminCategory::AVAILABLE_HOOKS;
        $hookValues = [];
        foreach ($hooks as $hook) {
            $hookValues[$hook] = $hook;
        }

        return $hookValues;
    }
}
