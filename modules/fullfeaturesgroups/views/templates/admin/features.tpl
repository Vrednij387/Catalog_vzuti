{**
* 2020 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2020 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{if isset($product->id) && isset($available_features)}
    <div id="product-features" class="panel product-tab">
        <input type="hidden" name="submitted_ffg_tabs" value="Features"/>
        <h3>{l s='Assign features to this product' mod='fullfeaturesgroups' }</h3>
        <div class="alert alert-info">
            {l s='You can specify a value for each relevant feature regarding this product. Empty fields will not be displayed.' mod='fullfeaturesgroups' }
            <br/>
            {l s='You can either create a specific value, or select among the existing pre-defined values you\'ve previously added.' mod='fullfeaturesgroups' }
        </div>
        <table class="table">
            <thead>
            <tr>
                <th><span class="title_box">{l s='Feature' mod='fullfeaturesgroups' }</span></th>
                <th><span class="title_box">{l s='Pre-defined value' mod='fullfeaturesgroups' }</span></th>
                <th>
                    <span class="title_box"><u>{l s='or' mod='fullfeaturesgroups' }</u> {l s='Customized value' mod='fullfeaturesgroups' }</span>
                </th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$available_features item=available_feature}
                <tr>
                    <td>{$available_feature.name|escape:'htmlall':'UTF-8'}</td>
                    <td class="feature-list-container">
                        {if sizeof($available_feature.featureValues)}
                            <ul id="ffrow-{$available_feature.id_feature|escape:'htmlall':'UTF-8'}"
                                class="sortable-list" style="list-style: none;">
                            {foreach from=$available_feature.featureValues item=value}
                                <li class="feature-field-item">
                                    <label for="feature-field-{$value.id_feature_value|escape:'htmlall':'UTF-8'}">
                                        <input type="checkbox" id="feature-field-{$value.id_feature_value|escape:'htmlall':'UTF-8'}"
                                               name="feature_gr_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_value[]"
                                               {if in_array($value.id_feature_value, $available_feature.current_item)}checked="checked"{/if}
                                               value="{$value.id_feature_value|escape:'htmlall':'UTF-8'}">
                                        {$value.value|truncate:40}
                                    </label>
                                    <span class="arrows-info">
                                        <?xml version="1.0" ?><!DOCTYPE svg  PUBLIC '-//W3C//DTD SVG 1.1//EN'  'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'>
                                        <svg enable-background="new 0 0 32 32" height="16px" version="1.1"
                                             viewBox="0 0 32 32" width="16px" xml:space="preserve"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g id="Double_Arrow_Up_x2F_Down">
                                                <path d="M27.704,7.19L20.706,0.29c-0.389-0.385-1.04-0.389-1.429,0l-6.999,6.9c-0.395,0.391-0.394,1.024,0,1.414   c0.394,0.391,1.034,0.391,1.428,0l5.275-5.2v15.593c0,0.552,0.452,1,1.01,1c0.558,0,1.01-0.448,1.01-1V3.404l5.275,5.2   c0.395,0.391,1.034,0.391,1.428,0C28.099,8.213,28.099,7.58,27.704,7.19z"/><path d="M18.294,23.396l-5.275,5.2V13.003c0-0.552-0.452-1-1.01-1c-0.558,0-1.01,0.448-1.01,1v15.593l-5.275-5.2   c-0.395-0.391-1.034-0.391-1.428,0c-0.395,0.391-0.395,1.024,0,1.414l6.999,6.899c0.389,0.385,1.04,0.389,1.429,0l6.999-6.9   c0.395-0.391,0.394-1.024,0-1.414C19.328,23.006,18.688,23.005,18.294,23.396z"/></g><g/><g/><g/><g/><g/><g/>
                                        </svg>
                                    </span>
                                </li>
                            {/foreach}
                            </ul>
                        {else}
                            <input type="hidden" name="feature_gr_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_value"
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
                    <fieldset class="form-group mb-0">
                        <label class="form-control-label">{l s='OR Customized value' mod='fullfeaturesgroups'}</label>
                        <div class="translations tabbable" id="form_step1_features_gr_custom_value">
                            <div class="translationsFields custom-feature-field-group tab-content">
                                {if $languages|count > 0}
                                {foreach from=$languages item=language}
                                    <div class="input-group tab-pane translation-field {if (isset($default_form_language) && $language.id_lang == $default_form_language)} show active {/if} translation-label-{$language['iso_code']|escape:'htmlall':'UTF-8'} language-inp-container">
                                        <span class="input-group-addon">{$language['iso_code']|escape:'htmlall':'UTF-8'}{if $language.id_lang == $default_form_language}*{/if}</span>
                                        <textarea
                                                class="custom_gr_{$available_feature.id_feature|escape:'htmlall':'UTF-8'} form-control feature-textarea-field textarea-autosize"
                                                name="custom_gr_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                                                cols="40" rows="1"
                                                onkeyup="if (isArrowKey(event)) return;$('#feature_{$available_feature.id_feature|escape:'htmlall':'UTF-8'}_value').val(0);">{$available_feature.val[$language.id_lang].value|escape:'html':'UTF-8'|default:""}</textarea>
                                    </div>
                                {/foreach}
                                {/if}
                            </div>
                        </div>
                    </fieldset>
                    </td>
                </tr>
                {foreachelse}
                <tr>
                    <td colspan="3" style="text-align:center;"><i
                                class="icon-warning-sign"></i> {l s='No features have been defined' mod='fullfeaturesgroups' }
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        <a href="{$link->getAdminLink('AdminFeatures')|escape:'html':'UTF-8'}&amp;addfeature"
           class="btn btn-link confirm_leave button">
            <i class="icon-plus-sign"></i> {l s='Add a new feature' mod='fullfeaturesgroups' } <i
                    class="icon-external-link-sign"></i>
        </a>
    </div>
{else}
    <div id="product-features" class="panel product-tab">
        <h3>{l s='Assign features to this product' mod='fullfeaturesgroups' }</h3>

        <div class="alert alert-warning">
            {l s='Please add this product category to one of the futures group in Feature Category section on Full Features Groupe module Settings' mod='fullfeaturesgroups' }
        </div>
    </div>
{/if}

{if isset($disabled_def_feature) && $disabled_def_feature == 1}
<style type="text/css">
    #features {
        display: none;
    }
</style>
{/if}
