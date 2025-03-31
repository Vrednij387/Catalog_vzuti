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
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputCheckboxService;
use Configuration;
use Context;
use Tools;
use Translate;

class CustomFieldsInputCheckbox extends CustomFieldsInput
{
    protected static $inputHtmlTemplateName = 'checkboxInput.tpl';

    protected $inputCheckboxService;

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);
        $this->inputCheckboxService = new CustomFieldsInputCheckboxService();
    }

    public function getListPosition()
    {
        return 6;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeCode()
    {
        return 'checkbox';
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeName()
    {
        return $this->l('Checkbox', 'CustomFieldsInputCheckbox');
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
        if (empty($conf['checkbox-option'])) {
            return [$this->l('No checkbox options was specified for this input.', 'CustomFieldsInputCheckbox')];
        }

        foreach ($conf['checkbox-option'] as &$option) {
            $option = json_decode($option, true);
        }

        if (isset($conf['checkbox-default-option'])) {
            foreach ($conf['checkbox-default-option'] as &$defaultOption) {
                $defaultOption = json_decode($defaultOption, true);
            }

            $this->default_value = json_encode($conf['checkbox-default-option']);
        } else {
            $this->default_value = null;
        }

        if (isset($conf['add-edit-option-text'])) {
            unset($conf['add-edit-option-text']);
        }

        $this->configurationArray = $conf['checkbox-option'];
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

        $inputValue = $this->inputCheckboxService->getInputValue($this->id, $idObject, null);
        $checkedValues = array_filter($value, function ($item) {
            return $item == 1;
        });

        $inputValue->value = json_encode(array_keys($checkedValues));

        return $inputValue->save();
    }

    /**
     * Gets the input value from DB
     *
     * @param int $idObject
     * @param bool|int $idLang
     *
     * @return array|bool|string
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getValue($idObject, $idLang = false)
    {
        $inputValue = $this->inputCheckboxService->getInputValue($this->id, $idObject, null);

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

        if ($value) {
            $value = json_decode($value);
        }
        // If we don't have a value for a translatable field,
        // attempt to get it with the default language
        if (!$value && ((!$this->required && $value !== '') || $this->required) && $this->is_translatable) {
            if ($idLangDefault != (int) Context::getContext()->language->id) {
                $value = json_decode($this->getValue($idObject, $idLangDefault));
            }
        }

        if (is_null($value) && ((!$this->required && $value !== '') || $this->required)) {
            $defaultValue = $this->default_value[$this->is_translatable ? Context::getContext()->language->id : $idLangDefault];
            $value = empty($defaultValue) ? false : array_keys(json_decode($defaultValue, true));
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
        $confOptions = $this->getConfigurationArray();
        $conf = $this->prepareConfigurationArray($confOptions);
        $idLangDefault = Configuration::get('PS_LANG_DEFAULT');
        $currentLang = Context::getContext()->language->id;
        $defaultConf = $this->getDefaultConfigurationArray();

        // Retrieve values
        $inputValues = $this->getValue((int) $idObject);
        $value = $inputValues ? json_decode($inputValues) : array_keys($this->prepareConfigurationArray($defaultConf));

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
            'defaultOptions' => $this->prepareConfigurationArray($defaultConf),
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
        $confOptions = $this->getConfigurationArray();
        if (isset($confOptions['add-edit-option-text'])) {
            unset($confOptions['add-edit-option-text']);
        }
        $conf = $this->prepareConfigurationArray($confOptions);

        $defaultConf = $this->getDefaultConfigurationArray();
        $defaultOptions = $this->prepareConfigurationArray($defaultConf);

        $data['currentConf'] = [
            'options' => $conf,
            'translatable' => $this->is_translatable,
            'defaultOptions' => $defaultOptions,
        ];

        $data['default_language'] = (int) Configuration::get('PS_LANG_DEFAULT');

        return $data;
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
            $value = [];
        }

        if ($this->required) {
            $selectedFound = false;
            foreach ($value as $checkbox) {
                if ($checkbox == '1') {
                    $selectedFound = true;
                    break;
                }
            }

            if (!$selectedFound) {
                return [
                    sprintf($this->l('Field %s : No value specified for required field', 'CustomFieldsInputCheckbox'), $this->name),
                ];
            }
        }

        return true;
    }

    public function getValueHtmlTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/front/custom_fields/inputs/valueCheckboxInput.tpl';
    }

    public function getConfigurationSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/config/formCheckboxInput.tpl';
    }

    public function delete()
    {
        $return = true;

        // Delete values
        $values = $this->inputCheckboxService->getAllInputValues($this->id);
        foreach ($values as $val) {
            /* @var CustomFieldsInputSelect $val */
            $return &= $val->delete();
        }

        return $return && parent::delete();
    }

    protected function getValueFromConfigurationByLang($value, $lang)
    {
        $configurations = $this->getConfigurationArray();
        $translatedValues = [];
        foreach ($configurations as $key => $configuration) {
            foreach ($value as $checkboxValue) {
                if ($key == $checkboxValue || json_encode($configuration) == $value) {
                    foreach ($configuration as $item) {
                        if ($item['langId'] == $lang) {
                            $translatedValues[] = $item['value'];
                        }
                    }
                    break;
                }
            }
        }

        return $translatedValues;
    }

    protected function prepareConfigurationArray($options)
    {
        if (is_null($options)) {
            return [];
        }

        $optionsArray = [];
        $defaultLanguage = (int) Configuration::get('PS_LANG_DEFAULT');
        if (isset($options['checkbox-option'])) {
            $options = $options['checkbox-option'];
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
     * Get configuration for this input
     *
     * @return array
     */
    public function getDefaultConfigurationArray()
    {
        if (!is_array($this->default_value[Configuration::get('PS_LANG_DEFAULT')])) {
            $this->default_value = json_decode($this->default_value[Configuration::get('PS_LANG_DEFAULT')], true);
        }

        return $this->default_value;
    }
}
