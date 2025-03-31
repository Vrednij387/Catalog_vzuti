{**
 * Copyright since 2022 totcustomfields
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tech@202-ecommerce.com so we can send you a copy immediately.
 *
 * @author    202 ecommerce <tech@202-ecommerce.com>
 * @copyright 2022 totcustomfields
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 *}

<form id="totNewInputForm" class="form-horizontal" action="{$module.config_url|escape:'htmlall':'UTF-8'}" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id_input" value="{$newInputData.id_input|escape:'htmlall':'UTF-8'}"/>

    <ps-input-text name="name" label="{l s='Name' mod='totcustomfields'}" size="255" required-input="true"
                   hint="{l s='The name (label) of this input.' mod='totcustomfields'}"
                   value="{if isset($newInputData.currentConf)}{$newInputData.currentConf.name|escape:'htmlall':'UTF-8'}{/if}"
                   fixed-width="lg"></ps-input-text>

    <ps-input-text name="code" label="{l s='Technical name' mod='totcustomfields'}" size="255"
                   required-input="true"
                   hint="{l s='The technical name of this input must be unique among your custom fields. This name should not contains space and hyphen "-"' mod='totcustomfields'}"
                   value="{if isset($newInputData.currentConf)}{$newInputData.currentConf.code|escape:'htmlall':'UTF-8'}{/if}"
                   {if !empty($newInputData.currentConf.unremovable)}readonly-input="true"{/if}
                   fixed-width="lg"></ps-input-text>

    <ps-switch name="required" label="{l s='Required' mod='totcustomfields'}" yes="{l s='Yes' mod='totcustomfields'}"
               no="{l s='No' mod='totcustomfields'}"
               hint="{l s='Choose if filling this input should be required or not.' mod='totcustomfields'}"
               active="{if isset($newInputData.currentConf) && !$newInputData.currentConf.required}false{else}true{/if}"></ps-switch>

    <ps-textarea-lang name="instructions" label="{l s='Instructions' mod='totcustomfields'}"
                      hint="{l s='Instructions for the user about this input.' mod='totcustomfields'}"
                      col-lg="10"
                      active-lang="{$id_current_lang|escape:'htmlall':'UTF-8'}">
        {foreach from=$languages item=lang}
            <div data-is="ps-textarea-lang-value"
                 iso-lang="{$lang.iso_code|escape:'htmlall':'UTF-8'}"
                 id-lang="{$lang.id_lang|escape:'htmlall':'UTF-8'}"
                 lang-name="{$lang.name|escape:'htmlall':'UTF-8'}"
            >{if isset($newInputData.currentConf) && isset($newInputData.currentConf.instructions[$lang.id_lang])}{$newInputData.currentConf.instructions[$lang.id_lang]|escape:'htmlall':'UTF-8'}{/if}</div>
        {/foreach}
    </ps-textarea-lang>

    <ps-select name="type" label="{l s='Type' mod='totcustomfields'}" required-input="true"
               hint="{l s='Choose the type of this input.' mod='totcustomfields'}" chosen='true'>
        <option value=""></option>
        {foreach from=$newInputData.inputTypes item=type}
            <option value="{$type.code_input_type|escape:'htmlall':'UTF-8'}"
                    {if isset($newInputData.currentConf) && $type.code_input_type == $newInputData.currentConf.code_input_type}selected{/if}>
                {$type.name|escape:'htmlall':'UTF-8'}
            </option>
        {/foreach}
    </ps-select>

    {* The following fields depend of the input type *}

    {foreach from=$newInputData.inputTypes item=type}
        <div class="newInputSubform type_{$type.code_input_type|escape:'htmlall':'UTF-8'}" style="display: none">
            {include file=$type.template data=$type.formData code_type=$type.code_input_type}
        </div>
    {/foreach}

    {*************************************************}

    <ps-switch name="active" label="{l s='Active' mod='totcustomfields'}" yes="{l s='Yes' mod='totcustomfields'}"
               no="{l s='No' mod='totcustomfields'}"
               hint="{l s='Choose if this input is active (visible) or not.' mod='totcustomfields'}"
               active="{if isset($newInputData.currentConf) && !$newInputData.currentConf.active}false{else}true{/if}"></ps-switch>

    {* Shop association tree *}
    {if $newInputData.shop_association_tree}
        <ps-form-group label="{l s='Associated shops' mod='totcustomfields'}">
            <raw content="{$newInputData.shop_association_tree|replace:'{':'\{'|replace:'}':'\}'|escape:'htmlall':'UTF-8'}"></raw>
        </ps-form-group>
    {/if}

    <ps-select name="object" label="{l s='Back Office location' mod='totcustomfields'}" required-input="true"
               hint="{l s='Choose the location (page) of this input.' mod='totcustomfields'}" chosen='true'>
        <option value=""></option>
        {foreach from=$objects item=object}
            <option value="{$object.code|escape:'htmlall':'UTF-8'}"
                    {if isset($newInputData.currentConf) && $object.code == $newInputData.currentConf.code_object}selected{/if}>
                {$object.location_name|escape:'htmlall':'UTF-8'}
            </option>
        {/foreach}
    </ps-select>

    <div id="totcustomfields-display-admin-hooks-form" class="clearfix"></div>

    <ps-select name="display" label="{l s='Front Office display method' mod='totcustomfields'}" required-input="true"
               hint="{l s='Choose the display method of this input.' mod='totcustomfields'}" chosen='true'>
        <option value=""></option>
        {foreach from=$displays item=display}
            <option value="{$display.code|escape:'htmlall':'UTF-8'}"
                    {if isset($newInputData.currentConf) && $display.code == $newInputData.currentConf.code_display}selected{/if}>
                {$display.name|escape:'htmlall':'UTF-8'}
            </option>
        {/foreach}
    </ps-select>

    {* This will contain the display subform *}
    <div id="totCustomFields-display-form" class="clearfix form-group"></div>

    <ps-panel-footer>

        <ps-panel-footer-submit title="{l s='Save' mod='totcustomfields'}" icon="process-icon-save" direction="right"
                                name="saveInput">
        </ps-panel-footer-submit>

        {if $newInputData.id_input}
            <a
                    href="{$module.config_url|escape:'htmlall':'UTF-8'}"
                    class="btn btn-default pull-right">
                <i class="process-icon-cancel"></i> {l s='Cancel' mod='totcustomfields'}
            </a>
        {/if}
    </ps-panel-footer>

</form>
