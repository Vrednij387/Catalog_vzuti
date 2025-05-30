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

namespace TotcustomfieldsClasslib\Database\Definition\Table;

use TotcustomfieldsClasslib\Database\Definition\Field\FieldDefinition;
use TotcustomfieldsClasslib\Database\ForeignKey\ForeignKey;
use ObjectModel;
use Shop;

class ShopTableDefinitionBuilder extends AbstractTableDefinitionBuilder
{
    protected function getColumns()
    {
        $fields = [];

        foreach ($this->objectModelDefinition->getPrimary() as $primaryKey) {
            $fields[] = new FieldDefinition($primaryKey, [
                'type' => ObjectModel::TYPE_INT,
                'validate' => 'isUnsignedId',
                'required' => true,
            ]);
        }

        $fields[] = new FieldDefinition('id_shop', [
            'type' => ObjectModel::TYPE_INT,
            'validate' => 'isInt',
            'required' => true,
        ]);

        $fieldsDefinitions = array_filter($this->objectModelDefinition->getFields(), function (FieldDefinition $fieldDefinition) {
            return !empty($fieldDefinition->getDefinition()['shop']);
        });

        return array_merge($fields, $fieldsDefinitions);
    }

    /**
     * @param TableDefinition $tableDefinition
     *
     * @return AbstractTableDefinitionBuilder
     */
    protected function buildSpecificFields(TableDefinition $tableDefinition)
    {
        $tableDefinition->setName($this->objectModelDefinition->getDbPrefix() . $this->objectModelDefinition->getTable() . '_shop')
            ->setAlias('s')
            ->setPrimaryKey(array_merge($this->objectModelDefinition->getPrimary(), ['id_shop']))
            ->setForeignKeys($this->getForeignKeys());

        return $this;
    }

    protected function getForeignKeys()
    {
        $foreignKeys = [];

        foreach ($this->objectModelDefinition->getPrimary() as $primaryMainKey) {
            $foreignKeys[] = (new ForeignKey())->build($this->objectModelDefinition->getDbPrefix() . $this->objectModelDefinition->getTable() . '_shop', [
                'type' => ObjectModel::HAS_ONE,
                'object' => $this->objectModelDefinition->getObjectName(),
                'association' => $this->objectModelDefinition->getTable(),
                'field' => $primaryMainKey,
            ]);
        }

        $foreignKeys[] = (new ForeignKey())->build($this->objectModelDefinition->getDbPrefix() . $this->objectModelDefinition->getTable() . '_shop', [
            'type' => ObjectModel::HAS_ONE,
            'object' => Shop::class,
            'association' => Shop::$definition['table'],
            'field' => 'id_shop',
        ]);

        return $foreignKeys;
    }
}
