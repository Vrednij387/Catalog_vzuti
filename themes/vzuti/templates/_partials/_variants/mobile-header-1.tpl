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

<div id="mobile-header-sticky">
    <div class="container">
        <div class="mobile-main-bar">
            <div class="row no-gutters align-items-center row-mobile-header">
                <div class="col col-auto col-mobile-btn col-mobile-btn-menu col-mobile-menu-{$iqitTheme.mm_type}">
                    <a class="m-nav-btn js-m-nav-btn-menu" data-toggle="dropdown" data-display="static">
                       {* <i class="fa fa-bars" aria-hidden="true"></i>*}
                        <img src="/themes/vzuti/assets/img/mobile_menu.svg" alt="burger" title="{l s="Подивитися меню"}">
                       {* <span>{l s='Menu' d='Shop.Theme.Global'}</span>*}
                    </a>
                    <div id="mobile_menu_click_overlay"></div>
                    <div id="_mobile_iqitmegamenu-mobile" class="dropdown-menu-custom dropdown-menu"></div>
                </div>
                <div id="mobile-btn-search" class="col col-auto col-mobile-btn col-mobile-btn-search">
                    <a class="m-nav-btn" data-toggle="dropdown" data-display="static">
                        {*<i class="fa fa-search" aria-hidden="true"></i>*}
                        <img src="/themes/vzuti/assets/img/mobile_search.svg" alt="шукати" title="{l s="Пошук"}">
                        {*<span>{l s='Search' d='Shop.Theme.Catalog'}</span>*}
                    </a>
                    <div id="search-widget-mobile" class="dropdown-content dropdown-menu dropdown-mobile search-widget">
                        {hook h="litespeedEsiBegin" m="iqitsearch" field="widget_block" tpl="module:iqitsearch/views/templates/hook/search-bar-mobile.tpl"}
                        {widget_block name="iqitsearch"}
                            {include 'module:iqitsearch/views/templates/hook/search-bar-mobile.tpl'}
                        {/widget_block}
                        {hook h="litespeedEsiEnd"}
                    </div>
                </div>
                <div class="col col-mobile-logo text-center">
                    {renderLogo}
                </div>
                <div class="col col-auto col-mobile-btn col-mobile-btn-account">
                    <a href="{$urls.pages.my_account}" class="m-nav-btn">
                        {*<i class="fa fa-user" aria-hidden="true"></i>*}
                        <img src="/themes/vzuti/assets/img/person.svg" alt="Увійти до особистого кабінету" title="Увійти до особистого кабінету">
                       {* <span>
                            {hook h="litespeedEsiBegin" m="ps_customersignin" field="widget_block" tpl="module:ps_customersignin/ps_customersignin-mobile.tpl"}
                            {widget_block name="ps_customersignin"}
                                {include 'module:ps_customersignin/ps_customersignin-mobile.tpl'}
                            {/widget_block}
                            {hook h="litespeedEsiEnd"}
                        </span>*}
                    </a>
                </div>
                {hook h='displayHeaderButtonsMobile'}
                {if !$configuration.is_catalog}
                <div class="col col-auto col-mobile-btn col-mobile-btn-cart ps-shoppingcart {if isset($iqitTheme.cart_style) && $iqitTheme.cart_style == "floating"}dropdown{else}side-cart{/if}">
                    <div id="mobile-cart-wrapper">
                    <a id="mobile-cart-toogle"  class="m-nav-btn" data-toggle="dropdown" data-display="static">
                       {* <i class="fa fa-shopping-bag mobile-bag-icon" aria-hidden="true"></i>*}
                        <img src="/themes/vzuti/assets/img/cart.svg" alt="Кошик" title="Переглянути товари у когшику">
                            <span id="mobile-cart-products-count" class="cart-products-count cart-products-count-btn">
                                {hook h="litespeedEsiBegin" m="ps_shoppingcart" field="widget_block" tpl="module:ps_shoppingcart/ps_shoppingcart-mqty.tpl"}
                                {widget_block name="ps_shoppingcart"}
                                    {include 'module:ps_shoppingcart/ps_shoppingcart-mqty.tpl'}
                                {/widget_block}
                                {hook h="litespeedEsiEnd"}
                            </span>
                       {* <span>{l s='Cart' d='Shop.Theme.Checkout'}</span>*}
                    </a>
                    <div id="_mobile_blockcart-content" class="dropdown-menu-custom dropdown-menu"></div>
                    </div>
                </div>
                {/if}
            </div>
        </div>
    </div>
</div>
{if !empty($cms_9)}
    <div class="marketing-baner">
        {$cms_9 nofilter}
    </div>
{/if}