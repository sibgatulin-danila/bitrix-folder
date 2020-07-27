 (function($) {
  
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
        
        
        
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();                
    
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
          })
          .keyup(function(e) {
           if(e.keyCode==13) {
             if($(".ui-menu-item").length==1) {
               var selectedText = $(".ui-menu-item:first").text();
                $('option',"#ICITY").each(function() {
                   if(selectedText==$(this).text()) {
                      $(".ui-autocomplete").hide();
                      locationChange($(this).val());
                      
                   }
                })
                
             } 
             
             
           }
        });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
	    locationChange(); 
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
	// console.dir(this.element.children( "option" ).length);
        var matcher = new RegExp('^'+$.ui.autocomplete.escapeRegex(request.term), "i" );
         var isFind= false;
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          
          if ( this.value && ( !request.term || matcher.test(text) ) ) {
            isFind= true;
            return {
              label: text,
              value: text,
              option: this
            };
          }
         }));
        if(!isFind) {
          $(".errAutoComplete").show();
        }  else
         $(".errAutoComplete").hide();
      
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
         

        this.input.val( "" ) .tooltip( "open" );
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
 
 function locationChange(lid) {
    $(".progress").show();
    
    location.href="http://poisondrop.ru/dostavka/?lid="+(lid?lid:$("#ICITY").val());
    
 }