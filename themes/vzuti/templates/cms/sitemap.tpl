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
{extends file='page.tpl'}

{block name='page_title'}
  {l s='Sitemap' d='Shop.Theme.Global'}
{/block}

{block name='page_content_container'}

  <div class="row sitemap ">
    {foreach $sitemapUrls as $group}
      <div class="col block-links">
        {*<h2 class="block-title"><span>{$group.name}</span></h2>*}
        {include file='cms/_partials/sitemap-nested-list.tpl' links=$group.links}
      </div>
    {/foreach}
	
	{if !empty($products)}
    <div class="col-12 sitemap_products table-container">
      <hr>
      <ul class="sitemap_products flex-table">
        {foreach $products as $product}
          <li class="col block-links">
            <a href="{$product.product_link}" title="{$product.name_product}">
              {$product.name_category} {$product.name_product} {$product.reference}
            </a>
          </li>
        {/foreach}
      </ul>
    </div>
	{/if}
	
  </div>
{/block}