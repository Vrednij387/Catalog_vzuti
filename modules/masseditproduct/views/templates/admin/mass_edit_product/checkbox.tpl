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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2012-2023 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($display_multishop_checkboxes) && $display_multishop_checkboxes}
	{if isset($multilang) && $multilang}
		{if isset($only_checkbox)}
			{foreach from=$languages item=language}
				<input type="checkbox" name="multishop_check[{$field|no_escape}][{$language.id_lang|intval}]" value="1" onclick="ProductMultishop.checkField(this.checked, '{$field|no_escape}_{$language.id_lang|intval}', '{$type|no_escape}')" {if !empty($multishop_check[$field][$language.id_lang])}checked="checked"{/if} />
			{/foreach}
		{else}
			{foreach from=$languages item=language}
				<input style="{if !$language.is_default}display: none;{/if}" class="multishop_lang_{$language.id_lang|intval} lang-{$language.id_lang|intval} translatable-field" type="checkbox" name="multishop_check[{$field|no_escape}][{$language.id_lang|intval}]" value="1" onclick="ProductMultishop.checkField(this.checked, '{$field|no_escape}_{$language.id_lang|intval}','{$type|no_escape}')"
				{if !empty($multishop_check[$field][$language.id_lang])}checked="checked"{/if} />
			{/foreach}
		{/if}
	{else}
		<input type="checkbox" name="multishop_check[{$field|no_escape}]" value="1" onclick="ProductMultishop.checkField(this.checked, '{$field|no_escape}', '{$type|no_escape}')" {if !empty($multishop_check[$field])}checked="checked"{/if} />
	{/if}
{/if}