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

namespace TotcustomfieldsClasslib\Actions;

use TotcustomfieldsClasslib\Actions\ActionsHandler;

class ExtensionActionsHandler extends ActionsHandler
{
    /**
     * @deprecated use ActionsHandler
     * Process the action call back of cross modules
     * Accept only namespaced classes
     *
     * @param string $chain Name of the actions chain
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function process($chain)
    {
        /** @var DefaultActions $classAction */
        $classAction = new $chain();
        $classAction->setModelObject($this->modelObject);
        $classAction->setConveyor($this->conveyor);

        foreach ($this->actions as $action) {
            if (!is_callable([$classAction, $action], false, $callable_name)) {
                continue;
            }
            if (!call_user_func_array([$classAction, $action], [])) {
                $this->setConveyor($classAction->getConveyor());

                return false;
            }
        }

        $this->setConveyor($classAction->getConveyor());

        return true;
    }
}
