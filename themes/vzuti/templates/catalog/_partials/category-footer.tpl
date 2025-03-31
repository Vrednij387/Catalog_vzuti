{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
<div id="js-product-list-footer">

    {if isset($category) && $listing.pagination.items_shown_from == 1}
        <div class="description-wrapper">
            <div class="category-additional-description">
                {if $category.additional_description}
                    <div class="additional-description">
                        {$category.additional_description nofilter}
                    </div>
                    <p id="show_text">{l s='Читати далі'}</p>
                {/if}
                {if $seo_products}
                    <div class="seo-table">
                        <h2 class="seo-table__title">Ціни на {$category.name|lower}</h2>
                        <table class="seo-table__table">
                            <thead>
                            <tr>
                                <td>Назва</td>
                                <td>Бренд</td>
                                <td>Ціна</td>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $seo_products as $seo_product}
                                <tr>
                                    <td>
                                        <a href="{$seo_product.product_link}" title="Купити {$seo_product.category_default|lower} {$seo_product.name} - ціна {$seo_product.price|intval} грн">{$seo_product.category_default} {$seo_product.name}</a>
                                    </td>
                                    <td>
                                        {$seo_product.manufacturer_name}
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

                    <!-- Питання та відповіді кінець -->
                     <div class="seo-faq" itemscope itemtype="https://schema.org/FAQPage">
                          <h2>
                              Поширені питання про {$category.name|lower}
                          </h2>
                         {if $category.id == 9}
                             <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                 <div class="faq-title" itemprop="name">Скільки коштує {$category.name|lower}?</div>
                                 <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                     <div itemprop="text">
                                         В магазині Vzuti можна купити оригінальне {$category.name|lower} за ціною від {$seo_min_price|intval}грн до {$seo_max_price|intval}грн.
                                     </div>
                                 </div>
                             </div>
                             <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                 <div class="faq-title" itemprop="name">Хочу купити нову пару жіночого взуття. Які моделі є в наявності?</div>
                                 <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                     <div itemprop="text">
                                         Магазин Vzuti пропонує купити наступні моделі цієї коллекції
                                         <ul>
                                             {foreach $seo_products_new as $seo_product_new}
                                                 <li>✓ {$seo_product_new.name} - ціна {$seo_product_new.price|intval} грн</li>

                                             {/foreach}
                                         </ul>
                                     </div>
                                 </div>
                             </div>
                             <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                 <div class="faq-title" itemprop="name">Яке {$category.name|lower} найпопулярніше у {$smarty.now|date_format:"%Y"} році?</div>
                                 <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                     <div itemprop="text">
                                         Наразі  найпопулярнішими моделями є:
                                         <ol>
                                             {foreach $seo_products as $seo_product}
                                                 <li>{$seo_product.name}</li>
                                             {/foreach}
                                         </ol>
                                     </div>
                                 </div>
                             </div>
                         {else}
                             <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                 <div class="faq-title" itemprop="name">Скільки коштують {$category.name|lower}?</div>
                                 <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                     <div itemprop="text">
                                         В магазині Vzuti можна купити оригінальне взуття {$category.name} за ціною від {$seo_min_price|intval}грн до {$seo_max_price|intval}грн.
                                     </div>
                                 </div>
                             </div>
                             <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                 <div class="faq-title" itemprop="name">Хочу купити нову пару {$category.name|lower}. Які моделі є в наявності?</div>
                                 <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                     <div itemprop="text">
                                         Магазин Vzuti пропонує купити наступні моделі цієї коллекції
                                         <ul>
                                             {foreach $seo_products_new as $seo_product_new}
                                                 <li>✓ {$seo_product_new.name} - ціна {$seo_product_new.price|intval} грн</li>

                                             {/foreach}
                                         </ul>
                                     </div>
                                 </div>
                             </div>
                             <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                 <div class="faq-title" itemprop="name">Які {$category.name|lower} найпопулярні у {$smarty.now|date_format:"%Y"} році?</div>
                                 <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                     <div itemprop="text">
                                         Наразі  найпопулярнішими моделями є:
                                         <ol>
                                             {foreach $seo_products as $seo_product}
                                                 <li>{$seo_product.name}</li>
                                             {/foreach}
                                         </ol>
                                     </div>
                                 </div>
                             </div>

                         {/if}


                         {if $totcustomfields_display_faqquestion1}
                             <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                 <div class="faq-title" itemprop="name">
                                     {$totcustomfields_display_faqquestion1 nofilter}
                                 </div>
                                 {if $totcustomfields_display_faqanswer1}
                                     <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                         <div itemprop="text">
                                             {$totcustomfields_display_faqanswer1 nofilter}
                                         </div>
                                     </div>
                                 {/if}
                             </div>
                         {/if}

                         {if $totcustomfields_display_faqquestion2}
                             <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                                 <div class="faq-title" itemprop="name">
                                     {$totcustomfields_display_faqquestion2 nofilter}
                                 </div>
                                 {if $totcustomfields_display_faqanswer2 }
                                     <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                                         <div itemprop="text">
                                             {$totcustomfields_display_faqanswer2 nofilter}
                                         </div>
                                     </div>
                                 {/if}
                             </div>
                         {/if}

                     </div>


                <!-- Питання та відповіді -->

        </div>
    {/if}
</div>
