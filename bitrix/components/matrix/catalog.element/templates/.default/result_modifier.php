<? 

 require_once($_SERVER['DOCUMENT_ROOT'].'/funcs/utils.php');
 
 if(!empty($arResult['PROPERTIES']['DESIGNER']['VALUE'])) {
    $rsDesigner = CIBlockElement::GetList(array(),array('IBLOCK_CODE'=>'designers','ID'=>$arResult['PROPERTIES']['DESIGNER']['VALUE']),false,false,array('ID','NAME','PREVIEW_TEXT','PREVIEW_PICTURE'));
     while($designer = $rsDesigner->GetNext()) {
       if(!empty($designer['PREVIEW_PICTURE'])) {
 	$designer['PREVIEW_PICTURE'] = CFile::GetPath($designer['PREVIEW_PICTURE']);
       }
       $arResult['DESIGNER'] = $designer;
     }
 }

 global $USER;
 $currentUser  = CUser::GetByID($USER->GetID())->GetNext();	   
 $arResult['WISH_LIST'] =  explode(',',$currentUser['UF_WISHLIST']);
 
 
 

 $arResult['GALLERY'] = array();
 
 if(!empty($arResult['DETAIL_PICTURE']['SRC'])) {
  $arResult['GALLERY'][]=array('THUMB'=>CFile::GetPath($arResult['PROPERTIES']['THUMB']['VALUE']),
    	                       'DETAIL'=>$arResult['DETAIL_PICTURE']['SRC']
			       );
 }
 
if(!empty($arResult['PROPERTIES']['PHOTOGALLERY']['VALUE'])) {
  $rsPhotoGallery = CIBlockElement::GetList(array('SORT'=>'ASC'),array('IBLOCK_CODE'=>'photogallery','SECTION_ID'=>$arResult['PROPERTIES']['PHOTOGALLERY']['VALUE']),false,false,array('PREVIEW_PICTURE','DETAIL_PICTURE','PROPERTY_THUMB'));    
 while($photo = $rsPhotoGallery->GetNext()) {   
   $arResult['GALLERY'][]=array('THUMB'=>CFile::GetPath($photo['PROPERTY_THUMB_VALUE']),
   	                        'PREVIEW'=>CFile::GetPath($photo['PREVIEW_PICTURE']),
                                'DETAIL'=>CFile::GetPath($photo['DETAIL_PICTURE']));
			      
 }
}


  
 if(empty($arResult['DETAIL_PICTURE']['SRC']) && !empty($arResult['GALLERY'][0]['DETAIL'])) {
  $arResult['DETAIL_PICTURE']['SRC'] =  $arResult['GALLERY'][0]['DETAIL'];
 }

 $rsBasket = CSaleBasket::GetList(array(),array('PRODUCT_ID'=>$arResult['ID'],'DELAY'=>'Y','FUSER_ID'=>CSaleBasket::GetBasketUserID(),'LID'=>SITE_ID, "ORDER_ID" => "NULL")); 
 if($rsBasket->GetNext()) {
  $arResult['IN_CART'] = 'Y'; 
 }
 
 
 $arrTags = explode(',',$arResult['TAGS']);
 //print_r($arrTags);
 $filterTag = array('LOGIC'=>'OR');
 foreach($arrTags as $tag)  {
  $filterTag[] = "%".$tag."%"; 
 }

    

 
 $rsRecommendProducts = CIBlockElement::GetList(array('rand'=>'rand'),
						array('IBLOCK_CODE'=>'catalog','ACTIVE'=>'Y','!PREVIEW_PICTURE'=>false,'!ID'=>7353,
						      'TAGS'=>$filterTag),
					         false,
						 array('nTopCount'=>6),
						 array('NAME','DETAIL_PAGE_URL','PREVIEW_PICTURE','PROPERTY_DESIGNER.NAME'));
 while($recommendProduct = $rsRecommendProducts->GetNext()) {
   $arResult['RECOMMENDED'][] = array('NAME'=>$recommendProduct['NAME'],
				      'DETAIL_PAGE_URL'=>$recommendProduct['DETAIL_PAGE_URL'],
				      'PIC'=>CFile::GetPath($recommendProduct['PREVIEW_PICTURE']),
				      'DESIGNER'=>$recommendProduct['PROPERTY_DESIGNER_NAME']
				       );
 }
 
 
   
 $arPrices =  CIBlockPriceTools::GetCatalogPrices(1, array(PRICE_TYPE));    
 $arResult['OFFERS'] = CIBlockPriceTools::GetOffersArray(1,array($arResult['ID']),array('SIZE_RINGS'=>'ASC'),array(),array('SIZE_RINGS'),0,$arPrices,1,array());
 
 if(!empty($arResult['OFFERS'])) {
  $arResult['HAS_OFFERS'] = 'Y';
 foreach($arResult['OFFERS'] as $i=>$offer) { 
   if($offer['CAN_BUY']!=1) {
     unset($arResult['OFFERS'][$i]);     
     continue;
   }
   $arResult['OFFERS'][$i]['SIZE_RINGS']['VALUE'] =  strip_tags($offer['DISPLAY_PROPERTIES']['SIZE_RINGS']['DISPLAY_VALUE']);
   $arResult['OFFERS'][$i]['ADD_URL'] = $arResult['DETAIL_PAGE_URL'].'?action=ADD2BASKET&id='.$offer['ID'];
 }
 
  $firstOffer = reset($arResult['OFFERS']);
   
  
  if(!empty($firstOffer)) {
   $arResult["PRICES"][PRICE_TYPE]=$firstOffer['PRICES'][PRICE_TYPE];
    $arResult['CAN_BUY']=1;
  }
  else
   $arResult['CAN_BUY']=0;
  
   $arResult['OFFERS']=array_values($arResult['OFFERS']);
 
 }  


 
 if(count($arResult['OFFERS'])>1) {
 
   $rsPage = CIBlockElement::GetList(array(),array('IBLOCK_CODE'=>'pages','ID'=>2057),false,array('nTopCount'=>1));
  if($page = $rsPage->GetNext()) {
    $arResult['DETAIL_SIZE'] = $page['DETAIL_TEXT'];
  
  }
 }
 
// if($USER->IsAdmin())
 // print_r( $arResult["PRICES"]);
  
 
 
 

?>