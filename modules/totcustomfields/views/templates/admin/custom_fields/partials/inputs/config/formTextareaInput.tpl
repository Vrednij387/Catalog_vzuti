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

<ps-select name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][format]"
           label="{l s='Format' mod='totcustomfields'}"
           required-input="true"
           hint="{l s='Choose which formats should be accepted by this input.' mod='totcustomfields'}"
           chosen='true'>
    {foreach from=$data.formats item=format key=id_format}
        <option value="{$id_format|escape:'htmlall':'UTF-8'}"
                {if isset($data.currentConf) && $data.currentConf.format == $id_format}selected{/if}>{$format.name|escape:'htmlall':'UTF-8'}</option>
    {/foreach}
</ps-select>

<ps-textarea-lang name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][default_value]"
                  label="{l s='Default value' mod='totcustomfields'}"
                  col-lg="10"
                  active-lang="{$id_current_lang|escape:'htmlall':'UTF-8'}">
    {foreach from=$languages item=lang}
        <div data-is="ps-textarea-lang-value"
             iso-lang="{$lang.iso_code|escape:'htmlall':'UTF-8'}"
             id-lang="{$lang.id_lang|escape:'htmlall':'UTF-8'}"
             lang-name="{$lang.name|escape:'htmlall':'UTF-8'}"
        >{if isset($data.currentConf) && isset($data.currentConf.default_value[$lang.id_lang])}{$data.currentConf.default_value[$lang.id_lang]|escape:'htmlall':'UTF-8'}{/if}</div>
    {/foreach}
</ps-textarea-lang>


<ps-input-text name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][maxlength]"
               label="{l s='Max. characters' mod='totcustomfields'}"
               hint="{l s='The maximum number of characters for the value.' mod='totcustomfields'}"
               value="{if isset($data.currentConf)}{$data.currentConf.maxlength|escape:'htmlall':'UTF-8'}{else}600{/if}"
               fixed-width="lg"></ps-input-text>

<ps-switch name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][translatable]"
           label="{l s='Translatable' mod='totcustomfields'}"
           yes="{l s='Yes' mod='totcustomfields'}" no="{l s='No' mod='totcustomfields'}"
           hint="{l s='Is this input multilang ?' mod='totcustomfields'}"
           active="{if isset($data.currentConf) && !$data.currentConf.translatable}false{else}true{/if}"></ps-switch>