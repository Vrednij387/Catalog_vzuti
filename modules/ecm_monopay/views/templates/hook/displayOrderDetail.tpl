{if isset($monoPayLink) && $monoPayLink}
<div class="info-order box">
	<p><strong class="dark">{l s='Pay order via Monobank' mod='ecm_monopay'}</strong></p>
	<p> <i class="icon-file-text"></i> <a target="_blank" href="{$monoPayLink|escape:'html':'UTF-8'}"> {l s='Your order has been not completed and payed. Pay this order via Monobank' mod='ecm_monopay'}</a>
	</p>
</div>
{/if}



