{**
* 2018 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2018 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{extends file="helpers/list/list_content.tpl"}

{block name="open_td"}
{if isset($params.type) && $params.type == 'feature_fast_edit'}
<td colspan="2">
    {else}
<td>
    {/if}
    {/block}
    {block name="td_content"}
        {if isset($params.type) && $params.type == 'image'}
			<img style="border: 1px solid #f6f6f6;" height="98px" src="{$tr.$key|escape:'html':'UTF-8'}" alt="">
        {elseif isset($params.type) && $params.type == 'feature_fast_edit'}
			<div class="td_feature_container product_general_info_{$tr['id_product']|intval}">
                {if isset($tr['features']) && !empty($tr['features'])}
					<div class="row">
                        {foreach from=$tr['features'] item='feature'}
							<a class="col-lg-4 col-md-6 col-sm-12 js-fast-feature-edit {if count($feature.featureValues) == 0 && (!isset($feature.val[$params['default_lang']]) || empty($feature.val[$params['default_lang']]))}not-filled{/if}"
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
                                            {$value.value|escape:'htmlall':'UTF-8'},
                                        {/if}
                                    {/foreach}
                                {/if}
                                {if isset($feature.val[$params['default_lang']]) && !empty($feature.val[$params['default_lang']])}
									"{$feature.val[$params['default_lang']].value|escape:'html':'UTF-8'|default:""}"
                                {/if}
							</a>
                        {/foreach}
					</div>
                {/if}
			</div>
			<div class="td_feature_editable_container edit_feature_form_{$tr['id_product']|intval}"></div>
        {elseif isset($params.type) && $params.type == 'product_name'}
            {if isset($tr['default_category'])}
				<b>{$tr['default_category']|escape:'htmlall':'UTF-8'}</b><br/>
            {/if}
            {$tr.$key|escape:'html':'UTF-8'}
        {elseif isset($params.type) && $params.type == 'bool'}
            {$smarty.block.parent}
        {else}
            {$smarty.block.parent}
        {/if}
    {/block}
