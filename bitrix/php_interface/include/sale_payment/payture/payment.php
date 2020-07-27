<?php

CModule::IncludeModule('poisondrop');
$orderid = CSalePaySystemAction::GetParamValue("ORDER_ID");

$amount = CSalePaySystemAction::GetParamValue("SHOULD_PAY");
 $arParams = array('SessionType'=>'Block','OrderId'=>$orderid,'Total'=>$amount,'Amount'=>$amount*100);


if(($order = CSaleOrder::GetByID($orderid)) && $order['PAYED']!='Y') {
 
 if(empty($order['PS_STATUS_MESSAGE'])) {

  $status = CPayture::Init($arParams);

  if($status['SUCCESS']=='Y'){
    CSaleOrder::Update($orderid, Array('PS_STATUS_MESSAGE' => $status['sessionId']));
  }
 ?>
  <a target="_blank" class="green_btn" style="width:90px;" href="https://secure.payture.com/apim/Pay?SessionId=<?=$status['sessionId']?>">Оплатить</a>
  <script>
   window.location.href = "https://secure.payture.com/apim/Pay?SessionId=<?=$status['sessionId']?>";
  </script>
<?
 } else {
   $status = CPayture::PayStatus($orderid); 
   if($status['CODE']=='NONE') { ?>
    <a target="_blank" class="green_btn" style="width:90px;" href="https://secure.payture.com/apim/Pay?SessionId=<?=$order['PS_STATUS_MESSAGE']?>">Оплатить</a>
   <? } elseif($status['SUCCESS']=='Y' && $status['STATE']=='Authorized') {
     // $statusCharge  = CPayture::Charge($orderid);
      echo 'Ваш заказ успешно оплачен!';
      /*if($statusCharge['SUCCESS']=='Y') {
        CSaleOrder::Update($orderid, Array('PAYED' => 'Y'));
      } */
    } elseif($status['STATE']=='Charged') {
      echo 'Ваш заказ успешно оплачен!';
    }
 }
}

 

if($order['PAYED']=='Y') {

 echo 'Ваш заказ успешно оплачен!';

}







 

// print_r(CSalePaySystemAction::GetParamValue("KEY").'<br>');

 //print_r(.'<br>');

 

// $arParams['buyer_ip'] = $_SERVER['REMOTE_ADDR'];











?>