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

class AdminCustomFieldsProductController extends AdminCustomFieldsController
{
    protected $_defaultOrderBy = 'id_product';

    /** @var string this is the field that will be used as "name", so we can use it to build the links */
    protected $_object_name = 'products_l.id_product';

    public function __construct()
    {
        parent::__construct();

        // Reorder fields; see parent class __construct for default order
        $this->fields_list['col_id_object']['title'] = $this->l('ID product', 'AdminCustomFieldsProductController');
        $this->fields_list['col_id_object']['filter_key'] = 'products_l!id_product';
        $this->fields_list['col_id_object']['filter_type'] = 'int';
        $this->fields_list['code_input_type']['fields_order'] = 3;
        $this->fields_list['input_name']['fields_order'] = 4;
        $this->fields_list['input_value']['fields_order'] = 5;
        $this->fields_list['product_name'] = [
            'title' => $this->l('Product', 'AdminCustomFieldsProductController'),
            'filter_key' => 'products_l!name',
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

        $this->_select .= 'a.id_input, a.name AS input_name, a.code_input_type, products_l.name AS product_name,
                          products_l.id_product AS col_id_object, ';
        // Select value from input type
        $this->_select .= $valuesSQL['input_value'] . ' AS input_value ';

        // Join Products' lang table to get the names
        $this->_join .= 'CROSS JOIN ' . _DB_PREFIX_ . 'product_lang products_l ';
        // Join values tables for all input types
        $this->_join .= $valuesSQL['joins'];

        $this->_where .= "AND a.code_object = 'product'
                          AND products_l.id_lang = " . (int) Context::getContext()->language->id . '
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
     * @param int $idObject
     *
     * @return string
     *
     * @throws \PrestaShopException
     */
    protected function getViewLink($idObject)
    {
        return Context::getContext()->link->getProductLink($idObject);
    }

    /**
     * @param int $id_object
     *
     * @return string
     *
     * @throws \PrestaShopException
     */
    protected function getEditLink($id_object)
    {
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            return Context::getContext()->link->getAdminLink(
                'AdminProducts',
                true,
                [
                    'updateproduct',
                    'id_product' => $id_object,
                ]
            ) . '#tab-hooks';
        }

        return Context::getContext()->link->getAdminLink('AdminProducts') . '&updateproduct&key_tab=ModuleTotcustomfields&id_product=' . $id_object;
    }
}
