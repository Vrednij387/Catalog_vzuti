{**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @author    Elcommerce <support@elcommece.com.ua>
 * @copyright 2010-2019 Elcommerce TM
 * @license   Comercial
 * @category  PrestaShop
 * @category  Module
*}
<div class="row">
    <div class="col-lg-12">
        <div class="panel card">
            <div class="panel-heading card-header">
                <i class="icon-help"></i>{l s='Змінити метод оплати на МonoPay' mod='ecm_monopay'}
            </div>
            <div class="card-body table-responsive">
                <form action="{$link->getAdminLink('AdminMonopayConfirmation')}" method="post">
                    <input name="ecmmonopayhold_id_order" type="hidden" value="{$ecmmonopayhold_id_order}">
                    <input name="ecmmonopayhold_id_cart" type="hidden" value="{$ecmmonopayhold_id_cart}">
                    <button class="btn btn-info" name="submitMonopayCreareInvoice"
                        id="creare_mono_invoice">{l s='Змінити' mod='ecm_monopay'}</button>
                    <hr>
                    <div class="alert alert-warning">
                        <p class="alert-text">
                            {l s='Змінити метод оплати на МonoPay та створити посилання на оплату' mod='ecm_monopay'}
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>