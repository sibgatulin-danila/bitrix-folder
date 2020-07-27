<?
 CModule::IncludeModule('iblock');
 CModule::IncludeModule('sale');
 CModule::IncludeModule('poisondrop');
 
$rsUser = CUser::GetList(($by="id"), ($order="desc"),array('!UF_WISHLIST'=>false),array('SELECT'=>array('UF_WISHLIST')));

$arUserWishList = array();
$arWLProductsIds  = array();
while($bxUser = $rsUser->GetNext()) {
 $arWL = explode(',',$bxUser['UF_WISHLIST']);
 $arWLProductsIds = array_merge($arWLProductsIds,(array)$arWL);
 foreach($arWL as $pid)
   $arUserWishList[$pid][$user['ID']] = array('NAME'=>$bxUser['LAST_NAME'].' '.$bxUser['NAME'],'ID'=>$bxUser['ID'],'CNT'=>1);
}



//$arResult = array('WISHLIST');
if(!empty($arWLProductsIds)) {
 $rsProducts = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>1,'ID'=>$arWLProductsIds),false,false);
 while($p = $rsProducts->GetNext())  {
  $arResult[$p['ID']] = array('PRODUCT'=>array('NAME'=>$p['NAME'],'DETAIL_PAGE_URL'=>$p['DETAIL_PAGE_URL'],'IMG'=>CPoisonUtils::getFirstImg($p['ID'])),'USERS'=>array('WISHLIST'=>$arUserWishList[$p['ID']]));
 }
}

$rsBasket = CSaleBasket::GetList(array("ID" => "ASC"),array("LID" => SITE_ID,"ORDER_ID"=>false), false, false);
while($basket = $rsBasket->Fetch()) {  
 if ($arFUser = CSaleUser::GetList(array('ID' => $basket['FUSER_ID']))) 
  $user = CUser::GetByID($arFUser['USER_ID'])->GetNext();
  if(empty($user['ID'])) {
    $user['LAST_NAME']='Не зарегистрирован';
    $user['ID'] = 'noreg';
  }
  
 
 if(!isset($arResult[$basket['PRODUCT_ID']]))
  $arResult[$basket['PRODUCT_ID']]  = array('PRODUCT'=>array('NAME'=>$basket['NAME'],'DETAIL_PAGE_URL'=>$basket['DETAIL_PAGE_URL'],'IMG'=>CPoisonUtils::getFirstImg($basket['PRODUCT_ID'])));
  
 if($basket['SUBSCRIBE']=='Y')
  $arResult[$basket['PRODUCT_ID']]['USERS']['SUBSCRIBE'][$user['ID']]=array('NAME'=>$user['LAST_NAME'].' '.$user['NAME'],'ID'=>$user['ID'],'CNT'=>1);      
 else {
     
  
  if(isset($arResult[$basket['PRODUCT_ID']]['USERS']['INCART'][$user['ID']])) {
   $arResult[$basket['PRODUCT_ID']]['USERS']['INCART'][$user['ID']]['CNT']++;  
  }
  else  
   $arResult[$basket['PRODUCT_ID']]['USERS']['INCART'][$user['ID']]=array('NAME'=>$user['LAST_NAME'].' '.$user['NAME'],'ID'=>$user['ID'],'CNT'=>1);
  
 }
  
}

foreach($arResult as $i=>$item) {
  $c=0; 
  foreach($item['USERS'] as $type)  {
   foreach($type  as $user) {
     $c+=$user['CNT'];
   }
  }
  $arResult[$i]['CNT'] = $c;
  
}
//print_r('<pre>');
//print_r($arResult);
//print_r('</pre>');
function cmp($a,$b) {
 if($a['CNT']>$b['CNT'])
  return -1;
 else
  return 1; 
}
uasort($arResult,'cmp'); 

$this->IncludeComponentTemplate();
?>