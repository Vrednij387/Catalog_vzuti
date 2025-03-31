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
    <!-- refefence tab -->
    <div class="row enabled_option_stage_static">
        <div class="col-sm-12">
            <label class="control-label margin-right float-left pt-0 pt-sm-1">{l s='Apply change for' mod='masseditproduct'}:</label>
            <span class="ps-switch prestashop-switch fixed-width-xxl switch-product-combination float-left">
                        {foreach [0,1] as $value}
                            <input type="radio" name="change_for_property" value="{$value|escape:'quotes':'UTF-8'}"
                                    {if $value == 1} id="change_for_product_property" {else} id="change_for_combination_property" {/if}
                                    {if $value == 0} checked="checked" {/if}
                            />
                            <label {if $value == 1} for="change_for_product_property" {else} for="change_for_combination_property" {/if}>
                                {if $value == 0}{l s='Product' mod='masseditproduct'}{else}{l s='Combination' mod='masseditproduct'}{/if}
                            </label>
                        {/foreach}
                <a class="slide-button"></a>
            </span>
        </div>
    </div>
    <div class="row enabled_option_stage" style="padding: 0 0 10px 10px">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="selected_set_unique_reference" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="md-checkbox">
            <label class="control-label">
                <input type="checkbox" name="set_unique_reference" class="tab4-checkbox" value="1"/>
                <i class="md-checkbox-control"></i>
                {l s='Set unique reference' mod='masseditproduct'}
            </label>
        </div>
    </div>
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="selected_reference" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 form-group">
            <label class="control-label margin-right pt-0 pt-sm-1">
                {l s='Reference code' mod='masseditproduct'}:
            </label>
            <input class="fixed-width-xl w-xs-100 form-control" name="reference" type="text"/>
        </div>
    </div>
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="selected_ean13" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 form-group">
            <label class="control-label margin-right pt-0 pt-sm-1">
                {l s='EAN-13 or JAN barcode' mod='masseditproduct'}:
            </label>
            <input class="fixed-width-xl w-xs-100 form-control" maxlength="13" name="ean13" type="text"/>
        </div>
    </div>
    <div class="row enabled_option_stage form-group">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="selected_upc" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 form-group">
            <label class="control-label margin-right pt-0 pt-sm-1">
                {l s='UPC barcode' mod='masseditproduct'}:
            </label>
            <input class="fixed-width-xl w-xs-100 form-control" maxlength="12" name="upc" type="text"/>
        </div>
    </div>
{/block}