 <div id="blockcart" class="blockcart cart-preview"
         data-refresh-url="{$refresh_url}">
        <a id="cart-toogle" class="cart-toogle header-btn header-cart-btn" data-toggle="dropdown" data-display="static">
           {* <i class="fa fa-shopping-bag fa-fw icon" aria-hidden="true"></i>*}
            <img src="/themes/vzuti/assets/img/cart.svg" alt="{l s='Кошик'}" title="{l s='Переглянути товари у когшику'}">
            <span class="cart-products-count-btn {if $cart.products_count < 1} d-none{/if}">{$cart.products_count}</span>
            <span class="info-wrapper">
           {* <span class="title">{l s='Cart' d='Shop.Theme.Checkout'}</span>*}
            <span class="cart-toggle-details">
            <span class="text-faded cart-separator"> / </span>
            {if $cart.products_count > 0}
            <span class="cart-products-count">({$cart.products_count})</span>
            {foreach from=$cart.subtotals item="subtotal"}
                {if isset($subtotal.type) && $subtotal.type == 'products'}
                        <span class="value">{$subtotal.value}</span>
                {/if}
            {/foreach}
            {else}
                {l s='Empty' d='Shop.Theme.Checkout'}
            {/if}
            </span>
            </span>
        </a>
        {include 'module:ps_shoppingcart/ps_shoppingcart-content.tpl' class='dropdown'}
 </div>




