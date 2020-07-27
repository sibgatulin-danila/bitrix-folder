$(function() {
 
 $("body").delegate('.changeQuant_pack','click',function(event) { 
 var productId = $(this).attr('data-id');     
 var currQuantity = parseInt($(".quantVal",".bt"+productId).text());    
     
     if($(this).hasClass('quanAsc')) {
      var act = 'asc_quant';
      currQuantity++;
     }
     else if($(this).hasClass('quanDesc')) {
       
      if(currQuantity>0) {
       var act = 'desc_quant';
       currQuantity--;
      }     
     }
    
    $(".pack_price #price_val").text(currQuantity*pack_price);
    $(".quantVal",".bt"+productId).text(currQuantity);
    if(currQuantity>0)
     $(".quanDesc",".bt"+productId).removeClass('disabled');   
    else
     $(".quanDesc",".bt"+productId).addClass('disabled');              
       
   event.stopPropagation();
 });
 
 $(".packing_tocart").click(function() {
   
    var quant = parseInt($(".packing__box .quantVal").text());
    
    if(isNaN(quant) || quant==0) {
      quant = 0;
    }
    if(quant>0) {
     $(this).attr('data-href',$(this).attr('href')+'&quantity='+quant);
     addToCart.call(this);
    }
    return false;
 });
 
});