{**
 *  PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @copyright PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 
{literal}
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-MZ6C7L7KWS"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-MZ6C7L7KWS');
	</script>
{/literal}
{if $smarty.server.REMOTE_ADDR =='134.249.84.233'} {var_dump($page.page_name, $cart.totals.total_including_tax.amount)} {/if}
{if $page.page_name == 'module-supercheckout-supercheckout' && $cart.products}

	<script type="text/javascript">
		gtag("event", "begin_checkout", {
			value: "{$cart.totals.total_including_tax.amount}",			
			currency: '{$currency.iso_code}',		                 
			coupon: '',		                                        
			items: [
			{foreach from=$cart.products item='product' name=products}
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
{/if}