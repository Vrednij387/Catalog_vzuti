{*changes done by kanishka kannoujia to show banner on the basis of the country selected by the admin*}    
{*{if isset($kb_free_shipping_percent)}*}
    {if isset($kb_free_shipping_percent) && isset($show_banner) && $show_banner}
        {*changes done by kanishka kannoujia to show banner on the basis of the country selected by the admin*}    
        <div class="" id="kb_cart_summary_free_shipping">
                    {if $hidden_amount == 0}
                        <h3>{l s='Congratulations!! You have reached the minumum amount limit to get Free Shipping.' mod='supercheckout'} </h3>
                    {else}
                        <h3>{l s='Almost there, Add ' mod='supercheckout'} {$kb_free_shipping_amount} {l s=' more to get Free Shipping' mod='supercheckout'}</h3>
                    {/if}
                    <div class="progress red">
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar"
                             aria-valuenow="{$kb_free_shipping_percent}" aria-valuemin="0" aria-valuemax="100" style="width:{$kb_free_shipping_percent}%">
                            {$kb_free_shipping_percent}% {l s='Complete (success) ' mod='supercheckout'} 
                        </div>
                    </div>
            </div>
    {/if}
    
    <div id="confirmCheckout" class="shopping-cart-totals">
            {assign var='image_display' value=0}
            {assign var='odd' value=0}
            {assign var='have_non_virtual_products' value=false}
            {if $logged}
                        {assign var='image_display' value=$settings['cart_options']['product_image']['logged']['display']}
            {else}
                {assign var='image_display' value=$settings['cart_options']['product_image']['guest']['display']}
            {/if}
            {foreach $products as $product}
                
                {if $product.is_virtual == 0}
                    {assign var='have_non_virtual_products' value=true}
                {/if}
                {assign var='productId' value=$product.id_product}
                {assign var='product_url' value=$link->getProductLink($product.id_product, $product.link_rewrite, $product.category, null, null, $product.id_shop, $product.id_product_attribute)}
                {assign var='productAttributeId' value=$product.id_product_attribute}
                {assign var='odd' value=($odd+1)%2}
            <div class="row cart_list_item" id="product_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}">
                {if $image_display eq 1}
                <div class="col-md-4 col-xs-2 text-md-center Cart-product-Image">
                        {* changes done by Kanishka to display variant image of the product in the cart.*}
                        {if !empty($product.default_image.bySize.large_default.url)}
                            <img class="product_img img-responsive" {*width='{$settings['cart_image_size']['width']}' height='{$settings['cart_image_size']['height']}'*} src="{$product.default_image.bySize.large_default.url|escape:'quotes'}" alt='{$product.name|escape:'quotes'}' onclick="showEnlargedImage(this)"/>
                        {else}
                            <img class="product_img img-responsive" {*width='{$settings['cart_image_size']['width']}' height='{$settings['cart_image_size']['height']}'*} src="{$product.cover.bySize.large_default.url|escape:'quotes'}" alt='{$product.name|escape:'quotes'}' onclick="showEnlargedImage(this)"/>
                        {/if}
                        
                        
                        {* changes done by Kanishka to display variant image of the product in the cart.*}
                </div>
                {/if}
                <div class="col-md-8 col-xs-10 shopping-cart-description">
                    <p class="product-title">
                        <a href="{$product_url|escape:'quotes'}">{$product.name|escape:'quotes'}</a>
                    </p>
                    <p class="product_reference">
                        <label class="label">{l s='Reference' d='Shop.Theme.Catalog'}:   </label>
                        <span class="bold">{$product.reference_to_display}</span>
                    </p>
                    {if isset($product.attributes) && count($product.attributes) > 0}
                        <p class="product_sizes">
                            {foreach from=$product.attributes key="attribute" item="value"}
                                <label class="label">{l s="Розмір"}: </label>
                                <span class="bold">{$value}</span>
                            {/foreach}
                        </p>
                    {/if}

                    <div class="clear-mobile-div"></div>
                    <div class="cart_product_prices row">
                        <div class="col-md-4 col-xs-4 col-4 col-lg-4 col-xl-4 shopping-product-price" style="{if $logged}{if $settings['cart_options']['product_price']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['cart_options']['product_price']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
                        <span class="cart-product-price" id="">
                                <span class="unit-price-text">{l s='Ціна' mod='supercheckout'}:</span>
                                <span class="price special-price">{$product.price nofilter}</span> {*escape not required as contains html*}
                        </span>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xs-4 col-4 text-md-center quantity-section" style="{if $logged}{if $settings['cart_options']['product_qty']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['cart_options']['product_qty']['guest']['display'] eq 1}{else}display:none{/if}{/if};" >
                            <div class="input-group bootstrap-touchspin">
                                <div class="input-group">
                                    <input type="hidden" value="{$product.quantity|intval}" name="quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}_hidden" />
                                    <input type="hidden" name="quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}_minqty" value="{$product.minimal_quantity|intval}">
                                    {if isset($settings['qty_update_option']) && $settings['qty_update_option'] eq 0 }
                                        <span class="input-group-btn">
                                            <button type="button" class="cart_quantity_down qty-btn increase_button quantity-left-minus btn btn-primary btn-number" data-type="plus" data-field="" onclick="downQty('quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}')">

                                                        {*<span class="fas fa-chevron-down"></span>*}
                                                <span>-</span>
                                                </button>
                                        </span>
                                        <input min="1" max="100" autocomplete="off" type="text" id="quantity" class="form-control input-number quantitybox" name="quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}" value="{$product.quantity|intval}">
                                        <span class="input-group-btn">
                                                <button type="button" class="cart_quantity_down qty-btn decrease_button quantity-right-plus btn btn-primary btn-number" data-type="plus" data-field=""  onclick="upQty('quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}')">
                                                        {*<span class="fas fa-chevron-up"></span>*}
                                                    <span>+</span>
                                                </button>
                                        </span>
                                    {else}
                                        <input min="1" max="100" autocomplete="off" type="text" id="quantity" class="form-control input-number quantitybox kb_text_update_qty" name="quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}" value="{$product.quantity|intval}">
                                        <a href="javascript:void(0)" id="demo_2_s" class="kb_update_link" title="{l s='update quantity' mod='supercheckout'}" onclick="updateQtyByBtn('quantity_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}')" ><small>{l s='Update' mod='supercheckout'}</small></a>
                                    {/if}

                                </div>

                                <a id="{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}_{$product.id_customization|intval}" onclick="deleteProductFromCart(this.id);" class="remove-from-cart" rel="nofollow" href="#" style="{if $logged}{if $settings['cart_options']['product_name']['logged']['display'] eq 1 || $settings['cart_options']['product_model']['logged']['display'] eq 1 || $settings['cart_options']['product_qty']['logged']['display'] eq 1 || $settings['cart_options']['product_price']['logged']['display'] eq 1 || $settings['cart_options']['product_total']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['cart_options']['product_name']['guest']['display'] eq 1 || $settings['cart_options']['product_model']['guest']['display'] eq 1 || $settings['cart_options']['product_qty']['guest']['display'] eq 1 || $settings['cart_options']['product_price']['guest']['display'] eq 1 || $settings['cart_options']['product_total']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
                                    {*<i class="fas fa-trash"></i>*}
                                    <img src="/themes/vzuti/assets/img/chekout_trush.svg" alt="Видалити" title="Видалити товар з кошика">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-4 col-4 text-md-right text-xs-right text-sm-right productTotalSection" id="total_product_price_{$product.id_product|intval}_{$product.id_product_attribute|intval}_{$product.id_address_delivery|intval}" style="{if $logged}{if $settings['cart_options']['product_total']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['cart_options']['product_total']['guest']['display'] eq 1}{else}display:none{/if}{/if};" >

                            <span class="total cart-product-price text-right">{$product.total nofilter}</span>{*escape not required as contains html*}
                        </div>

                    </div>


                </div>
                 

                </div>
            {/foreach}
    </div>
<div id="confirmCheckout">
<script type="text/javascript">
    var subtotal_msg = "{l s='Subtotal' mod='supercheckout'}";
    var shipping_msg = "{l s='Shipping' mod='supercheckout'}";
    var taxex_msg = "{l s='Taxes' mod='supercheckout'}";
</script>

<div class="velsof_sc_overlay"></div>

{*<div class="row cart_list_item">
    
</div>*}

{*<table class="supercheckout-totals table table-bordered totalTable">

    <tfoot>
        {foreach from=$subtotals item="subtotal"}
            {if isset($subtotal.value) && $subtotal.value}
                {if $subtotal.type == 'products'}
                    <tr id="supercehckout_summary_total_{$subtotal.type}" style="{if $logged}{if $settings['order_total_option']['product_sub_total']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['product_sub_total']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
                    {else if $subtotal.type == 'shipping'}
                    <tr id="supercehckout_summary_total_{$subtotal.type}" style="{if $logged}{if $settings['order_total_option']['shipping_price']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['shipping_price']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
                    {else if $subtotal.type == 'tax'}
                    <tr id="supercehckout_summary_total_{$subtotal.type}" style="{if $logged}{if $settings['order_total_option']['total_tax']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['total_tax']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
                    {else}
                    <tr id="supercehckout_summary_total_{$subtotal.type}">
                    {/if}
                    <td colspan="5" class="text-right title"><strong>{l s=$subtotal.label mod='supercheckout'}: </strong></td>
                    <td class="value text-right"><span id="supercehckout_total_{$subtotal.type}_value" class="price">{$subtotal.value nofilter}</span></td><span style="margin-left:4%;">
                </tr>
            {/if}
        {/foreach}
    </tfoot>
</table>*}

<div class="custom-panel rewardsection">
    {if $vouchers.allowed}
        {if $vouchers.added}
            <p>{l s='Вартість замовлення'}:   <span style="margin-left:2%;">{$subtotals["products"]["value"]}</span></p>
        {/if}
        {foreach $vouchers.added as $voucher}
            <div style="margin-bottom: 4%;" id="cart_discount_{$voucher.id_cart_rule}" class="cart_discount text-right" style="{if $logged}{if $settings['order_total_option']['voucher']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['voucher']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
                <span style="float:left;"><b>{$voucher.name}</b></span><a href="javascript:void(0)" style="float: left;margin-left: 2%;margin-right: 4%;" onclick="removeDiscount('{$voucher.id_cart_rule|intval}')"><div title="{l s='Redeem' mod='supercheckout'}" class="removeProduct">
                        {*<i class="fas fa-trash"></i>*}
                        [{l s="видалити знижку"}]
                        {*<img src="/themes/vzuti/assets/img/chekout_trush.svg" alt="Видалити" title="Видалити товар з кошика">*}
                    </div>
                </a>
                <span class="price text-right">{$voucher.reduction_formatted nofilter}{*escape not required as contains html*}</span>                            
            </div>
        {/foreach}

        <div class="rewardBody">
            <div id="supercheckout_voucher_input_row" class="form-group form-coupon" style="{if $logged}{if $settings['order_total_option']['voucher']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['voucher']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
                <div class="input-group" id="voucher-form">
                    <input type="hidden" value="1" name="submitDiscount">
                    <input name="discount_name" id="discount_name" type="text" placeholder="{l s='Enter your coupon here' mod='supercheckout'}" class="voucherText form-control">
                    <span class="input-group-btn"><button id="button-coupon" onClick="callCoupon();" type="button" data-loading-text="Loading..." class="btn btn-primary orangebuttonapply" style="min-height: 33px;">{l s='Apply' mod='supercheckout'}</button>
                    </span>
                </div>
            </div>
        </div>
    {else}
        <div id="supercheckout_voucher_input_row" style="display:none;"></div>
    {/if}

    {if isset($total_price_display_method) && $total_price_display_method == 0}
        <div class="totalAmount row" style="{if $logged}{if $settings['order_total_option']['total']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['total']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
            <div class="col-md-4"></div>
            <div class="col-md-8">
                <p class="cart_total_price">
                    {if $priceDisplay == 1}
                        <span class="label">{l s='Загальна вартість замовлення' mod='supercheckout'}:</span>
                        <span id="total_price" class="price amountMoney">{$totals.total.value nofilter}</span>
                        <input type="hidden" id="total_price_wfee" value="{$totals.total.value}"></td>
                    {else}
                        <span class="label">{l s='Загальна вартість замовлення' mod='supercheckout'}:</span>
                        <span id="total_price" class="price amountMoney">{$totals.total.value nofilter}</span>
                        <input type="hidden" id="total_price_wfee" value="{$totals.total.value}">
                    {/if}
                </p>
            </div>

        </div>
        {*
    {elseif isset($total_price_display_method) && $total_price_display_method == 1}
        <div class="totalAmount" style="{if $logged}{if $settings['order_total_option']['total']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['total']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
            <p class="cart_total_price">
                <span>{l s='Загальна вартість замовлення' mod='supercheckout'}:</span>
                <span id="total_price" class="price amountMoney">{$totals.total_including_tax.value nofilter}</span>
                <input type="hidden" id="total_price_wfee" value="{$totals.total_including_tax.value}">
            </p>
        </div><br>
    {elseif isset($total_price_display_method) && $total_price_display_method == 2}
        <div class="totalAmount" style="{if $logged}{if $settings['order_total_option']['total']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['total']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
            <h3>
                {l s='Total Amount' mod='supercheckout'} {l s='(Tax excl.)' mod='supercheckout'}:
                <span id="total_price" class="price amountMoney">{$totals.total_excluding_tax.value nofilter}</span>
                <input type="hidden" id="total_price_wfee" value="{$totals.total_excluding_tax.value}"></td>
            </h3>
        </div><br>
    {elseif isset($total_price_display_method) && $total_price_display_method == 3}
        <div class="totalAmount" style="{if $logged}{if $settings['order_total_option']['total']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['total']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
           <h3>
                {l s='Total Amount' mod='supercheckout'} {l s='(Tax excl.)' mod='supercheckout'}:
                <span id="total_price" class="price amountMoney">{$totals.total_excluding_tax.value nofilter}</span>
                <input type="hidden" id="total_price_wfee_exclusive" value="{$totals.total_excluding_tax.value}"></td>
            </h3>
        </div>
        <div class="totalAmount" style="{if $logged}{if $settings['order_total_option']['total']['logged']['display'] eq 1}{else}display:none{/if}{else}{if $settings['order_total_option']['total']['guest']['display'] eq 1}{else}display:none{/if}{/if};">
            <h3>
                {l s='Total Amount' mod='supercheckout'} {l s='(Tax incl.)' mod='supercheckout'}:
                <span id="total_price" class="price amountMoney">{$totals.total_including_tax.value nofilter}</span>
                <input type="hidden" id="total_price_wfee_inclusive" value="{$totals.total_including_tax.value}">
            </h3>
        </div>
        <br>
        *}
    {/if}
    {* End Code Added By Priyanshu on 11-Feb-2021 to implement the Total Price Display functionality*}
</div>
    {block name="product_managment_info"}
        <div class="advantages">
            <p class="advantage-original">{l s='Тільки оригінал'}</p>
            <p class="advantage-delivery">{l s='Доставка 1-2 дні'}</p>
            <p class="advantage-exchange"><a href="/content/3-garantia">{l s='Легкий обмін та повернення'}</a></p>
            <p class="advantage-conditions">
                <a href="/content/2-dostavka">{l s='Умови оплати,доставки та повернення'}</a>
            </p>
        </div>

    {/block}


<div id="highlighted_cart_rules">
    {if count($other_available_vouchers) > 0}
        <p id="title" class="title-offers" style="font-weight: 600;color: black!important;">{l s='Take advantage of our exclusive offers' mod='supercheckout'}:</p>
        <div id="display_cart_vouchers">
            {foreach $other_available_vouchers as $voucher}
                {if $voucher.code != ''}<span onclick="$('#discount_name').val('{$voucher.code}');
                        return false;" class="voucher_name" data-code="{$voucher.code}">{$voucher.code}</span> - {/if}{$voucher.name}<br />
                    {/foreach}
                    </div>
                    {/if}
                    </div>

                    <!-- INSERT INTO #CART BLOCK -->
                    <!-- Start - Code to insert custom fields in cart block -->
                    <div class="div_custom_fields">
                        {foreach from=$array_fields item=field}
                            {if $field['position'] eq 'cart_block'}
                                <div class="supercheckout-blocks form-group">
                                    {if $field['type'] eq "textbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input type="text" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 form-control">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "textarea"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <textarea name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" class="supercheckout-large-field width_100 form-control" style="width: 100%; height: 100px;">{$field['default_value']}</textarea>
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "selectbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <select name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" class="supercheckout-large-field width_100 form-control">
                                            <option value="">{l s='Select Option' mod='supercheckout'}</option>
                                            {foreach from=$field['options'] item=field_options}
                                                <option {if $field_options['default_value'] eq $field_options['option_value']}selected{/if} value="{$field_options['option_value']}">{$field_options['option_label']}</option>
                                            {/foreach}
                                        </select>
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "radio"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        {assign var=radio_counter value=1}
                                        {foreach from=$field['options'] item=field_options}
                                            <div class="supercheckout-extra-wrap">
                                                <div class="radio" id="uniform-field_{$field['id_velsof_supercheckout_custom_fields']}"><span>
                                                        <input type="radio" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field_options['option_value']}" {if $field_options['default_value'] eq $field_options['option_value']}checked{/if}>
                                                        <label for="field_{$field['id_velsof_supercheckout_custom_fields']}">{$field_options['option_label']}</label>
                                                    </span></div>
                                                
                                            </div>
                                            {assign var=radio_counter value=$radio_counter+1}
                                        {/foreach}
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {if $field['type'] eq "checkbox"}
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        {foreach from=$field['options'] item=field_options}
                                            <div class="input-box input-field_{$field['id_velsof_supercheckout_custom_fields']}">
                                                <div class="checker checkbox" id="uniform-field_{$field['id_velsof_supercheckout_custom_fields']}">
                                                    <span class="checked">
                                                        <input {if $field_options['default_value'] eq $field_options['option_value']}checked{/if} type="checkbox" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}][]" value="{$field_options['option_value']}">
                                                        <label for="field_{$field['id_velsof_supercheckout_custom_fields']}"><b>{$field_options['option_label']}</b></label>
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        {/foreach}
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                    {/if}

                                    {* Start: Code added by Anshul for date field *}
                                    {if $field['type'] eq "date"}                         
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input style="position: relative;" type="text" id="" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 kb_sc_custom_field_date form-control" readonly="true">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                        {if isset($field['validation_type']) && $field['validation_type'] == 'isDate'}
                                            <span style="color:#999999">
                                                {l s='Date format is Y-m-d' mod='supercheckout'}
                                            </span>
                                        {/if}
                                    {/if}
                                    {* Code added by Anshul for date field *}

                                    {* Start: Code added by Anshul for file field *}
                                    {if $field['type'] eq "file"}                         
                                        <label class="cursor_help" title="{$field['field_help_text']}">{$field['field_label']}{if $field['required'] eq "1"}<span style="display:inline;" class="supercheckout-required">*</span>{/if}</label>
                                        <input type="file" data-buttonText="{l s='Choose file' mod='supercheckout'}" id="kb_sc_custom_field_file_{$field['id_velsof_supercheckout_custom_fields']}" name="custom_fields[field_{$field['id_velsof_supercheckout_custom_fields']}]" value="{$field['default_value']}" class="supercheckout-large-field width_100 kbfiletype form-control">
                                        <span id="error_field_{$field['id_velsof_supercheckout_custom_fields']}" class="errorsmall_custom hidden_custom"></span>
                                        {if isset($field['validation_type']) && $field['validation_type'] == 'isFile'}
                                            <span style="color:#999999">
                                                {l s='Supported file formats are PDF, JPEG, PNG, DOCX, CSV & GIF.' mod='supercheckout'}
                                            </span>
                                        {/if}
                                    {/if}
                                    {* Code added by Anshul for file field *}
                                </div>
                            {/if}
                        {/foreach}
                    </div>
        </div>
                    <!-- End - Code to insert custom fields in registration form block -->

                    {*
                    * DISCLAIMER
                    *
                    * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
                    * versions in the future. If you wish to customize PrestaShop for your
                    * needs please refer tohttp://www.prestashop.com for more information.
                    * We offer the best and most useful modules PrestaShop and modifications for your online store.
                    *
                    * @category  PrestaShop Module
                    * @author    knowband.com <support@knowband.com>
                    * @copyright 2016 Knowband
                    *}