<? 
 global $USER;
 $APPLICATION->AddHeadScript('/js/lib/jquery.maskedinput-1.3.min.js');
CModule::IncludeModule('sale');

if ($_SERVER["REQUEST_METHOD"]=="POST") {
 
 if(strlen($_POST["save_delivery"]) > 0 ) {
    
    if(is_numeric($_POST['PROFILE_ID']) && $_POST['PROFILE_ID']>0) {
       $PROFILE_ID = $_POST['PROFILE_ID'];      
    } else {
      $fullname = $USER->GetFullName();
      $PROFILE_ID  = CSaleOrderUserProps::Add(array('NAME'=>!empty($fullname)?$fullname:$USER->GetLogin(),"USER_ID"=>$USER->GetID(), "PERSON_TYPE_ID" => 1));
      $_POST['backurl'] = '/personal/delivery/?PID='.$PROFILE_ID;
    }        
    
    CSaleOrderUserPropsValue::DeleteAll($PROFILE_ID);
    $dbOrderProps = CSaleOrderProps::GetList(
                           array("SORT" => "ASC", "NAME" => "ASC"),
                                   array("PERSON_TYPE_ID" => 1),
                                   false,
                                   false,
                                   array("ID", "PERSON_TYPE_ID", "NAME", "TYPE", "REQUIED", "DEFAULT_VALUE", "SORT", "USER_PROPS", "IS_LOCATION", "PROPS_GROUP_ID", "SIZE1", "SIZE2", "DESCRIPTION", "IS_EMAIL", "IS_PROFILE_NAME", "IS_PAYER", "IS_LOCATION4TAX", "CODE", "SORT")
                           );
                    
                    while ($arOrderProps = $dbOrderProps->GetNext()) {
		         if($arOrderProps['CODE']=='EMAIL') {			  
			   $arFields = array(
                                                   "USER_PROPS_ID" => $PROFILE_ID,
                                                   "ORDER_PROPS_ID" => $arOrderProps["ID"],
                                                   "NAME" => $arOrderProps["NAME"],
                                                   "VALUE" => $USER->GetEmail()
                                           );			  			  
			    CSaleOrderUserPropsValue::Add($arFields);
			  
			  
			 }
			 
                           $curVal = $_POST["ORDER_PROP_".$arOrderProps["ID"]];   
                           if (isset($_POST["ORDER_PROP_".$arOrderProps["ID"]])) {
                                   $arFields = array(
                                                   "USER_PROPS_ID" => $PROFILE_ID,
                                                   "ORDER_PROPS_ID" => $arOrderProps["ID"],
                                                   "NAME" => $arOrderProps["NAME"],
                                                   "VALUE" => $curVal
                                           );
                                   
                               CSaleOrderUserPropsValue::Add($arFields);
                           }
                   
                   }
		   
     if(!empty($_POST['backurl'])) {
       LocalRedirect($_POST['backurl']);
     }
 }  else if(strlen($_POST["del_delivery"]) > 0 ) {
 
   if(is_numeric($_POST['PROFILE_ID']) && $_POST['PROFILE_ID']>0) {
    
    
     $PROFILE_ID = $_POST['PROFILE_ID'];     
     CSaleOrderUserPropsValue::DeleteAll($PROFILE_ID);
     CSaleOrderUserProps::Delete($PROFILE_ID);
     LocalRedirect('/personal/delivery/'); 
   }
 }
    
} 


$dbUserProps = CSaleOrderUserProps::GetList(
		array("DATE_UPDATE" => "DESC"),
		array("USER_ID" => IntVal($GLOBALS["USER"]->GetID())),
		false,
		false,
		array("ID", "NAME", "USER_ID", "PERSON_TYPE_ID", "DATE_UPDATE")
	);

$i=0;
$GET_PROFILE_ID = $_GET['PID'];
while($arUserProps = $dbUserProps->GetNext()) {
    
 if($i++==0 && empty($GET_PROFILE_ID))  
   $arParams['PROFILE_ID']=$arUserProps['ID'];
 else if(is_numeric($GET_PROFILE_ID))  {
    $arParams['PROFILE_ID'] = $GET_PROFILE_ID;
 }
 
 $dbPropVals = CSaleOrderUserPropsValue::GetList(
				array("ID" => "DESC"),
				array("USER_PROPS_ID" => $arUserProps['ID']),
				false,
				false,
				array("ID", "ORDER_PROPS_ID", "VALUE", "SORT",'CODE')
			);
  while ($arPropVals = $dbPropVals->GetNext()) {
    $arResult['PROPS_VALUE'][$arUserProps['ID']][$arPropVals["ORDER_PROPS_ID"]] = $arPropVals["VALUE"];
   
    if($arPropVals["CODE"]=="LOCATION") {         
     if ($arLocation = CSaleLocation::GetByID($arPropVals["VALUE"])) {
      
        if(!empty($arLocation["COUNTRY_NAME_ORIG"]))
         $arResult['DISPLAY_VALUES'][$arUserProps['ID']]['ADDR']['COUNTRY']=$arLocation["COUNTRY_NAME"];        
        if(!empty($arLocation["REGION_NAME_ORIG"]))
         $arResult['DISPLAY_VALUES'][$arUserProps['ID']]['ADDR']['REGION']=$arLocation["REGION_NAME"];
        if(!empty($arLocation["CITY_NAME_ORIG"]))
         $arResult['DISPLAY_VALUES'][$arUserProps['ID']]['ADDR']['CITY']=$arLocation["CITY_NAME"];
     }
    } elseif($arPropVals["CODE"]=="INDEX") {
    // if(!empty($arPropVals["VALUE"]))
     //   $arResult['DISPLAY_VALUES'][$arUserProps['ID']]['ADDR']['INDEX']=$arPropVals["VALUE"];        
    }
    else     
      $arResult['DISPLAY_VALUES'][$arUserProps['ID']][$arPropVals["CODE"]] = $arPropVals["VALUE"];
  }
  
}


$dbProperties = CSaleOrderProps::GetList(array(
						"GROUP_SORT" => "ASC",
						"PROPS_GROUP_ID" => "ASC",
						"SORT" => "ASC",
						"NAME" => "ASC"
					),
				array("PERSON_TYPE_ID"=>1, "ACTIVE" => "Y", "UTIL" => "N"),
				false,
				false,
				array("ID", "NAME", "TYPE", "REQUIED", "DEFAULT_VALUE", "IS_LOCATION", "PROPS_GROUP_ID", "SIZE1", "SIZE2", "DESCRIPTION", "IS_EMAIL", "IS_PROFILE_NAME", "IS_PAYER", "IS_LOCATION4TAX", "CODE", "GROUP_NAME", "GROUP_SORT", "SORT", "USER_PROPS", "IS_ZIP")
			);
		
                while ($arProperties = $dbProperties->GetNext()) {
			unset($curVal);
			if(isset($arResult["POST"]["ORDER_PROP_".$arProperties["ID"]]))
				$curVal = $arResult["POST"]["ORDER_PROP_".$arProperties["ID"]];
                        if($arProperties['CODE']=='EMAIL') {
			  continue;   
			}
			$arProperties["FIELD_NAME"] = "ORDER_PROP_".$arProperties["ID"];
			if (IntVal($arProperties["PROPS_GROUP_ID"]) != $propertyGroupID || $propertyUSER_PROPS != $arProperties["USER_PROPS"])
				$arProperties["SHOW_GROUP_NAME"] = "Y";
			$propertyGroupID = $arProperties["PROPS_GROUP_ID"];
			$propertyUSER_PROPS = $arProperties["USER_PROPS"];

			if ($arProperties["REQUIED"]=="Y" || $arProperties["IS_EMAIL"]=="Y" || $arProperties["IS_PROFILE_NAME"]=="Y" || $arProperties["IS_LOCATION"]=="Y" || $arProperties["IS_LOCATION4TAX"]=="Y" || $arProperties["IS_PAYER"]=="Y" || $arProperties["IS_ZIP"]=="Y")
				$arProperties["REQUIED_FORMATED"]="Y";

			if ($arProperties["TYPE"] == "CHECKBOX") {
				if ($curVal=="Y" || !isset($curVal) && $arProperties["DEFAULT_VALUE"]=="Y")
					$arProperties["CHECKED"] = "Y";
				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 30);
			}
			elseif ($arProperties["TYPE"] == "TEXT")
			{
				if (strlen($curVal) <= 0)
				{
					if(strlen($arProperties["DEFAULT_VALUE"])>0 && !isset($curVal))
						$arProperties["VALUE"] = $arProperties["DEFAULT_VALUE"];
					elseif ($arProperties["IS_EMAIL"] == "Y")
						$arProperties["VALUE"] = $USER->GetEmail();
					elseif ($arProperties["IS_PAYER"] == "Y")
					{
						//$arProperties["VALUE"] = $USER->GetFullName();
						$rsUser = CUser::GetByID($USER->GetID());
						$fio = "";
						if ($arUser = $rsUser->Fetch())
						{
							if (strlen($arUser["LAST_NAME"]) > 0)
								$fio .= $arUser["LAST_NAME"];
							if (strlen($arUser["NAME"]) > 0)
								$fio .= " ".$arUser["NAME"];
							if (strlen($arUser["SECOND_NAME"]) > 0 AND strlen($arUser["NAME"]) > 0)
								$fio .= " ".$arUser["SECOND_NAME"];
						}
						$arProperties["VALUE"] = $fio;
					}
				}
				else {
					$arProperties["VALUE"] = $curVal;
                                        
				}

			}
			elseif ($arProperties["TYPE"] == "SELECT")
			{
				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 1);
				$dbVariants = CSaleOrderPropsVariant::GetList(
						array("SORT" => "ASC"),
						array("ORDER_PROPS_ID" => $arProperties["ID"]),
						false,
						false,
						array("*")
					);
				while ($arVariants = $dbVariants->GetNext())
				{

					if ($arVariants["VALUE"] == $curVal || !isset($curVal) && $arVariants["VALUE"] == $arProperties["DEFAULT_VALUE"])
						$arVariants["SELECTED"] = "Y";
					$arProperties["VARIANTS"][] = $arVariants;
				}
			}
			elseif ($arProperties["TYPE"] == "MULTISELECT")
			{
				$arProperties["FIELD_NAME"] = "ORDER_PROP_".$arProperties["ID"].'[]';
				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 5);
				$arDefVal = explode(",", $arProperties["DEFAULT_VALUE"]);
				$countDefVal = count($arDefVal);
				for ($i = 0; $i < $countDefVal; $i++)
					$arDefVal[$i] = Trim($arDefVal[$i]);

				$dbVariants = CSaleOrderPropsVariant::GetList(
						array("SORT" => "ASC"),
						array("ORDER_PROPS_ID" => $arProperties["ID"]),
						false,
						false,
						array("*")
					);
				while ($arVariants = $dbVariants->GetNext())
				{
					if ((is_array($curVal) && in_array($arVariants["VALUE"], $curVal)) || (!isset($curVal) && in_array($arVariants["VALUE"], $arDefVal)))
						$arVariants["SELECTED"] = "Y";
					$arProperties["VARIANTS"][] = $arVariants;
				}
			}
			elseif ($arProperties["TYPE"] == "TEXTAREA")
			{
				$arProperties["SIZE2"] = ((IntVal($arProperties["SIZE2"]) > 0) ? $arProperties["SIZE2"] : 4);
				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 40);
				$arProperties["VALUE"] = ((isset($curVal)) ? $curVal : $arProperties["DEFAULT_VALUE"]);
			}
			elseif ($arProperties["TYPE"] == "LOCATION")
			{
				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 1);
				$dbVariants = CSaleLocation::GetList(
						array("SORT" => "ASC", "COUNTRY_NAME_LANG" => "ASC", "CITY_NAME_LANG" => "ASC"),
						array("LID" => LANGUAGE_ID),
						false,
						false,
						array("ID", "COUNTRY_NAME", "CITY_NAME", "SORT", "COUNTRY_NAME_LANG", "CITY_NAME_LANG")
					);
				while ($arVariants = $dbVariants->GetNext())
				{
					if (IntVal($arVariants["ID"]) == IntVal($curVal) || !isset($curVal) && IntVal($arVariants["ID"]) == IntVal($arProperties["DEFAULT_VALUE"]))
						$arVariants["SELECTED"] = "Y";
					$arVariants["NAME"] = $arVariants["COUNTRY_NAME"].((strlen($arVariants["CITY_NAME"]) > 0) ? " - " : "").$arVariants["CITY_NAME"];
					$arProperties["VARIANTS"][] = $arVariants;
				}
			}
			elseif ($arProperties["TYPE"] == "RADIO")
			{
				$dbVariants = CSaleOrderPropsVariant::GetList(
						array("SORT" => "ASC"),
						array("ORDER_PROPS_ID" => $arProperties["ID"]),
						false,
						false,
						array("*")
					);
				while ($arVariants = $dbVariants->GetNext())
				{
					if ($arVariants["VALUE"] == $curVal || (strlen($curVal)<=0 && $arVariants["VALUE"] == $arProperties["DEFAULT_VALUE"]))
						$arVariants["CHECKED"]="Y";

					$arProperties["VARIANTS"][] = $arVariants;
				}
			}
			
	$arResult["PROPS"][$arProperties["ID"]] = $arProperties;
			
        }
 
 if(CModule::IncludeModule('poisondrop')) {    
    $arResult['IP_LOCATION'] = CPoisonUtils::getCityByIP();
    
 }
 $this->IncludeComponentTemplate();

?>