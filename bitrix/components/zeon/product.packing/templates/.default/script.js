var TPackboxModel  = Backbone.Model.extend({});

var TPackboxView = Backbone.View.extend({
events:{
   "click .changeQuant_pack":"changeQuantity",
   'click .packing_tocart':'addPackToCart'
},
initialize:function() {},
changeQuantity:function(e) {
 var quantity = +this.model.get('QUANTITY'),
        max_q = +this.model.get('MAX_QUANTITY');
    
    if(!$(e.currentTarget).hasClass('disabled')) {
      this.$(".quanAsc, .quanDesc").removeClass('disabled');
     if($(e.currentTarget).hasClass('quanAsc'))  {
       quantity++;
       if(quantity>=max_q)  {
	  quantity = max_q;
	  $(e.currentTarget).addClass('disabled');
	}
     }
     else {
       quantity--;
       if(quantity<=0)  {
	  quantity = 0;
	  $(e.currentTarget).addClass('disabled');
          
	}
	 
     }
     if(quantity>0)
       this.$(".packing_tocart").addClass('enabled');
     else 
        this.$(".packing_tocart").removeClass("enabled")
        
      this.model.set('QUANTITY',quantity);      
      this.$('.quantVal').text(this.model.get('QUANTITY'));
      this.$('#price_val').text(this.model.get('QUANTITY')*this.model.get('PRICE'));
  }
},
addPackToCart:function(e) {
   var q = this.model.get('QUANTITY');
   if(q>0) {
       
       
      basketEvents.trigger("refreshBasket",{O:'ADD_TO_BASKET','ID':4181,'VAL':q});                  
   }
   return false;
 }
});

$(function() {
 var $packbox = $(".packing__box");
 new TPackboxView({el:$packbox,model:new TPackboxModel($packbox.data('json'))});  

});