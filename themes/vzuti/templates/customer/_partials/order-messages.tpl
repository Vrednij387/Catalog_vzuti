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
{block name='order_messages_table'}
  {if $order.messages}
    <div class="box messages">
      <h3>{l s='Messages' d='Shop.Theme.Customeraccount'}</h3>
      {foreach from=$order.messages item=message}
        <div class="message row">
          <div class="col-sm-4">
            {$message.name}<br/>
            {$message.message_date}
          </div>
          <div class="col-sm-8">
           {$message.message|escape:'html'|nl2br nofilter}
          </div>
        </div>
      {/foreach}
    </div>
  {/if}
{/block}

{block name='order_message_form'}
  <div class="order-message-form box">
    <form action="{$urls.pages.order_detail}" method="post">

      <header>
        <strong>{l s='Add a message' d='Shop.Theme.Customeraccount'}</strong>
        <p>{l s='If you would like to add a comment about your order, please write it in the field below.' d='Shop.Theme.Customeraccount'}</p>
      </header>

      <div class="form-fields">

        <div class="form-group row">
          <label class="col-md-3 form-control-label">{l s='Product' d='Shop.Forms.Labels'}</label>
          <div class="col-md-5">
            <select name="id_product" class="form-control form-control-select" data-role="product">
              <option value="0">{l s='-- please choose --' d='Shop.Forms.Labels'}</option>
              {foreach from=$order.products item=product}
                <option value="{$product.id_product}">{$product.name}</option>
              {/foreach}
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 form-control-label"></label>
          <div class="col-md-9">
            <textarea rows="3" name="msgText" class="form-control" data-role="msg-text"></textarea>
          </div>
        </div>

      </div>

      <footer class="form-footer text-center text-md-right">
        <input type="hidden" name="id_order" value="{$order.details.id}">
        <button type="submit" name="submitMessage" class="btn btn-primary form-control-submit">
          {l s='Send' d='Shop.Theme.Actions'}
        </button>
      </footer>

    </form>
  </div>
{/block}
