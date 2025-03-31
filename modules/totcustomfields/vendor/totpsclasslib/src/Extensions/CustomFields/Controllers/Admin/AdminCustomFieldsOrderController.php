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

class AdminCustomFieldsOrderController extends AdminCustomFieldsController
{
    protected $_defaultOrderBy = 'id_order';

    /** @var string this is the field that will be used as "name", so we can use it to build the links */
    protected $_object_name = 'orders.id_order';

    public function __construct()
    {
        parent::__construct();

        // Reorder fields; see parent class __construct for default order
        $this->fields_list['col_id_object']['title'] = $this->l('ID order', 'AdminCustomFieldsOrderController');
        $this->fields_list['col_id_object']['filter_key'] = 'orders!id_order';
        $this->fields_list['col_id_object']['filter_type'] = 'int';
        $this->fields_list['code_input_type']['fields_order'] = 3;
        $this->fields_list['input_name']['fields_order'] = 4;
        $this->fields_list['input_value']['fields_order'] = 5;
        $this->fields_list['order_reference'] = [
            'title' => $this->l('Reference', 'AdminCustomFieldsOrderController'),
            'filter_key' => 'orders!reference',
            'width' => 'auto',
            'fields_order' => 6,
        ];

        $this->actions = ['edit'];
    }

    public function renderList()
    {
        $valuesSQL = $this->getValuesSummarySQL(
            (int) Context::getContext()->language->id,
            $this->_object_name
        );

        $this->_select .= 'a.id_input, a.name AS input_name, a.code_input_type, orders.reference AS order_reference,
                          orders.id_order AS col_id_object, ';
        // Select value from input type
        $this->_select .= $valuesSQL['input_value'] . ' AS input_value ';

        // Join Orders' table to get the references
        $this->_join .= 'CROSS JOIN ' . _DB_PREFIX_ . 'orders orders ';
        // Join values tables for all input types
        $this->_join .= $valuesSQL['joins'];

        $this->_where .= "AND a.code_object = 'order'
                          AND (
                            (
                              " . $valuesSQL['value_not_null'] . "
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
     *
     * @return string|bool
     */
    protected function getViewLink($idObject)
    {
        // We can't view the order in FO, client login is needed
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopException
     */
    protected function getEditLink($idObject)
    {
        return $linkToBO = Context::getContext()->link->getAdminLink('AdminOrders') . '&vieworder&id_order=' . $idObject;
    }
}
