<?
if(CModule::IncludeModule('iblock')) {
  $arResult=array();
  global $USER;
  $currentUser  = CUser::GetByID($USER->GetID())->GetNext();	   
  $arResult['WISH_LIST'] =  explode(',',$currentUser['UF_WISHLIST']);
  
  $arSelectedFields= array('ID','NAME','DETAIL_PAGE_URL','PREVIEW_PICTURE','PROPERTY_DESIGNER.NAME','CATALOG_GROUP_1');
  $arFilters = array('NEW'=>array('NAME'=>'Новинка','FILTER'=>array('!SECTION_ID'=>0,'!ID'=>7353),'NAV'=>array('nTopCount'=>30)),
                     'MUST_HAVE'=>array('NAME'=>'Must Have','FILTER'=>array('PROPERTY_MUST_HAVE'=>2),'NAV'=>array('nTopCount'=>1)),
                     'SALE'=>array('NAME'=>'Sale','FILTER'=>array('PROPERTY_SALE'=>3,'!PROPERTY_DISCOUNT'=>false),'NAV'=>array('nTopCount'=>1)));
  
 foreach($arFilters as $k=>$filter) {
  
  if($k=='NEW') {
   $rsProducts = CIBlockElement::GetList(array('ID'=>'DESC'),array('IBLOCK_CODE'=>'catalog','ACTIVE'=>'Y',$filter['FILTER'],'CATALOG_AVAILABLE'=>'Y'),false,$filter['NAV'],array('ID')); 
   $arNewProducts = array();
   while($product = $rsProducts->GetNext()) {
     $arNewProducts[] = $product['ID'];    
   }
      
   $filter['FILTER']['ID'] = $arNewProducts[array_rand($arNewProducts)]; 
 }
  
  $rsProducts = CIBlockElement::GetList(array('RAND'=>'RAND'),array_merge($filter['FILTER'],array('IBLOCK_CODE'=>'catalog','ACTIVE'=>'Y','CATALOG_AVAILABLE'=>'Y')),false,$filter['NAV'],$arSelectedFields); 
  if($product = $rsProducts->GetNext()) {          
   if(!empty($product['PREVIEW_PICTURE'])) 
   $thumb = CFile::GetPath($product['PREVIEW_PICTURE']);
  else {
    CModule::IncludeModule('poisondrop');
    $thumb = CPoisonUtils::getFirstImg($product['ID']);    
  }
  
      
    $arTmpPrice  = CIBlockPriceTools::GetItemPrices(1, CIBlockPriceTools::GetCatalogPrices(1, array(PRICE_TYPE)), $product);
    $arPrice=array();
    if($arTmpPrice[PRICE_TYPE]['DISCOUNT_VALUE']<$arTmpPrice[PRICE_TYPE]['VALUE']) {
       $arPrice['VALUE'] = $arTmpPrice[PRICE_TYPE]['PRINT_VALUE_VAT'];
       $arPrice['DISCOUNT_VALUE'] = $arTmpPrice[PRICE_TYPE]['PRINT_DISCOUNT_VALUE_VAT'];
       
    }  else {
      $arPrice['VALUE'] = $arTmpPrice[PRICE_TYPE]['PRINT_VALUE_VAT']; 
    }    
    
    $arResult['TOP'][$k] = array('CAPTION'=>$filter['NAME'],
                                    'ID'=>$product['ID'],
                                    'NAME'=>$product['NAME'],                                    
                                    'LEFT'=>'70px',                                    
                                    'DESIGNER'=>$product['PROPERTY_DESIGNER_NAME'],
                                    'DETAIL_PAGE_URL'=>$product['DETAIL_PAGE_URL'],
                                    'PRICE'=>$arPrice,
                                    'PICTURE'=>$thumb);
   }
  }
    

  
  $this->IncludeComponentTemplate();
}
?>