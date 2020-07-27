<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
include('functions.php');
?>

<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/include_areas/sale_breadcrumb.php',array('STEP'=>'DELIVERY'));?>
  <input type="hidden" name="PERSON_TYPE" value="1">
  <input type="hidden" name="PERSON_TYPE_OLD" value="1">
  

<input type="hidden" name="PROFILE_ID" id="PROFILE_ID"  value="<?=intval($checkedprofile)?>" />
<div class="title">Куда доставить?</div>  
  <div class="b-delivery" id="sof-prof-div" <?if($arResult['POST_PARAMS']['profile_change']=='Y' && $arResult['POST_PARAMS']['PROFILE_ID']>0 ):?>style="position:absolute;top:-3000px"<?endif?>>
 <div class="inner">  
  <?  PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], '', $arParams,$arResult['POST_PARAMS']);?>  
 </div>
</div>
<br/>

<?/*
$APPLICATION->IncludeComponent("pd:product.packing", ".default", array(),
	false
); */
?>
<div class="title">Кому доставить?</div>
<div class="b-delivery">
 <div class="inner">
<? PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], 'Кому доставить?', $arParams,$arResult['POST_PARAMS']); ?>
 </div>
</div>
<br>
