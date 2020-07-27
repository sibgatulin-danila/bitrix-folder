<?
//$APPLICATION->SetAdditionalCSS('/bitrix/components/matrix/catalog/templates/.default/style.css');
$APPLICATION->SetTitle('Конструктор именных подвесок');
$APPLICATION->AddHeadString(' <meta property="og:title" content="Именная подвеска по вашему дизайну" />');
$APPLICATION->AddHeadString(' <meta property="og:description" content="Именная подвеска по вашему дизайну" />');
$APPLICATION->AddHeadString(' <meta name="description" content="Именная подвеска по вашему дизайну" />');


if (CModule::IncludeModule('iblock')) {

    $arParams['IBLOCK_ID'] = 1;
    $elementId = 7353;

    $arPrices = CIBlockPriceTools::GetCatalogPrices($arParams['IBLOCK_ID'], array(PRICE_TYPE));

    $npProduct = CIBlockElement::GetByID($elementId)->GetNext();

    $pageElement = CIBlockElement::GetByID(7487)->GetNext();

    if ($USER->IsAuthorized()) {
        $currentUser = CUser::GetByID($USER->GetID())->GetNext();
        $arWishList = explode(',', $currentUser['UF_WISHLIST']);
        if (in_array(7353, $arWishList)) {
            $arResult['IN_WISHLIST'] = 'Y';
        }
    }

    $arResult['TEXT'] = $pageElement['DETAIL_TEXT'];


    $rsMetalls = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_CODE' => 'metalls', 'ACTIVE' => 'Y'), false, false, array('IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_OFFER_ELEMENT_LINK'));
    while ($metall = $rsMetalls->Fetch()) {

        $metallOffers = CIBlockPriceTools::GetOffersArray(array('IBLOCK_ID' => $metall['IBLOCK_ID']),
            array($metall['ID']),
            array(),
            array(),
            array('PLAITING', 'COVERING'), 0, $arPrices, 1, array());


        $arrOffers = array();
        $minPrice = false;
        foreach ($metallOffers as $offer) {
            $arPrice = array_shift($offer['PRICES']);
            if ($arPrice['VALUE'] < $minPrice || $minPrice === false)
                $minPrice = $arPrice['VALUE'];
            $fullPrice = $arPrice['VALUE'];
            if ($arPrice['VALUE'] > $arPrice['DISCOUNT_VALUE']) {
                $minPrice = $arPrice['DISCOUNT_VALUE'];
            }

            $offerProp = $offer['DISPLAY_PROPERTIES'];
            $arrOffers[$offer['ID']] = array('ID' => $offer['ID'],
                'COVERING' => array('VALUE' => $offerProp['COVERING']['VALUE'], 'DISPLAY_VALUE' => strip_tags($offerProp['COVERING']['DISPLAY_VALUE'])),
                'PLAITING' => array('VALUE' => $offerProp['PLAITING']['VALUE'], 'DISPLAY_VALUE' => strip_tags($offerProp['PLAITING']['DISPLAY_VALUE']))
            );
        }

        $arResult['METTALS'][$metall['ID']] = array('ID' => $metall['ID'], 'CATALOG_LINK' => $metall['PROPERTY_OFFER_ELEMENT_LINK_VALUE'], 'NAME' => $metall['NAME'], 'OFFERS' => $arrOffers, 'MIN_PRICE_FORMAT' => $minPrice . '<span class="rubSymbol">a</span>', 'MIN_PRICE' => intval($minPrice), 'FULL_PRICE' => intval($fullPrice));
    }


    $rsFonts = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_CODE' => 'fonts', 'ACTIVE' => 'Y'), false, false, array('ID', 'NAME', 'PREVIEW_PICTURE', 'PROPERTY_FONT_FILE'));
    while ($font = $rsFonts->Fetch()) {
        $arResult['FONTS'][$font['ID']] = array('ID' => $font['ID'], 'NAME' => $font['NAME'], 'ICON' => CFile::GetPath($font['PREVIEW_PICTURE']), 'FONT_FILE' => CFile::GetPath('PROPERTY_FONT_FILE_VALUE'));
    }


    $rsPlaiting = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_CODE' => 'plaiting', 'ACTIVE' => 'Y'), false, false, array('ID', 'NAME', 'PREVIEW_PICTURE'));
    while ($plaiting = $rsPlaiting->Fetch()) {
        $arResult['PLAITING'][$plaiting['ID']] = array('ID' => $plaiting['ID'], 'NAME' => $plaiting['NAME'], 'ICON' => CFile::GetPath($plaiting['PREVIEW_PICTURE']));
    }

    $rsCovering = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_CODE' => 'covering', 'ACTIVE' => 'Y'), false, false, array('ID', 'NAME'));
    while ($covering = $rsCovering->Fetch()) {
        $arResult['COVERING'][$covering['ID']] = array('ID' => $covering['ID'], 'NAME' => $covering['NAME']);
    }


    $metallId = key($arResult['METTALS']);
    $arResult['SELECTION'] = array('TEXT' => empty($_GET['text']) ? "" : $_GET['text'],
        'METALL_ID' => empty($_GET['m']) ? $metallId : $_GET['m'],
        'FONT_ID' => empty($_GET['f']) ? key($arResult['FONTS']) : $_GET['f'],
        'PLAITING_ID' => empty($_GET['p']) ? key($arResult['PLAITING']) : $_GET['p'],
        'COVERING_ID' => empty($_GET['c']) ? "" : $_GET['c'],
        'TIME_PRODUCTION' => (empty($_GET['t']) || !in_array($_GET['t'], array(3, 10))) ? 10 : $_GET['t'],
    );


    $this->IncludeComponentTemplate();
}


?>