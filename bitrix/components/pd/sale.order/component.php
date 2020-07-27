<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddHeadScript('/js/lib/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js');
$APPLICATION->SetAdditionalCSS('/js/lib/jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.css');
$APPLICATION->AddHeadScript('/js/lib/jquery.validate.js');
$APPLICATION->AddHeadScript('/js/underscore.js');

if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SOA_MODULE_NOT_INSTALL"));
	return;
}
if (!CModule::IncludeModule("currency"))
{
	ShowError(GetMessage("SOA_CURRENCY_MODULE_NOT_INSTALL"));
	return;
}

if($_REQUEST["AJAX_CALL"] == "Y" || $_REQUEST["is_ajax_post"] == "Y")
{
	$APPLICATION->RestartBuffer();
}
CAjax::Init();

if($arParams["SET_TITLE"] == "Y")
	$APPLICATION->SetTitle(GetMessage("SOA_TITLE"));

/*
$arParams = Array(
		"DELIVERY2PAY_SYSTEM",
		"PAY_FROM_ACCOUNT",
		"COUNT_DELIVERY_TAX",
		"COUNT_DISCOUNT_4_ALL_QUANTITY",
		"PATH_TO_BASKET",
		"SET_TITLE",
		"PATH_TO_PAYMENT",
		"PATH_TO_PERSONAL",
	);
*/

$arParams["PATH_TO_BASKET"] = Trim($arParams["PATH_TO_BASKET"]);
if (strlen($arParams["PATH_TO_BASKET"]) <= 0)
	$arParams["PATH_TO_BASKET"] = "basket.php";

$arParams["PATH_TO_PERSONAL"] = Trim($arParams["PATH_TO_PERSONAL"]);
if (strlen($arParams["PATH_TO_PERSONAL"]) <= 0)
	$arParams["PATH_TO_PERSONAL"] = "index.php";

$arParams["PATH_TO_PAYMENT"] = Trim($arParams["PATH_TO_PAYMENT"]);
if (strlen($arParams["PATH_TO_PAYMENT"]) <= 0)
	$arParams["PATH_TO_PAYMENT"] = "payment.php";

$arParams["PATH_TO_AUTH"] = Trim($arParams["PATH_TO_AUTH"]);
if (strlen($arParams["PATH_TO_AUTH"]) <= 0)
	$arParams["PATH_TO_AUTH"] = "/auth/";

$arParams["PAY_FROM_ACCOUNT"] = (($arParams["PAY_FROM_ACCOUNT"] == "N") ? "N" : "Y");
$arParams["COUNT_DELIVERY_TAX"] = (($arParams["COUNT_DELIVERY_TAX"] == "Y") ? "Y" : "N");
$arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] = (($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N");
$arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] = (($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N");
$arParams["DELIVERY_NO_AJAX"] = (($arParams["DELIVERY_NO_AJAX"] == "Y") ? "Y" : "N");
$arParams["USE_PREPAYMENT"] = $arParams["USE_PREPAYMENT"] == 'Y' ? 'Y' : 'N';
$arParams["DISPLAY_IMG_HEIGHT"] = Intval($arParams["DISPLAY_IMG_HEIGHT"]) <= 0  ? 90 : Intval($arParams["DISPLAY_IMG_HEIGHT"]);
$arParams["DISPLAY_IMG_WIDTH"] = Intval($arParams["DISPLAY_IMG_WIDTH"]) <= 0  ? 90 : Intval($arParams["DISPLAY_IMG_WIDTH"]);

$arParams["DELIVERY_TO_PAYSYSTEM"] = ((strlen($arParams["DELIVERY_TO_PAYSYSTEM"]) <= 0) ? "d2p" : trim($arParams["DELIVERY_TO_PAYSYSTEM"]));

$GLOBALS['CATALOG_ONETIME_COUPONS_BASKET'] = null;
$GLOBALS['CATALOG_ONETIME_COUPONS_ORDER']=null;

$arResult = Array(
		"PERSON_TYPE" => Array(),
		"PAY_SYSTEM" => Array(),
		"ORDER_PROP" => Array(),
		"DELIVERY" => Array(),
		"TAX" => Array(),
		"ERROR" => Array(),
		"ORDER_PRICE" => 0,
		"ORDER_WEIGHT" => 0,
		"VATE_RATE" => 0,
		"VAT_SUM" => 0,
		"bUsingVat" => false,
		"BASKET_ITEMS" => Array(),
		"BASE_LANG_CURRENCY" => CSaleLang::GetLangCurrency(SITE_ID),
		"WEIGHT_UNIT" => htmlspecialcharsbx(COption::GetOptionString('sale', 'weight_unit', false, SITE_ID)),
		"WEIGHT_KOEF" => htmlspecialcharsbx(COption::GetOptionString('sale', 'weight_koef', 1, SITE_ID)),
		"TaxExempt" => Array(),
		"DISCOUNT_PRICE" => 0,
		"DISCOUNT_PERCENT" => 0,
		"DELIVERY_PRICE" => 0,
		"TAX_PRICE" => 0,
		"PAYED_FROM_ACCOUNT_FORMATED" => false,
		"ORDER_TOTAL_PRICE_FORMATED" => false,
		"ORDER_WEIGHT_FORMATED" => false,
		"ORDER_PRICE_FORMATED" => false,
		"VAT_SUM_FORMATED" => false,
		"DELIVERY_SUM" => false,
		"DELIVERY_PROFILE_SUM" => false,
		"DELIVERY_PRICE_FORMATED" => false,
		"DISCOUNT_PERCENT_FORMATED" => false,
		"PAY_FROM_ACCOUNT" => false,
		"CURRENT_BUDGET_FORMATED" => false,
		"USER_ACCOUNT" => false,
		"DISCOUNTS" => Array(),
		"AUTH" => Array(),
		"HAVE_PREPAYMENT" => false,
		"PREPAY_PS" => array(),
		"PREPAY_ADIT_FIELDS" => "",
		"PREPAY_ORDER_PROPS" => array(),
);

$arUserResult = Array(
		"PERSON_TYPE_ID" => false,
		"PAY_SYSTEM_ID" => false,
		"DELIVERY_ID" => false,
		"ORDER_PROP" => false,
		"DELIVERY_LOCATION" => false,
		"TAX_LOCATION" => false,
		"PAYER_NAME" => false,
		"USER_EMAIL" => false,
		"PROFILE_NAME" => false,
		"PAY_CURRENT_ACCOUNT" => false,
		"CONFIRM_ORDER" => false,
		"FINAL_STEP" => false,
		"ORDER_DESCRIPTION" => false,
		"PROFILE_ID" => false,
		"PROFILE_CHANGE" => false,
		"DELIVERY_LOCATION_ZIP" => false,
	);

$arResult["AUTH"]["new_user_registration_email_confirmation"] = ((COption::GetOptionString("main", "new_user_registration_email_confirmation", "N") == "Y") ? "Y" : "N");
$arResult["AUTH"]["new_user_registration"] = ((COption::GetOptionString("main", "new_user_registration", "Y") == "Y") ? "Y" : "N");

$arParams["ALLOW_AUTO_REGISTER"] = (($arParams["ALLOW_AUTO_REGISTER"] == "Y") ? "Y" : "N");
if($arParams["ALLOW_AUTO_REGISTER"] == "Y" && ($arResult["AUTH"]["new_user_registration_email_confirmation"] == "Y" || $arResult["AUTH"]["new_user_registration"] == "N"))
	$arParams["ALLOW_AUTO_REGISTER"] = "N";
$arParams["SEND_NEW_USER_NOTIFY"] = (($arParams["SEND_NEW_USER_NOTIFY"] == "N") ? "N" : "Y");

$allCurrency = CSaleLang::GetLangCurrency(SITE_ID);

if (!$arParams["DELIVERY_NO_SESSION"])
	$arParams["DELIVERY_NO_SESSION"] = "N";

$arResult["BUYER_STORE"] = "";
if (isset($_POST["BUYER_STORE"]))
	$arResult["BUYER_STORE"] = intval($_POST["BUYER_STORE"]);

if (!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
{
	$arResult["AUTH"]["USER_LOGIN"] = ((strlen($_POST["USER_LOGIN"]) > 0) ? htmlspecialcharsbx($_POST["USER_LOGIN"]) : htmlspecialcharsbx(${COption::GetOptionString("main", "cookie_name", "BITRIX_SM")."_LOGIN"}));
	$arResult["AUTH"]["captcha_registration"] = ((COption::GetOptionString("main", "captcha_registration", "N") == "Y") ? "Y" : "N");
	if($arResult["AUTH"]["captcha_registration"] == "Y")
		$arResult["AUTH"]["capCode"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());

	$arResult["POST"] = array();

	if($_SERVER["REQUEST_METHOD"] == "POST" && ($arParams["DELIVERY_NO_SESSION"] == "N" || check_bitrix_sessid()))
	{
		foreach ($_POST as $vname=>$vvalue)
		{
			if (in_array($vname, Array("USER_LOGIN", "USER_PASSWORD", "do_authorize", "NEW_NAME", "NEW_LAST_NAME", "NEW_EMAIL", "NEW_GENERATE", "NEW_LOGIN", "NEW_PASSWORD", "NEW_PASSWORD_CONFIRM", "captcha_sid", "captcha_word", "do_register", "AJAX_CALL", "is_ajax_post")))
				continue;
			if(is_array($vvalue))
			{
				foreach($vvalue as $k => $v)
					$arResult["POST"][htmlspecialcharsbx($vname."[".$k."]")] = htmlspecialcharsbx($v);
			}
			else
				$arResult["POST"][htmlspecialcharsbx($vname)] = htmlspecialcharsbx($vvalue);
		}
		if ($_POST["do_authorize"] == "Y")
		{
			if (strlen($_POST["USER_LOGIN"]) <= 0)
				$arResult["ERROR"][] = GetMessage("STOF_ERROR_AUTH_LOGIN");

			if (empty($arResult["ERROR"]))
			{
				$arAuthResult = $USER->Login($_POST["USER_LOGIN"], $_POST["USER_PASSWORD"], "N");
				if ($arAuthResult != False && $arAuthResult["TYPE"] == "ERROR")
					$arResult["ERROR"][] = GetMessage("STOF_ERROR_AUTH").((strlen($arAuthResult["MESSAGE"]) > 0) ? ": ".$arAuthResult["MESSAGE"] : "" );
			}
		}
		elseif ($_POST["do_register"] == "Y" && $arResult["AUTH"]["new_user_registration"] == "Y")
		{
			if (strlen($_POST["NEW_NAME"]) <= 0)
				$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_NAME");

			if (strlen($_POST["NEW_LAST_NAME"]) <= 0)
				$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_LASTNAME");  

			if (strlen($_POST["NEW_EMAIL"]) <= 0)
				$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_EMAIL");
			elseif (!check_email($_POST["NEW_EMAIL"]))
				$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_BAD_EMAIL");

			$arResult["AUTH"]["NEW_EMAIL"] = $_POST["NEW_EMAIL"];
			

			if (empty($arResult["ERROR"]))
			{
				
				$_POST["NEW_LOGIN"] = $_POST["NEW_EMAIL"];

			$arResult["POST"]["NEW_GENERATE"] = 'N';
			
			if ($arResult["POST"]["NEW_GENERATE"] == "Y") 
				{
	//				$arResult["AUTH"]["NEW_EMAIL"] = $_POST["NEW_EMAIL"];
					$arResult["AUTH"]["NEW_LOGIN"] = $_POST["NEW_EMAIL"];

					$pos = strpos($arResult["AUTH"]["NEW_LOGIN"], "@");
					if ($pos !== false)
						$_POST["NEW_LOGIN"] = substr($arResult["AUTH"]["NEW_LOGIN"], 0, $pos);

					if (strlen($arResult["AUTH"]["NEW_LOGIN"]) > 47)
						$_POST["NEW_LOGIN"] = substr($arResult["AUTH"]["NEW_LOGIN"], 0, 47);

					if (strlen($arResult["AUTH"]["NEW_LOGIN"]) < 3)
						$arResult["AUTH"]["NEW_LOGIN"] .= "_";

					if (strlen($arResult["AUTH"]["NEW_LOGIN"]) < 3)
						$arResult["AUTH"]["NEW_LOGIN"] .= "_";

					$dbUserLogin = CUser::GetByLogin($arResult["AUTH"]["NEW_LOGIN"]);
					if ($arUserLogin = $dbUserLogin->Fetch())
					{
						$newLoginTmp = $arResult["AUTH"]["NEW_LOGIN"];
						$uind = 0;
						do
						{
							$uind++;
							if ($uind == 10)
							{
								$arResult["AUTH"]["NEW_LOGIN"] = $arResult["AUTH"]["NEW_EMAIL"];
								$newLoginTmp = $arResult["AUTH"]["NEW_LOGIN"];
							}
							elseif ($uind > 10)
							{
								$arResult["AUTH"]["NEW_LOGIN"] = "buyer".time().GetRandomCode(2);
								$newLoginTmp = $arResult["AUTH"]["NEW_LOGIN"];
								break;
							}
							else
							{
								$newLoginTmp = $arResult["AUTH"]["NEW_LOGIN"].$uind;
							}
							$dbUserLogin = CUser::GetByLogin($newLoginTmp);
						}
						while ($arUserLogin = $dbUserLogin->Fetch());
						$arResult["AUTH"]["NEW_LOGIN"] = $newLoginTmp;
					}

					$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
					if($def_group!="")
					{
						$GROUP_ID = explode(",", $def_group);
						$arPolicy = $USER->GetGroupPolicy($GROUP_ID);
					}
					else
					{
						$arPolicy = $USER->GetGroupPolicy(array());
					}

					$password_min_length = intval($arPolicy["PASSWORD_LENGTH"]);
					if($password_min_length <= 0)
						$password_min_length = 6;
					$password_chars = array(
						"abcdefghijklnmopqrstuvwxyz",
						"ABCDEFGHIJKLNMOPQRSTUVWXYZ",
						"0123456789",
					);
					if($arPolicy["PASSWORD_PUNCTUATION"] === "Y")
						$password_chars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
					$arResult["AUTH"]["NEW_PASSWORD"] = $arResult["AUTH"]["NEW_PASSWORD_CONFIRM"] = randString($password_min_length+2, $password_chars);
				}
				else
				{
					if (strlen($_POST["NEW_LOGIN"]) <= 0)
						$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_FLAG");

					if (strlen($_POST["NEW_PASSWORD"]) <= 0)
						$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_FLAG1");

					if (strlen($_POST["NEW_PASSWORD"]) > 0 && strlen($_POST["NEW_PASSWORD_CONFIRM"]) <= 0)
						$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_FLAG1");

					if (strlen($_POST["NEW_PASSWORD"]) > 0
						&& strlen($_POST["NEW_PASSWORD_CONFIRM"]) > 0
						&& $_POST["NEW_PASSWORD"] != $_POST["NEW_PASSWORD_CONFIRM"])
						$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_PASS");

					$arResult["AUTH"]["NEW_LOGIN"] = $_POST["NEW_LOGIN"];
					$arResult["AUTH"]["NEW_NAME"] = $_POST["NEW_NAME"];
					$arResult["AUTH"]["NEW_PASSWORD"] = $_POST["NEW_PASSWORD"];
					$arResult["AUTH"]["NEW_PASSWORD_CONFIRM"] = $_POST["NEW_PASSWORD_CONFIRM"];
				}
			}
			

			if (empty($arResult["ERROR"]))
			{

				$arAuthResult = $USER->Register($arResult["AUTH"]["NEW_LOGIN"], $_POST["NEW_NAME"], $_POST["NEW_LAST_NAME"], $arResult["AUTH"]["NEW_PASSWORD"], $arResult["AUTH"]["NEW_PASSWORD_CONFIRM"], $arResult["AUTH"]["NEW_EMAIL"], LANG, $_POST["captcha_word"], $_POST["captcha_sid"]);
				if ($arAuthResult != False && $arAuthResult["TYPE"] == "ERROR")
					$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG").((strlen($arAuthResult["MESSAGE"]) > 0) ? ": ".$arAuthResult["MESSAGE"] : "" );
				else
				{
					if ($USER->IsAuthorized())
					{
						if($arParams["SEND_NEW_USER_NOTIFY"] == "Y")
							CUser::SendUserInfo($USER->GetID(), SITE_ID, GetMessage("INFO_REQ"), true);
						LocalRedirect($APPLICATION->GetCurPageParam());
					}
					else
					{
						$arResult["OK_MESSAGE"][] = GetMessage("STOF_ERROR_REG_CONFIRM");
					}
				}
			}
			$arResult["AUTH"]["~NEW_LOGIN"] = $arResult["AUTH"]["NEW_LOGIN"];
			$arResult["AUTH"]["NEW_LOGIN"] = htmlspecialcharsEx($arResult["AUTH"]["NEW_LOGIN"]);
			$arResult["AUTH"]["~NEW_NAME"] = $_POST["NEW_NAME"];
			$arResult["AUTH"]["NEW_NAME"] = htmlspecialcharsEx($_POST["NEW_NAME"]);
			$arResult["AUTH"]["~NEW_LAST_NAME"] = $_POST["NEW_LAST_NAME"];
			$arResult["AUTH"]["NEW_LAST_NAME"] = htmlspecialcharsEx($_POST["NEW_LAST_NAME"]);
			$arResult["AUTH"]["~NEW_EMAIL"] = $arResult["AUTH"]["NEW_EMAIL"];
			$arResult["AUTH"]["NEW_EMAIL"] = htmlspecialcharsEx($arResult["AUTH"]["NEW_EMAIL"]);
		}
	}
}

if ($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y" )
{
	if(IntVal($_REQUEST["ORDER_ID"]) <= 0)
	{
		  
		$arId = array();
		$DISCOUNT_PRICE_ALL = 0;

		CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), SITE_ID);
		/* Check Values Begin */
		$arSelFields = array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "NAME", "CURRENCY", "CATALOG_XML_ID", "VAT_RATE", "NOTES", "DISCOUNT_PRICE");
		$dbBasketItems = CSaleBasket::GetList(
				array("NAME" => "ASC", "ID" => "ASC"),
				array(
						"FUSER_ID" => CSaleBasket::GetBasketUserID(),
						"LID" => SITE_ID,
						"ORDER_ID" => "NULL"
					),
				false,
				false,
				$arSelFields
			);
		while ($arBasketItems = $dbBasketItems->GetNext())
		{
			if ($arBasketItems["DELAY"] == "N" && $arBasketItems["CAN_BUY"] == "Y")
			{
				$arBasketItems["PRICE"] = roundEx($arBasketItems["PRICE"], SALE_VALUE_PRECISION);
				$arBasketItems["QUANTITY"] = DoubleVal($arBasketItems["QUANTITY"]);
				$arBasketItems["WEIGHT"] = DoubleVal($arBasketItems["WEIGHT"]);
				$arBasketItems["VAT_RATE"] = DoubleVal($arBasketItems["VAT_RATE"]);

				$DISCOUNT_PRICE_ALL += $arBasketItems["DISCOUNT_PRICE"] * $arBasketItems["QUANTITY"];

				$arBasketItems["DISCOUNT_PRICE"] = roundEx($arBasketItems["DISCOUNT_PRICE"], SALE_VALUE_PRECISION);

				$arResult["ORDER_PRICE"] += $arBasketItems["PRICE"] * $arBasketItems["QUANTITY"];
				$arResult["ORDER_WEIGHT"] += $arBasketItems["WEIGHT"] * $arBasketItems["QUANTITY"];
				if($arBasketItems["VAT_RATE"] > 0)
				{
					$arResult["bUsingVat"] = "Y";
					if($arBasketItems["VAT_RATE"] > $arResult["VAT_RATE"])
						$arResult["VAT_RATE"] = $arBasketItems["VAT_RATE"];
					//$arBasketItems["VAT_VALUE"] = roundEx((($arBasketItems["PRICE"] / ($arBasketItems["VAT_RATE"] +1)) * $arBasketItems["VAT_RATE"]), SALE_VALUE_PRECISION);
					$arBasketItems["VAT_VALUE"] = (($arBasketItems["PRICE"] / ($arBasketItems["VAT_RATE"] +1)) * $arBasketItems["VAT_RATE"]);
					$arResult["VAT_SUM"] += roundEx($arBasketItems["VAT_VALUE"] * $arBasketItems["QUANTITY"], SALE_VALUE_PRECISION);
				}
				$arBasketItems["PRICE_FORMATED"] = SaleFormatCurrency($arBasketItems["PRICE"], $arBasketItems["CURRENCY"]);
				$arBasketItems["WEIGHT_FORMATED"] = roundEx(DoubleVal($arBasketItems["WEIGHT"]/$arResult["WEIGHT_KOEF"]), SALE_VALUE_PRECISION)." ".$arResult["WEIGHT_UNIT"];

				if($arBasketItems["DISCOUNT_PRICE"] > 0)
				{
					$arBasketItems["DISCOUNT_PRICE_PERCENT"] = $arBasketItems["DISCOUNT_PRICE"]*100 / ($arBasketItems["DISCOUNT_PRICE"] + $arBasketItems["PRICE"]);
					$arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"] = roundEx($arBasketItems["DISCOUNT_PRICE_PERCENT"], 0)."%";
				}

				$arBasketItems["PROPS"] = Array();
				$dbProp = CSaleBasket::GetPropsList(Array("SORT" => "ASC", "ID" => "ASC"), Array("BASKET_ID" => $arBasketItems["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")));
				while($arProp = $dbProp -> GetNext())
					$arBasketItems["PROPS"][] = $arProp;

				$arResult["BASKET_ITEMS"][] = $arBasketItems;
			}
			$arResult["ORDER_WEIGHT_FORMATED"] = roundEx(DoubleVal($arResult["ORDER_WEIGHT"]/$arResult["WEIGHT_KOEF"]), SALE_VALUE_PRECISION)." ".$arResult["WEIGHT_UNIT"];
			$arResult["ORDER_PRICE_FORMATED"] = SaleFormatCurrency($arResult["ORDER_PRICE"], $arResult["BASE_LANG_CURRENCY"]);
			$arResult["VAT_SUM_FORMATED"] = SaleFormatCurrency($arResult["VAT_SUM"], $arResult["BASE_LANG_CURRENCY"]);

			$arId[] = $arBasketItems["PRODUCT_ID"];

			if (CModule::IncludeModule('catalog'))
			{
				$arParent = CCatalogSku::GetProductInfo($arBasketItems["PRODUCT_ID"]);
				if ($arParent)
					$arId[] = $arParent["ID"];
			}
		}

		if(empty($arResult["BASKET_ITEMS"])) {
		   LocalRedirect($arParams["PATH_TO_BASKET"]);
		   die();
		}
		
		include('post_processing.php');
		

		/* Get product picture */
		$arProductPicture = array();
		if (count($arId) > 0 && CModule::IncludeModule('catalog'))
		{
			$res = CIBlockElement::GetList(array(), array("ID" => $arId), false, false, array("ID", "PREVIEW_PICTURE", "DETAIL_PICTURE"));
			while ($arItems = $res->GetNext())
				$arProductPicture[$arItems["ID"]] = $arItems;
		}

		foreach ($arResult["BASKET_ITEMS"] as &$arResultItem)
		{
			$productId = $arResultItem["PRODUCT_ID"];
			$arParent = CCatalogSku::GetProductInfo($productId);
			if (intval($arProductPicture[$productId]["PREVIEW_PICTURE"]) <= 0
					&& intval($arProductPicture[$productId]["DETAIL_PICTURE"]) <= 0
					&& $arParent)
			{
				$productId = $arParent["ID"];
			}

			//$arResultItem["PREVIEW_PICTURE"] = CFile::GetFileArray($arProductPicture[$productId]["PREVIEW_PICTURE"]);
			//$arResultItem["DETAIL_PICTURE"] = CFile::GetFileArray($arProductPicture[$productId]["DETAIL_PICTURE"]);
		}
		if (isset($arResultItem))
			unset($arResultItem);
		
		if(CModule::IncludeModule('poisondrop')) {					 
		   $iplocation= CPoisonUtils::getCityByIP();
		 //$iplocation['LOCATION_CODE'] = 3094;
		}	
	     if(!empty($iplocation['LOCATION_CODE']))
  	       $arResult['IP_LOCATION'] = CSaleLocation::GetByID($iplocation['LOCATION_CODE']);
			 
 
		/* Person Type Begin */
		$dbPersonType = CSalePersonType::GetList(Array("SORT" => "ASC", "NAME" => "ASC"), Array("LID" => SITE_ID, "ACTIVE" => "Y"));
		while($arPersonType = $dbPersonType->GetNext())
		{
			if($arUserResult["PERSON_TYPE_ID"] == $arPersonType["ID"] || IntVal($arUserResult["PERSON_TYPE_ID"]) <= 0)
			{
				$arUserResult["PERSON_TYPE_ID"] = $arPersonType["ID"];
				$arPersonType["CHECKED"] = "Y";
			}
			$arResult["PERSON_TYPE"][$arPersonType["ID"]] = $arPersonType;
		}


		foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepPersonType", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, Array(&$arResult, &$arUserResult, &$arParams));
		/* Person Type End */


		/* User Profiles Begin */
		$bFirst = false;
		$bFillProfileFields = False;
		$dbUserProfiles = CSaleOrderUserProps::GetList(
				array("DATE_UPDATE" => "DESC"),
				array(
						"PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"],
						"USER_ID" => IntVal($USER->GetID())
					)
			);
		while($arUserProfiles = $dbUserProfiles->GetNext())
		{
			$bFillProfileFields = True;
			
			if(!$bFirst && (empty($arUserResult["PROFILE_CHANGE"]) || $arUserResult["PROFILE_CHANGE"]=='N'))
			{
				$bFirst = true;
				$arUserResult["PROFILE_ID"] = IntVal($arUserProfiles["ID"]);
				$arUserResult["PROFILE_CHANGE"] = "Y";
				$arUserResult["PROFILE_DEFAULT"] = "Y";
			}
			if (IntVal($arUserResult["PROFILE_ID"])==IntVal($arUserProfiles["ID"]))
				$arUserProfiles["CHECKED"] = "Y";
			
			
			
			$arUserProfiles["USER_PROPS_VALUES"] = Array();
				$dbUserPropsValues = CSaleOrderUserPropsValue::GetList(
						array("SORT" => "ASC"),
						array("USER_PROPS_ID" => $arUserProfiles["ID"]),
						false,
						false,
						array("VALUE", "PROP_TYPE", "VARIANT_NAME", "SORT", "ORDER_PROPS_ID")
					);
				while ($arUserPropsValues = $dbUserPropsValues->GetNext())
				{
					$valueTmp = "";
					if ($arUserPropsValues["PROP_TYPE"] == "SELECT"
						|| $arUserPropsValues["PROP_TYPE"] == "MULTISELECT"
						|| $arUserPropsValues["PROP_TYPE"] == "RADIO")
					{
						$arUserPropsValues["VALUE_FORMATED"] = $arUserPropsValues["VARIANT_NAME"];
					}
					elseif ($arUserPropsValues["PROP_TYPE"] == "LOCATION")
					{
						
						if ($arLocation = CSaleLocation::GetByID($arUserPropsValues["VALUE"], LANGUAGE_ID)) {
							
							/*$arUserPropsValues["VALUE_FORMATED"] = htmlspecialcharsEx($arLocation["COUNTRY_NAME"]);
							if (strlen($arLocation["COUNTRY_NAME"]) > 0
								&& strlen($arLocation["CITY_NAME"]) > 0)
							{
								$arUserPropsValues["VALUE_FORMATED"] .= " - ";
							}
							$arUserPropsValues["VALUE_FORMATED"] .= htmlspecialcharsEx($arLocation["CITY_NAME"]);
							*/

							$locationName = "";
							$locationName .= ((strlen($arLocation["COUNTRY_NAME"])<=0) ? "" : $arLocation["COUNTRY_NAME"]);

							if (strlen($arLocation["COUNTRY_NAME"])>0 && strlen($arLocation["REGION_NAME"])>0)
								$locationName .= " - ".$arLocation["REGION_NAME"];
							elseif (strlen($arLocation["REGION_NAME"])>0)
								$locationName .= $arLocation["REGION_NAME"];
							if (strlen($arLocation["COUNTRY_NAME"])>0 || strlen($arLocation["REGION_NAME"])>0)
								$locationName .= " - ".$arLocation["CITY_NAME"];
							elseif (strlen($arLocation["CITY_NAME"])>0)
								$locationName .= $arLocation["CITY_NAME"];

							$arUserPropsValues["VALUE_FORMATED"] = $locationName;
						}
					}
					else
						$arUserPropsValues["VALUE_FORMATED"] = $arUserPropsValues["VALUE"];
					$arUserProfiles["USER_PROPS_VALUES"][] = $arUserPropsValues;
				}
			
			$arResult["ORDER_PROP"]["USER_PROFILES"][$arUserProfiles["ID"]] = $arUserProfiles;
			
			
			
			
			
		}
		             
		
		
		if ($bFirst)
		{
			$arResult["USER_PROFILES_TO_FILL"] = "Y";
			if(isset($arResult["PROFILE_ID"]) && IntVal($arResult["PROFILE_ID"]) > 0 && $bFirst)
				$arResult["USER_PROFILES_TO_FILL_VALUE"] = "Y";
		}
		
		

		if(IntVal($arUserResult["PROFILE_ID"]) > 0 && empty($arResult["ORDER_PROP"]["USER_PROFILES"][$arUserResult["PROFILE_ID"]]))
			$arUserResult["PROFILE_ID"] = false;
		/* User Profiles End */

		/* Order Props Begin */
		$arDeleteFieldLocation = array();

		$arFilter = array("PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"], "ACTIVE" => "Y", "UTIL" => "N");
		if(!empty($arParams["PROP_".$arUserResult["PERSON_TYPE_ID"]]))
			$arFilter["!ID"] = $arParams["PROP_".$arUserResult["PERSON_TYPE_ID"]];
		$dbProperties = CSaleOrderProps::GetList(
				array(
						"GROUP_SORT" => "ASC",
						"PROPS_GROUP_ID" => "ASC",
						"USER_PROPS" => "ASC",
						"SORT" => "ASC",
						"NAME" => "ASC"
					),
				$arFilter,
				false,
				false,
				array("ID", "NAME", "TYPE", "REQUIED", "DEFAULT_VALUE", "IS_LOCATION", "PROPS_GROUP_ID", "SIZE1", "SIZE2", "DESCRIPTION", "IS_EMAIL", "IS_PROFILE_NAME", "IS_PAYER", "IS_LOCATION4TAX", "CODE", "GROUP_NAME", "GROUP_SORT", "SORT", "USER_PROPS", "IS_ZIP", "INPUT_FIELD_LOCATION")
			);

		while ($arProperties = $dbProperties->GetNext()) {
			if (isset($curVal))
				unset($curVal);
			$curLocation = false;
 
			if($arUserResult["PROFILE_CHANGE"] == "Y" && IntVal($arUserResult["PROFILE_ID"]) > 0 && !($arResult["HAVE_PREPAYMENT"] && $arUserResult["PROFILE_DEFAULT"] == "Y" && !empty($arResult["PREPAY_ORDER_PROPS"][$arProperties["CODE"]]))) {
			
				$dbUserPropsValues = CSaleOrderUserPropsValue::GetList(
						array("SORT" => "ASC"),
						array(
							"USER_PROPS_ID" => $arUserResult["PROFILE_ID"],
							"ORDER_PROPS_ID" => $arProperties["ID"],
							"USER_ID" => IntVal($USER->GetID()),

						),
						false,
						false,
						array("VALUE", "PROP_TYPE", "VARIANT_NAME", "SORT", "ORDER_PROPS_ID")
					);
				if ($arUserPropsValues = $dbUserPropsValues->Fetch()) {
					
					$valueTmp = "";
					if ($arUserPropsValues["PROP_TYPE"] == "MULTISELECT")
					{
						$arUserPropsValues["VALUE"] = explode(",", $arUserPropsValues["VALUE"]);
					}
					$curVal = $arUserPropsValues["VALUE"];
				}
			}
			//когда выбирает новый профиль
			elseif($arUserResult["PROFILE_CHANGE"] == "Y" && IntVal($arUserResult["PROFILE_ID"]) <= 0)
			{
				if (isset($curVal))
					unset($curVal);
			} elseif(isset($arUserResult["ORDER_PROP"][$arProperties["ID"]]))
				$curVal = $arUserResult["ORDER_PROP"][$arProperties["ID"]];
			elseif($arResult["HAVE_PREPAYMENT"] && !empty($arResult["PREPAY_ORDER_PROPS"][$arProperties["CODE"]]))
			{
				$curVal = $arResult["PREPAY_ORDER_PROPS"][$arProperties["CODE"]];
				if($arProperties["TYPE"] == "LOCATION")
					$curLocation = $curVal;
			}

			if (IntVal($_REQUEST["NEW_LOCATION_".$arProperties["ID"]]) > 0)
				$curVal = IntVal($_REQUEST["NEW_LOCATION_".$arProperties["ID"]]);

			$arProperties["FIELD_NAME"] = "ORDER_PROP_".$arProperties["ID"];
			if(strlen($arProperties["CODE"]) > 0)
				$arProperties["FIELD_ID"] = "ORDER_PROP_".$arProperties["CODE"];
			else
				$arProperties["FIELD_ID"] = "ORDER_PROP_".$arProperties["ID"];
				
			if (IntVal($arProperties["PROPS_GROUP_ID"]) != $propertyGroupID || $propertyUSER_PROPS != $arProperties["USER_PROPS"])
				$arProperties["SHOW_GROUP_NAME"] = "Y";
				
			$propertyGroupID = $arProperties["PROPS_GROUP_ID"];
			$propertyUSER_PROPS = $arProperties["USER_PROPS"];

			if ($arProperties["REQUIED"]=="Y" || $arProperties["IS_EMAIL"]=="Y" || $arProperties["IS_PROFILE_NAME"]=="Y" || $arProperties["IS_LOCATION"]=="Y" || $arProperties["IS_LOCATION4TAX"]=="Y" || $arProperties["IS_PAYER"]=="Y" || $arProperties["IS_ZIP"]=="Y")
				$arProperties["REQUIED_FORMATED"]="Y";

			if ($arProperties["TYPE"] == "CHECKBOX") {
				if ($curVal=="Y" || !isset($curVal) && $arProperties["DEFAULT_VALUE"]=="Y") {
				  $arProperties["CHECKED"] = "Y";
				  $arProperties["VALUE_FORMATED"] = GetMessage("SOA_Y");
				} else
				 $arProperties["VALUE_FORMATED"] = GetMessage("SOA_N");

				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 30);
			} elseif ($arProperties["TYPE"] == "TEXT") {
				if (strlen($curVal) <= 0) {
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
				else
					$arProperties["VALUE"] = $curVal;
				
				if ($arProperties["IS_ZIP"] == "Y" && $arUserResult["PROFILE_CHANGE"] == "N") {
					if ($arProperties["DEFAULT_VALUE"] == $curVal || strlen($curVal) <= 0) {
						$dbPropertiesLoc = CSaleOrderProps::GetList(
								array("ID" => "DESC"),
								array(
									"PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"],
									"ACTIVE" => "Y",
									"UTIL" => "N",
									"IS_LOCATION" => "Y"
									),
								false,
								false,
								array("ID")
							);
						$arPropertiesLoc = $dbPropertiesLoc->Fetch();
						if ($arPropertiesLoc["ID"] > 0) {
							$rsZipList = CSaleLocation::GetLocationZIP($arUserResult["ORDER_PROP"][$arPropertiesLoc["ID"]]);
							$arZip = $rsZipList->Fetch();

							if (strlen($arZip["ZIP"]) > 0)
								$arProperties["VALUE"] = $arZip["ZIP"];
						}
					}
				}

				if ($arProperties["IS_ZIP"]=="Y")
					$arUserResult["DELIVERY_LOCATION_ZIP"] = $arProperties["VALUE"];


				$arProperties["VALUE"] = htmlspecialcharsEx($arProperties["VALUE"]);
				$arProperties["VALUE_FORMATED"] = $arProperties["VALUE"];

			} elseif ($arProperties["TYPE"] == "SELECT") {
				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 1);
				$dbVariants = CSaleOrderPropsVariant::GetList(
						array("SORT" => "ASC", "NAME" => "ASC"),
						array("ORDER_PROPS_ID" => $arProperties["ID"]),
						false,
						false,
						array("*")

				);
				$flagDefault = "N";
				$nameProperty = "";
				while ($arVariants = $dbVariants->GetNext())
				{
					if ($flagDefault == "N" && $nameProperty == "")
					{
						$nameProperty = $arVariants["NAME"];
					}
					if (($arVariants["VALUE"] == $curVal) || ((!isset($curVal) || $curVal == "") && ($arVariants["VALUE"] == $arProperties["DEFAULT_VALUE"])))
					{
						$arVariants["SELECTED"] = "Y";
						$arProperties["VALUE_FORMATED"] = $arVariants["NAME"];
						$flagDefault = "Y";
					}
					$arProperties["VARIANTS"][] = $arVariants;
				}
				if ($flagDefault == "N")
				{
					$arProperties["VARIANTS"][0]["SELECTED"]= "Y";
					$arProperties["VARIANTS"][0]["VALUE_FORMATED"] = $nameProperty;
				}
			}
			elseif ($arProperties["TYPE"] == "MULTISELECT") {
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
				$i = 0;
				while ($arVariants = $dbVariants->GetNext())
				{
					if ((is_array($curVal) && in_array($arVariants["VALUE"], $curVal)) || (!isset($curVal) && in_array($arVariants["VALUE"], $arDefVal)))
					{
						$arVariants["SELECTED"] = "Y";
						if ($i > 0)
							$arProperties["VALUE_FORMATED"] .= ", ";
						$arProperties["VALUE_FORMATED"] .= $arVariants["NAME"];
						$i++;
					}
					$arProperties["VARIANTS"][] = $arVariants;
				}
			}
			elseif ($arProperties["TYPE"] == "TEXTAREA")
			{
				$arProperties["SIZE2"] = ((IntVal($arProperties["SIZE2"]) > 0) ? $arProperties["SIZE2"] : 4);
				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 40);
				//$arProperties["VALUE"] = htmlspecialcharsEx(isset($curVal) ? $curVal : $arProperties["DEFAULT_VALUE"]);
				//$arProperties["VALUE_FORMATED"] = $arProperties["VALUE"];
			}
			elseif ($arProperties["TYPE"] == "LOCATION") {				
				
				if ($_REQUEST["is_ajax_post"] == "Y" && $arProperties["IS_LOCATION"] == "Y" && IntVal($arProperties["INPUT_FIELD_LOCATION"]) > 0 && isset($_REQUEST["ORDER_PROP_".$arProperties["ID"]])) {
					$rsLocationsList = CSaleLocation::GetList(
						array(),
						array("ID" => $curVal),
						false,
						false,
						array("ID", "CITY_ID")
					);
					$arCity = $rsLocationsList->GetNext();

					if (IntVal($arCity["CITY_ID"]) <= 0)
						unset($arDeleteFieldLocation[$arProperties["ID"]]);
					else
						$arDeleteFieldLocation[$arProperties["ID"]] = $arProperties["INPUT_FIELD_LOCATION"];
				}
				elseif ($arProperties["IS_LOCATION"] == "Y" && IntVal($arProperties["INPUT_FIELD_LOCATION"]) > 0) {
				  $arDeleteFieldLocation[$arProperties["ID"]] = $arProperties["INPUT_FIELD_LOCATION"];
				}

				$arProperties["SIZE1"] = ((IntVal($arProperties["SIZE1"]) > 0) ? $arProperties["SIZE1"] : 1);
				$dbVariants = CSaleLocation::GetList(
						array("SORT" => "ASC", "COUNTRY_NAME_LANG" => "ASC", "CITY_NAME_LANG" => "ASC"),
						array("LID" => LANGUAGE_ID),
						false,
						false,
						array("ID", "COUNTRY_NAME", "CITY_NAME", "SORT", "COUNTRY_NAME_LANG", "CITY_NAME_LANG")
					);
				while ($arVariants = $dbVariants->GetNext()) {
					/*if (IntVal($arVariants["ID"]) == IntVal($curVal) && empty(!$arResult[])) {
						$arVariants["SELECTED"] = "Y";
						$arProperties["VALUE_FORMATED"] = $arVariants["COUNTRY_NAME"].((strlen($arVariants["CITY_NAME"]) > 0) ? " - " : "").$arVariants["CITY_NAME"];
						$arProperties["VALUE"] = $arVariants["ID"];

						if ($arProperties["IS_LOCATION"]=="Y")
							$arUserResult["DELIVERY_LOCATION"] = $arProperties["VALUE"];
						if ($arProperties["IS_LOCATION4TAX"]=="Y")
							$arUserResult["TAX_LOCATION"] = $arProperties["VALUE"];

					} */
					$arVariants["NAME"] = $arVariants["COUNTRY_NAME"].((strlen($arVariants["CITY_NAME"]) > 0) ? " - " : "").$arVariants["CITY_NAME"];
					$arProperties["VARIANTS"][] = $arVariants;
				}
				if(count($arProperties["VARIANTS"]) == 1) {
					$arProperties["VALUE"] = $arProperties["VARIANTS"][0]["ID"];
					if($arProperties["IS_LOCATION"]=="Y")
						$arUserResult["DELIVERY_LOCATION"] = $arProperties["VALUE"];
					if($arProperties["IS_LOCATION4TAX"]=="Y")
						$arUserResult["TAX_LOCATION"] = $arProperties["VALUE"];
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
					if ($arVariants["VALUE"] == $curVal || (!isset($curVal) && $arVariants["VALUE"] == $arProperties["DEFAULT_VALUE"]))
					{
						$arVariants["CHECKED"]="Y";
						$arProperties["VALUE_FORMATED"] = $arVariants["NAME"];
					}

					$arProperties["VARIANTS"][] = $arVariants;
				}
			}

			if($arProperties["USER_PROPS"]=="Y")
				$arResult["ORDER_PROP"]["USER_PROPS_Y"][$arProperties["ID"]] = $arProperties;
			else
				$arResult["ORDER_PROP"]["USER_PROPS_N"][$arProperties["ID"]] = $arProperties;
			$arResult["ORDER_PROP"]["PRINT"][$arProperties["ID"]] = Array("ID" => $arProperties["ID"], "NAME" => $arProperties["NAME"], "VALUE" => $arProperties["VALUE_FORMATED"], "SHOW_GROUP_NAME" => $arProperties["SHOW_GROUP_NAME"]);
		}
		

		foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepOrderProps", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, Array(&$arResult, &$arUserResult, &$arParams));
		/* Order Props End */


		//delete prop for text location
		if (count($arDeleteFieldLocation) > 0)
		{
			foreach ($arDeleteFieldLocation as $fieldId)
				unset($arResult["ORDER_PROP"]["USER_PROPS_Y"][$fieldId]);
		}


		if(empty($arUserResult["DELIVERY_LOCATION"])) {			 
		  $arUserResult["DELIVERY_LOCATION"] =  $arResult['IP_LOCATION']['ID'];
		}
		$arResult['TEST'] = $arUserResult["DELIVERY_LOCATION"] ;
		if (IntVal($arUserResult["DELIVERY_LOCATION"]) > 0)
		{
			$arFilter = array(
				"COMPABILITY" => array(
					"WEIGHT" => $arResult["ORDER_WEIGHT"],
					"PRICE" => $arResult["ORDER_PRICE"],
					"LOCATION_FROM" => COption::GetOptionString('sale', 'location', false, SITE_ID),
					"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
					"LOCATION_ZIP" => $arUserResult["DELIVERY_LOCATION_ZIP"],

				)
			);

			$bFirst = true;
			$arDeliveryServiceAll = Array();
			$bFound = false;
       
        
			$rsDeliveryServicesList = CSaleDeliveryHandler::GetList(array("SORT" => "ASC"),$arFilter);
			while ($arDeliveryService = $rsDeliveryServicesList->Fetch())
			{
				if (!is_array($arDeliveryService) || !is_array($arDeliveryService["PROFILES"])) continue;

				if(!empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") !== false)
				{
					foreach ($arDeliveryService["PROFILES"] as $profile_id => $arDeliveryProfile)
					{
						if($arDeliveryProfile["ACTIVE"] == "Y")
						{
							$delivery_id = $arDeliveryService["SID"];
							if($arUserResult["DELIVERY_ID"] == $delivery_id.":".$profile_id)
								$bFound = true;
						}
					}
				}

				$arDeliveryServiceAll[] = $arDeliveryService;
			}
			

			if(!$bFound && !empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") !== false)
				$arUserResult["DELIVERY_ID"] = "";


			//select delivery to paysystem
			$arUserResult["PAY_SYSTEM_ID"] = IntVal($arUserResult["PAY_SYSTEM_ID"]);
			$arUserResult["DELIVERY_ID"] = trim($arUserResult["DELIVERY_ID"]);
			$bShowDefaultSelected = True;
			$arD2P = array();
			$arP2D = array();

			$dbRes = CSaleDelivery::GetDelivery2PaySystem(array());
			
			while ($arRes = $dbRes->Fetch())
			{
				if(!in_array($arRes['PAYSYSTEM_ID'],array(1,2)))
				 continue;
				 
				$arD2P[$arRes["DELIVERY_ID"]][$arRes["PAYSYSTEM_ID"]] = $arRes["PAYSYSTEM_ID"];
				$arP2D[$arRes["PAYSYSTEM_ID"]][$arRes["DELIVERY_ID"]] = $arRes["DELIVERY_ID"];
				$bShowDefaultSelected = False;
			}
			
			$arD2P = array(1=>array(2=>2),
				       3 => Array (1 => 1 ,2 => 2),
				       6 => Array (1 => 1 ,2 => 2),
				       4 => Array (1=>1,2 =>2 ),
				       5 => Array (1=>1,2=>2),
				       7 => Array (1=>1,2=>2),
				       'cpcr' => Array (1=>1,2=>2),
				       'ems' => Array (1=>1,2=>2),				       
				       'russianpost' => Array (2=> 2));
			
			
			
			

			if ($arUserResult["PAY_SYSTEM_ID"] > 0 || strlen($arUserResult["DELIVERY_ID"]) > 0)
			{
				if (strlen($arUserResult["DELIVERY_ID"]) > 0 && $arParams["DELIVERY_TO_PAYSYSTEM"] == "d2p")
				{
					if (strpos($arUserResult["DELIVERY_ID"], ":"))
					{
						$tmp = explode(":", $arUserResult["DELIVERY_ID"]);
						$delivery = trim($tmp[0]);
					}
					else
						$delivery = intval($arUserResult["DELIVERY_ID"]);
				}
			}

			if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "d2p")
				$arP2D = array();

			if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
				$arD2P = array();

			if ($bShowDefaultSelected)
				$bFirst = True;
			else
				$bFirst = False;

			//select calc delivery
			foreach($arDeliveryServiceAll as $arDeliveryService)
			{
				 
				foreach ($arDeliveryService["PROFILES"] as $profile_id => $arDeliveryProfile)
				{
					//if (count($arP2D[$arUserResult["PAY_SYSTEM_ID"]]) <= 0 || in_array($arDelivery["ID"], $arP2D[$arUserResult["PAY_SYSTEM_ID"]]))

					if ($arDeliveryProfile["ACTIVE"] == "Y"
							&& (count($arP2D[$arUserResult["PAY_SYSTEM_ID"]]) <= 0
							|| in_array($arDeliveryService["SID"], $arP2D[$arUserResult["PAY_SYSTEM_ID"]])))
					{
						$delivery_id = $arDeliveryService["SID"];
						$arProfile = array(
							"SID" => $profile_id,
							"TITLE" => $arDeliveryProfile["TITLE"],
							"DESCRIPTION" => $arDeliveryProfile["DESCRIPTION"],
							"FIELD_NAME" => "DELIVERY_ID",
						);

						if((empty($arUserResult["DELIVERY_ID"]) && $bFirst) ||
							(strlen($arUserResult["DELIVERY_ID"]) > 0 && $arUserResult["DELIVERY_ID"] == $delivery_id.":".$profile_id)
						)
						{
							$arProfile["CHECKED"] = "Y";
							$arUserResult["DELIVERY_ID"] = $delivery_id.":".$profile_id;

							$arOrderTmpDel = array(
								"PRICE" => $arResult["ORDER_PRICE"],
								"WEIGHT" => $arResult["ORDER_WEIGHT"],
								"LOCATION_FROM" => COption::GetOptionInt('sale', 'location'),
								"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
								"LOCATION_ZIP" => $arUserResult["DELIVERY_LOCATION_ZIP"],
							);

							$arDeliveryPrice = CSaleDeliveryHandler::CalculateFull($delivery_id, $profile_id, $arOrderTmpDel, $arResult["BASE_LANG_CURRENCY"]);
							

							if ($arDeliveryPrice["RESULT"] == "ERROR")
								$arResult["ERROR"][] = $arDeliveryPrice["TEXT"];
							else
								$arResult["DELIVERY_PRICE"] = roundEx($arDeliveryPrice["VALUE"], SALE_VALUE_PRECISION);
						}

						if (empty($arResult["DELIVERY"][$delivery_id]))
						{
							$arResult["DELIVERY"][$delivery_id] = array(
								"SID" => $delivery_id,
								"TITLE" => $arDeliveryService["NAME"],
								"DESCRIPTION" => $arDeliveryService["DESCRIPTION"],
								"PROFILES" => array(),
							);
						}

						$arResult["DELIVERY"][$delivery_id]["LOGOTIP"] = $arDeliveryService["LOGOTIP"];
						$arResult["DELIVERY"][$delivery_id]["PROFILES"][$profile_id] = $arProfile;
						$bFirst = false;
					}
				}
			}

			/*Old Delivery*/
			$arStoreId = array();
			$arDeliveryAll = Array();
			$bFound = False;

			if ($bShowDefaultSelected)
				$bFirst = True;
			else
				$bFirst = False;

			$dbDelivery = CSaleDelivery::GetList(
						array("SORT"=>"ASC", "NAME"=>"ASC"),
						array(
								"LID" => SITE_ID,
								"+<=WEIGHT_FROM" => $arResult["ORDER_WEIGHT"],
								"+>=WEIGHT_TO" => $arResult["ORDER_WEIGHT"],
								"+<=ORDER_PRICE_FROM" => $arResult["ORDER_PRICE"],
								"+>=ORDER_PRICE_TO" => $arResult["ORDER_PRICE"],
								"ACTIVE" => "Y",
								"LOCATION" => $arUserResult["DELIVERY_LOCATION"],
							)
			);
			while ($arDelivery = $dbDelivery->Fetch())
			{
				$arStore = array();
				if (strlen($arDelivery["STORE"]) > 0)
				{
					$arStore = unserialize($arDelivery["STORE"]);
					foreach ($arStore as $val)
						$arStoreId[$val] = $val;
				}

				//foreach ($arDelivery as &$val)
				//	$val = htmlspecialcharsbx($val);

				$arDelivery["STORE"] = $arStore;

				if (isset($_POST["BUYER_STORE"]) && in_array($_POST["BUYER_STORE"], $arStore))
				{
					$arUserResult['DELIVERY_STORE'] = $arDelivery["ID"];
				}

				$arDeliveryDescription = CSaleDelivery::GetByID($arDelivery["ID"]);
				$arDelivery["DESCRIPTION"] = htmlspecialcharsbx($arDeliveryDescription["DESCRIPTION"]);

				$arDeliveryAll[] = $arDelivery;

				if(!empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") === false)
				{
					if(IntVal($arUserResult["DELIVERY_ID"]) == IntVal($arDelivery["ID"]))
						$bFound = true;
				}
			}

			$arStore = array();
			$dbList = CCatalogStore::GetList(
					array("ID" => "DESC"),
					array("ACTIVE" => "Y", "ID" => $arStoreId),
					false,
					false,
					array("ID", "TITLE", "ADDRESS", "DESCRIPTION", "IMAGE_ID", "PHONE", "SCHEDULE", "GPS_N", "GPS_S")
				);
			while ($arStoreTmp = $dbList->Fetch())
			{
				if ($arStoreTmp["IMAGE_ID"] > 0)
					$arStoreTmp["IMAGE_ID"] = CFile::GetFileArray($arStoreTmp["IMAGE_ID"]);

				$arStore[$arStoreTmp["ID"]] = $arStoreTmp;
			}

			$arResult["STORE_LIST"] = $arStore;

			if(!$bFound && !empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") === false)
				$arUserResult["DELIVERY_ID"] = "";

			foreach($arDeliveryAll as $arDelivery)
			{
				if (count($arP2D[$arUserResult["PAY_SYSTEM_ID"]]) <= 0 || in_array($arDelivery["ID"], $arP2D[$arUserResult["PAY_SYSTEM_ID"]]))
				{
					$arDelivery["FIELD_NAME"] = "DELIVERY_ID";
					if ((IntVal($arUserResult["DELIVERY_ID"]) == IntVal($arDelivery["ID"]))
						|| (strlen($arUserResult["DELIVERY_ID"]) <= 0 && $bFirst))
					{
						$arDelivery["CHECKED"] = "Y";
						$arUserResult["DELIVERY_ID"] = $arDelivery["ID"];
						$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
					}
					if (IntVal($arDelivery["PERIOD_FROM"]) > 0 || IntVal($arDelivery["PERIOD_TO"]) > 0)
					{
						$arDelivery["PERIOD_TEXT"] = GetMessage("SALE_DELIV_PERIOD");
						if (IntVal($arDelivery["PERIOD_FROM"]) > 0)
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_FROM")." ".IntVal($arDelivery["PERIOD_FROM"]);
						if (IntVal($arDelivery["PERIOD_TO"]) > 0)
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_TO")." ".IntVal($arDelivery["PERIOD_TO"]);
						if ($arDelivery["PERIOD_TYPE"] == "H")
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_HOUR")." ";
						elseif ($arDelivery["PERIOD_TYPE"]=="M")
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_MONTH")." ";
						else
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_DAY")." ";
					}

					if (intval($arDelivery["LOGOTIP"]) > 0)
						$arDelivery["LOGOTIP"] = CFile::GetFileArray($arDelivery["LOGOTIP"]);

					$arDelivery["PRICE_FORMATED"] = SaleFormatCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"]);
					$arResult["DELIVERY"][] = $arDelivery;
					$bFirst = false;
				}
			}

			if(DoubleVal($arResult["DELIVERY_PRICE"]) > 0)
				$arResult["DELIVERY_PRICE_FORMATED"] = SaleFormatCurrency($arResult["DELIVERY_PRICE"], $arResult["BASE_LANG_CURRENCY"]);

			foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepDelivery", true) as $arEvent)
				ExecuteModuleEventEx($arEvent, Array(&$arResult, &$arUserResult, &$arParams));
		}
		/* Delivery End */


		/* Pay Systems Begin */
		$arFilter = array(
							"ACTIVE" => "Y",
							"PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"],
							"PSA_HAVE_PAYMENT" => "Y"
						);
		if(!empty($arParams["DELIVERY2PAY_SYSTEM"]))
		{
			foreach($arParams["DELIVERY2PAY_SYSTEM"] as $val)
			{
				if(is_array($val[$arUserResult["DELIVERY_ID"]]))
				{
					foreach($val[$arUserResult["DELIVERY_ID"]] as $v)
						$arFilter["ID"][] = $v;
				}
				elseif(IntVal($val[$arUserResult["DELIVERY_ID"]]) > 0)
				{
					$arFilter["ID"][] = $val[$arUserResult["DELIVERY_ID"]];
				}
			}
		}

		if ($bShowDefaultSelected)
			$bFirst = True;
		else
			$bFirst = False;
            
	         global $USER;
		
                 $selectedLocation = CSaleLocation::GetByID($arUserResult["DELIVERY_LOCATION"]);
                if($selectedLocation['COUNTRY_ID'] !=24)  {
	          $arFilter['!ID'] = 1;
		 foreach($arD2P as $i =>$d2p) {
		  foreach($d2p as $j =>$p) {
		  // if($p==1)
		   // unset($arD2P[$i][$j]);
		  }
		 }
		 
		 
		  unset($arD2P['ems'][1]);
		
		  
		}
		
		 //if($USER->IsAdmin()) 
		  //AddMessage2Log(print_r($arD2P,true));
		
		$dbPaySystem = CSalePaySystem::GetList(
					array("SORT" => "ASC", "PSA_NAME" => "ASC"),
					$arFilter);
		while ($arPaySystem = $dbPaySystem->Fetch()) {
			if ($arPaySystem["PSA_LOGOTIP"] > 0)
		  	$arPaySystem["PSA_LOGOTIP"] = CFile::GetFileArray($arPaySystem["PSA_LOGOTIP"]);

		
			$arPaySystem["PSA_NAME"] = htmlspecialcharsEx($arPaySystem["PSA_NAME"]);
			$arResult["PAY_SYSTEM"][$arPaySystem["ID"]] = $arPaySystem;		
		
		}
		
		$arResult['D2P'] = $arD2P;
		
		if(empty($arResult["PAY_SYSTEM"]))
			$arResult["ERROR"][] = GetMessage("SOA_ERROR_PAY_SYSTEM");

		foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepPaySystem", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, Array(&$arResult, &$arUserResult, &$arParams));
		/* Pay Systems End */

		/* New discount */
		foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepDiscountBefore", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, Array(&$arResult, &$arUserResult, &$arParams));

		$arOrderForDiscount = array(
			'SITE_ID' => SITE_ID,
			'USER_ID' => $USER->GetID(),
			'ORDER_PRICE' => $arResult['ORDER_PRICE'],
			'ORDER_WEIGHT' => $arResult["ORDER_WEIGHT"],
			'PRICE_DELIVERY' => $arResult["DELIVERY_PRICE"],
			'BASKET_ITEMS' => $arResult["BASKET_ITEMS"],
			"PERSON_TYPE_ID" => $arUserResult['PERSON_TYPE_ID'],
			"PAY_SYSTEM_ID" => $arUserResult["PAY_SYSTEM_ID"],
			"DELIVERY_ID" => $arUserResult["DELIVERY_ID"],
		);

		$arDiscountOptions = array();

		$arDiscountErrors = array();

		CSaleDiscount::DoProcessOrder($arOrderForDiscount, $arDiscountOptions, $arDiscountErrors);

		$allSum = 0;
		$allVatSumm = 0;
		$allVatRate = 0;
		foreach ($arOrderForDiscount['BASKET_ITEMS'] as &$arOneItem)
		{
			$arOneItem["PRICE_FORMATED"] = SaleFormatCurrency($arOneItem["PRICE"], $arOneItem["CURRENCY"]);
			$arOneItem["DISCOUNT_PRICE_PERCENT"] = $arOneItem["DISCOUNT_PRICE"]*100 / ($arOneItem["DISCOUNT_PRICE"] + $arOneItem["PRICE"]);
			$arOneItem["DISCOUNT_PRICE_PERCENT_FORMATED"] = roundEx($arOneItem["DISCOUNT_PRICE_PERCENT"], SALE_VALUE_PRECISION)."%";
			$allSum += ($arOneItem["PRICE"] * $arOneItem["QUANTITY"]);
			$allVatSumm += 0;
			if (0 < $arOneItem["VAT_RATE"])
			{
				$arResult["bUsingVat"] = "Y";
				if ($arOneItem["VAT_RATE"] > $allVatRate)
					$allVatRate = $arOneItem["VAT_RATE"];
				$arOneItem["VAT_VALUE"] = (($arOneItem["PRICE"] / ($arOneItem["VAT_RATE"] +1)) * $arOneItem["VAT_RATE"]);
				$allVatSumm += roundEx($arOneItem["VAT_VALUE"] * $arOneItem["QUANTITY"], SALE_VALUE_PRECISION);
			}
		}
		if (isset($arOneItem))
			unset($arOneItem);

		$arResult["ORDER_PRICE"] = $allSum;
		$arResult["ORDER_PRICE_FORMATED"] = SaleFormatCurrency($arResult["ORDER_PRICE"], $arResult["BASE_LANG_CURRENCY"]);

		$arResult["VAT_RATE"] = $allVatRate;
		$arResult["VAT_SUM"] = $allVatSumm;
		$arResult["VAT_SUM_FORMATED"] = SaleFormatCurrency($arResult["VAT_SUM"], $arResult["BASE_LANG_CURRENCY"]);

		$arResult['DELIVERY_PRICE'] = $arOrderForDiscount['PRICE_DELIVERY'];
		$arResult['DELIVERY_PRICE_FORMATED'] = SaleFormatCurrency($arResult["DELIVERY_PRICE"], $arResult["BASE_LANG_CURRENCY"]);

		$arResult['BASKET_ITEMS'] = $arOrderForDiscount['BASKET_ITEMS'];

		/* New discount end */

		/* Tax Begin */
		$bHaveTaxExempts = False;
		if ($arUserResult["TAX_LOCATION"] > 0 || $arResult["bUsingVat"] == "Y")
		{
			if($arResult["bUsingVat"] != "Y")
			{
				$arUserGroups = $USER->GetUserGroupArray();

				$dbTaxExemptList = CSaleTax::GetExemptList(array("GROUP_ID" => $arUserGroups));
				while ($TaxExemptList = $dbTaxExemptList->Fetch())
				{
					if (!in_array(IntVal($TaxExemptList["TAX_ID"]), $arResult["TaxExempt"]))
					{
						$arResult["TaxExempt"][] = IntVal($TaxExemptList["TAX_ID"]);
					}
				}

				$dbTaxRate = CSaleTaxRate::GetList(
						array("APPLY_ORDER"=>"ASC"),
						array(
								"LID" => SITE_ID,
								"PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"],
								"ACTIVE" => "Y",
								"LOCATION" => $arUserResult["TAX_LOCATION"],
							)
					);
				while ($arTaxRate = $dbTaxRate->GetNext())
				{
					if (!in_array(IntVal($arTaxRate["TAX_ID"]), $arResult["TaxExempt"]))
					{
						$arResult["arTaxList"][] = $arTaxRate;
					}
				}

				$arTaxSums = array();
				if (count($arResult["arTaxList"]) > 0)
				{
					foreach($arResult["BASKET_ITEMS"] as $arBasket)
					{
						$TAX_PRICE_tmp = CSaleOrderTax::CountTaxes(
								$arBasket["PRICE"] * $arBasket["QUANTITY"],
								$arResult["arTaxList"],
								$arResult["BASE_LANG_CURRENCY"]
							);

						$countResultTax = count($arResult["arTaxList"]);
						for ($j = 0; $j < $countResultTax; $j++)
						{
							$arResult["arTaxList"][$j]["VALUE_MONEY"] += $arResult["arTaxList"][$j]["TAX_VAL"];

							//if ($arResult["arTaxList"][$j]=="Y")
							if ($arResult["arTaxList"][$j]["IS_IN_PRICE"]=="Y")
							{
								$arResult["arTaxList"][$j]["VALUE_FORMATED"] = " (".(($arResult["arTaxList"][$j]["IS_PERCENT"]=="Y")?"".DoubleVal($arResult["arTaxList"][$j]["VALUE"])."%, ":" ").GetMessage("SOA_VAT_INCLUDED").")";
							}
							elseif ($arResult["arTaxList"][$j]["IS_PERCENT"]=="Y")
							{
								$arResult["arTaxList"][$j]["VALUE_FORMATED"] = " (".DoubleVal($arResult["arTaxList"][$j]["VALUE"])."%)";
							}
						}
					}
					if(DoubleVal($arResult["DELIVERY_PRICE"])>0 && $arParams["COUNT_DELIVERY_TAX"] == "Y")
					{
						$TAX_PRICE_tmp = CSaleOrderTax::CountTaxes(
								$arResult["DELIVERY_PRICE"],
								$arResult["arTaxList"],
								$arResult["BASE_LANG_CURRENCY"]
							);

						$countResultTaxList = count($arResult["arTaxList"]);
						for ($j = 0; $j < $countResultTaxList; $j++)
						{
							$arResult["arTaxList"][$j]["VALUE_MONEY"] += $arResult["arTaxList"][$j]["TAX_VAL"];
						}
					}

					$countResultTax = count($arResult["arTaxList"]);
					for ($j = 0; $j < $countResultTax; $j++)
					{
						$arResult["arTaxList"][$j]["VALUE_MONEY"] = roundEx($arResult["arTaxList"][$j]["VALUE_MONEY"], SALE_VALUE_PRECISION);
						$arResult["arTaxList"][$j]["TAX_VAL"] = roundEx($arResult["arTaxList"][$j]["TAX_VAL"], SALE_VALUE_PRECISION);
					}

					$countResultTax = count($arResult["arTaxList"]);
					for ($i = 0; $i < $countResultTax ; $i++)
					{
						$arResult["arTaxList"][$i]["VALUE_MONEY_FORMATED"] = SaleFormatCurrency($arResult["arTaxList"][$i]["VALUE_MONEY"], $arResult["BASE_LANG_CURRENCY"]);

						if ($arResult["arTaxList"][$i]["IS_IN_PRICE"] != "Y")
						{
							$arResult["TAX_PRICE"] += roundEx($arResult["arTaxList"][$i]["VALUE_MONEY"], SALE_VALUE_PRECISION);
						}
					}
				}
			}
			else
			{
				if(DoubleVal($arResult["DELIVERY_PRICE"])>0 && $arParams["COUNT_DELIVERY_TAX"] == "Y")
					$arResult["VAT_SUM"] += roundEx($arResult["DELIVERY_PRICE"] * $arResult["VAT_RATE"] / (1 + $arResult["VAT_RATE"]), 2);

				$arResult["arTaxList"][] = Array(
							"NAME" => GetMessage("SOA_VAT"),
							"IS_PERCENT" => "Y",
							"VALUE" => $arResult["VAT_RATE"]*100,
							"VALUE_FORMATED" => "(".($arResult["VAT_RATE"]*100)."%, ".GetMessage("SOA_VAT_INCLUDED").")",
							"VALUE_MONEY" => $arResult["VAT_SUM"],
							"VALUE_MONEY_FORMATED" => SaleFormatCurrency($arResult["VAT_SUM"], $arResult["BASE_LANG_CURRENCY"]),
							"APPLY_ORDER" => 100,
							"IS_IN_PRICE" => "Y",
							"CODE" => "VAT"
				);
			}
		}

		$orderTotalSum = $arResult["ORDER_PRICE"] + $arResult["DELIVERY_PRICE"] + $arResult["TAX_PRICE"] - $arResult["DISCOUNT_PRICE"];

		

		$arResult["ORDER_TOTAL_PRICE_FORMATED"] = SaleFormatCurrency($orderTotalSum, $arResult["BASE_LANG_CURRENCY"]);
		/* Tax End */

		foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepProcess", true) as $arEvent)
			ExecuteModuleEventEx($arEvent, Array(&$arResult, &$arUserResult, &$arParams));

		$orderTotalSum = $arResult["ORDER_PRICE"] + $arResult["DELIVERY_PRICE"] + $arResult["TAX_PRICE"] - $arResult["DISCOUNT_PRICE"];

		if($arUserResult["CONFIRM_ORDER"] == "Y" && empty($arResult["ERROR"]))
		{
			
			
			

			if ($arUserResult["PAY_SYSTEM_ID"] <= 0)
				$arResult["ERROR"][] = GetMessage("STOF_ERROR_PAY_SYSTEM");
				
				
			if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "Y")
			{
				if(strlen($arUserResult["USER_EMAIL"]) > 0)
				{
					$NEW_LOGIN = $arUserResult["USER_EMAIL"];
					$NEW_EMAIL = $arUserResult["USER_EMAIL"];
					$NEW_NAME = "";
					$NEW_LAST_NAME = "";

					if(strlen($arUserResult["PAYER_NAME"]) > 0)
					{
						$arNames = explode(" ", $arUserResult["PAYER_NAME"]);
						$NEW_NAME = $arNames[1];
						$NEW_LAST_NAME = $arNames[0];
					}

					$pos = strpos($NEW_LOGIN, "@");
					if ($pos !== false)
						$NEW_LOGIN = substr($NEW_LOGIN, 0, $pos);

					if (strlen($NEW_LOGIN) > 47)
						$NEW_LOGIN = substr($NEW_LOGIN, 0, 47);

					if (strlen($NEW_LOGIN) < 3)
						$NEW_LOGIN .= "_";

					if (strlen($NEW_LOGIN) < 3)
						$NEW_LOGIN .= "_";

					$dbUserLogin = CUser::GetByLogin($NEW_LOGIN);
					if ($arUserLogin = $dbUserLogin->Fetch())
					{
						$newLoginTmp = $NEW_LOGIN;
						$uind = 0;
						do
						{
							$uind++;
							if ($uind == 10)
							{
								$NEW_LOGIN = $arUserResult["USER_EMAIL"];
								$newLoginTmp = $NEW_LOGIN;
							}
							elseif ($uind > 10)
							{
								$NEW_LOGIN = "buyer".time().GetRandomCode(2);
								$newLoginTmp = $NEW_LOGIN;
								break;
							}
							else
							{
								$newLoginTmp = $NEW_LOGIN.$uind;
							}
							$dbUserLogin = CUser::GetByLogin($newLoginTmp);
						}
						while ($arUserLogin = $dbUserLogin->Fetch());
						$NEW_LOGIN = $newLoginTmp;
					}

					$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
					if($def_group!="")
					{
						$GROUP_ID = explode(",", $def_group);
						$arPolicy = $USER->GetGroupPolicy($GROUP_ID);
					}
					else
					{
						$arPolicy = $USER->GetGroupPolicy(array());
					}

					$password_min_length = intval($arPolicy["PASSWORD_LENGTH"]);
					if($password_min_length <= 0)
						$password_min_length = 6;
					$password_chars = array(
						"abcdefghijklnmopqrstuvwxyz",
						"ABCDEFGHIJKLNMOPQRSTUVWXYZ",
						"0123456789",
					);
					if($arPolicy["PASSWORD_PUNCTUATION"] === "Y")
						$password_chars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
					$NEW_PASSWORD = $NEW_PASSWORD_CONFIRM = randString($password_min_length+2, $password_chars);

					$user = new CUser;
					$arAuthResult = $user->Add(Array(
						"LOGIN" => $NEW_LOGIN,
						"NAME" => $NEW_NAME,
						"LAST_NAME" => $NEW_LAST_NAME,
						"PASSWORD" => $NEW_PASSWORD,
						"CONFIRM_PASSWORD" => $NEW_PASSWORD_CONFIRM,
						"EMAIL" => $NEW_EMAIL,
						"GROUP_ID" => $GROUP_ID,
						"ACTIVE" => "Y",
						"LID" => SITE_ID,
						)
						);

					if (IntVal($arAuthResult) <= 0)
					{
						$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG").((strlen($user->LAST_ERROR) > 0) ? ": ".$user->LAST_ERROR : "" );
					}
					else
					{
						$USER->Authorize($arAuthResult);
						if ($USER->IsAuthorized())
						{
							if($arParams["SEND_NEW_USER_NOTIFY"] == "Y")
								CUser::SendUserInfo($USER->GetID(), SITE_ID, GetMessage("INFO_REQ"), true);
						}
						else
						{
							$arResult["ERROR"][] = GetMessage("STOF_ERROR_REG_CONFIRM");
						}
					}
				}
				else
					$arResult["ERROR"][] = GetMessage("STOF_ERROR_EMAIL");
			}
			
			
			

			if($USER->IsAuthorized() && empty($arResult["ERROR"]))
			{
				$arFields = array(
						"LID" => SITE_ID,
						"PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"],
						"PAYED" => "N",
						"CANCELED" => "N",
						"STATUS_ID" => "N",
						"PRICE" => $orderTotalSum,
						"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
						"USER_ID" => IntVal($USER->GetID()),
						"PAY_SYSTEM_ID" => $arUserResult["PAY_SYSTEM_ID"],
						"PRICE_DELIVERY" => $arResult["DELIVERY_PRICE"],
						"DELIVERY_ID" => (strlen($arUserResult["DELIVERY_ID"]) > 0 ? $arUserResult["DELIVERY_ID"] : false),
						"DISCOUNT_VALUE" => $arResult["DISCOUNT_PRICE"],
						"TAX_VALUE" => $arResult["bUsingVat"] == "Y" ? $arResult["VAT_SUM"] : $arResult["TAX_PRICE"],
						"USER_DESCRIPTION" => $arUserResult["ORDER_DESCRIPTION"]
				);

				if (IntVal($_POST["BUYER_STORE"]) > 0 && $arUserResult["DELIVERY_ID"] == $arUserResult["DELIVERY_STORE"])
					$arFields["STORE_ID"] = IntVal($_POST["BUYER_STORE"]);

				// add Guest ID
				if (CModule::IncludeModule("statistic"))
					$arFields["STAT_GID"] = CStatistic::GetEventParam();

				$affiliateID = CSaleAffiliate::GetAffiliate();
				if ($affiliateID > 0)
				{
					$dbAffiliat = CSaleAffiliate::GetList(array(), array("SITE_ID" => SITE_ID, "ID" => $affiliateID));
					$arAffiliates = $dbAffiliat->Fetch();
					if (count($arAffiliates) > 1)
						$arFields["AFFILIATE_ID"] = $affiliateID;
				}
				else
					$arFields["AFFILIATE_ID"] = false;

				$arResult["ORDER_ID"] = CSaleOrder::Add($arFields);
				$arResult["ORDER_ID"] = IntVal($arResult["ORDER_ID"]);
				if ($arResult["ORDER_ID"] <= 0)
				{
					if($ex = $APPLICATION->GetException())
						$arResult["ERROR"][] = $ex->GetString();
					else
						$arResult["ERROR"][] = GetMessage("SOA_ERROR_ORDER");
				}

				if (empty($arResult["ERROR"]))
				{
					CSaleBasket::OrderBasket($arResult["ORDER_ID"], CSaleBasket::GetBasketUserID(), SITE_ID, false);

					$dbBasketItems = CSaleBasket::GetList(
							array("NAME" => "ASC"),
							array(
									"FUSER_ID" => CSaleBasket::GetBasketUserID(),
									"LID" => SITE_ID,
									"ORDER_ID" => $arResult["ORDER_ID"]
								),
							false,
							false,
							array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "NAME")
						);
					$arResult["ORDER_PRICE"] = 0;

					$arOrderForDiscount = array(
						'SITE_ID' => SITE_ID,
						'USER_ID' => $USER->GetID(),
						'ORDER_PRICE' => $orderTotalSum,
						'ORDER_WEIGHT' => 0,
						'PRICE_DELIVERY' => $arResult["DELIVERY_PRICE"],
						'BASKET_ITEMS' => array(),
						"PERSON_TYPE_ID" => $arUserResult['PERSON_TYPE_ID'],
						"PAY_SYSTEM_ID" => $arUserResult["PAY_SYSTEM_ID"],
						"DELIVERY_ID" => $arUserResult["DELIVERY_ID"],
					);

					while ($arBasketItems = $dbBasketItems->GetNext())
					{
						//$arResult["ORDER_PRICE"] += roundEx($arBasketItems["PRICE"], SALE_VALUE_PRECISION) * DoubleVal($arBasketItems["QUANTITY"]);
						$arOrderForDiscount['BASKET_ITEMS'][] = $arBasketItems;
						$arOrderForDiscount['ORDER_WEIGHT'] += doubleval($arBasketItems['WEIGHT']);
					}

					$arDiscountOptions = array();

					$arDiscountErrors = array();

					CSaleDiscount::DoProcessOrder($arOrderForDiscount, $arDiscountOptions, $arDiscountErrors);

					$arResult["ORDER_PRICE"] = 0;

					foreach ($arOrderForDiscount['BASKET_ITEMS'] as &$arOneItem)
					{
						$arResult["ORDER_PRICE"] += doubleval($arOneItem['PRICE'])*doubleval($arOneItem['QUANTITY']);
						$arBasketInfo = array(
							'IGNORE_CALLBACK_FUNC' => 'Y',
							'PRICE' => $arOneItem['PRICE'],
						);
						if (array_key_exists('DISCOUNT_PRICE', $arOneItem))
						{
							$arBasketInfo['DISCOUNT_PRICE'] = $arOneItem['DISCOUNT_PRICE'];
						}
						CSaleBasket::Update(
							$arOneItem['ID'],
							$arBasketInfo
						);
					}
					if (isset($arOneItem))
						unset($arOneItem);
					$totalOrderPrice = $arResult["ORDER_PRICE"] + $arResult["DELIVERY_PRICE"] + $arResult["TAX_PRICE"] - $arResult["DISCOUNT_PRICE"];
					CSaleOrder::Update($arResult["ORDER_ID"], Array("PRICE" => $totalOrderPrice));
				}

				if (empty($arResult["ERROR"]))
				{
					$countResultTax = count($arResult["arTaxList"]);
					for ($i = 0; $i < $countResultTax; $i++)
					{
						$arFields = array(
								"ORDER_ID" => $arResult["ORDER_ID"],
								"TAX_NAME" => $arResult["arTaxList"][$i]["NAME"],
								"IS_PERCENT" => $arResult["arTaxList"][$i]["IS_PERCENT"],
								"VALUE" => ($arResult["arTaxList"][$i]["IS_PERCENT"]=="Y") ? $arResult["arTaxList"][$i]["VALUE"] : RoundEx(CCurrencyRates::ConvertCurrency($arResult["arTaxList"][$i]["VALUE"], $arResult["arTaxList"][$i]["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION),
								"VALUE_MONEY" => $arResult["arTaxList"][$i]["VALUE_MONEY"],
								"APPLY_ORDER" => $arResult["arTaxList"][$i]["APPLY_ORDER"],
								"IS_IN_PRICE" => $arResult["arTaxList"][$i]["IS_IN_PRICE"],
								"CODE" => $arResult["arTaxList"][$i]["CODE"]
							);
						CSaleOrderTax::Add($arFields);
					}

					$arTmpUserPropsVal = Array();
					if(IntVal($arUserResult["PROFILE_ID"]) > 0)
					{
						$dbUserPropsValues = CSaleOrderUserPropsValue::GetList(
								array("SORT" => "ASC"),
								array(
									"USER_PROPS_ID" => $arUserResult["PROFILE_ID"],
								),
								false,
								false,
								array("ORDER_PROPS_ID", "ID", "SORT", "VALUE")
							);
						while($arUserPropsValues = $dbUserPropsValues->Fetch())
						{
							$arTmpUserPropsVal[$arUserPropsValues["ORDER_PROPS_ID"]] = array("ID" => $arUserPropsValues["ID"], "VALUE" => $arUserPropsValues["ID"]);
						}
					}
					$dbOrderProperties = CSaleOrderProps::GetList(
							array("SORT" => "ASC"),
							array("PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"], "ACTIVE" => "Y", "UTIL" => "N"),
							false,
							false,
							array("ID", "TYPE", "NAME", "CODE", "USER_PROPS", "SORT")
						);
					while ($arOrderProperties = $dbOrderProperties->Fetch())
					{
						$curVal = $arUserResult["ORDER_PROP"][$arOrderProperties["ID"]];
						if ($arOrderProperties["TYPE"] == "MULTISELECT")
						{
							$curVal = "";

							$countUserResult = count($arUserResult["ORDER_PROP"][$arOrderProperties["ID"]]);
							for ($i = 0; $i < $countUserResult; $i++)
							{
								if ($i > 0)
									$curVal .= ",";
								$curVal .= $arUserResult["ORDER_PROP"][$arOrderProperties["ID"]][$i];
							}
						}

						if ($arOrderProperties["TYPE"] == "CHECKBOX" && strlen($curVal) <= 0 && $arOrderProperties["REQUIED"] != "Y")
						{
							$curVal = "N";
						}

						if (strlen($curVal) > 0)
						{
							$arFields = array(
									"ORDER_ID" => $arResult["ORDER_ID"],
									"ORDER_PROPS_ID" => $arOrderProperties["ID"],
									"NAME" => $arOrderProperties["NAME"],
									"CODE" => $arOrderProperties["CODE"],
									"VALUE" => $curVal
								);
							CSaleOrderPropsValue::Add($arFields);

							if ( $arOrderProperties["USER_PROPS"] == "Y" && IntVal($arUserResult["PROFILE_ID"])<=0 && IntVal($arUserResult["PROFILE_ID_new"])<=0)
							{
								if (strlen($arUserResult["PROFILE_NAME"]) <= 0)
									$arUserResult["PROFILE_NAME"] = GetMessage("SOA_PROFILE")." ".Date("Y-m-d");

								$arFields = array(
										"NAME" => $arUserResult["PROFILE_NAME"],
										"USER_ID" => IntVal($USER->GetID()),
										"PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"]
									);
								$arUserResult["PROFILE_ID_new"] = CSaleOrderUserProps::Add($arFields);
								$arUserResult["PROFILE_ID_new"] = IntVal($arUserResult["PROFILE_ID_new"]);
							}

							if ($arOrderProperties["USER_PROPS"] == "Y" && IntVal($arUserResult["PROFILE_ID_new"]) > 0)
							{
								$arFields = array(
										"USER_PROPS_ID" => $arUserResult["PROFILE_ID_new"],
										"ORDER_PROPS_ID" => $arOrderProperties["ID"],
										"NAME" => $arOrderProperties["NAME"],
										"VALUE" => $curVal
									);
								CSaleOrderUserPropsValue::Add($arFields);
							}

							if ($arOrderProperties["USER_PROPS"] == "Y" && IntVal($arUserResult["PROFILE_ID"]) > 0)
							{
								if(!empty($arTmpUserPropsVal[$arOrderProperties["ID"]]))
								{
									if($arTmpUserPropsVal[$arOrderProperties["ID"]]["VALUE"] != $curVal)
									{
										$arFields = Array(
												"NAME" => $arOrderProperties["NAME"],
												"VALUE" => $curVal
											);
										CSaleOrderUserPropsValue::Update($arTmpUserPropsVal[$arOrderProperties["ID"]]["ID"], $arFields);
									}
								}
								else
								{
									$arFields = array(
											"USER_PROPS_ID" => $arUserResult["PROFILE_ID"],
											"ORDER_PROPS_ID" => $arOrderProperties["ID"],
											"NAME" => $arOrderProperties["NAME"],
											"VALUE" => $curVal
										);
									CSaleOrderUserPropsValue::Add($arFields);
								}
							}
						}
					}
				}

				$withdrawSum = 0.0;
				if (empty($arResult["ERROR"]))
				{
					if ($arResult["PAY_FROM_ACCOUNT"] == "Y" && $arUserResult["PAY_CURRENT_ACCOUNT"] == "Y"
						&& (($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && DoubleVal($arResult["USER_ACCOUNT"]["CURRENT_BUDGET"]) >= DoubleVal($totalOrderPrice)) || $arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] != "Y"))
					{
						$withdrawSum = CSaleUserAccount::Withdraw(
								$USER->GetID(),
								$totalOrderPrice,
								$arResult["BASE_LANG_CURRENCY"],
								$arResult["ORDER_ID"]
							);

						if ($withdrawSum > 0)
						{
							$arFields = array(
									"SUM_PAID" => $withdrawSum,
									"USER_ID" => $USER->GetID()
								);

							CSaleOrder::Update($arResult["ORDER_ID"], $arFields);
							if ($withdrawSum == $totalOrderPrice)
								CSaleOrder::PayOrder($arResult["ORDER_ID"], "Y", False, False);
						}
					}
					if($arResult["HAVE_PREPAYMENT"])
					{
						if($psPreAction && $psPreAction->IsAction())
						{
							$psPreAction->orderId = $arResult["ORDER_ID"];
							$psPreAction->orderAmount = $totalOrderPrice;
							$psPreAction->payOrder();
						}
					}
				}

				// mail message
				if (empty($arResult["ERROR"]))
				{
					$strOrderList = "";
					$dbBasketItems = CSaleBasket::GetList(
							array("NAME" => "ASC"),
							array("ORDER_ID" => $arResult["ORDER_ID"]),
							false,
							false,
							array("ID", "NAME", "QUANTITY", "PRICE", "CURRENCY")
						);
					while ($arBasketItems = $dbBasketItems->Fetch())
					{
						$strOrderList .= $arBasketItems["NAME"]." - ".$arBasketItems["QUANTITY"]." ".GetMessage("SOA_SHT").": ".SaleFormatCurrency($arBasketItems["PRICE"], $arBasketItems["CURRENCY"]);
						$strOrderList .= "\n";
					}

					$arFields = Array(
						"ORDER_ID" => $arResult["ORDER_ID"],
						"ORDER_DATE" => Date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT", SITE_ID))),
						"ORDER_USER" => ( (strlen($arUserResult["PAYER_NAME"]) > 0) ? $arUserResult["PAYER_NAME"] : $USER->GetFormattedName(false)),
						"PRICE" => SaleFormatCurrency($totalOrderPrice, $arResult["BASE_LANG_CURRENCY"]),
						"BCC" => COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME),
						"EMAIL" => (strlen($arUserResult["USER_EMAIL"])>0 ? $arUserResult["USER_EMAIL"] : $USER->GetEmail()),
						"ORDER_LIST" => $strOrderList,
						"SALE_EMAIL" => COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME),
						"DELIVERY_PRICE" => $arResult["DELIVERY_PRICE"],
					);
					$eventName = "SALE_NEW_ORDER";

					$bSend = true;
					foreach(GetModuleEvents("sale", "OnOrderNewSendEmail", true) as $arEvent)
						if (ExecuteModuleEventEx($arEvent, Array($arResult["ORDER_ID"], &$eventName, &$arFields))===false)
							$bSend = false;

					if($bSend)
					{
						$event = new CEvent;
						$event->Send($eventName, SITE_ID, $arFields, "N");
					}
				}

				if(CModule::IncludeModule("statistic"))
				{
					$event1 = "eStore";
					$event2 = "order_confirm";
					$event3 = $arResult["ORDER_ID"];

					$e = $event1."/".$event2."/".$event3;

					if(!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"])))
					{
							CStatistic::Set_Event($event1, $event2, $event3);
							$_SESSION["ORDER_EVENTS"][] = $e;
					}
				}
				$arOrder = CSaleOrder::GetByID($arResult["ORDER_ID"]);

				foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepComplete", true) as $arEvent)
					ExecuteModuleEventEx($arEvent, Array($arResult["ORDER_ID"], $arOrder, $arParams));

				if (empty($arResult["ERROR"]))
					$arResult["REDIRECT_URL"] = $APPLICATION->GetCurPageParam("ORDER_ID=".$arResult["ORDER_ID"], Array("ORDER_ID"));
				else
					$arUserResult["CONFIRM_ORDER"] = "N";
			}
			else
			{
				$arUserResult["CONFIRM_ORDER"] = "N";
			}
		}
		else
		{
			$arUserResult["CONFIRM_ORDER"] = "N";
		}

		$arResult["USER_VALS"] = $arUserResult;
	}
	else
	{
		$arResult["USER_VALS"]["CONFIRM_ORDER"] = "Y";
		$arResult["ORDER_ID"] = IntVal($_REQUEST["ORDER_ID"]);
		$dbOrder = CSaleOrder::GetList(
				array("DATE_UPDATE" => "DESC"),
				array(
						"LID" => SITE_ID,
						"USER_ID" => IntVal($USER->GetID()),
						"ID" => $arResult["ORDER_ID"]
					)
			);
		if ($arOrder = $dbOrder->GetNext())
		{
			foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepFinal", true) as $arEvent)
				ExecuteModuleEventEx($arEvent, Array($arResult["ORDER_ID"], &$arOrder, &$arParams));

			if (IntVal($arOrder["PAY_SYSTEM_ID"]) > 0)
			{
				$dbPaySysAction = CSalePaySystemAction::GetList(
						array(),
						array(
								"PAY_SYSTEM_ID" => $arOrder["PAY_SYSTEM_ID"],
								"PERSON_TYPE_ID" => $arOrder["PERSON_TYPE_ID"]
							),
						false,
						false,
						array("NAME", "ACTION_FILE", "NEW_WINDOW", "PARAMS", "ENCODING", "LOGOTIP")
					);
				if ($arPaySysAction = $dbPaySysAction->Fetch())
				{
					$arPaySysAction["NAME"] = htmlspecialcharsEx($arPaySysAction["NAME"]);
					if (strlen($arPaySysAction["ACTION_FILE"]) > 0)
					{
						if ($arPaySysAction["NEW_WINDOW"] != "Y")
						{
							CSalePaySystemAction::InitParamArrays($arOrder, $arOrder["ID"], $arPaySysAction["PARAMS"]);

							$pathToAction = $_SERVER["DOCUMENT_ROOT"].$arPaySysAction["ACTION_FILE"];

							$pathToAction = str_replace("\\", "/", $pathToAction);
							while (substr($pathToAction, strlen($pathToAction) - 1, 1) == "/")
								$pathToAction = substr($pathToAction, 0, strlen($pathToAction) - 1);

							if (file_exists($pathToAction))
							{
								if (is_dir($pathToAction) && file_exists($pathToAction."/payment.php"))
									$pathToAction .= "/payment.php";

								$arPaySysAction["PATH_TO_ACTION"] = $pathToAction;
							}

							if(strlen($arPaySysAction["ENCODING"]) > 0)
							{
								define("BX_SALE_ENCODING", $arPaySysAction["ENCODING"]);
								AddEventHandler("main", "OnEndBufferContent", "ChangeEncoding");
								function ChangeEncoding($content)
								{
									global $APPLICATION;
									header("Content-Type: text/html; charset=".BX_SALE_ENCODING);
									$content = $APPLICATION->ConvertCharset($content, SITE_CHARSET, BX_SALE_ENCODING);
									$content = str_replace("charset=".SITE_CHARSET, "charset=".BX_SALE_ENCODING, $content);
								}
							}
						}
					}

					if ($arPaySysAction > 0)
						$arPaySysAction["LOGOTIP"] = CFile::GetFileArray($arPaySysAction["LOGOTIP"]);

					$arResult["PAY_SYSTEM"] = $arPaySysAction;
				}
			}
			$arResult["ORDER"] = $arOrder;
		}
	}
}

$this->IncludeComponentTemplate();

if($_REQUEST["AJAX_CALL"] == "Y" || $_REQUEST["is_ajax_post"] == "Y" || ($arResult['POST_PARAMS']['is_ajax_post']=='Y' && $arResult['POST_PARAMS']['confirmorder']=='Y'))
{
	die();
}
?>