{**
 * Copyright since 2022 totcustomfields
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to tech@202-ecommerce.com so we can send you a copy immediately.
 *
 * @author    202 ecommerce <tech@202-ecommerce.com>
 * @copyright 2022 totcustomfields
 * @license   https://opensource.org/licenses/AFL-3.0  Academic Free License (AFL 3.0)
 *}

<hr/>
<div class="totcustomfieldscf-wrapper">
    <div class="alert alert-info d-none hidden" role="alert">
        <button type="button" class="close">
            <span aria-hidden="true"><i class="material-icons">close</i></span>
        </button>
        <div class="totCustomFields-formReturn"></div>
    </div>
    <div class="totCustomFields-form">

        <input type="hidden" name="id_object" value="{$id_object|escape:'htmlall':'UTF-8'}"/>
        <input type="hidden" name="code_object" value="{$code_object|escape:'htmlall':'UTF-8'}"/>
        <input type="hidden" name="action" value="saveInputsValues"/>

        {foreach from=$totCustomFields_inputs item=input}
            {$input->getInputHtml($id_object, $code_object)|escape:'quotes':'UTF-8'}
        {/foreach}

        <div class="row">
            <div class="col-12 d-flex justify-content-end">
                <button type="button"
                        class="btn btn-outline-primary btn-default totCustomFields-submit">
                    {l s='Save' mod='totcustomfields'}
                </button>
            </div>
        </div>
    </div>
</div>
<hr/>
<script>
    $(function () {

        $('.totCustomFields-submit').on('click', function (ev) {
            ev.stopPropagation();
            ev.preventDefault();
            var self = this;

            // Clone the contents to avoid messing with the DOM
            var formContent = $(this).closest('.totCustomFields-form').clone();
            var form = formContent.wrap(document.createElement('form')).closest('form');
            var totFormData = new FormData(form[0]);
            var url = '{$ajax_url|escape:'quotes':'UTF-8'}';

            form.find("[type='submit']").prop('disabled', true)
                    .find('i').removeClass('process-icon-save').addClass('process-icon-loading');
            totFormData.append('ajax', true);

            $.ajax({
                'url': url,
                'method': 'POST',
                'type': 'POST',
                'dataType': 'JSON',
                'data': totFormData,
                processData: false,
                contentType: false,
                'error': function (data) {
                    $(self).closest('.totcustomfieldscf-wrapper').find('.totCustomFields-formReturn').html(data.responseText);
                    $(self).closest('.totcustomfieldscf-wrapper').find('.alert').removeClass('d-none').removeClass('hidden');
                },
                'success': function (data) {
                    $(self).closest('.totcustomfieldscf-wrapper').find('.totCustomFields-formReturn').html(data);
                    $(self).closest('.totcustomfieldscf-wrapper').find('.alert').removeClass('d-none').removeClass('hidden');
                },
                'complete': function () {
                    form.find("[type='submit']").prop('disabled', false)
                            .find('i').addClass('process-icon-save').removeClass('process-icon-loading');
                }
            });

            return false;
        });

        $(document).on('change', 'select', function () {
            $('option[value=' + this.value + ']', this)
                    .attr('selected', true).siblings()
                    .removeAttr('selected');
        });

        $('.totcustomfields-checkbox-custom-input input[type="checkbox"]').on('change', function () {
            var checkbox = $(this);
            checkbox.next().val(+(checkbox.prop('checked')));
        });

        $('.totcustomfieldscf-wrapper .alert .close').on('click', function (event) {
          var $alert = $(event.currentTarget).closest('.alert');
          $alert.addClass('d-none').addClass('hidden');
        });
    });
</script>