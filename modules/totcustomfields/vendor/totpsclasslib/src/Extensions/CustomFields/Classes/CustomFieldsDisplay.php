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

abstract class CustomFieldsDisplay
{
    use TranslateTrait;

    const DISPLAY_INPUT_TABLE = 'totcustomfields_display_input';

    private static $displays = [];

    protected $displayService;

    protected $inputService;

    protected $objectService;

    public function __construct()
    {
        $this->displayService = new CustomFieldsDisplayService();
        $this->inputService = new CustomFieldsInputService();
        $this->objectService = new CustomFieldsObjectService();
    }

    /**
     * Get subform template name, from the right folder
     *
     * @return string|bool
     */
    public function getInputSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/displays/inputs/';
    }

    /**
     * Get subform template name, from the right folder
     *
     * @return string
     */
    public function getObjectSubformTemplatePath()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/admin/custom_fields/partials/displays/objects/simpleTable.tpl';
    }

    /**
     * Returns the default template for all displays
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return _PS_MODULE_DIR_ . 'totcustomfields/views/templates/front/custom_fields/displays/default.tpl';
    }

    // region Abstract methods

    /**
     * Gets data for an input subform
     *
     * @param int $idInput
     * @param string $codeObject
     *
     * @return mixed
     */
    abstract public function getInputSubformData($codeObject, $idInput = null);

    /**
     * Installs SQL for the display
     *
     * @return bool
     */
    abstract public function installSQL();

    /**
     * Gets data for an Object subform
     *
     * @param array $inputs an array of TotCustomFieldsInput
     *
     * @return mixed
     */
    abstract public function getObjectSubformData($inputs);

    /**
     * Gets the unique code for the display
     *
     * @return string
     */
    abstract public function getDisplayCode();

    /**
     * Gets the display name
     *
     * @return string
     */
    abstract public function getDisplayName();

    /**
     * Saves an input display method
     *
     * @param CustomFieldsInput $input
     * @param array|null $displayParams
     * @param string $codeAdminDisplay
     *
     * @return string|bool|array
     */
    abstract public function saveInputDisplayMethod($input, $displayParams, $codeAdminDisplay);

    /**
     * Deletes an input's display method
     *
     * @param int $idInput
     *
     * @return mixed
     */
    abstract public function deleteInputDisplay($idInput);

    /**
     * Returns all displays of an input, for its current display; if active is true, returns only active displays
     *
     * @param int $id_input
     * @param bool $active
     *
     * @return array
     */
    abstract protected function getInputDisplays($id_input, $active = false);

    /**
     * Saves display configuration for some inputs, from an object subform <br/>
     *
     * @return mixed
     */
    abstract public function saveObjectDisplayConfiguration();

    // endregion
}
