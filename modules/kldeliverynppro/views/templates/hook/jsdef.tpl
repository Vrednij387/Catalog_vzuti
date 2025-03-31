{**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{if isset($ajaxurlget)}
	<div style="display:none;">
		<span id="ajaxurlget" data-value="{$ajaxurlget}"></span>
		<span id="saveCartUrl" data-value="{$saveCartUrl}"></span>
		<span id="CCNUrl" data-value="{$CCNUrl}"></span>
	</div>
{/if}
<script type="text/javascript">
	{if isset($ajaxurlget)}
		var	np_id_carrier = '{$np_id_carrier}';
		var	change_required_nppro = '{$change_required}';
	{/if}
</script>