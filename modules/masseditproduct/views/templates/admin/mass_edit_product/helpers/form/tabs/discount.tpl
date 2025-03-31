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
    <!-- discount tab -->
    <div class="row disabled_option_stage enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked="" type="checkbox" name="disabled[]" value="specific_price" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-12">
                    {*<label class="control-label apply_change_tab8 margin-right">{l s='Action' mod='masseditproduct'}:</label>*}
                    {*<span class="switch prestashop-switch fixed-width-xl margin-right">*}
                    {*{foreach [0,1] as $value}*}
                    {*<input type="radio" name="action_for_sp" value="{$value|escape:'quotes':'UTF-8'}"*}
                    {*{if $value == 1} id="change_for_sp_add" {else} id="change_for_sp_delete" {/if}*}
                    {*{if $value == 0} checked="checked" {/if} />*}
                    {*<label {if $value == 1} for="change_for_sp_add" {else} for="change_for_sp_delete" {/if}>*}
                    {*{if $value == 1}{l s='Delete' mod='masseditproduct'}{else}{l s='Add' mod='masseditproduct'}{/if}*}
                    {*</label>*}
                    {*{/foreach}*}
                    {*<a class="slide-button btn"></a>*}
                    {*</span>*}
                    <div class="float-left">

                        <label class="control-label float-left margin-right pt-0 pt-sm-1">{l s='Action' mod='masseditproduct'}:</label>

                        <div class="btn-group btn-group-radio margin-right float-left">
                            <label for="change_for_sp_add" id="off_menu">
                                <input type="radio" checked name="action_for_sp" value="0" id="change_for_sp_add"/>
                                <span class="">{l s='Add' mod='masseditproduct'}</span>
                            </label>
                            <label for="change_for_sp_delete" id="off_menus">
                                <input type="radio" name="action_for_sp" value="1" id="change_for_sp_delete"/>
                                <span class="">{l s='Delete' mod='masseditproduct'}</span>
                            </label>
                            <label for="change_for_sp_edit" id="trigger">
                                <input type="radio" name="action_for_sp" value="2" id="change_for_sp_edit"/>
                                <span class="">{l s='Edit' mod='masseditproduct'}</span>
                            </label>
                        </div>

                        <div class="float-left form-group">
                            <label class="control-label apply_change_tab8 margin-right float-left">{l s='Apply change for' mod='masseditproduct'}:</label>
                            <span class="ps-switch prestashop-switch fixed-width-xxl switch-product-combination float-left">
                                {foreach [0,1] as $value}
                                    <input type="radio" name="change_for_sp" value="{$value|escape:'quotes':'UTF-8'}"
                                            {if $value == 1} id="change_for_sp_product" {else} id="change_for_sp_combination" {/if}
                                            {if $value == 0} checked="checked" {/if} />
                                    <label {if $value == 1} for="change_for_sp_product" {else} for="change_for_sp_combination" {/if}>
                                        {if $value == 1}{l s='Combination' mod='masseditproduct'}{else}{l s='Product' mod='masseditproduct'}{/if}
                                    </label>
                                {/foreach}
                                <a class="slide-button"></a>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
            <hr />

            <div class="search-block" style="display: none">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label">{l s='Search' mod='masseditproduct'}:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label apply_change2 margin-right float-left">{l s='For' mod='masseditproduct'}:</label>
                        <div class="float-left form-group">
                            <select name="search_id_currency" class="custom-select fixed-width-lg margin-right">
                                <option value="0">{l s='All currencies' mod='masseditproduct'}</option>
                                {if is_array($currencies) && count($currencies)}
                                    {foreach from=$currencies item=currency}
                                        <option value="{$currency.id_currency|intval}">{$currency.name|escape:'quotes':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                        <div class="float-left form-group">
                            <select name="search_id_country" class="custom-select fixed-width-lg margin-right">
                                <option value="0">{l s='All countries' mod='masseditproduct'}</option>
                                {if is_array($countries) && count($countries)}
                                    {foreach from=$countries item=country}
                                        <option value="{$country.id_country|intval}">{$country.country|escape:'quotes':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                        <div class="float-left form-group">
                            <select name="search_id_group" class="custom-select fixed-width-lg">
                                <option value="0">{l s='All groups' mod='masseditproduct'}</option>
                                {if is_array($groups) && count($groups)}
                                    {foreach from=$groups item=group}
                                        <option value="{$group.id_group|intval}">{$group.name|escape:'quotes':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">

                        <div class="float-left form-group">
                            <label class="control-label margin-right">{l s='From' mod='masseditproduct'}:</label>
                            <input class="datepicker fixed-width-lg margin-right form-control" name="search_from" type="text"/>
                        </div>

                        <div class="float-left form-group">
                            <label class="control-label margin-right">{l s='To' mod='masseditproduct'}:</label>
                            <input class="datepicker fixed-width-lg form-control" name="search_to" type="text"/>
                        </div>

                    </div>
                </div>
                <div class="row form-group" style="margin-top: 10px;">
                    <div class="col-sm-12">
                        <label class="control-label margin-right">{l s='Begin from quantity' mod='masseditproduct'}:</label>
                        <input name="search_from_quantity" class="fixed-width-sm margin-right form-control" value="1" type="text"/>
                    </div>
                </div>
                <hr />
            </div>

            <div class="edit-block">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label pt-0">{l s='Edit' mod='masseditproduct'}:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label apply_change2 margin-right float-left">{l s='For' mod='masseditproduct'}:</label>
                        <div class="float-left form-group">
                            <select name="sp_id_currency" class="fixed-width-xl margin-right custom-select">
                                <option value="0">{l s='All currencies' mod='masseditproduct'}</option>
                                {if is_array($currencies) && count($currencies)}
                                    {foreach from=$currencies item=currency}
                                        <option value="{$currency.id_currency|intval}">{$currency.name|escape:'quotes':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                        <div class="float-left form-group">
                            <select name="sp_id_country" class="fixed-width-lg margin-right custom-select">
                                <option value="0">{l s='All countries' mod='masseditproduct'}</option>
                                {if is_array($countries) && count($countries)}
                                    {foreach from=$countries item=country}
                                        <option value="{$country.id_country|intval}">{$country.country|escape:'quotes':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                        <div class="float-left form-group">
                            <select name="sp_id_group" class="fixed-width-lg custom-select">
                                <option value="0">{l s='All groups' mod='masseditproduct'}</option>
                                {if is_array($groups) && count($groups)}
                                    {foreach from=$groups item=group}
                                        <option value="{$group.id_group|intval}">{$group.name|escape:'quotes':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>

                    </div>
                    <input name="sp_id_product_attribute" value="0" type="hidden"/>
                </div>
                <div class="row">
                    <div class="col-sm-12">

                        <div class="float-left form-group">
                            <label class="control-label margin-right">{l s='From' mod='masseditproduct'}:</label>
                            <input class="datepicker fixed-width-lg margin-right form-control" name="sp_from" type="text"/>
                        </div>

                        <div class="float-left form-group">
                            <label class="control-label margin-right">{l s='To' mod='masseditproduct'}:</label>
                            <input class="datepicker fixed-width-lg form-control" name="sp_to" type="text"/>
                        </div>

                    </div>
                </div>
                <script>
                    $('.datepicker').datetimepicker({
                        prevText: '',
                        nextText: '',
                        dateFormat: 'yy-mm-dd',
                        // Define a custom regional settings in order to use PrestaShop translation Tools
                        currentText: '{l s='Now' mod='masseditproduct' js=true}',
                        closeText: '{l s='Done' mod='masseditproduct' js=true}',
                        ampm: false,
                        amNames: ['AM', 'A'],
                        pmNames: ['PM', 'P'],
                        timeFormat: 'hh:mm:ss tt',
                        timeSuffix: '',
                        timeOnlyTitle: '{l s='Choose Time' mod='masseditproduct' js=true}',
                        timeText: '{l s='Time' mod='masseditproduct' js=true}',
                        hourText: '{l s='Hour' mod='masseditproduct' js=true}',
                        minuteText: '{l s='Minute' mod='masseditproduct' js=true}'
                    });
                </script>
                <div class="row form-group">
                    <div class="col-sm-12">
                        <label class="control-label margin-right">{l s='Begin from quantity' mod='masseditproduct'}:</label>
                        <input name="sp_from_quantity" class="fixed-width-sm margin-right form-control" value="1" type="text"/>
                    </div>
                </div>
                <hr />

                <div class="row">
                    <div class="col-sm-12">
                        <div class="btn-group btn-group-radio edit_menu1 form-group mt-0">
                            <label for="discount_price_disable">
                                <input type="radio" checked name="discount_price" value="-1" id="discount_price_disable"/>
                                <span class="">{l s='Keep' mod='masseditproduct'}</span>
                            </label>
                            <label for="discount_price_increase">
                                <input type="radio" name="discount_price" value="0" id="discount_price_increase"/>
                                <span class="">{l s='Increase' mod='masseditproduct'}</span>
                            </label>
                            <label for="discount_price_reduce">
                                <input type="radio" name="discount_price" value="1" id="discount_price_reduce"/>
                                <span class="">{l s='Reduce' mod='masseditproduct'}</span>
                            </label>
                            <label for="discount_price_rewrite">
                                <input type="radio" name="discount_price" value="2" id="discount_price_rewrite"/>
                                <span class="">{l s='Rewrite' mod='masseditproduct'}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-12">

                        <label class="control-label margin-right">{l s='Product price (tax excl.)' mod='masseditproduct'}:</label>
                        <input type="text" disabled class="specific_price_price fixed-width-sm margin-right form-control" name="price" value="">
                        <select name="discount_price_reduction_type" class="fixed-width-md margin-right custom-select" style="display:none;">
                            <option value="amount">{l s='Currency' mod='masseditproduct'}</option>
                            <option value="percentage">{l s='Percent' mod='masseditproduct'}</option>
                        </select>
                        <span class="md-checkbox">
                            <label>
                                <input type="checkbox" name="leave_base_price" class="leave_base_price" checked>
                                <i class="md-checkbox-control"></i>
                                {l s='Leave base price' mod='masseditproduct'}
                            </label>
                        </span>
                    </div>
                </div>
                <hr />

                <div class="row ">
                    <div class="col-sm-12">
                        <div class="btn-group btn-group-radio edit_menu2 form-group" style="display: none;">
                            <label for="discount_discount_disable">
                                <input type="radio" checked name="discount_discount" value="-1" id="discount_discount_disable"/>
                                <span class="">{l s='Keep' mod='masseditproduct'}</span>
                            </label>
                            <label for="discount_discount_increase">
                                <input type="radio" name="discount_discount" value="0" id="discount_discount_increase"/>
                                <span class="">{l s='Increase' mod='masseditproduct'}</span>
                            </label>
                            <label for="discount_discount_reduce">
                                <input type="radio" name="discount_discount" value="1" id="discount_discount_reduce"/>
                                <span class="">{l s='Reduce' mod='masseditproduct'}</span>
                            </label>
                            <label for="discount_discount_rewrite">
                                <input type="radio" name="discount_discount" value="2" id="discount_discount_rewrite"/>
                                <span class="">{l s='Rewrite' mod='masseditproduct'}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 float-left" style="margin-bottom: 10px;" id="select_discount">
                        <label class="control-label margin-right float-left pt-0 pt-sm-1">{l s='Apply change for' mod='masseditproduct'}:</label>
                        <span class="ps-switch prestashop-switch float-left fixed-width-500">
                            {foreach [1,0] as $value}
                                <input type="radio" name="select_discount" value="{$value|escape:'quotes':'UTF-8'}"
                                        {if $value == 1} id="select-discount_impact" {else} id="select-discount_apply" {/if}
                                        {if $value == 0} checked="checked" {/if} />
                                <label {if $value == 1} for="select-discount_impact" {else} for="select-discount_apply" {/if}>
                                    {if $value == 1}{l s='Apply a discount of' mod='masseditproduct'}{else}{l s='Impact on price' mod='masseditproduct'}{/if}
                                </label>
                            {/foreach}
                            <a class="slide-button"></a>
                        </span>
                    </div>
                </div>

                <div class="row margin-top" id="discount_impact">
                    <div class="col-sm-12">
                        <label class="control-label margin-right float-left">{l s='Apply discount' mod='masseditproduct'}:</label>
                        <input name="sp_reduction" class="fixed-width-sm margin-right form-control float-left" value="0" type="text"/>
                        <select name="sp_reduction_type" class="fixed-width-md  margin-right custom-select float-left">
                            <option value="amount">{l s='Currency' mod='masseditproduct'}</option>
                            <option value="percentage">{l s='Percent' mod='masseditproduct'}</option>
                        </select>
                        <select class="custom-select fixed-width-lg float-left mr-1" name="sp_reduction_tax" id="sp_reduction_tax">
                            <option value="0">{l s='Tax excluded' mod='masseditproduct'}</option>
                            <option value="1">{l s='Tax included' mod='masseditproduct'}</option>
                        </select>
                    </div>
                </div>
                <div class="row margin-top" id="discount_apply" style="display: none">
                    <div class="col-sm-12">
                        <label class="control-label margin-right form-group float-left" style="padding-right: 7px;">{l s='Cost of goods' mod='masseditproduct'}:</label>
                        <div class="float-left form-group">
                            <input name="sp_reduction_ai" class="fixed-width-sm margin-right form-control float-left" value="0" type="text"/>
                        </div>
                        <div class="float-left form-group">
                            <select name="sp_reduction_type_ai" class="fixed-width-md  margin-right custom-select float-left">
                                <option value="amount">{l s='Currency' mod='masseditproduct'}</option>
                                <option value="percentage">{l s='Percent' mod='masseditproduct'}</option>
                            </select>
                        </div>
                        <div class="float-left form-group">
                            <select class="custom-select fixed-width-lg float-left mr-2" name="sp_reduction_tax_ai" id="sp_reduction_tax_ai">
                                <option value="0">{l s='Tax excluded' mod='masseditproduct'}</option>
                                <option value="1">{l s='Tax included' mod='masseditproduct'}</option>
                            </select>
                        </div>

                    </div>

                </div>
                <script>
$('#select_discount').on('click', function () {
    var select = ($(this).find("input:checked").attr('id'));
    if (select == 'select-discount_impact') {
        $('#discount_impact').hide();
        $('#discount_apply').show();
     } else {
        $('#discount_impact').show();
        $('#discount_apply').hide();
    }
})
                </script>

                <hr />
                <div class="row form-group">
                    <div for="col-lg-12" class="col-lg-12 checkbox-delete">
                        <span class="md-checkbox">
                            <label>
                                <input type="checkbox" name="delete_old_discount">
                                <i class="md-checkbox-control"></i>
                                {l s='Delete old discount' mod='masseditproduct'}
                            </label>
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row disabled_option_stage enabled_option_stage form-group">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked="" type="checkbox" name="disabled[]" value="delete_specific_price_all" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div for="col-lg-12" class="col-lg-12 checkbox-delete form-group">
            <span class="md-checkbox">
                <label>
                    <input type="checkbox" name="delete_old_discount_all">
                    <i class="md-checkbox-control"></i>
                    {l s='Delete all discount' mod='masseditproduct'}
                </label>
            </span>
        </div>
    </div>
{/block}