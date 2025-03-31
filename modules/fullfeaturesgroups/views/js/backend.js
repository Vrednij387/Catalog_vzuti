/*
 * 2015 Terranet
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
 *  @author PrestaShop SA <info@terranet.md>
 *  @copyright  2015 Terranet SA
 *  Copyright their respective owners. Reproduction in any form is not allowed.
 */

$(document).on('click', '.js-fast-feature-edit', function(e){
  e.preventDefault();

  var id_product = $(e.currentTarget).attr('data-id_product');
  var id_feature = $(e.currentTarget).attr('data-id_feature');

  loadFastFeatureForm(id_product, id_feature, $(e.currentTarget));

  e.stopPropagation();
})

function loadFastFeatureForm(id_product, id_feature) {
  $('.td_feature_editable_container').hide();
  $('.td_feature_container').show()

  $.ajax({
    type: "POST",
    url : fullfeaturefastview_url,
    async: false,
    dataType: 'json',
    data : 'id_product='+parseInt(id_product)+'&id_feature='+parseInt(id_feature)+'&action=getFeatureEditForm&ajax=1&token='+fullfeaturefastview_token,
    success : function(data) {
      $('.product_general_info_' + id_product).hide();
      $('.edit_feature_form_' + id_product)
        .html(data.form)
        .show();

      hideOtherLanguage($('.default_init_lang').val());
      initCancelFeatureFunc();
      initSaveFFGFeature();
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      jAlert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
    }
  });
}

function initSaveFFGFeature() {
  $('.js-fast-feature-save').on('click', function(e){
    e.preventDefault();
    e.stopPropagation();
    var id_product = $(e.currentTarget).attr('data-id_product');
    var id_feature = $(e.currentTarget).attr('data-id_feature');
    var featureSerializeData = $(e.currentTarget).closest('form#fast_ffg_edit_form').serialize()
    $.ajax({
      type: "POST",
      url : fullfeaturefastview_url,
      async: false,
      dataType: 'json',
      data: 'id_product='+parseInt(id_product)
          +'&id_feature='+parseInt(id_feature)+'&action=setFeatureEditForm&ajax=1&token='+fullfeaturefastview_token
      +'&'+featureSerializeData,
      success : function(data) {
        if (data.form.length) {
          $('.product_general_info_' + id_product).html(data.form);

          $('.td_feature_editable_container').hide();
          $('.td_feature_container').show();
        }

        if (data.confirmations.length != 0)
          showSuccessMessage(data.confirmations);
        else
          showErrorMessage(data.error);
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {

        console.log(XMLHttpRequest);

        jAlert("TECHNICAL ERROR: \n\nDetails:\nError thrown: " + XMLHttpRequest + "\n" + 'Text status: ' + textStatus);
      }
    });
  })
}

function showSuccessMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function showErrorMessage(msg) {
  $.growl.error({ title: "", message:msg});
}

function showNoticeMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function initCancelFeatureFunc() {
  $('.js-fast-feature-cancel').on('click', function(e){
    e.preventDefault();
    e.stopPropagation();

    $('.td_feature_editable_container').hide();
    $('.td_feature_container').show();
  })
}
