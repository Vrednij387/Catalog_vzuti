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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Controllers\Admin;

use TotcustomfieldsClasslib\Extensions\CustomFields\Controllers\Admin\AdminCustomFieldsController;
use Context;

class AdminCustomFieldsCategoryController extends AdminCustomFieldsController
{
    protected $_defaultOrderBy = 'id_category';

    /** @var string this is the field that will be used as "name", so we can use it to build the links */
    protected $_object_name = 'categories_l.id_category';

    public function __construct()
    {
        parent::__construct();

        // Reorder fields; see parent class __construct for default order
        $this->fields_list['col_id_object']['title'] = $this->l('ID category', 'AdminCustomFieldsCategoryController');
        $this->fields_list['col_id_object']['filter_key'] = 'categories_l!id_category';
        $this->fields_list['col_id_object']['filter_type'] = 'int';
        $this->fields_list['code_input_type']['fields_order'] = 3;
        $this->fields_list['input_name']['fields_order'] = 4;
        $this->fields_list['input_value']['fields_order'] = 5;
        $this->fields_list['category_name'] = [
            'title' => $this->l('Category', 'AdminCustomFieldsCategoryController'),
            'filter_key' => 'categories_l!name',
            'width' => 'auto',
            'fields_order' => 6,
        ];
    }

    public function renderList()
    {
        $valuesSQL = $this->getValuesSummarySQL(
            (int) Context::getContext()->language->id,
            $this->_object_name
        );

        $this->_select .= 'a.id_input, a.name AS input_name, a.code_input_type, categories_l.name AS category_name,
                          categories_l.id_category AS col_id_object, ';
        // Select value from input type
        $this->_select .= $valuesSQL['input_value'] . ' AS input_value ';

        // Join Categories' lang table to get the names
        $this->_join .= 'CROSS JOIN ' . _DB_PREFIX_ . 'category_lang categories_l ';
        // Join values tables for all input types
        $this->_join .= $valuesSQL['joins'];

        $this->_where .= "AND a.code_object = 'category'
                          AND categories_l.id_lang = " . (int) Context::getContext()->language->id . '
                          AND (
                            (
                              ' . $valuesSQL['value_not_null'] . "
                            )
                            OR
                            # If we don't have a value, but we have a default value
                            b.default_value IS NOT NULL
                            OR
                            # If we don't have a value or a default value, but the field is required
                            a.required = 1
                          )
                          AND a.active = 1
        ";

        return parent::renderList();
    }

    /**
     * {@inheritdoc}
     */
    protected function getViewLink($idObject)
    {
        return Context::getContext()->link->getCategoryLink($idObject);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopException
     */
    protected function getEditLink($idObject)
    {
        return Context::getContext()->link->getAdminLink(
            'AdminCategories',
            true,
            [],
            ['id_category' => $idObject, 'updatecategory' => 1]
        );
    }
}
