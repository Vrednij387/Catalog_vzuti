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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputService;
use TotcustomfieldsClasslib\Utils\Translate\TranslateTrait;
use Context;
use HelperList;
use ModuleAdminController;

/**
 * Class AdminTotCustomFieldsController
 *
 * The base of every AdminTotCustomFieldsObjectController
 */
abstract class AdminCustomFieldsController extends ModuleAdminController
{
    use TranslateTrait;

    /** @var string this is the field that will be used as "name", so we can use it to build the links */
    protected $_object_name = '';

    /** {@inheritdoc} */
    protected $list_no_link = true;

    public $lang = true;

    protected $inputService;

    /**
     * @var string
     */
    protected $_filter;

    public function __construct()
    {
        $this->table = 'totcustomfields_input';
        $this->identifier = 'id_input';
        $this->_select = $this->_object_name . ' AS name, ';
        $this->_group = 'GROUP BY a.id_input, col_id_object';
        $this->inputService = new CustomFieldsInputService();

        // Specify this to pass the multishop test, but be careful as it's an abstract class...
        $this->className = CustomFieldsInput::class;

        $this->bootstrap = true;

        $this->actions = ['edit', 'view'];

        // Set fields here so they're available for subclasses
        $this->fields_list['id_input'] = [
            'title' => $this->l('ID input', 'AdminCustomFieldsController'),
            'filter_type' => 'int',
            'align' => 'center',
            'width' => 'auto',
            'fields_order' => 1,
        ];

        $this->fields_list['col_id_object'] = [
            'title' => '', // This should be changed by the subclass
            'filter_key' => '', // This should be changed by the subclass
            'align' => 'center',
            'width' => 'auto',
            'fields_order' => 2,
        ];

        $this->fields_list['input_name'] = [
            'title' => $this->l('Field name', 'AdminCustomFieldsController'),
            'filter_key' => 'a!name',
            'width' => 'auto',
            'fields_order' => 3,
        ];

        $this->fields_list['code_input_type'] = [
            'title' => $this->l('Type', 'AdminCustomFieldsController'),
            'width' => 'auto',
            'fields_order' => 4,
        ];

        $this->fields_list['input_value'] = [
            'title' => $this->l('Value', 'AdminCustomFieldsController'),
            'width' => 'auto',
            'fields_order' => 5,
            'float' => true, // Allows to display value as HTML; escape it if needed using TotCustomFieldsInput::formatValueForAdminSummary
        ];

        parent::__construct();
    }

    protected function getValuesSummarySQL($idLang, $colIdObject)
    {
        $inputs = array_filter($this->inputService->getInputTypes(), function ($input) {
            return preg_match('/^[a-zA-Z]+$/', $input['code']);
        });

        $summarySql = [
            'input_value' => 'CASE a.code_input_type ',
            'joins' => '',
            'value_not_null' => 'CASE a.code_input_type ',
        ];

        foreach ($inputs as $input) {
            $code = $input['code'];
            $summarySql['input_value'] .= " WHEN '$code' THEN IF(tiv_$code.value IS NULL, b.default_value, tiv_$code.value) ";
            $summarySql['value_not_null'] .= " WHEN '$code' THEN tiv_$code.value IS NOT NULL";
            $summarySql['joins'] .= '
                    LEFT JOIN `' . _DB_PREFIX_ . 'totcustomfields_input_' . $code . '_value` tiv_' . $code . ' 
                    ON tiv_' . $code . '.id_input = a.id_input
                    AND tiv_' . $code . '.id_object = ' . (int) $colIdObject . '
                    AND (
                        (a.is_translatable = 0 AND tiv_' . $code . '.id_lang IS NULL)
                        OR
                        (a.is_translatable = 1 AND tiv_' . $code . '.id_lang = ' . (int) $idLang . ')
                  ) 
            ';
        }

        $summarySql['input_value'] .= ' 
                ELSE 
                b.default_value
                END';
        $summarySql['value_not_null'] .= ' 
                END
        ';

        return $summarySql;
    }

    public function renderList()
    {
        uasort($this->fields_list, [$this, 'sortFieldsList']);

        // Delete new
        unset($this->toolbar_btn['new']);

        $lists = parent::renderList();
        parent::initToolbar();

        return $lists;
    }

    /**
     * We need to override this in order to process the values if necessary
     * "@throws \PrestaShopException
     */
    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        // Retrieve original list
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        // Edit the retrieved list
        foreach ($this->_list as &$row) {
            $row['input_value'] = $this->inputService->formatInputValueForAdminSummary(
                $row['code_input_type'],
                $row['id_input'],
                $row['input_value']
            );
        }
    }

    protected function sortFieldsList($a, $b)
    {
        if (!isset($a['fields_order'])) {
            return -1;
        }

        if (!isset($b['fields_order'])) {
            return 1;
        }

        return $a['fields_order'] < $b['fields_order'] ? -1 : 1;
    }

    /**
     * Returns the link for a row's 'view' action
     *
     * @param int $idObject the 'id_object' field from the corresponding row
     *
     * @return string
     */
    abstract protected function getViewLink($idObject);

    /**
     * Returns the link for a row's 'edit' action
     *
     * @param int $idObject the 'id_object' field from the corresponding row
     *
     * @return string
     */
    abstract protected function getEditLink($idObject);

    /**
     * Displays the link for a row's 'view' action <br/>
     * This method is called natively by the HelperList
     *
     * @param string $token the admin token
     * @param int $id the row id, as set in $this->identifier
     * @param string|int $name
     *
     * @return mixed
     *
     * @throws \SmartyException
     */
    public function displayViewLink($token, $id, $name)
    {
        $tpl = $this->helper->createTemplate('list_action_view.tpl');
        $tpl->caching = 0;
        if (!array_key_exists('View', HelperList::$cache_lang)) {
            HelperList::$cache_lang['View'] = $this->l('View', 'AdminCustomFieldsController');
        }

        $tpl->assign([
            'href' => $this->getViewLink($name),
            'action' => HelperList::$cache_lang['View'],
        ]);

        return $tpl->fetch();
    }

    /**
     * Displays the link for a row's 'edit' action <br/>
     * This method is called natively by the HelperList
     *
     * @param string $token the admin token
     * @param int $id the row id, as set in $this->identifier
     * @param string|int $name
     *
     * @return mixed
     *
     * @throws \SmartyException
     */
    public function displayEditLink($token, $id, $name)
    {
        $tpl = $this->helper->createTemplate('list_action_edit.tpl');
        $tpl->caching = 0;
        if (!array_key_exists('Edit', HelperList::$cache_lang)) {
            HelperList::$cache_lang['Edit'] = $this->l('Edit', 'AdminCustomFieldsController');
        }

        $tpl->assign([
            'href' => $this->getEditLink($name),
            'action' => HelperList::$cache_lang['Edit'],
        ]);

        return $tpl->fetch();
    }

    /**
     * Input values fields have a different treatment; return false so Prestashop won't check the field
     *
     * @param string $key
     * @param string $filter
     *
     * @return bool
     */
    protected function filterToField($key, $filter)
    {
        if ($key == 'input_value') {
            return false;
        }

        return parent::filterToField($key, $filter);
    }

    /**
     * Apply special treatment for input_value filter
     */
    public function processFilter()
    {
        parent::processFilter();

        $filterValue = $this->context->cookie->{$this->getCookieFilterPrefix() . $this->list_id . 'Filter_input_value'};
        if (!$filterValue) {
            return;
        }

        $valuesSQL = $this->getValuesSummarySQL(
            (int) Context::getContext()->language->id,
            $this->_object_name
        );

        $this->_filter .= ' AND (' . $valuesSQL['input_value'] . ') LIKE \'%' . pSQL(trim($filterValue), true) . '%\'';
    }
}
