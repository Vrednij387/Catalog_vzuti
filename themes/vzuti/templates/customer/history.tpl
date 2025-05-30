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
{extends file='customer/page.tpl'}

{block name='page_title'}
  {l s='Order history' d='Shop.Theme.Customeraccount'}
{/block}

{block name='page_content'}
  <h6>{l s='Here are the orders you\'ve placed since your account was created.' d='Shop.Theme.Customeraccount'}</h6>

  {if $orders}
    <table class="table table-striped table-bordered table-labeled table-responsive hidden-sm-down">
      <thead class="thead-default">
        <tr>
          <th>{l s='№' d='Shop.Theme.Checkout'}</th>
          <th>{l s='Дата' d='Shop.Theme.Checkout'}</th>
          <th>{l s='Сума' d='Shop.Theme.Checkout'}</th>
          <th class="hidden-md-down">{l s='Payment' d='Shop.Theme.Checkout'}</th>
          {*<th class="hidden-md-down text-center">{l s='Status' d='Shop.Theme.Checkout'}</th>*}
          <th class="adreses">{l s='Доставка' d='Shop.Theme.Checkout'}</th>
         {* <th class="text-center">{l s='Invoice' d='Shop.Theme.Checkout'}</th>*}
          <th>&nbsp;-</th>
        </tr>
      </thead>
      <tbody>
        {foreach from=$orders item=order}
          <tr>
            <th scope="row">{$order.details.reference}</th>
            <td>{$order.details.order_date}</td>
            <td class="text-xs-right">{$order.totals.total.value}</td>
           <td class="hidden-md-down">{$order.details.payment}</td>
             {*
             <td class="text-center">
              <span
                class="label label-pill {$order.history.current.contrast}"
                style="background-color:{$order.history.current.color}"
              >
                {$order.history.current.ostate_name}
              </span>
            </td>
            *}
            <td>
              <b>{$order.carrier.name}</b><br>
              {*$order.addresses.delivery.alias*}
              {$order.addresses.delivery.formatted nofilter}
            </td>
             {* <td class="text-center">
                {if $order.details.invoice_url}
                  <a href="{$order.details.invoice_url}"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                {else}
                  -
                {/if}
              </td>*}
            <td class="text-center order-actions">
              <a href="{$order.details.details_url}" data-link-action="view-order-details">
                {l s='Details' d='Shop.Theme.Customeraccount'}
              </a>
              {if $order.details.reorder_url && false}
                ​<hr />
                <a href="{$order.details.reorder_url}">{l s='Reorder' d='Shop.Theme.Actions'}</a>
              {/if}
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>

    <div class="orders hidden-md-up">
      {foreach from=$orders item=order}
        <div class="order">
          <div class="row">
            <div class="col-12">
              <a href="{$order.details.details_url}"><h3>Замовлення №{$order.details.reference}</h3></a>
              <div class="date mb-3">Дата замовлення - {$order.details.order_date}</div>
              <div class="total mb-3">Сума - {$order.totals.total.value}</div>
              <div class="order-details-payment">{$order.details.payment}</div>
             {* <div class="status">
                <span
                  class="label label-pill {$order.history.current.contrast}"
                  style="background-color:{$order.history.current.color}"
                >
                  {$order.history.current.ostate_name}
                </span>
              </div>*}
             {* <div class="payment-details">
                {$order.addresses.delivery.formatted nofilter}
              </div>*}
              <div class="mob_order-details">
                <a href="{$order.details.details_url}" data-link-action="view-order-details">
                  {l s='Деталі' d='Shop.Theme.Customeraccount'}
                </a>
              </div>

             {* <div class="invoice">
              {if $order.details.invoice_url}
                <a href="{$order.details.invoice_url}"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> {l s='Invoice' d='Shop.Theme.Checkout'}</a>
              {/if}
              </div>*}

            </div>
          </div>
        </div>
      {/foreach}
    </div>

  {/if}
{/block}
