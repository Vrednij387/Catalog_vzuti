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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Inputs;

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\CustomFieldsInput;
use ObjectModel;

/**
 * Class TotCustomFieldsInputImageValue
 */
class CustomFieldsInputImageValue extends ObjectModel
{
    public $id_input;

    public $id_object;

    public $id_lang; // NULL for non-translatable value

    public $value;

    public static $definition = [
        'table' => 'totcustomfields_input_image_value',
        'primary' => 'id_input_value',
        'fields' => [
            'id_input' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true,
            ],
            'id_object' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true,
            ],
            'id_lang' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId',
                'allow_null' => true,
            ],
            'value' => [
                'type' => self::TYPE_HTML,
                'validate' => 'isString',
                'allow_null' => true,
            ],
        ],
        'associations' => [
            'input' => [
                'type' => ObjectModel::HAS_ONE,
                'object' => CustomFieldsInput::class,
                'association' => 'totcustomfields_input',
                'field' => 'id_input',
            ],
            'lang' => [
                'type' => ObjectModel::HAS_ONE,
                'object' => 'Language',
                'association' => 'lang',
                'field' => 'id_lang',
            ],
        ],
        'indexes' => [
            [
                'fields' => [
                    [
                        'column' => 'id_input',
                    ],
                    [
                        'column' => 'id_object',
                    ],
                    [
                        'column' => 'id_lang',
                    ],
                ],
            ],
        ],
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
    ];
}
