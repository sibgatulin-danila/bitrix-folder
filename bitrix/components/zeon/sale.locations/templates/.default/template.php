<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$disabled = false;

if (isset($_REQUEST["NEW_LOCATION_".$arParams["ORDER_PROPS_ID"]]) && IntVal($_REQUEST["NEW_LOCATION_".$arParams["ORDER_PROPS_ID"]]) > 0)
	$disabled = true;
?>

<?if ($arParams["AJAX_CALL"] != "Y"):?><div id="LOCATION_<?=$arParams["CITY_INPUT_NAME"];?>"><?endif?>

<?if (count($arResult["COUNTRY_LIST"]) > 0):?>
	<?
	if ($arResult["EMPTY_CITY"] == "Y" && $arResult["EMPTY_REGION"] == "Y")
		$change = $arParams["ONCITYCHANGE"];
	else
		$change = "getLocation(this.value, '', '', ".$arResult["JS_PARAMS"].", '".CUtil::JSEscape($arParams["SITE_ID"])."')";
	?>
	Страна<span class="pink">*</span>
	<div class="select_wrap" style="margin-bottom:30px;"> 
	<select class="styled" <?if($disabled) echo "disabled";?> id="<?=$arParams["COUNTRY_INPUT_NAME"].$arParams["CITY_INPUT_NAME"]?>" name="<?=$arParams["COUNTRY_INPUT_NAME"].$arParams["CITY_INPUT_NAME"]?><?if($arParams['AUTO_LOCATION']=='Y'):?>_<?endif?>" onChange="<?=$change?>" type="location">	
		<?foreach ($arResult["COUNTRY_LIST"] as $arCountry):?>
		<option value="<?=$arCountry["ID"]?>"<?if ($arCountry["ID"] == $arParams["COUNTRY"]):?> selected="selected"<?endif;?>><?=$arCountry["NAME_LANG"]?></option>
		<?endforeach;?>
	</select>
	</div>
<?endif;?>

<?if (count($arResult["CITY_LIST"]) > 0):?>
	<?
	$id = "";
	if (count($arResult["COUNTRY_LIST"]) <= 0 && count($arResult["REGION_LIST"]) <= 0):
		$id = "id=\"".$arParams["COUNTRY_INPUT_NAME"]."\"";
	else:
		$id = "id=\"".$arParams["CITY_INPUT_NAME"]."\"";
	endif;?>
	Город<span class="pink">*</span> 
	<select  <?=$id?> <?if($disabled) echo "disabled";?> name="<?=$arParams["CITY_INPUT_NAME"]?><?if($arParams['AUTO_LOCATION']=='Y'):?>_<?endif?>"<?if (strlen($arParams["ONCITYCHANGE"]) > 0):?> onchange="<?=$arParams["ONCITYCHANGE"]?>"<?endif;?> type="location">
		<option></option>
		<?foreach ($arResult["CITY_LIST"] as $arCity):?>
			<option value="<?=$arCity["ID"]?>"<?if ($arCity["ID"] == $arParams["CITY"]):?> selected="selected"<?endif;?>><?=($arCity['CITY_ID'] > 0 ? $arCity["CITY_NAME"] : GetMessage('SAL_CHOOSE_CITY_OTHER'))?></option>
		<?endforeach;?>
	</select>
	
	<script>
	 $(function() { 
 	   $("#ORDER_PROP_2").combobox();
	 });
	 
	</script>
<?endif;?>

<?if ($arParams["AJAX_CALL"] != "Y"):?></div><div id="wait_container_<?=$arParams["CITY_INPUT_NAME"]?>" style="display: none;"></div><?endif;?>


<?if($arParams["AJAX_CALL"]=='Y'):?>
  	<script>
	 
	 $(function() {	 
       if(!window.Mobi)
	   cuSel({changedEl: ".styled",visRows: 13, scrollArrows: true}); 

	 if($("#COUNTRY_ORDER_PROP_2ORDER_PROP_2").val()==24) { 
          $("input[name=ORDER_PROP_18]").mask("+7 (999) 999-99-99");  
         } else
	  $("input[name=ORDER_PROP_18]").unmask();  	   

	   
	 })
	 </script>
	<?endif?>
	
	
<?if ($arParams["AJAX_CALL"] != "Y" && $arParams["PUBLIC"] != "N"):?>
<script>

 function newlocation(orderPropId) {
		var select = document.getElementById("LOCATION_ORDER_PROP_" + orderPropId);

		arSelect = select.getElementsByTagName("select");
		if (arSelect.length > 0)
		{
			for (var i in arSelect)
			{
				var elem = arSelect[i];
				elem.disabled = false;
			}
		}
	}
</script>
<?endif;?>
