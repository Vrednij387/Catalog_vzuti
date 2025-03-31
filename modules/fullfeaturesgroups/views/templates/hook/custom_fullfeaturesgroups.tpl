{**
* 2020 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2020 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{if !empty($data_feature)}
    <section class="product-features tabs card mt-2">
        <h3 class="h6">{l s='Data sheet list' mod='fullfeaturesgroups' }</h3>
        <table class="table table-bordered">
            <tbody>
            {foreach from=$data_feature item=feature}
                <tr>
                    <td colspan="2" class="feature-name">
                        <h4 class="page-product-heading">{$feature['name']|escape:'htmlall':'UTF-8'}</h4>
                    </td>
                </tr>
                {if count($feature['items']) > 0}
                    {foreach from=$feature['items'] item=feature_list}
                        <tr class="{cycle values="odd,even"}">
                            <td>{$feature_list['name_feature']|escape:'htmlall':'UTF-8'}</td>
                            <td>
                                {if isset($feature_list['value']) && is_array($feature_list['value'])}
                                    {assign var="feature_loop" value=count($feature_list['value'])}
                                    {foreach from=$feature_list['value'] item=feature_val}
                                        {$feature_loop = $feature_loop-1}
                                        {$feature_val|escape:'htmlall':'UTF-8'}{if $feature_loop > 0}{if isset($new_line_properties) && $new_line_properties}<br/>{else},{/if}{/if}
                                    {/foreach}
                                {else}
                                    {$feature_list['value']|escape:'htmlall':'UTF-8'}
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                {/if}
            {/foreach}
            </tbody>
        </table>
    </section>
{/if}