{*
* 2017 IQIT-COMMERCE.COM
*
* NOTICE OF LICENSE
*
* This file is licenced under the Software License Agreement.
* With the purchase or the installation of the software in your application
* you accept the licence agreement
*
* @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
* @copyright 2017 IQIT-COMMERCE.COM
* @license   Commercial license (You can not resell or redistribute this software.)
*
*}

{extends file='customer/page.tpl'}

{block name='page_header_container'}
    {if $readOnly}
        <header class="page-header">
            <h1 class="h1 page-title"><span>{l s='Збережені товари' mod='iqitwishlist'}</span></h1>
        </header>
        {else}
        {$smarty.block.parent}
    {/if}
{/block}


{block name='page_title'}
    {if !$readOnly}
        {l s='Збережені товари' mod='iqitwishlist'}
    {/if}
{/block}

{block name='page_content'}
    {if isset($wishlistProducts) && $wishlistProducts}
        <div id="iqitwishlist-user-products" class="mb-4">
            {foreach from=$wishlistProducts item="wishlistProduct"}
                {include 'module:iqitwishlist/views/templates/front/iqitwishlist-product.tpl' product=$wishlistProduct}
            {/foreach}
        </div>
        {if !$readOnly}
            <div id="iqitwishlist-share" class="iqitwishlist-share">
                <h3>{l s='Поділіться списком своїх бажань' mod='iqitwishlist'}</h3>
                <div class="input-group">
                    <input class="form-control js-to-clipboard" readonly="readonly" type="url"
                           value="{url entity='module' name='iqitwishlist' relative_protocol=false controller='view' params=['wishlistToken' => $token]}">
                    <span class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="iqitwishlist-clipboard-btn"
                                data-text-copied="{l s='скопіювати' mod='iqitwishlist'}"
                                data-text-copy="{l s='скопіювати посилання' mod='iqitwishlist'}">{l s='Скопіювати посилання' mod='iqitwishlist'}</button>
                    </span>
                </div>

                <div class="addthis_inline_share_toolbox mt-3" data-title="{l s='My wishlist' mod='iqitwishlist'}"
                     data-url="{url entity='module' name='iqitwishlist' controller='view' params=['wishlistToken' => $token]}"></div>

                <script type="text/javascript"
                        src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58591f8d80978019"></script>
            </div>
        {/if}
        <p class="alert alert-warning hidden-xs-up"
           id="iqitwishlist-warning">{l s='Збережені товари відсутні' mod='iqitwishlist'}</p>
        {if isset($crosselingProducts) && $crosselingProducts}
            <section id="iqitwishlist-crosseling" class="featured-products clearfix mt-4">
                <h3>{l s='Customers who bought this product(s) also bought:' mod='iqitwishlist'}</h3>
                <div class="swiper-container-wrapper">
                    <div class="products products-grid swiper-container swiper-default-carousel">
                        <div class="swiper-wrapper">
                            {foreach from=$crosselingProducts item="product"}
                                <div class="swiper-slide"> {include file="catalog/_partials/miniatures/product.tpl" product=$product carousel=true}</div>
                            {/foreach}
                        </div>
                        <div class="swiper-pagination swiper-pagination-product"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>


            </section>
        {/if}
    {else}
        <p class="alert alert-warning">{l s='Збережені товари відсутні' mod='iqitwishlist'}</p>
    {/if}
{/block}


