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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs;

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputSelectService;
use Configuration;
use Context;
use Language;
use Tools;
use Translate;

class CustomFieldsInputSelect extends CustomFieldsInput
{
    protected static $inputHtmlTemplateName = 'selectInput.tpl';

    protected $inputSelectService;

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);
        $this->inputSelectService = new CustomFieldsInputSelectService();
    }

    public function getListPosition()
    {
        return 5;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeCode()
    {
        return 'select';
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeName()
    {
        return $this->l('Select', 'CustomFieldsInputSelect');
    }

    /**
     * Set configuration for this input; configuration data validation is done here
     *
     * @param array $conf the configuration array for this input
     *
     * @return array|bool an array of errors, or true
     *
     * @throws \PrestaShopException
     */
    public function setConfigurationByArray($conf)
    {
        if (empty($conf['select-option'])) {
            return [$this->l('No select options was specified for this input.', 'CustomFieldsInputSelect')];
        }

        if (!isset($conf['default'])) {
            return [$this->l('The default select option was not specified.', 'CustomFieldsInputSelect')];
        }

        $this->default_value = $conf['default'];

        foreach ($conf['select-option'] as &$option) {
            $option = json_decode($option, true);
        }

        $this->configurationArray = $conf['select-option'];
        $this->is_translatable = false; // TODO discuss this point

        return $this->save() == true;
    }

    /**
     * Sets the input value; data validation is done here
     *
     * @param mixed $value
     * @param mixed $idObject
     *
     * @return bool
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function setValue($value, $idObject)
    {
        $isValid = $this->isValidValue($value, $idObject);
        if (true !== $isValid) {
            return $isValid;
        }

        $inputValue = $this->inputSelectService->getInputValue($this->id, $idObject, null);
        $inputValue->value = $value;

        return $inputValue->save();
    }

    /**
     * Gets the input value from DB
     *
     * @param int $idObject
     * @param int|bool $idLang
     *
     * @return array|false
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getValue($idObject, $idLang = false)
    {
        $inputValue = $this->inputSelectService->getInputValue($this->id, $idObject, null);

        return $inputValue->value;
    }

    /**
     * Gets the input value for an object, formatted for HTML
     *
     * @param mixed $idObject
     * @param string $customTemplate a path to a custom template
     *
     * @return mixed
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function getValueHtml($idObject, $customTemplate = null)
    {
        $value = $this->getValue($idObject, $this->is_translatable ? Context::getContext()->language->id : null);
        $idLangDefault = (int) Configuration::get('PS_LANG_DEFAULT');

        // If we don't have a value for a translatable field,
        // attempt to get it with the default language
        if (!$value && ((!$this->required && $value !== '') || $this->required) && $this->is_translatable) {
            if ($idLangDefault != (int) Context::getContext()->language->id) {
                $value = $this->getValue($idObject, $idLangDefault);
            }
        }

        if (!$value && ((!$this->required && $value !== '') || $this->required)) {
            $value = $this->default_value[$this->is_translatable ? Context::getContext()->language->id : $idLangDefault];
        }

        if (!$value) {
            return '';
        }

        $currentLanguageValue = $this->getValueFromConfigurationByLang($value, Context::getContext()->language->id);

        if ($customTemplate != null && file_exists($customTemplate)) {
            $tpl = Context::getContext()->smarty->createTemplate($customTemplate);
        } else {
            $tpl = Context::getContext()->smarty->createTemplate($this->getValueHtmlTemplatePath());
        }

        $tpl->caching = 0;
        $tpl->assign([
            'value' => $currentLanguageValue,
        ]);

        return $tpl->fetch();
    }

    private function getValueFromConfigurationByLang($value, $lang)
    {
        $configurations = $this->getConfigurationArray();
        foreach ($configurations as $key => $configuration) {
            if ($key == $value || json_encode($configuration) == $value) {
                foreach ($configuration as $item) {
                    if ($item['langId'] == $lang) {
                        return $item['value'];
                    }
                }
            }
        }

        return null;
    }

    /**
     * Get input HTML
     *
     * @param int $idObject the object id to get the associated value
     * @param string $codeObject the object code, to load the right html template
     *
     * @return string
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function getInputHtml($idObject, $codeObject)
    {
        $conf = $this->prepareConfigurationArray();
        $idLangDefault = Configuration::get('PS_LANG_DEFAULT');
        $currentLang = Context::getContext()->language->id;

        // Retrieve values
        $inputValues = $this->getValue((int) $idObject);
        $value = $inputValues ? $inputValues : $this->default_value[$idLangDefault];

        $instructions = '';
        if (!empty($this->instructions[$currentLang])) {
            $instructions = $this->instructions[$currentLang];
        } elseif (!empty($this->instructions[$idLangDefault])) {
            $instructions = $this->instructions[$idLangDefault];
        }

        $tpl = Context::getContext()->smarty->createTemplate($this->getInputHtmlTemplatePath($codeObject));
        $tpl->caching = 0;
        $tpl->assign([
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'required' => $this->required,
            'instructions' => $instructions,
            'value' => $value,
            'defaultLang' => $idLangDefault,
            'currentLang' => $currentLang,
            'conf' => $conf,
            'default_value' => $this->default_value[$idLangDefault],
        ]);

        return $tpl->fetch();
    }

    /**
     * Gets data for the configuration subform, with currently saved values
     *
     * @return array
     */
    public function getConfigurationSubformData()
    {
        $data = $this->getStaticConfigurationSubformData();
        $conf = $this->prepareConfigurationArray();
        $data['currentConf'] = [
            'options' => $conf,
            'translatable' => $this->is_translatable,
            'default' => is_array($this->default_value)
                ? $this->default_value[Configuration::get('PS_LANG_DEFAULT')]
                : $this->default_value,
        ];

        $data['default_language'] = (int) Configuration::get('PS_LANG_DEFAULT');

        return $data;
    }

    private function prepareConfigurationArray()
    {
        $options = $this->getConfigurationArray();
        $optionsArray = [];
        $defaultLanguage = (int) Configuration::get('PS_LANG_DEFAULT');

        if (isset($options['select-option'])) {
            $options = $options['select-option'];
        }

        foreach ($options as $key => $option) {
            if (!is_array($option)) {
                $option = json_decode($option, true);
                $option = is_null($option) ? [] : $option;
            }
            if (empty($option)) {
                continue;
            }
            $optionsArray[$key]['value'] = json_encode($option);
            $optionsArray[$key]['option'] = $option;
            foreach ($option as $optionLang) {
                if (!is_array($optionLang)) {
                    $optionLang = json_decode($optionLang, true);
                }
                if ($optionLang['langId'] == $defaultLanguage) {
                    $optionsArray[$key]['name'] = $optionLang['value'];
                    break;
                }
            }
        }

        return $optionsArray;
    }

    /**
     * Checks if a value is valid or not
     *
     * @param mixed $value
     * @param int $idObject an optional id_object, which may be useful in some cases
     *
     * @return array|bool
     */
    public function isValidValue(&$value, $idObject = null)
    {
        if (empty($value)) {
            return [
                sprintf($this->l('Field %s : No value specified for required field', 'CustomFieldsInputSelect'), $this->name),
            ];
        }

        return true;
    }

    public function getValueHtmlTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/front/custom_fields/inputs/valueSelectInput.tpl';
    }

    public function getConfigurationSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/config/formSelectInput.tpl';
    }

    public function delete()
    {
        $return = true;

        // Delete values
        $values = $this->inputSelectService->getAllInputValues($this->id);
        foreach ($values as $val) {
            /* @var CustomFieldsInputSelect $val */
            $return &= $val->delete();
        }

        return $return && parent::delete();
    }
}
