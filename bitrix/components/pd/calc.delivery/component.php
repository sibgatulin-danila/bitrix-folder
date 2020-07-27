<?
$APPLICATION->AddHeadScript('/js/lib/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js');
$APPLICATION->SetAdditionalCSS('/js/lib/jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.css');

CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');

if(CModule::IncludeModule('poisondrop')) {					 
 $iplocation= CPoisonUtils::getCityByIP();
 if(!empty($_GET['lid']))
  $arResult['locationCode'] = $_GET['lid'];
 else 
  $arResult['locationCode'] = empty($iplocation['LOCATION_CODE'])?3094:$iplocation['LOCATION_CODE'];
}

 
 $arLocation = CSaleLocation::GetByID($arResult['locationCode']);
 
     $arCompatibility = array(		     
		     "PRICE" => 0,                     
		     "LOCATION_FROM" => COption::GetOptionString('sale', 'location', false, SITE_ID),
		     "LOCATION_TO" => $arResult['locationCode'],
		     "LOCATION_ZIP" => "");
    
    $rsDeliveryServicesList = CSaleDeliveryHandler::GetList(array("SORT" => "ASC"),  array("ACTIVE"=>'Y',"COMPABILITY" =>$arCompatibility));
    
    $arDelivery = array();
    
    while ($tmpDelivery = $rsDeliveryServicesList->Fetch()) {                  
      
      foreach ($tmpDelivery["PROFILES"] as $profile_id => $arDeliveryProfile) {
        if($arDeliveryProfile['ACTIVE']=='N') continue;
	$arDeliveryPrice = CSaleDeliveryHandler::CalculateFull($tmpDelivery['SID'], $profile_id, $arCompatibility, 'RUB');
	
	if(empty($arDeliveryPrice['VALUE']))
	 if($tmpDelivery['SID']=='ems') {
	  $arDeliveryPrice['VALUE'] = 740;
	  $arDeliveryPrice['FROM']  = 3;
	  $arDeliveryPrice['TO']  = 5;
	 } else  continue;
	
	$a_id = $tmpDelivery['SID'].'_'.$profile_id;
        if(!empty($arIblockDeliveries[$a_id]['NAME']))
         $tmpDelivery['NAME'] = $arIblockDeliveries[$a_id]['NAME'];
         
	 if($tmpDelivery['SID']=='russianpost') {
           $arDeliveryPrice['FROM'] = 5;
           $arDeliveryPrice['TO'] = 7;
           $arDeliveryPrice['PAY'] = array('visa.png');
         } else if($tmpDelivery['SID']=='cpcr') {
           list($from,$to)=explode('-',$arDeliveryPrice['TRANSIT']);
	   if($from!=$to) {
	    $arDeliveryPrice['FROM'] = $from;	   
            $arDeliveryPrice['TO'] = $to;
	   } else {
	    $arDeliveryPrice['FROM'] = $from; 
	   }
           $arDeliveryPrice['PAY'] = array('visa.png','nal.png');
           $arDeliveryPrice['CHECK']='Y';
         } else if($tmpDelivery['SID']=='ems') {
	    $arDeliveryPrice['ADD_PRICE'] = 'Бесплатно от 3000 р';
	   if(!empty($arDeliveryPrice['TRANSIT'])) {
            list($from,$to)=explode('-',$arDeliveryPrice['TRANSIT']);
	    if($from!=$to) {
	     $arDeliveryPrice['FROM'] = $from;	   
             $arDeliveryPrice['TO'] = $to;
	    } else {
	     $arDeliveryPrice['FROM'] = $from; 
	    }
	   }
           $arDeliveryPrice['PAY'] = array('visa.png','nal.png');
           if(in_array($arLocation['COUNTRY_ID'],array(22,29,30))) {
	      unset($arDeliveryPrice['PAY'][1]);
	   }
         }
	 
        $arDelivery[] = array('NAME'=>$tmpDelivery['NAME'],
                              'PRICE'=>$arDeliveryPrice['VALUE'],
                              'PERIOD_FROM'=>$arDeliveryPrice['FROM'],
                              'PERIOD_TO'=>$arDeliveryPrice['TO'],
                              'CHECK'=>$arDeliveryPrice['CHECK'],
			      'ADD_PRICE'=>$arDeliveryPrice['ADD_PRICE'],
                              'PAY'=>$arDeliveryPrice['PAY'],
                              'LOGOTIP'=>$tmpDelivery['LOGOTIP']['SRC']);   
      }      
    }   
    

   $arFilter = array("LID" => SITE_ID,
                     "+<=WEIGHT_FROM" => 0,
                     "+>=WEIGHT_TO" => 0,
		     "+<=ORDER_PRICE_FROM" => 0,
                     "+>=ORDER_PRICE_TO" =>0,
                     "ACTIVE" => "Y",
                     "!ID"=>4,
		     "LOCATION" => $arResult['locationCode']);

    $dbDelivery = CSaleDelivery::GetList(array("SORT"=>"ASC"), $arFilter);
    
    while ($delivery = $dbDelivery->Fetch()) {
      if(!empty($arIblockDeliveries[$delivery['ID']]['NAME']))
       $delivery['NAME'] = $arIblockDeliveries[$delivery['ID']]['NAME'];
       
       
       $delivery['LOGOTIP'] = CFile::GetPath($delivery['LOGOTIP']);
       if(in_array($delivery['ID'],array(3,4,5,6,7))) {
         $delivery['CHECK']='Y';
         $delivery['PAY'] = array('visa.png','nal.png');
         $delivery['PART']='Y';
	 $delivery['ADD_PRICE'] = 'Бесплатно от 3000 р';
       }else if(in_array($delivery['ID'],array(1))) {
         $delivery['CHECK']='N';
         $delivery['PAY'] = array('visa.png');
         $delivery['PART']='N';
       }
      $arDelivery[] = $delivery;      
    }
    
     $arResult['DELIVERIES'] =  $arDelivery;

$this->IncludeComponentTemplate();

?>