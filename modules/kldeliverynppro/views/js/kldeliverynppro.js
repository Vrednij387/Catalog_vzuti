
/**
* @author    Antikov Evgeniy
* @copyright 2017-2022 kLooKva
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

$(document).ready(function () {
  changeRequiredNPPro();
  if($('#delivery_option_'+np_id_carrier+':checked').length > 0 && $('.carrier-extra-content').not(':visible').length > 0){
    $('.hook_extracarrier').show();
  } else if ($('#delivery_option_'+np_id_carrier+':checked').length <= 0 && $('.hook_extracarrier').is(':visible')) {
    $('.hook_extracarrier').hide();
  }

  initialiseSelect2('#js-cities');
  //initialiseSelect2('name=[city-js]');
  console.log($('#js-cities-carrier').length);

      initialiseSelect2('#js-cities-carrier');
  
  if ($('#js-warehouses').length > 0) {
      initialiseSelect2('#js-warehouses');
  }
  $('#delivery_option_'+np_id_carrier).click(function(){
    setTimeout(function (){ 
      $("#js-cities").focus();
    }, 5000)
  });

  $('[id^="delivery_option"]').click(function(){
    changeRequiredNPPro();
    $('.carrier-extra-content').hide();
    $(this).closest('.delivery-option').next().show();
  });
    
  $(document).on( 'click', '.payment_module,button[name=confirmDeliveryOption]', function(){
    if ($('#delivery_option_'+np_id_carrier+':checked').length <= 0) {
      return;
    }
    var city = $('#js-cities option[value="'+$('#js-cities option:selected').val().replaceAll('"','\\"').replaceAll("'","\\'")+'"]').data('ref'); 
    var warehouse = $('#js-warehouses option[value="'+$('#js-warehouses option:selected').val().replaceAll('"','\\"').replaceAll("'","\\'")+'"]').data('ref');
    $.ajax({
      url: $('#saveCartUrl').data('value'),
      type: "post",
      dataType: "json",
      async: false,
      data: {
          "city-js": city,
          "warehouse-js":  warehouse,
      }
    });
  });

  $('#checkout-addresses-step,#checkout-personal-information-step').find('input[name=city-js]').blur(function(){
  	var current_city = $('#select2-js-cities-container').html();
  	var true_city = $(this).val().trim();
  	if (typeof current_city == "undefined" || $(this).val().trim() != current_city.trim()) {
  		$.each($('.select-filling > option'), function( index, value) {
  			if(true_city == $(value).val()) {
  				$('.select-filling').val(true_city);
  				$('.select-filling').trigger('change'); 
  				return false;
  			}
  		});
  	}
  });
  $(document).on( 'click', '#delivery_option_'+np_id_carrier, function(){
    var timerId = setInterval(function() {
      if($('#delivery_option_'+np_id_carrier+':checked').length > 0 && $('.hook_extracarrier').not(':visible').length > 0){
        $('.hook_extracarrier').show();
        $('#js-cities').select2("destroy");
        initialiseSelect2('#js-cities');
        if ($('#js-warehouses').length > 0) {
            initialiseSelect2('#js-warehouses');
        } else {
          getWarehouses($('#js-cities'))
        }
        clearInterval(timerId);
      } else if ($('#delivery_option_'+np_id_carrier+':checked').length <= 0 && $('.hook_extracarrier').is(':visible')) {
        $('.hook_extracarrier').hide();
        clearInterval(timerId);
      }
    }, 1000);
  });
});

function saveCartNovaPoshta(elem) {
   	var warehouse = jQuery(elem).find('option:selected').data('ref');
	var city = $('#js-cities').find('option:selected').data('ref');
	jQuery(elem).parent().find('span.select2-selection--single').removeClass('error');
   console.log(warehouse);
   console.log(city);
   if (city && warehouse) {
    $.ajax({
      url: $('#saveCartUrl').data('value'),
      type: "post",
      dataType: "json",
      async: false,
      data: {
          "city-js": city,
          "warehouse-js":  warehouse,
      }
    });
   }
}


function createCCN(elem) {
  var form = $(elem).closest('form');
  $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serializeArray(),
        beforeSend: function(){
            form.ploading({
                action: "show",   
                containerHTML: "<div/>",
                containerAttrs: {},                             
                containerClass: "p-loading-container",       
                spinnerHTML: "<div/>",
                spinnerAttrs: {},                               
                spinnerClass: "p-loading-spinner piano-spinner",
                onShowContainer: undefined,                     
                onHideContainer: undefined,                     
                onDestroyContainer: undefined,                                        
                destroyAfterHide: false,                        
                idPrefix: "loader",            
                pluginNameSpace: "p-loader",                    
                maskHolder: true,                               
                maskColor: "rgba(0,0,0,0.6)",  
                useAddOns: []
            });
        },
        success: function(data){
            var message = data;
            if (isJsonString(data)) {
              var res = JSON.parse(data);
              message = res.message;
            }
            $('.js_notification_np').html(message);
            $('.js-delivery-action-buttons').show();
        },
        error: function(data) {
          console.log(data)
            $('.js_notification_np').html(ajax_error);
        },
        complete: function(){
            form.ploading({
                action: "hide"
            });
        }
    });
}

function actionCCN(elem, actionCCN, moreData = '') {
    var button = $(elem);
    var form = $(elem).closest('form');
    $.ajax({
        type: form.attr('method'),
        url: form.attr('action')+moreData,
        data: {
            'id_order':form.find('#id_order').val(),
            actionCCN:actionCCN
        },
        beforeSend: function(){
            form.ploading({
                action: "show",   
                containerHTML: "<div/>",
                containerAttrs: {},                             
                containerClass: "p-loading-container",       
                spinnerHTML: "<div/>",
                spinnerAttrs: {},                               
                spinnerClass: "p-loading-spinner piano-spinner",
                onShowContainer: undefined,                     
                onHideContainer: undefined,                     
                onDestroyContainer: undefined,                                        
                destroyAfterHide: false,                        
                idPrefix: "loader",            
                pluginNameSpace: "p-loader",                    
                maskHolder: true,                               
                maskColor: "rgba(0,0,0,0.6)",  
                useAddOns: []
            });
        },
        success: function(data){
            var message = data;
            if (isJsonString(data)) {
              var res = JSON.parse(data);
              message = res.message;
            }
            $('.js_notification_np').html(message);
            if (actionCCN == 'deleteCCN') {
                $('.js-delivery-action-buttons').hide();
            }
        },
        error: function(data) {
          console.log(data)
            $('.js_notification_np').html(ajax_error);
        },
        complete: function(){
            form.ploading({
                action: "hide"
            });
        }
    });
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function getWarehouses(elem) {
   var city = jQuery(elem).val();
   var id = jQuery(elem)[0].id;
   console.log(city);
   var count = 0;
   if (typeof jQuery(elem).data('count-click') != 'undefined') {
    count = jQuery(elem).data('count-click');
   }
   jQuery(elem).data('count-click', count + 1);
   count_click = parseInt(jQuery(elem).data('count-click'));

   if (typeof jQuery(elem)[0].dataset.idLang !== 'undefined') {
      var id_lang = jQuery(elem)[0].dataset.idLang;
   } else if (typeof id_language !== 'undefined') {
      var id_lang = id_language;
   } else {
      var id_lang = 1;
   }
    if (city && city != 'empty' && id_lang) {
		var $city_4 = $('#select2-js-cities-container');
		var $city_wait = $city_4.parent('.select2-selection--single');
		$city_wait.removeClass('error');
        jQuery.ajax({
            beforeSend: function () {
              jQuery('#warehouses').remove();
              jQuery('#js-cities').closest('.row').ploading({
                action: "show",   
                containerHTML: "<div/>",
                containerAttrs: {},                             
                containerClass: "p-loading-container",       
                spinnerHTML: "<div/>",
                spinnerAttrs: {},                               
                spinnerClass: "p-loading-spinner piano-spinner",
                onShowContainer: undefined,                     
                onHideContainer: undefined,                     
                onDestroyContainer: undefined,                                        
                destroyAfterHide: false,                        
                idPrefix: "loader",            
                pluginNameSpace: "p-loader",                    
                maskHolder: true,                               
                maskColor: "rgba(0,0,0,0.6)",  
                useAddOns: []
              });
            },
            url : $('#ajaxurlget').data('value'),
            type: 'post',
            data: {'city-js':city, 'id_lang':id_lang, 'count_click':count_click},
            success: function (data) {
              $('.js_warehouse_option').remove();
              $('#js-warehouses').append(data);
              if (typeof selected_warehouse !== 'undefined') {
                  //$('#js-warehouses > option[data-ref="'+selected_warehouse+'"]').prop('selected', true);
              }
              initialiseSelect2('#js-warehouses');
              $('#js-warehouses').parent().find('span.select2.select2-container.select2-container--default').attr('style','width:100%;');
            },
            error: function (data) {
              console.log('error get warehouse');
            },
            complete: function(data) {
              jQuery('#js-cities').closest('.row').ploading({
                action: "hide"
              });
            }
        });
   } else {
       jQuery('#warehouses').remove();
   }
}

function initialiseSelect2(perem) {
  let timer = null;
  var myarray = new Array();
  $(document).find(perem).find('option').each(function(){
    myarray.push($(this).text().trim());
  });
  $(document).find(perem).select2({
    sorter: function(results) {
      var query = $('.select2-search__field').val().toLowerCase();
	  //console.log(results);
	  //console.log(query);
	  return results;
      return results.sort(function(a, b) {
		  //console.log(a);
		  //console.log(b);
		 //console.log(a.text.toLowerCase().indexOf(query) - b.text.toLowerCase().indexOf(query));
		 
		 // Sort results by matching name with keyword position in name
		if(query)
		{
        if(a.text.toLowerCase().indexOf(query.toLowerCase()) > b.text.toLowerCase().indexOf(query.toLowerCase())) {
            return 1;
        } else if (a.text.toLowerCase().indexOf(query.toLowerCase()) < b.text.toLowerCase().indexOf(query.toLowerCase())) {
            return -1;
        } else {
            if(a.text > b.text)
                return 1;
            else
                return -1;
        }
		}
        return a.text.toLowerCase().indexOf(query) -
          b.text.toLowerCase().indexOf(query);
      });
    },
    ajax: {
      delay: 250,
      transport: function(params, success, failure) {
        let pageSize = 1000;
        let term = (params.data.term || '').toLowerCase();
        let page = (params.data.page || 1);
        if (timer) {
          clearTimeout(timer);
        }
		//console.log(perem);
		//console.log(myarray);
		//console.log(term);
        timer = setTimeout(function(){
          timer = null;
          let results = myarray
          .filter(function(f){
              return f.toLowerCase().includes(term);
          }).sort(function(a, b) {
			 if(perem == '#js-warehouses')
			 {
				return 0;
                if(a.toLowerCase().indexOf(term.toLowerCase()) > b.toLowerCase().indexOf(term.toLowerCase())) {
					return 1;
				} else if (a.toLowerCase().indexOf(term.toLowerCase()) < b.toLowerCase().indexOf(term.toLowerCase())) {
					return -1;
				} else {
					if(a > b)
						return 1;
					else
						return -1;
				}
			 }
        	else
			{
            	return a.toLowerCase().indexOf(term) - b.toLowerCase().indexOf(term);
			}
          })
          .map(function(f){
              return { id: f, text: f}; 
          });
          let paged = results.slice((page -1) * pageSize, page * pageSize);
          let options = {
              results: paged,
              pagination: {
                  more: results.length >= page * pageSize
              }
          };
          success(options);
        }, params.delay);
      }
    }
  });
  if ($('#js-warehouses').length > 0) {
    $('#js-warehouses').parent().find('span.select2.select2-container.select2-container--default').attr('style','width:100%;');
  }
}

function changeRequiredNPPro() {
  if (!change_required_nppro) {
    $('#js-cities').prop('required', false);
    $('#js-warehouses').prop('required', false);
    return;
  }
  if($('#delivery_option_'+np_id_carrier+':checked').length > 0) {
    $('#js-cities').prop('required', true);
    $('#js-warehouses').prop('required', true);
  } else {
    $('#js-cities').prop('required', false);
    $('#js-warehouses').prop('required', false);
  }
  return;
}
