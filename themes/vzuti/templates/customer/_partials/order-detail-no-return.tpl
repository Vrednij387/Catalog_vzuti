{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{block name='order_products_table'}
    <div class="box hidden-sm-down">
        <table id="order-products" class="table table-bordered">
            <thead class="thead-default">
            <tr>
                <th>{l s='Фото' d='Shop.Theme.Catalog'}</th>
                <th>{l s='Product' d='Shop.Theme.Catalog'}</th>
                <th>{l s='Quantity' d='Shop.Theme.Catalog'}</th>
                <th>{l s='Unit price' d='Shop.Theme.Catalog'}</th>
                <th>{l s='Total price' d='Shop.Theme.Catalog'}</th>
            </tr>
            </thead>
            {foreach from=$order.products item=product}
                <tr>
                    <td>
                        <img src="/{$product.image->id}-cart_default/cart_product.{$product.image->image_format}">
                    </td>
                    <td>
                        <div class="row">
                           {* <pre>{var_dump($product)}</pre>*}
                            {if isset($product.default_image.bySize.cart_default.url)}
                                <div class="col-2">
                                    <a href="{url entity='product' id=$product.product_id ipa=$product.id_product_attribute}">
                                        <img src="{$product.default_image.bySize.cart_default.url}" alt="{$product.name}" class="img-fluid" loading="lazy"/>
                                    </a>
                                </div>
                            {/if}
                            <div class="col-10">

                                <h3>
                                    <a href="{url entity='product' id=$product.product_id ipa=$product.id_product_attribute}">
                                        {$product.name}
                                    </a>
                                </h3>
                                {if isset($product.download_link)}<a
                                href="{$product.download_link}">{l s='Download' d='Shop.Theme.Catalog'}</a>{/if}
                                {if $product.product_reference}
                                    {l s='Reference' d='Shop.Theme.Catalog'}: {$product.product_reference}

                                {/if}
                                {if $product.customizations}
                                    {foreach from=$product.customizations item="customization"}
                                        <div class="customization">
                                            <a href="#" data-toggle="modal"
                                               data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
                                        </div>
                                        <div id="_desktop_product_customization_modal_wrapper_{$customization.id_customization}">
                                            <div class="modal fade customization-modal"
                                                 id="product-customizations-modal-{$customization.id_customization}"
                                                 tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="{l s='Close' d='Shop.Theme.Global'}">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h4 class="modal-title">{l s='Product customization' d='Shop.Theme.Catalog'}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            {foreach from=$customization.fields item="field"}
                                                                <div class="product-customization-line row">
                                                                    <div class="col-sm-3 col-xs-4 label">
                                                                        {$field.label}
                                                                    </div>
                                                                    <div class="col-sm-9 col-xs-8 value">
                                                                        {if $field.type == 'text'}
                                                                            {if (int)$field.id_module}
                                                                                {$field.text nofilter}
                                                                            {else}
                                                                                {$field.text}
                                                                            {/if}
                                                                        {elseif $field.type == 'image'}
                                                                            <img src="{$field.image.small.url}" class="img-fluid" loading="lazy">
                                                                        {/if}
                                                                    </div>
                                                                </div>
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
                    </td>
                    <td>
                        {if $product.customizations}
                            {foreach $product.customizations as $customization}
                                {$customization.quantity}
                            {/foreach}
                        {else}
                            {$product.quantity}
                        {/if}
                    </td>
                    <td class="text-xs-right">{$product.price}</td>
                    <td class="text-xs-right">{$product.total}</td>
                </tr>
            {/foreach}
            <tfoot>

            {foreach $order.subtotals as $line}
                {if $line.value}
                    <tr class="text-xs-right line-{$line.type}">
                        <td colspan="4">{$line.label}</td>
                        <td>{$line.value}</td>
                    </tr>
                {/if}
            {/foreach}
            <tr class="text-xs-right line-{$order.totals.total.type}">
                <td colspan="4">{$order.totals.total.label}</td>
                <td>{$order.totals.total.value}</td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="order-items hidden-md-up box">
        {foreach from=$order.products item=product}
            <div class="order-item">
                <div class="row">
                    <div class="col-sm-5 desc">
                        <img src="/{$product.image->id}-cart_default/cart_product.{$product.image->image_format}" class="mob_order-img">
                        <div class="name">{$product.name}</div>
                        {if $product.product_reference}
                            <div class="ref">{l s='Reference' d='Shop.Theme.Catalog'}: {$product.product_reference}</div>
                        {/if}
                        {if isset($product.download_link)}<a
                        href="{$product.download_link}">{l s='Download' d='Shop.Theme.Catalog'}</a>{/if}<br/>
                        {if $product.customizations}
                            {foreach $product.customizations as $customization}
                                <div class="customization">
                                    <a href="#" data-toggle="modal"
                                       data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
                                </div>
                                <div id="_mobile_product_customization_modal_wrapper_{$customization.id_customization}">
                                </div>
                            {/foreach}
                        {/if}
                    </div>
                    <div class="col-sm-7 qty">
                        <div class="row">
                            <div class="col-4 text-sm-left text-xs-left">
                                {$product.price}
                            </div>
                            <div class="col-4">
                                {if $product.customizations}
                                    {foreach $product.customizations as $customization}
                                        {$customization.quantity}
                                    {/foreach}
                                {else}
                                   x{$product.quantity}
                                {/if}
                            </div>
                            <div class="col-4 text-xs-right">
                                {$product.total}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
    <div class="order-totals hidden-md-up box">
        {foreach $order.subtotals as $line}
            {if $line.value}
                <div class="order-total row">
                    <div class="col-8"><strong>{$line.label}</strong></div>
                    <div class="col-4 text-xs-right">{$line.value}</div>
                </div>
            {/if}
        {/foreach}
        <div class="order-total row">
            <div class="col-8"><strong>{$order.totals.total.label}</strong></div>
            <div class="col-4 text-xs-right">{$order.totals.total.value}</div>
        </div>
    </div>
{/block}
