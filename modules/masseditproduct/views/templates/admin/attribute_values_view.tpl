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

<!-- attribute_values start-->
<span class="attribute_checkbox">
    {if count($values)}
        {foreach from=$values item=value}
            <span class="md-checkbox" style="display:none;">
                <label class="form-group">
                    <input type="checkbox" name="attributes[]" value="{$value.id_attribute|escape:'quotes':'UTF-8'}">
                    <i class="md-checkbox-control"></i>
                    {$value.name|escape:'quotes':'UTF-8'}
                </label>
            </span>
        {/foreach}
    {/if}
</span>

<!-- attribute_values end-->