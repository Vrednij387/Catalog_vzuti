{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
* @author    SeoSA <885588@bk.ru>
* @copyright 2012-2023 SeoSA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

{if isset($variables)}
    <!-- block-property-btn redirect-->
    <div class="row">
        <div class="col-md-4 form-group">
            <select class="custom-select" onclick="verify()" name="redirection_page">
                <option value="0">--</option>
                <option value="301-category">301-category</option>
                <option value="302-category">302-category</option>
                <option value="301-product">301-product</option>
                <option value="302-product">302-product</option>
                <option value="404">404</option>
            </select>
        </div>

        <div class="categories-block col-md-8 form-group">
            <select multiple class="category_redirect custom-select"></select>
        </div>
        <div class="product-block col-md-8 form-group">
            <select multiple class="product_redirect custom-select"></select>
        </div>
    </div>
    <script>
    {literal}
        $('.product_redirect').select2({
            placeholder: '{/literal}{l s='Start typing...' mod='masseditproduct'}{literal}',
            minimumInputLength: 2,
            width: '100%',
            ajax: {
                url: '{/literal}{Context::getContext()->link->getAdminLink('AdminMassEditProduct')}{literal}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                        ajax: 1,
                        action: 'productsList'
                    };
                }
            }
        });
        $('.category_redirect').select2({
            placeholder: '{/literal}{l s='Start typing...' mod='masseditproduct'}{literal}',
            minimumInputLength: 2,
            width: '100%',
            ajax: {
                url: '{/literal}{Context::getContext()->link->getAdminLink('AdminMassEditProduct')}{literal}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term,
                        ajax: 1,
                        action: 'categoryList'
                    };
                }
            }
        });

        $('.product-block').hide();

        function verify() {
            index_redirect = $('[name=redirection_page] option:selected').text();
            if (index_redirect == '301-category' ||
                index_redirect == '302-category'
            ) {
                $('.product-block').hide();
                $('.categories-block').show();
            }
            if (index_redirect == '301-product' ||
                index_redirect == '302-product'
            ) {
                $('.categories-block').hide();
                $('.product-block').show();
            }
            if (index_redirect == '404') {
                $('.categories-block').hide();
                $('.product-block').hide();
            }
        }
    {/literal}
    </script>
{/if}