/**
 * Copyright 2023 ModuleFactory
 *
 * @author    ModuleFactory
 * @copyright ModuleFactory all rights reserved.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

var FSMG = FSMG || {};
FSMG.isProcessing = false;
FSMG.scrollExtra = 150;

$(document).ready(function(){
    $('#fsmg_tabs a').click(function(){
        $('#fsmg_tabs a').removeClass('active');
        $(this).addClass('active');
    });

    $('#fsmg_maintenance_clear_type').change(function(){
        FSMG.contentTypeChangeCallback();
    });
});

FSMG.contentTypeChangeCallback = function() {
    var content_type = $('#fsmg_maintenance_clear_type').val();
    $('#fsmg_maintenance_clear_field').val('all');
    if (content_type != 'all') {
        $('#fsmg_maintenance_clear_field option').each(function() {
            var meta_field_val = $(this).val();
            if (meta_field_val != 'all') {
                $(this).hide();
                if (FSMG.isFieldEnabledForType(content_type, meta_field_val)) {
                    $(this).show();
                }
            }
        });
    }
    else {
        $('#fsmg_maintenance_clear_field option').each(function() {
            $(this).show();
        });
    }
};

FSMG.isFieldEnabledForType = function(type, field) {
    for (field_val in FSMG.metaFieldsByType[type]) {
        console.log(field_val);
        if (field_val == field) {
            return true;
        }
    }
    return false;
};

FSMG.generateClearQueue = function() {
    swal({
        title: FSMG.translateAreYouSure,
        text: FSMG.translateClearIntentText,
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: FSMG.translateCancel,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: FSMG.translateYesClearIt
    },
    function(){
        FSMG.isProcessing = false;
        $.ajax({
            url: FSMG.generateClearQueueUrl,
            type: 'GET',
            data: {
                json: true,
                fsmg_id_lang: $('#fsmg_maintenance_clear_id_lang').val(),
                fsmg_content_type: $('#fsmg_maintenance_clear_type').val(),
                fsmg_meta_field: $('#fsmg_maintenance_clear_field').val(),
                fsmg_request_token: FSMG.requestToken,
                fsmg_request_time: FSMG.requestTime
            },
            async: true,
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.error.length > 0)
                {
                    var errors = data.error.join('\n\n');
                    swal({
                        title: 'Oops...',
                        text: errors,
                        type: 'error',
                        confirmButtonText: FSMG.translateOk
                    });
                }
                else {
                    $('#fsmg_maintenance_clear_queue_content').html(data.content);
                    $.scrollTo($('#fsmg_maintenance_clear_queue_content').offset().top - FSMG.scrollExtra, {duration:300});
                    $('#fsmg_maintenance_clear_id_lang').focus();
                    FSMG.processNext();
                }
            }
        });
    });
};

FSMG.processNext = function() {
    var next = $('.fsmg_queue_item:not(.fsmg_status_done):first', $('#fsmg_queue_list'));
    var content_type = next.data('contenttype');
    var meta_field = next.data('metafield');
    var id_lang = next.data('selectedidlang');
    if (content_type && meta_field && id_lang) {
        var rowid = content_type + '_' + meta_field;
        var pbid = rowid + '_progress_bar';
        $('#'+pbid).html('Preparing...');
        $.scrollTo($('#'+rowid).offset().top - FSMG.scrollExtra, {duration:300});
        FSMG.clearMetaField(content_type, meta_field, id_lang, 0);
    }
    else {
        swal({
            title: FSMG.translateDone,
            text: FSMG.translateClearComplete,
            type: 'success',
            confirmButtonText: FSMG.translateOk
        });
    }
};

FSMG.clearMetaField = function(content_type, meta_field, id_lang, offset) {
    $.ajax({
        url: FSMG.clearMetaFieldUrl,
        type: 'GET',
        data: {
            json: true,
            fsmg_id_lang: id_lang,
            fsmg_content_type: content_type,
            fsmg_meta_field: meta_field,
            fsmg_offset: offset,
            fsmg_request_token: FSMG.requestToken,
            fsmg_request_time: FSMG.requestTime
        },
        async: true,
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.error.length > 0)
            {
                var errors = data.error.join('\n\n');
                swal({
                    title: 'Oops...',
                    text: errors,
                    type: 'error',
                    confirmButtonText: FSMG.translateOk
                });
            }
            else {
                var rowid = content_type + '_' + meta_field;
                var pbid = rowid + '_progress_bar';
                $('#'+pbid).css('width', data.content.progress_bar_percent + '%').attr('aria-valuenow', data.content.progress_bar_percent);
                $('#'+pbid).html(data.content.progress_bar_percent + '%');

                if (data.content.has_more) {
                    FSMG.clearMetaField(content_type, meta_field, id_lang, data.content.processed_count);
                }
                else {
                    $('#'+rowid).addClass('fsmg_status_done');
                    $('#'+pbid).addClass('progress-bar-success');
                    $('#'+pbid).html(data.content.progress_bar_message);

                    FSMG.processNext();
                }

            }
        }
    });
};

FSMG.addKeywordToInput = function(keyword, id_input) {
    $('#'+id_input).insertAtCaret('{' + keyword + '}');
};

FSMG.addKeywordToInputMultilang = function(keyword, id_input) {
    var visible_input = $('#'+id_input).parent().parent().parent().find('div:visible:first input');

    if (visible_input.attr('disabled') != 'disabled') {
        visible_input.insertAtCaret('{' + keyword + '}');
    }
};

FSMG.toggleDescription = function(id_panel) {
    $('#'+id_panel).toggle();
};

FSMG.generateProductLinkRewrite = function() {
    var type = 'product_link_rewrite';
    var pbid = FSMG.generatePBID(type);
    $('#'+pbid).css('width', 0 + '%').attr('aria-valuenow', 0);
    $('#'+pbid).html('Preparing...');
    FSMG.generateMeta(type, 0, {});
};

FSMG.generateProductMeta = function() {
    var type = 'product_meta';
    var pbid = FSMG.generatePBID(type);
    $('#'+pbid).css('width', 0 + '%').attr('aria-valuenow', 0);
    $('#'+pbid).html('Preparing...');
    var overwrite = $("#FSMG_GEN_PROD_META_OVERWRITE_on").prop("checked");
    FSMG.generateMeta(type, 0, {overwrite: overwrite});
};

FSMG.generateCategoryMeta = function() {
    var type = 'category_meta';
    var pbid = FSMG.generatePBID(type);
    $('#'+pbid).css('width', 0 + '%').attr('aria-valuenow', 0);
    $('#'+pbid).html('Preparing...');
    var overwrite = $("#FSMG_GEN_CAT_META_OVERWRITE_on").prop("checked");
    FSMG.generateMeta(type, 0, {overwrite: overwrite});
};

FSMG.generateManufacturerMeta = function() {
    var type = 'manufacturer_meta';
    var pbid = FSMG.generatePBID(type);
    $('#'+pbid).css('width', 0 + '%').attr('aria-valuenow', 0);
    $('#'+pbid).html('Preparing...');
    var overwrite = $("#FSMG_GEN_MANU_META_OVERWRITE_on").prop("checked");
    FSMG.generateMeta(type, 0, {overwrite: overwrite});
};

FSMG.generateSupplierMeta = function() {
    var type = 'supplier_meta';
    var pbid = FSMG.generatePBID(type);
    $('#'+pbid).css('width', 0 + '%').attr('aria-valuenow', 0);
    $('#'+pbid).html('Preparing...');
    var overwrite = $("#FSMG_GEN_SUPPLIER_META_OVERWRITE_on").prop("checked");
    FSMG.generateMeta(type, 0, {overwrite: overwrite});
};

FSMG.generateMeta = function(type, offset, params) {
    if (!FSMG.isProcessing) {
        FSMG.isProcessing = true;

        $.ajax({
            url: FSMG.generateMetaUrl,
            type: 'GET',
            data: {
                json: true,
                fsmg_type: type,
                fsmg_offset: offset,
                fsmg_request_token: FSMG.requestToken,
                fsmg_request_time: FSMG.requestTime,
                fsmg_params: params
            },
            async: true,
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.error.length > 0)
                {
                    var errors = data.error.join('\n\n');
                    swal({
                        title: 'Oops...',
                        text: errors,
                        type: 'error',
                        confirmButtonText: FSMG.translateOk
                    });
                }
                else {
                    if (data.content) {
                        var pbid = FSMG.generatePBID(type);
                        $('#'+pbid).css('width', data.content.progress_bar_percent + '%').attr('aria-valuenow', data.content.progress_bar_percent);
                        $('#'+pbid).html(data.content.progress_bar_percent + '%');

                        if (data.content.has_more) {
                            FSMG.isProcessing = false;
                            FSMG.generateMeta(type, data.content.processed_count, params);
                        }
                        else {
                            $('#'+pbid).addClass('progress-bar-success');
                            $('#'+pbid).html(data.content.progress_bar_message);

                            FSMG.isProcessing = false;

                            var messages = data.confirmations.join('\n\n');
                            swal({
                                title: data.content.alert_title,
                                text: messages,
                                type: 'success',
                                confirmButtonText: FSMG.translateOk
                            });
                        }
                    }
                }
            }
        });
    }
};

FSMG.generatePBID = function(type) {
    return 'fsmg_'+type+'_progress_bar';
};

$.fn.extend({
    insertAtCaret: function(insert_value){
        var obj;
        if( typeof this[0].name !='undefined' ) obj = this[0];
        else obj = this;

        if ($.browser.msie) {
            obj.focus();
            sel = document.selection.createRange();
            sel.text = insert_value;
            obj.focus();
        }
        else if ($.browser.mozilla || $.browser.webkit) {
            var startPos = obj.selectionStart;
            var endPos = obj.selectionEnd;
            var scrollTop = obj.scrollTop;
            obj.value = obj.value.substring(0, startPos) + insert_value + obj.value.substring(endPos,obj.value.length);
            obj.focus();
            obj.selectionStart = startPos + insert_value.length;
            obj.selectionEnd = startPos + insert_value.length;
            obj.scrollTop = scrollTop;
        }
        else {
            obj.value += insert_value;
            obj.focus();
        }
    }
});

FSMG.toggleMultishopDefaultValue = function(obj, key) {
    if (!$(obj).prop('checked') || $('.'+key).hasClass('isInvisible'))
    {
        $('.conf_id_'+key+' input, .conf_id_'+key+' textarea, .conf_id_'+key+' select, .conf_id_'+key+' button').attr('disabled', true);
        $('.conf_id_'+key+' label.conf_title').addClass('isDisabled');
    }
    else
    {
        $('.conf_id_'+key+' input, .conf_id_'+key+' textarea, .conf_id_'+key+' select, .conf_id_'+key+' button').attr('disabled', false);
        $('.conf_id_'+key+' label.conf_title').removeClass('isDisabled');
    }
    $('.conf_id_'+key+' input[name^=\'multishop_override_enabled\']').attr('disabled', false);
    $('.conf_id_'+key+' input[name^=\'multishop_override_fields\']').attr('disabled', false);
};