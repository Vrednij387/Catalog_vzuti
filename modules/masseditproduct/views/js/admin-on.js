/**
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
 */

if (typeof window.hideOtherLanguage == 'undefined')
    function hideOtherLanguage(id_lang) {
        changeFormLanguage(id_lang);
    }

Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

var ajax_url = document.location.href.replace(document.location.hash, '');
$(function () {
//Search tab
    window.page = 1;
    window.tree = new TreeCustom('.tree_custom .tree_categories', '.tree_custom .tree_categories_header');
    window.tree.init();

    $('.table_head th .title_box a').live('click', function (e) {
        e.preventDefault();
        var orderby = $(this).parent('span').data('orderby');
        // var orderway = $(this).data('orderway');
        if (window.orderway == undefined) {
            orderway = 'ASC';
        }
        if (orderway == 'ASC') {
            orderway = 'DESC'
        } else {
            orderway = 'ASC'
        }
        window.orderby = orderby;
        window.orderway = orderway;
        window.current = orderby;
        checkURL();
        return false;
    });
    $('#beginSearch').on('click order', function (event, orderby, orderway) {
        window.orderby = undefined;
        window.orderway = undefined;
        searchProducts(orderby, orderway, 1);
    });
    $(document).delegate('.product_checkbox', 'click', function () {
        var tr = $(this).closest('tr');
        if ($(this).is(':checked')) {
            tr.addClass('selected');
            popup_form.selectProduct({
                id: tr.find('[name=id_product]').val(),
                name: tr.find('[data-name]').text()
            });
            popup_form.mergeProducts();
        }
        else {
            tr.removeClass('selected');
            popup_form.unselectProduct(tr.find('[name=id_product]').val());
        }
    });

    checkURL();
    window.popup_form = new PopupForm('.popup_mep');
    window.popup_form.init();
    window.tab_container = new TabContainer('.tab_container');
    window.tab_container.init();

    $('.selectAll').on('click', function () {
        $('.table_search_product .product_checkbox').each(function () {
            var tr = $(this).closest('tr');
            tr.addClass('selected');
            popup_form.selectProduct({
                id: tr.find('[name=id_product]').val(),
                name: tr.find('[data-name]').text()
            });
        });
        popup_form.mergeProducts();
    });
    $('[name="type_search"]').on('change', function () {
        if (parseInt($(this).val()) != 1)
            $('.search_product_name').show();
        else
            $('.search_product_name').hide();
    });
//End search tab

    $('[name="supplier[]"]').on('change', function () {
        $('[name="id_supplier_default"]').html('');
        $(this).find('option:selected').each(function () {
            $('[name="id_supplier_default"]').append($(this).clone());
        });
    });
    $('[name="carrier[]"]').on('change', function () {
        $('[name="id_carrier_default"]').html('');
        $(this).find('option:selected').each(function () {
            $('[name="id_carrier_default"]').append($(this).clone());
        });
    });
    $( "body" ).delegate('.add_image', 'click', function () {
        $('.images').append($('#image_row').html());
    });
    $('.images').append($('#image_row').html());
    $('[name="change_type"]').on('change', function () {
        $('._type').removeClass('hide_option').hide();
        $('.type_' + $(this).val()).show();
    });
    $('._type').addClass('hide_option');
    $('._row_copy').rowCopy();
    $.changeLanguage(id_language);
    var ids_feature = [];
    $('[data-feature-values]').each(function () {
        var self = $(this);
        ids_feature.push(self.data('featureValues'));
    });

// Feature search start
    $('#select_feature').on('click', function () {
        var select_feature = $('#select_feature option:selected').val();
        $.ajax({
            url: document.location.href.replace('#' + document.location.hash, ''),
            type: 'POST',
            dataType: 'json',
            data: {
                ajax: true,
                action: 'feature',
                ids_feature: ids_feature
            },
            success: function (json) {
                $('#first-option-feature').replaceWith(json.return.html);
                $('#select_feature option[value= ' + select_feature +']').prop('selected', true);
                $('.fin').attr('data-feature-values', select_feature);
                var input_sel = $('#feature-sel').find('input');
                input_sel.each(function (i, element) {
                    if ($(element).prop('checked') == true) {
                        $(element).closest('span').show();
                    }
                    $('#feature-sel .fin').show();

                });

            }
        });
    });
    $('#select_feature').on('change', function () {
        var ids_feature = $('#select_feature option:selected').val();
        $.ajax({
            url: document.location.href.replace('#' + document.location.hash, ''),
            type: 'POST',
            dataType: 'json',
            data: {
                ajax: true,
                action: 'render_feature_values',
                ids_feature: ids_feature
            },
            success: function (json) {
                json.return.forEach(function (item) {
                    $('#feature-val').html(item.html);
                    $('#feature-sel [data-feature-values-view=' + ids_feature + ']').append(item.html);
                });
                $("#btn_list").click();
                $("#btn_list").removeClass("open btn-danger").addClass("active-btn btn-success");
                $('#feature-val').attr('data-feature-values', ids_feature);
                $('#feature-val').find('.no_feature').val(ids_feature);
            }
        });
    });
// Feature search end

    $('[name="feature_group"]').on('change', function () {
        $('[data-feature-values]').hide();
        //     $('[data-feature-values="'+$(this).val()+'"]').show();

        setTimeout(function() {
            // $('#btn_list').trigger( "click" );
        }, 0);

        if ($('[name="feature_group"] option:selected').hasClass('active')) {
            $("#btn_list").removeClass("open btn-danger").addClass("active-btn btn-success");
        } else {
            $("#btn_list").removeClass("active-btn");
        }

    }).trigger('change');
    $("body").on('change', '#feature-val input[type=checkbox]', function () {
        var data_feature_values = $(this).attr("value");
        var data_feature_group = $(this).closest('[data-feature-values]').attr('data-feature-values');
        if ($(this).hasClass('no_feature')) {
            $('[data-feature-values-view=' + data_feature_group + ']').find('.no_feature').parent().parent().toggle();
            $('.data-feature-values-view-label').show();
            $(this).prev().hide();
            if ($(this).prop('checked')) {
                $('[data-feature-values-view='+data_feature_group+']').find('.no_feature').prop("checked", true);
                $('[data-feature-values-view='+data_feature_group+']').prev().show();
                data_feature_values_view_label = true;
            } else {
                $('[data-feature-values-view='+data_feature_group+']').find('.no_feature').prop("checked", false);
                $('[data-feature-values-view]').each(function (index) {
                    $(this).prev().hide();
                    $(this).find('[type=checkbox]').each(function (index) {
                        if ($(this).prop('checked')) {
                            $(this).closest('[data-feature-values-view]').prev().show();
                            data_feature_values_view_label = true;
                        }
                    });
                });
            }
        } else {
            var checkBoxes = $('[data-feature-values-view='+data_feature_group+']').find('[value='+data_feature_values+']');
            checkBoxes.prop("checked", !checkBoxes.prop("checked"));
            $('[data-feature-values-view='+data_feature_group+']').find('[value='+data_feature_values+']').parent().parent().toggle();
            $('.data-feature-values-view-label').show();
            var data_feature_values_view_label = false;
            $('[data-feature-values-view]').each(function (index) {
                $(this).prev().hide();
                $(this).find('[type=checkbox]').each(function (index) {
                    if ($(this).prop('checked')) {
                        $(this).closest('[data-feature-values-view]').prev().show();
                        data_feature_values_view_label = true;
                    }
                });
            });
        }
        if (data_feature_values_view_label) {
            $('.data-feature-values-view-label').show();
            $('.data-feature-values-view-hr').show();
        } else {
            $('.data-feature-values-view-label').hide();
            $('.data-feature-values-view-hr').hide();
        }

    }).trigger('change');
    $("body").on('change', '#feature-sel input[type=checkbox]', function () {
        var data_feature_values = $(this).attr("value");
        var data_feature_group = $(this).closest('[data-feature-values-view]').attr('data-feature-values-view');
        if ($(this).hasClass('no_feature')) {
            $('[data-feature-values-view=' + data_feature_group + ']').find('.no_feature').parent().parent().toggle();
            $(this).prev().hide();
            if ($(this).prop('checked')) {
                $(this).closest('[data-feature-values-view]').prev().show();
                data_feature_values_view_label = true;
            } else {
                $(this).closest('span').hide();
                $(this).closest('[data-feature-values-view='+data_feature_group+']').prev().hide();
                $(this).closest('[data-feature-values-view]').prev().hide();

                $('[data-feature-values-view]').each(function (index) {
                    $(this).prev().hide();
                    $(this).find('[type=checkbox]').each(function (index) {
                        if ($(this).prop('checked')) {
                            $(this).closest('[data-feature-values-view]').prev().show();
                            data_feature_values_view_label = true;
                        }
                    });
                });
                $('#feature-val [data-feature-values='+data_feature_group+']').find('.no_feature').prop("checked", false);
            }
        } else {
            var checkBoxes = $('[data-feature-values=' + data_feature_group + ']').find('[value=' + data_feature_values + ']');
            checkBoxes.prop("checked", !checkBoxes.prop("checked"));
            $('[data-feature-values-view=' + data_feature_group + ']').find('[value=' + data_feature_values + ']').parent().parent().toggle();
            if (!$(this).prop('checked')) {
                $(this).closest('span').hide();
            }
            var data_feature_values_view_label = false;
            $('[data-feature-values-view]').each(function (index) {
                $(this).prev().hide();
                $(this).find('[type=checkbox]').each(function (index) {
                    if ($(this).prop('checked')) {
                        $(this).closest('[data-feature-values-view]').prev().show();
                        data_feature_values_view_label = true;
                    }
                });
            });
        }
        if (data_feature_values_view_label) {
            $('.data-feature-values-view-label').show();
        } else {
            $('.data-feature-values-view-label').hide();
        }

    }).trigger('change');
    $("#btn_list").click(function () {
        $(this).toggleClass("open btn-success btn-danger");
        $('#feature-val').toggle();
    });
    var ids_attribute = [];
    $('[data-attribute-values]').each(function () {
        var self = $(this);
        ids_attribute.push(self.data('attributeValues'));
    });
// attribute search start
    $('#select_attribute').on('change', function () {
        var ids_attribute = $('#select_attribute option:selected').val();
        if (ids_attribute == 0) {
            var ids_attribute = $('#select_attribute option:first').val();
        }
        $.ajax({
            url: document.location.href.replace('#' + document.location.hash, ''),
            type: 'POST',
            dataType: 'json',
            data: {
                ajax: true,
                action: 'render_attribute_values',
                ids_attribute: ids_attribute
            },
            success: function (json) {
                json.return.forEach(function (item) {
                    $('#attr-val').html(item.html);
                    $('#active-attr [data-attribute-values-view=' + ids_attribute + ']').append(item.html_sel);
                });
                $("#btn_list_at").click();
                $("#btn_list_at").removeClass("open btn-danger").addClass("active-btn btn-success");
            }
        });
    })
    $('#select_attribute').on('click', function () {
        var select_attribute = $('#select_attribute option:selected').val();
        $.ajax({
            url: document.location.href.replace('#' + document.location.hash, ''),
            type: 'POST',
            dataType: 'json',
            data: {
                ajax: true,
                action: 'attribute_group',
                ids_attribute: ids_attribute
            },
            success: function (json) {
                $('#first-option').replaceWith(json.return.html);
                $('#select_attribute option[value= ' + select_attribute +']').prop('selected', true);
                $('#attr-val').attr('data-attribute-values', select_attribute);
            }
        });
    })
// attribute search end

    $('[name="attribute_group_main"]').on('change', function () {
        $('[data-attribute-values]').hide();

        setTimeout(function() {
            // $('#btn_list_at').trigger( "click" );
        }, 0);

        //     $('[data-feature-values="'+$(this).val()+'"]').show();

        if ($('[name="attribute_group_main"] option:selected').hasClass('active')) {
            $("#btn_list_at").removeClass("open btn-danger").addClass("active-btn btn-success");
        } else {
            $("#btn_list_at").removeClass("active-btn");
        }

    }).trigger('change');
    $("body").on('change', '[data-attribute-values] input[type=checkbox]', function () {
        var data_attribute_values = $(this).attr("value");
        var data_attribute_group = $(this).closest('[data-attribute-values]').attr('data-attribute-values');
        var checkBoxes = $($('[data-attribute-values-view]').find('[value='+data_attribute_values+']'));
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        $('[data-attribute-values-view='+data_attribute_group+']').find('[value='+data_attribute_values+']').parent().parent().toggle();

        var data_attribute_values_view_label = false;
        $('[data-attribute-values-view]').each(function(index) {
            $(this).prev().hide();
            $(this).find('[type=checkbox]').each(function(index) {
                if ($(this).prop('checked')) {
                    $(this).closest('[data-attribute-values-view]').prev().show();
                    data_attribute_values_view_label = true;
                }
            });
        });

        if (data_attribute_values_view_label) {
            $('.data-attribute-values-view-label').show();
            $('.data-attribute-values-view-hr').show();
        } else {
            $('.data-attribute-values-view-label').hide();
            $('.data-attribute-values-view-hr').hide();
        }


    }).trigger('change');
    $("body").on('change', '[data-attribute-values-view] input[type=checkbox]', function () {
        var data_attribute_values = $(this).attr("value");
        var data_attribute_group = $(this).closest('[data-attribute-values-view]').attr('data-attribute-values-view');
        var checkBoxes = $('[data-attribute-values]').find('[value='+data_attribute_values+']');
        checkBoxes.prop("checked", !checkBoxes.prop("checked"));
        $('[data-attribute-values-view='+data_attribute_group+']').find('[value='+data_attribute_values+']').parent().parent().toggle();

        var data_attribute_values_view_label = false;
        $('[data-attribute-values-view]').each(function(index) {
            $(this).prev().hide();
            $(this).find('[type=checkbox]').each(function(index) {
                if ($(this).prop('checked')) {
                    $(this).closest('[data-attribute-values-view]').prev().show();
                    data_attribute_values_view_label = true;
                }
            });
        });

        if (data_attribute_values_view_label) {
            $('.data-attribute-values-view-label').show();
            $('.data-attribute-values-view-hr').show();
        } else {
            $('.data-attribute-values-view-label').hide();
            $('.data-attribute-values-view-hr').hide();
        }
    }).trigger('change');
// button View -> Hidden attribute value
    $("#btn_list_at").click(function () {
        $(this).toggleClass("open btn-success btn-danger");
        $('#attr-val').toggle();
    });
    $('.ajax_load_tab').each(function () {
        var tab_name = $(this).attr('id');
        modulePreloader().add(function (callback) {
            $.ajax({
                url: ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    ajax: true,
                    action: 'load_tab',
                    tab_name: tab_name
                },
                success: function (json) {
                    if (!json.hasError) {
                        $('.tab_content[id="' + tab_name + '"]').html(json.html);
                    } else {
                        $.alert(json.error);
                        setTimeout(function() {
                            $('body').find('.jconfirm').addClass('bootstrap');
                        }, 1);
                    }
                    callback(json);
                }
            });
        }, Translator().l('Loading tab ' + tab_name, 'mep'), tab_name);
    });
    modulePreloader().onReady(function () {
        //All tabs
        $('.disable_option').on('change', disableOption);
        $('.disable_option').each(disableOption);

        function disableOption() {
            var checked = $(this).is(':checked');
            if ($(this).is('[type=radio]')) {
                if (parseInt($('[name="' + $(this).attr('name') + '"]:checked').val()) >= 1)
                    checked = true;
                else
                    checked = false;
            }

            if (checked)
                $(this).closest('.row').addClass('disabled_option_stage');
            else
                $(this).closest('.row').removeClass('disabled_option_stage');
        }

        $('.ajax_load_tab').removeClass('ajax_load_tab loading');
    });
    modulePreloader().onStepStack(function (tab_name) {
        if (typeof tabsMEP[tab_name] != 'undefined') {
            tabsMEP[tab_name]();
        }
    });
    modulePreloader().init();
    $('[name=new_on]').live('change', function () {
        if (parseInt($(this).val())) {
            $('.new_on_input').show();
            $('.new_on_input input').val(
                $('.new_on_input input').attr('value')
            );
        } else {
            $('.new_on_input').hide();
        }
    });
});

var ajaxLoadCombinations = [];
function searchProducts(orderby, orderway, page) {
    $('.wrapp_content').addClass('loading');
    var categories = tree.getListSelectedCategories();
    var search_only_default_category = $('#search_only_default_category').prop('checked');
    var search_only_explicit_category = $('#search_only_explicit_category').prop('checked');
    var search_query = $('[name=search_query]').val();
    var type_search = $('[name=type_search]').val();
    var search_product =  parseInt($('[name="search_product"]:checked').val());
    var manufacturers = $('[name="manufacturer[]"]').val();
    var suppliers = $('[name="supplier[]"]').val();
    var carriers = $('[name="carrier[]"]').val();
    var how_many_show = $('[name="how_many_show"]').val();
    var active = parseInt($('[name="active"]:checked').val());
    var disable = parseInt($('[name="disable"]:checked').val());
    var no_image = parseInt($('[name="no_image"]:checked').val());
    var yes_image = parseInt($('[name="yes_image"]:checked').val());
    var mode_or = parseInt($('[name="mode_or"]:checked').val());
    var carrier_mode_or = parseInt($('[name="carrier_mode_or"]:checked').val());
    var carrier_pre = parseInt($('[name="carrier_pre"]:checked').val());
    var mode_or_at = parseInt($('[name="mode_or_at"]:checked').val());
    var no_discount = parseInt($('[name="no_discount"]:checked').val());
    var yes_discount = parseInt($('[name="yes_discount"]:checked').val());
    var low_on = parseInt($('[name="low_on"]:checked').val());
    var new_on = parseInt($('[name="new_on"]:checked').val());
    var date_period = parseInt($('[name="date_period"]').val());
    var log_on = parseInt($('[name="log_on"]:checked').val());
    var product_name_type_search = $('[name="product_name_type_search"]:checked').val();

    var qty_from = ($('[name="qty_from"]').val() != '' ? parseInt($('[name="qty_from"]').val()) : '');
    var qty_to = ($('[name="qty_to"]').val() != '' ? parseInt($('[name="qty_to"]').val()) : '');

    var type_price = $('[name="type_price"]').val();
    var price_from = ($('[name="price_from"]').val() != '' ? parseFloat($('[name="price_from"]').val()) : '');
    var price_to = ($('[name="price_to"]').val() != '' ? parseFloat($('[name="price_to"]').val()) : '');
    var visible = $('[name="type_visible"]').val();
    var date_from = $('[name="date_from"]').val();
    var percent_discout = $('[name="percent_discout"]').val();
    var value_discout = $('[name="value_discout"]').val();
    var date_to = $('[name="date_to"]').val();
    var id_feature = $("#select_feature :selected").val();
    var features = [];
    var attributes = [];
    var id_attribute = $("#select_attribute :selected").val();
    var custom_feature =  $('[name="custom_feature"]').val();
    $('[name="features[]"]:checked').each(function () {
        features.push($(this).val());
    });
    $('[name="attributes[]"]:checked').each(function () {
        attributes.push($(this).val());
    });


    var no_feature_value = [];

    $('[name="no_feature_value[]"]:checked').each(function () {
        no_feature_value.push($(this).val());
    });

    var exclude_ids = [];
    $('.table_selected_products [name="id_product"]').each(function () {
        exclude_ids.push($(this).val());
    });
    var url = document.location.href.replace(document.location.hash, '');

    var data = {
        categories: categories,
        search_only_default_category: search_only_default_category ? 1 : 0,
        search_only_explicit_category: search_only_explicit_category ? 1 : 0,
        search_query: search_query,
        type_search: type_search,
        search_product: search_product,
        manufacturers: manufacturers,
        suppliers: suppliers,
        carriers: carriers,
        how_many_show: how_many_show,
        active: active,
        disable: disable,
        new_on: new_on,
        date_period: date_period,
        no_image: no_image,
        yes_image: yes_image,
        low_on: low_on,
        no_discount: no_discount,
        yes_discount: yes_discount,
        percent_discout: percent_discout,
        value_discout: value_discout,
        log_on: log_on,
        page: window.page,
        ajax: true,
        action: 'search_products',
        exclude_ids: exclude_ids,
        product_name_type_search: product_name_type_search,
        qty_from: qty_from,
        qty_to: qty_to,
        type_price: type_price,
        price_from: price_from,
        price_to: price_to,
        type_visible: visible,
        date_from: date_from,
        date_to: date_to,
        features: features,
        attributes: attributes,
        id_attribute: id_attribute,
        custom_feature: custom_feature,
        id_feature: id_feature,
        no_feature_value: no_feature_value,
        mode_or: mode_or,
        carrier_mode_or: carrier_mode_or,
        carrier_pre: carrier_pre,
        mode_or_at: mode_or_at,
    };

    if (typeof orderby == 'string' && typeof orderway == 'string') {
        data['orderby'] = orderby;
        data['orderway'] = orderway;
    } else if (typeof window.orderby != 'undefined') {
        data['orderby'] = window.orderby;
        data['orderway'] = window.orderway;
    }

    if (typeof page != 'undefined') {
        data.page = page;
    }

    if (ajaxLoadCombinations.length) {
        $.each(ajaxLoadCombinations, function (key, item) {
            item.abort();
        });
        ajaxLoadCombinations = [];
    }

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function (r) {
            $('.table_search_product').html(r.products);

            $('.active_filter').removeClass('active_filter');
            $('[data-' + orderby + ']').addClass('active_filter');
            $('[data-orderby="' + orderby + '"]').closest('th').addClass('active_filter');

            popup_form.resetSelect();
            var id_tab = parseInt(tab_container.tab.find('ul li.active').data('tab').replace('tab', ''));
            if (id_tab == 'price' || id_tab == 'quantity')
                $('.table_selected_products [data-combinations]').show();
            else
                $('[data-combinations]').hide();
            $('#count_result').remove();

            $('.panel.mode_search .panel-heading:last').append('<span id="count_result" class="badge">' + r.count_result + '</span>');

            var $col_combinations = $('.table_search_product td[data-combinations]');
            if ($col_combinations.length) {
                $col_combinations.loadCombinationsOneRequest(ajaxLoadCombinations, function () {
                    $('.wrapp_content').removeClass('loading');
                });
            } else {
                $('.wrapp_content').removeClass('loading');
            }
            //var table = $('.table_search_product table').finderSelect({
            //    children: '> tr:not(.table_head)'
            //});
            //table.finderSelect('addHook','highlight:before', function(el) {
            //    el.find('input[name=product]').attr('checked', 'checked');
            //    el.each(function () {
            //        popup_form.selectProduct({
            //            id: $(this).find('[name=id_product]').val(),
            //            name: $(this).find('[data-name]').text()
            //        });
            //    });
            //});
            //table.finderSelect('addHook','unHighlight:before', function(el) {
            //    el.find('input[name=product]').removeAttr('checked');
            //    el.find('input[name=id_product]').each(function () {
            //        popup_form.unselectProduct($(this).val());
            //    });
            //});
            document.location.hash = r.hash;
            var i = window.orderby;
            if (window.orderway == 'DESC') {
                $('.table_head').find('[data-orderby="' + i + '"]').find('a').removeClass('asc-sort-column').addClass('desc-sort-column');
            } else {
                $('.table_head').find('[data-orderby="' + i + '"]').find('a').removeClass('desc-sort-column').addClass('asc-sort-column');
            }

        },
        error: function () {
            $('.wrapp_content').removeClass('loading');
        }
    });
}
function setAllProducts(data, field, afterUpdate) {
    var table = $('.table_selected_products tbody');
    var url = document.location.href.replace(document.location.hash, '');
    data['products'] = popup_form.products;
    data['ajax'] = true;
    data['change_date_upd'] = parseInt($('[name="change_date_upd"]:checked').val());
    data['reindex_products'] = parseInt($('[name="reindex_products"]:checked').val());
    data['action'] = 'api';
    data['method'] = 'set_all_product';
    data['tab_name'] = field;
    data['disabled'] = [];

    $('[name^="disabled"]:checked').each(function () {
        if ($(this).is('[type="checkbox"]')) {
            if ($(this).val().indexOf(',') != -1) {
                var values = $(this).val().split(',');
                $.each(values, function (index, value) {
                    data['disabled'].push(value);
                });
            }
            else {
                data['disabled'].push($(this).val());
            }
        }

        if ($(this).is('[type="radio"]')) {
            if ($(this).val() == 0) {
                if (typeof data['disabled[feature]'] == 'undefined')
                    data['disabled[feature]'] = [];
                data['disabled[feature]'].push(parseInt($(this).data('feature')));
            }
        }
    });

    var timeout_success = null;
    if (timeout_success != null)
        clearTimeout(timeout_success);

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function (r) {
            if (data['error']) {
                var error_mesage = [];
                $.each(data['error'], function (index, log) {
                   console.log(log);
                        error_mesage.push(log);
                });

                // $('.tn-box.mv_error .message_mv_content').html(error_mesage.join('<br>')).slideDown(500);
                // $('.tn-box.mv_error').addClass('tn-box-active');
                // setTimeout(function () {
                //     $('.stage_mv').fadeOut(300);
                //     $('.tn-box.mv_error').removeClass('tn-box-active');
                // }, 5000);
            }
            if (typeof afterUpdate != 'undefined')
                afterUpdate();
            if (!r.hasError) {
                $('.tn-box.mv_succes').not('.tn-box_combinations').addClass('tn-box-active');
                setTimeout(function () {
                    $('.stage_mv.mv_succes').fadeOut(300);
                    $('.tn-box').removeClass('tn-box-active');
                }, 5000);

                if (field == 'discount')
                    field = 'price';

                if (r.result != null) {
                    if (typeof r.result.delete_products !== 'undefined') {
                        $.each(r.result.delete_products, function (index, id_product) {
                            window.popup_form.removeProduct(id_product);
                        });
                        searchProducts();
                    }


                    if (typeof r.result.delete_combinations !== 'undefined') {
                        $.each(r.result.delete_combinations, function (index, id_pa) {
                            var $checkbox = $('.row_combination_' + id_pa).find('[type="checkbox"]:checked');
                            if ($checkbox.length) {
                                $checkbox.trigger('click');
                            }

                            $('.row_combination_' + id_pa).remove();
                        });
                    }


                    for (var i in r.result.products) {
                        if (field == 'active' || field == 'stock_management') {
                            table.find('.product_' + i + ' [data-' + field + '] i').text((r.result.products[i] ? 'check' : 'clear'));
                            if (r.result.products[i]) {
                                table.find('.product_' + i + ' [data-' + field + '] i').removeClass('action-disabled').addClass('action-enabled')
                            } else {
                                table.find('.product_' + i + ' [data-' + field + '] i').removeClass('action-enabled').addClass('action-disabled')
                            }
                        } else if (field == 'price') {
                            table.find('.product_' + i + ' [data-' + field + ']').text(r.result.products[i].price);
                            table.find('.product_' + i + ' [data-' + field + '_final]').text(r.result.products[i].price_final);
                        }
                        else if (field == 'accessories' || field == 'discount' || field == 'features') {

                        } else
                            table.find('.product_' + i + ' [data-' + field + ']').text(r.result.products[i]);
                    }

                    if (field == 'price') {
                        for (var i in r.result.combinations) {
                            $('[data-pa-price="' + i + '"]').text(r.result.combinations[i].price);
                            $('[data-pa-price-final="' + i + '"]').text(r.result.combinations[i].price_final);
                            $('[data-pa-total-price-final="' + i + '"]').text(r.result.combinations[i].total_price_final);
                        }
                    }
                    if (field == 'quantity') {
                        for (var i in r.result.combinations) {
                            $('[data-pa-quantity="' + i + '"]').text(parseInt(r.result.combinations[i]));
                        }
                    }
                    if (field == 'reference') {
                        for (var i in r.result.ids_product) {
                            $('tr.product_' + r.result.ids_product[i] + ' [data-reference]').text(r.result.reference);
                        }
                    }
                }
            }
            else {
                var error_mesage = [];
                $.each(r.log, function (index, log) {
                    if (log.type == 'error')
                        error_mesage.push(log.message);
                });

                $('.tn-box.mv_error .message_mv_content').html(error_mesage.join('<br>')).slideDown(500);
                $('.tn-box.mv_error').addClass('tn-box-active');
                setTimeout(function () {
                    $('.stage_mv').fadeOut(300);
                    $('.tn-box.mv_error').removeClass('tn-box-active');
                }, 5000);
            }
        },
        error: function (r) {
            $('.tn-box.mv_error .message_mv_content').html(r.responseText).slideDown(500);
            $('.tn-box.mv_error').addClass('tn-box-active');
            setTimeout(function () {
                $('.stage_mv').fadeOut(300);
                $('.tn-box.mv_error').removeClass('tn-box-active');
            }, 5000);
        }
    });
}
function checkURL() {
    var hash = document.location.hash;
    var data = hash.replace('#', '').split('&');
    for (var i = 0; i < data.length; i++)
        data[i] = decodeURIComponent(data[i]);
    for (var i in data) {
        var param = data[i].split('-');
        if (param[0] == 'categories') {
            $.each(param[1].split('_'), function (index, value) {
                window.tree.checkAssociatedCategory(value);
            });
        }
        else if (param[0] == 'manufacturers') {
            var manufacturers = $('[name="manufacturers[]"]');
            $.each(param[1].split('_'), function (index, value) {
                manufacturers.find('option[value=' + value + ']').attr('selected', 'selected');
            });
        }
        else if (param[0] == 'suppliers') {
            var suppliers = $('[name="supplier[]"]');
            $.each(param[1].split('_'), function (index, value) {
                suppliers.find('option[value=' + value + ']').attr('selected', 'selected');
            });
        }
        else if (param[0] == 'carriers') {
            var carriers = $('[name="carrier[]"]');
            $.each(param[1].split('_'), function (index, value) {
                carriers.find('option[value=' + value + ']').attr('selected', 'selected');
            });
        }
        else if (param[0] == 'search_query') {
            $('[name=search_query]').val(param[1]);
        }
        else if (param[0] == 'qty_from') {
            $('[name=qty_from]').val(param[1]);
        }
        else if (param[0] == 'qty_to') {
            $('[name=qty_to]').val(param[1]);
        }
        else if (param[0] == 'type_search') {
            var type_search = $('[name=type_search]');
            type_search.find('option').removeAttr('selected');
            type_search.find('option[value=' + param[1] + ']').attr('selected', 'selected');
        }
        else if (param[0] == 'product_name_type_search') {
            var product_name_type_search = $('[name=product_name_type_search]');
            product_name_type_search.removeAttr('checked');
            // $('[name=product_name_type_search][value=' + param[1] + ']').attr('checked', 'checked');
            $('[name=product_name_type_search][value=' + param[1] + ']').prop('checked', true); // ps178
        }
        else if (param[0] == 'how_many_show') {
            var how_many_show = $('[name=how_many_show]');
            how_many_show.find('option').removeAttr('selected');
            how_many_show.find('option[value=' + param[1] + ']').prop('selected', true);
        }
        else if (param[0] == 'active') {
            var active = $('[name=active]');
            active.removeAttr('checked');
            $('[name=active][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'disable') {
            var disable = $('[name=disable]');
            disable.removeAttr('checked');
            $('[name=disable][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'no_image') {
            var no_image = $('[name=no_image]');
            no_image.removeAttr('checked');
            $('[name=no_image][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'yes_image') {
            var yes_image = $('[name=yes_image]');
            yes_image.removeAttr('checked');
            $('[name=yes_image][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'low_on') {
            var low_on = $('[name=low_on]');
            low_on.removeAttr('checked');
            $('[name=low_on][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'mode_or') {
            var mode_or = $('[name=mode_or]');
            mode_or.removeAttr('checked');
            $('[name=mode_or][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'search_product') {
            var search_product = $('[name=search_product]');
            search_product.removeAttr('checked');
            $('[name=search_product][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'carrier_mode_or') {
            var carrier_mode_or = $('[name=carrier_mode_or]');
            carrier_mode_or.removeAttr('checked');
            $('[name=carrier_mode_or][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'carrier_pre') {
            var carrier_pre = $('[name=carrier_pre]');
            carrier_pre.removeAttr('checked');
            $('[name=carrier_pre][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'mode_or_at') {
            var mode_or_at = $('[name=mode_or_at]');
            mode_or_at.removeAttr('checked');
            $('[name=mode_or_at][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'no_discount') {
            var no_discount = $('[name=no_discount]');
            no_discount.removeAttr('checked');
            $('[name=no_discount][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'yes_discount') {
            var yes_discount = $('[name=yes_discount]');
            yes_discount.removeAttr('checked');
            $('[name=yes_discount][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'log_on') {
            var log_on = $('[name=log_on]');
            log_on.removeAttr('checked');
            $('[name=log_on][value=' + param[1] + ']').attr('checked', 'checked');
        }
        else if (param[0] == 'page') {
            window.page = param[1];
        }
    }

    if (data.length) {
        searchProducts(undefined, undefined);
    }
}
function setPage(page) {
    window.page = page;
    page = 'page-' + page;
    var hash = document.location.hash.replace('#', '');
    if (!hash.length)
        document.location.hash = page;
    else {
        var old_page = /page-[0-9]+/.exec(hash);
        if (old_page) {
            hash.replace(old_page[0], page);
        }
        else {
            hash += '&' + page;
        }
        document.location.hash = page;
    }
    checkURL();
}
function createListPositionsForImageCaption(obj, force) {
    force = true || false;

    if (!force && $('input[value="disable_image_caption"]').prop("checked"))
        return;
    // $('[value="disable_image_caption"]').closest('div').addClass('loading');
    var data = {};
    data['products'] = obj.products;
    data['ajax'] = true;
    data['action'] = 'getMaxPositionForImageCaption';

    $.ajax({
        url: document.location.href,
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function (r) {
            $('#caption_selection > select').find('option:gt(0)').remove();
            $('#caption_selection > select').append(r.option);
            $('[value="disable_image_caption"]').closest('div').removeClass('loading');
            return;
        }
    });
}

$(document).ready(function () {

    if(document.documentElement.clientWidth < 767) {

        $(".tab-menu").click(function () {
            $(".tab_container .tabs").slideToggle();
        });

        $(".tab_container .tabs").slideToggle();

        $(".tab_container li").click(function () {
            $(".tab_container .tabs").slideUp();
        });
    }

    return false;
});
$(document).ready(function () {
    $(".panel-heading span, .change_date_button").click(function () {
        $(".change_date_container").slideToggle();
    });
    $(".panel-heading span, .change_date_button").click(function () {
        $(".change_date_button i").toggleClass('icon-minus');
    });

    $('[name="variable_feature"]').trigger('click');
});
$(document).ready(function () {
    $('input.search_category:first').focus({el: $('.tree_categories.tree_root:first input.tree_input')}, function (eventObj) {
        eventObj.data.el.each(function () {
            $(this).attr('data-search', $(this).data('name').toLowerCase());
        });
    });

    $('.select2').select2();

    $('.tabs_content').on('change', '[name="action_for_sp"]', function () {
        var disabled = $('[name="sp_from_quantity"], [name="sp_reduction"], [name="price"], [name="leave_base_price"], [name="sp_reduction_type"]');
        var enabled = disabled;
        if ($('[name="leave_base_price"]').prop("checked"))
            enabled = enabled.not('[name="price"]');
        $(this).val() == 1 ? disabled.attr('disabled', true) : enabled.attr('disabled', false);
    });

    $('input[name="change_for"]').on('change', {
        product: change_product,
        combination: change_combination
    }, function (event) {
        var value = event.data.product;
        if ($('#change_for_combination').prop('checked'))
            value = event.data.combination;

        var row = $(this).closest('.row').next();
        row.find('.control-label').text(value.title);
        row.find('label[for="type_price_base"]').find('span').text(value.base);
        row.find('label[for="type_price_final"]').find('span').text(value.final);
    });
});
$(document).ready(function () {
    $('.start_select_combinations').on('click', function (event) {
        event.preventDefault();
        var data = {};
        data['ajax'] = true;
        data['action'] = 'get_combinations_by_attributes';
        var count_index = 0;
        $('.panel.mode_edit .attribute_group_block').each(function (index) {
            data['data[' + index + ']'] = {
                'attribute': $(this).find('.select_attribute').val(),
                'value': $(this).find('.select_attribute_value').val()
            };
            count_index = index;
        });

        $('.panel.mode_edit .selected-attr li').each(function (index2) {
            var data_attribute = $(this).data('attribute');
            data['data[' + (count_index + index2 + 1) + ']'] = {
                'attribute': data_attribute.attr,
                'value': data_attribute.val
            };
        });

        $.ajax({
            url: document.location.href.replace(document.location.hash, ''),
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (r) {
                if (r.hasError) {

                    $('.tn-box_combinations.mv_error').addClass('tn-box-active');
                    setTimeout(function () {
                        $('.stage_mv.mv_error').fadeOut(300);
                        $('.tn-box_combinations').removeClass('tn-box-active');
                    }, 5000);
                } else {
                    $('input[data-selector-item]').each(function (index, value) {
                        var selector_item_combination = $(this).data('selector-item').split('_');
                        for (var i in r.data) {
                            if (selector_item_combination[1] == r.data[i].id_product_attribute) {
                                $(this).prop('checked', true);
                            }
                        }
                    });
                    updateCountSelectedCombinations();
                    $('.tn-box_combinations.mv_succes').addClass('tn-box-active');
                    setTimeout(function () {
                        $('.stage_mv.mv_succes').fadeOut(300);
                        $('.tn-box_combinations').removeClass('tn-box-active');
                    }, 5000);
                }
            }
        });
    });

    $('.select_attribute').on('change', function () {
        var data = {};
        data['ajax'] = true;
        data['action'] = 'get_attributes_by_group';
        data['group'] = $(this).val();
        $(this).closest('.attribute_group_block').find('div:nth-child(2)').addClass('loading');
        $.ajax({
            url: document.location.href.replace(document.location.hash, ''),
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function (r) {
                if (r.hasError) {
                } else {
                    var option = '';
                    for (var i in r.data) {
                        option += '<option value="' + r.data[i].id_attribute + '">' + r.data[i].name + '</option>';
                    }
                    $('.select_attribute_value').html(option);
                }
                $('.select_attribute').closest('.attribute_group_block').find('div:nth-child(2)').removeClass('loading');
            },
        });

        var select_attr = $(this).closest('#attributes_select');
        var attr_text = select_attr.find('.select_attribute').find('option:selected').text();
        if (attr_text == '--') {
            $('.select_attribute_value_block').removeClass('active');
        } else {
            $('.select_attribute_value_block').addClass('active');
        }
    });

    $('.more_select_combinations').on('click', function (e) {
        e.preventDefault();
        var select_attr = $(this).closest('#attributes_select');
        var attr = select_attr.find('.select_attribute').val();
        var attr_val = select_attr.find('.select_attribute_value').val();
        var attr_text = select_attr.find('.select_attribute').find('option:selected').text();
        var attr_val_text = select_attr.find('.select_attribute_value').find('option:selected').text();
        var ul = select_attr.find('ul');
        if (attr_text == '--') {
            $('.tn-box_more_select_combinations.mv_error').addClass('tn-box-active');
            setTimeout(function () {
                $('.stage_mv.mv_error').fadeOut(300);
                $('.tn-box_more_select_combinations').removeClass('tn-box-active');
            }, 5000);
        } else {
            $('<li class="list-group-item">').data('attribute', {
                attr: attr,
                val: attr_val
            }).html('<b>' + attr_text + '</b>: ' + attr_val_text + '<img class="attribute-remove" src="../img/admin/disabled.gif">').appendTo(ul);
        }
    });

    $('#attributes_select > ul > li > img').on('click', function () {
        $(this).closest('li').remove();
    });

    $('.check_attribute_combinations').on('click', function (event) {
        event.preventDefault();
        $('.panel.mode_edit #attributes_select').toggle(200);
    });

    $('.combinations-btn').on('click', function (event) {
        event.preventDefault();
        $('.select_combinations').toggle(200);
        $('.selector_item_bg').toggleClass('active');
    });

    $('.selector_item_bg').on('click', function (event) {
        event.preventDefault();
        $('.select_combinations').toggle(200);
        $('.selector_item_bg').removeClass('active');
    });

    $('.close_combinations').on('click', function (event) {
        event.preventDefault();
        $('.select_combinations').toggle(200);
        $('.selector_item_bg').removeClass('active');
    });

    $('.check_all_combinations').on('click', function (event) {
        event.preventDefault();
        $('input[data-selector-item]').prop('checked', true);
        updateCountSelectedCombinations();
    });

    $('.uncheck_all_combinations').on('click', function (event) {
        event.preventDefault();
        $('input[data-selector-item], .selector_checkbox').prop('checked', false);
        updateCountSelectedCombinations();
    });

    $('.invert_all_combinations').on('click', function (event) {
        event.preventDefault();

        $("input[data-selector-item]").each(function(index) {
            if ($(this).prop('checked')) {
                $(this).prop('checked', false);
            } else {
                $(this).prop('checked', true);
            }
        });

        updateCountSelectedCombinations();
    });

    $( "body" ).delegate( ".js-translit", "keypress", function() {
        $(this).liTranslit();
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
            $('#scroller').fadeIn();
        } else {
            $('#scroller').fadeOut();
        }
    });

    $('#scroller').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });

});

function updateCountSelectedCombinations() {
    $.each($.fn.SelectorContainers, function () {
        this.updateContainer();
    });
    return false;
}
