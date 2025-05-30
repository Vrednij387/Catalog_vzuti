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
{extends file=$layout}

{block name='content'}

  <section id="main" class="cms_page">

    {*{block name='page_header_container'}
      {block name='page_title' hide}
        <header class="page-header">
            <h1 class="h1 page-title"><span>{$smarty.block.child}</span></h1>
        </header>
      {/block}
    {/block}*}
      {block name='page_title' hide}
          <header class="page-header">
          {if $cms.link_rewrite != "contacts"}
              {if $cms.link_rewrite == "pro_vtuti"}
                  <h1 class="h1 page-title">Про <span class="bold">VZUTI</span></h1>
              {else}
                  <h1 class="h1 page-title">{$smarty.block.child}</h1>
              {/if}
          {/if}
          </header>
      {/block}

    {block name='page_content_container'}
      <div id="content" class="page-content">

        {block name='page_content_top'}{/block}
        {block name='page_content'}

          <!-- Page content -->
        {/block}
      </div>
    {/block}

    {block name='page_footer_container'}
      <footer class="page-footer">
        {block name='page_footer'}
          <!-- Footer content -->
        {/block}
      </footer>
    {/block}

  </section>

{/block}
