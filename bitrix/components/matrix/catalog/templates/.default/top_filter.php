<?
 require($_SERVER['DOCUMENT_ROOT'].'/funcs/utils.php');
 
  global $USER;
  
 $arHelpers = getHelpers($arResult["VARIABLES"]["SECTION_CODE"]); 
 $arDesigners = $arHelpers['DESIGNERS'];
 $arColors = $arHelpers['COLORS']; 
 
 global $catalogFilter;


if(!empty($_GET['colors'])) { 
 $catalogFilter['PROPERTY_COLOR'] = explode(',',$_GET['colors']);
}

if(!empty($_GET['designers'])) { 
 $catalogFilter['PROPERTY_DESIGNER'] = explode(',',$_GET['designers']);
}
?>
<div class="topContentBar clearfix">
 
 <div class="sectionFilter fll">
    <div class="filterItem designersList">
	<? if(($cntd = count($catalogFilter['PROPERTY_DESIGNER']))>0):?>
	<div id="counterDesigner" style="display:inline;"><?=$cntd?>
	<div class="btn_clear"></div>
	</div>
	<?else:?>
	<div id="counterDesigner"><?=$cntd?></div>
	<?endif?>
     <div class="name"><span>Дизайнер</span></div>
     <div class="filterContent popup" style="padding-left:0;padding-right:0;width:100px;">
      <div style="width:250px;position:relative;background: #d0cebf;padding:10px 0;">
	 <table cellpadding="0" cellspacing="0"><tr>
	 <td valign="top" style="padding-right:10px;">
	 <?	 
	 $cnt = ceil(count($arDesigners)/2);
	 foreach($arDesigners as $i=>$designer):?>
	   <div class="designerItem<?if(in_array($designer['ID'],$catalogFilter['PROPERTY_DESIGNER'])): $activeDesigners[] = $designer['ID']; echo ' active'; endif?>" data-id="<?=$designer['ID']?>">
	     <?=$designer['NAME']?>
	   </div>
           <?if(($i+1)%$cnt==0):?></td><td valign="top"><?endif?>
	  <?endforeach?>
	 </td>
	 </tr>
	 </table>
     </div>
     </div>
    </div>
    <div class="filterItem colorsList">
        <div id="colorActiveHolder">
	  <?foreach($arColors as $color):?>
	   <?if(in_array($color['ID'],$catalogFilter['PROPERTY_COLOR'])):
	    $activeColors[] = $color['ID'];
	   ?>	    
	    <div class="selectedColor" data-id="<?=$color['ID']?>"><img src="<?=$color['PIC']?>"><div class="btn_clear color_clear"></div></div>
           <?endif?>	
	  <?endforeach?>	  
	 
	</div>
        <div class="name"><span>Цвет</span></div>
	<div class="filterContent popup" style="padding:40px 5px 10px 5px;">
	  <?foreach($arColors as $color):?>
	   <div class="colorItem<?=in_array($color['ID'],$catalogFilter['PROPERTY_COLOR'])?' active':''?>" data-id="<?=$color['ID']?>" style="background-image:url(<?=$color['PIC']?>)"></div>
	  <?endforeach?>
	</div>
    </div>	 
 </div>
 <?if($arResult["VARIABLES"]["SECTION_CODE"]!='new'):?>
 <div class="sectionSorts flr">
  <div class="priceSort name <?=$_GET['order']?>"><span>Сортировать по цене</span></div>
 </div>
 <?endif?>
</div>

<?
$jsDesigner = CUtil::PhpToJSObject($activeDesigners);
$jsColors = CUtil::PhpToJSObject($activeColors);

 if($jsDesigner!="''") { 
  $currentfilter[]="'designers':$jsDesigner";
 }

if($jsColors!="''")
 $currentfilter[]="'colors':$jsColors";
 

?>
<script>

var filterContent = {<?=implode(',',$currentfilter)?>},
    sortContent = {},
    currentSection="<?=$arResult["VARIABLES"]["SECTION_CODE"]?>";
</script>