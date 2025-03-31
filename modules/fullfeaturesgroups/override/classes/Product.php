<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

class Product extends ProductCore
{
    public static function getFrontFeaturesStatic($id_lang, $id_product)
    {
        $featureActive = Configuration::get('FFG_FEATURE_FRONT');
        $page_name = Dispatcher::getInstance()->getController();
        if ($page_name != 'product' || $featureActive == 0) {
            return parent::getFrontFeaturesStatic($id_lang, $id_product);
        }
        return array();
    }

    public function processFeatures()
    {
        $featureActive = Configuration::get('FFG_FEATURE_FRONT');
        if (!Feature::isFeatureActive() || $featureActive == 1) {
            return;
        }

        if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
            $product->deleteFeatures();
            $languages = Language::getLanguages(false);
            foreach ($_POST as $key => $val) {
                if (preg_match('/^feature_([0-9]+)_value/i', $key, $match)) {
                    if ($val) {
                        if (is_array($val)) {
                            foreach ($val as $elt) {
                                $product->addFeaturesToDB($match[1], $elt);
                            }
                        } else {
                            $product->addFeaturesToDB($match[1], $val);
                        }
                    } else {
                        if ($default_value = $this->checkFeatures($languages, $match[1])) {
                            $id_value = $product->addFeaturesToDB($match[1], 0, 1);
                            foreach ($languages as $language) {
                                if ($cust = Tools::getValue('custom_' . $match[1] . '_' . (int)$language['id_lang'])) {
                                    $product->addFeaturesCustomToDB($id_value, (int)$language['id_lang'], $cust);
                                } else {
                                    $product->addFeaturesCustomToDB($id_value, (int)$language['id_lang'], $default_value);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $this->errors[] = Tools::displayError('A product must be created before adding features.');
        }
    }
}
