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

use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsObjectService;
use TotcustomfieldsClasslib\Hook\AbstractHook;

class HookAdminProduct extends AbstractHook
{
    /**
     * @var CustomFieldsObjectService
     */
    protected $objectsService;

    const AVAILABLE_HOOKS = [
        'displayAdminProductsExtra',
        'displayAdminProductsMainStepLeftColumnBottom',
        'displayAdminProductsMainStepLeftColumnMiddle',
        'displayAdminProductsMainStepRightColumnBottom',
        'displayAdminProductsOptionsStepBottom',
        'displayAdminProductsOptionsStepTop',
        'displayAdminProductsPriceStepBottom',
        'displayAdminProductsQuantitiesStepBottom',
        'displayAdminProductsSeoStepBottom',
        'displayAdminProductsShippingStepBottom',
    ];

    public function __construct($module)
    {
        parent::__construct($module);
        $this->objectsService = new CustomFieldsObjectService();
    }

    public function displayAdminProductsExtra($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsMainStepLeftColumnBottom($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsMainStepLeftColumnMiddle($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsMainStepRightColumnBottom($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsOptionsStepBottom($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsOptionsStepTop($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsPriceStepBottom($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsQuantitiesStepBottom($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsSeoStepBottom($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }

    public function displayAdminProductsShippingStepBottom($params)
    {
        return $this->objectsService->displayObjectBackOfficeInputs('product', $params, __FUNCTION__);
    }
}
