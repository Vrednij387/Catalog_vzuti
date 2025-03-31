{**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{foreach $warehouses as $warehouse}
	<option class="js_warehouse_option" value="{$warehouse.name|escape:'htmlall':'UTF-8'}" data-ref="{$warehouse.ref|escape:'htmlall':'UTF-8'}">
		{$warehouse.name|escape:'htmlall':'UTF-8'}
	</option>	
{/foreach}