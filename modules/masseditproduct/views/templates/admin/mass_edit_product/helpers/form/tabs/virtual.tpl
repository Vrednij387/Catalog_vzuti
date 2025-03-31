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
    <!-- virtual tab -->
    <div class="row button_disable enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="action_for_virtual" id="type_product" class="disable_option check_box_disable">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12">

            <label class="control-label margin-right float-left pt-0 pt-sm-1">{l s='Product type' mod='masseditproduct'}:</label>

            <div class="btn-group btn-group-radio margin-right float-left action_for_virtual_wrap form-group">
                <label for="change_for_sp_standart" id="off_menu">
                    <input type="radio" checked name="action_for_virtual" value="0" id="change_for_sp_standart"/>
                    <span class="">{l s='Standard' mod='masseditproduct'}</span>
                </label>
                {*<label for="change_for_sp_set" id="off_menus">*}
                    {*<input type="radio" name="action_for_virtual" value="1" id="change_for_sp_set"/>*}
                    {*<span class="">{l s='Set of goods' mod='masseditproduct'}</span>*}
                {*</label>*}
                <label for="change_for_sp_virtual" id="trigger">
                    <input type="radio" name="action_for_virtual" value="2" id="change_for_sp_virtual"/>
                    <span class="">{l s='virtual goods' mod='masseditproduct'}</span>
                </label>
            </div>
        </div>
    </div>
    <div class="row button_disable enabled_option_stage"  style="margin-bottom: 20px;">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked="" type="checkbox" name="disabled[]" value="action_virtual" id="block-file"
                       class="disable_option check_box_disable check_block">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 clearfix">
            <div class="float-left">
                <label class="control-label margin-right float-left">
                    {l s='The item has a related item' mod='masseditproduct'}:
                </label>
                <span class="ps-switch prestashop-switch fixed-width-sm form-group" id="action_switch">

                {foreach [0,1] as $value}
                    <input type="radio" name="action_virtual" value="{$value|escape:'quotes':'UTF-8'}" class="action_virtual"
                            {if $value == 1} id="action_yes" {else} id="action_no" {/if}
                            {if $value == 0} checked="checked" {/if} />
                    <label {if $value == 1} for="action_yes" {else} for="action_no" {/if}>
                        {if $value == 1}{l s='Yes' mod='masseditproduct'}{else}{l s='No' mod='masseditproduct'}{/if}
                    </label>
                {/foreach}
                <a class="slide-button"></a>
            </span>
            </div>
        </div>
    </div>
    <div class="row  button_disable block-file" style="display:none">
        <div class="col-sm-12">
            <div class="form-group button_disable">
                <label class="control-label margin-right float-left">{l s='File' mod='masseditproduct'}:</label>
                <div class="custom-file fixed-width-xl float-left">
                    <input type="file" id="virtual_product_file" name="virtual_product_file" class="custom-file-input">
                    <label class="custom-file-label" for="virtual_product_file">
                        {l s='Choose file(s)' mod='masseditproduct'}
                    </label>
                </div>
            </div>
            <div class="form-group button_disable">
                <label class="control-label margin-right">{l s='Name file' mod='masseditproduct'}:</label>
                <input type="text" id="name_file" name="name_file" required="required"
                       class="form-control fixed-width-lg" value="">
            </div>
            <div class="form-group button_disable">
                <label class="control-label margin-right">{l s='Number of downloads allowed' mod='masseditproduct'}:</label>
                <input type="text" id="number_downloads" name="number_downloads" required="required"
                       class="form-control fixed-width-lg" value="">
            </div>
            <div class="form-group button_disable">
                <label class="control-label margin-right">{l s='Amount of days' mod='masseditproduct'}:</label>
                <input type="text" id="amount of days" name="amount_of_days" required="required"
                       class="form-control fixed-width-lg" value="">
            </div>
            <div class="form-group button_disable">
                <label class="control-label margin-right float-left">
                    {l s='Expiration date' mod='masseditproduct'}:
                </label>
                <div class="float-left fixed-width-xl input-group">
                    <input type="text" class="form-control datepicker" id="expiration_date" name="expiration_date"
                           placeholder="YYYY-MM-DD" value="0000-00-00">
                    <span class="input-group-addon"><i class="icon-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#datepicker').datepicker();

            $(document).on('click','#virtual #type_product', function () {
                if($(this).attr("checked") != 'checked') {
                    // $('#virtual .block-file').hide();
                    // $('#virtual #action_no').attr('checked', true);
                } else {
                    $('#virtual #block-file').attr('checked', true);
                    $('#virtual #block-file').closest('.button_disable').addClass('disabled_option_stage');

                    $('#virtual #action_no').attr('checked', true);
                    $('#virtual .block-file').hide();
                }
            });
            $(document).on('click','#virtual #block-file', function () {
                if($(this).attr("checked") != 'checked') {
                    $('#virtual #type_product').attr('checked', false);
                    $('#virtual #type_product').closest('.button_disable').removeClass('disabled_option_stage');

                    $('#virtual #change_for_sp_virtual').prop('checked', true);
                    // $('#virtual #type_product').closest('.button_disable').addClass('disabled_option_stage');
                } else {
                    $('#virtual #change_for_sp_standart').prop('checked', true);

                    $('#virtual #action_no').attr('checked', true);
                    $('#virtual .block-file').hide();
                }
            });

            $(document).on('click','#virtual #change_for_sp_virtual', function () {
                if($(this).attr("checked")) {
                    $('body').find('#virtual').find('#block-file').attr('checked', false);
                    $('body').find('#virtual').find('#block-file').closest('.button_disable').removeClass('disabled_option_stage');
                }
            });

            $(document).on('click','#virtual #change_for_sp_standart', function () {
                if($(this).attr("checked")) {
                    $('body').find('#virtual').find('#block-file').attr('checked', true);
                    $('body').find('#virtual').find('#block-file').closest('.button_disable').addClass('disabled_option_stage');

                    $('#virtual #action_no').attr('checked', true);
                    $('#virtual .block-file').hide();
                }
            });

            $(document).on('click','#virtual #change_for_sp_set', function () {
                if($(this).attr("checked")) {
                    $('body').find('#virtual').find('#block-file').attr('checked', true);
                    $('body').find('#virtual').find('#block-file').closest('.button_disable').addClass('disabled_option_stage');

                    $('#virtual #action_no').attr('checked', true);
                    $('#virtual .block-file').hide();
                }
            });

            $('#action_switch').on('click','input:checked', function () {
                if (this.value == 0) {
                    $('.block-file').hide();
                } else {
                    $('.block-file').show();
                }
            });

            $('#virtual_product_file').change(function(e) {
                if ($(this)[0].files !== undefined) {
                    var files = $(this)[0].files;
                    var name  = '';

                    $.each(files, function(index, value) {
                        name += value.name + ', ';
                    });
                    $('#name_file').val(name.slice(0, -2));
                } else {
                    // Internet Explorer 9 Compatibility
                    var name = $(this).val().split(/[\\/]/);
                    $('#name_file').val(name[name.length - 1]);
                }
            });
        });
    </script>
    <!-- end virtual tab -->
{/block}
