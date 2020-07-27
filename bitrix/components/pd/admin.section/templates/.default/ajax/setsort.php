<?
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
 die;
 
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');


 global $USER;   
  
if($USER->IsAdmin()) {   
 
 if(is_numeric($_GET['ID']) && is_numeric($_GET['SORT'])) {
   CModule::IncludeModule('iblock');    
    $new_el = new CIBlockElement;
    $new_el->Update($_GET['ID'],array('DATE_ACTIVE_FROM'=>ConvertTimeStamp($_GET['SORT'], "FULL")),false,false,false);
   
 }
}

Header('Content-Type: application/x-javascript; charset=utf-8');
echo json_encode(array(1));

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
?>