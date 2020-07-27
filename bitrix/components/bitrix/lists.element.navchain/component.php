<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule('lists'))
{
	ShowError(GetMessage("CC_BLEN_MODULE_NOT_INSTALLED"));
	return;
}

$lists_perm = CListPermissions::CheckAccess(
	$USER,
	$arParams["~IBLOCK_TYPE_ID"],
	$arParams["~IBLOCK_ID"] > 0? intval($arParams["~IBLOCK_ID"]): false,
	$arParams["~SOCNET_GROUP_ID"]
);
if($lists_perm < 0)
{
	switch($lists_perm)
	{
	case CListPermissions::WRONG_IBLOCK_TYPE:
		ShowError(GetMessage("CC_BLEN_WRONG_IBLOCK_TYPE"));
		return;
	case CListPermissions::WRONG_IBLOCK:
		ShowError(GetMessage("CC_BLEN_WRONG_IBLOCK"));
		return;
	default:
		ShowError(GetMessage("CC_BLEN_UNKNOWN_ERROR"));
		return;
	}
}
elseif($lists_perm < CListPermissions::CAN_READ)
{
	ShowError(GetMessage("CC_BLEN_ACCESS_DENIED"));
	return;
}

$arIBlock = CIBlock::GetArrayByID(intval($arParams["~IBLOCK_ID"]));
$arResult["~IBLOCK"] = $arIBlock;
$arResult["IBLOCK"] = htmlspecialcharsex($arIBlock);
$arResult["IBLOCK_ID"] = intval($arIBlock["ID"]);

if(isset($arParams["SOCNET_GROUP_ID"]) && $arParams["SOCNET_GROUP_ID"] > 0)
	$arParams["SOCNET_GROUP_ID"] = intval($arParams["SOCNET_GROUP_ID"]);
else
	$arParams["SOCNET_GROUP_ID"] = "";

if($arParams["ADD_NAVCHAIN_GROUP"] === "Y" && $arParams["SOCNET_GROUP_ID"])
{
	$arResult["~LISTS_URL"] = str_replace(
		array("#group_id#"),
		array($arParams["SOCNET_GROUP_ID"]),
		$arParams["~LISTS_URL"]
	);
	$arResult["LISTS_URL"] = htmlspecialchars($arResult["~LISTS_URL"]);

	$arGroup = CSocNetGroup::GetByID($arParams["SOCNET_GROUP_ID"]);
	if(!empty($arGroup))
	{
		$APPLICATION->AddChainItem($arGroup["NAME"], str_replace(
			array("#group_id#"),
			array($arParams["SOCNET_GROUP_ID"]),
			$arParams["~PATH_TO_GROUP"]
		));

		$feature = "group_lists";
		$arEntityActiveFeatures = CSocNetFeatures::GetActiveFeaturesNames(SONET_ENTITY_GROUP, $arGroup["ID"]);
		$strFeatureTitle = ((array_key_exists($feature, $arEntityActiveFeatures) && StrLen($arEntityActiveFeatures[$feature]) > 0) ? $arEntityActiveFeatures[$feature] : GetMessage("CC_BLEN_BREADCRUMB_LISTS"));

		$APPLICATION->AddChainItem($strFeatureTitle, $arResult["~LISTS_URL"]);
	}
}

if($arParams["ADD_NAVCHAIN_LIST"] !== "N")
{
	$arResult["~LIST_URL"] = CHTTP::urlAddParams(str_replace(
		array("#list_id#", "#section_id#", "#group_id#"),
		array($arResult["IBLOCK_ID"], 0, $arParams["SOCNET_GROUP_ID"]),
		$arParams["~LIST_URL"]
	), array("list_section_id" => ""));
	$arResult["LIST_URL"] = htmlspecialchars($arResult["~LIST_URL"]);

	$APPLICATION->AddChainItem($arResult["IBLOCK"]["NAME"], $arResult["~LIST_URL"]);
}

if($arParams["ADD_NAVCHAIN_SECTIONS"] !== "N")
{
	$arResult["~LIST_SECTION_URL"] = str_replace(
		array("#list_id#", "#section_id#", "#group_id#"),
		array($arResult["IBLOCK_ID"], intval($arParams["~SECTION_ID"]), $arParams["SOCNET_GROUP_ID"]),
		$arParams["~LIST_URL"]
	);
	$arResult["LIST_SECTION_URL"] = htmlspecialchars($arResult["~LIST_SECTION_URL"]);

	$rsElement = CIBlockElement::GetList(
		array(),
		array("IBLOCK_ID" => $arResult["IBLOCK_ID"], "=ID" => $arParams["ELEMENT_ID"]),
		false,
		false,
		array("ID", "NAME", "IBLOCK_SECTION_ID")
	);
	$arResult["ELEMENT"] = $rsElement->GetNext();
	if(is_array($arResult["ELEMENT"]))
		$arResult["ELEMENT_ID"] = intval($arResult["ELEMENT"]["ID"]);
	else
		$arResult["ELEMENT_ID"] = 0;

	$arResult["SECTION_PATH"] = array();
	if($arResult["ELEMENT_ID"] && $arResult["ELEMENT"]["IBLOCK_SECTION_ID"])
	{
		$rsPath = CIBlockSection::GetNavChain($arResult["IBLOCK_ID"], $arResult["ELEMENT"]["IBLOCK_SECTION_ID"]);
		while($arPath = $rsPath->Fetch())
		{
			$arResult["SECTION_PATH"][] = array(
				"NAME" => htmlspecialcharsex($arPath["NAME"]),
				"URL" => str_replace(
						array("#list_id#", "#section_id#", "#group_id#"),
						array($arIBlock["ID"], intval($arPath["ID"]), $arParams["SOCNET_GROUP_ID"]),
						$arParams["LIST_URL"]
				),
			);
		}
	}

	foreach($arResult["SECTION_PATH"] as $arPath)
	{
		$APPLICATION->AddChainItem($arPath["NAME"], $arPath["URL"]);
	}
}

if($arParams["ADD_NAVCHAIN_ELEMENT"] !== "N")
{
	if(!array_key_exists("ELEMENT_ID", $arResult))
	{
		$rsElement = CIBlockElement::GetList(
			array(),
			array("IBLOCK_ID" => $arResult["IBLOCK_ID"], "=ID" => $arParams["ELEMENT_ID"]),
			false,
			false,
			array("ID", "NAME", "IBLOCK_SECTION_ID")
		);
		$arResult["ELEMENT"] = $rsElement->GetNext();
		if(is_array($arResult["ELEMENT"]))
			$arResult["ELEMENT_ID"] = intval($arResult["ELEMENT"]["ID"]);
		else
			$arResult["ELEMENT_ID"] = 0;
	}

	$arResult["~LIST_ELEMENT_URL"] = str_replace(
		array("#list_id#", "#section_id#", "#element_id#", "#group_id#"),
		array($arResult["IBLOCK_ID"], intval($arResult["ELEMENT"]["IBLOCK_SECTION_ID"]), $arResult["ELEMENT_ID"], $arParams["SOCNET_GROUP_ID"]),
		$arParams["~LIST_ELEMENT_URL"]
	);
	$arResult["LIST_ELEMENT_URL"] = htmlspecialchars($arResult["~LIST_ELEMENT_URL"]);

	$APPLICATION->AddChainItem($arResult["ELEMENT"]["NAME"], $arResult["~LIST_ELEMENT_URL"]);
}
?>