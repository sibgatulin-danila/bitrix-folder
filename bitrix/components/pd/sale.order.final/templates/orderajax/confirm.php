<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<style>
 .stepOrderContent {
   width:100%;			 
 }
</style>


<?
if (!empty($arResult["ORDER"])) {
 
 
$rsOrderLocation = CSaleOrderPropsValue::GetList(
            array("SORT" => "ASC"),
            array(
                    "ORDER_ID" => $arResult['ORDER']['ID'],
		    'ORDER_PROPS_ID'=>2
                    
                )
        );
if($location = $rsOrderLocation->Fetch()) { 
  $orderLocation = CSaleLocation::GetByID($location['VALUE']);  
}

?>

 <script type="text/javascript">
 
  var _gaq = _gaq || [];

  _gaq.push(['_setAccount', 'UA-42461087-1']);
  _gaq.push(['_trackPageview']);
 

  _gaq.push(['_addTrans',
    'Заказ:<?=$arResult['ORDER']['ID']?>',           
    'Poisondrop',
    '<?=$arResult['ORDER']['PRICE']?>',          
    '',        
    '<?=$arResult['ORDER']['PRICE_DELIVERY']?>', 
    '<?=$orderLocation['CITY_NAME']?>',       
    '<?=$orderLocation['REGION_NAME']?>',    
    '<?=$orderLocation['COUNTRY_NAME']?>'    
  ]);
 
  <?
   $rsContentOrder = CSaleBasket::GetList(Array("ID"=>"ASC"), Array("ORDER_ID"=>$arResult['ORDER']['ID']),false,false,array('*'));
  while ($product = $rsContentOrder->Fetch()):
   //print_r($product);     
  ?>
  _gaq.push(['_addItem',
    'Заказ:<?=$product['ORDER_ID']?>',       
    '<?=$product['PRODUCT_ID']?>',           
    '<?=$product['NAME']?>',        
    '',
    '<?=$product['PRICE']?>',        
    '<?=$product['QUANTITY']?>'      
  ]);
  <?endwhile?>
  
 
  _gaq.push(['_trackTrans']); 
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
 
</script>
	
 
 
 <div class="orderBreadCrumb st3">
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
	<div style="width:433px;margin:0 auto;text-align:center;">
	<div class="grc1" style="font-size:18px;">
	<?= GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_ID#" => $arResult["ORDER_ID"]))?>
	</div>
	<br /><br />
	<div style="background:#eeebe4;border-radius:4px;">
	  <div style="padding:20px;">
	    <a href="http://instagram.com/poisondropru"><img src="/i/inst2.png" /></a><br>
	    Будем рады  знакомству с Вами на просторах instagram.<br>
	    Отмечайте фотографии с украшениями<br>
	    тегами <span class="grc1">#poisondropru</span> и <span class="grc1">@poisondropru</span>	    
	    
	  </div>
	</div>
	<?//=GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
	<br>
	 <div class="grc1">До скорой встречи!</div>
	<?
	if (!empty($arResult["PAY_SYSTEM"])) { ?>
		<br />
	 	<?
		
		if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0) { 
                     if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0) {
		       
			      include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
		     }
		  }                
	       
		
	}
	?>
	</div>
	<?
}
else
{
	?>
	<b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b><br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ORDER_ID"]))?>
				<?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>
	<?
}
?>
