<?php
/**
 * 2010-2023 Bl Modules.
 *
 * If you wish to customize this module for your needs,
 * please contact the authors first for more information.
 *
 * It's not allowed selling, reselling or other ways to share
 * this file or any other module files without author permission.
 *
 * @author    Bl Modules
 * @copyright 2010-2023 Bl Modules
 * @license
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class MergeAttributesByGroup
{
    private $parentGroup = 0;
    private $childGroup = 0;
    private $settings = [];
    
    /**
     * @return int
     */
    public function getParentGroup()
    {
        return $this->parentGroup;
    }

    /**
     * @param int $parentGroup
     */
    public function setParentGroup($parentGroup)
    {
        $this->parentGroup = $parentGroup;
    }

    /**
     * @return int
     */
    public function getChildGroup()
    {
        return $this->childGroup;
    }

    /**
     * @param int $childGroup
     */
    public function setChildGroup($childGroup)
    {
        $this->childGroup = $childGroup;
    }

    /**
     * @param array $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    public function getByParentGroup($productAttributes, $onlyInStock = true, $activeFeatures = [], $attributeMapValues = [], $outOfStockStatus = 1)
    {
        $parentGroupAttributes = array();

        if (empty($activeFeatures)) {
            return $parentGroupAttributes;
        }

        $settings = $this->getSettings();

        foreach ($productAttributes as $aParent) {
            if ($aParent['id_attribute_group'] == $this->getParentGroup()) {
                foreach ($productAttributes as $aChild) {
                    if ($onlyInStock && $aChild['quantity'] < 1) {
                        continue;
                    }

                    if (!empty($settings['only_available_for_order'])) {
                        if ($aChild['quantity'] < 1 && $outOfStockStatus == 0) {
                            continue;
                        }

                        if ($aChild['quantity'] < 1 && $outOfStockStatus == 2 && $settings['configuration']['PS_ORDER_OUT_OF_STOCK'] == 0) {
                            continue;
                        }
                    }

                    if ($aParent['id_attribute_group'] == $aChild['id_attribute_group'] || empty($activeFeatures[$aChild['id_attribute_group']])) {
                        continue;
                    }

                    if ($aParent['id_product_attribute'] == $aChild['id_product_attribute']) {
                        $parentGroupAttributes[$aParent['attribute_name']][$aChild['id_attribute_group']][] = !empty($attributeMapValues[$aChild['id_attribute_group'].'-'.$aChild['id_attribute']]) ? $attributeMapValues[$aChild['id_attribute_group'].'-'.$aChild['id_attribute']] : $aChild['attribute_name'];
                    }
                }
            }
        }

        return $parentGroupAttributes;
    }

    public function getCombinationParentGroupName($productAttributes, $combinationId)
    {
        foreach ($productAttributes as $a) {
            if ($a['id_attribute_group'] == $this->getParentGroup() && $a['id_product_attribute'] == $combinationId) {
                return $a['attribute_name'];
            }
        }

        return '';
    }

    public function getCombinationNameByMainGroup($name)
    {
        return ', '.$name;
    }
}
