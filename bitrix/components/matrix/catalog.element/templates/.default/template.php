<?if(!isset($arParams['IS_AJAX'])):?>
<script>
 var _ps = 'element';
 var __url = document.URL;
 $(function() {
   setTimeout(function() { $.scrollTo(0,100,{axis:'y'})} ,1000);
   
 });
</script>

<h1 class="topContentBar" align="center" style="position: relative">
 <b><?=$arResult['NAME']?><?=!empty($arResult['DESIGNER']['NAME'])?' / '.$arResult['DESIGNER']['NAME']:''?></b>
 <?  if($GLOBALS['USER']->IsAdmin() || CSite::InGroup(array(1,19))):?>
 <a href="/staff/?edit=Y&CODE=<?=$arResult['ID']?>" style="position: absolute;right:10px;top:7px;color:#000">редактировать</a>
 <?endif?>
</h1>
<?else:?>

<div class="e__in">
  <div class="arr_up"></div>
<div class="m_bg_up mb"></div>
<div class="m_bg_down mb"></div>
<div class="b__manage clearfix">

  <img src="/i/m_close.png" class="m_close" style="float:right"/> 
 <?  if($GLOBALS['USER']->IsAdmin() || CSite::InGroup(array(1,19))):?>
  <a href="/staff/?edit=Y&CODE=<?=$arResult['ID']?>" style="float:left;"><img src="/i/m_edit.png"/></a>
  <?endif?>
  <div id="yashare" style="float:left;margin-top:14px;" data-yashareL10n="ru" data-yashareQuickServices="vkontakte,facebook,twitter" data-yashareTheme="icon"></div> 
  <script>
  new Ya.share({
        element: 'yashare',
            elementStyle: {
                'type': 'button',
                'border': true,
                'quickServices': ['vkontakte','facebook','twitter']                
            },
	    title:'<?=str_replace("'","\'",$arResult['NAME'])?>',
        link:'http://poisondrop.ru<?=$arResult['DETAIL_PAGE_URL']?>',
	image:'http://poisondrop.ru<?=$arResult["DETAIL_PICTURE"]["SRC"]?>',
	description:'<?=preg_replace("/\n/","",$arResult['DETAIL_TEXT'])?>'
        
   });
  $(".b-share").prepend('<a class="b-share__handle" href="mailto:?subject=Понравилось&body=Смотри! Понравилось украшение на www.poisondrop.ru<?=$arResult['DETAIL_PAGE_URL']?>"><img src="/i/icons/share-mail.png" style="padding:0 5px;"/></a>');
  
  </script>
  <div class="share_button"></div>
 
</div>
<?endif?>
<div class="catalogElement clearfix" id="product<?=$arResult['ID']?>" itemscope itemtype="http://schema.org/Product">	
 <div class="scrollThumbs">
  <div class="thumbs">
  <?foreach($arResult['GALLERY'] as $i=>$picture):
   if($i>5) break; ?>
   <div class="thumb<?if(!$i):?> active<?endif?>" data-detail="<?=$picture['DETAIL']?>" style="background-image:url('<?=$picture['THUMB']?>')"></div>  
  <?endforeach?>
 </div>
 </div>
 
 <div class="pic">
  <?if(is_array($arResult["DETAIL_PICTURE"])):?>
    <img  itemprop="image" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
  <?endif;?>
    
  <div class="bottom_detail">
      <div class="product_code">
      Код товара: <?=str_pad($arResult['PROPERTIES']['PRODUCT_CODE']['VALUE'],6,0,STR_PAD_LEFT)?>
      </div>
      <? if($arResult['HAS_OFFERS'] && $arResult['CAN_BUY']):?>        
	        <div class="clearfix">
	   <? if(count($arResult['OFFERS'])==1) {	 
	      $arResult["ADD_URL"]=$arResult['OFFERS'][0]['ADD_URL'];
	      echo '<div>Размер: '.$arResult['OFFERS'][0]['SIZE_RINGS']['VALUE'].'</div>';
	     } else	 {
	      $arResult["ADD_URL"] = '';
	      $validate = 'size-required';
	     ?>
	     <div class="selectWrap size" style="width:128px;margin:0 0 0 0;float:left;position:relative;">
	      <select class="styled propsize" name="prop[SIZE]">
	      <option value="0">Размер</option>
	      <?
	       $i=0;
	      foreach($arResult['OFFERS'] as $offer):
	       
	        $i++;
	      ?>
	       <option value="<?=$offer["ADD_URL"]?>"><?=$offer['SIZE_RINGS']['VALUE']?></option>
	      <?endforeach?>
	      </select>		
	     </div>
	     <? if(!in_array('Универсальный',$arResult['PROPERTIES']['SIZE']['VALUE'])):?>
	        <div class="findYourSize">Узнать свой размер</div>
	     <?endif?>	     
	   <? } ?>
       </div>
     <br>
       <div class="clearfix">
	<input type="button" class="addToCart" data-validate="<?=$validate?>" data-href="<?=$arResult["ADD_URL"]?>" />
        <input type="button" class="to__wishList<? if(in_array($arResult['ID'],$arResult['WISH_LIST'])):?> active<?endif?>" data-id="<?=$arResult['ID']?>" />        
       </div>
     <?elseif($arResult['HAS_OFFERS'] && !$arResult['CAN_BUY']):?>       
       <div class="clearfix">
       <div class="grc5 fll" style="margin-right:10px;line-height:40px">ПРОДАНО</div>
       <div class="fll">
        <?$APPLICATION->IncludeComponent("matrix:sale.notice.product", ".default", array(
				"NOTIFY_ID" => $arResult['ID'],
				"NOTIFY_PRODUCT_ID" => $arParams['PRODUCT_ID_VARIABLE'],
				"NOTIFY_ACTION" => $arParams['ACTION_VARIABLE'],
				"NOTIFY_URL" => htmlspecialcharsback($arResult["SUBSCRIBE_URL"]),
				"NOTIFY_USE_CAPTHA" => "N"
				),
				$component);?>
        </div>
       </div>
     <?else:?>
        
        <?if($arResult["CAN_BUY"] && $arResult['CATALOG_QUANTITY']>0):?>
	  <div class="clearfix">
	  <input type="button" class="addToCart" data-validate="<?=$validate?>" data-href="<?=$arResult["ADD_URL"]?>" />
	  <input type="button" class="to__wishList<? if(in_array($arResult['ID'],$arResult['WISH_LIST'])):?> active<?endif?>" data-id="<?=$arResult['ID']?>" />	  
	  </div>
	<?else:?>
	<div class="clearfix">
	   <div class="grc5 fll" style="margin-right:10px;line-height:40px">ПРОДАНО</div>
	   <div class="fll">
	   <?$APPLICATION->IncludeComponent("matrix:sale.notice.product", ".default", array(
				"NOTIFY_ID" => $arResult['ID'],
				"NOTIFY_PRODUCT_ID" => $arParams['PRODUCT_ID_VARIABLE'],
				"NOTIFY_ACTION" => $arParams['ACTION_VARIABLE'],
				"NOTIFY_URL" => htmlspecialcharsback($arResult["SUBSCRIBE_URL"]),
				"NOTIFY_USE_CAPTHA" => "N"
				),
				$component
	  );?>
	   </div>
	</div>
	 
        <?endif?>
     <?endif?>
     </div>
     
     
 </div>
 <div class="detail" <?if(isset($arParams['IS_AJAX'])):?>style="padding-top:50px;"<?endif?>>
  <?if($arResult['PROPERTIES']['EXCLUSIVE']['VALUE']=='yes'):?>
    <span class="b_exclusive">эксклюзив</span>
    <div class="clear"></div>
   <?endif?>
    <?if(!empty($arResult['DESIGNER'])):?>
     <div class="designer"><a class="grc1" href="/designers/?id=<?=$arResult['PROPERTIES']['DESIGNER']['VALUE']?>"><?=$arResult['DESIGNER']['NAME']?></a>
     <?/* <img id="showDesignerDetail"  valign="middle" src="/i/i.png"/> */?>
     </div>
    <?endif?>
    <div class="name" itemprop="name">
     <?=$arResult['~NAME']?>
    </div>
    
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
     <meta itemprop="priceCurrency" content="RUB" />       
     <?if($arResult["PRICES"][PRICE_TYPE]["DISCOUNT_VALUE"] < $arResult["PRICES"][PRICE_TYPE]["VALUE"]):?>	
 	<span class="price_old grc5"><s><?=$arResult["PRICES"][PRICE_TYPE]["PRINT_VALUE_VAT"]?></s><span class="rubSymbol">a</span></span>	
	<span class="price grc6"  itemprop="price"><?=$arResult["PRICES"][PRICE_TYPE]["PRINT_DISCOUNT_VALUE_VAT"]?><span class="rubSymbol">a</span></span>
     <?else:?>
 	<span class="price grc5"  itemprop="price"><?=$arResult["PRICES"][PRICE_TYPE]["PRINT_VALUE_VAT"]?><span class="rubSymbol">a</span></span>
     <?endif?>
     </div>    
    
    
     <div class="product_tabs clearfix">     
      <div class="tab active" data-rel="description">Описание
      <div class="arr_tab"></div>
     </div>
      
     <div class="tab" data-rel="payAndback">Оплата и возврат
     <div class="arr_tab"></div>
     </div>
     <div class="tab" data-rel="delivery" style="margin-right:0;">Доставка
     <div class="arr_tab"></div>
     </div> 
    </div>
    <div class="contentTabsWrappper cleafix">
    
     <div class="content_tab description grc1 active"  itemprop="description">	
       <?=$arResult['DETAIL_TEXT']?>
     </div>
    
     <div class="content_tab payAndback grc1">
       Оплатить заказ можно как наличными курьеру непосредственно при получении заказа, так и банковской картой через интерфейс интернет-сайта. 
       <p>Если Вам не понравилась купленная вещь или не подошел размер, мы всегда обменяем ее или вернем Вам деньги. <a href="/dostavka/#v" target="_blank">Подробнее</a> об обмене и возврате. </p>
     </div>
     
     <div class="content_tab delivery grc1">
      
      Мы доставим Ваш заказ в любой город России.
      Доставка бесплатная при заказе от 3000 рублей.
      Подробнее о сроках и стоимости доставки можно узнать <a href="/dostavka/" target="_blank">здесь</a>.
     Доступен бесплатный самовывоз в г. Москве по адресу <a href="/contacts/" target="_blank">ул. Знаменка 7с3.</a> <br>
     Также возможна доставка в города Белоруссии, Украины и Казахстана.
     Подробнее о стоимости и условиях доставки узнать  <a href="/dostavka/" target="_blank">здесь</a>.


     
     </div>
    </div>
     
  </div>
  
<div class="designerDetailInfo">
 <?if(!empty($arResult['DESIGNER']['PREVIEW_PICTURE'])):?>
  <img src="<?=$arResult['DESIGNER']['PREVIEW_PICTURE']?>" style="float:left;margin-right:10px;"/> 
 <?endif?>
  <div>
    <h3><?=$arResult['DESIGNER']['NAME']?></h3>
    <?=$arResult['DESIGNER']['PREVIEW_TEXT']?>
  </div>
  
  <div id="x"></div>
</div>

<div class="sizeDetailInfo">
 <div class="inner">
 <?=$arResult['DETAIL_SIZE']?>
 </div>
 <div class="modalClose"></div>
</div>

</div>
<? if(!empty($arResult['RECOMMENDED']) && !isset($arParams['IS_AJAX'])): ?>
<div class="recProducts">
 <div class="caption">
  <span>Рекомендуем</span>
  <hr>
 </div>
<table><tr>
 <?foreach($arResult['RECOMMENDED'] as $product):?>
   <td>
    <div class="recommendProduct">
      <div class="thumb">
       <a class="view_modal" href="<?=$product['DETAIL_PAGE_URL']?>"><img src="<?=$product['PIC']?>"/></a>
      </div>
      <div class="designer grc1"><?=$product['DESIGNER']?></div>      
    </div>
   </td>
 <?endforeach?>
 </tr></table>

</div>
<?endif?>
<?if(isset($arParams['IS_AJAX'])):?>
</div>
<script>
 var __title = "<?=$arResult['NAME']?>";

</script>
<?endif?>
