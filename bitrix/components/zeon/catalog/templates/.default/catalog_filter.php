<?  
 if(CModule::IncludeModule('poisondrop')) {
   $arHelpers = CPoisonUtils::GetHelpers($arResult["VARIABLES"]["SECTION_CODE"]); 
   $arDesigners = $arHelpers['DESIGNERS'];
   $arColors = $arHelpers['COLORS']; 
   $arSizes = $arHelpers['SIZES'];
 } 

global $catalogFilter;

if(!empty($_GET['colors'])) { 
  $catalogFilter['PROPERTY_COLOR'] = explode(',',$_GET['colors']);
}

if(!empty($_GET['designers'])) { 
  $catalogFilter['PROPERTY_DESIGNER'] = explode(',',$_GET['designers']);
}
if(!empty($_GET['discount'])) {
    $catalogFilter['PROPERTY_DISCOUNT_VALUE'] = explode(',',$_GET['discount']);
}

$arSizesValues = array();
if(!empty($_GET['sizes'])) { 
  $arSizesValues = explode(',',$_GET['sizes']);
  $catalogFilter['ID'] = CIBlockElement::SubQuery('PROPERTY_CML2_LINK', array(
    "IBLOCK_ID" => 11,
    "PROPERTY_SIZE_RINGS" => $arSizesValues,
  ));
}
?>

<div class="b_site_content_bar align_left clearfix">
  <div class="b_catalog_filter b_filter_designer">
      <div class="b_catalog_filter_title us-none">
        <div class="b_filter_designer_counter" <?if(count($catalogFilter['PROPERTY_DESIGNER'])>0):?>style="display:block"<?endif?>>
	       <span class="b_filter_designer_counter_val"><?=count($catalogFilter['PROPERTY_DESIGNER']);?></span>
	       <span class="b_catalog_filter_reset"></span>
        </div>
         Дизайнер<i></i>
      </div>
      <div class="b_catalog_filter_items">         
      	 <table class="b_filter_designer_table"><tr>
      	 <?
      	 $arrChunkDesigner = array_chunk($arDesigners,ceil(count($arDesigners)/2));	
      	 foreach($arrChunkDesigner as $arrDesigners):?>
                 <td class="b_catalog_filter_col">
                <?  foreach($arrDesigners as $designer):?>
      	     <div class="b_catalog_filter_item<?if(in_array($designer['ID'],$catalogFilter['PROPERTY_DESIGNER'])):?> b_catalog_filter_item-active<?endif?>" data-id="<?=$designer['ID']?>"><?=$designer['NAME']?></div>           
      	    <?endforeach?>
                  </td>
      	  <?endforeach?>
      	  </tr></table>
      </div>
  </div>
  <div class="b_catalog_filter b_filter_colors">
     <div class="b_catalog_filter_title us-none">
      <div class="b_filter_color_selections">
        <?foreach($arColors as $color):?>
	   <?if(in_array($color['ID'],$catalogFilter['PROPERTY_COLOR'])):?>
	        <div class="b_filter_color_selections_item">
                      <img src="<?=$color['PIC']?>">
                     <span class="b_catalog_filter_reset" data-id="<?=$color['ID']?>"></span>
	         </div>
	   <?endif?>
	 <?endforeach?>       
      </div>
       Цвет<i></i>
     </div>
     <div class="b_catalog_filter_items">
      <?  foreach($arColors  as $color):?>
      <div class="b_catalog_filter_item<?=in_array($color['ID'],$catalogFilter['PROPERTY_COLOR'])?' b_catalog_filter_item-active':''?>" data-id="<?=$color['ID']?>" style="background-image:url(<?=$color['PIC']?>)"></div>
     <?endforeach?>
     </div>
  </div>

  <?//для колец выводим дополнительно фильтр по размерам?>
  <?if($arResult["VARIABLES"]["SECTION_CODE"]=='koltsa'):?>
  <div class="b_catalog_filter b_filter_sizes">
    <div class="b_catalog_filter_title us-none">
      <div class="b_filter_sizes_counter" <?if(count($arSizesValues)>0):?>style="display:block"<?endif?>>
         <span class="b_filter_sizes_counter_val"><?=count($arSizesValues);?></span>
         <span class="b_catalog_filter_reset"></span>
      </div>
      Размер<i></i>
    </div>
    <div class="b_catalog_filter_items">         
      <table class="b_filter_sizes_table">
        <tr>
         <?
         $arrChunkSizes = array_chunk($arSizes,ceil(count($arSizes)/2)); 
         foreach($arrChunkSizes as $arrChunkSize):?>
            <td class="b_catalog_filter_col">
              <?foreach($arrChunkSize as $size):?>
                <div class="b_catalog_filter_item<?if(in_array($size['ID'],$arSizesValues)):?> b_catalog_filter_item-active<?endif?>" data-id="<?=$size['ID']?>"><?=$size['NAME']?></div>           
              <?endforeach?>
            </td>
          <?endforeach?>
        </tr>
      </table>
    </div>
  </div>
  <?endif?>
    <?if ($arResult['VARIABLES']['SECTION_CODE'] == 'sale'):?>
        <div class="for_pc b_catalog_filter b_filter_discount">
            <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="20%">20%</div>
            <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="30%">30%</div>
            <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="40%">40%</div>
            <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="50%">50%</div>
            <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="60%">60%</div>
            <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="70%">70%</div>
        </div>
    <?endif?>


 <?if($arResult["VARIABLES"]["SECTION_CODE"]!='new'):?>
 <div class="b_catalog_sort">
   <div class="b_catalog_sort_item<? if(in_array($_GET['order'],array('desc','asc'))):?> b_catalog_sort_item-<?=$_GET['order']?><?endif?>"><span>Сортировать по цене</span></div>
 </div>
 <?endif?>
</div>
<?if ($arResult['VARIABLES']['SECTION_CODE'] == 'sale'):?>
    <div class="for_mobi b_catalog_filter b_filter_discount">
        <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="20%">20%</div>
        <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="30%">30%</div>
        <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="40%">40%</div>
        <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="50%">50%</div>
        <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="60%">60%</div>
        <div class="b_catalog_filter_discount b_catalog_filter-active" data-id="70%">70%</div>
    </div>
<?endif?>