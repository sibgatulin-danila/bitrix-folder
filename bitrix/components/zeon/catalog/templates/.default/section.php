<div id="CrossssInsertionPoint"></div>

<pre><?//print_r($_SERVER)?></pre>

<?/*<div class="b_uslovia">
  <img src="/i/uslovia.png"/>
 </div>*/?>

<? include('catalog_filter.php'); ?>
<div class="b_catalog">

<?
function GET_SALE_FILTER(){
      global $DB;

      $arDiscountElementID = array();
      $dbProductDiscounts = CCatalogDiscount::GetList(
         array("SORT" => "ASC"),
         array(
            "ACTIVE" => "Y",
            "!>ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"),
                  "YYYY-MM-DD HH:MI:SS",
                  CSite::GetDateFormat("FULL")),
            "!<ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"),
                  "YYYY-MM-DD HH:MI:SS",
                  CSite::GetDateFormat("FULL")),
         ),
         false,
         false,
         array(
            "ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO",
            "RENEWAL", "NAME", "SORT", "MAX_DISCOUNT", "VALUE_TYPE",
            "VALUE", "CURRENCY", "PRODUCT_ID"
         )
      );
      while ($arProductDiscounts = $dbProductDiscounts->Fetch())
      {
         if($res = CCatalogDiscount::GetDiscountProductsList(array(), array(">=DISCOUNT_ID" => $arProductDiscounts['ID']), false, false, array())){
            while($ob = $res->GetNext()){
               if(!in_array($ob["PRODUCT_ID"],$arDiscountElementID))
                  $arDiscountElementID[] = $ob["PRODUCT_ID"];
            }}
      }

      return $arDiscountElementID;

   } 
   
if($_GET['ajaxid']=='catalog' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {  
 $isAjax = true;
 $APPLICATION->RestartBuffer();
}


if($_GET['sort']=='price') { 
   $arParams["ELEMENT_SORT_FIELD"] ='CATALOG_PRICE_1';
}


global $USER;

if($arResult["VARIABLES"]["SECTION_CODE"]=='new') {
 $arParams["ELEMENT_SORT_FIELD"] = 'active_from';
 $arParams["ELEMENT_SORT_ORDER"] = 'desc';
 $arParams["PAGE_ELEMENT_COUNT"]=33;
 $catalogFilter['NO_SECTION']='Y';
 $APPLICATION->SetTitle('Новинки — новые коллекции дизайнерской бижутерии купить — интернет магазин Poison Drop');
} else if($arResult["VARIABLES"]["SECTION_CODE"]=='sale') {

 $catalogFilter['=ID']=GET_SALE_FILTER();
 print_r($catalogFilter['=ID']);
 die();
 //$catalogFilter['!PROPERTY_DISCOUNT']=false;
 $catalogFilter['NO_SECTION']='Y';
 $APPLICATION->SetTitle('Sale — Дизайнерская бижутерия со скидкой купить — интернет магазин Poison Drop');
}
else
 $catalogFilter['SECTION_CODE'] = $arResult["VARIABLES"]["SECTION_CODE"];



if(in_array($_GET['order'],array('asc','desc'))) {
 if($_GET['order']=='asc') {
  $arParams["ELEMENT_SORT_ORDER"] = 'ASC';
 } else
  $arParams["ELEMENT_SORT_ORDER"] = 'DESC';
}
?>

<?$APPLICATION->IncludeComponent(
	"zeon:catalog.section",
	".default",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
	    "ELEMENT_SORT_ORDER2" =>$arParams["ELEMENT_SORT_ORDER2"],
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"FILTER_NAME" => 'catalogFilter',
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_FILTER" => 'N',
		"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => '',
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
	),
	$component
);
?>

<?
if($isAjax) {
   die;
}

if($currSection = CIBlockSection::GetList(array(),array('IBLOCK_ID'=>1,'CODE'=>$arResult["VARIABLES"]["SECTION_CODE"]),false,array('UF_TITLE'))->GetNext()) {  
  if(!empty($currSection['UF_TITLE'])) 
    $APPLICATION->SetPageProperty('title',$currSection['UF_TITLE']);
 }
?>
</div>