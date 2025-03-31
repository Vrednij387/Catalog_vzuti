{**
* 2016 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2016 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{if isset($data_feature) && is_array($data_feature) && count($data_feature) > 0}
<section class="product-features">
    <h3 class="h6">{l s='Data sheet content' mod='fullfeaturesgroups' }</h3>
    <dl class="data-sheet">
        {foreach from=$data_feature item=feature}
            <dt class="features_title">
                <h4 class="page-product-heading">
                    {$feature['name']|escape:'htmlall':'UTF-8'}
                </h4>
            </dt>
            {if isset($feature['items']) && count($feature['items']) > 0}
                {foreach from=$feature['items'] item=feature_list}
                    <dt class="name">{$feature_list['name_feature']|escape:'htmlall':'UTF-8'}</dt>
                    <dd class="value">
                        {if isset($feature_list['value']) && is_array($feature_list['value'])}
                            {assign var="feature_loop" value=count($feature_list['value'])}
                            {foreach from=$feature_list['value'] item=feature_val}
                                {$feature_loop = $feature_loop-1}
                                {$feature_val|escape:'htmlall':'UTF-8'}
                                {if $feature_loop > 0}{if isset($new_line_properties) && $new_line_properties}<br/>{else},{/if}{/if}
                            {/foreach}
                        {else}
                            {$feature_list['value']|escape:'htmlall':'UTF-8'}
                        {/if}
                    </dd>
                {/foreach}
            {/if}
        {/foreach}
    </dl>
</section>
{/if}
