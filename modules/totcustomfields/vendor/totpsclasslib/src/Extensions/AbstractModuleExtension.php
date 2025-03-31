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

namespace TotcustomfieldsClasslib\Extensions;

use TotcustomfieldsClasslib\Hook\AbstractHookDispatcher;
use TotcustomfieldsClasslib\Module\Module;
use TotcustomfieldsClasslib\Utils\Dependency\ComposerDependency;

abstract class AbstractModuleExtension
{
    // region Fields

    public $name;

    /**
     * @var Module
     */
    public $module;

    public $objectModels = [];

    public $hooks = [];

    public $extensionAdminControllers = [];

    public $controllers = [];

    public $cronTasks = [];

    /**
     * @var array
     */
    protected $packages = [];

    /**
     * List of mandatory extensions
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * @var AbstractHookDispatcher
     */
    protected $hookDispatcher = null;

    // endregion

    public function __construct($module = null)
    {
        $this->module = $module;
    }

    /**
     * Set the module object for the extension
     *
     * @param Module $module
     *
     * @return AbstractModuleExtension
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Fetch Smarty template
     *
     * @param string $templatePath
     * @param null $cache_id
     * @param null $compile_id
     *
     * @return mixed|string
     *
     * @throws \SmartyException
     *
     * @todo Maybe this method should belong to classlib Module class
     *       so we'd have a unified way of calling the fetch method
     *       doing `$this->module->fetch(...)`
     */
    public function fetch($templatePath, $cache_id = null, $compile_id = null)
    {
        if (version_compare(_PS_VERSION_, '1.7', '>')) {
            return $this->module->fetch($templatePath, $cache_id, $compile_id);
        }

        return \Context::getContext()->smarty->fetch($templatePath, $cache_id, $compile_id);
    }

    /**
     * Do shmth while each initialisation of extension, must be overrided in nested classes
     *
     * @return void
     *
     * @throws \PrestaShopException
     */
    public function initExtension()
    {
        if (!empty($this->packages)) {
            $composerDependency = new ComposerDependency();
            $composerDependency->verifyDependencies($this->packages);
        }

        foreach ($this->dependencies as $dependency) {
            if (!in_array($dependency, $this->module->extensions)) {
                throw new \PrestaShopException(sprintf('Extension %s not found in module, add it please', $dependency));
            }
        }
    }

    /**
     * Do smth during the installation process
     *
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * Do smth during the uninstall action
     *
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * Helps to manage hooks and widgets
     *watc
     *
     * @return AbstractHookDispatcher
     */
    public function getHookDispatcher()
    {
        return $this->hookDispatcher;
    }

    /**
     * @return array
     */
    public function getExtensionAdminControllers()
    {
        return $this->extensionAdminControllers;
    }
}
