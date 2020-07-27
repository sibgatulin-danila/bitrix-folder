<?
if(CModule::IncludeModule('iblock')) {  
 $rsBanners  = CIBlockElement::GetList(array('RAND'=>'RAND'),array('IBLOCK_ID'=>22,'ACTIVE'=>'Y'),false,array('nTopCount'=>1),array('ID','PREVIEW_PICTURE','PROPERTY_HREF'));  
 $arBanners = array();
 if($banner = $rsBanners->GetNext()) {
   $arResult=array('PIC'=>CFile::GetPath($banner['PREVIEW_PICTURE']),'HREF'=>$banner['PROPERTY_HREF_VALUE']);
   $this->IncludeComponentTemplate();
 }  
 
}




?>