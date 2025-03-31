<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2019 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

class AdminKlookvaNovaPoshtaController extends ModuleAdminController
{
    public function display()
    {
        $encrypt_f = Tools::encrypt('kldeliverynppro/index');
        if (Tools::getValue('nptoken') != Tools::substr($encrypt_f, 0, 10)) {
            $a = $this->context->link->getAdminLink('AdminModules', true);
            Tools::redirectAdmin($a.'&configure=kldeliverynppro&tab_module=administration&module_name=kldeliverynppro');
            exit;
        }

        if (Tools::getValue('getWarehouse')) {
            if (Tools::getValue('city-js')) {
                $this->module->actionGetWarehouses();
                exit;
            }
        }
        if (Tools::getValue('create') &&
            Configuration::get('FIRSTNAME_ADMIN_NP') &&
            Configuration::get('MIDDLENAME_ADMIN_NP') &&
            Configuration::get('LASTNAME_ADMIN_NP') &&
            Configuration::get('PHONE_ADMIN_NP') &&
            Configuration::get('CITY_ADMIN_NP') &&
            Configuration::get('REGION_ADMIN_NP') &&
            Configuration::get('WAREHOUSE_ADMIN_NP')
        ) {
            $this->module->createCCN(
                Tools::getValue('name'),
                Tools::getValue('surname'),
                Tools::getValue('phone'),
                Tools::getValue('city-js'),
                trim(Tools::getValue('warehouse-js')),
                Tools::getValue('cod'),
                Tools::getValue('id_order'),
                Tools::getValue('price'),
                Tools::getValue('weight'),
                Tools::getValue('volume'),
                Tools::getValue('count')
            );
            exit;
        } elseif (Tools::getValue('actionCCN') == 'printCCN' &&
            Tools::getValue('id_order')
        ) {
            $this->module->printCCN(Tools::getValue('id_order'));
            exit;
        } elseif (Tools::getValue('actionCCN') == 'printMarkings' &&
            Tools::getValue('id_order')
        ) {
            $this->module->printMarkings(Tools::getValue('id_order'));
            exit;
        } elseif (Tools::getValue('actionCCN') == 'deleteCCN' &&
            Tools::getValue('id_order')
        ) {
            $this->module->deleteCCN(Tools::getValue('id_order'));
            exit;
        } elseif (Tools::getValue('actionCCN') == 'trackCCN' &&
            Tools::getValue('id_order')
        ) {
            $this->module->trackCCN(Tools::getValue('id_order'));
            exit;
        } elseif (Tools::getValue('actionCCN') == 'saveCCN') {
            $this->module->saveCCN(
                Tools::getValue('id_order'),
                Tools::getValue('city-js'),
                Tools::getValue('warehouse-js'),
                Tools::getValue('name'),
                Tools::getValue('surname'),
                Tools::getValue('phone'),
                filter_var(Tools::getValue('cod'), FILTER_VALIDATE_BOOLEAN),
                Tools::getValue('price'),
                Tools::getValue('weight'),
                Tools::getValue('volume'),
                Tools::getValue('count')
            );
            exit;
        } elseif (Tools::getValue('actionCCN') == 'saveCartNP' &&
            Tools::getValue('city-js') &&
            Tools::getValue('warehouse-js') &&
            Tools::getValue('id_order')
        ) {
            $this->module->saveCartNP(
                Tools::getValue('id_order'),
                Tools::getValue('city-js'),
                Tools::getValue('warehouse-js')
            );
            exit;
        }
        if (Tools::getValue('actionCCN') || Tools::getValue('create')) {
            echo json_encode(
                array(
                    'error' => 1,
                    'message' => $this->module->displayError(
                        $this->l('Wrong module configuration data')
                    )
                )
            );
            exit;
        }
        $a = $this->context->link->getAdminLink('AdminModules', true);
        Tools::redirectAdmin($a.'&configure=kldeliverynppro&tab_module=administration&module_name=kldeliverynppro');
        exit;
    }
}
