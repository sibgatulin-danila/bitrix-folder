function orderToCookie() {
    var NAME = $('[name=FIELD_NAME]').val();
    var LAST_NAME = $('[name=FIELD_LAST_NAME]').val();
    var EMAIL = $('[name=ORDER_PROP_6]').val();
    var CITY = $('[name=ORDER_PROP_2]').val();
    //var CITY_ID = $('[name=ORDER_PROP_2]').val();
    var PHONE = $('[name=ORDER_PROP_18]').val();
    var ADDRESS = $('[name=ADDRESS]').val();
    var PAY = $('[name=PAY_SYSTEM_ID]').val();
    var DELIVERY = $('[name=DELIVERY_ID]').val();

    $.cookie('last_order_name', NAME, { expires: 365 });
    $.cookie('last_order_last_name', LAST_NAME, { expires: 365 });
    $.cookie('last_order_email', EMAIL, { expires: 365 });
    $.cookie('last_order_city', CITY, { expires: 365 });
    //$.cookie('last_order_city_id', CITY_ID, { expires: 365 });
    $.cookie('last_order_phone', PHONE, { expires: 365 });
    $.cookie('last_order_address', ADDRESS, { expires: 365 });
    $.cookie('last_order_pay', PAY, { expires: 365 });
    $.cookie('last_order_delivery', DELIVERY, { expires: 365 });
}

function cookieToOrder() {
    var NAME = $.cookie('last_order_name');
    var LAST_NAME = $.cookie('last_order_last_name');
    var EMAIL = $.cookie('last_order_email');
    var CITY =  $.cookie('last_order_city');
    //var CITY_ID =  $.cookie('last_order_city_id');
    var PHONE = $.cookie('last_order_phone');
    var ADDRESS = $.cookie('last_order_address');
    var PAY = $.cookie('last_order_pay');
    var DELIVERY = $.cookie('last_order_delivery');

    if (NAME != 'undefined') {
        $('[name=FIELD_NAME]').val(NAME).trigger('focusout');
    }
    if (LAST_NAME != 'undefined') {
        $('[name=FIELD_LAST_NAME]').val(LAST_NAME).trigger('focusout');
    }
    if (EMAIL != 'undefined') {
        $('[name=ORDER_PROP_6]').val(EMAIL).trigger('focusout');
    }
    if (PHONE != 'undefined') {
        $('[name=ORDER_PROP_18]').val(PHONE).trigger('focusout');
    }
    if (CITY != 'undefined') {
        $('[name=ORDER_PROP_2]').val(CITY).trigger('focusout');
    }
    /*if (CITY_ID != 'undefined') {
        $('[name=ORDER_PROP_2]').val(CITY_ID);
    }*/
    if (ADDRESS != 'undefined') {
        $('[name=ADDRESS]').val(ADDRESS).trigger('focusout');
    }
    /*$('[name=PAY_SYSTEM_ID]').val(PAY);
    $('[name=DELIVERY_ID]').val(DELIVERY);*/
}

function changeAddress() {
    var city = $('[name=ORDER_PROP_2]').val();
    var address = $('[name=ADDRESS]').val();
    var newAddress = '';
    if (city.length > 0) {
        newAddress = city;
    }
    if (address.length > 0) {
        if (newAddress.length > 0) {
            newAddress += ', ' + address;
        } else {
            newAddress = address;
        }
    }
    $('[name=ORDER_PROP_5]').val(newAddress);
}

$(document).ready(function () {
    jQuery.validator.addMethod("phone", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/\(?[\d\s]{3}\)[\d\s]{3}-[\d\s]{2}-[\d\s]{2}$/);
    }, "");

    jQuery.validator.addMethod("validEmail", function(value, element)
    {
        if(value == '')
            return true;
        var temp1;
        temp1 = true;
        var ind = value.indexOf('@');
        var str2=value.substr(ind+1);
        var str3=str2.substr(0,str2.indexOf('.'));
        if(str3.lastIndexOf('-')==(str3.length-1)||(str3.indexOf('-')!=str3.lastIndexOf('-')))
            return false;
        var str1=value.substr(0,ind);
        if((str1.lastIndexOf('_')==(str1.length-1))||(str1.lastIndexOf('.')==(str1.length-1))||(str1.lastIndexOf('-')==(str1.length-1)))
            return false;
        str = /(^[a-zA-Z0-9]+[\._-]{0,1})+([a-zA-Z0-9]+[_]{0,1})*@([a-zA-Z0-9]+[-]{0,1})+(\.[a-zA-Z0-9]+)*(\.[a-zA-Z]{2,3})$/;
        temp1 = str.test(value);
        return temp1;
    }, "");

    $('.wrap, #order_form_div').css('padding', 0);
    $('label').on('click', function(e) {
        e.preventDefault();
    });
    function changeName() {
        var FIELD_NAME = $('[name=FIELD_NAME]').val();
        var FIELD_LAST_NAME = $('[name=FIELD_LAST_NAME]').val();
        var ORDER_PROP_7 = FIELD_NAME + ' ' + FIELD_LAST_NAME;
        $('input[name=ORDER_PROP_7]').val(ORDER_PROP_7);
    }
    changeName();
    $("input[name=ORDER_PROP_18]").mask("+7 (999) 999-99-99");
    if ($('.radio_btn').length > 0) {
        $('.radio_btn').radiobutton();
    }
    $('.auth-modal, .ov').hide();

    $('[name=FIELD_NAME], [name=FIELD_LAST_NAME]').on('change', function() {
        changeName();
    });
    var validator = $('#ORDER_FORM').validate({
        rules: {
            FIELD_NAME: {
                required: true,
                minlength: 3
            },
            FIELD_LAST_NAME: {
                required: true,
                minlength: 3
            },
            ORDER_PROP_18: {
                required: true,
                minlength: 18,
                maxlength: 18,
                phone: true
            },
            ORDER_PROP_6: {
                required: true,
                //validEmail: true,
                email: true,
                minlength: 5
            }
            /*CITY: {
                required: true,
                minlength: 3
            },*/
            /*ORDER_PROP_2: {
                required: true,
                number: true,
                minlength: 1,
                min: 1
            }*/
        },
        /*messages: {
            ORDER_PROP_2: {
                required: 'Выберите город из списка.',
                number: 'Выберите город из списка.',
                minlength: 'Выберите город из списка.'
            }
        },*/
        submitHandler: function(form) {
            changeName();
            changeAddress();
            /*var city_id = $('[name=ORDER_PROP_2]').val();
            if (parseInt(city_id) > 0) {*/
                orderToCookie();
                var url = $('[name=path]').val();
                $.post('/new/personal/order/',{'POST': $('#ORDER_FORM').serialize()},function(data) {
                    $('body').html(data);
                });
            /*} else {
                $('[name=CITY]').val('').trigger('focus');
                validator.element('[name=CITY]');
                $('.city_error').show();
            }*/

        }
    });

    $('[name=ORDER_PROP_6]').on('keyup', function() {
        validator.element('[name=ORDER_PROP_6]');
    });

    $('[name=RB_PAY_SYSTEM_ID]').on('change', function() {
        var thisVal = $(this).val();
        if (thisVal == 2) {
            $("#submitButtonOrder").val('Оплатить');
        } else {
            $("#submitButtonOrder").val('Отправить заказ');
        }
        $('[name=PAY_SYSTEM_ID]').val(thisVal);
    });
    $('[name=RB_DELIVERY_ID]').on('change', function() {
        var thisVal = $(this).val();
        if (thisVal == 4) {
            $('#city_line, #address_line').hide();
           /* $('#ID_CITY').attr('name', 'CITY-b');
            $('#ID_ORDER_PROP_2').attr('name', 'ORDER_PROP_2-b');
            $('#ID_ADDRESS').attr('name', 'ADDRESS-b');*/
            $('.pickup').show();
        } else {
            $('#city_line, #address_line').show();
            /*$('#ID_CITY').attr('name', 'CITY');
            $('#ORDER_PROP_2').attr('name', 'ORDER_PROP_2');
            $('#ADDRESS').attr('name', 'ADDRESS');*/
            $('.pickup').hide();
        }
        $('[name=DELIVERY_ID]').val(thisVal);
    });

    cookieToOrder();
});

/*function cityChange() {
  $("#ajax_refresh").val('delivery');
  $("#confirmorder").val('N');
  $("#profile_change").val("N");
  $("#delivery_detail .inner").fadeTo(0,0.5).append('<div class="progress"></div>');
  $("#paysystemsWrap").html('');
  submitOrderForm(function(data) {   
    $("#delivery_detail .inner").html(data).fadeTo(0,1);
     if($(".deliveryItem").length==1) {
       $(".b-delivery_w").hide();
       $(".deliveryItem:first").trigger('click');
     }
     else
       $(".b-delivery_w").show();
  });    
}*/

/*$(function() {
  $("#comments_show").live('click',function() {
    $(".comments").show();
    $(this).hide();
  });
  
  if($("#COUNTRY_ORDER_PROP_2ORDER_PROP_2").val()==24) {
     $("input[name=ORDER_PROP_18]").mask("+7 (999) 999-99-99");  
    }
  
  $("#submitButtonOrder").live('click',function() {
    if($("input[name=DELIVERY_ID]").val()=='') {
      $("#delivery_detail").addClass('b_error');      
    }    
    
    if($("#PAY_SYSTEM_ID").val()=='') {
      $("#paysystems").addClass('b_error');
    }
    
    if($("#ORDER_FORM").valid() && $("input[name=DELIVERY_ID]").val()!='' && $("#PAY_SYSTEM_ID").val()!='') {
    $("#confirmorder").val('Y');
     $("#ajax_refresh").val('');
     $("#is_ajax_post").val('Y');
     $('#confirmorder').val('Y');
     $("#delivery_detail .inner").fadeTo(0,0.5).append('<div class="progress"></div>');  
     submitOrderForm(function(data) {       
        $("#order_form_content").html(data);
	
     },'Y');
    } else {
       $.scrollTo($("input.error:first").offset().top-80,400,{axis:'y'});
       $("label.error").each(function() {
        if($(this).text()!='')
          $(this).closest('.input_wrap').addClass('validate_fail');       
       });
       
       if($('input[name=CITY]').val()=='')  {
           $('.custom-combobox').addClass('validate_fail');          
       }
    }
    return false;
  });
  
  $(".deliveryItem").live('click',function() {
     var delivery_id = $(this).attr('data-id');
     $("#submitButtonOrder").val('Отправить заказ');
     $("#delivery_detail").removeClass('b_error');
     $(".deliveryItem").removeClass('active').filter(this).addClass('active');
     $("#ID_DELIVERY").val($(this).attr('data-val'));      
     
     $(".deliveryProcess",this).length && $(".deliveryProcess",this).get(0).onclick();
 
     $("#paysystemsWrap").html(_.template($("#paysystem_template").html())({'DELIVERY_ID':delivery_id,'arD2P':arD2P,'arPaySystem':arPaySystem})); 
     
  });
  
  $(".psa_item").live('click',function() {
    $("#paysystems").removeClass('b_error');
    $("#PAY_SYSTEM_ID").val($(this).attr('data-id'));
    $(".psa_item").removeClass('active').filter(this).addClass('active');
    if($(this).attr('data-id')==2)
     $("#submitButtonOrder").val('Оплатить');
    else
     $("#submitButtonOrder").val('Отправить заказ');
    $("#PAY_SYSTEM_ID").val($(this).attr('data-id'));   
   });
  
  

 
  $(window).scroll(function() {
    var scrollTop = $(this).scrollTop();     
     if(scrollTop>$(".b_site_content_bar").offset().top) {
       $(".basketOrder").addClass("fixedBasket");
     } else
      $(".basketOrder").removeClass("fixedBasket");
 });
  

 /*$("#ORDER_FORM").validate({
       rules: {		 
                 ORDER_PROP_18:"required"
	      },
	      messages: {		
                ORDER_PROP_18: "Введите номер телефона"                    
              },
  invalidHandler: function(event, validator) {
   
   
  },  
  success: function(label) {
    label.closest('.validate_fail').removeClass('validate_fail');    
    
  }

});*/


/*
(function($) {
  
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();        
        this.input.attr('name','CITY');
        $("input[name=CITY]").rules("add", {required: true,
                              messages: {
                                     required: "Введите город"
                              }});
         if($.browser.msie) { 
        $("form").find("input[type='text']").each(function() {
            var tp = $(this).attr("placeholder");
            if(!$(this).val())
             $(this).attr('value',tp).css('color','#ccc');
        }).focusin(function() {
            var val = $(this).attr('placeholder');
            if($(this).val() == val) {
                $(this).attr('value','').css('color','#303030');
            }
        }).focusout(function() {
            var val = $(this).attr('placeholder');
            if($(this).val() == "") {
                $(this).attr('value', val).css('color','#ccc');
            }
        });
        
        $("form").submit(function() {
            $(this).find("input[type='text']").each(function() {
                var val = $(this).attr('placeholder');                
                if($(this).val() == val) {
                    $(this).attr('value','');
                }
            })
        });
    }
    
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,	    
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
	    cityChange(); 
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )          
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
	
        var matcher = new RegExp('^'+$.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
         }));
      },
 
      _removeIfInvalid: function( event, ui ) {
         
        if ( ui.item ) {
          return;
        } 
        
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
          this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        } 

        this.input
          .val( "" ) .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500);
        this.input.data( "ui-autocomplete" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });

})( jQuery );


})
*/
