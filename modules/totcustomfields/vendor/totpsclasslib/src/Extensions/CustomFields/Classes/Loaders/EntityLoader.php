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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders;

use Hook;

abstract class EntityLoader
{
    protected static $entities = [];

    protected $addHookName;

    protected $skipHookName;

    protected static $isLoaded = false;

    abstract public function addEntity($class);

    abstract public function removeEntity($class);

    abstract protected function loadDefaultEntities();

    /**
     * @return mixed
     *
     * @throws \PrestaShopException
     */
    public function loadEntities()
    {
        if (empty($this->addHookName) || empty($this->skipHookName)) {
            throw new \PrestaShopException('Entity\'s hooks not set!');
        }

        if (static::$isLoaded) {
            // already loaded
            return static::$isLoaded;
        }

        static::$isLoaded = true;

        $this->loadDefaultEntities();

        /** @var array|null $entitiesToAdd */
        $entitiesToAdd = Hook::exec($this->addHookName, [], null, true, false);

        if (!empty($entitiesToAdd)) {
            foreach ($entitiesToAdd as $module => $entityTypes) {
                if (!empty($entityTypes)) {
                    foreach ($entityTypes as $entityType) {
                        $this->addEntity($entityType);
                    }
                }
            }
        }

        /** @var array|null $entitesToSkip */
        $entitesToSkip = Hook::exec($this->skipHookName, [], null, true, false);

        if (!empty($entitesToSkip)) {
            foreach ($entitesToSkip as $module => $entityTypes) {
                if (!empty($entityTypes)) {
                    foreach ($entityTypes as $entityType) {
                        $this->removeEntity($entityType);
                    }
                }
            }
        }

        return static::$isLoaded;
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return static::$entities;
    }

    /**
     * @return mixed
     */
    public function getIsLoaded()
    {
        return static::$isLoaded;
    }
}
