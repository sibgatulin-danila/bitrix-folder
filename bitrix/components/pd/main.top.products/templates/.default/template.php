
<div class="mainTopProducts clearfix">
<div class="b-instagram">
  <div class="instHeader">
   <a href="http://instagram.com/poisondropru" class="tdu">@poisondropru</a> в Instagram
   </div>
  <div class="photoScrollable">
   <ul class="items">
   </ul>
   
  </div>
  <div class="instNavigator clearfix">
     <a href="#" class="active"></a><a href="#"></a><a href="#"></a><a href="#"></a><a href="#"></a>
   </div>
</div>

 <div class="topProductsList">
   <?foreach($arResult['TOP'] as $top):?>
    <div class="item">        
        <div class="pic">
           <a href="<?=$top["DETAIL_PAGE_URL"]?>"><img src="<?=$top['PICTURE']?>" /></a>
        </div>
	<div class="category"><?=$top['CAPTION']?></div>
        <?/*<div class="toWishList<?if(in_array($top['ID'],$arResult['WISH_LIST'])):?> active<?endif?>" data-id="<?=$top['ID']?>"></div>
        <div class="brand grc1">
         <?=$top['DESIGNER']?>
        </div> */?>
        <a href="<?=$top["DETAIL_PAGE_URL"]?>" class="name block"><?=$top["NAME"]?></a>
        <div class="price grc5">               
         <?if(!empty($top['PRICE']["DISCOUNT_VALUE"])):?>
          <span class="price_old grc5"><s><?=$top['PRICE']['VALUE']?></s><span class="rubSymbol">a</span></span>&nbsp;
          <span class="price grc6"><?=$top['PRICE']['DISCOUNT_VALUE']?><span class="rubSymbol">a</span>	  
	  </span>
	  
         <?else:?>
          <?=$top['PRICE']['VALUE']?><span class="rubSymbol">a</span>
         <?endif?>
       </div>
    </div>
   <?endforeach?>
 </div>
</div>
<script  type="text/javascript" >
 $(function() {
	
 $('.b-instagram ul').instagramLite({
    clientID: '14f892b79e7e451395a3a7690a66a71f',
    limit:5,
    username: 'poisondropru',
    success : function() {
         $(".photoScrollable .items li").addClass('item');
          $(".photoScrollable").scrollable({items:'.items',next:null,prev:null}).navigator({navi:'.instNavigator'});
    }
});
				
			
		

 });
</script>
