var TCatalogFilter = Backbone.Model.extend({ 
 initialize:function() {   
   this.selections = [];
 },
 addVal:function(val) { 
    this.selections.push(val);
 },
 removeVal:function(val) {  
   this.selections.splice(this.selections.indexOf(val),1);
 },
 reset:function() {
   this.selections=[];
   return this;
 },
 getFilterURL:function() {
    if (this.get('filterKey') && this.selections.length) {      
      return   this.get('filterKey')+'='+encodeURIComponent(this.selections.join(','));
    }
    return "";
 }   
 
});

var TCatalogFilterView = Backbone.View.extend({
   events:{
      "click .b_catalog_filter_title":"showItems",
      "click .b_catalog_filter_item":"checkItem",
      "click .b_catalog_filter_reset":"resetItems",
      "click .b_catalog_filter_discount":"checkItem"
   },
   initialize:function() {
   var model = this.model;
       console.log(this.$el);
     if(this.$el.hasClass('b_filter_designer')) {
       this.model.set('filterKey','designers');
     }     
     if(this.$el.hasClass('b_filter_colors')) {
         this.model.set('filterKey','colors');
     }
     if(this.$el.hasClass('b_filter_sizes')) {
       this.model.set('filterKey','sizes');
     }
     if(this.$el.hasClass('b_filter_discount')) {
       this.model.set('filterKey','discount');
     }
     
     this.$(".b_catalog_filter_item-active").each(function() {
      if ($(this).data('id')) {
          model.selections.push($(this).data('id'));
      }     
     });
   },
   showItems:function(e) {     
     $(".b_catalog_filter").not(this.$el).removeClass('b_catalog_filter-active');
     this.$el[!this.$el.hasClass("b_catalog_filter-active")?'addClass':'removeClass']("b_catalog_filter-active");
     e.stopPropagation();
   },
   checkItem:function(e) {
      var $target = $(e.currentTarget),
            itemVal = $target.data('id');
       if($target.hasClass('b_catalog_filter_item-active')) {
        this.model.removeVal(itemVal);
        $target.removeClass("b_catalog_filter_item-active");
        if(this.model.get('filterKey')=='colors') {                   
          this.$(".b_catalog_filter_reset[data-id='"+itemVal+"']").parent().remove();
        }
      } else {
        this.model.addVal(itemVal);
        $target.addClass("b_catalog_filter_item-active");
        
        if(this.model.get('filterKey')=='colors') {          
          var src = $target.css('background-image').substring(4,$target.css('background-image').length-1);
           this.$(".b_filter_color_selections").append('<div class="b_filter_color_selections_item">\
                            <img src="'+src+'">\
                        <span class="b_catalog_filter_reset" data-id="'+itemVal+'"></span>\
	                 </div>');
        }      
      }
      
      if(this.model.get('filterKey')=='designers') {
        if (this.model.selections.length) {
          this.$(".b_filter_designer_counter").show().find(".b_filter_designer_counter_val").text(this.model.selections.length);
        } else
         this.$(".b_filter_designer_counter").hide();             
      }

      if(this.model.get('filterKey')=='sizes') {
        if (this.model.selections.length) {
          this.$(".b_filter_sizes_counter").show().find(".b_filter_sizes_counter_val").text(this.model.selections.length);
        } else
         this.$(".b_filter_sizes_counter").hide();             
      }
      
      this.model.collection.acceptFilter();
      return false;
   },
   resetItems:function(e) {
     if(this.model.get('filterKey')=='designers') {
       this.$(".b_filter_designer_counter").hide();
       this.$(".b_catalog_filter_item").removeClass("b_catalog_filter_item-active");
       this.model.reset();
     }
     
     if(this.model.get('filterKey')=='colors') {
      var removeColorId = $(e.currentTarget).data('id');
        this.model.removeVal(removeColorId);             
        this.$(".b_catalog_filter_item[data-id='"+removeColorId+"']").removeClass("b_catalog_filter_item-active");        
        $(e.currentTarget).parent().remove();
     }

    if(this.model.get('filterKey')=='sizes') {
       this.$(".b_filter_sizes_counter").hide();
       this.$(".b_catalog_filter_item").removeClass("b_catalog_filter_item-active");
       this.model.reset();
     }
     
     this.model.collection.acceptFilter();
     return false;
   }
   
});

var TCatalogFilterCollection = Backbone.Collection.extend({
  model: TCatalogFilter,
  getFiltersURL:function() {      
     var tmpURL = [];
     this.each(function(filter) {
         tmpURL.push(filter.getFilterURL());
     });
     
  
     return tmpURL.join(tmpURL.length?'&':'');
  },
  acceptFilter:function(params) {
       var $rootEl =  $(".b_catalog");       
       $rootEl.fadeTo(200,0.8);       
       var sectionUrl = $(".b_catalog_section").data("href");
       var url  = sectionUrl+'?'+this.getFiltersURL()+(params && params instanceof Array?params.join('&'):"");
       $.get(url+'&ajaxid=catalog',function(data) {         
         $rootEl.fadeTo(500,1).html(data);
         $(".b_product_item").each(function() {       
         new TProductListItemView({el:this,model:new TProductListItem($(this).data('json'))});
       });
      })  ;
       
      try {
         history.pushState(null,null,url);
       } catch(e) {     }
       
   }
});

var TCatalogSortView = Backbone.View.extend({
   events:{
      "click ":"doSort"     
   },
   doSort:function() {      
      var order =this.$el.hasClass('b_catalog_sort_item-asc')?'desc':'asc';      
      this.$el.removeClass('b_catalog_sort_item-asc b_catalog_sort_item-desc').addClass('b_catalog_sort_item-'+order);
      this.collection.acceptFilter(["sort=price","order="+order]);
      
   }
});
$(function() {
    var filterCollection = new TCatalogFilterCollection();
    $(".b_catalog_filter").each(function() {
        var catalogFilter = new  TCatalogFilter();
        filterCollection.add(catalogFilter);
        new TCatalogFilterView({el:this,model:catalogFilter });
    });
    
    $(".b_catalog_sort_item").each(function() {        
        new TCatalogSortView({el:this,collection:filterCollection});
    });
    
    
})

  