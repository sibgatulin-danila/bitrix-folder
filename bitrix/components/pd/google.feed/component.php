<?
set_time_limit(100);
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
{
	return;
}

/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;

unset($arParams["IBLOCK_TYPE"]); //was used only for IBLOCK_ID setup with Editor
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["SECTION_ID"] = intval($arParams["SECTION_ID"]);
$arParams["SECTION_CODE"] = trim($arParams["SECTION_CODE"]);
$arParams["NUM_DAYS"] = intval($arParams["NUM_DAYS"]);
$arParams["NUM_NEWS"] = intval($arParams["NUM_NEWS"]);

if(!array_key_exists("RSS_TTL", $arParams))
	$arParams["RSS_TTL"] = 60;
$arParams["RSS_TTL"] = intval($arParams["RSS_TTL"]);

$arParams["YANDEX"] = $arParams["YANDEX"]=="Y";

$arParams["CHECK_DATES"] = $arParams["CHECK_DATES"]!="N";

$arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
if(strlen($arParams["SORT_BY1"])<=0)
	$arParams["SORT_BY1"] = "ACTIVE_FROM";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"]))
	$arParams["SORT_ORDER1"]="DESC";

if(strlen($arParams["SORT_BY2"])<=0)
	$arParams["SORT_BY2"] = "SORT";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER2"]))
	$arParams["SORT_ORDER2"]="ASC";

if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
{
	$arrFilter = array();
}
else
{
	$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arrFilter))
		$arrFilter = array();
}

$arParams["CACHE_FILTER"] = $arParams["CACHE_FILTER"]=="Y";
if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;

$bDesignMode = $GLOBALS["APPLICATION"]->GetShowIncludeAreas() && is_object($GLOBALS["USER"]) && $GLOBALS["USER"]->IsAdmin();

if(!$bDesignMode)
{
	$APPLICATION->RestartBuffer();
	header("Content-Type: text/xml; charset=".LANG_CHARSET);
	header("Pragma: no-cache");
}
else
{
	ob_start();
}
/*************************************************************************
	Start caching
*************************************************************************/

///if($this->StartResultCache(false, array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups(), $arrFilter)))
{
	 
	$rsResult = CIBlock::GetList(array(), array(
		"ACTIVE" => "Y",
		"SITE_ID" => SITE_ID,
		"ID" => $arParams["IBLOCK_ID"],
	));
	$arResult = $rsResult->Fetch();
	if(!$arResult)
	{
		$this->AbortResultCache();
		if($bDesignMode)
		{
			ob_end_flush();
			ShowError(GetMessage("CT_RO_IBLOCK_NOT_FOUND"));
			return;
		}
		else
			die();
	}
	else
	{
		foreach($arResult as $k => $v)
		{
			if(substr($k, 0, 1)!=="~")
			{
				$arResult["~".$k] = $v;
				$arResult[$k] = htmlspecialcharsbx($v);
			}
		}
	}

	$arResult["RSS_TTL"] = $arParams["RSS_TTL"];

	if($arParams["SECTION_ID"] > 0 || strlen($arParams["SECTION_CODE"]) > 0)
	{
		$arFilter = array(
			"ACTIVE" => "Y",
			"GLOBAL_ACTIVE" => "Y",
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"IBLOCK_ACTIVE" => "Y",
		);
		if($arParams["SECTION_ID"] > 0)
			$arFilter["ID"] = $arParams["SECTION_ID"];
		elseif(strlen($arParams["SECTION_CODE"]) > 0)
			$arFilter["=CODE"] = $arParams["SECTION_CODE"];

		$rsResult = CIBlockSection::GetList(array(), $arFilter);
		$arResult["SECTION"] = $rsResult->Fetch();
		if(!$arResult["SECTION"])
		{
			$this->AbortResultCache();
			if($bDesignMode)
			{
				ob_end_flush();
				ShowError(GetMessage("CT_RO_SECTION_NOT_FOUND"));
				return;
			}
			else
				die();
		}
		else
		{
			foreach($arResult["SECTION"] as $k => $v)
			{
				if(substr($k, 0, 1)!=="~")
				{
					$arResult["SECTION"]["~".$k] = $v;
					$arResult["SECTION"][$k] = htmlspecialcharsbx($v);
				}
			}
		}
	}

	if(strlen($arResult["SERVER_NAME"])<=0 && defined("SITE_SERVER_NAME"))
	{
		$arResult["SERVER_NAME"] = SITE_SERVER_NAME;
	}
	if(strlen($arResult["SERVER_NAME"])<=0 && defined("SITE_SERVER_NAME"))
	{
		$rsSite = CSite::GetList(($b="sort"), ($o="asc"), array("LID" => $arResult["LID"]));
		if($arSite = $rsSite->Fetch())
			$arResult["SERVER_NAME"] = $arSite["SERVER_NAME"];
	}
	if(strlen($arResult["SERVER_NAME"])<=0)
	{
		$arResult["SERVER_NAME"] = COption::GetOptionString("main", "server_name", "www.bitrixsoft.com");
	}

	$arResult["PICTURE"] = CFile::GetFileArray($arResult["PICTURE"]);

	$arResult["NODES"] = CIBlockRSS::GetNodeList($arResult["ID"]);

	$arSelect = array(
		"ID",
		"CODE",		
		"IBLOCK_ID",
		"NAME",
		"SORT",
		"DETAIL_PAGE_URL",		
		"DETAIL_TEXT",		
		"DETAIL_PICTURE",
		"IBLOCK_SECTION_ID",	
		"PROPERTY_*",
		//"CATALOG_GROUP_1"
		
	);
	$arFilter = array ("ACTIVE" => "Y");

	//if($arParams["CHECK_DATES"])
	//	$arFilter["ACTIVE_DATE"] = "Y";

	if(array_key_exists("SECTION", $arResult))
	{
		$arFilter["SECTION_ID"] = $arResult["SECTION"]["ID"];
		if($arParams["INCLUDE_SUBSECTIONS"])
			$arFilter["INCLUDE_SUBSECTIONS"] = "Y";
	}
	else
	{
		$arFilter["IBLOCK_ID"] = $arResult["ID"];
	}

	//if($arParams["NUM_DAYS"] > 0)
	//	$arFilter["ACTIVE_FROM"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")), mktime(date("H"), date("i"), date("s"), date("m"), date("d")-IntVal($arParams["NUM_DAYS"]), date("Y")));

	$arSort = array(
		$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
		$arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
	);
	if(!array_key_exists("ID", $arSort))
		$arSort["ID"] = "DESC";

	if($arParams["NUM_NEWS"]>0)
		$limit = array("nTopCount"=>$arParams["NUM_NEWS"]);
	else
		$limit = false;

	$arResult["ITEMS"]=array();


      $rsDesigners = CIBlockElement::GetList(array(), array('IBLOCK_CODE'=>'designers','ACTIVE'=>'Y'), false, false);
	$arDesigners=array();
	while($d = $rsDesigners->GetNext()) {
	  $arDesigners[$d['ID']]	 = $d['NAME'];
	}
	
	
	$rsElements = CIBlockElement::GetList(array(), array_merge($arFilter, $arrFilter), false, false, $arSelect);	

	$rsElements->SetUrlTemplates($arParams["DETAIL_URL"]);
	$arItemIds = array();
	CModule::IncludeModule('catalog');
	CModule::IncludeModule('sale');
	CModule::IncludeModule('poisondrop');
	//$arResultPrices = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], array('BASE'));
        $arProducts=array();
	while($obElement = $rsElements->GetNextElement()) {
		
		$arElement = $obElement->GetFields();
		$arProperties = $obElement->GetProperties();
		$arProducts[] =$arElement['ID'];
 		
		if(!empty($arElement["DETAIL_PICTURE"])) {
		  $arItem["DETAIL_PICTURE"] = CFile::GetPath($arElement["DETAIL_PICTURE"]);
		} else {
		   $thumb = CPoisonUtils::getFirstImg($arElement['ID']);
		   $strFile="http://poisondrop.ru".$thumb;					 
		}
		
		$arItem["title"] =  htmlspecialcharsbx(htmlspecialcharsback($arElement["NAME"]));

		 $arItem["link"] = CHTTP::URN2URI($arElement["DETAIL_PAGE_URL"], $arResult["SERVER_NAME"]);
               
	        if(!empty($arProperties['PHOTOGALLERY']['VALUE']))
	  	 $arItemIds[] = $arProperties['PHOTOGALLERY']['VALUE'];
		
		$arItem["description"]=htmlspecialcharsbx(htmlspecialcharsback(strip_tags($arElement["~DETAIL_TEXT"])));
		$arItem["category"] = "";
	  	$rsNavChain = CIBlockSection::GetNavChain($arResult["ID"], $arElement["IBLOCK_SECTION_ID"]);
	 	while($arNavChain = $rsNavChain->Fetch()) {
		  $arItem["category"] .= htmlspecialcharsbx($arNavChain["NAME"])."";
		}
		$arItem['BREND'] = $arDesigners[$arProperties['DESIGNER']['VALUE']];
		$arItem["ELEMENT"] = $arElement;
		$arItem["PROPERTIES"] = $arProperties;		
		$arResult["ITEMS"][$arElement['ID']]=$arItem;
	}
	
	
	$rsPrices = CPrice::GetList(array(),array('PRODUCT_ID'=>$arProducts),false,false);
	$arResult['PRICES'] = array();
	while($price = $rsPrices->GetNext()) {
		

	  
	   $arDiscounts = CCatalogDiscount::GetDiscountByPrice(
            $price["ID"],
            $USER->GetUserGroupArray(),
            "N",
            SITE_ID
        );
      $discountPrice = CCatalogProduct::CountPriceWithDiscount(
            $price["PRICE"],
            $price["CURRENCY"],
            $arDiscounts
        );
    
     
         if($discountPrice>0 && $discountPrice<$price['PRICE']) {
	   $price['PRICE']=$discountPrice;
	 }
    
    
	  $arResult['PRICES'][$price['PRODUCT_ID']] = $price['PRICE'];
	}
	
	$rsCatalogProducts = CCatalogProduct::GetList(array(),array('ID'=>$arProducts),false,false);
	$arResult['CATALOG'] = array();
	while($p = $rsCatalogProducts->GetNext()) {
	  $arResult['CATALOG'][$p['ID']] = $p['QUANTITY'];
	}
	
	
	$rsPhotoAlubms = CIBlockElement::GetList(array(),array('IBLOCK_CODE'=>'photogallery','SECTION_ID'=>$arItemIds),false,false);
	$arResult['ALBUMS'] = array();
	while($p = $rsPhotoAlubms->GetNext()) {
	 $arResult['ALBUMS'][$p['IBLOCK_SECTION_ID']][]= CFile::GetPath($p['DETAIL_PICTURE']);
	} 
	
	
	

 
	$this->IncludeComponentTemplate();
}

if(!$bDesignMode)
{
	$r = $APPLICATION->EndBufferContentMan();
	echo $r;
	if(defined("HTML_PAGES_FILE") && !defined("ERROR_404")) CHTMLPagesCache::writeFile(HTML_PAGES_FILE, $r);
	die();
}
else
{
	$contents = ob_get_contents();
	ob_end_clean();
	echo "<pre>",htmlspecialcharsbx($contents),"</pre>";
}
?>

