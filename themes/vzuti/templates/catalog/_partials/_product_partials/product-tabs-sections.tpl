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

{block name='product_tabs'}
    <div class="tabs product-tabs product-sections">
        {if $iqitTheme.pp_tabs_placement == 'footer'}
            {capture name="productElementorDescription"}{hook h='displayProductElementor'}{/capture}
        {/if}

        {if $product.description || (isset($smarty.capture.productElementorDescription) && $smarty.capture.productElementorDescription != '')}
            <section class="product-description-section block-section">
                <h4 class="section-title"><span>{l s='Description' d='Shop.Theme.Catalog'}</span></h4>
                <div class="section-content">
                    {block name='product_description'}
                        <div class="product-description ">
                            <div class="rte-content">{$product.description nofilter}</div>
                            {if $iqitTheme.pp_tabs_placement == 'footer'}
                                {$smarty.capture.productElementorDescription nofilter}
                            {/if}
                        </div>
                    {/block}
                </div>
            </section>
        {/if}

        <section id="product-details-wrapper" class="product-details-section block-section {if !$product.grouped_features}empty-product-details{/if}">
            <h4 class="section-title"><span>{l s='Product Details' d='Shop.Theme.Catalog'}</span></h4>
            <div class="section-content">
                {block name='product_details'}
                    {include file='catalog/_partials/product-details.tpl'}
                {/block}
            </div>
        </section>

        {if $product.attachments}
            <section class="product-attachments-section block-section">
                <h4 class="section-title"><span>{l s='Attachments' d='Shop.Theme.Catalog'}</span></h4>

                <div class="section-content">
                    {block name='product_attachments'}
                        {if $product.attachments}
                            <div class="tab-pane in" id="attachments">
                                <section class="product-attachments">
                                    {foreach from=$product.attachments item=attachment}
                                        <div class="attachment">
                                            <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                                                {$attachment.name}
                                            </a>
                                            <p> <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.description}</a></p>
                                            <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                                                <i class="fa fa-download" aria-hidden="true"></i> {l s='Download' d='Shop.Theme.Actions'}
                                                ({$attachment.file_size_formatted})
                                            </a>
                                            <hr />
                                        </div>
                                    {/foreach}
                                </section>
                            </div>
                        {/if}
                    {/block}
                </div>
            </section>
        {/if}

        {if $iqitTheme.pp_accesories == 'tab'}
            {if $accessories}
                <section class="product-accesories-section block-section">
                    <h4 class="section-title"><span>{l s='Related products' d='Shop.Theme.Catalog'}</span></h4>

                    <div class="section-content swiper-container-wrapper">
                        <div class="products products-grid swiper-container swiper-default-carousel">
                            <div class="swiper-wrapper">
                                {foreach from=$accessories item="product_accessory" key="position"}
                                    <div class="swiper-slide"> {include file="catalog/_partials/miniatures/product.tpl" product=$product_accessory  carousel=true position=$position}</div>
                                {/foreach}
                            </div>
                            <div class="swiper-pagination swiper-pagination-product"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                    
                </section>
            {/if}
        {/if}


        {if $iqitTheme.pp_man_desc}
        {if isset($product_manufacturer)}
            {capture name="manufacturerElementorDescription"}{if $iqitTheme.pp_tabs_placement == 'footer'}{hook h='displayManufacturerElementor' manufacturerId = $product_manufacturer->id}{/if}{/capture}
            {if $smarty.capture.manufacturerElementorDescription != '' || $product_manufacturer->description != ''}
        <section class="product-brand-section block-section">
            <h4 class="section-title"><span> {l s='About' d='Shop.Warehousetheme'} {$product_manufacturer->name}</span></h4>

            <div class="section-content">
                <div class="rte-content">
                {$product_manufacturer->description nofilter}
                </div>
                {$smarty.capture.manufacturerElementorDescription nofilter}
            </div>
        </section>
            {/if}
        {/if}
        {/if}




        {foreach from=$product.extraContent item=extra key=extraKey}
            <section 
            {foreach $extra.attr as $key => $val}
                {if $key == "class"}
                    {$key}="product-extracontent-section block-section {$val}"
                {else}
                    {if $val != ""}
                        {$key}="{$val}"
                    {/if}
                {/if}
            {/foreach}
            >
                <h4 class="section-title"><span>{$extra.title nofilter}</span></h4>

                <div class="section-content">
                    {$extra.content nofilter}
                </div>
            </section>
        {/foreach}

    </div>
{/block}
