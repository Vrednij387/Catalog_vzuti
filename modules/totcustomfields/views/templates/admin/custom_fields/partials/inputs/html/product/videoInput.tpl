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
    <div class="col-md-9">
        <fieldset class="form-group">
            <label class="form-control-label">
                {if $required}
                    <span style="color: red">*</span>
                {/if}
                {$name|escape:'htmlall':'UTF-8'}
                {if !empty($instructions)}
                    <span class="help-box"
                          data-toggle="popover"
                          data-content="{$instructions|escape:'htmlall':'UTF-8'}"
                          data-original-title=""
                          title=""></span>
                {/if}
            </label>

            {if !$translatable}
                <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                       class="form-control"
                       name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                       value="{if $value}{$value|escape:'htmlall':'UTF-8'}{else}{$default_value[$defaultLang]|escape:'htmlall':'UTF-8'}{/if}"
                       type="text"/>
            {else}
                <div class="translations tabbable">
                    <div class="translationsFields tab-content">
                        {foreach from=$languages item=lang}
                            <div class="tab-pane translation-field translation-label-{$lang.iso_code|escape:'htmlall':'UTF-8'} {if $currentLang == $lang.id_lang}active{/if}">
                                <input id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}_{$lang.id_lang|escape:'htmlall':'UTF-8'}"
                                       class="form-control"
                                       name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}][{$lang.id_lang|escape:'htmlall':'UTF-8'}]"
                                       value="{if isset($value[$lang.id_lang])}{$value[$lang.id_lang]|escape:'htmlall':'UTF-8'}{else}{$default_value[$defaultLang]|escape:'htmlall':'UTF-8'}{/if}"
                                       type="text">
                            </div>
                        {/foreach}
                    </div>
                </div>
            {/if}

        </fieldset>
    </div>
</div>