{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2023 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

{if isset($variables)}
        <!-- block-property-btn variables -->
        <div class="block-property">
            {if is_array($variables.features) && count($variables.features)}
                <div class="">
                    <div data-feature-btn class="">
                        <div class="column_feature w-xs-100 form-group">
                            <select class="custom-select fixed-width-lg w-xs-100" onclick="var btn = $(this).closest('[data-feature-btn]'); btn.find('[class^=column_feature_value_]').hide(); btn.find('.column_feature_value_'+$(this).val()).show();" name="variable_feature">
                                {foreach from=$variables.features item=feature}
                                    {if !is_array($feature.values) || !count($feature.values)}{continue}{/if}
                                    <option value="{$feature.id_feature|intval}">{$feature.name|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                            </select>
                        </div>
                        {*{foreach from=$variables.features item=feature}*}
                        {*{if is_array($feature.values) && count($feature.values)}*}
                        {*<div {if !$smarty.foreach.feature.first}style="display: none;"{/if} class="column_feature_value_{$feature.id_feature|intval}">*}
                        {*<select name="variable_feature_value">*}
                        {*{foreach from=$feature.values item=value}*}
                        {*<option value="{$value.id_feature_value|intval}">{$value.value|escape:'quotes':'UTF-8'}</option>*}
                        {*{/foreach}*}
                        {*</select>*}
                        {*</div>*}
                        {*{/if}*}
                        {*{/foreach}*}
                        <div class="w-xs-100">
                            <div class="float-left form-group w-xs-100">
                                <button onclick="var btn = $(this).closest('[data-feature-btn]'); $('[name={$name|escape:'quotes':'UTF-8'|trim}]').insertAtCaret({literal}'{feature_value_'+btn.find('[name=variable_feature]').val()+'}'{/literal});" class="btn btn-success w-xs-100" type="button">
                                    {l s='add value' mod='masseditproduct'}
                                </button>
                            </div>
                            <div class="float-left form-group w-xs-100">
                                <button onclick="var btn = $(this).closest('[data-feature-btn]'); $('[name={$name|escape:'quotes':'UTF-8'|trim}]').insertAtCaret({literal}'{feature_'+btn.find('[name=variable_feature]').val()+'}'{/literal});" class="btn btn-success w-xs-100" type="button">
                                    {l s='add feature' mod='masseditproduct'}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            {/if}
            <div>
                {if is_array($variables) && count($variables)}
                    {foreach from=$variables.static key=var_name item=variable}
                        <div class="form-group mr-1 float-left">
                            <button type="button" class="btn btn-default" onclick="$('[name={$name|escape:'quotes':'UTF-8'|trim}]').insertAtCaret('{$var_name|escape:'quotes':'UTF-8'}');">
                                {$variable|escape:'quotes':'UTF-8'}
                            </button>
                        </div>
                    {/foreach}
                {/if}
            </div>
        </div>
{/if}