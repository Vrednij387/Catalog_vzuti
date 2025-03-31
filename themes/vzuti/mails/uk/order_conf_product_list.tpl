<table id="mail_table">
    <tr id="mail_table_header">
        <td>Назва</td>
        <td>Артикул</td>
        <td>Ціна</td>
        <td>Кількість</td>
        <td>Вартість</td>
    </tr>
    {foreach $list as $product}
        <tr>
            <td>
                <a href="https://vzutistore.com.ua/{$product['id_product']}-vzuti.html">
                    {$product['name']}
                </a>
            </td>
            <td>{$product['reference']}
            </td>
            <td>{$product['unit_price']}</td>
            <td>{$product['quantity']}</td>
            <td>{$product['price']}</td>
        </tr>
    {/foreach}
</table>
