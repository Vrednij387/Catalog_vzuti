{**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
<script type="text/javascript">
	function getURLselector(){
		var city = $('#js-cities option[value="'+$('#js-cities option:selected').val().replaceAll('"','\\"').replaceAll("'","\\'")+'"]').data('ref'); 
    	var warehouse = $('#js-warehouses option[value="'+$('#js-warehouses option:selected').val().replaceAll('"','\\"').replaceAll("'","\\'")+'"]').data('ref');
    	// return '&city-js='+city+'&warehouse-js='+warehouse+'&name='+$('#name').val()+'&surname='+$('#surname').val()+'&phone='+$('#phone').val()+'&price='+$('#price').val()+'&weight='+$('#weight').val()+'&volume='+$('#volume').val()+'&count='+$('#count').val()+'&cod='+$('#cod').is(':checked');
    	return '&city-js='+city+'&warehouse-js='+warehouse;
    }
</script>
<div class="row">
	<div class="col-12">
		<div class="panel card" id="fieldset_np">
			<div class="panel-heading card-header">
				<i class="icon-truck"></i>{l s='Nova poshta extras' mod='kldeliverynppro'}
			</div>
			<div class="form-wrapper card-body">
				<form action="{$CCNUrl}" class="form-horizontal" method="post">
					<button type="button" onclick="$('.js_add_city_warhouse').toggleClass('hidden');$('.js_add_button_np').toggleClass('hidden');$('.js_cancel_button_np').toggleClass('hidden'); return false;" class="btn btn-primary">
						<span class="js_add_button_np">{l s='Add delivery Nova Poshta' mod='kldeliverynppro'}</span>
						<span class="js_cancel_button_np hidden">{l s='Cancel' mod='kldeliverynppro'}</span>
					</button>
					<div class="row js_add_city_warhouse hidden">
						<div class="col-md-12">
							{if version_compare(_PS_VERSION_, '1.7.0.0', '>=')}
								{hook h='displayCarrierExtraContent' city_np=$city mod='kldeliverynppro'}
							{else}
								{hook h='displayCarrierList' city_np=$city mod='kldeliverynppro'}
							{/if}
						</div>
						<input type="button" onclick="actionCCN(this, 'saveCartNP', getURLselector()); return false;" class="btn btn-primary" value="{l s='Save' mod='kldeliverynppro'}">
					</div>
					<input type="hidden" name="id_order" id="id_order" value="{$id_order}" class="">
				</form>
				<div class="js_notification_np"></div>
			</div>
		</div>
	</div>
</div>
{addJsDef ajax_error=str_replace("\n", '', $ajax_error|trim)}
