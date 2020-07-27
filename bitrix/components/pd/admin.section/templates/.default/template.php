<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$rsDesigners = CIBlockElement::GetList(array(),array('IBLOCK_CODE'=>'designers'));
$arDesigner =array();
while($designer = $rsDesigners->GetNext()) {
 $arDesigner[$designer['ID']] = $designer['NAME'];
}

?>
<div class="admin-section">
<table cellpadding="0" cellspacing="0" border="0" id="sorttable">
<thead>
<tr>
<th></th>
<th width="120">Фото</th>
<th>Код</th>
<th width="250">Название</th>
<th>Дизайнер</th>
<th>Артикул</th>
<th>Цена</th>
<th width="50">Акт.</th>
<?/*<th width="50">Сорт.</th> */?>
</tr>
</thead>
<tbody>
<?foreach($arResult["ITEMS"] as $cell=>$arElement):?>		
<tr id="tr<?=$arElement['ID']?>" data-sortval="<?=strtotime($arElement['DATE_ACTIVE_FROM'])-$arElement['TS']?>">
<td>
<?if($_GET['arrFilter_ff']['SECTION_ID']):?>
<div class="up sortbutton" data-id="<?=$arElement['ID']?>"></div>
 <div class="down sortbutton" data-id="<?=$arElement['ID']?>" ></div>
 <?endif?>
</td>
 <td>
  <?if(!empty($arElement["THUMB"])):?>
   <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img border="0" width="90" src="<?=$arElement["THUMB"]?>"  /></a>
 <?endif?>
 </td>					
 <td>
 <?=$arElement['DISPLAY_PROPERTIES']['PRODUCT_CODE']['VALUE']?></td>
 <td><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></td>
 <td><?=$arDesigner[$arElement['DISPLAY_PROPERTIES']['DESIGNER']['VALUE']]?></td>
 <td> 
 <?=$arElement['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE']?>
 </td>
 <td>
    <?if($arElement["PRICES"]['BASE']["DISCOUNT_VALUE"] < $arElement["PRICES"]['BASE']["VALUE"]):?>
	  <s><?=$arPrice["PRINT_VALUE"]?></s>
	  <span><?=$arElement["PRICES"]['BASE']["PRINT_DISCOUNT_VALUE"]?></span>
    <?else:?>
     <span><?=$arElement["PRICES"]['BASE']["PRINT_VALUE"]?></span>
    <?endif;?>
  </td>
 <td><?if($arElement['ACTIVE']=='Y'):?>да<?else:?>нет<?endif?></td>
 <?/*<td class="sort">
   <?=$arElement['DATE_ACTIVE_FROM']?>
 </td> */?>
  </tr>		
<?endforeach;?>
</tbody>
</table>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>

<script>
var  timerSort = '';
$(".sortbutton").click(function() {
  var curr_tr = $(this).closest('tr'),
      prev_tr = curr_tr.prev(),
      next_tr = curr_tr.next();
      
  if(prev_tr.length || next_tr.length) {
    var  idval = $(this).attr('data-id');
         if($(this).hasClass('up'))  {
	  var sortval = parseInt(prev_tr.attr('data-sortval'))+10;	  
         }
	 else if($(this).hasClass('down')) {
	  var sortval = parseInt(next_tr.attr('data-sortval'))-10;
	  
	 }
	 

    $.getJSON('/bitrix/components/pd/admin.section/templates/.default/ajax/setsort.php',{ID:idval,SORT:sortval},function(data) {	
      $("#tr"+idval).attr('data-sortval',sortval);
      $(".sort","#tr"+idval).html(sortval);
      var rows = $("#sorttable tbody tr");    
      rows.sort(function(a, b){
         var keyA = parseInt($(a).attr('data-sortval'));
         var keyB = parseInt($(b).attr('data-sortval'));	 
         return (keyA > keyB) ? -1 : 1;        
      });     
     
       $.each(rows, function(index, row){
        $("#sorttable tbody").append(row);
      });      
  }); 
  }
});

  /* $(".sort_field").keyup(function() {
    var sortval = $(this).val(),
        idval = $(this).attr('data-id');
	
   clearTimeout(timerSort);
    timerSort = setTimeout(function() {	
    $.getJSON('/bitrix/components/pd/admin.section/templates/.default/ajax/setsort.php',{ID:idval,SORT:sortval},function(data) {	
      $("#tr"+idval).attr('data-sortval',sortval);
      var rows = $("#sorttable tbody tr");
      rows.sort(function(a, b){
         var keyA = parseInt($(a).attr('data-sortval'));
         var keyB = parseInt($(b).attr('data-sortval'));
	 console.dir(keyA+'  '+keyB)	;
          return (keyA > keyB) ? 1 : -1;        
      });     
     
       $.each(rows, function(index, row){
        $("#sorttable tbody").append(row);
      });
    });
   },200);
    
    
  }); */
</script>
