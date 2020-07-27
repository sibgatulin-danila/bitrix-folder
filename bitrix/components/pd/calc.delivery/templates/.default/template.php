<font size="5">
   Доставка &nbsp; &nbsp;&nbsp;
  <br>
</font>
 Для расчета стоимости и возможностей доставки воспользуйтесь таблицей &nbsp;&nbsp;
 <? if($arResult['locationCode']==3094):?>
  <p>Доставка курьером по Москве платная, осуществляется с понедельника по пятницу с 11.00 до 23.00 и в субботу с 10.00 до 18.00. <br>Вы можете уточнить время доставки в пределах трехчасового интервала в комментариях к заказу.</p>
 <? elseif($arResult['locationCode']==3740):?>
   <p>Доставка курьером по Москве за пределами МКАД и Московской области платная, осуществляется с понедельника по пятницу с 11.00 до 23.00.<br> Вы можете уточнить время доставки в пределах трехчасового интервала в комментариях к заказу.</p>
  <? elseif($arResult['locationCode']==3040):?>
   <p>Доставка курьером по Санкт-Петербургу платная, осуществляется с понедельника по субботу с 10.00 до 21.00. <br>Вы можете уточнить время доставки в пределах трехчасового интервала в комментариях к заказу.</p>
 <?endif?>

<div id="topDeliveryLocation">

<? $APPLICATION->IncludeComponent("zeon:sale.locations", "dostavka", array(
									"AJAX_CALL" => "N",
									"COUNTRY_INPUT_NAME" => "ICOUNTRY",
									"REGION_INPUT_NAME" => "IREGION",
									"CITY_INPUT_NAME" => "ICITY",
									"CITY_OUT_LOCATION" => "Y",									
									"LOCATION_VALUE" => $arResult['locationCode'],									
									"ONCITYCHANGE" => "locationChange()",
								),
								null,
								array('HIDE_ICONS' => 'Y')
							);						
	
?>
 <div class="progress"></div>
</div>

<?
$tdWidth  = (960-240)/count($arResult['DELIVERIES']).'px';
?>
<div id="dt" style="position:relative;">

<table class="delivery_table" cellpadding="0" cellspacing="0" width="100%">
 <tr class="even">
  <td class="first">Способ доставки</td>  
   <?foreach($arResult['DELIVERIES'] as $delivery):?>
   <td class="delivery_info" style="width:<?=$tdWidth?>">
    <img src="<?=$delivery['LOGOTIP']?>"/><br>
    <?=$delivery['NAME']?>
   </td>
   <?endforeach?>
 </tr>
 <tr>
  <td class="first">Стоимость доставки</td>  
   <?foreach($arResult['DELIVERIES'] as $delivery):?>
   <td class="delivery_info" style="width:<?=$tdWidth?>">    
    <span class="color_lime" style="font-size:18px;">
    <div style="font-size:12px;"><?=$delivery['ADD_PRICE']?></div>
    <?=intval($delivery['PRICE'])>0?intval($delivery['PRICE']).' р.':'Бесплатно'?></span>
   </td>
   <?endforeach?>
 </tr>
 <tr class="even">
  <td class="first">Сроки доставки*</td>  
   <?foreach($arResult['DELIVERIES'] as $delivery):?>
   <td class="delivery_info" style="width:<?=$tdWidth?>">    
     <?if(!empty($delivery['PERIOD_FROM']) && !empty($delivery['PERIOD_TO'])):?>
       <?=$delivery['PERIOD_FROM'].'-'.$delivery['PERIOD_TO']?> дн.
     <?elseif(!empty($delivery['PERIOD_FROM']) && empty($delivery['PERIOD_TO'])):?>
      <?=$delivery['PERIOD_FROM']?> дн.
     <?endif?>
   </td>
   <?endforeach?>
 </tr>
 <tr>
  <td class="first">Проверка содержания заказа**</td>  
   <?foreach($arResult['DELIVERIES'] as $delivery):?>
   <td class="delivery_info" style="width:<?=$tdWidth?>">
    <?if($delivery['CHECK']=='Y'):?>
      да
     <?else:?>
      нет
    <?endif?>
   </td>
   <?endforeach?>
 </tr>
 <tr class="even">
  <td class="first">Способ оплаты</td>  
   <?foreach($arResult['DELIVERIES'] as $delivery):?>
   <td class="delivery_info" style="width:<?=$tdWidth?>">
    <?foreach($delivery['PAY'] as $ps):?>
      <img src="/i/icons/<?=$ps?>"/>
     <?endforeach?>
   </td>
   <?endforeach?>
 </tr>
 <tr>
  <td class="first">Возможность покупки части заказа</td>  
   <?foreach($arResult['DELIVERIES'] as $delivery):?>
   <td class="delivery_info" style="width:<?=$tdWidth?>">
    <?if($delivery['PART']=='Y'):?>
      да
     <?else:?>
      нет
    <?endif?>
   </td>
   <?endforeach?>
 </tr>
</table>
<br>
<div style="font-size:12px;">
(*) &ndash; Срок доставки рассчитывается с момента подтверждения заказа с оператором интернет-магазина.<br>
(**) &ndash; Возможность вскрыть транспортную упаковку при курьере для проверки содержания заказа и примерки.
</div>
</div>