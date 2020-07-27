<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
  $arResult["NOTIFY_URL"] = str_replace('&ajax_catalog=Y','',  $arResult["NOTIFY_URL"]);  
 global $USER;
 ?>
<?if ($arResult["STATUS"] == "Y"):?>
 <a href="/wishlist/" class="to__wishList2 active"></a> 
<?elseif ($arResult["STATUS"] == "N"):?>
 <div class="to__wishList2" id="url_notify_<?=$arParams['NOTIFY_ID']?>"> 
   <div class="notify_btn" <?if($USER->IsAuthorized()):?>onClick="notifyProduct('<?=$arResult["NOTIFY_URL"]?>', <?=$arParams['NOTIFY_ID']?>);"<?endif?>> </div> 
 </div>
<?elseif ($arResult["STATUS"] == "R"):?>
 <div class="to__wishList2" id="url_notify_<?=$arParams['NOTIFY_ID']?>">
   <div class="notify_btn" <?if($USER->IsAuthorized()):?> onClick="showNotify(<?=$arParams['NOTIFY_ID']?>)"<?endif?> id="notify_product_<?=$arParams['NOTIFY_ID']?>">   </div>   
 </div> 
<?endif;?>
<input type="hidden" value="<?=$arResult["NOTIFY_URL"]?>" name="notify_url_<?=$arParams['NOTIFY_ID']?>" id="notify_url_<?=$arParams['NOTIFY_ID']?>">
<?
if (!defined("EXIST_FORM")):
 define("EXIST_FORM", "Y");
?>


<?endif;?>