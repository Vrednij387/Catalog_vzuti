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

<form class="horizontal-form clearfix objectDisplayForm" action="{$module.config_url|escape:'htmlall':'UTF-8'}" method="POST">
    <input type="hidden" name="code_display" value="{$display.code|escape:'htmlall':'UTF-8'}"/>

    <ps-table header="{$display.name|escape:'htmlall':'UTF-8'}" icon="{$display.data.icon|escape:'htmlall':'UTF-8'}"
              content="{$display.data.tableContent|replace:'{':'\{'|replace:'}':'\}'|escape:'htmlall':'UTF-8'}"
              no-items-text="{l s='No items found' mod='totcustomfields'}">

        <button type="submit" class="btn btn-primary pull-right"
                name="saveObjectDisplayConfiguration">{l s='Save' mod='totcustomfields'}</button>

    </ps-table>
</form>