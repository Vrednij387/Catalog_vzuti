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

{block name='product_list_header'}
    <h1 class="h1 page-title">
    <span>{$manufacturer.name}</span></h1>
    <div id="manufacturer-description-wrapper" class="mb-3">
        <div id="manufacturer-short-description" class="rte-content">
            {if $manufacturer.short_description}
                {$manufacturer.short_description nofilter}
            {/if}
        </div>
         <div class="brand-logo"><img src="{$manufacturer.logoLink}"></div>
    </div>    
    {hook h='displayManufacturerElementor' manufacturerId = $manufacturer.id}
{/block}

{block name='product_list_footer'}
    {if isset($manufacturer) && $listing.pagination.items_shown_from == 1}
        <div class="description-wrapper manufacturer-additional-description">
            {if $manufacturer.description}
                <div id="manufacturer-description" class="rte-content additional-description">
                    {$manufacturer.description nofilter}
                </div>
                <p id="show_text">{l s='Читати далі'}</p>
            {/if}

            {if $seo_products}
                <div class="seo-table">
                    <h2 class="seo-table__title">Взуття від {$manufacturer.name}, ціни на найпопулярніші моделі:</h2>
                    <table class="seo-table__table">
                        <thead>
                            <tr><td>Назва</td><td>Ціна</td></tr>
                        </thead>
                        <tbody>
                        {foreach $seo_products as $seo_product}
                            <tr>
                                <td>
                                    <a href="{$seo_product.product_link}" title="Купити {$seo_product.category_default|lower} {$seo_product.name} - ціна {$seo_product.price|intval} грн">{$seo_product.category_default} {$seo_product.name}</a>
                                </td>
                                <td>
                                    {$seo_product.price|intval} {l s='грн'}
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            {/if}
            <!-- Питання та відповіді -->
            <div class="seo-faq" itemscope itemtype="https://schema.org/FAQPage">
                <h2>
                    Поширені питання про {$manufacturer.name}
                </h2>
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <div class="faq-title" itemprop="name">🤑 Які ціни на взуття від {$manufacturer.name}?</div>
                    <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            В магазині Vzuti можна купити оригінальне взуття {$manufacturer.name} за ціною від {$seo_min_price|intval}грн до {$seo_max_price|intval}грн.
                        </div>
                    </div>
                </div>
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <div class="faq-title" itemprop="name">🛍 Які нові моделі від {$manufacturer.name} можна придбати?</div>
                    <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            Vzuti пропонують нові моделі {$manufacturer.name}:
                            <ul>
                                {foreach $seo_products_new as $seo_product_new}
                                    <li>✓ {$seo_product_new.category_default} {$seo_product_new.name} - ціна {$seo_product_new.price|intval} грн</li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                </div>
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <div class="faq-title" itemprop="name">👟 Які моделі взуття {$manufacturer.name} найпопулярні у {$smarty.now|date_format:"%Y"} році?</div>
                    <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            Найпопулярніші моделі від {$manufacturer.name} це:
                            <ol>
                                {foreach $seo_products as $seo_product}
                                    <li>{$seo_product.name}</li>
                                {/foreach}
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

    {/if}
{/block}



