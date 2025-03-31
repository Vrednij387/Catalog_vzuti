{*
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
*  @author    Goryachev Dmitry <dariusakafest@gmail.com>
*  @copyright 2012-2023 Goryachev Dmitry
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- feature_values -->
<span class="fin" >
<span class="feature_checkbox">
    {if count($values)}

        {foreach from=$values item=value}
            <span class="hid md-checkbox">
                <label class="mr-3 form-group">
                    <input type="checkbox" name="features[]" value="{$value.id_feature_value|escape:'quotes':'UTF-8'}">
                    <i class="md-checkbox-control"></i>
                    {$value.value|escape:'quotes':'UTF-8'}
                </label>
            </span>
        {/foreach}

       <span class="mr-1 hid md-checkbox">
            <label class="mr-3 form-group">
                <input type="checkbox" class="no_feature" name="no_feature_value[]" value="no_feature_value">
                <i class="md-checkbox-control"></i>
                {l s='No value' mod='masseditproduct'}
            </label>
        </span>
</span>
    {/if}
</span>