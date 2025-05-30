{*
* 2007-2016 PrestaShop
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
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2023 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<span class="ps-switch prestashop-switch fixed-width-400 switch-product-combination" style="display: flex;align-items: center;">
                        {if isset($input) && is_array($input) && count($input)}
                            {foreach from=$input.values item=value}
								<input type="radio" {if $input.default_id == $value.id}checked{/if}
									   name="{$input.name|escape:'quotes':'UTF-8'}"
									   value="{$value.id|escape:'quotes':'UTF-8'}"
									   id="{$input.name|escape:'quotes':'UTF-8'}_{$value.id|escape:'quotes':'UTF-8'}" />
								<label for="{$input.name|escape:'quotes':'UTF-8'}_{$value.id|escape:'quotes':'UTF-8'}">
									{$value.text|escape:'quotes':'UTF-8'}
								</label>
                            {/foreach}
                        {/if}
	<a class="slide-button"></a>
</span>