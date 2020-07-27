<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
echo ShowError($arResult["ERROR_MESSAGE"]);
?>
<br /><br />

<table class="cart_basket_items" cellpadding="0" cellspacing="0">
	<tr>		
	  <th colspan="2" class="align_left">Наименование</th>
	  <th>Размер</th>
	  <th>Количество</th>
	  <th>Цена</th>
	  <th>&nbsp</th>	
	</tr>
	<? foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems):?>
		<tr class="cart_basket_item" data-json='<?=$arBasketItems['JS_DATA']?>'>
			 <td width="80">
			   <div class="cart_basket_item-thumb">
			      <a href="<?=$arBasketItems["DETAIL_PAGE_URL"] ?>" target="_blank">
				<img src="<?=$arResult['PRODUCTS'][$arBasketItems['PRODUCT_ID']]['SM_PIC']?>" width="80" style="vertical-align:middle;"/>		     
			   </a>
			     <div class="cart_basket_item-img"></div>
			   </div>
			 </td>
			 <td width="330" >
				<div class="cart_basket_item-designer grc1"><?=$arResult['PRODUCTS'][$arBasketItems['PRODUCT_ID']]['DESIGNER']?></div>
				 <a class="cart_basket_item-name grc2" target="_blank" href="<?=$arBasketItems["DETAIL_PAGE_URL"] ?>">
				 <?=$arBasketItems["NAME"] ?>
				 </a>
			 </td>						
			 <td class="align_center">
		 	  <? foreach($arBasketItems["PROPS"] as $val) {			
			       if($val['CODE'] =='SIZE_RINGS')
				    echo $val["VALUE"];
			      }
			  ?>
			 </td>			
		 	<td width="95" class="align_center">
			  <table class="b-quantity" cellpadding="0" cellspacing="0"><tr>
			   <td><div class="b-quantity_dec us-none"></div></td>
			   <td><div class="b-quantity_val"><?=$arBasketItems["QUANTITY"]?></div></td>
			   <td><div class="b-quantity_inc us-none "></div></td>
			  </tr></table>
			</td>			
			<td width="115" class="align_center">
			  <div class="cart_basket_item-price  grc5"><?=$arBasketItems["PRICE_FORMATED"]?><span class="rubSymbol">a</span><div>			
			</td>			
			<td class="align_center">
			   <div class="cart_basket_item-del us-none"></div>
			</td>			
		</tr>
		<tr class="cart_basket_items-separate">
		 <td colspan="6">&nbsp;</td>	 
		</tr>
	<?endforeach?>	
</table>


  <div class="cart_basket_summary">
   <?	if (doubleval($arResult["DISCOUNT_PRICE"]) > 0):?>
  <div class="cart_basket_summary-discount" align="right">Скидка: <span  class="grc5"><?=$arResult["DISCOUNT_PRICE_FORMATED"]?><span class="rubSymbol">a</span></span></div>
   <?endif?>
    <div class="cart_basket_summary-totalprice" align="right">Итого: <span class="grc5"><?=$arResult["allSum_FORMATED"]?><span class="rubSymbol">a</span></span></div>
    <br>
  <a  class="green_btn cart_basket_button_order" href="/personal/order/"><?=GetMessage("SALE_ORDER")?></a>   
   <div class="b-promocode" style="clear:right" align="right">
       <span class="b-promocode_ref">У меня есть promo-код!</span>      
      <div class="b-promocode_form flr">
       <div class="textWrap fll">				
	  <div class="inputWrap" style="margin-right:5px;">						 
	   <input type="text" name="COUPON" value="<?=$arResult["COUPON"]?>" size="20">	  
	  </div>	  
         </div>
        <input type="submit" class="green_btn b-promocode_form-btn flr" value="Обновить" name="BasketRefresh">		
     </div>
      
   </div>
  </div>  
 
 <div class="clear"></div>
    
   