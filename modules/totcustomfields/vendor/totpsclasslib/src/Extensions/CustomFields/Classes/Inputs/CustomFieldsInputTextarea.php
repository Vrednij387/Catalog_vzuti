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
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputTextareaService;
use Configuration;
use Context;
use Language;
use Media;
use Tools;
use Translate;

/**
 * Class CustomFieldsInputTextarea
 *
 * @include 'totcustomfields/views/js/custom_fields/textarea-input-html.js'
 */
class CustomFieldsInputTextarea extends CustomFieldsInput
{
    /*
     * Configuration array(
     *      'format' => int,
     *      'maxlength' => int,
     *      'default_value' => string,
     *      'translatable' => bool // Received during configuration, but not in array
     * )
     */

    protected static $inputHtmlTemplateName = 'textareaInput.tpl';

    protected $inputTextareaService;

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);
        $this->inputTextareaService = new CustomFieldsInputTextareaService();
    }

    public function getListPosition()
    {
        return 2;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeCode()
    {
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeName()
    {
        return $this->l('Text area', 'CustomFieldsInputTextarea');
    }

    public function installSQL()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getStaticConfigurationSubformData()
    {
        return [
            'formats' => [
                'text' => ['name' => $this->l('Text only', 'CustomFieldsInputTextarea')],
                'html' => ['name' => $this->l('HTML allowed', 'CustomFieldsInputTextarea')],
                'wysiwyg' => ['name' => $this->l('WYSIWYG editor', 'CustomFieldsInputTextarea')],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationSubformData()
    {
        $data = $this->getStaticConfigurationSubformData();
        $conf = $this->getConfigurationArray();
        $data['currentConf'] = [
            'format' => $conf['format'],
            'maxlength' => $conf['maxlength'],
            'default_value' => $this->default_value,
            'translatable' => $this->is_translatable,
        ];

        return $data;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopException
     */
    public function setConfigurationByArray($newConf)
    {
        if (empty($newConf['format'])) {
            return [$this->l('No format was specified for the input.', 'CustomFieldsInputTextarea')];
        }

        $subformData = $this->getStaticConfigurationSubformData();
        if (!isset($subformData['formats'][$newConf['format']])) {
            return [$this->l('The specified format is invalid.', 'CustomFieldsInputTextarea')];
        }

        if ($newConf['maxlength']) {
            $newConf['maxlength'] = (int) $newConf['maxlength'];
        } else {
            $newConf['maxlength'] = null;
        }

        if ($newConf['default_value']) {
            $errors = [];
            foreach ($newConf['default_value'] as &$defaultValue) {
                // Check length
                if ($newConf['maxlength'] > 0 && Tools::strlen($defaultValue) > $newConf['maxlength']) {
                    $errors[] = $this->l('The default value is too long.', 'CustomFieldsInputTextarea');
                }

                // Format value
                $this->formatInputValue($defaultValue, $newConf['format']);
            }

            if (!empty($errors)) {
                return $errors;
            }
        } else {
            foreach (Language::getIDs() as $idLang) {
                $newConf['default_value'][$idLang] = null;
            }
        }

        // If the input BECOMES translatable
        if (!$this->is_translatable && !empty($newConf['translatable'])) {
            // Copy untranslated values to default language values
            $this->inputTextareaService->copyUntranslatedValuesToDefaultLanguage($this->id);
        }

        $this->is_translatable = !empty($newConf['translatable']);
        unset($newConf['translatable']); // Don't save this in configuration array, there's a field for that

        $this->default_value = $newConf['default_value'];
        unset($newConf['default_value']); // Don't save this in configuration array, there's a field for that

        $this->configurationArray = $newConf;

        return $this->save() == true;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopException
     */
    public function setValue($value, $idObject)
    {
        $ret = $this->isValidValue($value, $idObject);
        if (true !== $ret) {
            return $ret;
        }

        if (!$this->is_translatable) {
            $inputValue = $this->inputTextareaService->getInputValue($this->id, $idObject, null);
            $inputValue->value = $value;
            $inputValue->save();
        } else {
            foreach ($value as $idLang => $val) {
                $inputValue = $this->inputTextareaService->getInputValue($this->id, $idObject, $idLang);
                $inputValue->value = $val;
                $inputValue->save();
            }
        }

        return true;
    }

    /**
     * Gets the input value from DB
     *
     * @param mixed $idObject
     * @param mixed $idLang when input is translatable :
     *                      if false, will return all saved translatable values;
     *                      else, will return value for corresponding id_lang
     *
     * @return mixed
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function getValue($idObject, $idLang = false)
    {
        if (!$this->is_translatable) {
            $inputValue = $this->inputTextareaService->getInputValue($this->id, $idObject, null);

            return $inputValue->value;
        }

        if (false !== $idLang) {
            $inputValue = $this->inputTextareaService->getInputValue($this->id, $idObject, $idLang);

            return $inputValue->value;
        }

        $inputValues = $this->inputTextareaService->getInputTranslatableValues($this->id, $idObject);
        if (!$inputValues) {
            return [];
        }

        $inputValuesLang = [];
        foreach ($inputValues as $inputValue) {
            /* @var $inputValue CustomFieldsInputTextareaValue */
            $inputValuesLang[$inputValue->id_lang] = $inputValue->value;
        }

        return $inputValuesLang;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $idObject
     * @param string|null $customTemplate
     *
     * @return string
     *
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function getValueHtml($idObject, $customTemplate = null)
    {
        $conf = $this->getConfigurationArray();
        $idLangDefault = (int) Configuration::get('PS_LANG_DEFAULT');
        $value = $this->getValue($idObject, $this->is_translatable ? Context::getContext()->language->id : null);

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

        if ($customTemplate != null && file_exists($customTemplate)) {
            $tpl = Context::getContext()->smarty->createTemplate($customTemplate);
        } else {
            $tpl = Context::getContext()->smarty->createTemplate($this->getValueHtmlTemplatePath());
        }

        $tpl->caching = 0;
        $tpl->assign([
            'value' => $value,
            'format' => $conf['format'],
        ]);

        return $tpl->fetch();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \PrestaShopException
     * @throws \SmartyException
     */
    public function getInputHtml($idObject, $codeObject)
    {
        $conf = $this->getConfigurationArray();
        $idLangDefault = Configuration::get('PS_LANG_DEFAULT');
        $current_lang = Context::getContext()->language->id;

        // Retrieve values
        $value = $this->getValue((int) $idObject);
        if (!$value) {
            if ($this->is_translatable) {
                $value = $this->default_value;
            } else {
                $value = $this->default_value[$idLangDefault];
            }
        }

        // Add JS; not always included by default
        $jsFiles = [];
        $jsDef = [];
        if ($conf['format'] == 'wysiwyg') {
            $jsFiles = [
                _PS_JS_DIR_ . 'tiny_mce/tiny_mce.js',
                _PS_JS_DIR_ . 'admin/tinymce.inc.js',
                // Exists only on PS1.7, so we'll have some JS in the PS1.6 template
                _PS_JS_DIR_ . 'admin/tinymce_loader.js',
            ];
        } else {
            // Add autosize plugin; not always included by default
            // It also allows to enter newlines by pressing "enter" rather than "shift+enter"
            /** @var array|bool|string $autorizePluginPath */
            $autorizePluginPath = Media::getJqueryPluginPath('autosize');
            $jsFiles = [
                $autorizePluginPath['js'],
            ];
        }

        $jsFiles[] = _PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/textarea-input-html.js';

        // Process js_files and js_def to avoid adding them twice
        foreach ($jsFiles as $key => $file) {
            $file = explode('?', $file);
            $version = '';
            if (isset($file[1]) && $file[1]) {
                $version = $file[1];
            }
            $file = $file[0];
            $jsPath = Media::getJSPath($file);

            if ($jsPath) {
                if (!in_array($jsPath, Context::getContext()->controller->js_files)) {
                    Context::getContext()->controller->js_files[] = $jsPath . ($version ? '?' . $version : '');
                    if (!Context::getContext()->controller->ajax) {
                        unset($jsFiles[$key]);
                    }
                } else {
                    unset($jsFiles[$key]);
                }
            } else {
                unset($jsFiles[$key]);
            }
        }

        $currentJsDef = Media::getJsDef();
//        TODO verify old version and the necessity of this code in current version
//        foreach ($jsDef as $key => $def) {
//            if (!empty($currentJsDef[$key]) && isset($jsDef[$key])) {
//                unset($jsDef[$key]);
//            } else {
//                Media::addJsDef($def);
//            }
//        }

        $instructions = '';
        if (!empty($this->instructions[$current_lang])) {
            $instructions = $this->instructions[$current_lang];
        } elseif (!empty($this->instructions[$idLangDefault])) {
            $instructions = $this->instructions[$idLangDefault];
        }

        $tpl = Context::getContext()->smarty->createTemplate($this->getInputHtmlTemplatePath($codeObject));
        $tpl->caching = 0;
        $tpl->assign([
            'js_files' => $jsFiles,
            'totcustomfields_js_def' => $jsDef,
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'required' => $this->required,
            'instructions' => $instructions,
            'value' => $value,
            'translatable' => $this->is_translatable,
            'defaultLang' => $idLangDefault,
            'currentLang' => $current_lang,
            'maxlength' => $conf['maxlength'],
            'format' => $conf['format'],
            'default_value' => $this->default_value,
        ]);

        if ($this->is_translatable) {
            $tpl->assign('languages', Context::getContext()->controller->getLanguages());
        }

        return $tpl->fetch();
    }

    /**
     * Validates a value according to format, length, etc...
     *
     * @param string|array $value
     * @param int|null $idObject
     *
     * @return bool|array true or an array of errors
     *
     * @throws \Exception
     */
    public function isValidValue(&$value, $idObject = null)
    {
        $errors = [];
        $conf = $this->getConfigurationArray();

        if (!$this->is_translatable) {
            if ($this->required) {
                if (empty($value)) {
                    return [
                        sprintf($this->l('Field %s : No value specified for required field', 'CustomFieldsInputTextarea'), $this->name),
                    ];
                }
            }

            // Check length
            if ($conf['maxlength'] > 0 && Tools::strlen($value) > $conf['maxlength']) {
                $errors[] = sprintf($this->l('Field %s : Text is too long', 'CustomFieldsInputTextarea'), $this->name);
            }

            // Format value
            $this->formatInputValue($value, $conf['format']);

            return count($errors) ? $errors : true;
        }

        $languages = Context::getContext()->controller->getLanguages();
        $idLangDefault = (int) Configuration::get('PS_LANG_DEFAULT');

        if (!is_array($value)) {
            $value = [$idLangDefault => $value];
        }

        if ($this->required) {
            if (empty($value)) {
                return [
                    sprintf($this->l('Field %s : No value specified for required field', 'CustomFieldsInputTextarea'), $this->name),
                ];
            } elseif (empty($value[$idLangDefault])) {
                return [
                    sprintf($this->l('Field %s : Missing value for default language "%s"', 'CustomFieldsInputTextarea'), $this->name, Language::getIsoById($idLangDefault)),
                ];
            }
        }

        foreach ($languages as $lang) {
            if (!isset($value[$lang['id_lang']])) {
                $value[$lang['id_lang']] = $value[$idLangDefault];
            }

            // Check length
            if ($conf['maxlength'] > 0 && Tools::strlen($value[$lang['id_lang']]) > $conf['maxlength']) {
                $errors[] = sprintf($this->l('Field %s : Text is too long', 'CustomFieldsInputTextarea'), $this->name);
            }

            // Format value
            $this->formatInputValue($value, $conf['format']);
        }

        return count($errors) ? $errors : true;
    }

    /**
     * Formats a value according to the specified format
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function formatInputValue(&$value, $format)
    {
        if (!is_array($value)) {
            switch ($format) {
                case 'text':
                    $value = strip_tags($value);
                    break;
                default:
                    break;
            }
        } else {
            foreach ($value as $key => $v) {
                switch ($format) {
                    case 'text':
                        $value[$key] = strip_tags($value[$key]);
                        break;
                    default:
                        break;
                }
            }
        }

        return $value;
    }

    public function delete()
    {
        $return = true;

        // Delete values
        $values = $this->inputTextareaService->getAllInputValues($this->id);
        foreach ($values as $val) {
            $return &= $val->delete();
        }

        return $return && parent::delete();
    }

    public function getValueHtmlTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/front/custom_fields/inputs/valueTextareaInput.tpl';
    }

    public function getConfigurationSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/config/formTextareaInput.tpl';
    }
}
