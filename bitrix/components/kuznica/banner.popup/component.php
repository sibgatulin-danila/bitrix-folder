<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!CModule::IncludeModule("alexkova.popupad"))
    return false;

$moduleId = 'alexkova.popupad';
$jqueryOn = COption::GetOptionString($moduleId, "POPUP_JQUERY", 0);

if($arParams["ONLY_RETURN"] != "Y") {
    if ($jqueryOn) {
        CJSCore::Init(array("jquery"));
    }
    $FancyBoxOn = COption::GetOptionString($moduleId, "POPUP_FANCYBOX", 1);
    $arParams['DELAY'] = COption::GetOptionString($moduleId, "POPUP_TIME_DELAY_SHOW", 0);
    $fancyboxStyleOverLay = COption::GetOptionString($moduleId, "POPUP_FANCYBOX_OVERLAY", 0);
    $componentDir = $this->__path;
    if ($fancyboxStyleOverLay)
        $APPLICATION->SetAdditionalCSS($componentDir . '/fancybox/source/jquery.fancybox.overlay.css');
    if ($FancyBoxOn) {
        $APPLICATION->AddHeadScript($componentDir . "/fancybox/lib/jquery.mousewheel-3.0.6.pack.js");
        $APPLICATION->AddHeadScript($componentDir . "/fancybox/source/jquery.fancybox.js");
        $APPLICATION->AddHeadScript($componentDir . "/fancybox/source/helpers/jquery.fancybox-buttons.js");
        $APPLICATION->AddHeadScript($componentDir . "/fancybox/source/helpers/jquery.fancybox-thumbs.js");
        $APPLICATION->AddHeadScript($componentDir . "/fancybox/source/helpers/jquery.fancybox-media.js");
        if (!$fancyboxStyleOverLay)
            $APPLICATION->SetAdditionalCSS($componentDir . '/fancybox/source/jquery.fancybox.css');
        $APPLICATION->SetAdditionalCSS($componentDir . '/fancybox/source/helpers/jquery.fancybox-buttons.css');
    }
    if ($arParams["ONLY_INIT"] == "Y")
        return;
}
$arResult = Array();
$limit=0;

$curPage = $APPLICATION->GetCurDir();
$arBanners = CKuznicaPopupad::GetBanners($limit, $arParams["BACKURL"]);
$cnt=0;
$incIDs = array();
$incDayIDs = array();
$uniqueInfo = array();
if(!is_array($arBanners))
    return false;

//get time protect
$arResult['OPTIONS']['PROTECT_TIME'] = COption::GetOptionString($moduleId,'POPUP_PROTECTION_TIMER',3600);
foreach($arBanners as $banner)
{
    $arInfo = unserialize($banner['~INFO']);
    $arResult['OPTIONS']['DELAY'] = intval($arParams['DELAY']);
    $banner['TIME_NOT_SHOW'] = intval($arInfo['SHOW_PER_TIME']);
    $overflowLink = "";
    if(strlen($banner["URL"])>0)
    {
        if($banner["SHOW_TYPE"] <> "html")
            $overflowLink = $banner["URL"];
    }

    if($banner["IMAGE_ID"]>0)
        $bannerContent = CKuznicaPopupad::getContent($banner["IMAGE_ID"],$banner["FLASH_TRANSPARENT"],$overflowLink,true,$banner);
    else
    {
        if($banner["CODE_TYPE"] == "html")
            $bannerContent = $banner["CODE"];
        else
            $bannerContent = nl2br(strip_tags($banner["CODE"]));
    }
    if($banner["INFO"]["ICON_FILE"] && $banner["INFO"]["SHOW_TYPE"] == "icon")
        $banner["INFO"]["ICON_FILE"] = \CFile::GetFileArray($banner["INFO"]["ICON_FILE"]);
    $arResult["BANNERS"][$banner["ID"]] = $banner;
    $arResult["BANNERS"][$banner["ID"]]["HTML"] = $bannerContent;
    $incIDs[] = $banner["ID"];
}
/** @var $this CBitrixComponent */
$arResult["IMAGES_PATH"] = $this->getPath() . "/images";
if(count($incIDs)>0)
    CKuznicaPopupad::incShow($incIDs);
if($arParams["ONLY_RETURN"] == "Y")
    return $arResult;

$this->includeComponentTemplate();
