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
                <input checked type="checkbox" name="disabled[]" value="delete_customization_fields" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 form-group">
            <span class="md-checkbox">
                <label>
                    <input type="checkbox" name="delete_customization_fields">
                    <i class="md-checkbox-control"></i>
                    {l s='Delete old customization fields' mod='masseditproduct'}
                </label>
            </span>
        </div>
    </div>
    <div class="row enabled_option_stage">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="customization_file_labels" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 form-group">
            <div id="customization_fields_0" class="customization_file_labels clearfix">
                {renderTemplate file="admin/mass_edit_product/helpers/form/customization_field.tpl" v=['type'=>0, 'counter'=>0, 'languages'=>$languages]}
            </div>
            <input type="button" class="btn btn-default addFileLabel" value="{l s='Add file label' mod='masseditproduct'}">
        </div>
    </div>
    <div class="row enabled_option_stage form-group">
        <span class="md-checkbox disable_option_wrap">
            <label>
                <input checked type="checkbox" name="disabled[]" value="customization_text_labels" class="disable_option">
                <i class="md-checkbox-control"></i>
            </label>
        </span>
        <div class="col-sm-12 form-group">
            <div id="customization_fields_1" class="customization_text_labels clearfix">
                {renderTemplate file="admin/mass_edit_product/helpers/form/customization_field.tpl" v=['type'=>1, 'counter'=>0, 'languages'=>$languages]}
            </div>
            <input type="button" class="btn btn-default addTextLabel" value="{l s='Add text label' mod='masseditproduct'}">
        </div>
    </div>
{/block}