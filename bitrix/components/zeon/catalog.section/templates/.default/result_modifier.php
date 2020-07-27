<?

if(!empty($arResult['ID'])) {
    $rsBanner = CIBlockElement::GetList(array('ID'=>'DESC'),array('IBLOCK_CODE'=>'banners','PROPERTY_SECTION'=>$arResult['ID'],'ACTIVE'=>'Y'),false,array('nTopCount'=>1),array('NAME','DETAIL_PICTURE','PROPERTY_HREF','PROPERTY_LOC','PROPERTY_SECTION','PROPERTY_ROW_NUM'));
    if($banner = $rsBanner->Fetch()) {
        $arResult['BANNER']= array('NAME'=>$banner['NAME'],
            'PIC'=>CFile::GetPath($banner['DETAIL_PICTURE']),
            'URL'=>$banner['PROPERTY_HREF_VALUE'],
            'LOC'=>$banner['PROPERTY_LOC_ENUM_ID'] == 61?'left':'right',
            'ROW_NUM'=>$banner['PROPERTY_ROW_NUM_VALUE']
        );



    }
}

if($arParams['WISHLIST']=='Y') {

    $currentUser  = CUser::GetByID($USER->GetID())->GetNext();
    $arWishList  =  explode(',',$currentUser['UF_WISHLIST']);
    $arNameplateData   =  unserialize($currentUser['~UF_NAMEPLATE_DATA']);

    if(in_array(7353,$arWishList)) {
        $arResult['NAMEPLATE'] = $arNameplateData[7353];
        foreach($arResult["ITEMS"] as $cell=>&$arElement) {
            if($arElement['ID']==7353) {
                $tmpParams = array();
                foreach($arResult['NAMEPLATE'] as $key => $param) {
                    $tmpParams[] = $key.'='.urlencode($param);
                }
                $arElement["DETAIL_PAGE_URL"] = '/namenecklaceconstructor/?'.implode('&',$tmpParams);
                $arElement['IS_NAMEPLATE'] = 'Y';
                $arElement["PREVIEW_PICTURE"]["SRC"] = '/rest/np/img.php?'.implode('&',$tmpParams);
            }
        }
    }
}
$catalogPrices = CIBlockPriceTools::GetCatalogPrices(1, array('Розничная'));
foreach ($arResult['ITEMS'] as $key => $item) {
    $offersExist = CCatalogSKU::IsExistOffers($item['ID'], 1);
    if ($offersExist) {

        $arResult['ITEMS'][$key]['IS_OFFER'] = 'Y';
        $arTrue = CIBlockPriceTools::GetOffersArray(
            1,
            $item['ID'],
            array(),
            array(),
            array(),
            0,
            $catalogPrices,
            true
        );
        $arResult['ITEMS'][$key]['TRUE_CAN_BUY'] = 'N';
        foreach ($arTrue as $item) {
            if ($item['CAN_BUY']) {
                $arResult['ITEMS'][$key]['TRUE_CAN_BUY'] = 'Y';
                break;
            }
        }
        $arResult['ITEMS'][$key]['TRUE_PRICES'] = array(
            "PRICE" => $arTrue[0]['MIN_PRICE']['PRINT_VALUE_VAT'],
            "PRICE_DISCOUNT" => $arTrue[0]['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT']
        );
        unset($arTrue);
    } else {
        $arResult['ITEMS'][$key]['IS_OFFER'] = 'N';
    }
}

if (count($arResult['ITEMS']) == 0) {
    global $showRRsearch;
    $showRRsearch = 'N';
}

?>
