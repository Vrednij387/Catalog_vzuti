{*
* 2012-2022 PrestaShop
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
*  @author    Goryachev Dmitry <dariusakafest@gmail.com>
*  @copyright 2012-2023 Goryachev Dmitry
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<a class="SMPro_doc button btn btn-default" href="{$return_back_link|escape:'quotes':'UTF-8'}">{l s='Settings' mod='masseditproduct'}</a>
<div class="form-group tab_manager">
    <div class="col-lg-3">
        <div class="panel">
            <div class="panel-heading">
                {'Content'|ld}
            </div>
            <div class="panel-body">
                <ul class="tab_links nav nav-pills nav-stacked">
                    {include file="./doc_tree.tpl" tree=$tree}
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="panel">
            <div class="panel-body tab_contents">
                {foreach item='documentation_page' from=$documentation_pages}
                    <div data-tab-content="{str_replace(array($documentation_folder|cat:'/', '.tpl'), '', $documentation_page)|no_escape}">
                        {include file=$documentation_page}
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>

<script>
    $.fn.tabManager = function () {
        function TabManager(elem)
        {
            var self = this;
            self.element = $(elem);

            self.element.find('[data-tab]').live('click', function (e) {
                e.preventDefault();
                self.element.find('[data-tab-content]').hide();
                self.element.find('[data-tab-content="'+$(this).data('tab')+'"]').show();
            });

            self.element.find('[book-link]').live('click', function (e) {
                e.preventDefault();
                if (!self.element.find('[data-tab-content="'+$(this).attr('book-link')+'"]').length)
                    return false;
                self.element.find('[data-tab-content]').hide();
                self.element.find('[data-tab-content="'+$(this).attr('book-link')+'"]').show();
            });

            self.element.find('[data-tab]').eq(0).trigger('click');
        }

        $.each(this, function (index, elem) {
            if (!$(elem).data('tab-manager'))
                $(elem).data('tab-manager', new TabManager(elem));
        });
    };

    $('.tab_manager').tabManager();

    $('[name="doc_switch"]').live('change', function () {
        if (parseInt($(this).val()))
        {
            $('.wrap_not_documentation').hide();
            $('.wrap_documentation').show();
        }
        else
        {
            $('.wrap_not_documentation').show();
            $('.wrap_documentation').hide();
        }
    });
</script>