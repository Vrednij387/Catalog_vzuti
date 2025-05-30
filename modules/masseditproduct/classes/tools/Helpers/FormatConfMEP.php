<?php
/**
 * 2012-2022 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 * @copyright 2012-2019 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class FormatConfMEP
{
    public static function prepareSetting($post_data, $input_array, $id_key, $properties)
    {
        $return_data = [];

        if (is_array($input_array) && count($input_array)) {
            foreach ($input_array as $item) {
                if (!array_key_exists($item[$id_key], $return_data)) {
                    $return_data[$item[$id_key]] = [];
                }

                foreach ($properties as $property => $value) {
                    if (is_array($post_data) && array_key_exists($item[$id_key], $post_data)
                        && array_key_exists($property, $post_data[$item[$id_key]])) {
                        $return_data[$item[$id_key]][$property] = ToolsModuleMEP::formatValue(
                            $post_data[$item[$id_key]][$property],
                            $value['validate']
                        );
                    } else {
                        $return_data[$item[$id_key]][$property] = $value['default_value'];
                    }
                }
            }
        }

        return $return_data;
    }

    public static function prepareSettingOrderStates($post_data)
    {
        $input_array = OrderState::getOrderStates(Context::getContext()->language->id);
        $properties = [
            'add_bonus' => ['default_value' => 0, 'validate' => ObjectModel::TYPE_INT],
            'cancel_bonus' => ['default_value' => 0, 'validate' => ObjectModel::TYPE_INT],
        ];
        return self::prepareSetting($post_data, $input_array, 'id_order_state', $properties);
    }

    public static function prepareSettingGroup($post_data)
    {
        $input_array = Group::getGroups(Context::getContext()->language->id);
        $properties = [
            'enabled' => ['default_value' => 0, 'validate' => ObjectModel::TYPE_INT],
            'commission' => ['default_value' => 0, 'validate' => ObjectModel::TYPE_FLOAT],
            'allow_count_product' => ['default_value' => 0, 'validate' => ObjectModel::TYPE_INT],
            'allow_count_image' => ['default_value' => 0, 'validate' => ObjectModel::TYPE_INT],
        ];
        return self::prepareSetting($post_data, $input_array, 'id_group', $properties);
    }

    public static function getSettingGroup()
    {
        return self::prepareSettingGroup(ConfMEP::getConf('SETTING_GROUPS', ConfMEP::TYPE_ARRAY));
    }

    public static function getSettingOrderStates()
    {
        return self::prepareSettingOrderStates(ConfMEP::getConf('SETTING_ORDER_STATES', ConfMEP::TYPE_ARRAY));
    }
}
