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
    <div class="row">
        <div class="col-sm-12">

            <div class="float-left">
                <label class="control-label required margin-right float-left pt-0 pt-sm-1">{l s='Filename' mod='masseditproduct'}:</label>
                {renderTemplate file="admin/mass_edit_product/helpers/form/input_text_lang.tpl" v=['input_name'=>'filename', 'languages'=>$languages, 'required'=>true, 'maxlength'=>32]}
            </div>

            <div class="float-left form-group">
                <label class="control-label margin-right float-left pt-0 pt-sm-1">{l s='Description' mod='masseditproduct'}:</label>
                {renderTemplate file="admin/mass_edit_product/helpers/form/input_text_lang.tpl" v=['input_name'=>'description', 'languages'=>$languages]}

            </div>

            <div class="float-left form-group">
                <span data-attachment-file class="btn btn-default wrap_file_input">
                    <span class="label_input">
                        {l s='Select file' mod='masseditproduct'}
                    </span>
                    <input type="file">
                </span>
            </div>

        </div>
        <div class="col-sm-12">
            <div class="select_attachments">
                <div class="search_row row">
                    <div class="left_column col-sm-6 form-group">
                        <label class="control-label pt-0">{l s='Select from list' mod='masseditproduct'}:</label>
                        <select class="no_selected_product" multiple>
                            {if is_array($attachments) && count($attachments)}
                                {foreach from=$attachments item=attachment}
                                    <option value="{$attachment.id_attachment|escape:'quotes':'UTF-8'}">{$attachment.name|escape:'quotes':'UTF-8'}</option>
                                {/foreach}
                            {/if}
                        </select>
                        <input class="add_select_product btn btn-default" value="{l s='Add in select products' mod='masseditproduct'}" type="button"/>
                    </div>
                    <div class="right_column col-sm-6 form-group">
                        <label class="control-label pt-0">{l s='Selected' mod='masseditproduct'}:</label>
                        <select name="attachments[]" class="selected_product" multiple></select>
                        <input class="remove_select_product btn btn-default" value="{l s='Remove from select products' mod='masseditproduct'}" type="button"/>
                    </div>
                </div>
            </div>
            <script>
                $(function () {
                    $('.select_attachments').selectProducts({
                        path_ajax: document.location.href.replace(document.location.hash, ''),
                        search: false
                    });
                });
            </script>
        </div>
    </div>
    <div class="row delete-old-attachment">
        <div class="col-sm-12 mt-1">
            <span class="md-checkbox">
                <label>
                    <input type="checkbox" name="old_attachment">
                    <i class="md-checkbox-control"></i>
                    {l s='Delete old attachment' mod='masseditproduct'}
                </label>
            </span>
        </div>
    </div>
{/block}
