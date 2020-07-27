#!/usr/local/php/bin/php -q
<?php
//$_SERVER["DOCUMENT_ROOT"] = "/home/d/diskzakaz/public_html";

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define("BX_CAT_CRON", true);
define('NO_AGENT_CHECK', true);
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

$profile_id = $argv[1];

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

set_time_limit (0);

if (CModule::IncludeModule("vsfr.merchant") && CModule::IncludeModule("catalog"))
{
	$profile_id = intval($profile_id);
	if ($profile_id<=0) die();

	$ar_profile = CCatalogVSFRExport::GetByID($profile_id);
	if (!$ar_profile) die();
file_put_contents($_SERVER["DOCUMENT_ROOT"]."/123454.txt", print_r($ar_profile, true));
	$strFile = VSFRCATALOG_PATH2EXPORTS.$ar_profile["FILE_NAME"]."_run.php";
	if (!file_exists($_SERVER["DOCUMENT_ROOT"].$strFile))
	{
		$strFile = VSFRCATALOG_PATH2EXPORTS_DEF.$ar_profile["FILE_NAME"]."_run.php";
		if (!file_exists($_SERVER["DOCUMENT_ROOT"].$strFile))
		{
			die();
		}
	}

	$arSetupVars = array();
	$intSetupVarsCount = 0;
	if ('Y' != $ar_profile["DEFAULT_PROFILE"])
	{
		parse_str($ar_profile["SETUP_VARS"], $arSetupVars);
		if (!empty($arSetupVars) && is_array($arSetupVars))
		{
			$intSetupVarsCount = extract($arSetupVars, EXTR_SKIP);
		}
	}

	CCatalogDiscountSave::Disable();
	include($_SERVER["DOCUMENT_ROOT"].$strFile);
	CCatalogDiscountSave::Enable();

	CCatalogExport::Update($profile_id, array(
		"=LAST_USE" => $DB->GetNowFunction()
		)
	);
}
?>