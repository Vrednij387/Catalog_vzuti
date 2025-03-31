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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Hook;

use TotcustomfieldsClasslib\Hook\AbstractHook;
use AdminLegacyLayoutControllerCore;
use Context;

class HookAdmin extends AbstractHook
{
    const AVAILABLE_HOOKS = [
        'actionAdminControllerSetMedia',
    ];

    public function actionAdminControllerSetMedia($params)
    {
        $controller = Context::getContext()->controller;
        if ($controller instanceof AdminLegacyLayoutControllerCore) {
            if ($controller->controller_name != 'AdminCategories'
                && $controller->controller_name != 'AdminProducts') {
                return;
            }
            $controller->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/custom_fields/textarea-input-html.js');
            if ($controller->controller_name == 'AdminProducts') {
                return;
            }
            $controller->addJqueryPlugin('autosize');
            $controller->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
            $controller->addJS(_PS_JS_DIR_ . 'admin/tinymce.inc.js');
            $controller->addJS(_PS_JS_DIR_ . 'admin/tinymce_loader.js');
            $controller->addJS(_PS_MODULE_DIR_ . 'totcustomfields/views/js/totcf-tools.js');
        }
    }
}
