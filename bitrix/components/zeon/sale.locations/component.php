<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("sale")) {
	ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
	return;
}
CUtil::InitJSCore(array('core', 'ajax'));

$arParams["AJAX_CALL"] = $arParams["AJAX_CALL"] == "Y" ? "Y" : "N";
$arParams["COUNTRY"] = intval($arParams["COUNTRY"]);
$arParams["LOCATION_VALUE"] = intval($arParams["LOCATION_VALUE"]);
$arParams["ALLOW_EMPTY_CITY"] = $arParams["ALLOW_EMPTY_CITY"] == "N" ? "N" : "Y";


if (strlen($arParams["SITE_ID"]) <= 0)
	$arParams["SITE_ID"] = SITE_ID;


if ($arParams["LOCATION_VALUE"] > 0) {
   if ($arLocation = CSaleLocation::GetByID($arParams["LOCATION_VALUE"])) {
     $arParams["COUNTRY"] = $arLocation["COUNTRY_ID"];
     //$arParams["CITY"] = $arLocation["CITY_ID"];
   }
}

$arResult["EMPTY_CITY"] = "N";
$arCityFilter = array("!CITY_ID" => "NULL", ">CITY_ID" => "0");
if ($arParams["COUNTRY"] > 0)
	$arCityFilter["COUNTRY_ID"] = $arParams["COUNTRY"];
$rsLocCount = CSaleLocation::GetList(array(), $arCityFilter, false, false, array("ID"));
if (!$rsLocCount->Fetch())
  $arResult["EMPTY_CITY"] = "Y";

/*if ($arResult["EMPTY_CITY"] == "Y") {
	$arCityFilter = array("!CITY_ID" => "NULL", ">CITY_ID" => "0");
	$rsLocCount = CSaleLocation::GetList(array(), $arCityFilter, false, false, array("ID"));
	if ($rsLocCount->Fetch())
		$arResult["EMPTY_CITY"] = "N";
} */


/*$arParams["LOC_DEFAULT"] = array();
$dbLocDefault = CSaleLocation::GetList(
		array(
			"SORT" => "ASC",
			"COUNTRY_NAME_LANG" => "ASC",
			"CITY_NAME_LANG" => "ASC"
		),
		array("LOC_DEFAULT" => "Y", "LID" => LANGUAGE_ID),
		false,
		false,
		array("*")
);

while ($arLocDefault = $dbLocDefault->Fetch()) {
	if ($arLocDefault["LOC_DEFAULT"] == "Y") {
		$nameDefault = "";
		$nameDefault .= ((strlen($arLocDefault["COUNTRY_NAME"])<=0) ? "" : $arLocDefault["COUNTRY_NAME"]);		

		if ((strlen($arLocDefault["COUNTRY_NAME"])>0 || strlen($arLocDefault["REGION_NAME"])>0) && strlen($arLocDefault["CITY_NAME"])>0)
			$nameDefault .= " - ".$arLocDefault["CITY_NAME"];
		elseif (strlen($arLocDefault["CITY_NAME"])>0)
			$nameDefault .= $arLocDefault["CITY_NAME"];

		$arLocDefault["LOC_DEFAULT_NAME"] = $nameDefault;
		$arParams["LOC_DEFAULT"][] = $arLocDefault;
	}
} */


//location value
if ($arParams["LOCATION_VALUE"] > 0) {
   if ($arLocation = CSaleLocation::GetByID($arParams["LOCATION_VALUE"])) {
         $arParams["COUNTRY"] = $arLocation["COUNTRY_ID"];		
	  $arParams["CITY"] = $arParams["CITY_OUT_LOCATION"] == "Y" ? $arParams["LOCATION_VALUE"] : $arLocation["CITY_ID"];	
   }
}

$locationString = "";

//select country
$arResult["COUNTRY_LIST"] = array();
$rsCountryList = CSaleLocation::GetCountryList(array("SORT" => "ASC", "NAME_LANG" => "ASC"));
while ($arCountry = $rsCountryList->GetNext()) {
 $arResult["COUNTRY_LIST"][] = $arCountry;
 /*if ($arCountry["ID"] == $arParams["COUNTRY"] && strlen($arCountry["NAME_LANG"]) > 0)
   $locationString .= $arCountry["NAME_LANG"]; */
}

if (count($arResult["COUNTRY_LIST"]) <= 0)
 $arResult["COUNTRY_LIST"] = array();


$arResult["CITY_LIST"] = array();
if ($arResult["EMPTY_CITY"] == "N" && ((count($arResult["COUNTRY_LIST"]) > 0 && $arParams["COUNTRY"] > 0) || (count($arResult["COUNTRY_LIST"]) <= 0))) {
	$arCityFilter = array("LID" => LANGUAGE_ID);
	if ($arParams["COUNTRY"] > 0)
  	  $arCityFilter["COUNTRY_ID"] = $arParams["COUNTRY"];

	if ($arParams['ALLOW_EMPTY_CITY'] == 'Y') {
		$arCityFilter['>CITY_ID'] = 0;
		$rsLocationsList = CSaleLocation::GetList(
			array(
				"SORT" => "ASC",
				"COUNTRY_NAME_LANG" => "ASC",
				"CITY_NAME_LANG" => "ASC"
			),
			$arCityFilter,
			false,
			false,
			array("ID", "CITY_ID", "CITY_NAME")
		);

		while ($arCity = $rsLocationsList->GetNext()) {
			$arResult["CITY_LIST"][] = array(
				"ID" => $arCity[$arParams["CITY_OUT_LOCATION"] == "Y" ? "ID" : "CITY_ID"],
				"CITY_ID" => $arCity['CITY_ID'],
				"CITY_NAME" => $arCity["CITY_NAME"]);
			
			if ($arCity["ID"] == $arParams["CITY"] && strlen($arCity["CITY_NAME"]) > 0) {
			   //$locationString = $arCity["CITY_NAME"].", ".$locationString;
			   $arResult["LOCATION_DEFAULT"] = $arCity["ID"];
			}
		}//end while
	}//end if
}

//$arResult["LOCATION_STRING"] = $locationString; 
$arParams["JS_CITY_INPUT_NAME"] = CUtil::JSEscape($arParams["CITY_INPUT_NAME"]);

$arTmpParams = array(
	"COUNTRY_INPUT_NAME" => $arParams["COUNTRY_INPUT_NAME"],
	"CITY_INPUT_NAME" => $arParams["CITY_INPUT_NAME"],
	"CITY_OUT_LOCATION" => $arParams["CITY_OUT_LOCATION"],
	"ALLOW_EMPTY_CITY" => $arParams["ALLOW_EMPTY_CITY"],
	"ONCITYCHANGE" => $arParams["ONCITYCHANGE"]);

$arResult["JS_PARAMS"] = CUtil::PhpToJsObject($arTmpParams);

$serverName = COption::GetOptionString("main", "server_name", "");
if (strlen($serverName) > 0)
  $arParams["SERVER_NAME"] = "http://".$serverName;

$this->IncludeComponentTemplate();

if ($arParams["AJAX_CALL"] != "Y") {
  IncludeAJAX();
  $template =& $this->GetTemplate();
  $APPLICATION->AddHeadScript($template->GetFolder().'/proceed.js');
}
?>