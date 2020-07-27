<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
include('functions.php');

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
<?=bitrix_sessid_post()?>
<div  style="width:250px;margin:0 auto;">
	
 <div class="fieldItem">
  <div class="textWrap">
    Название
    <div class="inputWrap"><input type="text" class="text" id="property_name" name="PROPERTY[NAME][0]" value="<?=getValueForText('NAME',$arParams,$arResult)?>" /></div>
  </div>
 </div>
 
 
 <div class="fieldItem">
  <div class="textWrap" style="width:450px;">
   Описание
   <div class="textareaWrap" style="height:200px;">
    <textarea  rows="20" class="text" cols="60" style="width:450px;height:200px;" name="PROPERTY[PREVIEW_TEXT][0]"><?=getValueForText('PREVIEW_TEXT',$arParams,$arResult)?></textarea>
   </div>
  </div>
 </div>
    <div class="fieldItem">
        <div class="textWrap">
            Sale<br>
            <div class="styled">
                <select name="PROPERTY[93][0]" value="<?=$arResult['ELEMENT_PROPERTIES'][93][0]['VALUE']?>">
                    <option value="0">Нет</option>
                    <?foreach($arResult['PROPERTY_LIST_FULL']['93']['ENUM'] as $discount):?>
                        <option <?if($discount['ID'] == $arResult['ELEMENT_PROPERTIES'][93][0]['VALUE']) echo 'selected'?> value="<?=$discount['ID']?>"><?=$discount['VALUE']?></option>
                    <?endforeach?>
                </select>
            </div>
        </div>
    </div>
   <div class="fieldItem">  
 <?
 $value=$arResult["ELEMENT"]['PREVIEW_PICTURE']; 
 if (!empty($value) && is_array($arResult["ELEMENT_FILES"][$value]) ) {
    if ($arResult["ELEMENT_FILES"][$value]["IS_IMAGE"]) { ?>								
	<img class="thumb"  src="<?=$arResult["ELEMENT_FILES"][$value]["SRC"]?>" border="0" /><br />
	<input type="checkbox"  name="DELETE_FILE['PREVIEW_PICTURE'][0]" id="file_delete_PREVIEW_PICTURE_0" value="Y" />
	<label for="file_delete_PREVIEW_PICTURE_0"><?=GetMessage("IBLOCK_FORM_FILE_DELETE")?></label><br />
 <?
   }										
 }
 ?>
 <input type="hidden" name="PROPERTY['PREVIEW_PICTURE'][0]" value="<?=$value?>" />
 <input type="file" style="width:140px;"  name="PROPERTY_FILE_PREVIEW_PICTURE_0" /><br />
 </div>
  <?
  
  $v = getValueForCheckBox('28',$arParams,$arResult);
 
?>
 <input type="checkbox" name="PROPERTY[28][]"  value="8" <? if($v==8):?> checked="checked"<?endif?> />
   Вывести в меню?
  <input type="hidden" name="PROPERTY[ACTIVE][]"  value="Y" />
  

</div>

</div>
</div>
 <br>
 <table style="margin:0 auto;"><tr>
  <td><input type="submit"  name="iblock_submit" class="green_btn" value="<?=GetMessage("IBLOCK_FORM_SUBMIT")?>" /></td>
  </tr>
 </table>
<? 
 //$ACTIVE = getValueForCheckBox('ACTIVE',$arParams,$arResult); 
 //print_r($ACTIVE);
?>

 
   <?/*if (strlen($arParams["LIST_URL"]) > 0 && $arParams["ID"] > 0):?>
 	 <input type="submit" name="iblock_apply" class="green_btn" value="<?=GetMessage("IBLOCK_FORM_APPLY")?>" />
   <?endif*/?>
		
  <?if (strlen($arParams["LIST_URL"]) > 0):?><a href="<?=$arParams["LIST_URL"]?>">Назад</a><?endif?>
</form>