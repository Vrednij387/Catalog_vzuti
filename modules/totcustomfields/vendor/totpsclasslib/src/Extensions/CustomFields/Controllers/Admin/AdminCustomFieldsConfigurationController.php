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

use TotcustomfieldsClasslib\Actions\ExtensionActionsHandler;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Actions\CustomFieldsSaveValuesActions;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsDisplay;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsObject;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputText;
use TotcustomfieldsClasslib\Extensions\CustomFields\CustomFieldsExtension;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsDisplayService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsObjectService;
use TotcustomfieldsClasslib\Module\Module;
use TotcustomfieldsClasslib\Utils\Translate\TranslateTrait;
use Cache;
use Configuration;
use Context;
use Exception;
use HelperTreeShops;
use Hook;
use ModuleAdminController;
use Shop;
use Tools;
use Validate;

/**
 * Class AdminCustomFieldsController
 *
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/formInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/advancedSettings.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/displays/objectAdminHooks.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/prestui/ps-tabs.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/prestui/ps-tabs.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/prestui/ps-panel.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/prestui/ps-form.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/prestui/ps-alert.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/prestui/ps-table.tpl'
 */
class AdminCustomFieldsConfigurationController extends ModuleAdminController
{
    use TranslateTrait;

    public $className = 'Configuration';

    public $bootstrap = true;

    protected $displayService;

    protected $objectsService;

    protected $inputService;

    public function __construct()
    {
        parent::__construct();
        $this->displayService = new CustomFieldsDisplayService();
        $this->objectsService = new CustomFieldsObjectService();
        $this->inputService = new CustomFieldsInputService();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJqueryUi('ui.widget');
        $this->addJqueryPlugin('tagify');
        $this->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
        $this->addJS(_PS_JS_DIR_ . 'admin/tinymce.inc.js');
        $this->addJS(_PS_JS_DIR_ . 'admin/tinymce_loader.js');
        $this->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/riot+compiler.min.js');
        $this->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/textarea-input-html.js');
        $this->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/prestui.js');
        $this->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/totcf-tools.js');
        $this->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/totcf-config.js');
        $this->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/selectInputProcessor.js');
        $this->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/checkboxInputProcessor.js');

        $this->addCSS(_MODULE_DIR_ . 'totcustomfields/views/css/custom_fields/admin.css');
    }

    /**
     * @return bool|\ObjectModel
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function postProcess()
    {
        $inputUpdateFailed = false;
        $this->addCompatibilityVariables();

        if (Tools::isSubmit('deleteCache')) {
            Tools::enableCache();

            try {
                // May throw an exception if cache is disabled / not configured
                Cache::getInstance()->delete('totcustomfields|*');
            } catch (Exception $ex) {
            }

            Cache::getInstance()->delete('totcustomfields|*');
            Context::getContext()->smarty->clearCache(null, 'totcustomfields');
            Tools::restoreCacheSettings();
            $this->confirmations[] = $this->l('Cache successfully deleted.', 'AdminCustomFieldsConfigurationController');
        } elseif (Tools::isSubmit('saveInput')) {
            if ($this->processSaveInput()) {
                $this->confirmations[] = $this->l('Input was successfully saved.', 'AdminCustomFieldsConfigurationController');
            } else {
                $this->errors[] = $this->l('Input could not be saved.', 'AdminCustomFieldsConfigurationController');
                $inputUpdateFailed = true;
            }
        } elseif (Tools::isSubmit('saveObjectDisplayConfiguration')) {
            if ($this->processSaveObjectDisplayConfiguration()) {
                $this->confirmations[] = $this->l('Update successful.', 'AdminCustomFieldsConfigurationController');
            } else {
                $this->errors[] = $this->l('Configuration could not be saved.', 'AdminCustomFieldsConfigurationController');
                $inputUpdateFailed = true;
            }
        } elseif (Tools::getValue('action') == 'delete_input') {
            if ($this->processDeleteInputAction()) {
                $this->confirmations[] = $this->l('Input successfully deleted.', 'AdminCustomFieldsConfigurationController');
            } else {
                $this->errors[] = $this->l('Input could not be deleted.', 'AdminCustomFieldsConfigurationController');
            }
        } elseif (Tools::isSubmit('saveAdvancedSettings')) {
            if ($this->processAdvancedSettings()) {
                $this->confirmations[] = $this->l('Advanced settings successfully saved.', 'AdminCustomFieldsConfigurationController');
            } else {
                $this->errors[] = $this->l('Advanced settings could not be saved.', 'AdminCustomFieldsConfigurationController');
            }
        }

        $id_input = null;
        $input = null;
        $action = Tools::getValue('action');
        if ('edit_input' == $action) {
            $id_input = (int) Tools::getValue('id_input');
            if ($id_input) {
                $input = $this->inputService->getById($id_input);
                if (!Validate::isLoadedObject($input)) {
                    $input = null;
                    $id_input = null;
                }
            }
        }

        // Get configuration from post if needed
        if ($inputUpdateFailed) {
            try {
                $postConfiguration = $this->getInputConfigurationFromPost();
            } catch (\Exception $e) {
                // there is an error during form fetch, we cannot create a post configuration
            }
            $id_input = (int) Tools::getValue('id_input');
        }

        $inputTypes = [];
        foreach ($this->inputService->getInputTypes() as $type) {
            if (empty($type['code']) || !preg_match('/^[a-zA-Z]+$/', $type['code'])) {
                continue;
            }
            if (!class_exists($type['className'])) {
                continue;
            }

            /** @var CustomFieldsInput $inputType */
            $inputType = new $type['className']();

            if ($inputUpdateFailed && isset($postConfiguration) && $postConfiguration['inputData']['code_input_type'] == $type['code']) {
                // If we're updating an input, and we've just submitted the form
                $formData = $postConfiguration['typeSubformData'];
            } elseif ($input && $input->code_input_type == $type['code']) {
                // If we're updating and input, and we haven't submitted the form already
                $formData = $input->getConfigurationSubformData();
            } else {
                // If we're not updating an input

                $formData = $inputType->getStaticConfigurationSubformData();
            }

            /** @var CustomFieldsInput $className */
            $className = $type['className'];
            $inputTypes[] = [
                'code_input_type' => $type['code'],
                'name' => $type['name'],
                'template' => $inputType->getConfigurationSubformTemplatePath(),
                'formData' => $formData,
                'position' => $inputType->getListPosition(),
            ];
        }
        usort($inputTypes, [$this, 'cmpPositionGt']);

        $shop_association_tree = null;
        if (Shop::isFeatureActive()) {
            // Create shop association tree
            $helper = new HelperTreeShops('shops-tree', $this->l('Multistore tree', 'AdminCustomFieldsConfigurationController'));

            if ($inputUpdateFailed && isset($postConfiguration)) {
                $helper->setSelectedShops($postConfiguration['shopList']);
            } elseif ($input) {
                $helper->setSelectedShops($input->getAssociatedShops());
            }
            $shop_association_tree = $helper->render();
        }

        $currentConf = null;
        if ($inputUpdateFailed && isset($postConfiguration)) {
            $currentConf = $postConfiguration['inputData'];
        } elseif ($input) {
            $currentConf = $input->getConfigurationFormData();
        }

        $this->context->smarty->assign(
            'newInputData',
            [
                'inputTypes' => $inputTypes,
                'id_input' => $id_input,
                // This is the conf data common to all input types
                'currentConf' => $currentConf,
                'shop_association_tree' => $shop_association_tree,
            ]
        );

        $objectsData = [];
        foreach ($this->objectsService->getObjects() as $object) {
            /** @var CustomFieldsObject $instance */
            $instance = $object['instance'];
            $objectsData[] = [
                'code' => $instance->getObjectCode(),
                'location_name' => $instance->getObjectLocationName(),
                'icon' => 'icon-cogs',
                'configurationData' => $instance->getConfigurationData(),
                'position' => $instance->list_position,
                'countInputs' => $this->inputService->countInputsFromCodeObject($instance->getObjectCode()),
            ];
        }
        usort($objectsData, [$this, 'cmpPositionGt']);

        $displaysData = [];
        foreach ($this->displayService->getDisplayMethods() as $display) {
            /* @var $display CustomFieldsDisplay */
            $displaysData[] = [
                'code' => $display['instance']->getDisplayCode(),
                'name' => $display['instance']->getDisplayName(),
            ];
        }

        $this->context->smarty->assign([
            'confirmations' => $this->confirmations,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'objects' => $objectsData,
            'displays' => $displaysData,
            'module' => [
                'folder_url' => _PS_MODULE_DIR_ . 'totcustomfields/',
                'folderLink' => '/modules/totcustomfields/',
                'config_url' => $this->context->link->getAdminLink('AdminTotcustomfieldsCustomFieldsConfiguration', true),
                'displayName' => $this->module->displayName,
                'description' => $this->module->description,
            ],
            'deleteInputConfirmation' => $this->l('This will delete the input along with all its values. Are you sure ?', 'AdminCustomFieldsConfigurationController'),
            'languages' => $this->getLanguages(),
            'id_current_lang' => Context::getContext()->language->id,
            'ps_version' => '1.7',
        ]);

        $this->assignAdvancedSettingsVars();

        $this->content .= $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/config.tpl');
        $this->content .= $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/prestui/ps-tags.tpl');

        return parent::postProcess();
    }

    protected function assignAdvancedSettingsVars()
    {
        $languages = $this->getLanguages();
        $productTabTitle = [];
        foreach ($languages as $lang) {
            $productTabTitle[$lang['id_lang']] = Configuration::get(CustomFieldsExtension::PRODUCT_TAB_TITLE, $lang['id_lang']);
        }

        $this->context->smarty->assign('advancedSettings', [
            CustomFieldsExtension::MULTIPLE_PRODUCT_TABS => Configuration::get(CustomFieldsExtension::MULTIPLE_PRODUCT_TABS),
            CustomFieldsExtension::PRODUCT_TAB_TITLE => $productTabTitle,
        ]);
    }

    protected function processAdvancedSettings()
    {
        $hasMultipleProductsTabs = (bool) Tools::getValue(CustomFieldsExtension::MULTIPLE_PRODUCT_TABS);
        Configuration::updateValue(CustomFieldsExtension::MULTIPLE_PRODUCT_TABS, $hasMultipleProductsTabs);

        if (!$hasMultipleProductsTabs) {
            $productTabTitle = Tools::getValue(CustomFieldsExtension::PRODUCT_TAB_TITLE);
            if (is_array($productTabTitle)) {
                Configuration::updateValue(CustomFieldsExtension::PRODUCT_TAB_TITLE, $productTabTitle, true);
            }
        }

        return true;
    }

    /**
     * Creates/updates an input
     *
     * @return bool
     *
     * @throws \PrestaShopException
     */
    public function processSaveInput()
    {
        // Check if type is valid
        $code_type = Tools::getValue('type');
        if (!$this->inputService->isValidTypeCode($code_type)) {
            $this->errors[] = $this->l('Invalid type for input.', 'AdminCustomFieldsConfigurationController');

            return false;
        }

        // Check if object is valid
        $codeObject = Tools::getValue('object');
        if (!$this->objectsService->objectExists($codeObject)) {
            $this->errors[] = $this->l('Invalid object.', 'AdminCustomFieldsConfigurationController');

            return false;
        }

        // Check if display is valid
        $codeDisplay = Tools::getValue('display');
        if (!$this->displayService->displayExists($codeDisplay)) {
            $this->errors[] = $this->l('Invalid display.', 'AdminCustomFieldsConfigurationController');

            return false;
        }

        $codeAdminDisplay = Tools::getValue('code_admin_display');
        if (!$this->displayService->isAdminHookAvailable($codeObject, $codeAdminDisplay)) {
            $this->errors[] = $this->l('Invalid admin display.', 'AdminCustomFieldsConfigurationController');

            return false;
        }

        // Are we modifying an input or creating a new one ?
        $input = null;
        $existing_input = null;
        if (Tools::getValue('id_input')) {
            $existing_input = $this->inputService->getById((int) Tools::getValue('id_input'));
            if (!Validate::isLoadedObject($existing_input)) {
                $this->errors[] = $this->l('The specified input was not found.', 'AdminCustomFieldsConfigurationController');

                return false;
            }

            // If the type was changed, we need to use the right class
            if ($existing_input->code_input_type != $code_type) {
                $input = $this->inputService->getByType($code_type);
                $input->force_id = true;
                $input->id = $existing_input->id;
            } else {
                $input = $existing_input;
            }
        } else {
            $input = $this->inputService->getByType(Tools::getValue('type'));
            if (!$input) {
                $this->errors[] = $this->l('The specified input type was not found.', 'AdminCustomFieldsConfigurationController');

                return false;
            }
        }

        $name = Tools::getValue('name');
        if (empty($name)) {
            $this->errors[] = $this->l('Your must specify a name for your input.', 'AdminCustomFieldsConfigurationController');

            return false;
        }

        if (empty($existing_input->unremovable)) {
            $code = Tools::getValue('code');
            if (!Validate::isConfigName(Tools::getValue('code'))) {
                $this->errors[] = $this->l('Input technical name may only contain letters, numbers, _ and -.', 'AdminCustomFieldsConfigurationController');

                return false;
            }
            if (!$this->inputService->isUniqueInputCode($code, $input ? $input->id : null)) {
                $this->errors[] = $this->l('Input technical name is already used by another input.', 'AdminCustomFieldsConfigurationController');

                return false;
            }
        }

        $instructions = Tools::getValue('instructions');
        if (!is_array($instructions)) {
            $instructions = [
                Configuration::get('PS_LANG_DEFAULT') => $instructions,
            ];
        }

        $active = Tools::getValue('active') == true;

        // Multishop
        $active_shops = Tools::getValue('checkBoxShopAsso_configuration');
        if (Shop::isFeatureActive()) {
            if (is_array($active_shops) && !empty($active_shops)) {
                foreach ($active_shops as $id_shop) {
                    $input->id_shop_list[] = (int) $id_shop;
                }
            } else {
                $this->warnings[] = $this->l('The field must be associated to at least one shop.', 'AdminCustomFieldsConfigurationController');
            }
        }

        $input->code_input_type = $code_type;
        $input->code_object = $codeObject;
        $input->name = $name;

        if (empty($existing_input->unremovable) && isset($code)) {
            $input->code = $code;
        }

        $input->required = Tools::getValue('required') == true;
        $input->instructions = $instructions;
        $input->active = $active;

        $type_configuration = Tools::getValue('type_configuration');
        if (($ret = $input->setConfigurationByArray($type_configuration[$code_type])) !== true) {
            if (is_array($ret)) {
                $this->errors = array_merge($this->errors, $ret);
            }
            if (!$existing_input) {
                $input->delete();
            }

            return false;
        }

        $displayConfiguration = Tools::getValue('display_configuration');
        $display = $this->displayService->getInstanceFromCode($codeDisplay);
        $displayParams = empty($displayConfiguration)
            ? false
            : $displayConfiguration[$codeDisplay];

        if (($ret = $display->saveInputDisplayMethod($input, $displayParams, $codeAdminDisplay)) !== true) {
            if (is_array($ret)) {
                $this->errors = array_merge($this->errors, $ret);
            }
            if (!$existing_input) {
                $input->delete();
            }

            return false;
        }

        return true;
    }

    protected function getInputConfigurationFromPost()
    {
        $formData = [
            'inputData' => [],
            'typeSubformData' => [],
            'shopList' => [],
        ];

        $code_type = Tools::getValue('type');
        $isValidInputType = true;
        if (!$this->inputService->isValidTypeCode($code_type)) {
            $isValidInputType = false;
            $input = new CustomFieldsInputText();
        } else {
            $input = $this->inputService->getByType(Tools::getValue('type'));
            if (!$input) {
                $isValidInputType = false;
                $input = new CustomFieldsInputText();
            }
        }

        // Set common input configuration
        $input->code_object = Tools::getValue('object');
        $input->name = Tools::getValue('name');
        $input->code = Tools::getValue('code');
        $input->required = Tools::getValue('required') == true;
        $input->instructions = Tools::getValue('instructions');
        $input->active = Tools::getValue('active') == true;

        if ($isValidInputType) {
            $input->code_input_type = $code_type;
        }

        $formData['inputData'] = $input->getConfigurationFormData();
        $formData['inputData']['code_display'] = Tools::getValue('display');

        // Set shop list
        $active_shops = Tools::getValue('checkBoxShopAsso_configuration');
        if (!empty($active_shops)) {
            foreach ($active_shops as $id_shop) {
                $formData['shopList'][] = (int) $id_shop;
            }
        }

        // Set type configuration
        if ($isValidInputType) {
            $type_configuration = Tools::getValue('type_configuration');
            $input->configurationArray = $type_configuration[$code_type];
        }
        $formData['typeSubformData'] = $input->getConfigurationSubformData();

        return $formData;
    }

    /**
     * Saves inputs values.
     *
     * This function is used by the Order form since there's no "native" form
     * to hook into, and by the Product form on PS 1.7 because the usual process
     * is bugged (see hookActionAdminCategoriesControllerSaveBefore)
     *
     * @throws \Exception
     */
    public function ajaxProcessSaveInputsValues()
    {
        $inputsValues = Tools::getValue('totcustomfields_inputs');
        $id_object = (int) Tools::getValue('id_object');
        $code_object = Tools::getValue('code_object');

        // TODO
        if (empty($inputsValues)) {
            exit(json_encode([sprintf(
                $this->l('Custom Fields : No fields to save.', 'AdminCustomFieldsConfigurationController'),
                $code_object
            )]));
        }

        if (!$this->objectsService->objectExists($code_object)) {
            exit(json_encode([sprintf(
                $this->l('Custom Fields : Code "%s" does not match any known object.', 'AdminCustomFieldsConfigurationController'),
                $code_object
            )]));
        }

        $actionsHandler = new ExtensionActionsHandler();
        $validation = $actionsHandler
            ->setConveyor([
                'id_object' => $id_object,
                'code_object' => $code_object,
                'inputs_values' => $inputsValues,
            ])
            ->addActions('validateValues', 'saveValues')
            ->process(CustomFieldsSaveValuesActions::class);
        $conveyor = $actionsHandler->getConveyor();

        if (!empty($conveyor['validation_errors']) || !empty($conveyor['save_errors'])) {
            $errors = [];
        }

        if (!empty($conveyor['validation_errors'])) {
            foreach ($conveyor['validation_errors'] as $error) {
                $errors[] = $error; // TODO add normal error
            }
        }

        if (!empty($conveyor['save_errors'])) {
            foreach ($conveyor['save_errors'] as $error) {
                $errors[] = $error; // TODO add normal error Tools::displayError...
            }
        }
        if (!empty($errors)) {
            exit(implode('', $errors));
        }

        Hook::exec('actionSaveTotcustomfieldsFields', $conveyor);

        exit(json_encode([$this->l('Custom fields successfully saved.', 'AdminCustomFieldsConfigurationController')]));
    }

    /**
     * Returns a Display subform, in input configuration form
     *
     * @throws \SmartyException
     */
    public function ajaxProcessGetDisplaySubform()
    {
        $code_display = Tools::getValue('code_display');
        $code_object = Tools::getValue('code_object');
        $id_input = Tools::getValue('id_input', null);

        $display = $this->displayService->getInstanceFromCode($code_display);
        if (!$display) {
            exit;
        }

        $this->context->smarty->assign([
            'displayData' => $display->getInputSubformData($code_object, $id_input),
        ]);

        $templatePath = $display->getInputSubformTemplatePath();
        if ($templatePath) {
            exit($this->context->smarty->fetch($templatePath));
        }
        exit;
    }

    /**
     * Returns a Display subform, in input configuration form
     *
     * @throws \SmartyException
     */
    public function ajaxProcessGetDisplayAdminHooks()
    {
        $codeObject = Tools::getValue('code_object');
        $idInput = Tools::getValue('id_input', null);

        $availableHooks = $this->displayService->getAdminDisplayHooksByCodeObject($codeObject);
        $codeAdminDisplay = $idInput ? $this->displayService->getAdminDisplayHookByInputId($idInput) : null;

        $this->context->smarty->assign([
            'hooks' => $availableHooks,
            'selectedHook' => $codeAdminDisplay,
        ]);

        $templatePath = $this->displayService->getAdminDisplayHooksTemplatePath();
        if ($templatePath) {
            exit($this->context->smarty->fetch($templatePath));
        }
        exit;
    }

    /**
     * Returns a Display subform, in input configuration form
     */
    public function ajaxProcessToggleActiveDisplay()
    {
        $id_input = Tools::getValue('id_input');

        $input = $this->inputService->getById($id_input);
        if (!$input) {
            exit('failure');
        }

        $input->active = !$input->active;
        if ($input->update()) {
            exit('success');
        }
        exit('failure');
    }

    public function processSaveObjectDisplayConfiguration()
    {
        $code_display = Tools::getValue('code_display');

        $display = $this->displayService->getInstanceFromCode($code_display);
        if (!$display) {
            return false;
        }

        if (!$display->saveObjectDisplayConfiguration()) {
            return false;
        }

        return true;
    }

    public function processDeleteInputAction()
    {
        $id_input = Tools::getValue('id_input');

        $input = $this->inputService->getById($id_input);
        if (!Validate::isLoadedObject($input)) {
            $this->errors[] = sprintf($this->l('Input "%s" was not found.', 'AdminCustomFieldsConfigurationController'), $id_input);

            return false;
        }

        if (!empty($input->unremovable)) {
            $this->errors[] = sprintf($this->l('Input "%s" was added programmatically and cannot be removed.', 'AdminCustomFieldsConfigurationController'), $id_input);

            return false;
        }

        return $input->delete();
    }

    public function cmpPositionGt($a, $b)
    {
        return $a['position'] > $b['position'] ? 1 : -1;
    }

    protected function addCompatibilityVariables()
    {
        $this->context->smarty->assign([
            'table' => $this->table,
            'current' => self::$currentIndex,
            'token' => $this->token,
            'host_mode' => defined('_PS_HOST_MODE_') ? 1 : 0,
            'stock_management' => (int) \Configuration::get('PS_STOCK_MANAGEMENT'),
        ]);

        if ($this->display_header) {
            $this->context->smarty->assign('displayBackOfficeHeader', \Hook::exec('displayBackOfficeHeader', []));
        }

        $this->context->smarty->assign([
            'displayBackOfficeTop' => \Hook::exec('displayBackOfficeTop', []),
            'submit_form_ajax' => (int) \Tools::getValue('submitFormAjax'),
        ]);
    }
}
