function modalClose() {
   $("#view__modal",$(".view__modal__wrap").removeClass('visible')).html('');
   $("body").removeClass('noScroll');   
 }
 
 var beginURL='';
var sortContent = {};

 
function acceptFilter(reload) {
  var strFilter = '';
  window.viewList = window.viewList || '';
  
  for(filterProp in filterContent) {    
    strFilter+='&'+filterProp+'='+filterContent[filterProp].join(',')	;
  }
  if(sortContent.price) 
   strFilter+='&sort=price&order='+sortContent.price;  
  $(".sectionWrap").fadeTo(100,0.8);
   if(reload=='Y')  {
      
      location ='/catalog/'+window.currentSection+'/?'+strFilter; 
   } else {
      $.get('/catalog/'+window.currentSection+'/?ajax_catalog=1'+strFilter,function(data) {
        $(".sectionWrap").fadeTo(500,1).html(data);    
        setLazyImgLoad();
        
      })  
       
      try {
         history.pushState(null,null,'?'+strFilter);
       } catch(e) {
        
       }
   }
}


function setLazyImgLoad() {
 $.ias({
  container 	: ".catalog-section",
  item		: ".product_item, .catalogElement",
  pagination	: ".blog-page-navigation",
  next		: ".nav_next",
  triggerPageThreshold:100,
  thresholdMargin:-450,
  loader	: "<img class='page_nav_loader' src='/i/ajaxloader.gif'/>",
  history:false,
  onRenderComplete: function(items) {
    $("img.lazy[src='/i/empty.png']").lazyload({effect : "fadeIn"});
      $(".thumb a").hover(function() {
       $(this).addClass('active');
     },function() {
       $(this).removeClass('active');
   });
      
   $(".catalogElement .scrollThumbs .thumb").hover(function() {   
    $("#"+$(this).closest('.catalogElement').attr('id')+" .scrollThumbs .thumb").removeClass('active').filter(this).addClass('active');   
    $("#"+$(this).closest('.catalogElement').attr('id')+" .pic img").attr('src',$(this).attr('data-detail'));
     },function() {
   });
   
       
  }
 });

 $(".thumb a").hover(function() {
       $(this).addClass('active');
     },function() {
       $(this).removeClass('active');
 });
 
 $("img.lazy").lazyload({effect : "fadeIn"}); 
  
}


$(function() {
 
  if($('.priceSort').hasClass('asc')) {
       sortContent.price='asc';
  }
      if($('.priceSort').hasClass('desc')) {
       sortContent.price='desc';
     }
     
 
   $("body").delegate(".share_button",'click',function() {
  
    $("#yashare").toggleClass('act');
  });
 $("body").delegate(".scrollThumbs .thumb",'hover',function(e) { 
  if(e.type=='mouseenter') {
    $("#"+$(this).closest('.catalogElement').attr('id')+" .scrollThumbs .thumb").removeClass('active').filter(this).addClass('active');   
   $("#"+$(this).closest('.catalogElement').attr('id')+" .pic img").attr('src',$(this).attr('data-detail'));
  }
 
 });

 $("body").delegate(".designerDetailInfo #x",'click',function() {
   $(".designerDetailInfo").hide();
 });
 
 $("body").delegate("#showDesignerDetail",'click',function() {
  $(".designerDetailInfo").show();
 });
  
 $("body").delegate(".modalClose",'click',function() {
   $(".sizeDetailInfo").hide();
 });
 
 $("body").delegate(".findYourSize",'click',function() {   
   $(".sizeDetailInfo").show();
 });
 
 $("body").delegate(".propsize",'click',function() {
   $(this).closest('.size').removeClass('active');
 }); 
 
 $("body").delegate(".propsize",'change',function() {   
   $(".addToCart[data-href]",$(this).closest('.catalogElement')).attr('data-href',$(".propsize span.cuselActive").attr('val'));
 });
 
 $(".topContentBar .view").click(function() {    
     $(".topContentBar .view").removeClass('active').filter(this).addClass('active');
     if($(this).hasClass('one'))  
       window.viewList = 'byone';
     else
       window.viewList = '';
       
     $.cookie("s_view",window.viewList,{path:'/',expires: 7});     
     acceptFilter.call(this);
  });
   
   $(".colorItem").click(function() {	
     $(this).toggleClass('active');
     $("#colorActiveHolder").html('');
     var selectedColors = [];
     $(".colorItem.active").each(function() {
	var scr = $(this).css('background-image');
	selectedColors.push($(this).attr('data-id'));	
	$("#colorActiveHolder").append($('<div class="selectedColor" data-id="'+$(this).attr('data-id')+'"><img src='+scr.substr(4,scr.length-5)+' /><div class="btn_clear color_clear"></div></div>'));	
     });
     
     filterContent['colors'] = selectedColors;
     acceptFilter();     
     return false;
   });
   
   
   $(".designerItem").click(function() {	
     $(this).toggleClass('active');
     var designers = [];
     $(".designerItem.active").each(function() {
	designers.push($(this).attr('data-id')); 
     });
     
     if((cntDisigners = $(".designerItem.active").length)>0)     
      $("#counterDesigner").show().html(cntDisigners+'<div class="btn_clear"></div>');
     else
      $("#counterDesigner").hide();      
            
      filterContent['designers'] = designers;
      acceptFilter();     
      
     return false;
   });
   
   $(".designersList .name, #counterDesigner").hover(function() {
    $("#counterDesigner").addClass('hover');   
   },function() {
    $("#counterDesigner").removeClass('hover');    
   });
   
   $("#counterDesigner").click(function() {
      $(".designerItem").removeClass('active');      
      filterContent['designers'] = [];
      acceptFilter();
      $("#counterDesigner").html(0).hide();
   });
   
   $("body").delegate(".colorsList .selectedColor",'click',function() {	     
     var colorId = $(this).attr('data-id');
     index = $.inArray(colorId,filterContent['colors']);
     $(this).remove();
     $(".colorItem[data-id="+colorId+"]").removeClass('active');
     if(index>=0) {
      filterContent['colors'].splice(index,1);      
      acceptFilter();  
     }
     
     return false;
   });
   
    $(".thumb a").hover(function() {
       $(this).addClass('active');
     },function() {
       $(this).removeClass('active');
   });
   
     
   $("body").delegate(".filterItem",'click',function() {
      var fi = $(this);
      $(".filterContent").each(function() {
	if($(this).closest(fi).length)
	 $(this).toggleClass('active');
	else
	$(this).removeClass('active');      
      });
      return false;
   });
   
  $(".priceSort").click(function() {
     if(!sortContent.price)
      sortContent.price='asc';
     
    
     
     if(sortContent.price=='asc')
       sortContent.price='desc';
     else
       sortContent.price='asc';
      acceptFilter('Y');
     if(sortContent.price=='asc') {
      $(this).removeClass('desc').addClass('asc'); 
     } else {
      $(this).removeClass('asc').addClass('desc'); 
     }
  });
  
 setLazyImgLoad();
 
 $('body').delegate('.view_modal','click',function(e) {
   var pi = $(this).closest('.product_item');
   var speed=0,arr_up=-10000;
   var t = 280;
   if($(".view__modal__wrap").length==0) {
     $('body').append('<div class="view__modal__wrap clearfix"><div class="bg_wrapper"></div><div id="view__modal"></div></div>');
   }
 
   if(!pi.length==0) {   
    var afterInsertArea = pi.hasClass('mr0')?pi:(pi.next().hasClass('mr0')?pi.next():pi.next().next());       
    arr_up = pi.position().left-$(".siteContent").offset().left+90;
    speed=500;    
    pi.append('<img class="loader" src="/i/preloader-15-5.gif"/>');    
   }
   
   var isLast = afterInsertArea.hasClass('last')?true:false;
   
   try {
    history.pushState(null,null,$(this).attr('href'));
   } catch(e) {} 
      
   var that = this;
      
   $.scrollTo(afterInsertArea.offset().top+280,speed,{axis:'y',onAfter: function() {   

     $.get($(that).attr('href')+'?product_ajax=1',$.proxy(function(response) {      
      $(".clear",".catalog-section").remove();
      $(".view__modal__wrap").removeClass('visible');
      $("#view__modal").css({height:0});        
      $(".view__modal__wrap").insertAfter(afterInsertArea).before('<div class="clear"></div>');
        
      $("#view__modal",$(".view__modal__wrap")).html(response);      
      var __title = eval("window.__title") ;
      
      window.yaCounter21794221.hit($(that).attr('href'),__title,'');
      
      ga('send', 'pageview', {
         'page': $(that).attr('href'),
         'title': __title
        });
      
       
       if(!isLast) {
         $.scrollTo(afterInsertArea.offset().top+280,0);   
      }
             
        
        $(".bg_wrapper").css({left:(-20-parseInt($(".siteContent").offset().left)+$(document).scrollLeft())+'px',width:($(document).width()-$(document).scrollLeft())+'px'});
        $(".view__modal__wrap").addClass('visible').find("#view__modal").animate({height:'590px'},isLast?0:300,function() {
         $(".loader",pi).remove();
         $("#view__modal .arr_up").css({'left':arr_up+'px'});
         if(isLast) {
           $.scrollTo(afterInsertArea.offset().top+280,0);    
         }
         
         
        });      
      
      cuSel({changedEl: "#view__modal .styled"});
      
    },that));     
        
   }});
   
    
    e.stopPropagation();
    return false; 
 });
 
 $('body').delegate('#view__modal  .m_close','click touchend',function(e) {
   
   $("#view__modal .arr_up").removeAttr('style');
   $("#view__modal").animate({height:'0px'},500,function() {   
      $(".view__modal__wrap").removeClass('visible');
      modalClose();      
   });
   try {   
   var __url = window.__url || 0;
    if(__url) {
     history.pushState(null,null,__url);
    }
   } catch(e) {}  
   e.stopPropagation();
   return false;
 });
  
 $('body').delegate('#view__modal','click touchstart',function(e) {    
    e.stopPropagation();
    
 });
 
 
  $('body').delegate(".notify_btn",'click touchstart',function() {
    var that = this;        
    if(authorized) {      
      
    } 
 });
  
  
 

  $(window).bind('popstate', function(e) {
   if($(".view__modal__wrap").hasClass('visible')) {
      
    ///if(document.URL==__url)
    if(2==2)
    {
      
      $("#view__modal .arr_up").removeAttr('style');
      $("#view__modal").animate({height:'0px'},500,function() {   
       $(".view__modal__wrap").removeClass('visible');
       modalClose();
      
     });
      
      
    } else {
      
    $("#view__modal  .e__in").append('<img class="loader" src="/i/ajaxloader.gif"/>');
    $.get(document.URL+'?product_ajax=1',function(data) {
      
      $("#view__modal",$(".view__modal__wrap")).html(data);
      $.scrollTo($(".view__modal__wrap").offset().top-280,0);
      if(!$(".view__modal__wrap").hasClass('visible')) {
         $(".bg_wrapper").css({left:(-20-parseInt($(".siteContent").offset().left)+$(document).scrollLeft())+'px',width:($(document).width()-$(document).scrollLeft())+'px'});
         $(".view__modal__wrap").addClass('visible').find("#view__modal").animate({height:'590px'},300,function() {          
        });
      }      
      
      cuSel({changedEl: "#view__modal .styled"});       
    });    
    e.stopPropagation();
    return false;
   }
   } 
   
   
   if(_ps == 'element' && __url!=location.href) {        
      location.reload();
   }
   return false;
 
  
   
   
 });
 
  
 $('body').delegate(".product_tabs .tab",'click',function() { 
    $(this).addClass('active').siblings().removeClass('active');
    $("."+$(this).data('rel'),$(this).closest('.detail')).addClass('active').siblings().removeClass('active'); 
  });
 
 


});