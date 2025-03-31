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
            <select name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                    id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                    class="custom-select select2"
            >
                {foreach from=$conf item=option key=id_option}
                    <option value="{$id_option|escape:'htmlall':'UTF-8'}"
                            data-id="{$id_option|escape:'htmlall':'UTF-8'}"
                            {if $value && $id_option == $value}
                                selected
                            {else}
                                {if !$value && $id_option == $default_value}selected{/if}
                            {/if}>
                        {$option.name|escape:'htmlall':'UTF-8'}
                    </option>
                {/foreach}
            </select>
        </div>
    </div>
{else}
    <div class="form-group">
        <label class="control-label col-lg-3 {if $required}required{/if}" for="{$code|escape:'htmlall':'UTF-8'}">
            {if $instructions}
            <span class="label-tooltip"
                  data-toggle="tooltip"
                  title=""
                  data-original-title="{$instructions|escape:'htmlall':'UTF-8'}">
            {/if}
            {$name|escape:'htmlall':'UTF-8'}
            {if $instructions}
                </span>
            {/if}
        </label>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-9">
                    <select name="totcustomfields_inputs[{$id|escape:'htmlall':'UTF-8'}]"
                            id="totcustomfields_inputs_{$id|escape:'htmlall':'UTF-8'}"
                            class="form-control select2"
                    >
                        {foreach from=$conf item=option key=id_option}
                            <option value="{$id_option|escape:'htmlall':'UTF-8'}"
                                    data-id="{$id_option|escape:'htmlall':'UTF-8'}"
                                    {if $value && $id_option == $value}
                                        selected
                                    {else}
                                        {if !$value && $id_option == $default_value}selected{/if}
                                    {/if}>
                                {$option.name|escape:'htmlall':'UTF-8'}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
    </div>
{/if}

