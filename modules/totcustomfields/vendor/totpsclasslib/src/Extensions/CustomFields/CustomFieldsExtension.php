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

namespace TotcustomfieldsClasslib\Extensions\CustomFields;

use TotcustomfieldsClasslib\Actions\ExtensionActionsHandler;
use TotcustomfieldsClasslib\Extensions\AbstractModuleExtension;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Actions\CustomFieldsSaveValuesActions;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsDisplay;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsObject;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplayBackOfficeOnly;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplayNativeHooks;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplaySmarty;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplayWidget;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Hook\CustomFieldsHookDispatcher;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputCheckbox;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputCheckboxValue;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputImage;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputImageValue;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputSelect;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputSelectValue;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputText;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputTextarea;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputTextareaValue;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputTextValue;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputVideo;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputVideoValue;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders\DisplayEntityLoader;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders\InputEntityLoader;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders\ObjectEntityLoader;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Objects\CustomFieldsObjectCategory;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Objects\CustomFieldsObjectOrder;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Objects\CustomFieldsObjectProduct;
use TotcustomfieldsClasslib\Extensions\CustomFields\Controllers\Admin\AdminCustomFieldsCategoryController;
use TotcustomfieldsClasslib\Extensions\CustomFields\Controllers\Admin\AdminCustomFieldsConfigurationController;
use TotcustomfieldsClasslib\Extensions\CustomFields\Controllers\Admin\AdminCustomFieldsOrderController;
use TotcustomfieldsClasslib\Extensions\CustomFields\Controllers\Admin\AdminCustomFieldsProductController;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsDisplayService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsObjectService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsService;
use TotcustomfieldsClasslib\Hook\AbstractHookDispatcher;
use TotcustomfieldsClasslib\Utils\Translate\TranslateTrait;
use Context;
use Shop;

class CustomFieldsExtension extends AbstractModuleExtension
{
    use TranslateTrait;

    public $name = 'custom_fields';

    public $hooks = [];

    /**
     * List of objectModel used in this Module
     *
     * @var array
     */
    public $objectModels = [];

    protected $displayService;

    protected $objectsService;

    protected $inputsService;

    /**
     * List of input objectModels
     *
     * @var array
     */
    public static $inputObjectModels = [
        CustomFieldsInputText::class,
        CustomFieldsInputImage::class,
        CustomFieldsInputTextarea::class,
        CustomFieldsInputVideo::class,
        CustomFieldsInputSelect::class,
        CustomFieldsInputCheckbox::class,
    ];

    /**
     * List of input values objectModels
     *
     * @var array
     */
    public static $inputValueObjectModels = [
        CustomFieldsInputTextValue::class,
        CustomFieldsInputImageValue::class,
        CustomFieldsInputTextareaValue::class,
        CustomFieldsInputVideoValue::class,
        CustomFieldsInputSelectValue::class,
        CustomFieldsInputCheckboxValue::class,
    ];

    /**
     * @var array
     */
    public $extensionAdminControllers = [
        [
            'name' => [
                'en' => 'Advanced Custom Fields',
                'fr' => 'Champs personnalisés avancés',
                'it' => 'Campi personalizzati avanzati',
            ],
            'class_name' => 'AdminTotcustomfieldsCustomFieldsSummaries',
            'parent_class_name' => 'totcustomfields',
            'visible' => true,
        ],
        [
            'name' => [
                'en' => 'Custom Fields Configuration',
                'fr' => 'Configuration des champs personnalisés',
                'it' => 'Configurazione campi personalizzati',
            ],
            'class_name' => 'AdminTotcustomfieldsCustomFieldsConfiguration',
            'parent_class_name' => 'AdminTotcustomfieldsCustomFieldsSummaries',
            'visible' => true,
            'icon' => 'settings',
        ],
        [
            'name' => [
                'en' => 'My categories fields',
                'fr' => 'Mes champs de catégories',
                'it' => 'I miei campi Categoria',
            ],
            'class_name' => 'AdminTotcustomfieldsCustomFieldsCategory',
            'parent_class_name' => 'AdminTotcustomfieldsCustomFieldsSummaries',
            'visible' => true,
            'icon' => 'list',
        ],
        [
            'name' => [
                'en' => 'My product fields',
                'fr' => 'Mes champs de produits',
                'it' => 'I miei campi Prodotto',
            ],
            'class_name' => 'AdminTotcustomfieldsCustomFieldsProduct',
            'parent_class_name' => 'AdminTotcustomfieldsCustomFieldsSummaries',
            'visible' => true,
            'icon' => 'list',
        ],
        [
            'name' => [
                'en' => 'My order fields',
                'fr' => 'Mes champs de commandes',
                'it' => 'I miei campi Ordine',
            ],
            'class_name' => 'AdminTotcustomfieldsCustomFieldsOrder',
            'parent_class_name' => 'AdminTotcustomfieldsCustomFieldsSummaries',
            'visible' => true,
            'icon' => 'list',
        ],
    ];

    const MULTIPLE_PRODUCT_TABS = 'TOTCUSTOMFIELDS_CUSTOMFIELDS_MULTIPLE_PRODUCT_TABS';

    const PRODUCT_TAB_TITLE = 'TOTCUSTOMFIELDS_CUSTOMFIELDS_PRODUCT_TAB_TITLE';

    public function __construct($module = null)
    {
        parent::__construct($module);
        $this->displayService = new CustomFieldsDisplayService();
        $this->objectsService = new CustomFieldsObjectService();
        $this->inputsService = new CustomFieldsInputService();
        $this->hookDispatcher = new CustomFieldsHookDispatcher($this->module);

        $this->objectModels = array_merge(static::$inputObjectModels, static::$inputValueObjectModels);

        $this->hooks = array_merge($this->hooks, $this->getHookDispatcher()->getAvailableHooks());

        // So PS will manage multishop on its own for this table
        Shop::addTableAssociation('totcustomfields_input', ['type' => 'shop']);

//         Register the smarty function
        if (!isset(Context::getContext()->smarty->registered_plugins['function']['customField'])) {
            Context::getContext()->smarty->registerPlugin('function', 'customField', [CustomFieldsService::class, 'getValueFromSmarty']);
        }
    }

    /**
     * Installs the module extension
     *
     * @return bool
     */
    public function install()
    {
        if (!$this->displayService->installAllSQL() || !$this->inputsService->installAllSQL()) {
            return false;
        }

        return true;
    }

    /**
     * Load entities
     *
     * @throws \PrestaShopException
     */
    public function initExtension()
    {
        parent::initExtension();
        $inputEntityLoader = new InputEntityLoader();
        $displayEntityLoader = new DisplayEntityLoader();
        $objectEntityLoader = new ObjectEntityLoader();
        $inputEntityLoader->loadEntities();
        $displayEntityLoader->loadEntities();
        $objectEntityLoader->loadEntities();
    }
}
