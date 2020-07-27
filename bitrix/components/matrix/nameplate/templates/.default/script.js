
var TNameplateView = Backbone.View.extend({
  events:{
    "keyup .chain_text":"changeChainText",
    "click .chain_text":"clickChainText",
    "click .font_letter":"changeFont",
    "click .chain__type":"changeChain",    
    "click .metall":"changeMetall",
    "click .nameplate_content__right_tabs li":"clickViewTab",
    "click .nameplate_nav":"switchBackground",
    'click  .nameplate_share_button':'clickShare',
    "click .left_bottom_info__title": function(e) {
      var $description = this.$(".left_bottom_info__description");
      if($description.is(":visible")) {
       $description.fadeOut(100); 
      } else {
       $description.fadeIn(100);  
      }
    }
    
  },
  initialize:function() {    
    this.timeReload=0;
    this.acceptFilter();
    var  self = this; 
    if(!this.share) {
        this.share =   new Ya.share({
            element: 'yashare',
                elementStyle: {
                    'type': 'button',
                    'border': true,
                    'quickServices': ['vkontakte','facebook','twitter']                
                },
               title:'Именная подвеска по вашему дизайну',
            link:'http://poisondrop.ru/namenecklaceconstructor/?3&'+self.getFilterParam(),
            image:'http://poisondrop.ru/namenecklaceconstructor/gentext.php?'+self.getFilterParam()+'&scale=1',
            
            description:'Именная подвеска по вашему дизайну'        
          });
      }
      setTimeout($.proxy(function() {
        this.$(".nameplate__option_text").addClass('showbuble');        
       },this),2000);
      
  },
  clickSliderNav:function(e) {
    var i = $(e.currentTarget).data('index'),
        that = this;
        
     $(e.currentTarget).addClass('active').siblings().removeClass('active');    
     return false; 
  },
  changeChainText:function(e) {
    this.acceptFilter();    
  },
  clickChainText:function(e) {    
    if($(e.currentTarget).val()=='Carrie') {
       $(e.currentTarget).val('');
    }
  },
  changeFont:function(e) {
    $(e.currentTarget).addClass('active').siblings().removeClass('active');
    this.acceptFilter();
  },
  changeChain:function(e) {
    $(e.currentTarget).addClass('active').siblings().removeClass('active');
     this.acceptFilter();    
  },  
  changeMetall:function(e) {
    $(e.currentTarget).addClass('active').siblings().removeClass('active');
    this.acceptFilter();    
  },
  clickViewTab:function(e) {
    var rightContentID = $(e.currentTarget).data('rel');
    $(e.currentTarget).addClass('active').siblings().removeClass('active');
    $(".tab_content").removeClass('active').filter("."+rightContentID).addClass('active');
  },
  acceptFilter:function() {    
    if($(".chain_text").val().length>0) {
      clearTimeout(this.timeReload);
      var that  = this;
      this.$("#loaderImage").show();
      this.timeReload = setTimeout(function() {
        if(that.getFilterParam())
          $("#nameplate_text").attr('src',"/namenecklaceconstructor/gentext.php?"+that.getFilterParam()).show();
          //$("#nameplate_text").attr('src',"/namenecklaceconstructor/gentext.php?date="+(new Date())+"&"+that.getFilterParam()).show();
         else    
            $("#nameplate_text").hide();
      
      },500);
      if(this.share) {
        var src_i  = 'http://poisondrop.ru/namenecklaceconstructor/gentext.php?'+this.getFilterParam()+'&scale=1';         
           this.share.updateShareLink('http://poisondrop.ru/namenecklaceconstructor/?'+this.getFilterParam(),'Именная подвеска по вашему дизайну',{
                                  facebook: {                                
                                    image:src_i                                
                                  },
                                  vkontakte: {                                
                                    image:src_i                                
                                  }
                     });
      }
       
    }
    var offerData = $(".metall.active").data('offer');
     
    
    var wishList = window.wishList || {};

    if(wishList && wishList[offerData.ID]) {
       this.$('.to__wishList').addClass('active');  
    } else
     this.$('.to__wishList').removeClass('active');  
    
    
    this.$('.addToCart').attr('data-href',offerData.ADD_URL+'&'+this.getFilterParam());
    this.$('.to__wishList').attr({'data-id':offerData.ID,'data-params':this.getFilterParam()});
    this.$('#nameplate_price').text(offerData.PRICE);
    this.$("#tab_font").trigger('click');
    
  },
  getFilterParam:function() {
    var arrParams=[];
    var text = this.$el.find(".chain_text").val();
    if(text.replace(/ /g,'').length>0) {    
     arrParams.push("text="+encodeURIComponent(this.$el.find(".chain_text").val()));
     arrParams.push("f="+this.$el.find(".font_letter.active").attr('id'));
     arrParams.push("c="+this.$el.find(".metall.active").data('color'));
     arrParams.push("ct="+this.$el.find(".chain__type.active").data('type'));
     return arrParams.join('&');
    } else
     return false;
  },
  switchBackground:function(e) {
     var color = $(e.currentTarget).data('color');
     $(e.currentTarget).addClass('active').siblings().removeClass('active');
     $(".siteContent").removeClass("nameplate_bg_wrapper_green nameplate_bg_wrapper_red nameplate_bg_wrapper_gray nameplate_bg_wrapper_blue").addClass('nameplate_bg_wrapper_'+color);
     this.$(".nameplate_text_bg.active").hide().removeClass('active').parent().find(".nameplate_text_bg[data-color="+color+"]").show().addClass('active');     
  } ,
  clickShare:function() {
     var self = this;      
       $("#yashare").toggleClass('visible');
  }
  
});

$(function() {	
  new TNameplateView({el:$(".nameplate_content")});
  
  /*$(window).bind("resize touchend",function() {    

  var left  = (-20-parseInt($(".siteContent").offset().left)+$(document).scrollLeft());
       var width = $(document).width()-$(document).scrollLeft()-1;
       if(width>=$("#sitePageWrapper").width()-1)
        width=$("#sitePageWrapper").width();
      $(".fw").css({left:left+'px',width:width+'px'});
  
    
  }).trigger('resize');
  */
  
});