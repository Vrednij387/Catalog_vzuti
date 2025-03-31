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

use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsDisplayService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsInputService;
use TotcustomfieldsClasslib\Extensions\CustomFields\Services\CustomFieldsObjectService;
use TotcustomfieldsClasslib\Utils\Translate\TranslateTrait;

/**
 * Class TotCustomFieldsObject
 *
 * Each object is a singleton
 * loadObjects MUST be called prior to doing anything with this class
 * subclasses MUST declare a static $instance attribute
 */
abstract class CustomFieldsObject
{
    use TranslateTrait;

    private static $objects = [];

    public $list_position = 0;

    protected $objectsService;

    protected $displayService;

    protected $inputService;

    public function __construct()
    {
        $this->objectsService = new CustomFieldsObjectService();
        $this->displayService = new CustomFieldsDisplayService();
        $this->inputService = new CustomFieldsInputService();
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
    }

    /**
     * Gets data for the configuration form
     *
     * @return array|bool
     */
    final public function getConfigurationData()
    {
        $inputs = $this->inputService->getAllInputsFromCodeObject($this->getObjectCode());
        if (!$inputs) {
            return false;
        }
        $inputsByDisplay = [];
        foreach ($inputs as $input) {
            /* @var $input CustomFieldsInput */
            $inputsByDisplay[$input->getDisplayCode()][] = $input;
        }

        $data = [];
        foreach ($inputsByDisplay as $codeDisplay => $displayInputs) {
            $display = $this->displayService->getInstanceFromCode($codeDisplay);
            if (!$display) {
                continue;
            }
            $data['displayMethods'][] = [
                'name' => $display->getDisplayName(),
                'code' => $display->getDisplayCode(),
                'data' => $display->getObjectSubformData($inputs),
                'templatePath' => $display->getObjectSubformTemplatePath(),
            ];
        }

        return $data;
    }

    // region AbstractMethods

    /**
     * Tries to guess from context if this Object is in use
     */
    abstract public function isObject();

    /**
     * Returns the theme template file associated to this object
     *
     * @return string
     */
    abstract public function getThemeTemplate();

    /**
     * Tries to retrieve the id_object from the context
     *
     * @return mixed
     */
    abstract public function getIdObject();

    /**
     * Gets back office hook for this object
     *
     * @return array
     */
    abstract public function getBackOfficeHooks();

    /**
     * Gets front office hooks for this object; used for displaying values with native hooks
     *
     * @return array
     */
    abstract public function getFrontOfficeHooks();

    /**
     * Returns the form for the back office, using the specified params
     *
     * @param array $params an array of params
     * @param string $hookName
     *
     * @return string
     */
    abstract public function displayBackOfficeInputs($params, $hookName);

    /**
     * Gets the unique code for the object
     *
     * @return string
     */
    abstract public function getObjectCode();

    /**
     * Gets the display name for the object
     *
     * @return string
     */
    abstract public function getObjectLocationName();

    /**
     * Gets the admin tab name for the object
     *
     * @return string
     */
    abstract public function getObjectTabName();

    /**
     * Returns the admin controller class name
     *
     * @return string
     */
    abstract public function getAdminControllerName();

    abstract public function getFormTemplatePath();

    /**
     * @return array
     */
    abstract public function getAvailableAdminDisplayHooks();

    // endregion
}
