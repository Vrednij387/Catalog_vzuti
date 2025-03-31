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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes;

use TotcustomfieldsClasslib\Database\Index\IndexType;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsDisplayService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsObjectService;
use TotcustomfieldsClasslib\Utils\Translate\TranslateTrait;
use Db;
use DbQuery;
use ObjectModel;
use Shop;
use Tools;

/**
 * Class TotCustomFieldsInput
 *
 * loadInputTypes MUST be called prior to doing anything with this class
 *
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/imageInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/textareaInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/textInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/videoInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/selectInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/checkboxInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/product/selectInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/product/checkboxInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/product/imageInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/product/textareaInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/product/textInput.tpl'
 * @include 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/html/product/videoInput.tpl'
 */
abstract class CustomFieldsInput extends ObjectModel
{
    use TranslateTrait;

    const INPUT_HTML_TEMPLATES_DIR = '/views/templates/admin/custom_fields/partials/inputs/html/';

    const INPUT_CONFIGURATION_SUBFORM_TEMAPLTES_PATH = '';

    // region Fields

    /** @var string Retrieved from display_input table */
    protected $code_display;

    public $code_input_type;

    public $code_object;

    public $name;

    public $code;

    public $required;

    public $instructions;

    public $is_translatable; // 0 by default; use it in concrete class when needed

    public $default_value; // NULL by default; use it in concrete class when needed

    /**
     * @var bool|null If set to 1, will prevent deletion by the user and make the
     *                "code" field read-only in configuration. Cannot be set using the form.
     */
    public $unremovable;

    public $active;

    /**
     * @var array|string This should be a JSON string
     */
    public $configuration;

    /**
     * @var array|string This should be the decoded
     */
    public $configurationArray;

    // endregion

    protected $inputService;

    protected $displayService;

    protected $objectService;

    /**
     * @var string the name of this input type configuration template
     */
    protected static $subformTemplateName = '';

    /**
     * @var string the name of this input type HTML template used in BO
     */
    protected static $inputHtmlTemplateName = '';

    public static $definition = [
        'table' => 'totcustomfields_input',
        'primary' => 'id_input',
        'multilang' => true,
        'multishop' => true,
        'multilang_shop' => true,
        'fields' => [
            'code_input_type' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
            ],
            'code_object' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
            ],
            'name' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => true,
            ],
            'code' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName',
                'required' => true,
            ],
            'required' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true,
            ],
            'instructions' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'lang' => true,
            ],
            'is_translatable' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true,
                'default' => '0',
            ],
            'default_value' => [
                'type' => self::TYPE_HTML,
                'validate' => 'isString',
                'allow_null' => true,
                'lang' => true,
            ],
            'unremovable' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true,
                'default' => '0',
            ],
            'active' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool',
                'required' => true,
            ],
            'configuration' => [
                'type' => self::TYPE_HTML,
                'validate' => 'isString',
                'required' => true,
            ],
        ],
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'indexes' => [
            [
                'fields' => [
                    [
                        'column' => 'code',
                    ],
                ],
                'type' => IndexType::UNIQUE,
            ],
            [
                'fields' => [
                    [
                        'column' => 'code_object',
                    ],
                    [
                        'column' => 'active',
                    ],
                ],
            ],
        ],
    ];

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);
        $this->inputService = new CustomFieldsInputService();
        $this->displayService = new CustomFieldsDisplayService();
        $this->objectService = new CustomFieldsObjectService();
    }

    public function getListPosition()
    {
        return 0;
    }

    /**
     * Returns the code_display of this input active display
     *
     * @return false|string|null
     */
    public function getDisplayCode()
    {
        if (!$this->code_display) {
            $query = new DbQuery();
            $query->select('code_display');
            $query->from(CustomFieldsDisplay::DISPLAY_INPUT_TABLE);
            $query->where('id_input = ' . (int) $this->id);
            $query->where('active <> 0');

            $this->code_display = Db::getInstance()->getValue($query);
        }

        return $this->code_display;
    }

    /**
     * Gets the unique code for the input type
     * This method should be overridden, but there's no way to make it abstract without a warning
     *
     * @return string
     */
    public function getInputTypeCode()
    {
        return '';
    }

    /**
     * Gets the display name for the input type
     * This method should be overridden, but there's no way to make it abstract without a warning
     *
     * @return string
     */
    public function getInputTypeName()
    {
        return '';
    }

    /**
     * Installs SQL for a given type; override if needed
     *
     * @return bool
     */
    public function installSQL()
    {
        return true;
    }

    /**
     * Get configuration for this input
     *
     * @return array
     */
    public function getConfigurationArray()
    {
        if (!is_array($this->configurationArray)) {
            $this->configurationArray = json_decode($this->configuration, true);
        }

        return $this->configurationArray;
    }

    /**
     * {@inheritdoc}
     */
    public function save($null_values = false, $auto_date = true)
    {
        $this->configuration = json_encode($this->getConfigurationArray());
        if (is_null($this->unremovable)) {
            $this->unremovable = false;
        }

        if ($this->id && !empty($this->id_shop_list)) {
            $deleteShopEntriesSql =
                'DELETE FROM ' .
                _DB_PREFIX_ . self::$definition['table'] . '_shop ' .
                'WHERE id_input = ' . (int) $this->id . ' ' .
                'AND id_shop NOT IN (' . implode(', ', $this->id_shop_list) . ')';
            Db::getInstance()->query($deleteShopEntriesSql);
        }

        foreach ($this->id_shop_list as $id_shop) {
            $data = [
                'id_input' => (int) $this->id,
                'id_shop' => (int) $id_shop,
            ];

            Db::getInstance()->insert(
                self::$definition['table'] . '_shop',
                $data,
                false,
                true,
                Db::INSERT_IGNORE
            );
        }

        return parent::save($null_values, $auto_date);
    }

    public function delete()
    {
        // Delete displays
        $this->displayService->deleteAllInputDisplaysMethods($this->id);

        return parent::delete();
    }

    /**
     * Set configuration for this input; configuration data validation is done here
     *
     * @param array $conf the configuration array for this input
     *
     * @return array|bool an array of errors, or true
     */
    abstract public function setConfigurationByArray($conf);

    /**
     * Sets the input value; data validation is done here
     *
     * @param mixed $value
     * @param mixed $idObject
     *
     * @return bool|array|void
     */
    abstract public function setValue($value, $idObject);

    /**
     * Gets the input value from DB
     *
     * @param int $idObject
     * @param int|bool|null $idLang
     *
     * @return array|bool
     */
    abstract public function getValue($idObject, $idLang = false);

    /**
     * Gets the input value for an object, formatted for HTML
     *
     * @param mixed $idObject
     * @param string $customTemplate a path to a custom template
     *
     * @return mixed
     */
    abstract public function getValueHtml($idObject, $customTemplate = null);

    /**
     * Get input HTML
     *
     * @param int $idObject the object id to get the associated value
     * @param string $codeObject the object code, to load the right html template
     *
     * @return string
     */
    abstract public function getInputHtml($idObject, $codeObject);

    /**
     * Gets data for the configuration subform, with currently saved values
     *
     * @return array
     */
    abstract public function getConfigurationSubformData();

    /**
     * Checks if a value is valid or not
     *
     * @param mixed $value
     * @param int $idObject an optional id_object, which may be useful in some cases
     *
     * @return bool
     */
    abstract public function isValidValue(&$value, $idObject = null);

    /**
     * Gets data for the main configuration form, with currently saved values
     *
     * @return array
     */
    public function getConfigurationFormData()
    {
        $data = [
            'code_input_type' => $this->code_input_type,
            'code_object' => $this->code_object,
            'code_display' => $this->getDisplayCode(),
            'name' => $this->name,
            'code' => $this->code,
            'required' => $this->required,
            'instructions' => $this->instructions,
            'active' => $this->active,
            'unremovable' => $this->unremovable,
            // is_translatable is used in concrete class when needed
            // default_value is used in concrete class when needed
        ];

        return $data;
    }

    /**
     * Get subform template name, from the right folder
     *
     * @return string
     */
    public function getConfigurationSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/inputs/config/';
    }

    /**
     * Gets data for the configuration subform, with default values
     *
     * @return array
     */
    public function getStaticConfigurationSubformData()
    {
        return [];
    }

    /*TODO
     * Formats a value to display it in an AdminController summary <br/>
     * This should be overridden by subclasses if needed
     *
     * @param int $idInput the input id
     * @param string $value the value to format
     *
     * @return string
     */
    public function formatValueForAdminSummary($idInput, $value)
    {
        return Tools::htmlentitiesUTF8($value);
    }

    /**
     * @param string $codeObject
     *
     * @return bool|string
     */
    protected function getInputHtmlTemplatePath($codeObject)
    {
        if (!static::$inputHtmlTemplateName) {
            return false;
        }

        $path = _PS_MODULE_DIR_ . 'totcustomfields' . self::INPUT_HTML_TEMPLATES_DIR;

        if ($codeObject && Tools::file_exists_cache($path . $codeObject . '/' . static::$inputHtmlTemplateName)) {
            $path .= $codeObject . '/';
        }

        return $path . static::$inputHtmlTemplateName;
    }

    public function getValueHtmlTemplatePath()
    {
        return false;
    }

    public function getRawValueTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/front/custom_fields/inputs/rawValue.tpl';
    }
}
