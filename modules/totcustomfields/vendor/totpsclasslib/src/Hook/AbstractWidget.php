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

namespace TotcustomfieldsClasslib\Hook;

use TotcustomfieldsClasslib\Extensions\AbstractModuleExtension;
use Module;

abstract class AbstractWidget
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * AbstractExtensionHook constructor.
     *
     * @param Module $module
     */
    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * Call this function if you need to pass parameters from the template e.g.
     * {widget ... tpl_vars=["variant" => "normal"]}`
     *
     * @param string $hookName
     * @param array $configuration
     *
     * @return array
     */
    public function getWidgetVariablesFromTpl($hookName, $configuration)
    {
        $variables = [];

        // Pass 'tpl_vars' array from widget declaration to the template.
        if (isset($configuration['tpl_vars']) && !empty($configuration['tpl_vars'])) {
            foreach ($configuration['tpl_vars'] as $key => $value) {
                $variables[$key] = $value;
            }
        }

        return $variables;
    }
}
