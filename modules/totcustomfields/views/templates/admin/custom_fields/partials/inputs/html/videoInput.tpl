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

{if version_compare($version, '1.7.6', '>=') && !(isset($isOldPage) && $isOldPage)}
  <div class="form-group row">
    <label class="form-control-label col-lg-3" for="{$code|escape:'htmlall':'UTF-8'}">
        {if $required}
          <span class="text-danger">*</span>
        {/if}
        {$name|escape:'htmlall':'UTF-8'}
        {if !empty($instructions)}
          <span class="help-box"
                data-toggle="popover"
                data-content="{$instructions|escape:'htmlall':'UTF-8'}"
                data-original-title=""
                title="">
            </span>
        {/if}
    </label>

    <div class="col-sm">

        {if !$translatable}
          <div class="row m-0">
            <div class="w-100">
              <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                     class="form-control"
                     name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                     value="{if $value}{$value|escape:'htmlall':'UTF-8'}{else}{$default_value[$defaultLang]|escape:'htmlall':'UTF-8'}{/if}"
                     type="text"/>
            </div>
          </div>
        {else}

            {foreach from=$languages item=lang}
              <div class="translatable-field row lang-{$lang.id_lang|escape:'htmlall':'UTF-8'} locale-input-group flex-nowrap m-0"
                   {if $lang.id_lang != $defaultLang}style="display:none"{/if}>

                <div class="w-100">
                  <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$lang.id_lang|escape:'htmlall':'UTF-8'}"
                         class="form-control"
                         name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$lang.id_lang|escape:'htmlall':'UTF-8'}]"
                         value="{if isset($value[$lang.id_lang])}{$value[$lang.id_lang]|escape:'htmlall':'UTF-8'}{else}{$default_value[$defaultLang]|escape:'htmlall':'UTF-8'}{/if}"
                         type="text">
                </div>

                <div class="flex-shrink-0">
                  <div class="dropdown">

                    <button type="button" class="btn btn-outline-secondary dropdown-toggle js-locale-btn"
                            data-toggle="dropdown"
                            tabindex="-1">
                        {$lang.iso_code|escape:'htmlall':'UTF-8'}
                      <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu">
                        {foreach from=$languages item=lang}
                          <a class="dropdown-item"
                             href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'});">
                              {$lang.name|escape:'htmlall':'UTF-8'}
                          </a>
                        {/foreach}
                    </div>
                  </div>
                </div>
              </div>
            {/foreach}

        {/if}
    </div>
  </div>
{else}
<div class="form-group">
    <label class="control-label col-lg-3 {if $required}required{/if}" for="{$code|escape:'htmlall':'UTF-8'}">
        {if $instructions}
        <span class="label-tooltip" data-toggle="tooltip" title=""
              data-original-title="{$instructions|escape:'htmlall':'UTF-8'}">
        {/if}
            {$name|escape:'htmlall':'UTF-8'}
            {if $instructions}
            </span>
        {/if}
    </label>

    <div class="col-lg-9">

        {if !$translatable}
            <div class="row">
                <div class="col-lg-9">
                    <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                           class="form-control"
                           name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                           value="{if $value}{$value|escape:'htmlall':'UTF-8'}{else}{$default_value|escape:'htmlall':'UTF-8'}{/if}"
                           type="text"/>
                </div>
            </div>
        {else}

            {foreach from=$languages item=lang}
                <div class="translatable-field row lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}"
                     {if $lang.id_lang != $defaultLang}style="display:none"{/if}>

                    <div class="col-lg-9">
                        <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$lang.id_lang|escape:'htmlall':'UTF-8'}"
                               class="form-control"
                               name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$lang.id_lang|escape:'htmlall':'UTF-8'}]"
                               value="{if isset($value[$lang.id_lang])}{$value[$lang.id_lang]|escape:'htmlall':'UTF-8'}{else}{$default_value|escape:'htmlall':'UTF-8'}{/if}"
                               type="text">
                    </div>

                    <div class="col-lg-2">

                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                tabindex="-1">
                            {$lang.iso_code|escape:'htmlall':'UTF-8'}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            {foreach from=$languages item=lang}
                                <li>
                                    <a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'htmlall':'UTF-8'});">
                                        {$lang.name|escape:'htmlall':'UTF-8'}
                                    </a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>

                </div>
            {/foreach}

        {/if}
    </div>
</div>
{/if}
