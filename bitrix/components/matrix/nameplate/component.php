<?
$APPLICATION->AddHeadScript('/js/underscore-min.js');
$APPLICATION->AddHeadScript('/js/backbone-min.js');
$APPLICATION->SetAdditionalCSS('/bitrix/components/matrix/catalog/templates/.default/style.css');
$APPLICATION->SetTitle('Конструктор именных подвесок');
$APPLICATION->AddHeadString(' <meta property="og:title" content="Именная подвеска по вашему дизайну" />');
$APPLICATION->AddHeadString(' <meta property="og:description" content="Именная подвеска по вашему дизайну" />');
$APPLICATION->AddHeadString(' <meta name="description" content="Именная подвеска по вашему дизайну" />');

 
 
if(CModule::IncludeModule('iblock')) {
 $rsMetall = CIBlockElement::GetList(array('SORT'=>'ASC'),array('IBLOCK_ID'=>13,'ACTIVE'=>'Y'),false,false,array('ID','NAME','PREVIEW_PICTURE','PROPERTY_COLOR'));
 while($m = $rsMetall->Fetch()) {
   $arResult['METALL'][$m['ID']] = array('NAME'=>$m['NAME'],'PIC'=>CFile::GetPath($m['PREVIEW_PICTURE']),'COLOR'=>$m['PROPERTY_COLOR_VALUE']);   
 }
 
 
 
 $arPrices =  CIBlockPriceTools::GetCatalogPrices(1, array(PRICE_TYPE));    
 $arResult['OFFERS'] = CIBlockPriceTools::GetOffersArray(1,array(7353),array(),array(),array('METALL'),0,$arPrices,1,array());
 
 
 $nameplate_element = CIBlockElement::GetByID(7353)->GetNext();
 
 
 $pageElement = CIBlockElement::GetByID(7487)->GetNext();
 //$pageTopElement = CIBlockElement::GetByID(7707)->GetNext();
 
 $arMonth = array(1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
 $arResult['DATE_READY'] = date('j',time()+86400*10).' '.$arMonth[date('n',time()+86400*10)];
 $arResult['TEXT']=str_replace('#DATE#',$arResult['DATE_READY'],$pageElement['DETAIL_TEXT']);
 //$arResult['TOP_TEXT']=$pageTopElement['DETAIL_TEXT'];
 $arResult['OFFERS'][$i]['SIZE_RINGS']['VALUE'] =  strip_tags($offer['DISPLAY_PROPERTIES']['SIZE_RINGS']['DISPLAY_VALUE']);
 if(!empty($arResult['OFFERS'])) {
  $arResult['HAS_OFFERS'] = 'Y';
  foreach($arResult['OFFERS'] as $i=>$offer) { 
    if($offer['CAN_BUY']!=1) {
      unset($arResult['OFFERS'][$i]);     
      continue;
    }
    
     $offerData  = array('ID'=>$offer['ID'],
                         'ADD_URL' => $nameplate_element['DETAIL_PAGE_URL'].'?action=ADD2BASKET&id='.$offer['ID'].'&product_ajax=1',
                         'PRICE'=>$offer['PRICES'][PRICE_TYPE]['PRINT_VALUE']
                         
                         );
     $arResult['METALL'][$offer['DISPLAY_PROPERTIES']['METALL']['VALUE']]['JS_OFFER'] = str_replace('\'','"',CUtil::PhpToJSObject($offerData));
     
   }
 }
 
 $arResult['METALL'] = array_values($arResult['METALL']);
 
 
 global $USER;
 
 if(isset($_GET['id'])) {
  $currentUser  = CUser::GetByID($USER->GetID())->GetNext();	    
  $arResult['NP_DATA'] = unserialize($currentUser['~UF_NAMEPLATE_DATA']);
  if(isset($arResult['NP_DATA'][$_GET['id']])) {
   $arResult['WISHLIST'] ='Y';   
    $arNameplateParams = explode("&",$arResult['NP_DATA'][$_GET['id']]);
    
    foreach($arNameplateParams as $param) {
      $param = explode("=",$param);
      $arResult['OPTIONS'][$param[0]] = $param[1];
    }
   }
 }
 
 if($arResult['WISHLIST'] !='Y') {
   $arResult['OPTIONS']['text'] = empty($_GET['text'])?'':$_GET['text'];
   $arResult['OPTIONS']['f'] = empty($_GET['f'])?'Stainy':$_GET['f'];
   $arResult['OPTIONS']['ct'] = empty($_GET['ct'])?'c1':$_GET['ct'];
   $arResult['OPTIONS']['c'] = empty($_GET['c'])?'204,204,204':$_GET['c'];
   
 }
 
 $arResult['FONTS'] = array('Stainy','Melany','Carpente','Cosmic','Hermes','Cabriolet');
 $arResult['CHAIN_TYPE'] = array('c1','c2');
 
 

 
 $this->IncludeComponentTemplate();    
}
 

?>