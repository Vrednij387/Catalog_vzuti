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

{extends file="../tab_layout.tpl"}

{block name="form"}
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="disable_image" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-12">

                    <div class="float-left form-group">
                        <label class="control-label margin-right float-left pt-0 pt-sm-1">{l s='Apply change for' mod='masseditproduct'}:</label>
                        <span class="ps-switch prestashop-switch fixed-width-xxl switch-product-combination margin-right float-left">
                            {foreach [0,1] as $value}
                                <input type="radio" name="change_for_img" value="{$value|escape:'quotes':'UTF-8'}"
                                        {if $value == 1} id="change_for_product_image" {else} id="change_for_combination_image" {/if}
                                        {if $value == 0} checked="checked" {/if}
                                />
                                <label {if $value == 1} for="change_for_product_image" {else} for="change_for_combination_image" {/if}>
                                    {if $value == 0}{l s='Product' mod='masseditproduct'}{else}{l s='Combination' mod='masseditproduct'}{/if}
                                </label>
                            {/foreach}
                            <a class="slide-button"></a>
                        </span>
                    </div>
                    <div class="float-left">
                        <div class="float-left">
                            <button class="add_image btn btn-default margin-right">
                                <i class="icon-plus"></i>
                                {l s='Add image' mod='masseditproduct'}
                            </button>
                        </div>

                        <div class="form-group float-left">
                            <div class="md-checkbox">
                                <label class="control-label">
                                    <input type="checkbox" name="delete_images">
                                    <i class="md-checkbox-control"></i>
                                    {l s='Delete old images about products' mod='masseditproduct'}
                                </label>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <div class="images">
            </div>

        </div>
    </div>
    <div class="row enabled_option_stage form-group">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="disable_image_caption" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-lg-12">

            <div class="row form-group">
                <div class="col-lg-12">
                    <label class="control-label margin-right pt-0">
                    <span class="" data-toggle="tooltip" mod='masseditproduct'
                          title="{l s='Update all captions at once, or select the position of the image whose caption you wish to edit. Invalid characters: %s'|sprintf:'<>;=#{}' mod='masseditproduct'}">
                        {l s='Caption' mod='masseditproduct'}:
                    </span>
                    </label>
                    {foreach from=$languages item=language}
                        {if $languages|count > 1}
                            <div class="translatable-field row lang-{$language.id_lang|intval}">
                            <div class="col-sm-4">
                            <div class="row">
                            <div class="col-xs-9">
                        {/if}

                        <input type="text" id="legend_{$language.id_lang|intval}" class="float-left form-control  {if isset($input_class)}{$input_class|escape:'html':'UTF-8'}{/if}" name="legend_{$language.id_lang|intval}" data-lang="{$language.id_lang|intval}" value=""/>

                        {if $languages|count > 1}
                            </div>
                        <div class="col-xs-3">
                            <div class="btn-languages margin-right float-left ">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                    {$language.iso_code|escape:'html':'UTF-8'}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    {foreach from=$languages item=language}
                                        <li>
                                            <a href="javascript:hideOtherLanguage({$language.id_lang|intval});">{$language.name|escape:'html':'UTF-8'}</a>
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                        </div>
                        </div>
                        {/if}
                        <label class="control-label col-xs-12 pt-0">{l s='If other language an empty caption for him will be removed' mod='masseditproduct'}</label>
                        {if $languages|count > 1}
                            </div>
                        {/if}
                    {/foreach}
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">

                    <div class="w-100 clearfix">
                        <div id="caption_selection" class="form-group w-xs-100 margin-right float-left">
                            <select name="id_caption" class="fixed-width-xl w-xs-100 custom-select">
                                <option value="0">{l s='All captions' mod='masseditproduct'}</option>
                            </select>
                        </div>
                        <div class="form-group checkbox-delete float-left">
                            <span class="md-checkbox">
                                <label class="control-label pt-0 pt-sm-1">
                                    <input type="checkbox" name="delete_captions">
                                    <i class="md-checkbox-control"></i>
                                    {l s='Delete old captions' mod='masseditproduct'}
                                </label>
                            </span>
                        </div>
                    </div>

                    <div class="">
                        <div class="float-left form-group margin-right">
                            <button type="button" class="btn btn-default" onclick="$('[name^=legend]:visible').insertAtCaret('{literal}{name}{/literal}');">
                                {l s='name product' mod='masseditproduct'}
                            </button>
                        </div>

                        <div class="float-left form-group margin-right">
                            <button type="button" class="btn btn-default" onclick="$('[name^=legend]:visible').insertAtCaret('{literal}{manufacturer}{/literal}');">
                                {l s='manufacturer' mod='masseditproduct'}
                            </button>
                        </div>

                        <div class="float-left form-group">
                            <button type="button" class="btn btn-default" onclick="$('[name^=legend]:visible').insertAtCaret('{literal}{category}{/literal}');">
                                {l s='default category' mod='masseditproduct'}
                            </button>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
{/block}