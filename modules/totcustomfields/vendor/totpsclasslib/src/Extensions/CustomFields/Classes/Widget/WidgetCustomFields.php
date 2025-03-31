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

namespace TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Widget;

use TotcustomfieldsClasslib\Extensions\CustomFields\Classes\Displays\CustomFieldsDisplayWidget;
use TotcustomfieldsClasslib\Hook\AbstractWidget;

class WidgetCustomFields extends AbstractWidget
{
    /**
     * Render CustomFields inputs on different pages using widgets
     *
     * @param string $hookName
     * @param array $configuration
     *
     * @return string
     *
     * @throws \PrestaShopDatabaseException
     */
    public function renderCustomFields($hookName, $configuration)
    {
        $customFieldsDisplayWidget = new CustomFieldsDisplayWidget();

        return $customFieldsDisplayWidget->displayInputs($configuration);
    }

    public function getCustomFieldsWidgetVariables($hookName, $configuration)
    {
        return $this->getWidgetVariablesFromTpl($hookName, $configuration);
    }
}
