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

<ps-input-text-lang name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][add-edit-option-text]"
                    class="add-edit-checkbox-option-block"
                    label="{l s='Add/Edit option' mod='totcustomfields'}"
                    hint="{l s='Add or edit existing option' mod='totcustomfields'}"
                    size="255" col-lg="5" active-lang="1">

    {foreach from=$languages item=lang}
      <div data-is="ps-input-text-lang-value"
           class="checkbox-lang-wrapper"
           iso-lang="{$lang.iso_code|escape:'htmlall':'UTF-8'}"
           id-lang="{$lang.id_lang|escape:'htmlall':'UTF-8'}"
           lang-name="{$lang.name|escape:'htmlall':'UTF-8'}"
      ></div>
    {/foreach}
</ps-input-text-lang>

<div class="form-group">
  <div class="col-lg-offset-3 col-lg-9">
    <button type="button"
            class="btn btn-default add-edit-option-checkbox"
            name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][add-edit-option-checkbox]">
      <span class="button-text-add-option">{l s='Add' mod='totcustomfields'}</span>
      <span class="button-text-edit-option hidden">{l s='Edit' mod='totcustomfields'}</span>
    </button>
    <button type="button"
            class="btn btn-default cancel-option-checkbox"
            name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][cancel-option-checkbox]">
      <span class="button-text-edit-option">{l s='Cancel' mod='totcustomfields'}</span>
    </button>
  </div>
</div>

<div class="form-group checkbox-options-box">
  <label for="" class="control-label col-lg-3">
    <span class="label-tooltip"
          data-toggle="tooltip"
          data-html="true"
          data-original-title="{l s='List of added options' mod='totcustomfields'}"
    >{l s='Available options' mod='totcustomfields'}</span>
  </label>
  <div class="col-lg-9">
    <select size="5" class="checkbox-options">
        {if isset($data.currentConf)}
            {foreach from=$data.currentConf.options item=option key=id_option}
              {if isset($option['name'])}
                <option data-id="{$id_option|escape:'htmlall':'UTF-8'}">
                    {$option.name|escape:'html':'UTF-8'}
                </option>
              {/if}
            {/foreach}
        {/if}
    </select>
    <div class="checkbox-options-inputs hidden">
        {if isset($data.currentConf)}
            {foreach from=$data.currentConf.options item=option key=id_option}
              <input type="hidden" name="type_configuration[checkbox][checkbox-option][{$id_option|escape:'htmlall':'UTF-8'}]">
            {/foreach}
        {/if}
    </div>
  </div>
  <div class="col-lg-9 col-lg-offset-3"></div>
</div>

<div class="form-group">
  <div class="col-lg-9 col-lg-offset-3">
    <button type="button"
            class="btn btn-default delete-selected-option-checkbox"
            name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][delete-selected-option-checkbox]">
        {l s='Delete' mod='totcustomfields'}
    </button>
    <button type="button"
            class="btn btn-default edit-selected-option-checkbox"
            name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][edit-selected-option-checkbox]">
        {l s='Edit' mod='totcustomfields'}
    </button>
    <button type="button"
            class="btn btn-default default-selected-option-checkbox"
            name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][default-selected-option-checkbox]">
      <span class="button-text-add-default-option">{l s='Add to default' mod='totcustomfields'}</span>
      <span class="button-text-remove-default-option hidden">{l s='Remove from default' mod='totcustomfields'}</span>
    </button>
  </div>
</div>

<div class="form-group checkbox-default-box">
  <label for="" class="control-label col-lg-3">
    <span class="label-tooltip"
          data-toggle="tooltip"
          data-html="true"
          data-original-title="{l s='List of default options' mod='totcustomfields'}"
    >{l s='Default options' mod='totcustomfields'}</span>
  </label>
  <div class="col-lg-9">
    <select size="5" class="checkbox-default-options">
        {if isset($data.currentConf)}
            {foreach from=$data.currentConf.defaultOptions item=option key=id_option}
              {if isset($option['name'])}
                <option data-id="{$id_option|escape:'htmlall':'UTF-8'}">
                    {$option.name|escape:'html':'UTF-8'}
                </option>
              {/if}
            {/foreach}
        {/if}
    </select>
    <div class="checkbox-default-options-inputs hidden">
        {if isset($data.currentConf)}
            {foreach from=$data.currentConf.defaultOptions item=option key=id_option}
              <input type="hidden" name="type_configuration[checkbox][checkbox-default-option][{$id_option|escape:'htmlall':'UTF-8'}]">
            {/foreach}
        {/if}
    </div>
  </div>
  <div class="col-lg-9 col-lg-offset-3"></div>
</div>

<div class="form-group">
  <div class="col-lg-9 col-lg-offset-3">
    <button type="button"
            class="btn btn-default remove-default-selected-option-checkbox"
            name="type_configuration[{$code_type|escape:'htmlall':'UTF-8'}][remove-default-selected-option-checkbox]">
      <span class="button-text-remove-default-option">{l s='Remove from default' mod='totcustomfields'}</span>
    </button>
  </div>
</div>

{if isset($data.currentConf)}
    {addJsDef checkboxOptions=$data.currentConf.options}
    {addJsDef checkboxDefaultOptions=$data.currentConf.defaultOptions}
{/if}