<div class="packing__box">
 <h2>Подарочная упаковка</h2>
<div class="inner_d">
  <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="pack__thumb" />
  <div class="flr packing_cart">
   <div class="text">Пожалуйста укажите количество единиц<br>подарочной упаковки</div>
   <table class="bt<?=$arResult['ID']?>" style="margin-top:5px;">
    <td>
       <table>
        <tbody><tr>
         <td width="20"><div class="changeQuant_pack quanDesc ns disabled" data-id="<?=$arResult['ID']?>"></div></td>
         <td align="center" class="quantVal">0</td>
         <td width="20"><div class="changeQuant_pack quanAsc ns" data-id="<?=$arResult['ID']?>"></div></td>
         <td width="80" class="pack_price grc5"><span id="price_val">0</span><span class="rubSymbol">a</span></td>
        </tr>
       </tbody>
       </table>
    </td>
    <td>
     <a class="gray_btn packing_tocart" data-href="<?=$arResult["ADD_URL"]?>" data-pack="1" href="<?=$arResult["ADD_URL"]?>">Добавить</a> 
    </td>
    </tr>
   </table>
  </div>
</div>
<script>
var pack_price = parseInt(<?=$arResult["PRICES"][PRICE_TYPE]['VALUE_VAT']?>);
</script>
</div>
