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
class HelperModuleMEP
{
    public static function addCSS($css_uri, $css_media_type = 'all', $offset = null, $check_path = true)
    {
        Context::getContext()->controller->addCSS(
            _MODULE_DIR_ . ToolsModuleMEP::getModNameForPath(__FILE__)
            . '/views/css/' . $css_uri,
            $css_media_type,
            $offset,
            $check_path
        );
    }

    public static function addJS($js_uri, $check_path = true)
    {
        Context::getContext()->controller->addJS(
            _MODULE_DIR_ . ToolsModuleMEP::getModNameForPath(__FILE__)
            . '/views/js/' . $js_uri,
            $check_path
        );
    }

    /**
     * @return string
     */
    public static function getModuleTabAdminLink()
    {
        /**
         * @var $module Module
         */
        $module = Module::getInstanceByName(ToolsModuleMEP::getModNameForPath(__FILE__));
        return Context::getContext()->link->getAdminLink(
            'AdminModules',
            true
        ) . '&configure=' . ToolsModuleMEP::getModNameForPath(__FILE__)
        . '&tab_module=' . $module->name . '&tab_module=' . $module->tab . '&module_name=' . $module->name;
    }

    public static function createAjaxApiCall($class)
    {
        $method = Tools::getValue('method');
        $call_method = 'ajaxProcess' . ToolsModuleMEP::toCamelCase($method, 1);
        if (method_exists($class, $call_method)) {
            try {
                $result = call_user_func([$class, $call_method]);
                exit(json_encode([
                    'hasError' => LoggerMEP::getInstance()->hasError(),
                    'result' => $result,
                    'log' => LoggerMEP::getInstance()->getMessages(),
                ]));
            } catch (Exception $e) {
                LoggerMEP::getInstance()->exception($e);
                exit(json_encode([
                    'hasError' => LoggerMEP::getInstance()->hasError(),
                    'log' => LoggerMEP::getInstance()->getMessages(),
                ]));
            }
        }
    }
}
