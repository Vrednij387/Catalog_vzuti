{*
* 2007-2017 IQIT-COMMERCE.COM
*
* NOTICE OF LICENSE
*
*  @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
*  @copyright 2007-2017 IQIT-COMMERCE.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
*
*}

<div class="cbp-products-big row ">
    {foreach from=$products item=product name=homeFeaturedProducts}
        <div class="product-grid-menu col-{$perline}">
            <div class="product-miniature-container">
                <div class="product-image-container grid">
                    <ul class="product-flags">
                        {foreach from=$product.flags item=flag}
                            <li class="product-flag {$flag.type}">{$flag.label}</li>
                        {/foreach}
                    </ul>
                    <a class="product_img_link" href="{$product.url}" title="{$product.name}">
                        
                    {if $product.cover}
                    <img class="img-fluid"
                             src="{$product.cover.bySize.home_default.url}"
                             loading="lazy"
                             alt="{if !empty($product.legend)}{$product.legend}{else}{$product.name}{/if}"
                            {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} />
                    {else}
                        <img class="img-fluid"
                        src="{$urls.no_picture_image.bySize.cart_default.url}"
                        loading="lazy"
                        alt="{if !empty($product.legend)}{$product.legend}{else}{$product.name}{/if}"
                       {if isset($homeSize)} width="{$homeSize.width}" height="{$homeSize.height}"{/if} />
                    {/if}


                    </a>
                </div>
                <h6 class="product-title">
                    <a href="{$product.url}">{$product.name|truncate:100:'...'}</a>
                </h6>
                {if $product.show_price}
                <div class="product-price-and-shipping" >
                    <span class="product-price">{$product.price}</span>
                    {if $product.has_discount}
                        {hook h='displayProductPriceBlock' product=$product type="old_price"}
                        <span class="regular-price text-muted">{$product.regular_price}</span>
                    {/if}
                </div>
                {/if}
            </div>
        </div>
    {/foreach}
</div>
