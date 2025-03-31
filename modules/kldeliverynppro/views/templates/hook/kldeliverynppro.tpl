{**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{foreach $carriers as $carrier}
	<div class="form-group label-floating bmd-form-group col-md-12">
		<div class="js-city-insert">
			<label>{l s='City' mod='kldeliverynppro'}</label>
			<select name="city-js" id="js-cities" class="select-filling js_delivery_{$carrier|escape:'htmlall':'UTF-8'}" onchange="getWarehouses(this)" style="width:100%" data-id-lang="{$id_lang|escape:'htmlall':'UTF-8'}" data-count-click="0">
				<option value="">{l s='Choose a city...' mod='kldeliverynppro'}</option>
				{foreach from=$cities item=city}
				    <option value="{$city.name|escape:'htmlall':'UTF-8'}" data-ref="{$city.ref|escape:'htmlall':'UTF-8'}" {*if $city.ref == $current_city}selected{/if*}>{$city.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="form-group label-floating bmd-form-group col-md-12">
		<label>{l s='Warehouse' mod='kldeliverynppro'}</label>
		<select id="js-warehouses" class="select-filling" name="warehouse-js" onchange="saveCartNovaPoshta(this)">
			<option value="" class="js_warehouse_first_elem">
				{l s='Choose a warehouses...' mod='kldeliverynppro'}
			</option>
		</select>
	</div>
{/foreach}
{if $current_warehouse && $current_warehouse != ''}
	<script type="text/javascript">
		selected_warehouse='{$current_warehouse}'
	</script>
{/if}
<script type="text/javascript">
$(document).ready(function () {
	console.log('load');
	initialiseSelect2('#js-cities');
	if ($('#js-warehouses').length > 0) {
		initialiseSelect2('#js-warehouses');
	}
});
window.addEventListener('load', function () {
	getWarehouses('#js-cities');
	$(document).on("click","#js-cities,#js-warehouses",function() {
        if ($(this).closest('.form-group').hasClass('has-error')) {
			$(this).closest('.form-group').removeClass('has-error');
		}
    });
	$('.steco_confirmation_btn').click(function(e){
		if ($('#js-cities > option:selected').length == 0 || $('#js-warehouses > option:selected').length == 0) {
			if ($('#js-cities > option:selected').length == 0) {
				$('#js-cities').closest('.form-group').addClass('has-error');
			} else {
				$('#js-warehouses').closest('.form-group').addClass('has-error');
			}
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
			return false;
		} else {
			if ($('#delivery_option_'+np_id_carrier+':checked').length <= 0) {
		      return;
		    }
		    // var city = $('#js-cities option:selected').val();
		    // var warehouse = $('#js-warehouses option:selected').val();
		    var city = $('#js-cities option[value="'+$('#js-cities option:selected').val().replaceAll('"','\\"').replaceAll("'","\\'")+'"]').data('ref'); 
    		var warehouse = $('#js-warehouses option[value="'+$('#js-warehouses option:selected').val().replaceAll('"','\\"').replaceAll("'","\\'")+'"]').data('ref');
		    $.ajax({
		      url: $('#saveCartUrl').data('value'),
		      type: "post",
		      dataType: "json",
		      data: {
		          "city-js": city,
		          "warehouse-js":  warehouse,
		      }
		    });
		}
	});
});
</script>