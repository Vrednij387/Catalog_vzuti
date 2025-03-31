{*
* 2014-2019 Fondy
*
*  @author DM
*  @copyright  2014-2019 Fondy
*  @version  1.0.0
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{extends "$layout"}

{block name="content"}
{l s='Ви будете перенаправлені на сервіс оплати за мить...' mod='fondy'}

<form id="fondy" method="post" action="{$fondy_url|escape:'htmlall'}" style="opacity: 0;">
	<input type="hidden" name="order_id" value="{$order_id|escape:'htmlall'}" />
	<input type="hidden" name="merchant_id" value="{$merchant_id|escape:'htmlall'}" />
	<input type="hidden" name="order_desc" value="{$order_desc|escape:'htmlall'}" />
	<input type="hidden" name="amount" value="{$amount|escape:'htmlall'}" />
	<input type="hidden" name="currency" value="{$currency|escape:'htmlall'}" />
	<input type="hidden" name="server_callback_url" value="{$server_callback_url|escape:'htmlall'}" />
	<input type="hidden" name="response_url" value="{$response_url|escape:'htmlall'}" />
    <input type="hidden" name="lang" value="{$lang|escape:'htmlall'}" />
    <input type="hidden" name="sender_email" value="{$sender_email|escape:'htmlall'}" />
    <input type="hidden" name="signature" value="{$signature|escape:'htmlall'}" />
	<input type="submit" value="{l s='Pay' mod='fondy'}">
</form>

<script>
	gtag('event', 'conversion', {
		'send_to': 'AW-11412755620/243BCJnmmvcYEKSpg8Iq',
		'transaction_id': fondy_{$order_id|escape:'htmlall'},
		'value': {$amount|escape:'htmlall'},
}); 
</script>

<script type="text/javascript">
	gtag("event", "purchase", {
		value: "{round($total_products)}",			
		currency: '{$currency_iso_code}',		                 
		coupon: '',		                                        
		items: [
		{foreach from=$products item='product' name=products}
		{
			item_id: "{$product.reference}",
			item_name: "{$product.name|escape:'html':'UTF-8'} {if $product.attributes_small}{$product.attributes_small}{/if}",
			affiliation: 'vzutistore.com.ua',
			item_brand: "{if $product.manufacturer_name}{$product.manufacturer_name|escape:'html':'UTF-8'}{else}undefined{/if}",
			item_category: "{$product.category_name}",
			price: "{$product.price_with_reduction}",
			quantity: "{$product.quantity|intval}"	                   
		},
		{/foreach}
		]
	});
</script>

<script type="text/javascript">
	{literal}
		setTimeout(() => {document.getElementById('fondy').submit();}, 2000);
	{/literal}
</script>

{/block}
