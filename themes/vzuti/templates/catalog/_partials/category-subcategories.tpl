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

{block name='product_list_subcategories'}
    {if isset($subcategories) && $subcategories}
        <!-- Subcategories -->
        <div class="product-list-subcategories {if $iqitTheme.cat_hide_mobile} hidden-sm-down{/if}">
            <div class="row">
                {foreach from=$subcategories item=subcategory}
                    {if $category.id != 20}
                        <div class="col-{$iqitTheme.cat_sub_thumbs_p} col-md-{$iqitTheme.cat_sub_thumbs_t} col-lg-{$iqitTheme.cat_sub_thumbs_d}">
                    {else}
                            <div class="col-6 col-md-6 col-lg-2 id_{$category.id}">

                    {/if}

                        {*
                        <div class="subcategory-image">
                            <a href="{$subcategory.url}">
                                {if $subcategory.image}
                                    <picture>
                                    {if !empty($subcategory.image.bySize.small_default.sources.avif)}<source srcset="{$subcategory.image.bySize.small_default.sources.avif}" type="image/avif">{/if}
                                    {if !empty($subcategory.image.bySize.small_default.sources.webp)}<source srcset="{$subcategory.image.bySize.small_default.sources.webp}" type="image/webp">{/if}
                                    <img src="{$subcategory.image.bySize.small_default.url}" alt="{$subcategory.name}" width="{$subcategory.image.bySize.small_default.width}"
                                     height="{$subcategory.image.bySize.small_default.height}" class="img-fluid" loading="lazy" />
                                     </picture>
                                {else}
                                    <picture>
                                    {if !empty($urls.no_picture_image.bySize.small_default.sources.avif)}<source srcset="{$urls.no_picture_image.bySize.small_default.sources.avif}" type="image/avif">{/if}
                                    {if !empty($urls.no_picture_image.bySize.small_default.sources.webp)}<source srcset="{$urls.no_picture_image.bySize.small_default.sources.webp}" type="image/webp">{/if}
                                    <img src="{$urls.no_picture_image.bySize.small_default.url}" alt="{$subcategory.name}" width="{$urls.no_picture_image.bySize.small_default.width}"
                                         height="{$urls.no_picture_image.bySize.small_default.height}" class="img-fluid" loading="lazy" />
                                    </picture>
                                {/if}
                            </a>
                        </div>
                        *}
                        <a class="subcategory-name id_{$category.id}" href="{$subcategory.url}" title="{$subcategory.name}">{$subcategory.name}</a>
                    </div>
                {/foreach}
            </div>
        </div>
    {/if}
{/block}

