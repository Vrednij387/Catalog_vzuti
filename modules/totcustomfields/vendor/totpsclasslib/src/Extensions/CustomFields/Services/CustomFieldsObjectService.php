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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Services;

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsObject;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders\ObjectEntityLoader;
use Hook;

class CustomFieldsObjectService
{
    /**
     * @var ObjectEntityLoader
     */
    protected $entityLoader;

    /**
     * CustomFieldsObjectService constructor.
     *
     * @throws \PrestaShopException
     */
    public function __construct()
    {
        $this->entityLoader = new ObjectEntityLoader();
        $this->entityLoader->loadEntities();
    }

    public function objectExists($code)
    {
        return isset($this->entityLoader->getEntities()[$code]);
    }

    /**
     * @param string $code
     *
     * @return bool|CustomFieldsObject
     */
    public function getInstanceFromCode($code)
    {
        if (isset($this->entityLoader->getEntities()[$code])) {
            return $this->entityLoader->getEntities()[$code]['instance'];
        }

        return false;
    }

    /**
     * Tries to retrieve the id_object from the context, using the code_object
     *
     * @param string $codeObject
     *
     * @return mixed
     */
    public function getIdObjectFromCode($codeObject)
    {
        $object = $this->getInstanceFromCode($codeObject);

        return $object ? $object->getIdObject() : false;
    }

    /**
     * Displays any object back office form using the specified array $params <br/>
     * This array is passed to the correct concrete Object class
     *
     * @param string $codeObject
     * @param mixed $params
     * @param string $hookName
     *
     * @return string|bool
     */
    public function displayObjectBackOfficeInputs($codeObject, $params, $hookName)
    {
        /** @var CustomFieldsObject|bool $object */
        $object = $this->getInstanceFromCode($codeObject);
        if (!$object) {
            return false;
        }

        // return form; call method from instance with params
        return $object->displayBackOfficeInputs($params, $hookName);
    }

    /**
     * Gets all objects hooks for registration
     *
     * @return array
     */
    public function getAllObjectsHooks()
    {
        $hooks = [];

        foreach ($this->entityLoader->getEntities() as $object) {
            /** @var CustomFieldsObject $instance */
            $instance = $object['instance'];
            if ($boHooks = $instance->getBackOfficeHooks()) {
                $hooks = array_merge($hooks, $boHooks);
            }

            $hooks = array_merge($hooks, $instance->getFrontOfficeHooks());
            $hooks = array_unique($hooks);
        }

        return $hooks;
    }

    /**
     * @param string $codeObject
     *
     * @return array|false
     */
    public function getFrontOfficeHooksFromCode($codeObject)
    {
        $object = $this->getInstanceFromCode($codeObject);

        return $object ? $object->getFrontOfficeHooks() : false;
    }

    /**
     * Tries to retrieve the code_object from the context
     *
     * @return string
     */
    public function getCodeFromContext()
    {
        foreach ($this->entityLoader->getEntities() as $object) {
            /** @var CustomFieldsObject $instance */
            $instance = $object['instance'];

            if ($instance->isObject()) {
                return $instance->getObjectCode();
            }
        }

        return '';
    }

    /**
     * Returns the theme template path associated to this object
     *
     * @param string $codeObject
     *
     * @return string|false
     */
    public function getThemeTemplateFromCode($codeObject)
    {
        $object = $this->getInstanceFromCode($codeObject);

        return _PS_THEME_DIR_ . $object->getThemeTemplate();
    }

    public function getObjects()
    {
        return $this->entityLoader->getEntities();
    }
}
