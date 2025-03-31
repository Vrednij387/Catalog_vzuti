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
use TotcustomfieldsClasslib\Extensions\CustomFields\CustomFieldsExtension;
use Configuration;
use Context;
use Db;
use DbQuery;
use Exception;
use Hook;
use PrestaShop\PrestaShop\Core\Product\ProductExtraContent;
use Shop;
use Tools;
use Validate;

/**
 * Class CustomFieldsDisplayNativeHooks
 *
 * @include 'totcustomfields/views/templates/front/custom_fields/displays/hooks/displayLeftColumn/hook.tpl'
 * @include 'totcustomfields/views/templates/front/custom_fields/displays/hooks/displayOrderDetail/hook.tpl'
 */
class CustomFieldsDisplayNativeHooks extends CustomFieldsDisplay
{
    const DISPLAY_NATIVE_HOOKS_TABLE = 'totcustomfields_display_input_native_hooks';

    /**
     * @var string
     *             This is only used when not using multiple product tabs :
     *             - Make sure the tab title is right before the tab content
     *             - Prevent tab title display when tab content is empty
     */
    protected $tabTitle = '';

    /**
     * Get subform template name, from the right folder
     *
     * @return string
     */
    public function getInputSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/displays/inputs/native_hooks.tpl';
    }

    public function installSQL()
    {
        $sql = '
         CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . self::DISPLAY_NATIVE_HOOKS_TABLE . '` (
         `id_display_input` INT(11) NOT NULL PRIMARY KEY,
		 `hook` VARCHAR(64) NOT NULL,
		 `position` INT(11) NOT NULL,
		 INDEX `hook_position` (`hook`, `position`)
		 ) ENGINE = ' . _MYSQL_ENGINE_ . ' CHARACTER SET utf8 COLLATE utf8_general_ci;
        ';

        return Db::getInstance()->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayCode()
    {
        return 'native_hooks';
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayName()
    {
        return $this->l('Simple display (native hooks)', 'CustomFieldsDisplayNativeHooks');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function getInputSubformData($codeObject, $idInput = null)
    {
        $hooks = $this->objectService->getFrontOfficeHooksFromCode($codeObject);
        $hooksConfig = [];
        foreach ($hooks as $hookName) {
            $hook = new Hook(Hook::getIdByName($hookName));
            if (Validate::isLoadedObject($hook)) {
                $hooksConfig[$hookName] = [
                    'name' => $hookName,
                    'title' => !empty($hook->title) ? $hook->title : $hookName,
                ];
            }
        }

        if ($idInput != null) {
            $inputDisplays = $this->getInputDisplays($idInput);
            foreach ($inputDisplays as $input_display) {
                if ($input_display['active'] && isset($hooksConfig[$input_display['hook']])) {
                    $hooksConfig[$input_display['hook']]['selected'] = true;
                }
            }
        }

        return [
            'hooks' => $hooksConfig,
            'code_display' => $this->getDisplayCode(),
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
            $newRow = [
                'id_input' => $input->id,
                'name' => $input->name,
                'type' => $input->getInputTypeName(),
                'active' => $input->active,
            ];

            foreach ($this->getInputDisplays($input->id, true) as $input_display) {
                $newRow['position'] = '<input type=\'text\' value="' . $input_display['position'] . '" name="display_input_position[' . $input_display['id_display_input'] . ']"/>';
                $newRow['display'] = $input_display['hook'];
                $rows[] = $newRow;
            }
        }

        usort($rows, function ($r1, $r2) {
            if ($r1['display'] == $r2['display']) {
                return $r1['position'] < $r2['position'] ? -1 : 1;
            }

            return $r1['display'] < $r2['display'] ? -1 : 1;
        });

        return [
            'icon' => 'icon-cogs',
            'tableContent' => json_encode([
                'columns' => [
                    ['content' => $this->l('Name', 'CustomFieldsDisplayNativeHooks'), 'key' => 'name'],
                    ['content' => $this->l('Type', 'CustomFieldsDisplayNativeHooks'), 'key' => 'type'],
                    ['content' => $this->l('Position', 'CustomFieldsDisplayNativeHooks'), 'key' => 'position', 'raw' => true],
                    ['content' => $this->l('Display', 'CustomFieldsDisplayNativeHooks'), 'key' => 'display'],
                    [
                        'content' => $this->l('Input status', 'CustomFieldsDisplayNativeHooks'),
                        'key' => 'active',
                        'bool' => true,
                        'link' => '#%id_input%',
                        'center' => true,
                        'class' => 'toggleActiveDisplay',
                    ],
                ],
                'rows' => $rows,
                'rows_actions' => [
                    ['title' => $this->l('Edit', 'CustomFieldsDisplayNativeHooks'),
                        'action' => 'edit_input',
                        'icon' => 'pencil',
                        'img' => '../img/admin/edit.gif',
                        'fa' => 'pencil',
                    ],
                    [
                        'class' => 'deleteInputAction',
                        'title' => $this->l('Delete', 'CustomFieldsDisplayNativeHooks'),
                        'action' => 'delete_input',
                        'icon' => 'trash',
                        'img' => '../img/admin/delete.gif',
                        'fa' => 'trash', ],
                ],
                'url_params' => ['configure' => 'totcustomfields'],
                'identifier' => 'id_input',
            ]),
        ];
    }

    /**
     * @return mixed
     *
     * @throws \PrestaShopDatabaseException
     */
    public function saveObjectDisplayConfiguration()
    {
        $inputs_positions = Tools::getValue('display_input_position');
        if (empty($inputs_positions)) {
            return true;
        }

        // Save positions individually
        foreach ($inputs_positions as $id_display_input => $position) {
            $ret = Db::getInstance()->update(self::DISPLAY_NATIVE_HOOKS_TABLE, ['position' => (int) $position], 'id_display_input = ' . (int) $id_display_input);
            if (!$ret) {
                return false;
            }
        }

        // Reorder everything
        $query = new DbQuery();
        $query->select('tcf_di_nh.id_display_input, tcf_di_nh.hook, tcf_di_nh.position, tcf_i.code_object');
        $query->from(self::DISPLAY_NATIVE_HOOKS_TABLE, 'tcf_di_nh');
        $query->innerJoin(self::DISPLAY_INPUT_TABLE, 'tcf_di', 'tcf_di.id_display_input = tcf_di_nh.id_display_input');
        $query->innerJoin('totcustomfields_input', 'tcf_i', 'tcf_i.id_input = tcf_di.id_input');
        $query->where('tcf_di.active <> 0');
        $query->orderBy('code_object ASC, hook ASC, position ASC');

        $sqlRes = Db::getInstance()->executeS($query);

        if (!$sqlRes) {
            return false;
        }

        $pos = 1;
        $prevHook = '';
        $prevObj = '';

        foreach ($sqlRes as $row) {
            if ($row['code_object'] != $prevObj) {
                $prevObj = $row['code_object'];
                $pos = 1;
            }
            if ($row['hook'] != $prevHook) {
                $prevHook = $row['hook'];
                $pos = 1;
            }

            db::getInstance()->update(
                self::DISPLAY_NATIVE_HOOKS_TABLE,
                ['position' => (int) $pos],
                'id_display_input = ' . (int) $row['id_display_input']
            );
            ++$pos;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopDatabaseException
     */
    public function saveInputDisplayMethod($input, $displayParams, $codeAdminDisplay)
    {
        $availableHooks = $this->objectService->getFrontOfficeHooksFromCode($input->code_object);
        if (!$availableHooks) {
            return [$this->l('No hooks found for this object.', 'CustomFieldsDisplayNativeHooks')];
        }

        if (empty($displayParams['hooks']) || !is_array($displayParams['hooks'])) {
            return [$this->l('You must specify at least one hook to be used.', 'CustomFieldsDisplayNativeHooks')];
        }

        if (!$codeAdminDisplay) {
            return [$this->l('You must specify an admin hook.', 'CustomFieldsDisplayNativeHooks')];
        }

        // Keep only valid hooks
        $hooks = array_intersect($availableHooks, $displayParams['hooks']);
        if (!$hooks) {
            return [$this->l('You must specify at least one hook to be used.', 'CustomFieldsDisplayNativeHooks')];
        }

        // Reset previous input display configuration
        Db::getInstance()->update(
            self::DISPLAY_INPUT_TABLE,
            ['active' => 0],
            'id_input = ' . (int) $input->id
        );

        // Update current configuration
        $inputDisplays = $this->getInputDisplays($input->id);
        if ($inputDisplays) {
            foreach ($inputDisplays as $input_display) {
                Db::getInstance()->update(
                    self::DISPLAY_INPUT_TABLE,
                    [
                        'active' => in_array($input_display['hook'], $hooks) ? 1 : 0,
                        'code_admin_display' => $codeAdminDisplay,
                    ],
                    'id_display_input = ' . (int) $input_display['id_display_input']
                );
                // We updated this hook; remove it from the hooks array
                $hooks = array_diff($hooks, [$input_display['hook']]);
            }
        }

        // Add hooks that weren't updated
        if (count($hooks)) {
            $insertData = [];
            foreach ($hooks as $hookName) {
                $id_display_input = $this->displayService->insertDisplayInput($input->id, $this->getDisplayCode(), $codeAdminDisplay, true);
                if (!$id_display_input) {
                    continue;
                }
                $insertData[] = [
                    'id_display_input' => (int) $id_display_input,
                    'hook' => pSQL($hookName),
                    'position' => (int) ($this->getHookMaxPosition($hookName, $input->code_object) + 1),
                ];
            }
            Db::getInstance()->insert(self::DISPLAY_NATIVE_HOOKS_TABLE, $insertData);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopDatabaseException
     */
    protected function getInputDisplays($id_input, $active = false)
    {
        $query = new DbQuery();
        $query->select('tcf_di_nh.*, tcf_di.id_input, tcf_di.code_display, tcf_di.active');
        $query->from(self::DISPLAY_NATIVE_HOOKS_TABLE, 'tcf_di_nh');
        $query->innerJoin(self::DISPLAY_INPUT_TABLE, 'tcf_di', 'tcf_di.id_display_input = tcf_di_nh.id_display_input');
        $query->where('id_input = ' . (int) $id_input);

        if ($active) {
            $query->where('active = 1');
        }

        $query->orderBy('hook ASC, position ASC');

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
        $idDisplayInputArray = [];
        foreach ($inputDisplays as $row) {
            $idDisplayInputArray[] = (int) $row['id_display_input'];
        }

        Db::getInstance()->delete(
            self::DISPLAY_NATIVE_HOOKS_TABLE,
            '`id_display_input` IN (' . implode(',', $idDisplayInputArray) . ')'
        );
        Db::getInstance()->delete(
            self::DISPLAY_INPUT_TABLE,
            '`id_display_input` IN (' . implode(',', $idDisplayInputArray) . ')'
        );
    }

    /**
     * Gets the max position for a given hook and object
     *
     * @param string $hook
     * @param string $code_object
     *
     * @return int
     */
    protected function getHookMaxPosition($hook, $code_object)
    {
        $query = new DbQuery();
        $query->select('MAX(tcf_di_nh.position) AS maxPos');
        $query->from(self::DISPLAY_NATIVE_HOOKS_TABLE, 'tcf_di_nh');
        $query->innerJoin(self::DISPLAY_INPUT_TABLE, 'tcf_di', 'tcf_di.id_display_input = tcf_di_nh.id_display_input');
        $query->innerJoin('totcustomfields_input', 'tcf_i', 'tcf_i.id_input = tcf_di.id_input');
        $query->where("tcf_di_nh.hook = '" . pSQL($hook) . "'");
        $query->where("tcf_i.code_object = '" . pSQL($code_object) . "'");

        $max = Db::getInstance()->getValue($query);

        return $max ? (int) $max : 0;
    }

    protected function getDisplayTemplatesFolder()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/front/custom_fields/displays/hooks/';
    }

    protected function getHookTemplatesFolder($hookName, $codeObject)
    {
        $path = $this->getDisplayTemplatesFolder();

        if (is_dir($path . $hookName)) {
            $path .= $hookName . '/';
        }

        if (is_dir($path . $codeObject)) {
            $path .= $codeObject . '/';
        }

        return $path;
    }

    /**
     * Retrieves a custom template for a hook or an input <br/>
     * Folders are organized like : front/displays/hooks/[hook_name]/[object_code]/[$tpl_name] <br/>
     * Default template for a hook is 'hook.tpl', '[input_type_code].tpl' for an input (see TotCustomFieldsDisplayNativeHooks::displayInputs) <br/>
     * Checks are made from deepest folder to the highest; function will return whenever a template is found
     *
     * @param string $hookName
     * @param string $codeObject
     * @param string $tplName
     *
     * @return bool|string
     */
    protected function getCustomTemplate($hookName, $codeObject, $tplName)
    {
        $basePath = $this->getDisplayTemplatesFolder();
        $path = $this->getHookTemplatesFolder($hookName, $codeObject);

        while ($path != $basePath) {
            if (Tools::file_exists_cache($path . $tplName . '.tpl')) {
                return $path . $tplName . '.tpl';
            }
            // Remove last folder from path
            preg_match('#(.+\/)+?.+\/$#', $path, $matches);
            $path = $matches[1];
        }

        return false;
    }

    /**
     * Returns the HTML for all inputs corresponding to the hook and object
     *
     * @param string $codeObject
     * @param string $hookName
     *
     * @return string
     */
    public function displayInputs($codeObject, $hookName)
    {
        try {
            $idObject = $this->objectService->getIdObjectFromCode($codeObject);
        } catch (Exception $e) {
            return '';
        }

        if (!$idObject) {
            return '';
        }

        $cacheKey = 'totcustomfields|' .
            'displayInputs|' .
            'display_' . $this->getDisplayCode() . '_' . $hookName . '-' .
            'object_' . $codeObject . '_' . $idObject . '-' .
            'shop_' . Context::getContext()->shop->id . '-' .
            'lang_' . Context::getContext()->language->id;

        $tplFile = $this->getDefaultTemplate();

        try {
            // Check if we have a specific template for this hook and this object
            $tplHook = $this->getCustomTemplate($hookName, $codeObject, 'hook');
            if (file_exists($tplHook)) {
                $tplFile = $tplHook;
            }

            Tools::enableCache();

            $querySelect = new DbQuery();
            $querySelect->select('tcf_i.id_input');
            $querySelect->from(self::DISPLAY_NATIVE_HOOKS_TABLE, 'tcf_di_nh');
            $querySelect->innerJoin(self::DISPLAY_INPUT_TABLE, 'tcf_di', 'tcf_di.id_display_input = tcf_di_nh.id_display_input');
            $querySelect->innerJoin('totcustomfields_input', 'tcf_i', 'tcf_i.id_input = tcf_di.id_input');
            $querySelect->where("tcf_i.code_object = '" . pSQL($codeObject) . "'");
            $querySelect->where('tcf_i.active = 1');
            $querySelect->where('tcf_di.active = 1');
            $querySelect->where("tcf_di_nh.hook = '" . pSQL($hookName) . "'");
            $querySelect->where("tcf_di.code_display = '" . pSQL($this->getDisplayCode()) . "'");
            $querySelect->groupBy('tcf_i.id_input');
            $querySelect->orderBy('position ASC');

            // Multishop; we have to remove the 'AND' from PS's query...
            $querySelect->innerJoin('totcustomfields_input_shop', 'tcf_i_s', 'tcf_i_s.id_input = tcf_i.id_input');
            $querySelect->where(str_replace('AND', '', Shop::addSqlRestriction(false, 'tcf_i_s')));

            $inputs_rows = Db::getInstance()->executeS($querySelect);

            if (empty($inputs_rows)) {
                Tools::restoreCacheSettings();

                return '';
            }

            $output = [];
            $inputs = [];
            foreach ($inputs_rows as $row) {
                try {
                    $input = $this->inputService->getById($row['id_input']);
                    if (!Validate::isLoadedObject($input)) {
                        continue;
                    }

                    // Keep the input for later
                    $inputs[] = $input;

                    // For each input, check if we have a specific template
                    $tpl_input = $this->getCustomTemplate($hookName, $codeObject, $input->getInputTypeCode());
                    $output[$input->code] = $input->getValueHtml($idObject, $tpl_input ? $tpl_input : null);
                } catch (Exception $e) {
                    continue;
                }
            }

            // If we have a specific process for a hook before rendering it, we
            // can call it here
            if (method_exists(get_called_class(), 'hook' . ucfirst($hookName) . 'BeforeRender')) {
                $hookReturn = call_user_func(
                    [$this, 'hook' . ucfirst($hookName) . 'BeforeRender'],
                    [
                        'inputs' => &$inputs,
                        'output' => &$output,
                        'code_object' => $codeObject,
                        'tpl_file' => &$tplFile,
                        'cacheKey' => &$cacheKey,
                    ]
                );

                // If the hook returns false, don't display anything
                if (false === $hookReturn) {
                    Tools::restoreCacheSettings();

                    return '';
                } elseif (true !== $hookReturn) {
                    // If the hooks returns something else than true, return the
                    // result
                    Tools::restoreCacheSettings();

                    return $hookReturn;
                }
                // If the hooks returns true, continue the process with the
                // possibly modified variables
            }

            $tpl = Context::getContext()->smarty->createTemplate($tplFile, $cacheKey);
            $tpl->assign('output', $output);

            $html = $tpl->fetch();

            // Unescape single quotes that seems to be automatically escaped by Smarty fetch(),
            // even when setting no_output_filter to true.
            $html = str_replace("\'", "'", $html);

            // If we have a specific process for a hook after rendering it, we
            // can call it here
            if (method_exists(get_called_class(), 'hook' . ucfirst($hookName) . 'AfterRender')) {
                Tools::restoreCacheSettings();

                return call_user_func(
                    [$this, 'hook' . ucfirst($hookName) . 'AfterRender'],
                    $html
                );
            }

            Tools::restoreCacheSettings();

            return $html;
        } catch (Exception $e) {
            Tools::restoreCacheSettings();

            return '';
        }
    }

    /**
     * Product tab title on PS 1.6; checks if we should display a tab title
     *
     * @param array $params
     *
     * @return mixed
     */
    protected function hookDisplayProductTabBeforeRender($params)
    {
        return !(bool) Configuration::get(CustomFieldsExtension::MULTIPLE_PRODUCT_TABS);
    }

    /**
     * Product tab title on PS 1.6; only when not using multiple product tabs.
     * Saves the tab title to render it right before the tab content
     *
     * @param string $html the tab's title as HTML
     *
     * @return mixed
     */
    protected function hookDisplayProductTabAfterRender($html)
    {
        // We should only enter this hook when not using multiple product tabs
        $this->tabTitle = $html;

        return false;
    }

    /**
     * Product tab content on PS 1.6 :
     * - If using multiple product tabs, renders each input as a tab if they
     * have a content, using the input's name as the title
     * - If not using multiple product tabs, stops the process if the tab has no
     * content, or renders both the saved tab title and its content.
     *
     * @param array $params
     *
     * @return mixed
     */
    protected function hookDisplayProductTabContentBeforeRender($params)
    {
        $output = [
            'data' => $params['output'],
            'multiple_tabs' => (bool) Configuration::get(CustomFieldsExtension::MULTIPLE_PRODUCT_TABS),
        ];

        if ($output['multiple_tabs']) {
            /** @var CustomFieldsInput $input */
            foreach ($params['inputs'] as $input) {
                $value = trim($output['data'][$input->code]);
                if (empty($value)) {
                    unset($output['data'][$input->code]);
                    continue;
                }

                $output['data'][$input->code] = [
                    // Use the name as tab title
                    'title' => $input->name,
                    // Retrieve the tab content
                    'content' => $value,
                ];
            }
        } else {
            // When not using multiple product tabs, put the title before the
            // content
            $output['title'] = $this->tabTitle;
        }

        $params['output'] = $output;

        return true;
    }

    /**
     * Product tab on PS 1.7 :
     * - If using multiple product tabs, renders each input as a tab if they
     * have a content, using the input's name as the title
     * - If not using multiple product tabs, stops the process if the tab has no
     * content
     *
     * @param array $params
     *
     * @return mixed
     */
    protected function hookDisplayProductExtraContentBeforeRender($params)
    {
        if (!Configuration::get(CustomFieldsExtension::MULTIPLE_PRODUCT_TABS)) {
            // If common tab is empty, hide it
            $hasContent = false;
            foreach ($params['output'] as $value) {
                $value = trim($value);
                $hasContent = $hasContent || !empty($value);
            }

            return (bool) $hasContent;
        }

        $extraContentTabs = [];
        foreach ($params['inputs'] as $input) {
            // Create a tab for each input
            // If tab is empty, skip it
            $value = trim($params['output'][$input->code]);
            if (empty($value)) {
                continue;
            }

            $extraContent = new ProductExtraContent();
            $extraContentTabs[] = $extraContent
                ->setTitle($input->name)
                ->setContent($value);
        }

        // Not returning "true" will return the result immediately
        return $extraContentTabs;
    }

    /**
     * Product tab on PS 1.7; only when not using multiple product tabs
     * Returns a single tab with the configured title and all the content
     *
     * @param string $html the tab's content as HTML
     *
     * @return array
     */
    protected function hookDisplayProductExtraContentAfterRender($html)
    {
        $tabTitle = Configuration::get(CustomFieldsExtension::PRODUCT_TAB_TITLE, Context::getContext()->language->id);
        if (!$tabTitle) {
            $tabTitle = $this->l('Product tab title', 'CustomFieldsDisplayNativeHooks');
        }

        $productExtraContent = new ProductExtraContent();
        $productExtraContent
            ->setTitle($tabTitle)
            ->setContent($html);

        return [$productExtraContent];
    }
}
