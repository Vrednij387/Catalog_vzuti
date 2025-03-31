{extends file='page.tpl'}
{block name='content'}
   <section id="content-hook_order_confirmation">
   <style>
   .bank-info .color-blue {
		color: #09F !important;
	}
	.bank-info span {
		font-weight: 600;
		display: inline-block;
	}
	.bank-info a:hover {
		text-decoration: underline !important;
	}
	.bank-info .btn-copy {
		cursor: pointer;
	}
	.bank-info .copied-txt {
		opacity: 0;
		font-weight: 400;
	}
	.copy-txt .copied-txt {
		opacity: 1 !important;
	}
  </style>
  <script type="text/javascript">
	function copyFunction(element) {
	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val($(element).text()).select();
	document.execCommand("copy");
	$temp.remove();
	$(element).parent().addClass('copy-txt');  
	setTimeout(function () {
	
	$(element).parent().removeClass('copy-txt'); 
	
	}, 1000);
	}
  </script>
      <div class="row">
        <div class="col-2 hidden-sm-down"></div>
        <div class="col-md-8 col-sm-12">
          <h1 class="h1 page-title">
            {l s='Дякуємо за замовлення'  d='Shop.Theme.Checkout'}</span>
          </h1>
          <p class="mail-sent-info">
            {l s='Ми вже обробляємо ваше замовлення'  d='Shop.Theme.Checkout'}. <br>
            {l s='An email has been sent to your mail address %email%.' d='Shop.Theme.Checkout' sprintf=['%email%' => $order_customer.email]} <br>
          </p>
          <h2 class="h2">{l s='Замовлення №'  d='Shop.Theme.Checkout'}<span>{$order_id}</span></h2>
          <p>{l s='Інформація про замовлення'  d='Shop.Theme.Checkout'}</p>
		  {if $order.details.module != 'ecm_monopay'}
              <div class="bank-info">
              {if Configuration::get('SHOP_BANK_ACTIVE')|intval}
				<p>Реквізити для оплати замовлення:</p>
				<ul>
					<li>{l s='IBAN'}: <span class="color-blue iban_num">{Configuration::get('SHOP_BANK_IBAN')}</span> <span class="btn-copy" onclick="copyFunction('.iban_num')"><img src="/img/copy-icon.svg" alt="" /></span> <span class="copied-txt">Скопійовано</span></li>
					<li>{l s='Назва банку'}: <span>{Configuration::get('SHOP_BANK_NAME')}</span> </li>
					<li>{l s='ЄДРПОУ'}: <span class="color-blue bank_name">{Configuration::get('SHOP_BANK_CODE')}</span> <span class="btn-copy" onclick="copyFunction('.bank_name')"><img src="/img/copy-icon.svg" alt="" /></span> <span class="copied-txt">Скопійовано</span></li>
					<li>{l s='Отримувач'}: <span class="color-blue bank_rec">{Configuration::get('SHOP_BANK_RECIPIENT')}</span> <span class="btn-copy" onclick="copyFunction('.bank_rec')"><img src="/img/copy-icon.svg" alt="" /></span> <span class="copied-txt">Скопійовано</span></li>
				</ul>
				<p>Призначення платежу: <span class="color-blue title_pay">оплата за товар №{$order_id}</span> <span class="btn-copy" onclick="copyFunction('.title_pay')"><img src="/img/copy-icon.svg" alt="" /></span> <span class="copied-txt">Скопійовано</span></p>
                {/if}

                {*https://telegram.me/vzuti_store*}
                {* 18-12-2024 винести в адмін розділ,*}
                {*
                <p>Якщо ви вказали в призначенні платежу номер замовлення, то ми автоматично опрацюємо замовлення. Якщо ви забули вказати номер замовлення, то квитанцію можете надіслати сюди на
                <a class="color-blue" href="viber://chat?number=%2B380502492615">Viber</a>,
				<a class="color-blue" href="https://t.me/vzuti_store_manager">Telegram</a>,
				<a class="color-blue" href="https://www.instagram.com/vzuti_store">Instagram</a>, 
				(на номер <a class="color-blue" href="tel:+380502492615">+380502492615</a>).</p>
                (на номер <a class="color-blue" href="tel:+380502492615">+380502492615</a>).</p>
                {* end 18-12-2024 *}

                {*Якщо ви вказали в призначенні платежу номер замовлення, то ми автоматично опрацюємо замовлення. Якщо ви забули вказати номер замовлення, то квитанцію можете надіслати сюди на <a class="color-blue" href="viber://chat?number=%2B380502492615">Viber</a>, <a class="color-blue" href="https://t.me/vzuti_store_manager">Telegram</a>, <a class="color-blue" href="https://www.instagram.com/vzuti_store">Instagram</a>, (на номер <a class="color-blue" href="tel:+380502492615">+380502492615</a>).*}

                {assign var="order_confirm_text" value=Configuration::get('SHOP_ORDER_CONFIRM_TEXT')}
                {if !empty($order_confirm_text)}
                    <p>{$order_confirm_text nofilter}</p>
                {/if}

			</div>
		 {/if}



		  
        <p><b>{l s='Відправка вашого замовлення буде протягом 48 годин, очікуйте ттн на номер телефону, який був вказаний при оформленні замовлення.'  d='Shop.Theme.Checkout'}</b>
          <ul>
            <li>{l s='Payment method: %method%' d='Shop.Theme.Checkout' sprintf=['%method%' => $order.details.payment]}</li>
            {if !$order.details.is_virtual}
              <li>
                {l s='Shipping method: %method%' d='Shop.Theme.Checkout' sprintf=['%method%' => $order.carrier.name]}
                ({$order.carrier.delay})
              </li>
			  {if $order.carrier.name == 'Нова пошта'}
			  	<li><b>{l s='Термін доставки: 1-3 дні'  d='Shop.Theme.Checkout'}</b></li>
			  {/if}
            {/if}
            <li>{l s='Загальна сума замовлення:'  d='Shop.Theme.Checkout'} {$order.totals.total.value}</li>
            <li>{l s=''}</li>
          </ul>
          </p>
          <hr>
          {block name='order_confirmation_table'}
            {include
            file='checkout/_partials/order-confirmation-table-simple.tpl'
            products=$order.products
            subtotals=$order.subtotals
            totals=$order.totals
            labels=$order.labels
            add_product_link=false
            }
          {/block}
          <hr>
          <p class="h3">
            <a href="/">{l s='Продовжити...'}</a>
          </p>
        </div>
        <div class="col-2 hidden-sm-down"></div>
      </div>
	<script>
	gtag('event', 'conversion', {
		'send_to': 'AW-11412755620/243BCJnmmvcYEKSpg8Iq',
		'transaction_id': '{$order_id}',
		'value': '{$order.totals.total.amount}'
	}); 
	</script>
	
	<script type="text/javascript">
		gtag("event", "purchase", {
			value: "{round($total_products)}",			
			currency: '{$currency.iso_code}',		                 
			coupon: '',		                                        
			items: [
			{foreach from=$products item='product' name=products}
			{
				item_id: "{$product.reference}",
				item_name: "{$product.name|escape:'html':'UTF-8'} {if $product.attributes_small}{$product.attributes_small}{/if}",
				affiliation: 'vzutistore.com.ua',
				item_brand: "{if $product.manufacturer_name}{$product.manufacturer_name|escape:'html':'UTF-8'}{else}undefined{/if}",
				item_category: "{$product.category_name}",
				price: "{$product.price_with_reduction}",
				quantity: "{$product.quantity|intval}"	                   
			},
			{/foreach}
			]
		});
    </script>
    </section>

    {block name='customer_registration_form'}

  {/block}

  {block name='hook_order_confirmation_2'}
    <section id="content-hook-order-confirmation-footer">
      {hook h='displayOrderConfirmation2'}
    </section>
  {/block}

{/block}


