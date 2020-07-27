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
  ga('require', 'ecommerce', 'ecommerce.js');
  
  ga('ecommerce:addTransaction', {
id: '<?=$arResult['ORDER']['ID']?>',
affiliation: 'Poisondrop', // Affiliation or store name
revenue: '<?=$arResult['ORDER']['PRICE']?>', // Grand Total
shipping: '<?=$arResult['ORDER']['PRICE_DELIVERY']?>' , // Shipping cost
tax: '0' }); // Tax.


<?
 $rsContentOrder = CSaleBasket::GetList(Array("ID"=>"ASC"), Array("ORDER_ID"=>$arResult['ORDER']['ID']),false,false,array('*'));
 $arFloktoryItems = array();
    CModule::IncludeModule('poisondrop');
 while ($product = $rsContentOrder->Fetch()):

 $thumb = CPoisonUtils::getFirstThumb($product['PRODUCT_ID']);
 $arFloktoryItems[]='{
          "id":       "'.$product['PRODUCT_ID'].'",
          "title":    "'.$product['NAME'].'",
          "price":    "'.$product['PRICE'].'",
          "image":    "http://poisondrop.ru'.$thumb.'",
          "count":    "'.$product['QUANTITY'].'"
        }';	
	
 ?>    
ga('ecommerce:addItem', {
id: '<?=$product['ORDER_ID']?>', // Transaction ID.
sku:'<?=$product['PRODUCT_ID']?>',
name: '<?=$product['NAME']?>', // Product name.
price: '<?=$product['PRICE']?>', // Unit price.
quantity: '<?=$product['QUANTITY']?>'}); // Quantity.
<?endwhile?>

ga('ecommerce:send');

</script>

<?
$this->SetViewTarget("mix_transact"); 
?>
<script>
var univar1='ID объекта';
var univar2='Сумма транзакции';
document.write('<img src="http://mixmarket.biz/uni/tev.php?id=1294942042&r='+escape(document.referrer)+'&t='+(new Date()).getTime()+'&a1='+univar1+'&a2='+univar2+'" width="1" height="1"/>');</script>
<noscript><img src="http://mixmarket.biz/uni/tev.php?id=1294942042&a1=<?=$arResult['ORDER']['ID']?>&a2=<?=$arResult['ORDER']['PRICE']?>" width="1" height="1"/></noscript>
<?
$this->EndViewTarget("mix_transact"); 
?>

<?
  $order_props = CSaleOrderPropsValue::GetOrderProps($arResult['ORDER']['ID']);
  $email = $fio = '';
  while ($arProps = $order_props->Fetch()) {
    if ($arProps["CODE"] == "EMAIL") {
      $email = $arProps["VALUE"];
    } else if ($arProps["CODE"] == "CONTACT_PERSON") {
      $fio = $arProps['VALUE'];
    }
    
  }
  
  
  
?>
	
<script type="text/javascript">
  //<![CDATA[
    var _flocktory = window._flocktory = _flocktory || [];
    _flocktory.push({
      "order_id":     "<?=$arResult['ORDER']['ID']?>",
      "email":        "<?=$email?>",
      "name":         "<?=$fio?>",
      "sex":          "f",
      "price":        "<?=$arResult['ORDER']['PRICE']?>",      
      "items": [
         <?=implode(',',$arFloktoryItems);?>                
      ]
    });

    (function() {
      var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
      s.src = "//api.flocktory.com/1/hello.js";
      var l = document.getElementsByTagName('script')[0]; l.parentNode.insertBefore(s, l);
    })();
  //]]>
</script>
	
 
 
<?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/include_areas/sale_breadcrumb.php',array('STEP'=>'PAY'));?>
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
