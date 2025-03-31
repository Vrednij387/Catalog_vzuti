{**
* 2018 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2018 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{if isset($tr['features']) && !empty($tr['features'])}
	<div class="row">
		{foreach from=$tr['features'] item='feature'}
			<a class="col-lg-4 col-md-6 col-sm-12 js-fast-feature-edit"
			   data-id_product="{$tr['id_product']|intval}"
			   data-id_feature="{$feature['id_feature']|intval}"
			   href="">
				<b>{$feature.name|escape:'htmlall':'UTF-8'}:</b>
				{if sizeof($feature.featureValues)}
					{foreach from=$feature.featureValues item=value}
						{if isset($feature.current_item)
						&& is_array($feature.current_item)
						&& count($feature.current_item)
						&& in_array($value.id_feature_value, $feature.current_item)}
							{$value.value|escape:'html':'UTF-8'},
						{/if}
					{/foreach}
				{/if}
				{if isset($feature.val[$default_lang]) && !empty($feature.val[$default_lang])}
					"{$feature.val[$default_lang].value|escape:'html':'UTF-8'|default:""}"
				{/if}
			</a>
		{/foreach}
	</div>
{/if}


