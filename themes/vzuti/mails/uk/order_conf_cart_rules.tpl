{foreach $list as $cart_rule}
	<p>{$cart_rule['voucher_name']} : <b>{$cart_rule['voucher_reduction']}</p>
{/foreach}

