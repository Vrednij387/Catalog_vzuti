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

<ps-input-file-deletable name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][default_value]"
               label="{l s='Default value' mod='totcustomfields'}"
                         select-title="{l s='Select a default image' mod='totcustomfields'}"
                         delete-title="{l s='Delete current default image' mod='totcustomfields'}"
               file-type="gif, jpg, jpeg, png"
               id="totNewInputSubform-{$code_type|escape:'htmlall':'UTF-8'}_default-value"
>
    {if isset($data.currentConf) && $data.currentConf.default_value != ''}
        <img src="{$data.currentConf.images_path|escape:'htmlall':'UTF-8'}{$data.currentConf.default_value|escape:'htmlall':'UTF-8'}" class="img_default" />
    {/if}
</ps-input-file-deletable>

<ps-select name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][size_type]"
           label="{l s='Front office size' mod='totcustomfields'}"
           required-input="true"
           hint="{l s='Choosing "Standard" will use Prestashop\'s default image size; "Custom" will allow you to specify your own size.' mod='totcustomfields'}"
           chosen='true'>
    {foreach from=$data.size_types item=size_type key=id_size_type}
        <option value="{$id_size_type|escape:'htmlall':'UTF-8'}"
                {if isset($data.currentConf) && $data.currentConf.size_type == $id_size_type}selected{/if}>{$size_type.name|escape:'htmlall':'UTF-8'}</option>
    {/foreach}
</ps-select>

<div id="totNewInputSubform-{$code_type|escape:'htmlall':'UTF-8'}_sizetype-1">

    <ps-select name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][standard_size]"
               label="{l s='Size' mod='totcustomfields'}"
               required-input="true"
               hint="{l s='Choose a value among Prestashop\'s standard sizes (Width x Height)' mod='totcustomfields'}"
               chosen='true'>
        {foreach from=$data.standard_sizes item=standard_size key=id_size}
            <option value="{$id_size|escape:'htmlall':'UTF-8'}"
                    {if isset($data.currentConf) && $data.currentConf.standard_size == $id_size}selected{/if}>{$standard_size|escape:'htmlall':'UTF-8'}</option>
        {/foreach}
    </ps-select>
</div>

<div id="totNewInputSubform-{$code_type|escape:'htmlall':'UTF-8'}_sizetype-2" style="display: none;">

    <ps-input-text name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][size_height]"
                   label="{l s='Max. height' mod='totcustomfields'}"
                   hint="{l s='The max. height of this image on front office, in pixels.' mod='totcustomfields'}"
                   value="{if isset($data.currentConf)}{$data.currentConf.size_height|escape:'htmlall':'UTF-8'}{/if}"
                   suffix="px"
                   maxlength="4"
                   fixed-width="lg"></ps-input-text>


    <ps-input-text name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][size_width]"
                   label="{l s='Max. width' mod='totcustomfields'}"
                   hint="{l s='The max. width of this image on front office, in pixels.' mod='totcustomfields'}"
                   value="{if isset($data.currentConf)}{$data.currentConf.size_width|escape:'htmlall':'UTF-8'}{/if}"
                   suffix="px"
                   maxlength="4"
                   fixed-width="lg"></ps-input-text>
</div>

<ps-switch name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][translatable]"
           label="{l s='Translatable' mod='totcustomfields'}"
           yes="{l s='Yes' mod='totcustomfields'}" no="{l s='No' mod='totcustomfields'}"
           hint="{l s='Is this input multilang ?' mod='totcustomfields'}"
           active="{if isset($data.currentConf) && !$data.currentConf.translatable}false{else}true{/if}"></ps-switch>