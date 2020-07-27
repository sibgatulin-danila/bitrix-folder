function submitOrderForm(func_response, val) { 
 if(val != 'Y')  {
   $('#confirmorder').val('N');   
 }
 
 $.post('/personal/order/',{'POST':$("#ORDER_FORM").serialize()},function(data) {
    func_response(data);
    
    
 });
}

function cityChange() {    
  $("#ajax_refresh").val('delivery');
  $("#confirmorder").val('N');
  $("#profile_change").val("N");
  $("#delivery_detail .inner").fadeTo(0,0.5).append('<div class="progress"></div>');
  $("#paysystemsWrap").html('');
  submitOrderForm(function(data) {   
    $("#delivery_detail .inner").html(data).fadeTo(0,1);
  });    
}

$(function() {
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
          $(this).closest('.inputWrap').addClass('validate_fail');       
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
     $(".deliveryProcess",this).get(0).onclick();
 
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
     if(scrollTop>$(".topContentBar").offset().top) {
       $(".basketOrder").addClass("fixedBasket");
     } else
      $(".basketOrder").removeClass("fixedBasket");
 });
  

 $("#ORDER_FORM").validate({
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

});


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
