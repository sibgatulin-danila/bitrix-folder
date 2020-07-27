<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule("catalog");
if (!CModule::IncludeModule("sale")) {
	ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
	return;
}

$arParams["PATH_TO_BASKET"] = Trim($arParams["PATH_TO_BASKET"]);
$arParams["PATH_TO_ORDER"] = Trim($arParams["PATH_TO_ORDER"]);

$dbBaket = CSaleBasket::GetList(
	array("ID" => "DESC"),
	array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL",'!PRODUCT_ID'=>4181),
	false,false,array('*')
);

$bReady = False;
$bDelay = False;
$bNotAvail = False;
$bSubscribe = False;
$arItems = array();
$arProductsId = array();

while ($arBasket = $dbBaket->GetNext()) {
	
	if ($arBasket["DELAY"]=="N" && $arBasket["CAN_BUY"]=="Y") {
		$bReady = True;
		$arProductsId[]=$arBasket['PRODUCT_ID'];
	}
	elseif ($arBasket["DELAY"]=="Y" && $arBasket["CAN_BUY"]=="Y")
		$bDelay = True;
	elseif ($arBasket["CAN_BUY"]=="N" && $arBasket["SUBSCRIBE"]=="N")
		$bNotAvail = True;
	elseif ($arBasket["CAN_BUY"]=="N" && $arBasket["SUBSCRIBE"]=="Y")
		$bSubscribe = True;
	
	$arBasket["PRICE_FORMATED"] = SaleFormatCurrency($arBasket["PRICE"], $arBasket["CURRENCY"]);
	
	$dbProp = CSaleBasket::GetPropsList(Array("SORT" => "ASC", "ID" => "ASC"), Array("BASKET_ID" => $arBasket["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")));
	while($arProp = $dbProp -> GetNext()) {
		 
	  $arBasket["PROPS"][] = $arProp; 
	}
 


	$arItems[$arBasket['PRODUCT_ID']] = $arBasket;
	
}
if(!empty($arProductsId)) {	
 $rsCatalogProducts = CCatalogProduct::GetList(array(),array('ID'=>$arProductsId));
 while($catalogProduct = $rsCatalogProducts->GetNext())
  $arItems[$catalogProduct['ID']]['PRODUCT_QUANTITY'] = $catalogProduct['QUANTITY'];
  
foreach ($arItems as $i=>$v) {
 if($v["QUANTITY"]>=$v["PRODUCT_QUANTITY"])
   $arItems[$i]['QUANTITY'] = $v["PRODUCT_QUANTITY"];
}
}



$arResult["READY"] = (($bReady)?"Y":"N");
$arResult["DELAY"] = (($bDelay)?"Y":"N");
$arResult["NOTAVAIL"] = (($bNotAvail)?"Y":"N");
$arResult["SUBSCRIBE"] = (($bSubscribe)?"Y":"N");
$arResult["ITEMS"] = $arItems;
$arResult["CNT_ITEMS_READY"] =  count($arResult["ITEMS"]);

$this->IncludeComponentTemplate();
?>