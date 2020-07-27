<?
if($arParams['STEP']=='AUTH')
  $step_n = 1;
else if($arParams['STEP']=='DELIVERY')
 $step_n = 2;
else if($arParams['STEP']=='PAY')
 $step_n = 3;
 
?>
<div class="orderBreadCrumb st<?=$step_n?>">
 <div class="step step1">
 Авторизация
</div>
<div class="step step2">
 Адрес и способ<br> доставки 
</div>
 <div class="step step3">
    Подтверждение<br/> и оплата
 </div>	
</div>		