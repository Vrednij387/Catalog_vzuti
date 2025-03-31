{**
* 2018 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2018 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{if isset($product->id) && isset($available_feature)}
	<form id="fast_ffg_edit_form" action="">
	<div id="product-features" class="product-tab">
		<input type="hidden" value="{$default_form_language|escape:'htmlall':'UTF-8'}" class="default_init_lang">
		<table class="table">
			<tbody>
            {if isset($available_feature) && count($available_feature)}
				<tr>
					<td>{$available_feature.name|escape:'htmlall':'UTF-8'}</td>
					<td width="40%">
						{if sizeof($available_feature.featureValues)}
							<select size="3" multiple="3" id="feature_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_value"
									name="feature_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_value[]"
									onchange="$('.custom_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_').val('');">
								<option selected="selected" value="0">---</option>
								{if isset($available_feature['featureValues']) && is_array($available_feature['featureValues']) && count($available_feature['featureValues'])}
									{foreach from=$available_feature.featureValues item=value}
										<option value="{$value.id_feature_value|escape:'htmlall':'UTF-8'}"{if isset($available_feature.current_item) && is_array($available_feature.current_item) && count($available_feature.current_item) && in_array($value.id_feature_value, $available_feature.current_item)}selected="selected"{/if} >
											{$value.value|escape:'htmlall':'UTF-8'}
										</option>
									{/foreach}
								{/if}
							</select>
                        {else}
							<input type="hidden"
								   name="feature_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_value"
								   value="0"/>
							<span>{l s='N/A' mod='fullfeaturesgroups'} -
						<a href="{$link->getAdminLink('AdminFeatures')|escape:'html':'UTF-8'}&amp;addfeature_value&amp;id_feature={$available_feature.id_feature|escape:'htmlall':'UTF-8'}"
						   class="confirm_leave btn btn-link"><i
									class="icon-plus-sign"></i> {l s='Add pre-defined values first' mod='fullfeaturesgroups' }
							<i class="icon-external-link-sign"></i></a>
					</span>
                        {/if}
					</td>
					<td>
						<div class="row lang-0" style='display: none;'>
							<div class="col-lg-10">
                                <textarea
										class="custom_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_ALL textarea-autosize"
										name="custom_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_ALL"
										cols="40" style='background-color:#CCF' rows="1"
										onkeyup="{foreach from=$languages key=k item=language}$('.custom_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}').val($(this).val());{/foreach}">{$available_feature.val[1].value|escape:'html':'UTF-8'|default:""}</textarea>
							</div>
                            {if $languages|count > 1}
								<div class="col-lg-2">
									<button type="button" class="btn btn-default dropdown-toggle"
											data-toggle="dropdown">
                                        {l s='ALL' mod='fullfeaturesgroups'}
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
                                        {foreach from=$languages item=language}
										<li>
											<a href="javascript:void(0);"
											   onclick="restore_lng($(this),{$language.id_lang|escape:'htmlall':'UTF-8'});">{$language.iso_code|escape:'htmlall':'UTF-8'}</a>
										</li>
                                        {/foreach}
									</ul>
								</div>
                            {/if}
						</div>
                        {foreach from=$languages key=k item=language}
                            {if $languages|count > 1}
								<div class="row translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}">
								<div class="col-lg-10">
                            {/if}
							<textarea
									class="custom_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'} textarea-autosize"
									name="custom_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
									cols="40"
									rows="1"
									onkeyup="if (isArrowKey(event)) return;$('#feature_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_value').val(0);">{$available_feature.val[$language.id_lang].value|escape:'html':'UTF-8'|default:""}</textarea>
                            {if $languages|count > 1}
								</div>
								<div class="col-lg-2">
									<button type="button" class="btn btn-default dropdown-toggle"
											data-toggle="dropdown">
                                        {$language.iso_code|escape:'htmlall':'UTF-8'}
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="javascript:void(0);"
											   onclick="all_languages($(this));">{l s='ALL' mod='fullfeaturesgroups'}</a>
										</li>
                                        {foreach from=$languages item=language}
											<li>
												<a href="javascript:hideOtherLanguage({$language.id_lang|intval});">{$language.iso_code|escape:'htmlall':'UTF-8'}</a>
											</li>
                                        {/foreach}
									</ul>
								</div>
								</div>
                            {/if}
                        {/foreach}
					</td>
					<td class="fgg_action_td">
						<div class="action-btn-ffg-container pull-right">
							<button data-id_product="{$available_feature['id_product']|intval}"
									data-id_feature="{$available_feature['id_feature']|intval}"
									class="js-fast-feature-save btn btn-default">
								<i class="process-icon-save"></i>
							</button>
							<br/>
							<button class="js-fast-feature-cancel btn btn-default">
								<i class="process-icon-back"></i>
							</button>
						</div>
					</td>
				</tr>
                {else}
				<tr>
					<td colspan="4" style="text-align:center;"><i class="icon-warning-sign"></i>
						{l s='No features have been defined' mod='fullfeaturesgroups' }
					</td>
				</tr>
				{/if}
			</tbody>
		</table>
		<script type="text/javascript">
		{literal}
			function all_languages(pos) {
		{/literal}
				{if isset($languages) && is_array($languages)}
					{foreach from=$languages key=k item=language}
						pos.parents('td').find('.lang-{$language.id_lang|escape:'htmlall':'UTF-8'}').addClass('nolang-{$language.id_lang|escape:'htmlall':'UTF-8'}').removeClass('lang-{$language.id_lang|escape:'htmlall':'UTF-8'}');
					{/foreach}
				{/if}
				pos.parents('td').find('.translatable-field').hide();
				pos.parents('td').find('.lang-0').show();
				{literal}
					}

				function restore_lng(pos, i) {
				{/literal}
				{if isset($languages) && is_array($languages)}
					{foreach from=$languages key=k item=language}
						pos.parents('td').find('.nolang-{$language.id_lang|escape:'htmlall':'UTF-8'}').addClass('lang-{$language.id_lang|escape:'htmlall':'UTF-8'}').removeClass('nolang-{$language.id_lang|escape:'htmlall':'UTF-8'}');
					{/foreach}
				{/if}
				{literal}
					pos.parents('td').find('.lang-0').hide();
					hideOtherLanguage(i);
					}
		{/literal}
		</script>
	</div>
	</form>
{else}
	<div id="product-features" class="panel product-tab">
		<h3>{l s='Assign features to this product' mod='fullfeaturesgroups' }</h3>

		<div class="alert alert-warning">
            {l s='Please add this product category to one of the futures group in Feature Category section on Full Features Groupe module Settings' mod='fullfeaturesgroups' }
		</div>
	</div>
{/if}



