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
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputVideoService;
use Configuration;
use Context;
use Language;
use Translate;

class CustomFieldsInputVideo extends CustomFieldsInput
{
    /*
     * Configuration array(
     *      'format' => int,
     *      'maxlength' => int,
     *      'default_value' => string,
     *      'translatable' => bool // Received during configuration, but not in array
     * )
     */

    protected static $inputHtmlTemplateName = 'videoInput.tpl';

    protected $inputVideoService;

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);
        $this->inputVideoService = new CustomFieldsInputVideoService();
    }

    public function getListPosition()
    {
        return 4;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeCode()
    {
        return 'video';
    }

    /**
     * {@inheritdoc}
     */
    public function getInputTypeName()
    {
        return $this->l('Video (ex: iframe YouTube)', 'CustomFieldsInputVideo');
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
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationSubformData()
    {
        $data = $this->getStaticConfigurationSubformData();
        $data['currentConf'] = [
            'default_value' => is_array($this->default_value)
                ? $this->default_value[Configuration::get('PS_LANG_DEFAULT')]
                : $this->default_value,
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
        // If the input BECOMES translatable
        if (!$this->is_translatable && !empty($newConf['translatable'])) {
            // Copy untranslated values to default language values
            $this->inputVideoService->copyUntranslatedValuesToDefaultLanguage($this->id);
        }

        $this->is_translatable = !empty($newConf['translatable']);
        unset($newConf['translatable']); // Don't save this in configuration array, there's a field for that

        $this->default_value = $newConf['default_value'] ? $newConf['default_value'] : null;
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
            $inputValue = $this->inputVideoService->getInputValue($this->id, $idObject, null);
            $inputValue->value = $value;
            $inputValue->save();
        } else {
            foreach ($value as $idLang => $val) {
                $inputValue = $this->inputVideoService->getInputValue($this->id, $idObject, $idLang);
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
            $inputValue = $this->inputVideoService->getInputValue($this->id, $idObject, null);

            return $inputValue->value;
        }

        if (false !== $idLang) {
            $inputValue = $this->inputVideoService->getInputValue($this->id, $idObject, $idLang);

            return $inputValue->value;
        }

        $inputValues = $this->inputVideoService->getInputTranslatableValues($this->id, $idObject);
        if (!$inputValues) {
            return [];
        }

        $inputValuesLang = [];
        foreach ($inputValues as $inputValue) {
            /* @var $inputValue CustomFieldsInputVideoValue */
            $inputValuesLang[$inputValue->id_lang] = $inputValue->value;
        }

        return $inputValuesLang;
    }

    /**
     * {@inheritdoc}
     *
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

        if ($customTemplate != null && file_exists($customTemplate)) {
            $tpl = Context::getContext()->smarty->createTemplate($customTemplate);
        } else {
            $tpl = Context::getContext()->smarty->createTemplate($this->getValueHtmlTemplatePath());
        }

        $tpl->caching = 0;
        $tpl->assign([
            'value' => $value,
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
        $idLangDefault = Configuration::get('PS_LANG_DEFAULT');
        $currentLang = Context::getContext()->language->id;

        // Retrieve values
        $inputValues = $this->getValue((int) $idObject);
        if ($inputValues) {
            $value = $inputValues;
        } else {
            if ($this->is_translatable) {
                $value = $this->default_value;
            } else {
                $value = $this->default_value[$idLangDefault];
            }
        }

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
            'translatable' => $this->is_translatable,
            'defaultLang' => $idLangDefault,
            'currentLang' => $currentLang,
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

        if (!$this->is_translatable) {
            if ($this->required) {
                if (empty($value)) {
                    return [
                        sprintf($this->l('Field %s : No value specified for required field', 'CustomFieldsInputVideo'), $this->name),
                    ];
                }
            }

            return true;
        }

        $idLangDefault = (int) Configuration::get('PS_LANG_DEFAULT');

        if (!is_array($value)) {
            $value = [$idLangDefault => $value];
        }

        if ($this->required) {
            if (empty($value)) {
                return [
                    sprintf($this->l('Field %s : No value specified for required field', 'CustomFieldsInputVideo'), $this->name),
                ];
            } elseif (empty($value[$idLangDefault])) {
                return [
                    sprintf($this->l('Field %s : Missing value for default language "%s"', 'CustomFieldsInputVideo'), $this->name, Language::getIsoById($idLangDefault)),
                ];
            }
        }

        return true;
    }

    public function delete()
    {
        $return = true;

        // Delete values
        $values = $this->inputVideoService->getAllInputValues($this->id);
        foreach ($values as $val) {
            $return &= $val->delete();
        }

        return $return && parent::delete();
    }

    public function getValueHtmlTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/front/custom_fields/inputs/valueVideoInput.tpl';
    }

    public function getConfigurationSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/config/formVideoInput.tpl';
    }
}
