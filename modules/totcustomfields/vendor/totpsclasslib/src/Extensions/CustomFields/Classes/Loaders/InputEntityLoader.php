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

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputCheckbox;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputImage;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputSelect;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputText;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputTextarea;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs\CustomFieldsInputVideo;
use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Loaders\EntityLoader;

class InputEntityLoader extends EntityLoader
{
    protected static $entities = [];

    protected static $isLoaded = false;

    protected $addHookName = 'addTotcustomfieldsCustomFieldsInput';

    protected $skipHookName = 'skipTotcustomfieldsCustomFieldsInput';

    public function addEntity($class)
    {
        /** @var CustomFieldsInput $instance */
        $instance = new $class();

        foreach (self::$entities as $inputType) {
            if ($inputType['code'] == $instance->getInputTypeCode()
                && $inputType['name'] == $instance->getInputTypeName()) {
                return;
            }
        }

        self::$entities[] = [
            'code' => $instance->getInputTypeCode(),
            'name' => $instance->getInputTypeName(),
            'className' => $class,
        ];
    }

    public function removeEntity($class)
    {
        $indexToRemove = null;
        foreach (self::$entities as $index => $inputType) {
            if ($inputType['className'] == $class) {
                $indexToRemove = $index;
                break;
            }
        }

        if ($indexToRemove !== null) {
            unset(self::$entities[$indexToRemove]);
        }
    }

    protected function loadDefaultEntities()
    {
        $this->addEntity(CustomFieldsInputImage::class);
        $this->addEntity(CustomFieldsInputText::class);
        $this->addEntity(CustomFieldsInputTextarea::class);
        $this->addEntity(CustomFieldsInputVideo::class);
        $this->addEntity(CustomFieldsInputSelect::class);
        $this->addEntity(CustomFieldsInputCheckbox::class);
    }
}
