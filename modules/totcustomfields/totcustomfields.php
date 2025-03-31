<?php
/**
 * Copyright since 2022 totcustomfields
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tech@202-ecommerce.com so we can send you a copy immediately.
 *
 * @author    202 ecommerce <tech@202-ecommerce.com>
 * @copyright 2022 totcustomfields
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 */

/*
 * Check PS is installed
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'totcustomfields/vendor/autoload.php';

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use Totcustomfields\Hook\TotCustomfieldsHookDispatcher;
use TotcustomfieldsClasslib\Extensions\CustomFields\CustomFieldsExtension;
use TotcustomfieldsClasslib\Module\Module;

class TotCustomFields extends Module implements WidgetInterface
{
    /**
     * This module requires at least PHP version
     *
     * @var string
     */
    public $php_version_required = '5.6';

    /** @var string Admin tab corresponding to the module */
    public $tab = 'front_office_features';

    /** @var string Version */
    public $version = '2.2.3';

    /** @var string author of the module */
    public $author = '202 ecommerce';

    /** @var int need_instance */
    public $need_instance = 0;

    /** @var array filled with known compliant PS versions */
    public $ps_versions_compliancy = [
        'min' => '1.7.1.0',
        'max' => _PS_VERSION_,
    ];

    /**
     * List of objectModel used in this Module
     *
     * @var array
     */
    public $objectModels = [];

    /**
     * List of hooks used in this Module
     *
     * @var array
     */
    public $hooks = [];

    public $extensions = [
        CustomFieldsExtension::class,
    ];

    public $secure_key;

    public function __construct()
    {
        // Module information
        $this->name = 'totcustomfields';
        $this->version = '2.2.3';
        $this->author = '202 ecommerce';
        $this->tab = 'front_office_features';
        $this->module_key = 'e8271f93240caa3de65cbdc42046fced';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Advanced Custom Fields : create new fields quickly');
        $this->description = $this->l('This module allows you to create your custom fields and to show them on 
            Product, Category or Order pages in the Front Office and the Back Office. 6 types of fields are 
            available : text, text area, image, video, select and checkbox.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall ?');
        $this->hookDispatcher = new TotCustomfieldsHookDispatcher($this);
        $this->hooks = array_merge($this->hooks, $this->hookDispatcher->getAvailableHooks());
    }

    public function getContent()
    {
        $configurationLink = Context::getContext()->link->getAdminLink('AdminTotcustomfieldsCustomFieldsConfiguration');
        Tools::redirectAdmin($configurationLink);
    }
}
