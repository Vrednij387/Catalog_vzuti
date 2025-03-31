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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplayNativeHooks;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplaySmarty;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsObjectService;
use TotcustomfieldsClasslib\Hook\AbstractHook;
use AdminLegacyLayoutControllerCore;
use Context;

class HookLayout extends AbstractHook
{
    /**
     * @var CustomFieldsObjectService
     */
    protected $objectsService;

    /**
     * @var CustomFieldsDisplayNativeHooks
     */
    protected $customFieldsDisplayNativeHooks;

    const AVAILABLE_HOOKS = [
        'displayFooter',
        'displayHeader',
        'displayLeftColumn',
        'displayRightColumn',
        'displayBackOfficeHeader',
    ];

    public function __construct($module)
    {
        parent::__construct($module);
        $this->objectsService = new CustomFieldsObjectService();
        $this->customFieldsDisplayNativeHooks = new CustomFieldsDisplayNativeHooks();
    }

    public function displayFooter($params)
    {
        $customFieldsDisplayNativeHooks = new CustomFieldsDisplayNativeHooks();

        return $customFieldsDisplayNativeHooks->displayInputs($this->objectsService->getCodeFromContext(), __FUNCTION__);
    }

    public function displayHeader($params)
    {
        $codeObject = $this->objectsService->getCodeFromContext();
        if (!$codeObject) {
            return;
        }
        $customFieldsDisplaySmarty = new CustomFieldsDisplaySmarty();
        $customFieldsDisplaySmarty->displayInputs($codeObject);
    }

    public function displayLeftColumn($params)
    {
        $customFieldsDisplayNativeHooks = new CustomFieldsDisplayNativeHooks();

        return $customFieldsDisplayNativeHooks->displayInputs($this->objectsService->getCodeFromContext(), __FUNCTION__);
    }

    public function displayRightColumn($params)
    {
        $customFieldsDisplayNativeHooks = new CustomFieldsDisplayNativeHooks();

        return $customFieldsDisplayNativeHooks->displayInputs($this->objectsService->getCodeFromContext(), __FUNCTION__);
    }

    public function displayBackOfficeHeader()
    {
        // This adds the CSS to add an icon to the admin tab
        Context::getContext()->controller->addCSS(_PS_MODULE_DIR_ . 'totcustomfields/views/css/custom_fields/tab.css');
    }
}
