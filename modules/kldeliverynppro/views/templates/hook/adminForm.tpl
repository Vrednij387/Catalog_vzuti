{**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
<script type="text/javascript">
	function getURLselector(){
		var city = $('#js-cities option[value="'+$('#js-cities option:selected').val().replaceAll('"','\\"').replaceAll("'","\\'")+'"]').data('ref'); 
    	var warehouse = $('#js-warehouses option[value="'+$('#js-warehouses option:selected').val().replaceAll('"','\\"').replaceAll("'","\\'")+'"]').data('ref');
    	return '&city-js='+city+'&warehouse-js='+warehouse+'&name='+$('#name').val()+'&surname='+$('#surname').val()+'&phone='+$('#phone').val()+'&price='+$('#price').val()+'&weight='+$('#weight').val()+'&volume='+$('#volume').val()+'&count='+$('#count').val()+'&cod='+$('#cod').is(':checked');
    }
</script>
<div class="row">
	<div class="col-12">
		<div class="panel card" id="fieldset_np">
			<div class="panel-heading card-header">
				<i class="icon-truck"></i>{l s='Nova poshta extras' mod='kldeliverynppro'}
			</div>
			<div class="form-wrapper card-body">
				<form action="{$CCNUrl}" class="form-horizontal js_pdf_generator_garantee" method="post">
					<input type="button" onclick="createCCN(this); return false;" class="btn btn-primary" value="{l s='Create CCN' mod='kldeliverynppro'}">
					<input type="button" onclick="actionCCN(this, 'saveCCN', getURLselector()); return false;" class="btn btn-primary" value="{l s='Save' mod='kldeliverynppro'}">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">
									{l s='Name' mod='kldeliverynppro'}
								</label>
								<div>
									<input type="text" name="name" id="name" value="{$ccn.name|escape:'htmlall':'UTF-8'}" class="" required >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">
									{l s='Surname' mod='kldeliverynppro'}
								</label>
								<div>
									<input type="text" name="surname" id="surname" value="{$ccn.surname|escape:'htmlall':'UTF-8'}" class="" required >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">
									{l s='Phone' mod='kldeliverynppro'}
								</label>
								<div>
									<input type="text" name="phone" id="phone" value="{if strlen($ccn.phone) == 9}0{$ccn.phone|escape:'htmlall':'UTF-8'}{else}{$ccn.phone|escape:'htmlall':'UTF-8'}{/if}" class="" required >
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">
									{l s='Price' mod='kldeliverynppro'}
								</label>
								<div>
									<input type="text" name="price" id="price" value="{$ccn.price|escape:'htmlall':'UTF-8'}" class="" required >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">
									{l s='Weight' mod='kldeliverynppro'}
								</label>
								<div>
									<input type="text" name="weight" id="weight" value="{$ccn.weight|escape:'htmlall':'UTF-8'}" class="" required >
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">
									{l s='Volume' mod='kldeliverynppro'}
								</label>
								<div>
									<input type="text" name="volume" id="volume" value="{$ccn.volume|escape:'htmlall':'UTF-8'}" class="" required >
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">
									{l s='Seats amount' mod='kldeliverynppro'}
								</label>
								<div>
									<input type="text" name="count" id="count" value="{$ccn.count|escape:'htmlall':'UTF-8'}" class="" required >
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							{if version_compare(_PS_VERSION_, '1.7.0.0', '>=')}
								{hook h='displayCarrierExtraContent' city_np=$ccn.city mod='kldeliverynppro'}
							{else}
								{hook h='displayCarrierList' city_np=$ccn.city mod='kldeliverynppro'}
							{/if}
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">
							{l s='Cash on delivery' mod='kldeliverynppro'}
						</label>
						<div>
							<input type="checkbox" name="cod" id="cod" value="1" class="" {if $ccn.cod == 1}checked{/if}>
						</div>
					</div>
					<input type="hidden" name="id_order" id="id_order" value="{$ccn.id_order|escape:'htmlall':'UTF-8'}" class="">
					<input type="hidden" name="id_cn" id="id_cn" value="{$ccn.id_cn|escape:'htmlall':'UTF-8'}" class="">
					<input type="hidden" name="create" id="create" value="1" class="">
					<input type="button" class="btn btn-primary js-delivery-action-buttons" onclick="actionCCN(this, 'printCCN'); return false;" value="{l s='Print CCN' mod='kldeliverynppro'}" {if !$ccn.id_cn}style="display: none;"{/if}>
					<input type="button" class="btn btn-primary js-delivery-action-buttons" onclick="actionCCN(this, 'printMarkings'); return false;" value="{l s='Print Markings' mod='kldeliverynppro'}" {if !$ccn.id_cn}style="display: none;"{/if}>
					
					<input type="button" class="btn btn-primary js-delivery-action-buttons" onclick="actionCCN(this, 'deleteCCN'); return false;" value="{l s='Delete CCN' mod='kldeliverynppro'}" {if !$ccn.id_cn}style="display: none;"{/if}>
					{hook h='displayNPTracker' id_order=$ccn.id_order}
				</form>
				<div class="js_notification_np"></div>
			</div>
		</div>
	</div>
</div>
{addJsDef ajax_error=str_replace("\n", '', trim($ajax_error))}
{addJsDef selected_warehouse=trim($ccn.warehouse)}