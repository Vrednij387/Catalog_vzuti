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

namespace TotcustomfieldsClasslib\Database\Action;

use TotcustomfieldsClasslib\Database\Action\Table\TableActionFactory;
use TotcustomfieldsClasslib\Database\Action\Table\TableActionType;
use TotcustomfieldsClasslib\Database\Definition\ObjectModel\ObjectModelDefinition;
use TotcustomfieldsClasslib\Database\Definition\Schema\SchemaDefinition;
use TotcustomfieldsClasslib\Database\Repository\ActionRepository;

class UninstallAction implements ActionInterface
{
    /**
     * @var ActionRepository
     */
    protected $actionRepository;

    /**
     * @var TableActionFactory
     */
    protected $tableActionFactory;

    public function __construct()
    {
        $this->actionRepository = new ActionRepository();
        $this->tableActionFactory = new TableActionFactory();
    }

    /**
     * @param ObjectModelDefinition $objectModelDefinition
     *
     * @return bool
     *
     * @throws \PrestaShopException
     */
    public function performAction(ObjectModelDefinition $objectModelDefinition)
    {
        $schemaDefinition = new SchemaDefinition($objectModelDefinition);
        $schemaDefinition->buildTableDefinitions();

        $tableDefinitions = $schemaDefinition->filterTableByAction(TableActionType::DELETE);
        foreach ($tableDefinitions as $tableDefinition) {
            if ($this->actionRepository->isTableExist($tableDefinition->getName())) {
                $this->tableActionFactory->getTableAction(TableActionType::DELETE)->handle($tableDefinition);
            }
        }

        return true;
    }
}
