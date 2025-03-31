{if isset($settings['hide_ship_pay']) && $settings['hide_ship_pay'] eq 1 && $address_selector == 'new'}
    <span class="permanent-warning" style="display: inline-block;">{l s='Please choose your shipping address first in order to check the payment methods.' mod='supercheckout'}</span>
{else}
<div class="velsof_sc_overlay"></div>
{if isset($payment_method_not_required)}
    <div class='supercheckout-checkout-content' style='display:block'>
        <div class='permanent-warning not-required-msg'>{l s='No payment method required.' mod='supercheckout'}</div>
    </div>
{elseif count($payment_methods) == 0}
    <div class='supercheckout-checkout-content' style='display:block'>
        <div class='permanent-warning not-required-msg'>{l s='No payment method is available.' mod='supercheckout'}</div>
    </div>
{else}
<ul class="payment-options">
    {foreach from=$payment_methods item="option"}
        <li style="display: contents;">
            <div class="radio ">
                <div id="{$option.id}-container">
                    <input type="radio" name="payment_method"  data-module-name="{$option.module_name nofilter}{*escape not required as contains html*}" value="{$option.id}" id="{$option.id}" {if $option.id_module == $selected_payment_method}checked="checked" {elseif $option.id == $selected_payment_method} checked="checked"{/if} class="{if $option.binary}binary{/if}"/>
                    {*if $display_payment_style neq *}
                        {if $option.payment_image_url neq ''}
                            <img src='{$option.payment_image_url}' alt='{$option.call_to_action_text}' {if isset($option.width) && $option.width !="" && $option.width !="auto"}width='{$option.width}'{else} width="50"{/if} {if isset($option.height) && $option.height !="" && $option.height !="auto"}height='{$option.height}'{/if}/>{if $display_payment_style neq 2}{/if}
                            {* Start Code Added By Priyanshu on 3-June-2020 to fix the Payment method logo issue*}
                        {else if $option.logo neq '' }
                            <img src='{$option.logo}' alt='{$option.call_to_action_text}' {if isset($option.width) && $option.width !="" && $option.width !="auto"}width='{$option.width}'{else} width="50"{/if} {if isset($option.height) && $option.height !="" && $option.height !="auto"}height='{$option.height}'{/if}/>{if $display_payment_style neq 2}{/if}
                            {* End of Code Added By Priyanshu on 3-June-2020 to fix the Payment method logo issue*}
                        {/if}
                    {*/if*}
                    <div class="payment_method_info">

                        <label id="payment_lbl_{$option.id_module|intval}" for="{$option.id}">

                            {if $display_payment_style neq 2}
                                {$option.call_to_action_text}
                            {/if}
                        </label>
                        <form method="GET" class="ps-hidden-by-js" style="display: none">
                            <button class="ps-hidden-by-js" type="submit" name="select_payment_option" value="{$option.id}"></button>
                        </form>
                        <div id="pay-with-{$option.id}-form" class="js-payment-option-form ps-hidden ">
                            <form class="stripe-payment-form" id="stripe-card-payment">
                                <button style="display:none" id="pay-with-{$option.id}" type="submit"></button>
                            </form>
                        </div>
                        <div class="payment_method_description {$option.module_name}">
                            {if $option.module_name == "ps_cashondelivery_DISABLE"}
                                <ul>
                                    <li>{l s='Додатково клієнтом оплачується доставка та комісія Нової Пошти за грошовий переказ (20 грн+2% від суми).'}</li>
                                </ul>
                                {*<p>Реквізити для оплати зʼявляться на сторінці після оформлення замовлення та будуть відправлені на емейл.</p>*}
                            {elseif $option.module_name == "ps_cashondelivery2_DISABLE"}
                                <ul>
                                    <li>{l s='Послуги доставки за рахунок клієнта згідно тарифів Нової Пошти.'}</li>
                                </ul>
                                <p>Реквізити для оплати зʼявляться на сторінці після оформлення замовлення та будуть відправлені на емейл.</p>
                            {elseif  $option.module_name == "fondy_DISABLE"}
                                <ul>
                                    <li>{l s='Клієнт оплачує лише вартість доставки згідно тарифів Нової Пошти'}</li>
                                </ul>
                            {elseif  $option.module_name == "ecm_liqpay_DISABLE"}
                                <ul>
                                    <li>{l s='Клієнт оплачує вартість доставки згідно тарифів Нової Пошти'}</li>
                                </ul>
                            {elseif  $option.module_name == "ecm_monopay_DISABLE"}
                                <ul>
                                    <li>{l s='Клієнт оплачує лише вартість доставки згідно тарифів Нової Пошти'}</li>
                                </ul>
                            {/if}

                            <ul>
                                <li>{$option.call_to_action_description nofilter}</li>
                            </ul>
                        </div>
                        <div class="paymentInfo" id="payment_methods_additional_container">
                            <div id="{$option.id}-additional-information" class="{$option.id}_info_container payment-additional-info" style="display:none;">
                                <div class="supercheckout-blocks js-additional-information definition-list additional-information">
                                    {*$option.additionalInformation nofilter*}{*escape not required as contains html*}

                                </div>
                                {if $option.form}
                                    {$option.form nofilter}{*escape not required as contains html*}
                                {else}
                                    <form id="payment-form" method="POST" action="{$option.action nofilter}{*escape not required as contains url*}">
                                        {foreach from=$option.inputs item=input}
                                            <input type="{$input.type}" name="{$input.name}" value="{$input.value}">
                                        {/foreach}
                                        <button style="display:none" id="pay-with-{$option.id}" type="submit"></button>
                                    </form>
                                {/if}

                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </li>
        {/foreach}
    </ul>
        
<div id="payment-confirmation" style="display: none">
    <div class="ps-shown-by-js"> 
        <button type="submit" class="btn btn-primary center-block"> {l s='PASSER LA COMMANDE' mod='supercheckout'} </button>
    </div>
    <div class="ps-hidden-by-js"></div>
</div>

    <div id="payment_methods_binaries" style="display:none;">
        {hook h='displayPaymentByBinaries'}
    </div>
{/if}
{/if}
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