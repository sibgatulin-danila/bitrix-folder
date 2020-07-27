<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="b-delivery_w">
<div class="title">Как доставить?</div>
<div class="b-delivery" id="delivery_detail">
<div class="error_msg">Выберите способ доставки</div>
<div class="inner">
<?
if($arResult['POST_PARAMS']["ajax_refresh"] == "delivery")
$APPLICATION->RestartBuffer();

if(!empty($arResult["DELIVERY"])):?>
	
<?
 $delivery_cnt = count($arResult["DELIVERY"]);
 global $USER;
?>

<table class="delivery_table" cellspacing="20"><tr>
<? foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
    if ($delivery_id !== 0 && intval($delivery_id) <= 0):?>
      
	  <? foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile):
	   
	      if ($arProfile["CHECKED"]=="Y") {
	         $deliverychecked=$delivery_id.":".$profile_id;
	      }
	  ?>
		       <td class="deliveryItem" id="delivery<?= $delivery_id ?>" <?if(!empty($mapCounter['delivery'.$delivery_id])):?>onclick="yaCounter21794221.reachGoal('<?=$mapCounter['delivery'.$delivery_id]?>');" <?endif?> data-id="<?=$delivery_id?>" data-val="<?=$delivery_id.':'.$profile_id?>">		         
			       <div class="d_inner">
				 <?if(!empty($arProfile['DESCRIPTION'])):?>
				   <div class="deliveryDescription"><div class="d_arr"></div><?=$arProfile['DESCRIPTION']?></div>
				   <?endif?>
				 	<label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>">
					   <div style="padding-top:<?=40-intval($arDelivery['LOGOTIP']['HEIGHT']/2)?>px">
						<img src="<?=$arDelivery['LOGOTIP']['SRC']?>" />
						 </div>					        
						<?=$arDelivery["TITLE"]?>
					      
						<? $APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', array(
										"NO_AJAX" => $arResult['POST_PARAMS']["ajax_refresh"] == "paysystems"?'N':$arParams["DELIVERY_NO_AJAX"],
										"DELIVERY" => $delivery_id,
										"PROFILE" => $profile_id,
										"ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
										"ORDER_PRICE" => $arResult["ORDER_PRICE"],
										"LOCATION_TO" => $arResult["USER_VALS"]["DELIVERY_LOCATION"],
										"LOCATION_ZIP" => $arResult["USER_VALS"]["DELIVERY_LOCATION_ZIP"],
										"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
									), null, array('HIDE_ICONS' => 'Y'));
						 ?>
						</label>
			        </div>
			</td>							 
	     <?endforeach?>
				
      <?else:
      
          
      ?>
	<td class="deliveryItem" data-delivery="<?=$arDelivery["PRICE_FORMATED"]?>" id="delivery<?= $arDelivery["ID"] ?>" data-val="<?=$arDelivery["ID"]?>" data-id="<?=$arDelivery["ID"]?>">
          <div class="d_inner" >
	   <?if(!empty($arDelivery['DESCRIPTION'])):?>
	    <div class="deliveryDescription"><div class="d_arr"></div><?=$arDelivery['DESCRIPTION']?></div>
	    <?endif?>
	     <label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>">
	      <div style="padding-top:<?=40-intval($arDelivery['LOGOTIP']['HEIGHT']/2)?>px">
		<img src="<?=$arDelivery['LOGOTIP']['SRC']?>" />
	      </div>
	      <?=$arDelivery["NAME"] ?>												
	      </label>
	      <?/* if($arDelivery["PRICE"]==0):?>
	       <div class="deliveryProcess grc5" style="position:absolute;bottom:-30px;width:100%;text-align:center;" onclick="$('.summaryDelivery').html('<?=$arDelivery["PRICE_FORMATED"]?>');$('#bsp').html(parseInt($('#bsp').attr('data-price'))+<?=intval($arDelivery["PRICE"])?>+'<span class=rubSymbol>a</span>');">Бесплатно</div>
              <?else:?>
	        <div class="deliveryProcess grc5" style="position:absolute;bottom:-30px;width:100%;text-align:center;" onclick="$('.summaryDelivery').html('<?=$arDelivery["PRICE_FORMATED"]?>');$('#bsp').html(parseInt($('#bsp').attr('data-price'))+<?=intval($arDelivery["PRICE"])?>+'<span class=rubSymbol>a</span>');"><?=$arDelivery["PRICE_FORMATED"]?><span class="rubSymbol">a</span></div>
	      <?endif*/?>

              </div>
	 </td> 		
	<?endif?>
</tr></table>
<input type="hidden" id="ID_DELIVERY" name="DELIVERY_ID" value="" />

<?endif?>
 
<script>
var arD2P = <?=CUtil::PhpToJSObject($arResult['D2P'])?>;
var arPaySystem = <?=CUtil::PhpToJSObject($arResult['PAY_SYSTEM'])?>;
</script>
<? if($arResult['POST_PARAMS']["ajax_refresh"] == "delivery")
 die();
?>
 </div>
</div>
</div>
