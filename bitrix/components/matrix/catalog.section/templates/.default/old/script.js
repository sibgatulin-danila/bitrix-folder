function acceptFilter() {
  var strFilter = '';
  window.viewList = window.viewList || '';
  
  for(filterProp in filterContent) {    
    strFilter+='&'+filterProp+'='+filterContent[filterProp].join(',')	;
  }
  if(sortContent.price) 
   strFilter+='&sort=price&order='+sortContent.price+'&view='+window.viewList;  
    
  $(".sectionWrap").fadeTo(100,0.8);
  
  if(window.viewList == 'byone') {
    if($(this).hasClass('view')) {
      location = '/catalog/'+window.currentSection+'/?'+strFilter;
      return; 
    }
  } 
  $.get('/catalog/'+window.currentSection+'/?ajax_catalog=1'+strFilter,function(data) {
    $(".sectionWrap").fadeTo(500,1).html(data);    
    setLazyImgLoad();
    
    
    
    
  })  
   
  try {
     history.pushState(null,null,'?'+strFilter);
   } catch(e) {
    
   }
}


function setLazyImgLoad() {
 $.ias({
  container 	: ".catalog-section",
  item		: ".product_item, .catalogElement",
  pagination	: ".blog-page-navigation",
  next		: ".nav_next",
  triggerPageThreshold:100,
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
    $("#"+$(this).closest('.catalogElement').attr('id')+" .pic").html('<img src="'+$(this).attr('data-detail')+'"/>');
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
      acceptFilter();
     if(sortContent.price=='asc') {
      $(this).removeClass('desc').addClass('asc'); 
     } else {
      $(this).removeClass('asc').addClass('desc'); 
     }
     
     
  });
  
 setLazyImgLoad();
 
 /*$(".product_item .thumb a").live('click',function() {
   if($(this).attr('href')) {
     location = $(this).attr('href'); 
     return false;
   }
   
 }); */
   
   
});