
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
include('functions.php');

$rsSections = CIBlockSection::GetList(array('SORT'=>'ASC'),array('IBLOCK_ID'=>1,'ACTIVE'=>'Y'),false,array('ID','CODE','NAME'));
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
<?
 $SECTION_VALUE = getValueForCheckBox('IBLOCK_SECTION',$arParams,$arResult);
?>
<input type="hidden" id="FIELD_SECTION" name="PROPERTY[IBLOCK_SECTION][]" value="<?=$SECTION_VALUE?>"/>
<h3>Раздел</h3>
<div class="sections clearfix">
<table cellpadding="0" cellspacing="0"><tr>	
<?foreach($arrSections as $section):?>
 <td class="item <?if($SECTION_VALUE==$section['ID']):?>active<?endif?>" data-id="<?=$section['ID']?>"><?=$section['NAME']?></td>
<?endforeach?>
</tr></table>
</div>

<div class="fll">
<div class="basicFields">	
 <div class="fieldItem">
  <div class="textWrap">
   Дизайнер
   <?$currDesigner = getValueForAnchor('13',$arParams,$arResult);?>
  <div class="selectWrap">
  <table cellpadding="0" cellspacing="0"><tr>
  <td>
 
   <select  class="fixed_width11" style="width:370px;height:30px;" name="PROPERTY[13][]">
    <?foreach($arDesigners as $designer):?>
     <option value="<?=$designer['ID']?>" <?if($currDesigner[0]==$designer['ID']):?>selected="selected"<?endif?>><?=$designer['NAME']?></option>
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
  <div class="textWrap">
    Название
    <div class="inputWrap"><input type="text" class="text" id="property_name" name="PROPERTY[NAME][0]" value="<?=getValueForText('NAME',$arParams,$arResult)?>" /></div>
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="textWrap">
    Символьный код
    <div class="inputWrap"><input type="text" class="text" id="property_code" name="PROPERTY[CODE][0]" value="<?=getValueForText('CODE',$arParams,$arResult)?>" /></div>
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="textWrap">
   Описание
   <div class="textareaWrap">
    <textarea  rows="10" class="text" cols="40" name="PROPERTY[DETAIL_TEXT][0]"><?=getValueForText('DETAIL_TEXT',$arParams,$arResult)?></textarea>
   </div>
  </div>
 </div>
  
 <div class="fieldItem">
  <div class="textWrap">
   Теги через запятую
   <div class="inputWrap">
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
  <div class="textWrap">
    Сортировка
    <div class="inputWrap"><input type="text" class="text" id="property_name" name="PROPERTY[SORT][0]" value="<?=getValueForText('SORT',$arParams,$arResult)?>" /></div>
  </div>
 </div>

 <div class="fieldItem">
  <div class="textWrap">
   Комментарии
   <div class="textareaWrap">
    <textarea  rows="10" class="text" cols="40" name="PROPERTY[32][0]"><?=getValueForText('32',$arParams,$arResult)?></textarea>
   </div>
  </div>
 </div>
 
 
</div>
  <div class="inMainLocation">
  <?
   $new = getValueForList('15',$arParams,$arResult);
   $musthave = getValueForList('17',$arParams,$arResult);
   $sale = getValueForList('18',$arParams,$arResult);   
   ?>
   <h3>На главную</h3>   
   <input type="hidden"  id="FIELD_LOC_1" name="PROPERTY[15]" value="<?if(!empty($new)):?>1<?endif?>"/>
   <input type="hidden"  id="FIELD_LOC_2" name="PROPERTY[17]" value="<?if(!empty($musthave)):?>2<?endif?>"/>
   <input type="hidden"  id="FIELD_LOC_3" name="PROPERTY[18]" value="<?if(!empty($sale)):?>3<?endif?>"/>
   <div class="fields">
	<div class="item<?if(!empty($new)):?> active<?endif?>" data-val="1">     
	  Новинка
	</div>
	<div class="item<?if(!empty($musthave)):?> active<?endif?>" data-val="2">     
	  Must Have
	</div>
	<div class="item<?if(!empty($sale)):?> active<?endif?>" data-val="3">     
	  Sale
	</div>
   </div>
  </div>
</div>

<?//Правая часть?>
<div class="rightParams">
 <table><tr>
 <td width="140" valign="top">
 <div class="fieldItem">
  <div class="textWrap">
    Артикул
    <div class="inputWrap"><input type="text" class="text" name="PROPERTY[5][0]" value="<?=getValueForText('5',$arParams,$arResult)?>" /></div>
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


 <div class="fieldItem">  
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
 
 
 <input type="hidden" id="albumID" name="PROPERTY[16][0]" value="<?=getValueForText(16,$arParams,$arResult)?>" />
 <div id="add_gallery">Альбом</div>
  <div id="albumName"><?=$arResult['ALBUM']['NAME']?></div>
 </div>



 </td>
 <td width="80">&nbsp;</td>
 <td valign="top" width="120">
  <div class="fieldItem">
  <div class="textWrap">
    Количество
    <div class="inputWrap"><input type="text" class="text" name="PROPERTY[QUANTITY][0]" value="<?=getValueForText('QUANTITY',$arParams,$arResult)?>" /></div>
  </div>
 </div><br><br>
 
 
 <div class="fieldItem">
  <div class="textWrap">
    Цена закупки
    <div class="inputWrap"><input type="text" class="text" name="PROPERTY[19][0]" value="<?=getValueForText('19',$arParams,$arResult)?>" /></div>
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="textWrap">
    Цена

    <div class="inputWrap"><input type="text" class="text" name="PROPERTY[PRICE][0]" value="<?=getValueForText('PRICE',$arParams,$arResult)?>" /></div>
  </div>
 </div>
 
 <div class="fieldItem">
  <div class="textWrap">
   Sale
  <?
  $currSale = getValueForList('27',$arParams,$arResult);
  // print_r($currSale);
  $rsDiscountsEnum = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>1, "CODE"=>"DISCOUNT"));
  ?>
  <div class="selectWrap">
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
  <div class="textWrap">
   Promo-скидка
  <?
  $currPromoSale = getValueForList('29',$arParams,$arResult);
  // print_r($currSale);
  $rsDiscountsEnum = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>1, "CODE"=>"PROMO_DOSCOUNT"));
  ?>
  <div class="selectWrap">
   <select class="styled" name="PROPERTY[29][]">
    <option <?if(empty($currPromoSale[0])):?>value="0"<?endif?>>нет</option>
    <?while($discount = $rsDiscountsEnum->GetNext()):?>
     <option value="<?=$discount['ID']?>" <?if($currPromoSale[0]==$discount['ID']):?>selected="selected"<?endif?>><?=$discount['VALUE']?></option>
    <?endwhile?>
   </select>
  </div>
  </div>
 </div>
   
 </td>
 </tr>
 </table>
 	
</div>
<div class="clear"></div>
	
</div>
</div>
 <br>
 <table style="margin:0 auto;"><tr>
  <td><input type="submit"  name="iblock_submit" class="green_btn" value="<?=GetMessage("IBLOCK_FORM_SUBMIT")?>" /></td>
  <td>
   <input type="checkbox" id="element_active" name="PROPERTY[ACTIVE][]"     <? if($arResult['ELEMENT']['ACTIVE']=='Y'):?> value="Y" checked="checked"<?else:?>value="N"<?endif?> />
   <label for="element_active">Активность</label>
  </td>	
  </tr>
 </table>
<? 
 //$ACTIVE = getValueForCheckBox('ACTIVE',$arParams,$arResult); 
 //print_r($ACTIVE);
?>

<script>
 var ELEMENT_ID = <?=$arParams['ID']?>;
</script>

<script type="text/javascript">
   $(document).ready(function() {
    
    
    $(".fixed_width11").searchable();

    });
   
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
  </script>
 
   <?/*if (strlen($arParams["LIST_URL"]) > 0 && $arParams["ID"] > 0):?>
 	 <input type="submit" name="iblock_apply" class="green_btn" value="<?=GetMessage("IBLOCK_FORM_APPLY")?>" />
   <?endif*/?>
		
  <?if (strlen($arParams["LIST_URL"]) > 0):?><a href="<?=$arParams["LIST_URL"]?>">Назад</a><?endif?>
</form>