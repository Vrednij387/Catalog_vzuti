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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays;

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsDisplay;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use Db;
use DbQuery;
use Tools;

/**
 * Class CustomFieldsDisplayBackOfficeOnly
 */
class CustomFieldsDisplayBackOfficeOnly extends CustomFieldsDisplay
{
    /**
     * {@inheritdoc}
     */
    public function installSQL()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputSubformData($codeObject, $idInput = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopDatabaseException
     */
    public function getObjectSubformData($inputs)
    {
        $rows = [];
        /** @var CustomFieldsInput $input */
        foreach ($inputs as $input) {
            // Basically, check if this input is using this display
            if ($this->getInputDisplays($input->id, true)) {
                $rows[] = [
                    'id_input' => $input->id,
                    'name' => $input->name,
                    'type' => $input->getInputTypeName(),
                    'active' => $input->active,
                    'display' => $input->code,
                ];
            }
        }

        return [
            'icon' => 'icon-cogs',
            'tableContent' => json_encode([
                'columns' => [
                    ['content' => $this->l('Name', 'CustomFieldsDisplayBackOfficeOnly'), 'key' => 'name'],
                    ['content' => $this->l('Type', 'CustomFieldsDisplayBackOfficeOnly'), 'key' => 'type'],
                    ['content' => $this->l('Display', 'CustomFieldsDisplayBackOfficeOnly'), 'key' => 'display'],
                    [
                        'content' => $this->l('Input status', 'CustomFieldsDisplayBackOfficeOnly'),
                        'key' => 'active',
                        'bool' => true,
                        'link' => '#%id_input%',
                        'center' => true,
                        'class' => 'toggleActiveDisplay',
                    ],
                ],
                'rows' => $rows,
                'rows_actions' => [
                    [
                        'title' => $this->l('Edit', 'CustomFieldsDisplayBackOfficeOnly'),
                        'action' => 'edit_input',
                        'icon' => 'pencil',
                        'img' => '../img/admin/edit.gif',
                        'fa' => 'pencil',
                    ],
                    [
                        'class' => 'deleteInputAction',
                        'title' => $this->l('Delete', 'CustomFieldsDisplayBackOfficeOnly'),
                        'action' => 'delete_input',
                        'icon' => 'trash',
                        'img' => '../img/admin/delete.gif',
                        'fa' => 'trash',
                    ],
                ],
                'url_params' => ['configure' => 'totcustomfields'],
                'identifier' => 'id_input',
            ]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayCode()
    {
        return 'bo_only';
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        return $this->l('None (Back Office only)', 'CustomFieldsDisplayBackOfficeOnly');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopDatabaseException
     */
    public function saveInputDisplayMethod($input, $displayParams, $codeAdminDisplay)
    {
        // Reset previous input display configuration
        Db::getInstance()->update(
            self::DISPLAY_INPUT_TABLE,
            ['active' => 0],
            'id_input = ' . (int) $input->id
        );

        // Update current configuration
        $inputDisplays = $this->getInputDisplays($input->id);
        if ($inputDisplays === false) {
            // If the request failed
            return false;
        } elseif (empty($inputDisplays)) {
            // If the input has no display yet
            return (bool) $this->displayService->insertDisplayInput($input->id, $this->getDisplayCode(), $codeAdminDisplay, true);
        }
        // If there's a display, update it
        return Db::getInstance()->update(
            self::DISPLAY_INPUT_TABLE,
            [
                'active' => 1,
                'code_admin_display' => $codeAdminDisplay,
            ],
            'id_display_input = ' . (int) $inputDisplays[0]['id_display_input']
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopDatabaseException
     */
    public function deleteInputDisplay($idInput)
    {
        $inputDisplays = $this->getInputDisplays($idInput);
        $id_display_input_array = [];
        foreach ($inputDisplays as $row) {
            $id_display_input_array[] = (int) $row['id_display_input'];
        }
        Db::getInstance()->delete(
            self::DISPLAY_INPUT_TABLE,
            '`id_display_input` IN (' . implode(',', $id_display_input_array) . ')'
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return array|bool
     *
     * @throws \PrestaShopDatabaseException
     */
    protected function getInputDisplays($id_input, $active = false)
    {
        $query = new DbQuery();
        $query->select('tcf_di.*');
        $query->FROM(self::DISPLAY_INPUT_TABLE, 'tcf_di');
        $query->where('id_input = ' . (int) $id_input);
        $query->where("code_display = '" . pSQL($this->getDisplayCode()) . "'");

        if ($active) {
            $query->where('active = 1');
        }

        return Db::getInstance()->executeS($query);
    }

    /**
     * {@inheritdoc}
     */
    public function saveObjectDisplayConfiguration()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputSubformTemplatePath()
    {
        return false;
    }
}
