{**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @author    Elcommerce <support@elcommece.com.ua>
 * @copyright 2010-2019 Elcommerce TM
 * @license   Comercial
 * @category  PrestaShop
 * @category  Module
*}
<div class="row">
	<div class="col-lg-12">
		<div id="fieldset_0" class="panel card">
			<div class="panel-heading card-header">
				<i class="icon-help"></i>{l s='Payment confirmation via Monobank' mod='ecm_monopay'}
			</div>
			{if isset($payment_holded) && $payment_holded}
				<form action="{$link->getAdminLink('AdminMonopayConfirmation')}" method="post">
					<fieldset style="padding:2rem">
						<p><b>{l s='You have a payment pending confirmation via Monobank' mod='ecm_monopay'}</b></p>
						<div class="alert alert-info">
							{l s='You can:' mod='ecm_monopay'}
							<ul style="list-style: square outside;">
								<li>{l s='cancel payment for the full amount;' mod='ecm_monopay'}</li>
								<li>{l s='confirm payment for the full amount;' mod='ecm_monopay'}</li>
								<li>{l s='confirm the payment in part by entering the amount in the appropriate field below (but not more than the entire amount of the order). Returning the remaining amount can take up to 3 days' mod='ecm_monopay'}
								</li>
							</ul>
						</div>
						<div class="row">
							<div class="col-md-2">
								<input name="ecmmonopayhold_invoiceid_monopay" type="hidden"
									value="{$ecmmonopayhold_invoiceid_monopay}">
								<input name="ecmmonopayhold_id_order" type="hidden" value="{$ecmmonopayhold_id_order}">
								<input id="ecmmonopayhold_currency_sign" name="ecmmonopayhold_currency_sign" type="hidden"
									value="{$ecmmonopayhold_currency_sign}">
								<input id="ecmmonopayhold_action" name="ecmmonopayhold_action" type="hidden" value="">
								<input id="ecmmonopayhold_paid" class="form-control" name="ecmmonopayhold_paid"
									type="number" step="0.01" value="{$ecmmonopayhold_paid}">
							</div>
							<div class="col-md-9">
								<input id="submitMonopayRefund" name="submitMonopayRefund" class="btn btn-danger"
									type="submit" value="{l s='Refund' mod='ecm_monopay'}"
									onclick="if(confirm('{l s='You definitely want to cancel the payment in the amount of ' mod='ecm_monopay'}'+ $('#ecmmonopayhold_paid').val() + $('#ecmmonopayhold_currency_sign').val() +' ?'))submit();else return false;" />
								<input id="submitMonopayHoldCompletion" name="submitMonopayHoldCompletion"
									class="btn btn-success" type="submit" value="{l s='Completion' mod='ecm_monopay'}"
									onclick="if(confirm('{l s='You confirm the payment for the amount ' mod='ecm_monopay'}'+ $('#ecmmonopayhold_paid').val()+ $('#ecmmonopayhold_currency_sign').val() + ' ?'))submit();else return false;" />
							</div>
						</div>
					</fieldset>
				</form>
			{/if}
			{if isset($invoice_created) && $invoice_created}
				<div class="info-order box" style="padding:2rem">
				<p><strong class="dark">{l s='Pay order via Monobank' mod='ecm_monopay'}</strong></p>
				<p> <i class="icon-file-text"></i> <a target="_blank" href="{$monoPayLink|escape:'html':'UTF-8'}">{$monoPayLink|escape:'html':'UTF-8'}</a></br>{l s='Order has been not completed and payed. You can send this link to client for complete payment this order via Monobank' mod='ecm_monopay'}
				</p>
			</div>
			{/if}
		</div>
	</div>
	{if isset($err) && $err}
		<script>
			{literal}
				function error_modal(heading, msg, alert_type) {
					var errorModal =
						$('<div class="bootstrap modal fade in">' +
							'<div class="modal-dialog">' +
							'<div class="modal-content alert alert-' + alert_type + ' clearfix">' +
							'<div class="modal-header">' +
							'<h4>' + heading + '</h4>' +
							'<a class="close" data-dismiss="modal" >&times;</a>' +
							'</div>' +
							'<div class="modal-body">' +
							'<p><b>' + msg + '</b></p>' +
							'</div>' +
							'<div class="modal-footer">' +
							'<a href="#" id="error_modal_right_button" class="btn btn-default">' +
							'{/literal}{l s='Close' mod='ecm_monopay'}{literal}' +
							'</a>' +
							'</div>' +
							'</div>' +
							'</div>' +
							'</div>');
					errorModal.find('#error_modal_right_button').click(function() {
						errorModal.modal('hide');
					});
					errorModal.modal('show');
				}
				error_modal('{/literal}{l s='Monobank' mod='ecm_monopay'}{literal}','{/literal}{$err}{literal}','{/literal}{$type}{literal}');{/literal}	
			</script>
	{/if}