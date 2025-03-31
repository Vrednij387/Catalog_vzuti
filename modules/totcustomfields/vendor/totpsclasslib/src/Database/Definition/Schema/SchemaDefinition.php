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

namespace TotcustomfieldsClasslib\Database\Definition\Schema;

use TotcustomfieldsClasslib\Database\Action\Table\TableActionType;
use TotcustomfieldsClasslib\Database\Definition\ObjectModel\ObjectModelDefinition;
use TotcustomfieldsClasslib\Database\Definition\Table\TableDefinition;
use TotcustomfieldsClasslib\Database\Definition\Table\TableDefinitionFactory;
use TotcustomfieldsClasslib\Database\Definition\Table\TableType;

class SchemaDefinition
{
    /**
     * @var ObjectModelDefinition
     */
    private $objectModelDefinition;

    /**
     * @var TableDefinition[]
     */
    private $tableDefinitions = [];

    /**
     * @param ObjectModelDefinition $objectModelDefinition
     */
    public function __construct(ObjectModelDefinition $objectModelDefinition)
    {
        $this->objectModelDefinition = $objectModelDefinition;
    }

    public function buildTableDefinitions()
    {
        $tableDefinitionFactory = new TableDefinitionFactory($this->objectModelDefinition);
        $this->tableDefinitions[] = $tableDefinitionFactory->getTableDefinitionBuilder(TableType::MAIN)->build();

        if ($this->objectModelDefinition->isMultilang() || $this->objectModelDefinition->isMultilangShop()) {
            $this->tableDefinitions[] = $tableDefinitionFactory->getTableDefinitionBuilder(TableType::LANG)->build();
        }

        if ($this->objectModelDefinition->isMultishop()) {
            $this->tableDefinitions[] = $tableDefinitionFactory->getTableDefinitionBuilder(TableType::SHOP)->build();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getTableDefinitions()
    {
        return $this->tableDefinitions;
    }

    public function filterTableByAction($actionType)
    {
        if ($actionType != TableActionType::DELETE) {
            return $this->getTableDefinitions();
        }

        $langShopsTables = array_filter($this->getTableDefinitions(), function (TableDefinition $tableDefinition) {
            return $tableDefinition->getAlias() != 'm';
        });

        $mainTables = array_filter($this->getTableDefinitions(), function (TableDefinition $tableDefinition) {
            return $tableDefinition->getAlias() == 'm';
        });

        return array_merge($langShopsTables, $mainTables);
    }
}
