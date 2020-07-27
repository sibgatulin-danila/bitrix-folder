<div class="topContentBar"><b>Адрес доставки</b></div>
<div class="profilesList">
<? /*
$i=1;

foreach($arResult['PROPS_VALUE'] as $pid=>$profile):?>
 <div class="item <?if($arParams['PROFILE_ID']==$pid):?> active<?endif?>" data-id="<?=$pid?>">
  <div class="number"><?=$i++?></div> 
  <div class="location">
   <?=implode($arResult['DISPLAY_VALUES'][$pid]['ADDR'],', ');?>
  </div>
  <div class="addr">
    <?=$arResult['DISPLAY_VALUES'][$pid]['ADDRESS']?>
  </div>
  <div class="addr_add">
    <?=$arResult['DISPLAY_VALUES'][$pid]['LOCATION2']?>
  </div>
  <?if(!empty($arResult['DISPLAY_VALUES'][$pid]['TEL'])):?>
  <div class="tel">
    <?=$arResult['DISPLAY_VALUES'][$pid]['TEL']?>
  </div>
  <?endif?>
  <?if(!empty($arResult['DISPLAY_VALUES'][$pid]['CONTACT_PERSON'])):?>
  <div class="contact_person">
    <?=$arResult['DISPLAY_VALUES'][$pid]['CONTACT_PERSON']?>
  </div>
  <?endif?>
  
 </div>
<?endforeach*/?>
</div>
<?/*if(count($arResult['PROPS_VALUE'])>0):?>
<input type="button"  value="Добавить" class="addNewProfile gray_btn"/>
<?endif*/?>

<script>
$(function() { 
 $(".profilesList .item").click(function() {
     $(".profilesList .item").removeClass('active').filter(this).addClass('active');
     $.get("<?=$_SERVER['PHP_SELF']?>?ajax=1&PID="+$(this).attr('data-id'),function(data) {
       $(".formProfile").html(data) ;
       $.scrollTo($(".formProfile"),300);
       cuSel({changedEl: ".styled",visRows: 10, scrollArrows: true});  
     });
 });
 
<?/* $(".addNewProfile").click(function() {
   $.scrollTo($(".formProfile").offset().top-60,300,{axis:'y'});
   $(".formProfile .profileid").val('0');
   $("input[type='text'], textarea",".formProfile").val('');
   
 }); */?>
 
 
});
</script>
<div class="formProfile">
<?
if($_REQUEST['ajax']==1)
 $APPLICATION->RestartBuffer();
?>

<form method="POST" action="">
<?=bitrix_sessid_post()?>
<?if(!empty($arParams['PROFILE_ID'])):?>
 <input type="hidden" name="backurl" value="/personal/delivery/?PID=<?=$arParams['PROFILE_ID']?>" />
 <input type="hidden" value="<?=$arParams['PROFILE_ID']?>" class="profileid" name="PROFILE_ID">
<?endif?>
<table class="sale_order_full_table">
<? foreach($arResult['PROPS'] as $arProperties) {    
    $arProperties['VALUE'] =  $arResult['PROPS_VALUE'][$arParams['PROFILE_ID']][$arProperties['ID']];
?>    
   <tr>
	<td align="right" valign="top">
  	 <div class="textWrap">	               
	<? if($arProperties["TYPE"] == "CHECKBOX") { ?>
          <div class="inputWrap">
	   <input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
           </div>
	<? } elseif($arProperties["TYPE"] == "TEXT") { ?>
            <?=$arProperties["NAME"] ?>:<?/*<? if($arProperties["REQUIED_FORMATED"]=="Y"):?><span class="sof-req">*</span><?endif?>*/?>
          
	    <div class="inputWrap">
            <input type="text" maxlength="250" size="<?=$arProperties["SIZE1"]?>" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>">
            </div>
	<? } elseif($arProperties["TYPE"] == "SELECT") { ?>
           <?=$arProperties["NAME"] ?>:<? if($arProperties["REQUIED_FORMATED"]=="Y"):?><span class="sof-req">*</span><?endif?>
	    <div class="inputWrap"> 
            <select name="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>"> <?
          foreach($arProperties["VARIANTS"] as $arVariants) { ?>
	     <option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
	<?
	}
	?>
	  </select>          
          </div>
	<? 
        }  elseif ($arProperties["TYPE"] == "TEXTAREA") { ?>
         <?=$arProperties["NAME"] ?>:<? if($arProperties["REQUIED_FORMATED"]=="Y"):?><span class="sof-req">*</span><?endif?>
         <div class="textareaWrap">
	  <textarea rows="<?=$arProperties["SIZE2"]?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
          </div>
	
	 <? } elseif ($arProperties["TYPE"] == "LOCATION") {
	  
         if(empty($arProperties['VALUE'])) {	   
	    $arProperties['VALUE'] = $arResult['IP_LOCATION']['LOCATION_CODE'];	    
	 }
	 
  	 $GLOBALS["APPLICATION"]->IncludeComponent(
								"bitrix:sale.ajax.locations",
								".default",
								array(
									"AJAX_CALL" => "N",
									"COUNTRY_INPUT_NAME" => "COUNTRY_".$arProperties["FIELD_NAME"],
									"REGION_INPUT_NAME" => "REGION_".$arProperties["FIELD_NAME"],
									"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
									"CITY_OUT_LOCATION" => "Y",
									"LOCATION_VALUE" =>   $arProperties['VALUE'],
									"ORDER_PROPS_ID" => $arProperties["ID"],
									"ONCITYCHANGE" => "",
								),
								null,
								array('HIDE_ICONS' => 'Y')
							);												
											
						
	 } elseif ($arProperties["TYPE"] == "RADIO") {
	        foreach($arProperties["VARIANTS"] as $arVariants) { ?>
		 <input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>" value="<?=$arVariants["VALUE"]?>"<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>"><?=$arVariants["NAME"]?></label><br />
 <?             }
	}
 ?>
            
         </div>
   	</td>
  </tr>
<?
  }
?>
</table>
<table class="manageProfile">
 <tr>
  <td>
 <input type="submit" name="save_delivery" class="green_btn" value="Сохранить">
  </td>
<?/*  <td>
 <input type="submit" name="del_delivery" class="green_btn" value="Удалить">
  </td> */?>
 </tr>
</table>
<script>

$(function() {
 if($("#COUNTRY_ORDER_PROP_2ORDER_PROP_2").val()==24) { 
  $("input[name=ORDER_PROP_18]").mask("+7 (999) 999-99-99");  
 } 

});
</script>
</form>
<?
 if($_REQUEST['ajax']==1)
 die;
 ?>
</div>