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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsDisplay;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplayBackOfficeOnly;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplayNativeHooks;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplaySmarty;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplayWidget;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders\EntityLoader;

class DisplayEntityLoader extends EntityLoader
{
    protected static $entities = [];

    protected static $isLoaded = false;

    protected $addHookName = 'addTotcustomfieldsCustomFieldsDisplay';

    protected $skipHookName = 'skipTotcustomfieldsCustomFieldsDisplay';

    public function addEntity($class)
    {
        /** @var CustomFieldsDisplay $instance */
        $instance = new $class();
        $name = $instance->getDisplayCode();
        self::$entities[$name] = [
            'class' => $class,
            'instance' => $instance,
        ];
    }

    public function removeEntity($class)
    {
        /** @var CustomFieldsDisplay $instance */
        $instance = new $class();
        if (array_key_exists($instance->getDisplayCode(), self::$entities)) {
            unset(self::$entities[$instance->getDisplayCode()]);
        }
    }

    protected function loadDefaultEntities()
    {
        $this->addEntity(CustomFieldsDisplayBackOfficeOnly::class);
        $this->addEntity(CustomFieldsDisplayNativeHooks::class);
        $this->addEntity(CustomFieldsDisplaySmarty::class);
        $this->addEntity(CustomFieldsDisplayWidget::class);
    }
}
