<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 require_once $_SERVER['DOCUMENT_ROOT'].'/staff/tcpdf/tcpdf.php';
include('functions.php');
$APPLICATION->AddHeadScript('/js/lib/jquery.searchabledropdown-1.0.8.min.js');
$rsSections = CIBlockSection::GetList(array('SORT'=>'ASC'),array('IBLOCK_ID'=>1,'ACTIVE'=>'Y','!ID'=>533),false,array('ID','CODE','NAME'));
$arrSections = array();
while($sections=$rsSections->GetNext()) {
 $arrSections[$sections['CODE']]=array('ID'=>$sections['ID'],
				       'CODE'=>$sections['CODE'],
				       'NAME'=>$sections['NAME'],
				       );
}

$rsDesigners = CIBlockElement::GetList(array(),array('IBLOCK_CODE'=>'designers','ACTIVE'=>'Y'));
$arDesigners = array();
while($designer = $rsDesigners->GetNext()) {
 $arDesigners[] = array('ID'=>$designer['ID'],'NAME'=>$designer['NAME']);
}

$rsColors = CIBlockElement::GetList(array(),array('IBLOCK_CODE'=>'colors','ACTIVE'=>'Y'));
$arColors = array();
while($color = $rsColors->GetNext()) {
 $arColors[] = array('ID'=>$color['ID'],'NAME'=>$color['NAME'],'PIC'=>CFile::GetPath($color['PREVIEW_PICTURE']));
}


$rsSize = CIBlockProperty::GetPropertyEnum("SIZE", Array('SORT'=>'ASC'), Array("IBLOCK_ID"=>1));
$arSize=array();
while($size = $rsSize->GetNext()) {
  $arSize[$size['ID']]=$size['VALUE'];
}


 CModule::IncludeModule('catalog');
//print_r('<pre>');
//print_r(getValueForText('14',$arParams,$arResult));
//print_r('</pre>');
//print_r();

?>

<?if (count($arResult["ERRORS"])):?>
	<?=ShowError(implode("<br />", $arResult["ERRORS"]))?>
<?endif?>
<?if (strlen($arResult["MESSAGE"]) > 0):?>
	<?=ShowNote($arResult["MESSAGE"])?>
<?endif?>
<?//print_r($arResult);?>
<form name="iblock_add" class="product_add_form" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
<div class="productCart">
 <div class="inner">
  <h1>Карта товара</h1>
<?=bitrix_sessid_post()?>

<div class="fll">
<div class="basicFields">	
 <div class="fieldItem">
  <div class="text_wrap">
   Дизайнер
   <?$currDesigner = getValueForAnchor('13',$arParams,$arResult);?>
  <div class="select_wrap">
  <table cellpadding="0" cellspacing="0"><tr>
  <td> 
   <select  class="fixed_width11" style="width:370px;height:30px;" name="PROPERTY[13][]">
    <?foreach($arDesigners as $designer):?>
     <option value="<?=$designer['ID']?>" <?if($currDesigner[0]==$designer['ID']): $arCurrentProduct['DESIGNER'] = $designer['NAME']; ?>selected="selected"<?endif?>><?=$designer['NAME']?></option>
    <?endforeach?>
   </select>
  </td>
  <td>
   
   <style>
    #select_designer {
     text-decoration:none;
     cursor:pointer;
     display: block;
     width:25px;
     height:30px;
     background: url(/i/plusik.png) no-repeat center center;
    }
   </style>
   <a  id="select_designer"></a></td>
  </tr></table>
  </div>
  </div>
 </div>
 
 
 <div class="fieldItem">
  <div class="text_wrap">
    Название
    
    <div class="input_wrap2">
     <b><?=getValueForText('NAME',$arParams,$arResult)?></b>
     <?/*<input type="hidden" class="text" id="property_name" name="PROPERTY[NAME][0]" value="<?=getValueForText('NAME',$arParams,$arResult)?>" />
       */ ?>
        </div> 
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="text_wrap">
    Символьный код
    <div class="input_wrap"><input type="text" class="text" id="property_code" name="PROPERTY[CODE][0]" value="<?=getValueForText('CODE',$arParams,$arResult)?>" /></div>
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="text_wrap">
   Описание
   <div class="textarea_wrap">     
    <textarea  rows="10" class="text" cols="40" name="PROPERTY[DETAIL_TEXT][0]"><?=getValueForText('DETAIL_TEXT',$arParams,$arResult)?></textarea>
   </div>
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="text_wrap">
   Состав
   <div class="text_wrap">
    <div class="input_wrap">
      <? $arCurrentProduct['SOSTAV'] = getValueForText('35',$arParams,$arResult);
      ?>
     <input type="text" class="text"  name="PROPERTY[35][0]" value="<?=$arCurrentProduct['SOSTAV']?>" />
    </div>
    
   </div>
  </div>
 </div>
  
 <div class="fieldItem">
  <div class="text_wrap">
   Теги через запятую
   <div class="input_wrap">
   <?$APPLICATION->IncludeComponent("bitrix:search.tags.input","",array(
								"VALUE" => $arResult["ELEMENT"]['TAGS'],
								"NAME" => "PROPERTY[TAGS][0]",
								'width'=>'100%',
								'class'=>'text',
								"TEXT" => 'size="'.$arResult["PROPERTY_LIST_FULL"]['TAGS']["COL_COUNT"].'"',
								), null, array("HIDE_ICONS"=>"Y")
				  );    
  ?>
   </div>
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="text_wrap">
   Комментарии
   <div class="textarea_wrap">
    <textarea  rows="10" class="text" cols="40" name="PROPERTY[32][0]"><?=getValueForText('32',$arParams,$arResult)?></textarea>
   </div>
  </div>
 </div>
 

</div>
  <div class="inMainLocation">
  <?
   $archive = getValueForList('56',$arParams,$arResult);
   
   $musthave = getValueForList('17',$arParams,$arResult);
   $sale = getValueForList('18',$arParams,$arResult);
   $nodelivery = getValueForList('54',$arParams,$arResult);
   $exclusive = getValueForList('55',$arParams,$arResult);
   $in_action = getValueForList('82',$arParams,$arResult);
   ?>
   
   <input type="hidden"  id="FIELD_LOC_58" name="PROPERTY[56]" value="<?if(!empty($archive)):?>58<?endif?>"/>
   <input type="hidden"  id="FIELD_LOC_2" name="PROPERTY[17]" value="<?if(!empty($musthave)):?>2<?endif?>"/>
   <input type="hidden"  id="FIELD_LOC_3" name="PROPERTY[18]" value="<?if(!empty($sale)):?>3<?endif?>"/>
   <input type="hidden"  id="FIELD_LOC_48" name="PROPERTY[54]" value="<?if(!empty($nodelivery)):?>48<?endif?>"/>
   <input type="hidden"  id="FIELD_LOC_57" name="PROPERTY[55]" value="<?if(!empty($exclusive)):?>57<?endif?>"/>
   <input type="hidden"  id="FIELD_LOC_78" name="PROPERTY[82]" value="<?if(!empty($in_action)):?>78<?endif?>"/>
   <div class="fields">	
	<div class="item<?if(!empty($musthave)):?> active<?endif?>" data-val="2">     
	  Must Have
	</div>
	<div class="item<?if(!empty($sale)):?> active<?endif?>" data-val="3">     
	  Sale
	</div>
        <div class="item<?if(!empty($nodelivery)):?> active<?endif?>" data-val="48">     
	  No delivery
	</div>
        <div class="item<?if(!empty($exclusive)):?> active<?endif?>" data-val="57">     
	  Эксклюзив
	</div>
       <div class="item<?if(!empty($in_action)):?> active<?endif?>" data-val="78">     
	  Акция
	</div>
      <div class="item<?if(!empty($archive)):?> active<?endif?>" data-val="58">     
	  В архив
	</div>

   </div>
  </div>
</div>

<?//Правая часть?>
<div class="rightParams">
 <table><tr>
 <td width="140" valign="top">
 <div class="fieldItem">
  <div class="text_wrap">
    Артикул
    <? $arCurrentProduct['ARTIKUL'] = getValueForText('5',$arParams,$arResult);?>
    <div class="input_wrap"><input type="text" class="text" id="artikul" name="PROPERTY[5][0]" value="<?=$arCurrentProduct['ARTIKUL']?>" /></div>
  </div>
 </div>
 
 
 <div class="fieldItem">  
    <h3>Цвет</h3>
    
    <?
    $arCurrColors = array();
    foreach($arResult['ELEMENT_PROPERTIES'][14] as $color) {
      $arCurrColors[] = $color['VALUE'];
    }    
    ?>
    <div class="colorBox clearfix">
	<?foreach($arColors as $color):?>
	<input type="hidden" id="FIELD_COLOR_<?=$color['ID']?>" name="PROPERTY[14][]" value="<?=(in_array($color['ID'],$arCurrColors))?$color['ID']:''?>" />
        <div data-id="<?=$color['ID']?>" class="color<?if(in_array($color['ID'],$arCurrColors)):?> active<?endif?>" style="background-image:url(<?=$color['PIC']?>)" data-id="">	  
	</div>
        <?endforeach?>	
    </div>
</div>


 <div class="fieldItem" style="display:none;">  
 <?
 $value=$arResult["ELEMENT"]['DETAIL_PICTURE']; 
 if (!empty($value) && is_array($arResult["ELEMENT_FILES"][$value]) ) {
    if ($arResult["ELEMENT_FILES"][$value]["IS_IMAGE"]) { ?>								
	<img class="thumb" width="100%" src="<?=$arResult["ELEMENT_FILES"][$value]["SRC"]?>" border="0" /><br />
	<input type="checkbox"  name="DELETE_FILE['DETAIL_PICTURE'][0]" id="file_delete_DETAIL_PICTURE_0" value="Y" />
	<label for="file_delete_DETAIL_PICTURE_0"><?=GetMessage("IBLOCK_FORM_FILE_DELETE")?></label><br />
 <?
   }										
 }
 ?>
 <input type="hidden" name="PROPERTY['DETAIL_PICTURE'][0]" value="<?=$value?>" />
 <input type="file" style="width:140px;"  name="PROPERTY_FILE_DETAIL_PICTURE_0" /><br />
 </div>
 
 <? $albumId  = getValueForText(16,$arParams,$arResult)?>
 <input type="hidden" id="albumID" name="PROPERTY[16][0]" value="<?=$albumId?>" />
 <div id="add_gallery" data-albumid="<?=$albumId?>">Альбом</div>
  <div id="albumName"><?=$arResult['ALBUM']['NAME']?></div>
  <pre>
    <??>
  </pre>
 </div>
 <br/>
<?
CModule::IncludeModule("iblock");
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_LOGO_MAG", "PROPERTY_LINKLABEL");
$arFilter = Array("IBLOCK_ID"=>1, "ID"=>$_GET['CODE']);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement()){ 
 $arFields = $ob->GetFields(); 
 $arResult['LOGO'] = $arFields; 
}
?> 
 <div class="fieldItem">
  <div class="text_wrap">
    Лого журнала
    <script src="/js/jquery.dd.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/css/dd.css" />
    <div class="select_wrap" style="margin:0;">
      <select class="styled" name="logo" value="<?=$arResult['LOGO']['PROPERTY_LOGO_MAG_VALUE']?>">
            <option data-url="" value="">Нет</option>
          <?foreach ($arResult['LOGO_MAGAZINE'] as $logo):?>
            <option data-url="<?=$logo['PROPERTY_LINKLABEL_VALUE']?>" value="<?=CFile::GetPath($logo['DETAIL_PICTURE'])?>" data-image="<?//=CFile::GetPath($logo['DETAIL_PICTURE'])?>" <?if(CFile::GetPath($logo['DETAIL_PICTURE'])==$arResult['LOGO']['PROPERTY_LOGO_MAG_VALUE']):?>selected<?endif;?>><?=$logo['NAME']?></option>
          <?endforeach;?>
        </select>
        <input type="hidden" name="linklabel" value="<?=$arFields['PROPERTY_LINKLABEL_VALUE']?>"/>
    </div>
      <script language="javascript">
/*        $(document).ready(function(e) {
          try {
            $("#logo-magazine").msDropDown();
          } catch(e) {
            alert(e.message);
          }
        });*/
      </script>
  </div>
 </div> 
<br>

 </td>
 <td width="80">&nbsp;</td>
 <td valign="top" width="120">
  <div class="fieldItem">
  <div class="text_wrap">
    Количество: <b><?=getValueForText('QUANTITY',$arParams,$arResult)?></b>
<?/*    <div class="inputWrap"><input type="text" class="text" name="PROPERTY[QUANTITY][0]" value="<?=getValueForText('QUANTITY',$arParams,$arResult)?>" /></div> */?>
  </div>
 </div><br>
 <?/*<div class="fieldItem">
  <div class="textWrap">
    Цена закупки
    <div class="inputWrap"><input type="text" class="text" name="PROPERTY[19][0]" value="<?=getValueForText('19',$arParams,$arResult)?>" /></div>
  </div>
 </div> */?>
 <? if(CSite::InGroup(array(1))):?>
 <div class="fieldItem">
  <div class="text_wrap">
    Цена
    <?
    $arCurrentProduct['PRICE'] = getValueForText('PRICE',$arParams,$arResult);
    ?>
    <div class="input_wrap"><input type="text" class="text" name="PROPERTY[PRICE][0]" value="<?=$arCurrentProduct['PRICE']?>" /></div>
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="text_wrap">
   Sale
  <?
  $currSale = getValueForList('27',$arParams,$arResult);
  // print_r($currSale);
  $rsDiscountsEnum = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>1, "CODE"=>"DISCOUNT"));
  ?>
  <div class="select_wrap">
   <select class="styled" name="PROPERTY[27][]">
    <option <?if(empty($currSale[0])):?>value="0"<?endif?>>нет</option>
    <?while($discount = $rsDiscountsEnum->GetNext()):?>
     <option value="<?=$discount['ID']?>" <?if($currSale[0]==$discount['ID']):?>selected="selected"<?endif?>><?=$discount['VALUE']?></option>
    <?endwhile?>
   </select>
  </div>
  </div>
 </div>
    
 <div class="fieldItem">
  <div class="text_wrap">
   Promo-скидка
  <?
  $currPromoSale = getValueForList('29',$arParams,$arResult);
  // print_r($currSale);
  $rsDiscountsEnum = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>1, "CODE"=>"PROMO_DOSCOUNT"));
  ?>
  <div class="select_wrap">
   <select class="styled" name="PROPERTY[29][]">
    <option <?if(empty($currPromoSale[0])):?>value="0"<?endif?>>нет</option>
    <?while($discount = $rsDiscountsEnum->GetNext()):?>
     <option value="<?=$discount['ID']?>" <?if($currPromoSale[0]==$discount['ID']):?>selected="selected"<?endif?>><?=$discount['VALUE']?></option>
    <?endwhile?>
   </select>
  </div>
  </div>
 </div>    
  
  <a id="print" target="_blank" href="<?=$APPLICATION->GetCurPageParam('print=yes')?>" style="text-align:center;font-size:15px;color:#808168;"><img src="/i/printer.png" style="vertical-align:bottom;padding-right:10px;"/>Бирка</div>
 <?endif?> 
 </td>
 </tr>
 </table> 
 	
</div>
<div class="clear"></div>
	
</div>
</div>
 <br>
 
 <table><tr>
  <td width="400"><a class="gray_btn" style="float:left;color:#000;text-decoration:none;padding:0 30px;" href="/staff/">Назад</a></td>
  <td><input type="submit"  name="iblock_submit" id="admin_submitForm" class="site_button site_button-green" value="<?=GetMessage("IBLOCK_FORM_SUBMIT")?>" /></td>
  <td>
   <input type="checkbox" name="PROPERTY[ACTIVE][]"  value="Y" <? if($arResult['ELEMENT']['ACTIVE']=='Y'):?> checked="checked"<?endif?> />
   Активность
  </td>	
  </tr>
 </table>
<? 
 if($_GET['print']=='yes') {
  $APPLICATION->RestartBuffer();
/* $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

 $pdf->SetFont('dejavusans', '', 15,'',true); 

 $pdf->AddPage(); // Добавляем страницу
*/
 $html = '
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <style>
  body {
   font-size:27px;
   font-family:Arial;
   color:#2e3315;
   width:600px;
   height:400px;
  }
 .box {
  width:449px;height:278px;
 }
  .caption {margin-bottom:10px;padding-bottom:10px;color:#2b2f18;border-bottom:3px solid #949597;}
    .caption .name {
     float:left;
    }
    
    .caption .date {
     float:right;
    }
    .field_title {
     color:#7b7869;
     padding-right:10px;
    }
 </style>
 </head>
 <body>
 <div class="box">
<div class="caption">
 <div class="name">ООО &laquo;Пойзон Дроп&raquo;</div><div class="date">'.date('d.m.Y').'</div><div style="clear:both"></div></div>

<span class="field_title">'.$arCurrentProduct['SECTION'].':</span>'.$arCurrentProduct['ARTIKUL'].'<br/>
<span  class="field_title">Марка:</span>'.$arCurrentProduct['DESIGNER'].'<br>
<span class="field_title">Состав:</span>'.($arCurrentProduct['SOSTAV']?$arCurrentProduct['SOSTAV']:'N/A').'<br>
<span class="field_title">Размер:</span>N/A<br>
<span class="field_title">Цена:</span>'.$arCurrentProduct['PRICE'].' руб.
</div>
</body>
</html>
';
  echo $html;
  //$pdf->writeHTML($html);
  //$pdf->Output('print.pdf'); 
  
  exit;

 }

 if(CSite::InGroup(array(1)) && $arResult["ELEMENT"]['ID'] ) {
  
  $dbElement = CCatalogStoreDocsElement::getList(array(),array('ELEMENT_ID'=>$arResult["ELEMENT"]['ID']));
  $arDocs = array();
  $storeid = array();
  while($e = $dbElement->GetNext()) {
    
    $storeid[] = $e['STORE_TO'];
    $arDocs[$e['DOC_ID']] = array('AMOUNT'=>$e['AMOUNT'],'PURCHASING_PRICE'=>$e['PURCHASING_PRICE'],'STORE'=>CCatalogStoreControlUtil::getStoreName($e['STORE_TO']));
  }
  if(!empty($arDocs)) {   
   $rsContractors = CCatalogContractor::GetList();
   $arContractors = array();
   while($arContractor = $rsContractors->Fetch()) {
    $arContractors[$arContractor['ID']] = $arContractor['COMPANY'];
   }



   $rsDocs = CCatalogDocs::GetList(array(),array('SITE'=>'s1','ID'=>array_keys($arDocs)));
   while($doc = $rsDocs->GetNext()) {
    
     $arDocs[$doc['ID']]['CURRENCY'] = $doc['CURRENCY'];
     $arDocs[$doc['ID']]['DATE'] = substr($doc['DATE_DOCUMENT'],0,10);
     $arDocs[$doc['ID']]['CONTRACTOR'] =  $arContractors[$doc['CONTRACTOR_ID']];
     
   // $storeid[] = $doc['STORE_TO'];
   // $arDocs[$doc['DOC_ID']] = array('AMOUNT'=>$doc['AMOUNT'],'PURCHASING_PRICE'=>$doc['PURCHASING_PRICE']);
  }
  
 }

  
  
 }
?>
<?if(!empty($arDocs)):?>
<table width="100%" class="cat_store" cellspacing="0"> 
 <tr>
  <th>Дата поставки</th>
  <th>Поставщик</th>
  <th>Склад получатель</th>
  <th>Количество</th>
  <th>Валюта</th>
  <th>Цена закупки</th>
 </tr>
<?
$i=0;
foreach($arDocs as  $doc):?>
<tr <?if($i++%2==0):?>class="odd"<?endif?>>
 <td><?=$doc['DATE']?></td>
 <td><?=$doc['CONTRACTOR']?></td>
 <td><?=$doc['STORE']?></td>
 <td><?=$doc['AMOUNT']?></td>
 <td><?=$doc['CURRENCY']?></td>
 <td><?=$doc['PURCHASING_PRICE']?></td>
</tr>
<?endforeach?>
</table>
<?endif?>
<script>

 var ELEMENT_ID = <?=$arParams['ID']?>;
</script>


<script type="text/javascript">
   $(function() {

   $("#element_active").click(function() {
     if($(this).val()=='Y')
      $(this).val('N');
      else
       $(this).val('Y');
   });
    
    $("#select_designer").click(function() {
        var leftvar = (screen.width-450)/2;
	  var topvar = (screen.height-300)/2;
	  var params = "scrollbars=0,status=0,toolbar=0,location=0,height=700,width=800,left="+leftvar+",top="+topvar;
	  window.open('/staff/designer/', "", params);
	  return false;
    });
    
    $(".fixed_width11").searchable();
   
    });
   
   
   $("#print").click(function() {
      var leftvar = (screen.width-450)/2;
      var topvar = (screen.height-300)/2;      
       var params = "scrollbars=0,status=0,toolbar=0,location=0,height=300,width=600,left="+leftvar+",top="+topvar;
     window.open($(this).attr('href'),'Бирка',params);
     return false;
   });
  </script>
 
 
   <?/*if (strlen($arParams["LIST_URL"]) > 0 && $arParams["ID"] > 0):?>
 	 <input type="submit" name="iblock_apply" class="green_btn" value="<?=GetMessage("IBLOCK_FORM_APPLY")?>" />
   <?endif*/?>
		
  <?if (strlen($arParams["LIST_URL"]) > 0):?><a href="<?=$arParams["LIST_URL"]?>">Назад</a><?endif?>
</form>
