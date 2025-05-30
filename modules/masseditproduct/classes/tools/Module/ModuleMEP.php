<?php
/**
 * 2012-2022 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2012-2019 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class ModuleMEP extends Module
{
    /**
     * @var array
     */
    public $hooks = [];

    /**
     * @var array
     */
    public $classes = [];

    /**
     * @var array
     */
    public $config = [];

    /**
     * @var array
     */
    public $tabs = [];

    public $documentation = true;
    public $documentation_type = null;

    public $install_fixtures = false;

    const DOCUMENTATION_TYPE_TAB = 'tab';
    const DOCUMENTATION_TYPE_SIMPLE = 'simple';

    public function __construct()
    {
        $this->name = ToolsModuleMEP::getModNameForPath(__FILE__);
        $this->documentation_type = self::DOCUMENTATION_TYPE_SIMPLE;
        $this->bootstrap = true;

        if (count($this->tabs)) {
            $this->tabs = $this->formatTabs($this->tabs);
        }
        parent::__construct();
    }

    /**
     * @return bool
     */
    public function registerHooks()
    {
        foreach ($this->hooks as $hook) {
            $this->registerHook($hook);
        }
        return true;
    }

    /**
     * @return bool
     */
    public function installClasses()
    {
        foreach ($this->classes as $class) {
            HelperDbMEP::loadClass($class)->installDb();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function uninstallClasses()
    {
        foreach ($this->classes as $class) {
            HelperDbMEP::loadClass($class)->uninstallDb();
        }
        return true;
    }

    /**
     * @return bool
     */
    public function installConfig()
    {
        foreach ($this->config as $name => $value) {
            $type = (is_array($this->module->config[$name]) ? ConfMEP::TYPE_ARRAY : ConfMEP::TYPE_STRING);
            ConfMEP::setConf($name, $value, $type);
        }
        return true;
    }

    /**
     * @return bool
     */
    public function uninstallConfig()
    {
        foreach (array_keys($this->config) as $name) {
            ConfMEP::deleteConf($name);
        }
        return true;
    }

    /**
     * @param array $tabs
     * @return array
     */
    public function formatTabs($tabs)
    {
        if (version_compare(_PS_VERSION_, '1.7.1.0', '>=')) {
            foreach ($tabs as &$tab) {
                if (!is_array($tab['name'])) {
                    $tab['name'] = ['en' => $tab['name']];
                }

                $languages = ToolsModuleMEP::getLanguages(false);
                $name = [];
                foreach ($languages as $language) {
                    $name[$language['locale']] = (isset($tab['name'][$language['iso_code']])
                        ? $tab['name'][$language['iso_code']] :
                        $tab['name']['en']);
                }

                $tab = [
                    'class_name' => $tab['tab'],
                    'ParentClassName' => $tab['parent'],
                    'name' => $name,
                    'visible' => (!isset($tab['visible']) ? true : $tab['visible']),
                    'icon' => (!isset($tab['icon']) ? '' : $tab['icon']),
                ];
            }
        }
        return $tabs;
    }

    /**
     * @return bool
     */
    public function installTabs()
    {
        if (version_compare(_PS_VERSION_, '1.7.1.0', '<')) {
            foreach ($this->tabs as $tab) {
                ToolsModuleMEP::createTab(
                    $this->name,
                    $tab['tab'],
                    $tab['parent'],
                    $tab['name'],
                    isset($tab['visible']) ? !$tab['visible'] : false
                );
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function uninstallTabs()
    {
        foreach ($this->tabs as $tab) {
            ToolsModuleMEP::deleteTab(
                isset($tab['tab']) ? $tab['tab'] : $tab['class_name']
            );
        }
        return true;
    }

    /**
     * @return bool
     */
    public function install()
    {
        return parent::install()
        && $this->registerHooks()
        && $this->installClasses()
        && $this->importFixtures()
        && $this->installConfig()
        && $this->installTabs();
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall()
        && $this->uninstallClasses()
        && $this->uninstallConfig()
        && $this->uninstallTabs();
    }

    public function getDocumentation()
    {
        DocumentationMEP::assignDocumentation();
        $return_back_link = '#';
        if (count($this->tabs)) {
            $return_back_link = $this->context->link->getAdminLink($this->tabs[0]['class_name']);
        }

        $this->context->smarty->assign('return_back_link', $return_back_link);
        return ToolsModuleMEP::fetchTemplate('admin/documentation.tpl');
    }

    public function getContent()
    {
        if (!$this->documentation) {
            return $this->getContentTab();
        } else {
            if ($this->documentation_type == self::DOCUMENTATION_TYPE_SIMPLE) {
                return $this->getDocumentation();
            }
            SmartyMEP::registerSmartyFunctions();
            $this->context->smarty->assign([
                'content_tab' => $this->getContentTab(),
                'documentation' => $this->getDocumentation(),
            ]);
            return ToolsModuleMEP::fetchTemplate('admin/content.tpl');
        }
    }

    public function getContentTab()
    {
    }

    public function importFixtures()
    {
        if (!$this->install_fixtures) {
            return true;
        }
        $fixture_class = FixtureMEP::getInstance($this->name);

        foreach ($this->classes as $class_name) {
            $fixture_class->importEntity($class_name);
        }
        return true;
    }
}
