<?
CModule::IncludeModule('poisondrop');
		global $USER;
 
		$checkUserProfiles = CSaleOrderUserProps::GetList(
				array("DATE_UPDATE" => "DESC"),
				array("PERSON_TYPE_ID" => 1,
				      "USER_ID" => IntVal($USER->GetID()))
			);
		if($_SERVER["REQUEST_METHOD"] == "POST") {
	          parse_str($_POST['POST'],$postParams);
	  	  $arResult['POST_PARAMS'] = $postParams;
		  if($arResult['POST_PARAMS']['is_ajax_post']=='Y' && $arResult['POST_PARAMS']['confirmorder']=='Y') {
		   $APPLICATION->RestartBuffer(); 	
		  }
	 	}
	
 
	
		 
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($arResult['POST_PARAMS']["confirmorder"])) {
			 
			 
			if(IntVal($arResult['POST_PARAMS']["PERSON_TYPE"]) > 0)
			  $arUserResult["PERSON_TYPE_ID"] = IntVal($arResult['POST_PARAMS']["PERSON_TYPE"]);
			if(IntVal($arResult['POST_PARAMS']["PERSON_TYPE_OLD"]) == $arUserResult["PERSON_TYPE_ID"])
			{
				if(isset($arResult['POST_PARAMS']["PROFILE_ID"]))
					$arUserResult["PROFILE_ID"] = IntVal($arResult['POST_PARAMS']["PROFILE_ID"]);
				if(isset($arResult['POST_PARAMS']["PAY_SYSTEM_ID"]))
					$arUserResult["PAY_SYSTEM_ID"] = IntVal($arResult['POST_PARAMS']["PAY_SYSTEM_ID"]);
				if(isset($arResult['POST_PARAMS']["DELIVERY_ID"]))
					$arUserResult["DELIVERY_ID"] = $arResult['POST_PARAMS']["DELIVERY_ID"];
				if(strlen($arResult['POST_PARAMS']["ORDER_DESCRIPTION"]) > 0)
					$arUserResult["ORDER_DESCRIPTION"] = $arResult['POST_PARAMS']["ORDER_DESCRIPTION"];
				if($arResult['POST_PARAMS']["PAY_CURRENT_ACCOUNT"] == "Y")
					$arUserResult["PAY_CURRENT_ACCOUNT"] = "Y";
				if($arResult['POST_PARAMS']["confirmorder"] == "Y")
				{
					$arUserResult["CONFIRM_ORDER"] = "Y";
					$arUserResult["FINAL_STEP"] = "Y";
				}
				if($arResult['POST_PARAMS']["profile_change"] == "Y")
					$arUserResult["PROFILE_CHANGE"] = "Y";
				else
					$arUserResult["PROFILE_CHANGE"] = "N";
			}
			
			
			

			if(IntVal($arUserResult["PERSON_TYPE_ID"]) <= 0)
				$arResult["ERROR"][] = GetMessage("SOA_ERROR_PERSON_TYPE");


	 		 
			foreach($arResult['POST_PARAMS'] as $k => $v)
			{
				if(strpos($k, "ORDER_PROP_") !== false)
				{
					if(strpos($k, "[]") !== false)
						$orderPropId = IntVal(substr($k, strlen("ORDER_PROP_"), strlen($k)-2));
					else
						$orderPropId = IntVal(substr($k, strlen("ORDER_PROP_")));
						
				     
				
                             
					if($orderPropId > 0) {
						 
					    
					    $arUserResult["ORDER_PROP"][$orderPropId] = $v;
					    
					}
					elseif(strpos($k, "COUNTRY_ORDER_PROP_") !== false)
						$arUserResult["ORDER_PROP"]["COUNTRY_".IntVal(substr($k, strlen("COUNTRY_ORDER_PROP_")))] = $v;
				}
				if(strpos($k, "NEW_LOCATION_") !== false && intval($v) > 0)
				{
					$orderPropId = IntVal(substr($k, strlen("NEW_LOCATION_")));
					$arUserResult["ORDER_PROP"][$orderPropId] = $v;
				}
			}
			
			
  			

			$arFilter = array("PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"], "ACTIVE" => "Y", "UTIL" => "N");
			if(!empty($arParams["PROP_".$arUserResult["PERSON_TYPE_ID"]]))
				$arFilter["!ID"] = $arParams["PROP_".$arUserResult["PERSON_TYPE_ID"]];
			$dbOrderProps = CSaleOrderProps::GetList(
					array("SORT" => "ASC"),
					$arFilter,
					false,
					false,
					array("ID", "NAME", "TYPE", "IS_LOCATION", "IS_LOCATION4TAX", "IS_PROFILE_NAME", "IS_PAYER", "IS_EMAIL", "REQUIED", "SORT", "IS_ZIP", "CODE")
				);
			
			while ($arOrderProps = $dbOrderProps->GetNext())
			{
				 
				//if(isset($arUserResult["ORDER_PROP"][$arOrderProps["ID"]]) || isset($arUserResult["ORDER_PROP"]["COUNTRY_".$arOrderProps["ID"]]))
				//{
					$bErrorField = False;
					$curVal = $arUserResult["ORDER_PROP"][$arOrderProps["ID"]];
					
					if ($arOrderProps["TYPE"]=="LOCATION" && ($arOrderProps["IS_LOCATION"]=="Y" || $arOrderProps["IS_LOCATION4TAX"]=="Y"))
					{
						if ($arOrderProps["IS_LOCATION"]=="Y") {
						    $arUserResult["DELIVERY_LOCATION"] = IntVal($curVal);						    
						}
						if ($arOrderProps["IS_LOCATION4TAX"]=="Y")
							$arUserResult["TAX_LOCATION"] = IntVal($curVal);

						if (IntVal($curVal)<=0)
							$bErrorField = True;
					}
					elseif ($arOrderProps["IS_PROFILE_NAME"]=="Y" || $arOrderProps["IS_PAYER"]=="Y" || $arOrderProps["IS_EMAIL"]=="Y" || $arOrderProps["IS_ZIP"]=="Y")
					{
						if ($arOrderProps["IS_PROFILE_NAME"]=="Y")
						{
							$arUserResult["PROFILE_NAME"] = Trim($curVal);
							if (strlen($arUserResult["PROFILE_NAME"])<=0)
								$bErrorField = True;
						}
						if ($arOrderProps["IS_PAYER"]=="Y")
						{
							$arUserResult["PAYER_NAME"] = Trim($curVal);
							if (strlen($arUserResult["PAYER_NAME"])<=0)
								$bErrorField = True;
						}
						if ($arOrderProps["IS_EMAIL"]=="Y")
						{
							$arUserResult["USER_EMAIL"] = Trim($curVal);
							if (strlen($arUserResult["USER_EMAIL"])<=0)
								$bErrorField = True;
							elseif(!check_email($arUserResult["USER_EMAIL"]))
								$arResult["ERROR"][] = GetMessage("SOA_ERROR_EMAIL");
						}
						if ($arOrderProps["IS_ZIP"]=="Y")
						{
							$arUserResult["DELIVERY_LOCATION_ZIP"] = Trim($curVal);
							if (strlen($arUserResult["DELIVERY_LOCATION_ZIP"])<=0)
								$bErrorField = True;
						}
					}
					elseif ($arOrderProps["REQUIED"]=="Y")
					{
						if ($arOrderProps["TYPE"]=="TEXT" || $arOrderProps["TYPE"]=="TEXTAREA" || $arOrderProps["TYPE"]=="RADIO" || $arOrderProps["TYPE"]=="SELECT" || $arOrderProps["TYPE"] == "CHECKBOX")
						{
							if (strlen($curVal)<=0)
								$bErrorField = True;
						}
						elseif ($arOrderProps["TYPE"]=="LOCATION")
						{
							if (IntVal($curVal)<=0)
								$bErrorField = True;
						}
						elseif ($arOrderProps["TYPE"]=="MULTISELECT")
						{
							if (!is_array($curVal) || count($curVal)<=0)
								$bErrorField = True;
						}
					}

					if ($bErrorField)
						$arResult["ERROR"][] = GetMessage("SOA_ERROR_REQUIRE")." \"".$arOrderProps["NAME"]."\"";

				//}//end isset
			}//end while
			 /*global $USER;
			 $current_mail=$USER->GetEmail();
			 
			 if(empty($current_mail) && !empty($arUserResult["USER_EMAIL"])) {
			  	
			  $user = new CUser();
			  $user->Update($USER->GetID(), array('EMAIL'=>$arUserResult["USER_EMAIL"]));
			  
			 } 
			 if(strlen($arResult['POST_PARAMS']["DELIVERY_ID"])<= 0)
			   $arResult["ERROR"][] = 'Не выбрана служба доставки';
			 
			 if(($arResult['POST_PARAMS']["confirmorder"] == "Y") && $arResult['POST_PARAMS']['PAY_SYSTEM_ID']<=0) {
		   	  $arResult["ERROR"][] = 'Выберите платежную систему';				
			}*/
 
 
                   
  
			if(IntVal($arUserResult["DELIVERY_LOCATION"]) > 0)
			{
				if (strlen($arUserResult["DELIVERY_ID"]) > 0 && strpos($arUserResult["DELIVERY_ID"], ":") !== false)
				{
					$delivery = explode(":", $arUserResult["DELIVERY_ID"]);
					$obDeliveryHandler = CSaleDeliveryHandler::GetBySID($delivery[0]);
					$arResult["DELIVERY_SUM"] = $obDeliveryHandler->Fetch();
					$arResult["DELIVERY_PROFILE_SUM"] = $delivery[1];

					$arOrderTmpDel = array(
						"PRICE" => $arResult["ORDER_PRICE"],
						"WEIGHT" => $arResult["ORDER_WEIGHT"],
						"LOCATION_FROM" => COption::GetOptionInt('sale', 'location'),
						"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
						"LOCATION_ZIP" => $arUserResult["DELIVERY_LOCATION_ZIP"],

					);

					$arDeliveryPrice = CSaleDeliveryHandler::CalculateFull($delivery[0], $delivery[1], $arOrderTmpDel, $arResult["BASE_LANG_CURRENCY"]);

					if ($arDeliveryPrice["RESULT"] == "ERROR")
						$arResult["ERROR"][] = $arDeliveryPrice["TEXT"];
					else
						$arResult["DELIVERY_PRICE"] = roundEx($arDeliveryPrice["VALUE"], SALE_VALUE_PRECISION);

				}
				elseif ((IntVal($arUserResult["DELIVERY_ID"]) > 0) && ($arDeliv = CSaleDelivery::GetByID($arUserResult["DELIVERY_ID"])))
				{
					$arDeliv["NAME"] = htmlspecialcharsEx($arDeliv["NAME"]);
					$arResult["DELIVERY_SUM"] = $arDeliv;
					$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arDeliv["PRICE"], $arDeliv["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
				}
				elseif (IntVal($DELIVERY_ID)>0)
				{
					$arResult["DELIVERY"] = "ERROR";
				}

				$arResult["DELIVERY_PRICE_FORMATED"] = SaleFormatCurrency($arResult["DELIVERY_PRICE"], $arResult["BASE_LANG_CURRENCY"]);
			}
		}
?>