<?

function getValueForText($propertyID,&$arParams,&$arResult) {
 
 if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)	{
	$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][0]["VALUE"] : $arResult["ELEMENT"][$propertyID];
   }
  elseif ($i == 0) {
	$value = intval($propertyID) <= 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
 }
 else {
    $value = "";
 }
 return is_array($value)?$value['TEXT']:$value;
}

function getValueForHTML($propertyID,&$arParams,&$arResult) {
 if (intval($_GET["CODE"]) > 0 || count($arResult["ERRORS"]) > 0) {
	
   $value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][0]["~VALUE"] : $arResult["ELEMENT"][$propertyID];   
   $description = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][0]["DESCRIPTION"] : "";
   
 }
 elseif ($i == 0) {
    $value = intval($propertyID) <= 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
    $description = "";
 }
 else {
	$value = "";
	$description = "";
 }
								

 return $value;									
									
}


function getValueForCheckBox($propertyID,&$arParams,&$arResult) {
   $value='';
  if (intval($propertyID) > 0) $sKey = "ELEMENT_PROPERTIES";
else
 $sKey = "ELEMENT";
	foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as $key => $arEnum)
	{
 	$checked = false;
		if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
		{
		foreach ($arResult[$sKey][$propertyID] as $elKey => $arElEnum)
		{
			
			if ($key == $arElEnum["VALUE"]) {
			   $checked = true;
			   $value=$arElEnum["VALUE"];
			   break;
			}
		}
		}
		else
		{
	if ($arEnum["DEF"] == "Y") $checked = true;
		}
	}								
											
return $value;
}
								
								
								
function getValueForList($propertyID,&$arParams,&$arResult) {
  $value=array();
  
 if (intval($propertyID) > 0) $sKey = "ELEMENT_PROPERTIES";
else
 $sKey = "ELEMENT";  
  
	foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as $key => $arEnum)
	{
		 
 	$checked = false;
		if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
		{
		foreach ($arResult[$sKey][$propertyID] as $elKey => $arElEnum)
		{
			
			if ($key == $arElEnum["VALUE"]) {
			   $checked = true;
			   $value[]=$arElEnum["VALUE"];
			   break;
			}
		}
		}
		else
		{
	if ($arEnum["DEF"] == "Y") $checked = true;
		}
	}								
		
return $value;
}


function getValueForAnchor($propertyID,&$arParams,&$arResult) {
  $value=array();
  
  foreach ($arResult['ELEMENT_PROPERTIES'][$propertyID] as $elKey => $arElEnum) {	
 	 $value[]=$arElEnum["VALUE"];		
	
 }		

return $value;
}

?> 