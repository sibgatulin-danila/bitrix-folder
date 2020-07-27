$(function() {
  
 $(".scrollThumbs .thumb").hover(function() {   
   $("#"+$(this).closest('.catalogElement').attr('id')+" .scrollThumbs .thumb").removeClass('active').filter(this).addClass('active');   
   $("#"+$(this).closest('.catalogElement').attr('id')+" .pic").html('<img src="'+$(this).attr('data-detail')+'"/>');
 },function() {
 });


 $("#x").click(function() {
   $(".designerDetailInfo").hide();
 });
 
 $("#showDesignerDetail").click(function() {
    $(".designerDetailInfo").show();
 });
 
 
 $(".modalClose").click(function() {
   $(".sizeDetailInfo").hide();
 });
 
 $(".findYourSize").click(function() {
    $(".sizeDetailInfo").show();
 });

})


