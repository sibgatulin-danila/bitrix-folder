<?
 
if(CModule::IncludeModule('iblock')) {
  
 $rsBanners  = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>22,'ACTIVE'=>'Y'),false,false,array('ID','NAME'));  
  $arBanners = array();
  while($banner = $rsBanners->GetNext()) {
   $arBanners[$banner['ID']]=$banner['NAME'];   
  }  
 
}
  
 $arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(                
                "BANNER_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => "Какой баннер показываем?",
                        "TYPE" => "LIST",                        
			"VALUES" => $arBanners,
			"DEFAULT" => '',			
		)				
		
	)
);

?>
