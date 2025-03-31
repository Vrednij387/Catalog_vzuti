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
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Objects\CustomFieldsObjectProduct;
use TotcustomfieldsClasslib\Hook\AbstractHook;
use Context;

class HookFrontProduct extends AbstractHook
{
    /**
     * @var CustomFieldsDisplayNativeHooks
     */
    protected $customFieldsDisplayNativeHooks;

    const AVAILABLE_HOOKS = [
        'displayLeftColumnProduct',
        'displayRightColumnProduct',
        'displayProductAdditionalInfo',
        'displayAfterProductThumbs',
        'displayProductExtraContent',
        'displayFooterProduct',
        'displayProductActions',
        'displayReassurance',
    ];

    public function __construct($module)
    {
        parent::__construct($module);
        $this->customFieldsDisplayNativeHooks = new CustomFieldsDisplayNativeHooks();
    }

    public function displayLeftColumnProduct($params)
    {
        return $this->customFieldsDisplayNativeHooks->displayInputs(CustomFieldsObjectProduct::OBJECT_CODE, __FUNCTION__);
    }

    public function displayRightColumnProduct($params)
    {
        return $this->customFieldsDisplayNativeHooks->displayInputs(CustomFieldsObjectProduct::OBJECT_CODE, __FUNCTION__);
    }

    public function displayProductAdditionalInfo($params)
    {
        return $this->customFieldsDisplayNativeHooks->displayInputs(CustomFieldsObjectProduct::OBJECT_CODE, __FUNCTION__);
    }

    public function displayAfterProductThumbs($params)
    {
        return $this->customFieldsDisplayNativeHooks->displayInputs(CustomFieldsObjectProduct::OBJECT_CODE, __FUNCTION__);
    }

    public function displayProductExtraContent($params)
    {
        return $this->customFieldsDisplayNativeHooks->displayInputs(CustomFieldsObjectProduct::OBJECT_CODE, __FUNCTION__);
    }

    public function displayFooterProduct($params)
    {
        return $this->customFieldsDisplayNativeHooks->displayInputs(CustomFieldsObjectProduct::OBJECT_CODE, __FUNCTION__);
    }

    public function displayProductActions($params)
    {
        return $this->customFieldsDisplayNativeHooks->displayInputs(CustomFieldsObjectProduct::OBJECT_CODE, __FUNCTION__);
    }

    public function displayReassurance($params)
    {
        $controller = Context::getContext()->controller;
        if (!($controller instanceof \ProductController)) {
            return;
        }

        return $this->customFieldsDisplayNativeHooks->displayInputs(CustomFieldsObjectProduct::OBJECT_CODE, __FUNCTION__);
    }
}
