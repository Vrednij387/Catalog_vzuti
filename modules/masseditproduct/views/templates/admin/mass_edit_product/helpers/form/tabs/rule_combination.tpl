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
    <div class="row hidden enabled_option_stage">
        <div class="col-sm-12 clearfix ">
            <label class="control-label margin-right ">{l s='Apply change for' mod='masseditproduct'}:</label>
            <span class="ps-switch prestashop-switch fixed-width-xl switch-product-combination margin-right">
                        {foreach [0,1] as $value}
                            <input type="radio" name="rc_apply_change_for" value="{$value|escape:'quotes':'UTF-8'}"
                                    {if $value == 1} id="rc_apply_change_for_product" {else} id="rc_apply_change_for_combination" {/if}
                                    {if $value == 0} checked="checked" {/if}
                            />
                            <label {if $value == 1} for="rc_apply_change_for_product" {else} for="rc_apply_change_for_combination" {/if}>
                                {if $value == 0}{l s='Product' mod='masseditproduct'}{else}{l s='Combination' mod='masseditproduct'}{/if}
                            </label>
                        {/foreach}
                <a class="slide-button"></a>
            </span>
        </div>
    </div>
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="selected_attributes" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-xs-12">
            <label class="control-label pt-1 pt-sm-0">{l s='Delete combinations, which match attributes' mod='masseditproduct'}:</label>
        </div>
        <div class="col-xs-12">
            <div class="col-xs-12">
                <div class="row row_attributes">
                    {if is_array($attribute_groups) && count($attribute_groups)}
                        <div class="form-group float-left w-xs-100">
                            <select id="select_tab" class="fixed-width-xl margin-right custom-select select_attribute w-xs-100" name="attribute_group">
                                <option class="first" value="">--</option>
                            </select>
                        </div>
                        <div class="form-group float-left w-xs-100">
                            <select name="attributes" class="fixed-width-xl margin-right custom-select attr-values w-xs-100">
                            </select>
                        </div>
                        <div class="form-group float-left w-xs-100">
                            <button class="btn btn-success addAttribute w-xs-100">
                                <i class="icon-plus"></i>
                                {l s='Add attribute' mod='masseditproduct'}
                            </button>
                        </div>
                        <input type="hidden" name="selected_attributes" value="">
                    {/if}
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="selected_attributes"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <span class="md-checkbox">
                        <label>
                            <input type="checkbox" name="exact_match">
                            <i class="md-checkbox-control"></i>
                            {l s='Exact Match' mod='masseditproduct'}:
                        </label>
                    </span>
                </div>
                <div class="col-xs-12 form-group">
                    <div class="alert alert-info">
                        {l s='Search exact match. In combinations of products in this case must be the same set of attributes that you have chosen' mod='masseditproduct'}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="delete_attribute" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-xs-12">
            <label class="control-label pt-1 pt-sm-0">{l s='Delete attribute from combinations' mod='masseditproduct'}:</label>
        </div>
        <div class="col-xs-12 form-group">
            <div class="row_attributes">
                {if is_array($attribute_groups) && count($attribute_groups)}
                    <div class="clearfix">
                        <div class="form-group float-left w-xs-100">
                            <select id="select_tab2" class="fixed-width-xl margin-right custom-select select_attribute w-xs-100" name="attribute_group">
                                <option class="first" value="">--</option>
                            </select>
                        </div>

                        <div class="form-group float-left w-xs-100">
                            <select name="attributes" class="fixed-width-xl margin-right custom-select attr-values w-xs-100">
                            </select>
                        </div>

                        <div class="form-group float-left w-xs-100 margin-right">
                            <button class="btn btn-success addAttribute w-xs-100">
                                <i class="icon-plus"></i>
                                {l s='Add attribute' mod='masseditproduct'}
                            </button>
                        </div>

                        <input type="hidden" name="selected_attributes" value="">
                    </div>

                {/if}

                <div class="">
                    <span class="md-checkbox">
                        <label class="">
                            <input type="checkbox" name="force_delete_attribute" value="1">
                            <i class="md-checkbox-control"></i>
                            {l s='Force delete attribute from combinations' mod='masseditproduct'}
                        </label>
                    </span>
                </div>

            </div>
        </div>
    </div>
    <div class="row enabled_option_stage form-group">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="add_attribute" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-xs-12 form-group">
            <div class="row_attributes">
                <label class="control-label margin-right float-left">{l s='Add attribute in combinations' mod='masseditproduct'}:</label>
                {if is_array($attribute_groups) && count($attribute_groups)}
                    <div class="form-group float-left w-xs-100">
                        <select id="select_tab3" class="fixed-width-xl margin-right custom-select select_attribute w-xs-100" name="attribute_group">
                            <option class="first" value="">--</option>
                        </select>
                    </div>
                    <div class="form-group float-left w-xs-100">
                        <select name="attributes" class="fixed-width-xl margin-right custom-select attr-values w-xs-100">
                        </select>
                    </div>
                    <div class="float-left w-xs-100">
                        <button class="btn btn-success addAttribute w-xs-100">
                            <i class="icon-plus"></i>
                            {l s='Add attribute' mod='masseditproduct'}
                        </button>
                    </div>
                    <input type="hidden" name="selected_attributes" value="">
                {/if}
            </div>
        </div>
    </div>
    <script>
        $('#select_tab, #select_tab2, #select_tab3').live('click', function () {
            var ids_attribute = $('this option:selected').val();
            var cur = $(this);
            if (ids_attribute == 0) {
            var ids_attribute = $('this option:first').val();
            }
            $.ajax({
                url: document.location.href.replace('#' + document.location.hash, ''),
                type: 'POST',
                dataType: 'json',
                data: {
                    ajax: true,
                    action: 'attribute_group',
                    ids_attribute: ids_attribute
                },
                success: function (json) {
                    $(cur).find('.first').replaceWith(json.return.html);
                    $('this option[value= ' + ids_attribute +']').prop('selected', true);
                }
            });
        });
        $('#select_tab, #select_tab2, #select_tab3').live('change', function () {
            var id_attribute = $(this).val();
            var cur = $(this);
            $.ajax({
                url: document.location.href.replace('#' + document.location.hash, ''),
                type: 'POST',
                dataType: 'json',
                data: {
                    ajax: true,
                    action: 'render_attribute_values',
                    tabs: 'yes',
                    ids_attribute: id_attribute,
                },
                success: function (json) {
                    var element = $(cur).next('select');
                    console.log(json.return[0].html);
                    $(element).html(json.return[0].html);
                }
            });
        })
    </script>
{/block}
