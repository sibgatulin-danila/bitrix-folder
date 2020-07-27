<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CUtil::InitJSCore(array('popup'));


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = 'xmlcatalog';
$arParams["IBLOCK_ID"] = '1';
$arParams["SECTION_ID"] = 0;
$arParams["ELEMENT_ID"] = 4181;

$arParams["SECTION_URL"]=trim($arParams["SECTION_URL"]);
$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);
$arParams["BASKET_URL"]=trim($arParams["BASKET_URL"]);
$arParams["ACTION_VARIABLE"] = "action";
$arParams["PRODUCT_ID_VARIABLE"] = "id";
$arParams["PRODUCT_QUANTITY_VARIABLE"] = "quantity";
$arParams["PRODUCT_PROPS_VARIABLE"] = "prop";
$arParams["SECTION_ID_VARIABLE"] = "SECTION_ID";

if(!is_array($arParams["PRICE_CODE"]))
  $arParams["PRICE_CODE"] = array();

$arParams["USE_PRODUCT_QUANTITY"] = "Y";

/*if (array_key_exists($arParams["ACTION_VARIABLE"], $_REQUEST) && array_key_exists($arParams["PRODUCT_ID_VARIABLE"], $_REQUEST)) {
	if(array_key_exists($arParams["ACTION_VARIABLE"]."BUY", $_REQUEST))
		$action = "BUY";
	elseif(array_key_exists($arParams["ACTION_VARIABLE"]."ADD2BASKET", $_REQUEST))
		$action = "ADD2BASKET";
	else
		$action = strtoupper($_REQUEST[$arParams["ACTION_VARIABLE"]]);
		

	$productID = intval($_REQUEST[$arParams["PRODUCT_ID_VARIABLE"]]);
	if (($action == "ADD2BASKET" || $action == "BUY" || $action == "SUBSCRIBE_PRODUCT") && $productID > 0) {
		if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog") && CModule::IncludeModule('iblock'))
		{
			if($arParams["USE_PRODUCT_QUANTITY"])
				$QUANTITY = intval($_REQUEST[$arParams["PRODUCT_QUANTITY_VARIABLE"]]);
			if($QUANTITY <= 1)
				$QUANTITY = 1;

			$product_properties = array();
			
			if(!$strError && Add2BasketByProductID($productID, $QUANTITY, $arRewriteFields, $product_properties)) {			 
			 //LocalRedirect($APPLICATION->GetCurPageParam("", array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));                
			} else {
				if ($ex = $GLOBALS["APPLICATION"]->GetException())
					$strError = $ex->GetString();
				else
					$strError = GetMessage("CATALOG_ERROR2BASKET").".";
			}
		}
	}
}
*/


if(strlen($strError)>0)
{
	ShowError($strError);
	return 0;
}

{
  CModule::IncludeModule("iblock");
	global $CACHE_MANAGER;
	if($arParams["ELEMENT_ID"] > 0) {
		$arResultPrices = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], array(PRICE_TYPE));
                
		
		$arSelect = array(
			"ID",
			"IBLOCK_ID",
			"CODE",
			"XML_ID",
			"NAME",
			"ACTIVE",
			"DATE_ACTIVE_FROM",
			"DATE_ACTIVE_TO",
			"SORT",
			"PREVIEW_TEXT",
			"PREVIEW_TEXT_TYPE",
			"DETAIL_TEXT",
			"DETAIL_TEXT_TYPE",
			"DATE_CREATE",
			"CREATED_BY",
			"TIMESTAMP_X",
			"MODIFIED_BY",
			"TAGS",
			"IBLOCK_SECTION_ID",
			"DETAIL_PAGE_URL",
			"LIST_PAGE_URL",
			"DETAIL_PICTURE",
			"PREVIEW_PICTURE",
			"PROPERTY_*",
		);
		//WHERE
		$arFilter = array(
			"ID" => $arParams["ELEMENT_ID"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"IBLOCK_LID" => SITE_ID,
			"IBLOCK_ACTIVE" => "Y",			
			"CHECK_PERMISSIONS" => "Y",
			"MIN_PERMISSION" => 'R',
			"SHOW_HISTORY" => $WF_SHOW_HISTORY,
		);
		
		
	   	$arSort = array( );
		//PRICES
		$arPriceTypeID = array();
		if(!$arParams["USE_PRICE_COUNT"])
		{
			foreach($arResultPrices as &$value)
			{
				$arSelect[] = $value["SELECT"];
				$arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = $arParams["SHOW_PRICE_COUNT"];
			}
			if (isset($value))
				unset($value);
		}
		else
		{
			foreach ($arResultPrices as &$value)
			{
				$arPriceTypeID[] = $value["ID"];
			}
			if (isset($value))
				unset($value);
		}

		$arSection = false;

        /**/
        $dbHelp = CIBlockElement::GetList(
            array(),
            array('IBLOCK_ID' => 1, 'CODE' => 'pomoshch_detyam'),
            false,
            false,
            array('IBLOCK_ID', 'NAME', 'ID')
        );

        $obHelp = $dbHelp->Fetch();


		$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
		$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
		$rsElement->SetSectionContext($arSection);
		if($obElement = $rsElement->GetNextElement()) {
                    
			$arResult = $obElement->GetFields();	
			$arResult['CONVERT_CURRENCY'] = $arConvertParams;

			$arResult["CAT_PRICES"] = $arResultPrices;
			$arResult["DETAIL_PICTURE"] = CFile::GetFileArray($arResult["DETAIL_PICTURE"]);

			$arResult["PROPERTIES"] = $obElement->GetProperties();
			
			$arResult["PRICE_MATRIX"] = false;
			$arResult["PRICES"] = CIBlockPriceTools::GetItemPrices($arParams["IBLOCK_ID"], $arResult["CAT_PRICES"], $arResult, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
                        
                        
			$arResult["QUANTITY"]  = 1;
			
			
			$arCatalogProduct = CCatalogProduct::GetByID($arResult['ID']);
			$arPrice = CPrice::GetBasePrice($arResult['ID']);
			
			$arResult["CAN_BUY"] = CIBlockPriceTools::CanBuy($arParams["IBLOCK_ID"], $arResult["CAT_PRICES"], $arResult);			
			$arResult["ADD_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam($arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arResult["ID"], array($arParams["PRODUCT_ID_VARIABLE"], $arParams["ACTION_VARIABLE"])));
			$arResult["PRICE"] = intval($arPrice['PRICE']);
			$arResult['JS_DATA']= str_replace('\'','"',CUtil::PhpToJSObject(array('ID'=>$arResult["ID"],
											                                                'PRICE'=>intval($arPrice['PRICE']),
											                                                'QUANTITY'=>$arResult["QUANTITY"],
																	'MAX_QUANTITY'=>$arCatalogProduct['QUANTITY'])));

            $arResult['LINE_LIFE_JSON'] = str_replace('\'','"',CUtil::PhpToJSObject(array('ID'=>$obHelp["ID"],
                'PRICE'=> 100,
                'QUANTITY'=> 1,
                'MAX_QUANTITY'=> 1000000)));
            $arResult['LINE_LIFE_ID'] = $obHelp["ID"];
			
				
			$this->SetResultCacheKeys(array(
				"IBLOCK_ID",
				"ID",
				"IBLOCK_SECTION_ID",
				"NAME",
				"LIST_PAGE_URL",
				"PROPERTIES",
				"SECTION",
			));

			$this->IncludeComponentTemplate();
		}
		else
		{
			$this->AbortResultCache();
			ShowError(GetMessage("CATALOG_ELEMENT_NOT_FOUND"));
			@define("ERROR_404", "Y");
			if($arParams["SET_STATUS_404"]==="Y")
				CHTTP::SetStatus("404 Not Found");
		}
	}
	else
	{
		$this->AbortResultCache();
		ShowError(GetMessage("CATALOG_ELEMENT_NOT_FOUND"));
		@define("ERROR_404", "Y");
		if($arParams["SET_STATUS_404"]==="Y")
			CHTTP::SetStatus("404 Not Found");
	}
}

if(isset($arResult["ID"])) {
  return $arResult["ID"];
}
else
{
	return 0;
}


?>