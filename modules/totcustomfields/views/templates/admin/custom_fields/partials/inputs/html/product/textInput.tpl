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

<div class="row px-0 mx-0">
    <div class="form-group col-12 w-100">
        <label class="form-control-label col-lg-3 px-0 {if $required}required{/if}" for="{$code|escape:'htmlall':'UTF-8'}">
            {if $required}
                <span style="color: red">*</span>
            {/if}
            {$name|escape:'htmlall':'UTF-8'}
            {if !empty($instructions)}
                <span class="help-box"
                      data-toggle="popover"
                      title=""
                      data-content="{$instructions|escape:'htmlall':'UTF-8'}"></span>
            {/if}
        </label>

        <div class="col-lg-9">
            {if !$translatable}
                <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                       class="form-control"
                       name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                       value="{if $value}{$value|escape:'htmlall':'UTF-8'}{else}{$default_value|escape:'htmlall':'UTF-8'}{/if}"
                       maxlength="{$maxlength|escape:'htmlall':'UTF-8'}"
                       pattern="{$regex|escape:'htmlall':'UTF-8'}"
                       type="text"/>
            {else}
                {foreach from=$languages item=lang}
                    <div class="translatable-field row lang-{$lang.id_lang|escape:'htmlall':'UTF-8'}"
                         {if $lang.id_lang != $defaultLang}style="display:none"{/if}>

                        <div class="input-text-wrapper d-flex w-100">
                            <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$lang.id_lang|escape:'htmlall':'UTF-8'}"
                                   class="form-control"
                                   name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$lang.id_lang|escape:'htmlall':'UTF-8'}]"
                                   value="{if isset($value[$lang.id_lang])}{$value[$lang.id_lang]|escape:'htmlall':'UTF-8'}{else}{$default_value|escape:'htmlall':'UTF-8'}{/if}"
                                   maxlength="{$maxlength|escape:'htmlall':'UTF-8'}"
                                   pattern="{$regex|escape:'htmlall':'UTF-8'}"
                                   type="text">
                            <div class="col-2">
                              <div class="dropdown">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle js-locale-btn"
                                        data-toggle="dropdown">
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
                    </div>
                {/foreach}
            {/if}
        </div>
    </div>
</div>