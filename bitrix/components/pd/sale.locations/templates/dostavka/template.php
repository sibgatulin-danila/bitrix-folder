<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$disabled = false;

if (isset($_REQUEST["NEW_LOCATION_".$arParams["ORDER_PROPS_ID"]]) && IntVal($_REQUEST["NEW_LOCATION_".$arParams["ORDER_PROPS_ID"]]) > 0)
	$disabled = true;
?>

<?if ($arParams["AJAX_CALL"] != "Y"):?><div id="LOCATION_<?=$arParams["CITY_INPUT_NAME"];?>"><?endif?>
 <?
	if ($arResult["EMPTY_CITY"] == "Y" && $arResult["EMPTY_REGION"] == "Y")
		$change = $arParams["ONCITYCHANGE"];
	else
		$change = "getLocation(this.value, '', '', ".$arResult["JS_PARAMS"].", '".CUtil::JSEscape($arParams["SITE_ID"])."')";
	?>
	<table style="margin:0 auto"><tr>
	<td width="250">
	 Выберите страну
	 <div class="selectWrap" style="margin:0;"> 
	<select class="styled" <?if($disabled) echo "disabled";?> id="<?=$arParams["COUNTRY_INPUT_NAME"].$arParams["CITY_INPUT_NAME"]?>" name="<?=$arParams["COUNTRY_INPUT_NAME"].$arParams["CITY_INPUT_NAME"]?><?if($arParams['AUTO_LOCATION']=='Y'):?>_<?endif?>" onChange="<?=$change?>" type="location">	
		<?foreach ($arResult["COUNTRY_LIST"] as $arCountry):?>
		<option value="<?=$arCountry["ID"]?>"<?if ($arCountry["ID"] == $arParams["COUNTRY"]):?> selected="selected"<?endif;?>><?=$arCountry["NAME_LANG"]?></option>
		<?endforeach;?>
	</select>
	</div>
	</td>
	<td width="45"></td>
        <td width="250">
	<?
	$id = "";
	if (count($arResult["COUNTRY_LIST"]) <= 0 && count($arResult["REGION_LIST"]) <= 0):
		$id = "id=\"".$arParams["COUNTRY_INPUT_NAME"]."\"";
	else:
		$id = "id=\"".$arParams["CITY_INPUT_NAME"]."\"";
	endif;?>
	и впишите название города
	<div style="position:relative;">
	<select  <?=$id?> <?if($disabled) echo "disabled";?> name="<?=$arParams["CITY_INPUT_NAME"]?><?if($arParams['AUTO_LOCATION']=='Y'):?>_<?endif?>"<?if (strlen($arParams["ONCITYCHANGE"]) > 0):?> onchange="<?=$arParams["ONCITYCHANGE"]?>"<?endif;?> type="location">
		<option></option>
		<?foreach ($arResult["CITY_LIST"] as $arCity):?>
			<option value="<?=$arCity["ID"]?>"<?if ($arCity["ID"] == $arParams["CITY"]):?> selected="selected"<?endif;?>><?=($arCity['CITY_ID'] > 0 ? $arCity["CITY_NAME"] : GetMessage('SAL_CHOOSE_CITY_OTHER'))?></option>
		<?endforeach;?>
	</select>
	<span class="errAutoComplete">Вы допустили ошибку в названии города, либо в этот город доставка не возможна</span>
	</div>
	</td>
	</tr></table>
	<script>
	 $(function() { 
 	  $("#ICITY").combobox();
	 });
	 
	</script>


<?if ($arParams["AJAX_CALL"] != "Y"):?></div><div id="wait_container_<?=$arParams["CITY_INPUT_NAME"]?>" style="display: none;"></div><?endif;?>

	
	
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
