<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
if (strlen($arResult["ERROR_MESSAGE"]) <= 0) {
	if (is_array($arResult["WARNING_MESSAGE"]) && !empty($arResult["WARNING_MESSAGE"])) {
		foreach ($arResult["WARNING_MESSAGE"] as $v) {
		  echo ShowError($v);
		}
	}

	$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$normalHidden = ($normalCount == 0) ? "style=\"display:none\"" : "";
  if($normalCount):?>
	<br>
     <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/include_areas/sale_breadcrumb.php',array('STEP'=>'BASKET'));?>
     
		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
		     <div style="position:relative">
			  <div id="basket_form_container">				
 				<?
				 if($arParams['AJAX']=='Y')
					$APPLICATION->RestartBuffer();
				?>
				 <?
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");					
				  ?>
				  <?
				    if($arParams['AJAX']=='Y') {
					 die;
				    }
				  ?>
			
			  </div>
			   <div class="cart_basket_pack">
						<?
						$APPLICATION->IncludeComponent("zeon:product.packing", ".default", array('TITLE_SHOW'=>'N'),
							false
						);
						?>
				 </div>
			</div>
 
			<input type="hidden" name="BasketOrder" value="BasketOrder" />			
		</form>
   <?else:?>
    <div align="center">Ваша корзина пуста</div>
   <?endif?>
<? } else {
  ShowError($arResult["ERROR_MESSAGE"]);
}
?>