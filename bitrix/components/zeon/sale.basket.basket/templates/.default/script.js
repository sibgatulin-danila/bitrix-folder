var basketEvents = {},
    clearTimeoutBasket = null;
    isLoading = false;

_.extend(basketEvents,Backbone.Events);


basketEvents.on('refreshBasket',function(args) {
	
   clearTimeout(clearTimeoutBasket);
   clearTimeoutBasket = setTimeout(function() { 
	var params = {AJAX:'Y','O':args.O,'ID':isFinite(args.ID)?args.ID:"",VAL:isFinite(args.VAL)?args.VAL:""};
        
	$("#basket_form_container").fadeTo(100,0.9);
	isLoading = true;        
	$.get("/personal/cart/",params,function(response) {
         
	  $("#basket_form_container").html(response);
	  $(".cart_basket_item").each(function() {
	  new TCartItemView({el:$(this),model: new TCartItem($(this).data('json'))});
	  });          
                    
	  $("#basket_form_container").fadeTo(100,1.0);
	  isLoading = false;
	});
   },400);
   
   
});


var TCartItem = Backbone.Model.extend({
    initialize:function() {
       this.set("img_loaded",false);
    }
    
 });



var TCartItemView = Backbone.View.extend({
   events:{
   'click .b-quantity .b-quantity_inc':"quantityChange",
   'click .b-quantity .b-quantity_dec':"quantityChange",   
   'click .cart_basket_item-del':'deleteItem',
   'hover .cart_basket_item-thumb':"hoverThumb"
   },
   initialize:function() {
        var q = +this.model.get('QUANTITY'),
	    max_q = +this.model.get('MAX_QUANTITY');
        if(q<=1)
	  this.$(".b-quantity_dec").addClass('disabled');
	 if(q>=max_q) {
	  this.$(".b-quantity_inc").addClass('disabled');
	 }

   },
   quantityChange:function(e) {
     if(isLoading) return false;
     
      var quantity = +this.model.get('QUANTITY'),
        max_q = +this.model.get('MAX_QUANTITY');
    if(!$(e.currentTarget).hasClass('disabled')) {
      this.$(".b-quantity_dec, .b-quantity_inc").removeClass('disabled');
     if($(e.currentTarget).hasClass('b-quantity_inc'))  {
       quantity++;
       if(quantity>=max_q)  {
	  quantity = max_q;
	  $(e.currentTarget).addClass('disabled');
	}
     }
     else {
       quantity--;
       if(quantity<=1)  {
	  quantity = 1;
	  $(e.currentTarget).addClass('disabled');
	}
	 
     }        
      this.model.set('QUANTITY',quantity);      
      this.$('.b-quantity_val').text(this.model.get('QUANTITY'));      
      basketEvents.trigger("refreshBasket",{O:'CHANGE_QUANT','ID':this.model.get('ID'),'VAL':this.model.get('QUANTITY')});
    }
     return false;
   },   
   deleteItem:function() {
      if(isLoading) return false;
      basketEvents.trigger("refreshBasket",{O:'DELETE','ID':this.model.get('ID')});
      return false;
   },
   hoverThumb:function(e){
      if(e.type=='mouseenter') {
        this.$(".cart_basket_item-img").show();
        if(!this.model.get("img_loaded") && this.model.get('IMG')) {         
         this.$(".cart_basket_item-img").append('<img src="'+this.model.get('IMG')+'" />');
         this.model.set("img_loaded",true);
        }
      }   else
        this.$(".cart_basket_item-img").hide();       
   }
});


$(function() {
  $(".cart_basket_item").each(function() {
    new TCartItemView({el:$(this),model: new TCartItem($(this).data('json'))});
  });
 $(".siteContent").delegate(".b-promocode_ref",'click',function() {
    $(this).css('visibility','hidden');	
    $(".b-promocode_form").show();
 })
  
});