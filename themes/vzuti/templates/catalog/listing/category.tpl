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
{extends file='catalog/listing/product-list.tpl'}

{if isset($category)}
    {capture name="justElementor"}{hook h='isJustElementor' categoryId=$category.id}{/capture}
{/if}

{block name='head' append}
    {if $iqitTheme.bread_bg_category}
        {if isset($category.image.bySize.category_default.url) && $category.image.bySize.category_default.url != ''}
            <style>
                #wrapper .breadcrumb{  background-image: url('{$category.image.bySize.category_default.url}'); }
            </style>
        {/if}
    {/if}
{/block}



{block name='head_og_image'}
    {if isset($category.image.bySize.category_default.url) && $category.image.bySize.category_default.url}
        <meta property="og:image" content="{$category.image.bySize.category_default.url}">
        <meta property="og:image:width" content="{$category.image.bySize.category_default.width}">
        <meta property="og:image:height" content="{$category.image.bySize.category_default.height}">
    {else}
        {$smarty.block.parent}
    {/if}
{/block}



{block name='hook_display_wrapper_top_container' append}
    {if $listing.pagination.items_shown_from == 1}
        {if $iqitTheme.cat_desc == 'above-s'}
            {if $category.description}
                <div class="category-description category-description-top top1{if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if}">
                    <div class="rte-content">{$category.description nofilter}</div>
                </div>
            {/if}
            {hook h='displayCategoryElementor'}
        {/if}
    {/if}
{/block}


{block name='hook_display_wrapper_bottom_container' append}
    {if $listing.pagination.items_shown_from == 1}
        {if $iqitTheme.cat_desc == 'below-s'}
            {if $category.description}
                <div class="category-description category-description-top top2{if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if}">
                    <div class="rte-content">{$category.description nofilter}</div>
                </div>
            {/if}
            {hook h='displayCategoryElementor'}
        {/if}
    {/if}
{/block}




{if $smarty.capture.justElementor}
    {block name='left_column'}{/block}
    {block name='right_column'}{/block}
    {block name='content_wrapper_start'} <div id="content-wrapper" class="col-12"> {/block}
        {block name='content'}
            {hook h='displayCategoryElementor'}
        {/block}
    {else}


        {block name='product_list_footer'}
            {include file='catalog/_partials/category-footer.tpl' listing=$listing category=$category}
        {/block}

        
        {block name='product_list_header'}
            {include file='catalog/_partials/category-header.tpl' listing=$listing category=$category}
            {if $listing.pagination.items_shown_from == 1}
                {if $iqitTheme.cat_image == 1}
                    {if isset($category.image.bySize.category_default.url) && $category.image.bySize.category_default.url}
                        <div class="category-image {if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if}">
                            {if $iqitTheme.cat_desc == 'onimage'}
                                {if $category.description}
                                    <div class="category-description category-description-image">
                                        <div class="rte-content">{$category.description nofilter}</div>
                                    </div>
                                {/if}
                            {/if}
                            <picture>
                            {if !empty($category.image.bySize.category_default.sources.avif)}<source srcset="{$category.image.bySize.category_default.sources.avif}" type="image/avif">{/if}
                            {if !empty($category.image.bySize.category_default.sources.webp)}<source srcset="{$category.image.bySize.category_default.sources.webp}" type="image/webp">{/if}
                            <img src="{$category.image.bySize.category_default.url}"
                                alt="{if !empty($category.image.legend)}{$category.image.legend}{else}{$category.name}{/if}"
                                class="img-fluid" width="{$category.image.bySize.category_default.width}"
                                height="{$category.image.bySize.category_default.height}" loading="lazy">
                            </picture>
                        </div>
                    {else}
                        {if $iqitTheme.cat_desc == 'onimage'}
                            {if $category.description}
                                <div class="category-description category-description-top top3{if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if}">
                                    <div class="rte-content">{$category.description nofilter}</div>
                                </div>
                            {/if}
                        {/if}
                    {/if}
                {/if}


                {if $iqitTheme.cat_desc == 'above'}
                    {if $category.description}
                        <div id="above_description" class="category-description category-description-top top4{if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if}">
                            <div class="rte-content">{$category.description nofilter}</div>
                            <div id="show_descr" class="d-md-none">{l s='Читати далі'}</div>
                        </div>
                    {/if}
                    {hook h='displayCategoryElementor'}
                {/if}


                {if $iqitTheme.cat_desc == 'above-d'}
                    {if $category.description}
                        <div class="category-description category-description-top top5{if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if}">
                            <div class="rte-content">{$category.description nofilter}</div>
                        </div>
                    {/if}
                {/if}

                {if $iqitTheme.cat_desc == 'below-d'}
                    {hook h='displayCategoryElementor'}
                {/if}

                {if $iqitTheme.cat_sub_thumbs == 1}
                    {include file='catalog/_partials/category-subcategories.tpl'}
                {/if}

            {/if}
        {/block}

        {block name='product_list_bottom'}
            {include file='catalog/_partials/products-bottom.tpl' listing=$listing}
        {/block}

        {block name='product_list_bottom_static'}
            {if $listing.pagination.items_shown_from == 1}
                {if $iqitTheme.cat_desc == 'below'}
                    {if $category.description}
                        <div class="category-description category-description-bottom {if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if}">
                            <hr />
                            <div class="rte-content">{$category.description nofilter}</div>
                        </div>
                    {/if}
                    {hook h='displayCategoryElementor'}
                {/if}

                {if $iqitTheme.cat_desc == 'above-d'}
                    {hook h='displayCategoryElementor'}
                {/if}

                {if $iqitTheme.cat_desc == 'below-d'}
                    {if $category.description}
                        <div class="description-wrapper">
                        <div class="category-description category-description-top top6{if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if} mt-4">
                            <hr />
                            <div class="rte-content">{$category.description nofilter}</div>
                        </div>
                        </div>    
                    {/if}
                {/if}


            {/if}
        {/block}
{/if}