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
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsObject;
use Cache;
use Context;
use Db;
use DbQuery;
use Exception;
use Shop;
use Tools;
use Validate;

/**
 * Class CustomFieldsDisplaySmarty
 */
class CustomFieldsDisplaySmarty extends CustomFieldsDisplay
{
    /**
     * {@inheritdoc}
     */
    protected static $inputSubformTemplateName = 'smarty.tpl';

    /**
     * Get subform template name, from the right folder
     *
     * @return string
     */
    public function getInputSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/displays/inputs/smarty.tpl';
    }

    public function installSQL()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputSubformData($codeObject, $idInput = null)
    {
        $tpl = $this->objectService->getThemeTemplateFromCode($codeObject);

        return [
            'objectTemplateFile' => $tpl,
        ];
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
                        ['content' => $this->l('Name', 'CustomFieldsDisplaySmarty'), 'key' => 'name'],
                        ['content' => $this->l('Type', 'CustomFieldsDisplaySmarty'), 'key' => 'type'],
                        ['content' => $this->l('Display', 'CustomFieldsDisplaySmarty'), 'key' => 'display'],
                        [
                            'content' => $this->l('Input status', 'CustomFieldsDisplaySmarty'),
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
                            'title' => $this->l('Edit', 'CustomFieldsDisplaySmarty'),
                            'action' => 'edit_input',
                            'icon' => 'pencil',
                            'img' => '../img/admin/edit.gif',
                            'fa' => 'pencil',
                        ],
                        [
                            'class' => 'deleteInputAction',
                            'title' => $this->l('Delete', 'CustomFieldsDisplaySmarty'),
                            'action' => 'delete_input',
                            'icon' => 'trash',
                            'img' => '../img/admin/delete.gif',
                            'fa' => 'trash',
                        ],
                    ],
                    'url_params' => ['configure' => 'totcustomfields'],
                    'identifier' => 'id_input',
                ]
            ),
        ];
    }

    /**
     * @return mixed
     */
    public function getDisplayCode()
    {
        return 'smarty';
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->l('Smarty display', 'CustomFieldsDisplaySmarty');
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
            'id_display_input = ' . (int) $inputDisplays[0]['id_display_input']);
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
     * @param int $idInput
     *
     * @return mixed|void
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
     * @return mixed
     */
    public function saveObjectDisplayConfiguration()
    {
        return true;
    }

    /**
     * Loads the inputs' smarty variables for the current object
     */
    public function displayInputs($codeObject)
    {
        try {
            $idObject = $this->objectService->getIdObjectFromCode($codeObject);
        } catch (Exception $e) {
            return '';
        }

        if (!$idObject) {
            return '';
        }

        try {
            try {
                // May throw an exception if cache is disabled / not configured
                $cache = Cache::getInstance();
            } catch (Exception $ex) {
                $cache = false;
            }

            $cacheKey = 'totcustomfields|' .
                'displayInputs|' .
                'display_' . $this->getDisplayCode() . '-' .
                'object_' . $codeObject . '_' . $idObject . '-' .
                'shop_' . Context::getContext()->shop->id . '-' .
                'lang_' . Context::getContext()->language->id;

            $output = [];
            if (!$cache || !$cache->exists($cacheKey)) {
                $querySelect = new DbQuery();
                $querySelect->select('tcf_i.id_input');
                $querySelect->from('totcustomfields_input', 'tcf_i');
                $querySelect->innerJoin(self::DISPLAY_INPUT_TABLE, 'tcf_di', 'tcf_di.id_input = tcf_i.id_input');
                $querySelect->where("tcf_i.code_object = '" . pSQL($codeObject) . "'");
                $querySelect->where('tcf_i.active = 1');
                $querySelect->where('tcf_di.active = 1');
                $querySelect->where("tcf_di.code_display = '" . pSQL($this->getDisplayCode()) . "'");
                $querySelect->groupBy('tcf_i.id_input');

                // Multishop; we have to remove the 'AND' from PS's query...
                $querySelect->innerJoin('totcustomfields_input_shop', 'tcf_i_s', 'tcf_i_s.id_input = tcf_i.id_input');
                $querySelect->where(str_replace('AND', '', Shop::addSqlRestriction(false, 'tcf_i_s')));

                $res = Db::getInstance()->executeS($querySelect);
                if (empty($res)) {
                    return '';
                }

                foreach ($res as $row) {
                    try {
                        $input = $this->inputService->getById($row['id_input']);
                        if (!Validate::isLoadedObject($input)) {
                            continue;
                        }

                        $output[$this->getSmartyVariableName($input)] = $input->getValueHtml($idObject);
                    } catch (Exception $e) {
                        continue;
                    }
                }

                if ($cache) {
                    $cache->set($cacheKey, $output);
                }
            } else {
                $output = $cache->get($cacheKey);
            }

            foreach ($output as $smartyVariableName => $inputHtml) {
                $tpl = Context::getContext()->smarty->createTemplate(self::getDefaultTemplate());
                $tpl->caching = 0;
                $tpl->assign('output', [$inputHtml]);
                $html = $tpl->fetch();
                // Unescape single quotes that seems to be automatically escaped by Smarty fetch(),
                // even when setting no_output_filter to true.
                $html = str_replace("\'", "'", $html);
                Context::getContext()->smarty->assign($smartyVariableName, $html);
            }
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Returns the name of the smarty variable for the input
     *
     * @param CustomFieldsInput $input
     *
     * @return string
     */
    protected function getSmartyVariableName($input)
    {
        return 'totcustomfields_display_' . $input->code;
    }
}
