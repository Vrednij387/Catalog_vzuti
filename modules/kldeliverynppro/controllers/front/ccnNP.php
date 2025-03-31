<?php
/**
* @author    Antikov Evgeniy
* @copyright 2017-2019 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

include_once _PS_MODULE_DIR_.'kldeliverynppro/src/NovaPoshtaApi2.php';

class KlDeliveryNPProccnNPModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->ajax = true;

        parent::initContent();

        $encrypt_f = Tools::encrypt('kldeliverynppro/index');
        if (Tools::getValue('nptoken') != Tools::substr($encrypt_f, 0, 10)) {
            die($this->module->l('Invalid token'));
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
        } elseif (Tools::getValue('actionCCN') == 'printCCN' &&
            Tools::getValue('id_order')
        ) {
            $this->module->printCCN(Tools::getValue('id_order'));
        } elseif (Tools::getValue('actionCCN') == 'printMarkings' &&
            Tools::getValue('id_order')
        ) {
            $this->module->printMarkings(Tools::getValue('id_order'));
        } elseif (Tools::getValue('actionCCN') == 'deleteCCN' &&
            Tools::getValue('id_order')
        ) {
            $this->module->deleteCCN(Tools::getValue('id_order'));
        } elseif (Tools::getValue('actionCCN') == 'trackCCN' &&
            Tools::getValue('id_order')
        ) {
            $this->module->trackCCN(Tools::getValue('id_order'));
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
        } else {
            $result = array(
                'error' => 1,
                'message' => $this->module->displayError(
                    $this->module->l('Not all parametrs used', 'ccnNP')
                )
            );
            echo json_encode($result);
        }
        exit;
    }
}
