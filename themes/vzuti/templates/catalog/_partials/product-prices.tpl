
    <div class="product-prices js-product-prices">

        {block name='product_reference'}
            {if $iqitTheme.pp_reference == 'title'}
            {if isset($product.reference_to_display) && $product.reference_to_display neq ''}
                <div class="product-reference mb-md-4 mb-3">
                    <label class="label">{l s='Reference' d='Shop.Theme.Catalog'}:   </label>
                    <span>{$product.reference_to_display}</span>
                </div>
            {/if}
            {/if}
        {/block}

        {if $product.show_price}

        {block name='product_price'}
            <div class="product_prices {if $product.has_discount}has-discount{/if}">
                    <span class="current-price"><span class="product-price current-price-value" content="{$product.rounded_display_price}">
                          {capture name='custom_price'}{hook h='displayProductPriceBlock' product=$product type='custom_price' hook_origin='product_sheet'}{/capture}
                            {if '' !== $smarty.capture.custom_price}
                                {$smarty.capture.custom_price nofilter}
                            {else}
                                {$product.price}
                            {/if}
                        </span></span>
                    {if $product.has_discount}
                        <span class="product-discount">
                            {hook h='displayProductPriceBlock' product=$product type="old_price"}
                            <span class="regular-price">{$product.regular_price}</span>
                         </span>
                        {if $product.discount_type === 'percentage'}
                            <span class="badge badge-discount discount discount-percentage">-{$product.discount_percentage_absolute}</span>
                        {else}
                            <span class="badge badge-discount discount discount-amount">
                              -{$product.discount_to_display}%
                            </span>
                        {/if}

                        {if isset($product.specific_prices.to) && $product.specific_prices.to != '0000-00-00 00:00:00'}<meta itemprop="priceValidUntil" content="{$product.specific_prices.to}"/>{/if}
                    {/if}

                {block name='product_unit_price'}
                    {if $displayUnitPrice}
                        <p class="product-unit-price text-muted">{$product.unit_price_full}</p>
                    {/if}
                {/block}
            </div>
        {/block}

        {block name='product_coupon'}
    {if !empty($coupon) && $product.available_for_order}
        {*
        <div class="product-coupon mb-md-4 mb-3">
            <p class="promo_code">
                {strip}
                    Купон.Назва: <strong>-
                    {if $coupon.reduction_percent|floatval}
                        Купон.Знижка
                    {elseif $coupon.reduction_amount|floatval}
                        {$coupon.reduction_amount|floatval}
                    {/if}
                    {if $coupon.reduction_percent|floatval}
                        %
                    {elseif $coupon.reduction_amount|floatval}
                        {l s="грн"}
                    {/if}
                </strong> {l s="за промокодом"} <strong>Купон.Код</strong>
                {/strip}
            </p>
            {if $coupon.code}
                <p class="promo_info">
                    {l s="* Умови"}: Купон.Опис
                </p>
            {/if}
        </div>
        *}

        {if $coupon.show_banner == 1}
            <div class="product-coupon mb-md-4 mb-3">
                <p class="promo_code">
                    {strip}
                        {$coupon.name}: <strong>-
                            {if $coupon.reduction_percent|floatval}
                                {$coupon.reduction_percent|floatval}
                            {elseif $coupon.reduction_amount|floatval}
                                {$coupon.reduction_amount|floatval}
                            {/if}
                            {if $coupon.reduction_percent|floatval}
                                %
                            {elseif $coupon.reduction_amount|floatval}
                                {l s="грн"}
                            {/if}
                        </strong> {l s="за промокодом"} <strong>{$coupon.code}</strong>
                    {/strip}
                </p>
                {if $coupon.code}
                    <p class="promo_info">
                        {l s="* Умови"}: {$coupon.description}
                    </p>
                {/if}
            </div>
        {/if}

    {/if}
{/block}
        {block name='product_without_taxes'}
            {if $priceDisplay == 2}
                <p class="product-without-taxes text-muted">{l s='%price% tax excl.' d='Shop.Theme.Catalog' sprintf=['%price%' => $product.price_tax_exc]}</p>
            {/if}
        {/block}

        {block name='product_pack_price'}
            {if $displayPackPrice}
                <p class="product-pack-price">
                    <span>{l s='Instead of %price%' d='Shop.Theme.Catalog' sprintf=['%price%' => $noPackPrice]}</span>
                </p>
            {/if}
        {/block}

        {block name='product_ecotax'}
            {if !$product.is_virtual && $product.ecotax.amount > 0}
                <p class="price-ecotax text-muted">{l s='Including %amount% for ecotax' d='Shop.Theme.Catalog' sprintf=['%amount%' => $product.ecotax.value]}
                    {if $product.has_discount}
                        {l s='(not impacted by the discount)' d='Shop.Theme.Catalog'}
                    {/if}
                </p>
            {/if}
        {/block}

        {hook h='displayProductPriceBlock' product=$product type="weight" hook_origin='product_sheet'}



        {hook h='displayCountDown'}
        {/if}
    </div>




