
<?
$APPLICATION->SetAdditionalCSS('/css/jquery.sb.css');
$APPLICATION->AddHeadScript('/js/lib/jquery.searchabledropdown-1.0.8.min.js');
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$APPLICATION->IncludeComponent("pd:product.add.form", "", $arParams, $component);
?>