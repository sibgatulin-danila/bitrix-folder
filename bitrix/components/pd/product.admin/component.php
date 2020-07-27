<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//echo "<pre>"; print_r($arParams); echo "</pre>";

if (!empty($_REQUEST["edit"])) $componentPage = "detail";
else $componentPage = "news";

$arParams["EDIT_URL"] = $APPLICATION->GetCurPage("", array("edit", "delete", "CODE"));
$arParams["LIST_URL"] = $arParams["EDIT_URL"];

$this->IncludeComponentTemplate($componentPage);
?>