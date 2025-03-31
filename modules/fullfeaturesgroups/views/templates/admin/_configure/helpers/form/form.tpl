{**
* 2016 TerraNet
*
* NOTICE OF LICENSE
*
* @author    TerraNet
* @copyright 2016 TerraNet
* @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{extends file="helpers/form/form.tpl"}

{block name="script"}
    $(document).ready(function(){
    $('#menuOrderUp').click(function(e){
    e.preventDefault();
    move(true);
    });
    $('#menuOrderDown').click(function(e){
    e.preventDefault();
    move();
    });
    $("#items").closest('form').on('submit', function(e) {
    $("#items option").prop('selected', true);
    });
    $("#addItem").click(add);
    $(".availableItems").dblclick(add);
    $("#removeItem").click(remove);
    $("#items").dblclick(remove);
    function add()
    {
    $(".availableItems option:selected").each(function(i){
    var val = $(this).val();
    var text = $(this).text();
    text = text.replace(/(^\s*)|(\s*$)/gi,"");
    var def = '';
    if ($(this).hasClass('def')) {
    def = 'def';
    } else {
    if ($('.mod .availableItems option[value='+val+']').length) {
    def = 'def';
    }
    }
    $("#items").append('<option value="'+val+'" class="'+def+'" selected="selected">'+text+'</option>');
    $('.availableItems option[value='+val+']').remove();
    $(this).remove();
    });
    serialize();
    return false;
    }
    function remove()
    {
    $("#items option:selected").each(function(i){
    var val = $(this).val();
    var text = $(this).text();
    if ($(this).hasClass('def')) {
    $(".availableItems").append('<option class="def" value="'+val+'">'+text+'</option>');
    } else {
    $(".all .availableItems").append('<option value="'+val+'">'+text+'</option>');
    }
    $(this).remove();
    });
    serialize();
    return false;
    }
    function serialize()
    {
    var options = "";
    $("#items option").each(function(i){
    options += $(this).val()+",";
    });
    $("#itemsInput").val(options.substr(0, options.length - 1));
    }
    function move(up)
    {
    var tomove = $('#items option:selected');
    if (tomove.length >1)
    {
    alert('{l s='Please select just one item' mod='fullfeaturesgroups'}');
    return false;
    }
    if (up)
    tomove.prev().insertAfter(tomove);
    else
    tomove.next().insertBefore(tomove);
    serialize();
    return false;
    }
    });
    function showHideBlock() {
    $('.switch_revert').each(function(){
    if ($(this).find('input.ch:radio:checked').val() == '1') {
    $('.mod').css('display','none');
    $('.all').css('display','block');
    }else{
    $('.all').css('display','none');
    $('.mod').css('display','block');
    }
    });
    }
    $(document).ready(function(){
    showHideBlock();
    $('.switch_revert').each(function(){
    $(this).find('input.ch').change(function() {
    if($(this).val() == 1) {
    $('.mod').css('display','none');
    $('.all').css('display','block');
    }else{
    $('.all').css('display','none');
    $('.mod').css('display','block');
    }
    });
    });
    $('#items option').each(function(){
        $('.availableItems option[value='+$(this).val()+']').remove();
    });
    });
{/block}
{block name="input"}
    {if $input.type == 'link_choice'}
        <div class="row">
            <div class="col-lg-1">
                <h4 style="margin-top:5px;">{l s='Change position' mod='fullfeaturesgroups'}</h4>
                <a href="#" id="menuOrderUp" class="btn btn-default" style="font-size:20px;display:block;"><i
                            class="icon-chevron-up"></i></a><br/>
                <a href="#" id="menuOrderDown" class="btn btn-default" style="font-size:20px;display:block;"><i
                            class="icon-chevron-down"></i></a><br/>
            </div>
            <div class="col-lg-4">
                <h4 style="margin-top:5px;">{l s='Selected items' mod='fullfeaturesgroups'}</h4>
                <select multiple="multiple" name="items[]" id="items" style="width: 300px; height: 160px;">
                    {foreach from=$selected_links item=selected_link}
                        <option selected="selected"  value="{$selected_link['value']|intval}">{$selected_link['name']|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
            </div>
            <div class="col-lg-4">
                <h4 style="margin-top:5px;">{l s='Available items' mod='fullfeaturesgroups'}</h4>

                <div style="display: none;" class="all">
                    <select multiple="multiple" class="availableItems" style="width: 300px; height: 160px;">
                        {if isset($choices_all) && is_array($choices_all) && count($choices_all) > 0}
                            {foreach from=$choices_all item=choices_all_link}
                                <option class="{if ($choices_all_link['def'])}def{/if}" value="{$choices_all_link['value']|intval}">{$choices_all_link['name']|escape:'htmlall':'UTF-8'}</option>
                            {/foreach}
                        {/if}
                    </select>
                </div>
                <div style="display: none;" class="mod">
                    <select multiple="multiple" class="availableItems" style="width: 300px; height: 160px;">
                        {foreach from=$choices item=choices_link}
                            <option class="{if ($choices_link['def'])}def{/if}" value="{$choices_link['value']|intval}">{$choices_link['name']|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-4"><a href="#" id="removeItem" class="btn btn-default"><i
                            class="icon-arrow-right"></i> {l s='Remove' mod='fullfeaturesgroups'}</a></div>
            <div class="col-lg-4"><a href="#" id="addItem" class="btn btn-default"><i
                            class="icon-arrow-left"></i> {l s='Add' mod='fullfeaturesgroups'}</a></div>
        </div>
        <br/><br/>
        <div class="row">
            <div  class="col-lg-9">
                <div style="text-align: right; display: inline-block; float: right;">
                    <span class="switch prestashop-switch switch_revert fixed-width-lg">
                        <input type="radio" class="ch" name="all_list" id="all_list_on" value="1" {if ($fields_value.all_list == 1)}checked="checked"{/if}>
                        <label for="all_list_on">{l s='Yes' mod='fullfeaturesgroups'}</label>
                        <input type="radio" class="ch" name="all_list" id="all_list_off" value="" {if ($fields_value.all_list != 1)}checked="checked"{/if}>
                        <label for="all_list_off">{l s='No' mod='fullfeaturesgroups'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                    <p class="help-block">
                        {l s='Show all available items' mod='fullfeaturesgroups'}
                    </p>
                </div>
            </div>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
