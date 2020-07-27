<?

$answer = $_GET['answer'];

if(CModule::IncludeModule('iblock')) { }

 $saleId = $APPLICATION->get_cookie('SALE_UID');
 
 $rsElement  =  CIBlockElement::GetList(array(),array('IBLOCK_ID'=>16,'XML_ID'=>$saleId),false,false,array('ID','PREVIEW_TEXT'));
 
if(in_array($answer,array('yes','no'))) {
    
   $good = 'yes';
    if($rsElement->SelectedRowsCount()==0) {
         
      $arFields = array('IBLOCK_ID'=>16,'NAME'=>$saleId,'XML_ID'=>$saleId,'PROPERTY_VALUES'=>array('VOTE'=>array('VALUE'=>$answer=='yes'?62:63)));
      $el = new CIBlockElement;
      if($el->Add($arFields)) {                    }
    }
 }


if(!empty($_POST['text']) && !empty($_POST['v_feedback_submit'])) {
   $_POST['text']    = substr($_POST['text'],0,2000);
     $element = $rsElement->Fetch();
     if(is_numeric($element['ID'])) {
          if(!empty($element['PREVIEW_TEXT'])) {
                $_POST['text'] = $element['PREVIEW_TEXT'].'  /****/  '.$_POST['text'] ;
          }
          $el = new CIBlockElement;
          $arResult['IS_UPDATE'] ='Y';
          $el->Update($element['ID'],array('PREVIEW_TEXT'=>$_POST['text'],"PREVIEW_TEXT_TYPE"=>'html'));
    }
}

if($good == 'yes') {
    $this->IncludeComponentTemplate();
 } else {
     ?>
       <div style="color:red;text-align:center;">Не был выбраон ответ!</div>  
     <?
 }
?>