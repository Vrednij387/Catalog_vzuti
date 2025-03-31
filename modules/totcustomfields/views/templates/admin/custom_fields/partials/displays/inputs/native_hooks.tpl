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

{if !empty($displayData.hooks)}
    <ps-checkboxes label="{l s='Choose one or more hooks :' mod='totcustomfields'}" class="asyncMount">

        {foreach from=$displayData.hooks item=hook}
            <ps-checkbox
                    name="display_configuration[{$displayData.code_display|escape:'htmlall':'UTF-8'}][hooks]"
                    value="{$hook.name|escape:'htmlall':'UTF-8'}" checked="{if isset($hook.selected)}true{else}false{/if}">
                {$hook.title|escape:'htmlall':'UTF-8'} ({$hook.name|escape:'htmlall':'UTF-8'})
            </ps-checkbox>
        {/foreach}
    </ps-checkboxes>
{else}
    <div class="col-lg-9 col-lg-offset-3">
        {l s='No hooks available for this location.' mod='totcustomfields'}
    </div>
{/if}