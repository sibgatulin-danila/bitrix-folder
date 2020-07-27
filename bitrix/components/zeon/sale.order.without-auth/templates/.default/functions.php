<? function PrintPropsForm($arSource=Array(), $PRINT_TITLE = "", $arParams,$post) {
	global $mapCounter;
	global $location;
	if (!empty($arSource)) {
		if (strlen($PRINT_TITLE) > 0):?>
			<?/*<div class="title"><?= $PRINT_TITLE ?></div> */?>
		<?endif?>
		<?	
		global $USER;
		$email = $USER->GetEmail();
		?>
		<table class="sale_order_full_table">
		<?foreach($arSource as $arProperties) { ?>
		 <? if($arProperties['CODE']=='EMAIL' && !empty($email)):?>
			 <input type="hidden"  value="<?=$email?>" name="<?=$arProperties["FIELD_NAME"]?>">
			<?continue;?>
		  <?endif?>		 
			<tr>				
				<td>
					<?
					if($arProperties["TYPE"] == "CHECKBOX")
					{
						?>
						<div class="text_wrap">			
						 <?=$arProperties["NAME"] ?>
						  <div class="input_wrap">	
						    <input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
						  </div>
						</div>
						<?
					}
					elseif($arProperties["TYPE"] == "TEXT") {
					?>
					
					
					<div class="text_wrap">			
	 				 <?=$arProperties["NAME"] ?><?if(in_array($arProperties['ID'],array(2,5,6,18))):?><span class="pink">*</span><?endif?>
					   <div class="input_wrap">						 
						<input type="text" <?if(!empty($mapCounter[$arProperties["FIELD_NAME"]])):?>onclick="yaCounter21794221.reachGoal('<?=$mapCounter[$arProperties["FIELD_NAME"]]?>');" <?endif?> maxlength="250" size="<?=$arProperties["SIZE1"]?>" value="<?=!empty($post[$arProperties["FIELD_NAME"]])?$post[$arProperties["FIELD_NAME"]]:$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>">
					   </div>
					</div>
					
					<?
					}
					elseif($arProperties["TYPE"] == "SELECT") {
						?>
						<select name="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
						<?
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
						}
						?>
						</select>
						<?
					} elseif ($arProperties["TYPE"] == "TEXTAREA") { ?>					     					     
						<?if($arProperties["FIELD_NAME"]=='ORDER_PROP_17'):?>
						 <span id="comments_show"><span>Добавить комментарий</span></span>
						<?endif?>
							
						<div class="text_wrap<?if($arProperties["FIELD_NAME"]=='ORDER_PROP_17'):?> comments<?endif?>">			
						 <?=$arProperties["NAME"] ?>
						  <div class="textarea_wrap">	
						  <textarea   <?if(!empty($mapCounter[$arProperties["FIELD_NAME"]])):?>onclick="yaCounter21794221.reachGoal('<?=$mapCounter[$arProperties["FIELD_NAME"]]?>');" <?endif?> rows="<?=$arProperties["SIZE2"]?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
						</div>
						</div>
					
					<?
					}
					elseif ($arProperties["TYPE"] == "LOCATION") {
						$value = 0;
						foreach ($arProperties["VARIANTS"] as $arVariant) 
						{
							if ($arVariant["SELECTED"] == "Y") 
							{
								$value = $arVariant["ID"]; 
								break;
							}
						}
						
					$locationValue = !empty($value)?$value:$location['ID'];
					$bxLocation = CSaleLocation::GetByID($locationValue);					

							$GLOBALS["APPLICATION"]->IncludeComponent(
								"zeon:sale.locations",
								".default",
								array(
									"AJAX_CALL" => "N",
									"COUNTRY_INPUT_NAME" => "COUNTRY_".$arProperties["FIELD_NAME"],
									"REGION_INPUT_NAME" => "REGION_".$arProperties["FIELD_NAME"],
									"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
									"CITY_OUT_LOCATION" => "Y",
									'IN_ORDER'=>'Y',
									"LOCATION_VALUE" =>$bxLocation['ID'],
									"ORDER_PROPS_ID" => $arProperties["ID"],
									"ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "cityChange();" : "",
								),
								null,
								array('HIDE_ICONS' => 'Y')
							);						
	
					
		
					}
					elseif ($arProperties["TYPE"] == "RADIO") {
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>" value="<?=$arVariants["VALUE"]?>"<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["ID"]?>"><?=$arVariants["NAME"]?></label><br />
							<?
						}
					}

					if (strlen($arProperties["DESCRIPTION"]) > 0) {
						?><br /><small><?echo $arProperties["DESCRIPTION"] ?></small><?
					}
					?>
					
				</td>
			</tr>
			<?
		}
		?>
		</table>
		<?
		return true;
	}
	return false;
}

global $location;
 $location = $arResult['IP_LOCATION'];
?>