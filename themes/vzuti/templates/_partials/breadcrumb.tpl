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

{if $breadcrumb.count > 1}
{if $iqitTheme.bread_width == 'inherit'}<div class="container">{/if}

<nav data-depth="{$breadcrumb.count}" class="breadcrumb {if $smarty.server.REMOTE_ADDR == '31.202.82.208' && !empty($cms_9)}page_with_message{/if}">
    {if $iqitTheme.bread_width == 'fullwidth'}
        <div class="container-fluid">
    {elseif $iqitTheme.bread_width == 'fullwidth-bg'}
        <div class="container">
    {/if}
            <div class="row align-items-center">
                <div class="col">
                    <ol>
                        {block name='breadcrumb'}
                            {foreach from=$breadcrumb.links item=path name=breadcrumb}



                                 {block name='breadcrumb_item'}
                                    {if not $smarty.foreach.breadcrumb.last}
                                        <li>
                                            <a href="{$path.url}"><span>{$path.title}</span></a>
                                        </li>
                                    {elseif isset($path.title)}
                                        <li>
                                            <span>{$path.title}</span>
                                        </li>
                                    {/if}
                                {/block}

                            {/foreach}
                        {/block}
                    </ol>
                </div>
                <div class="col col-auto"> {hook h='displayAfterBreadcrumb'}</div>
            </div>
            {if $iqitTheme.bread_width == 'fullwidth' || $iqitTheme.bread_width == 'fullwidth-bg'}
        </div>
        {/if}
</nav>

{if $iqitTheme.bread_width == 'inherit'}</div>{/if}
{/if}
