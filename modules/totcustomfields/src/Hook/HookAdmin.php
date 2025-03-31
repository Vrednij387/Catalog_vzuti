<?php
/**
 * Copyright since 2022 totcustomfields
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tech@202-ecommerce.com so we can send you a copy immediately.
 *
 * @author    202 ecommerce <tech@202-ecommerce.com>
 * @copyright 2022 totcustomfields
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 */

namespace Totcustomfields\Hook;

use AdminTotcustomfieldsCustomFieldsConfigurationController;
use Context;
use Module;
use TotcustomfieldsClasslib\Hook\AbstractHook;

class HookAdmin extends AbstractHook
{
    const AVAILABLE_HOOKS = [
        'displayBackOfficeHeader',
    ];

    /**
     * @throws \Exception
     */
    public function displayBackOfficeHeader()
    {
        $controller = Context::getContext()->controller;
        if ($controller instanceof AdminTotcustomfieldsCustomFieldsConfigurationController) {
            $this->assignSeeMoreVars();
        }
    }

    /**
     * Assigns template variables for "See more" tab in BO
     *
     * @throws \Exception
     */
    protected function assignSeeMoreVars()
    {
        $isoLang = Context::getContext()->language->iso_code;
        $linkLang = $isoLang == 'fr' ? 'fr' : 'en';

        $recommendedModules = [
            [
                'short_name' => 'totshowmailalerts',
                'installed' => false,
                'name' => $this->l('Product Out of Stock : Emails and Number of Requests'),
                'descr' => $this->l('Discover the #1 solution that allows you to learn which out-of-stock
                    products are your clients most interested in. Export email addresses in .csv. Notify your client of 
                    the sales end'),
                'link' => 'https://addons.prestashop.com/' . $linkLang . '/stock-supplier-management/'
                    . '6320-product-out-of-stock-emails-and-number-of-requests.html',
            ],
            [
                'short_name' => 'totswitchattribute',
                'installed' => false,
                'name' => $this->l('Enable / disable a combination, Import CSV module'),
                'descr' => $this->l('This module allows you to disable a product attribute 
                        combinations (without deleting) and activate them whenever you want. You can update combinations
                         status quickly by importing a list of disabled products via .csv file.'),
                'link' => 'https://addons.prestashop.com/' . $linkLang . '/combinaisons-customization'
                    . '/17711-enable-disable-a-combination-import-csv.html',
            ],
            [
                'short_name' => 'totloyaltyadvanced',
                'installed' => false,
                'name' => $this->l('Advanced Loyalty Program'),
                'descr' => $this->l('This is the best module for customer rewards points! Customize the 
                    number of points awarded for each product or each client : add, modify or delete points manually.'),
                'link' => 'https://addons.prestashop.com/' . $linkLang . '/referral-loyalty-programs/'
                    . '7301-advanced-loyalty-program.html',
            ],
            [
                'short_name' => 'totcarrierupdate',
                'installed' => false,
                'name' => $this->l('Quickly change carriers by categories Module'),
                'descr' => $this->l('The optimal solution for managing your carriers. With a few clicks
                        change carrier(s) of your products and save time with this module.'),
                'link' => 'https://addons.prestashop.com/' . $linkLang . '/fast-mass-updates'
                    . '/23518-quickly-change-carriers-by-categories-ps-15-17.html',
            ],
        ];

        foreach ($recommendedModules as $key => $module) {
            if (Module::isInstalled($module['short_name'])) {
                $link = Context::getContext()->link->getAdminLink('AdminModules', true, [], [
                    'configure' => $module['short_name'],
                    'module_name' => $module['short_name'],
                ]);

                $recommendedModules[$key]['link'] = $link;
                $recommendedModules[$key]['installed'] = true;
            }
        }

        $assigns = [
            'path' => '/modules/totcustomfields',
            'recommended' => $recommendedModules,
        ];

        Context::getContext()->smarty->assign('seemore', $assigns);
    }
}
