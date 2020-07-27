<?if(!empty($arParams['PAGER_TITLE']) && count($arResult["ITEMS"])>0):?>
<div class="topContentBar"><b><?=$arParams['PAGER_TITLE']?></b></div>
<?endif?>
<div class="catalog-section clearfix">
 <?
  $c=0;
 $count = count($arResult["ITEMS"]);
   
 foreach($arResult["ITEMS"] as $cell=>$arElement):?> 
 <?
   $galleryid = $arElement['PROPERTIES']['PHOTOGALLERY']['VALUE'];
    $row = intval($c/3)+1;    
 ?>
   
   
   <?if(isset($arResult['BANNER']) && $arResult['BANNER']['LOC']=='left'):?>
     <?if($row==$arResult['BANNER']['ROW_NUM']):?>
      <? $c+=2;
        
      ?>
       <div class="section_banner section_banner_left">
	<a href="<?=$arResult['BANNER']['URL']?>"><img src="<?=$arResult['BANNER']['PIC']?>"></a><br>
	<a href="<?=$arResult['BANNER']['URL']?>" class="section_banner_name"><?=$arResult['BANNER']['NAME']?></a>
       </div>
     <?endif?>
    <?endif?>
    
   
   
   
 <div class="product_item<?if($cell == $count-1):?> mr0 last<?elseif(($c+1)%3==0):?> mr0<?endif?><?if($arElement['ACTIVE']!='Y'):?> noactive<?endif?>" data-id="<?=$arElement['ID']?>">
  <div class="inner">   
    <div class="thumb">     
     <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="view_modal">
      <?if($cell>=21):?>
       <img src="/i/empty.png" class="lazy<?if(!empty($arResult['ALBUMS'][$galleryid])):?> first<?endif?>" data-original="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" alt="" title="<?=$arElement["NAME"]?>"/> 
      <?else:?>
       <img  <?if(!empty($arResult['ALBUMS'][$galleryid])):?>class="first"<?endif?> src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>"  alt="" title="<?=$arElement["NAME"]?>" />       
      <?endif?>
      <?if(!empty($arResult['ALBUMS'][$galleryid])):?>      
        <img  class="last" src="<?=$arResult['ALBUMS'][$galleryid]?>"  alt="" title="<?=$arElement["NAME"]?>" />
      <?endif?>
     </a>
    
    </div>
<?if($arElement['PROPERTIES']['EXCLUSIVE']['VALUE']=='yes'):?>
    <span class="b_exclusive cs">эксклюзив</span>
    <div class="clear"></div>
   <?endif?>
    <div class="brand" style="margin-top:10px;">
     <a class="grc1" href="/designers/?id=<?=$arElement['PROPERTIES']['DESIGNER']['VALUE']?>"><?=$arResult['DESIGNERS'][$arElement['PROPERTIES']['DESIGNER']['VALUE']]?></a>
    </div>
    <a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="name grc3 view_modal block"><?=$arElement["NAME"]?></a>
    <?if($arElement['CATALOG_QUANTITY']>0 && $arElement['CAN_BUY']=='Y'):?>
    
    <div style="display: none">
    
    </div>
    <?
    
    if($arElement["PRICES"][PRICE_TYPE]["DISCOUNT_VALUE"] < $arElement["PRICES"][PRICE_TYPE]["VALUE"]):?>	  
       <div class="price grc6">	
              <span class="grc5" style="text-decoration:line-through"><?=$arElement["PRICES"][PRICE_TYPE]['PRINT_VALUE_VAT']?><span class="rubSymbol">a</span></span>
	      <span><?=$arElement["PRICES"][PRICE_TYPE]['PRINT_DISCOUNT_VALUE_VAT'] ?><span class="rubSymbol">a</span></span>
	</div>
     <?else:?>     
      <div class="price grc5">     
       <span><?=$arElement["PRICES"][PRICE_TYPE]['PRINT_VALUE_VAT']?><span class="rubSymbol">a</span></span>
      </div>
     <?endif?>     
    <?else:?>
    
     <?if($arElement["PRICES"][PRICE_TYPE]["DISCOUNT_VALUE"] < $arElement["PRICES"][PRICE_TYPE]["VALUE"]):?>	  
       <div class="grc6">	
              <span class="price_old2 grc5"><s><?=$arElement["PRICES"][PRICE_TYPE]['PRINT_VALUE_VAT']?></s><span class="rubSymbol">a</span></span>
	      <span><?=$arElement["PRICES"][PRICE_TYPE]['PRINT_DISCOUNT_VALUE_VAT'] ?><span class="rubSymbol">a</span></span>
	</div>
     <?else:?>     
      <div class="grc5">     
        <span><?=$arElement["PRICES"][PRICE_TYPE]['PRINT_VALUE_VAT']?><span class="rubSymbol">a</span></span>
      </div>
     <?endif?>           
      <div class="sold grc5">ПРОДАНО</div>            
    <?endif?>
  </div>
 </div>

 
   <?if(isset($arResult['BANNER']) && $arResult['BANNER']['LOC']=='right'):?>
     <?if($row==$arResult['BANNER']['ROW_NUM']):?>
     <? $c+=2; ?>
       <div class="section_banner mr0">
	<a href="<?=$arResult['BANNER']['URL']?>"><img src="<?=$arResult['BANNER']['PIC']?>"></a><br>
	<a href="<?=$arResult['BANNER']['URL']?>" class="section_banner_name"><?=$arResult['BANNER']['NAME']?></a>
       </div>
     <?endif?>
    <?endif?>
    
   
   <?
    $c++; 
   ?>  
<?endforeach;?>		
<div class="clear"></div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
 <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
<script>
 var _ps = 'section';
 var __url = document.URL;
</script>