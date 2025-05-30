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
    <div class="row enabled_option_stage_static">
        <div class="col-sm-12 clearfix">
            <label class="control-label select-lang margin-right float-left pt-0 pt-sm-1">{l s='Select language' mod='masseditproduct'}:</label>
            <span class="btn-group btn-group-radio">
                <label for="all_language">
                    <input type="radio" checked name="language" value="0" id="all_language"/>
                    <span class="">{l s='For all' mod='masseditproduct'}</span>
                </label>
                {foreach from=$languages item=language}
                    <label for="{$language.id_lang|intval}_language">
                        <input type="radio" name="language" value="{$language.id_lang|intval}" id="{$language.id_lang|intval}_language"/>
                        <span class="">{$language.name|escape:'quotes':'UTF-8'}</span>
                    </label>
                {/foreach}
            </span>
        </div>
    </div>
    <div class="row enabled_option_stage">

        <div class="col-sm-12 form-group">
            <label class="control-label select-lang margin-right float-left pt-0 pt-sm-1">{l s='Location' mod='masseditproduct'}:</label>
            <span class="btn-group btn-group-radio">
                <label for="before_location_name">
                    <input type="radio" checked name="location_name" value="1" id="before_location_name"/>
                    <span class="">{l s='Before' mod='masseditproduct'}</span>
                </label>
                <label for="after_location_name">
                    <input type="radio" name="location_name" value="2" id="after_location_name"/>
                    <span class="">{l s='After' mod='masseditproduct'}</span>
                </label>
                <label for="instead_location_name">
                    <input type="radio" name="location_name" value="0" id="instead_location_name"/>
                    <span class="">{l s='Instead' mod='masseditproduct'}</span>
                </label>
            </span>
        </div>

        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="product_name" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>

        <div class="col-sm-12">
            <div class="row">
                <div class="col-xs-12">
                    <label class="control-label label-meta margin-right pt-0 pt-sm-1">{l s='Name' mod='masseditproduct'}:</label>
                    <input class="form-control fixed-width-xxxxl" name="name">
                </div>
            </div>
            {renderTemplate file="admin/mass_edit_product/helpers/form/row_variables.tpl" v=['name'=>'name']}
        </div>
    </div>
    {renderTemplate file="admin/mass_edit_product/helpers/form/copy_row.tpl" v=['field'=>'description_short']}
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="description_short" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-xs-12 clearfix">
            <label class="control-label desc-label pt-0">{l s='Short description' mod='masseditproduct'}:</label>
        </div>
        <div class="col-xs-12 fixed-width-xxxxl">
            <label class="control-label select-lang margin-right float-left">{l s='Location' mod='masseditproduct'}:</label>
            <span class="btn-group btn-group-radio">
                <label for="before_location_description_short">
                    <input type="radio" checked name="location_description_short" value="1" id="before_location_description_short"/>
                    <span class="">{l s='Before' mod='masseditproduct'}</span>
                </label>
                <label for="after_location_description_short">
                    <input type="radio" name="location_description_short" value="2" id="after_location_description_short"/>
                    <span class="">{l s='After' mod='masseditproduct'}</span>
                </label>
                <label for="instead_location_description_short">
                    <input type="radio" name="location_description_short" value="0" id="instead_location_description_short"/>
                    <span class="">{l s='Instead' mod='masseditproduct'}</span>
                </label>
                 <label for="replace_location_description_short">
                    <input type="radio" name="location_description_short" value="3" id="replace_location_description_short"/>
                    <span class="">{l s='Replace' mod='masseditproduct'}</span>
                </label>
            </span>
            <div id="replace" style="display: none">
                <p>{l s="Search?"  mod='masseditproduct'}</p>
            <textarea class="editor_html" name="replace_description_short"></textarea>
                <p>{l s="What to replace?"  mod='masseditproduct'}</p>
            </div>
            <textarea class="editor_html" name="description_short"></textarea>
        </div>
        {*{renderTemplate file="admin/mass_edit_product/helpers/form/row_variables_description.tpl" v=['name'=>'description_short']}*}
        <div class="col-lg-12">
            {renderTemplate file="admin/mass_edit_product/helpers/form/row_variables.tpl" v=['name'=>'description_short']}
        </div>
    </div>
    {renderTemplate file="admin/mass_edit_product/helpers/form/copy_row.tpl" v=['field'=>'description']}
    <div class="row enabled_option_stage form-group">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="description" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <label class="control-label col-lg-12 desc-label pt-0 pt-sm-1">{l s='Description' mod='masseditproduct'}:</label>
        <div class="col-lg-12 fixed-width-xxxxl">
            <label class="control-label select-lang margin-right float-left">{l s='Location' mod='masseditproduct'}:</label>
            <span class="btn-group btn-group-radio">
                <label for="before_location_description">
                    <input type="radio" checked name="location_description" value="1" id="before_location_description"/>
                    <span class="">{l s='Before' mod='masseditproduct'}</span>
                </label>
                <label for="after_location_description">
                    <input type="radio" name="location_description" value="2" id="after_location_description"/>
                    <span class="">{l s='After' mod='masseditproduct'}</span>
                </label>
                <label for="instead_location_description">
                    <input type="radio" name="location_description" value="0" id="instead_location_description"/>
                    <span class="">{l s='Instead' mod='masseditproduct'}</span>
                </label>
          <label for="replace_location_description">
                    <input type="radio" name="location_description" value="3" id="replace_location_description"/>
                    <span class="">{l s='Replace' mod='masseditproduct'}</span>
                </label>
            </span>
            <div id="replace_desc" style="display: none">
                <p>{l s="Search?"  mod='masseditproduct'}</p>
                <textarea class="editor_html" name="replace_description"></textarea>
                <p>{l s="What to replace?"  mod='masseditproduct'}</p>
            </div>
            <textarea class="editor_html" name="description"></textarea>
        </div>
        {*{renderTemplate file="admin/mass_edit_product/helpers/form/row_variables_description.tpl" v=['name'=>'description']}*}
        <div class="col-lg-12">
            {renderTemplate file="admin/mass_edit_product/helpers/form/row_variables.tpl" v=['name'=>'description']}
        </div>
    </div>
  <script>
      $('._row_copy').rowCopy();
      $('[name="location_description_short"]').on('click', function() {
         if ($(this).val() == 3) {
             $('#replace').show();
         } else {
             $('#replace').hide();
         }
      });
      $('[name="location_description"]').on('click', function() {
          if ($(this).val() == 3) {
              $('#replace_desc').show();
          } else {
              $('#replace_desc').hide();
          }
      });
    </script>
{/block}