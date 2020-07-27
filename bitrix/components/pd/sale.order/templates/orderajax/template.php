<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
global $mapCounter;
       $mapCounter = array('ORDER_PROP_5'=>'ADDRESS','ORDER_PROP_18'=>'TEL','deliveryems'=>'EMS','deliverycpcr'=>'SPSR','deliveryrussianpost'=>'POST','delivery3'=>'COURIER','delivery4'=>'SAMOVIVOZ','1'=>'NAL','2'=>'BUY_CART');
       	
?>
<a name="order_fform"></a>
<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
 <div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>


<div class="stepOrderContent">
<? if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N") { ?>


<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/include_areas/sale_breadcrumb.php',array('STEP'=>'AUTH'));?>
<?  if(!empty($arResult["ERROR"])) {
	  foreach($arResult["ERROR"] as $v)
	   echo ShowError($v);
     } elseif(!empty($arResult["OK_MESSAGE"])) {
	foreach($arResult["OK_MESSAGE"] as $v)
	  echo ShowNote($v);
     }
   include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
   
}
else {	
	
 if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y") {
    if(strlen($arResult["REDIRECT_URL"]) > 0) { ?>
          <script>	
           window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';	
	 </script>
   <?
	die();
  } else { 
      $confirm = true;			
      include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
  }
 } else { ?>		

		<?if($arResult['POST_PARAMS']["is_ajax_post"] != "Y") { ?>
		   <form action="" method="POST" name="ORDER_FORM" id="ORDER_FORM">
			<?=bitrix_sessid_post()?>
		     <div id="order_form_content">
		<? }
		else {
		  if(empty($arResult['POST_PARAMS']['ajax_refresh']))
		    $APPLICATION->RestartBuffer();			
		}
		
		if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y") {
			foreach($arResult["ERROR"] as $v)
			 echo ShowError($v);

			?>
			<script>
				top.BX.scrollToNode(top.BX('ORDER_FORM'));
			</script>
			<?
		}?>
		
		<div class="stepOrder active" id="step1">			
               <?	       
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
		?>
		
		<?
	        include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");		  
		?>		
		
		<?/* <input type="button" data-step="1" class="next_step green_btn" value="Дальше"/> */?>
		</div>
		<div class="b-paysystems" style="margin-top:20px;" id="step2">			
		  <? include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php"); ?>
                  <input type="button" class="green_btn" name="submitbutton" id="submitButtonOrder" value="Отправить заказ" onclick="yaCounter21794221.reachGoal('PAY_ORDER'); return true;">
        	</div>
		<? if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
		    echo $arResult["PREPAY_ADIT_FIELDS"];
		?>
		<? if($arResult['POST_PARAMS']["is_ajax_post"] != "Y"):?>
		   </div>
		    <input type="hidden" name="confirmorder" id="confirmorder" value="Y">
		    <input type="hidden" name="profile_change" id="profile_change" value="N">
		    <input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
		   <input type="hidden" name="ajax_refresh" id="ajax_refresh" value="" />
		  </form>
		<?else:?>
			<script>
				top.BX('confirmorder').value = 'Y';
				top.BX('profile_change').value = 'N';
			</script>
	       <?
	       if(empty($arResult['POST_PARAMS']['ajax_refresh']))
	         die();
		endif; 
	}
}
?>


<script type="text/template" id="paysystem_template">
<div class="title">Как хотите оплатить?</div>
<div class="b-delivery" id="paysystems">
<div class="error_msg">Выберите способ оплаты</div>
<div class="inner">
<div id="b-paysystems">
<table class="psa_table" style="margin:0 auto;" cellspacing="20">
<tr>
<%
// console.dir(arD2P[DELIVERY_ID]);
for(id in arD2P[DELIVERY_ID]) {
 
 var paySystem = arPaySystem[id];
 
 if(!paySystem)
    continue;
%>  
   <td class="psa_item" data-id="<%=id%>">
      <div>
      <% if(paySystem && paySystem.PSA_LOGOTIP) { %>
	 <img src="<%=paySystem.PSA_LOGOTIP.SRC%>" />
	<% } %>
	 <label for="ID_PAY_SYSTEM_ID_<%=paySystem.ID%>">	  
	  <%=paySystem.PSA_NAME%>
	 </label>
       </div>				
  </td>
				
<% } %>
</tr>
</table>
<input type="hidden"  id="PAY_SYSTEM_ID" name="PAY_SYSTEM_ID" value=""/>

</div>
</div>
</div>

</script>

</div>
<?if(!$confirm):?>
<div class="right_holder">
<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "orderbasket", Array(
	"COLUMNS_LIST" => array(	// Выводимые колонки
		0 => "NAME",
		1 => "PRICE",
		2 => "QUANTITY",
		3 => "DELETE",
		4 => "DISCOUNT",
	),
	"PATH_TO_ORDER" => "/personal/order/make/",	// Страница оформления заказа
	"HIDE_COUPON" => "N",	// Спрятать поле ввода купона
	"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
	),
	false
);?>
</div>
<?endif?>

