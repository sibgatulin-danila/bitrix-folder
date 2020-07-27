$(function () {
   
 $(".sections .item").click(function() {      
   $('#FIELD_SECTION').val($(this).attr('data-id'));
   $(".sections .item").removeClass('active').filter(this).addClass('active'); 
 });
 
 $(".inMainLocation .item").click(function() {      
   
   
   $(this).toggleClass('active');
   if($(this).hasClass('active')) {
    $('#FIELD_LOC_'+$(this).attr('data-val')).val($(this).attr('data-val'));  
   } else {   
    $('#FIELD_LOC_'+$(this).attr('data-val')).val('');
   }
 });
 
 
  $(".colorBox .color").click(function() {         
   
   $(this).toggleClass('active');
   if($(this).hasClass('active')) {
    $('#FIELD_COLOR_'+$(this).attr('data-id')).val($(this).attr('data-id'));  
   } else {   
    $('#FIELD_COLOR_'+$(this).attr('data-id')).val('');
   }
 });


 $("#property_name").keyup(function() {
  $.getJSON('/ajax/translate.php',{text:$(this).val()},function(jsondata) {
    $("#property_code").val(jsondata.TEXT);    
  });  
 });
 
  
  $("#add_gallery").click(function() {
      
	  var leftvar = (screen.width-450)/2;
	  var topvar = (screen.height-300)/2;
	  var params = "scrollbars=0,status=0,toolbar=0,location=0,height=700,width=800,left="+leftvar+",top="+topvar;
	  window.open('/gallery/?id='+ELEMENT_ID, "", params);
	  return false;
  });

  /*$(".gallery_admin a.change").click(function() {
	  var leftvar = (screen.width-450)/2;
	  var topvar = (screen.height-300)/2;
	  var params = "scrollbars=0,status=0,toolbar=0,location=0,height=700,width=800,left="+leftvar+",top="+topvar;
	  window.open('/gallery/index.php?PAGE_NAME=section&SECTION_ID=<?=$arResult['PROPERTIES']['PHOTOGALLERY']['VALUE']?>', "", params);
	  return false;
	});	
	
      */
 
     
     
  
});