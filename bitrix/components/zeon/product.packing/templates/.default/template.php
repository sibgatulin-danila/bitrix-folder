<div class="packing__box" data-json='<?=$arResult['JS_DATA']?>'>
 <?if($arParams['TITLE_SHOW']=='Y'):?>
 <h2>Подарочная упаковка</h2>
 <?endif?>
<div class="inner_d">
  <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="pack__thumb" />
  <div class="flr packing_cart">
   <div class="text">Подарочная упаковка</div>
   <table class="bt<?=$arResult['ID']?>" style="margin-top:5px;">
    <td>
       <div class="pack_price grc5"><span id="price_val"><?=$arResult["PRICE"]?></span><span class="rubSymbol">a</span></div>
    </td>
    <td>
     <a class="gray_btn packing_tocart" data-href="<?=$arResult["ADD_URL"]?>">Добавить</a> 
    </td>
    </tr>
   </table>
  </div>
</div>
<script>
var pack_price = parseInt(<?=$arResult["PRICES"][PRICE_TYPE]['VALUE_VAT']?>);
</script>
</div>
