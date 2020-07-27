<?




if (!isset($arParams['IS_AJAX']) && !empty($arResult['TAGS'])) {
    CModule::IncludeModule('poisondrop');
    CModule::IncludeModule('iblock');

    $filterTag = array('LOGIC' => 'OR');
    foreach (explode(',', $arResult['TAGS']) as $tag) {
        $filterTag[] = "%" . $tag . "%";
    }
}


if (count($arResult['OFFERS']) > 1) {
    $rsPage = CIBlockElement::GetList(array(), array('IBLOCK_CODE' => 'pages', 'ID' => 2057), false, array('nTopCount' => 1));
    if ($page = $rsPage->GetNext()) {
        $arResult['DETAIL_SIZE'] = $page['DETAIL_TEXT'];
    }
}


if (!empty($arResult['PROPERTIES']['DESIGNER']['VALUE'])) {
    $rsDesigner = CIBlockElement::GetList(array(), array('IBLOCK_CODE' => 'designers', 'ID' => $arResult['PROPERTIES']['DESIGNER']['VALUE']), false, false, array('ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'PROPERTY_COUNTRY.PROPERTY_COUNTRY_SKL', 'PROPERTY_GLOBAL_PRICING', 'PROPERTY_HREF'));


    while ($designer = $rsDesigner->GetNext()) {

        if ($designer['PROPERTY_GLOBAL_PRICING_VALUE'] == 'да') {
            $arResult['GLOBAL_PRICING'] = array('HREF' => $designer['PROPERTY_HREF_VALUE'], 'COUNTRY' => $designer['PROPERTY_COUNTRY_PROPERTY_COUNTRY_SKL_VALUE']);
        }

        if (!empty($designer['PREVIEW_PICTURE'])) {
            $designer['PREVIEW_PICTURE'] = CFile::GetPath($designer['PREVIEW_PICTURE']);
        }
        $arResult['DESIGNER'] = $designer;
    }
}
/*Теги*/
if ($USER->IsAdmin()) {
    CModule::IncludeModule('search');
    $arFilterTags = array(
        'SITE_ID' => array('s1'),
        'MODULE_ID' => 'iblock'
    );
    $arOrderTags = array('NAME' => 'ASC');
    $rsTags = CSearchTags::GetList(
        $arSelectTags,
        $arFilterTags,
        $arOrderTags
    );
    $arTagsResult = array();
    while ($arTag = $rsTags->Fetch()) {
        /*$arTagsResult[] = array(
          'label' => $arTag['NAME'].' <b>'.$arTag['CNT'].'</b>',
          'value' => $arTag['NAME']
        );*/
        $arTagsResult[] = $arTag['NAME'];
    }
    $available = json_encode($arTagsResult, JSON_HEX_APOS | JSON_HEX_QUOT);
    $elementTags = explode(',', $arResult['TAGS']);
    foreach ($elementTags as $key => $val) {
        $elementTags[$key] = trim($val);
    }
    $arResult['TAGS_LIST'] = array(
        'LIST' => $arTagsResult,
        'JSON' => $available,
        'THIS' => $elementTags
    );


}

$catalogPrices = CIBlockPriceTools::GetCatalogPrices(1, array('Розничная'));
//foreach ($arResult['ITEMS'] as $key => $item) {
$offersExist = CCatalogSKU::IsExistOffers($arResult['ID'], 1);
if ($offersExist) {

    $arResult['IS_OFFER'] = 'Y';
    $arTrue = CIBlockPriceTools::GetOffersArray(
        1,
        $arResult['ID'],
        array(),
        array(),
        array(),
        0,
        $catalogPrices,
        true
    );
    $arResult['TRUE_CAN_BUY'] = 'N';
    foreach ($arTrue as $item) {
        if ($item['CAN_BUY']) {
            $arResult['TRUE_CAN_BUY'] = 'Y';
            break;
        }
    }
    $arResult['TRUE_PRICES'] = array(
        "PRICE" => $arTrue[0]['MIN_PRICE']['PRINT_VALUE_VAT'],
        "PRICE_DISCOUNT" => $arTrue[0]['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT']
    );
    unset($arTrue);
} else {
    $arResult['IS_OFFER'] = 'N';
}

if ($arResult['TRUE_PRICES']['PRICE']) {
    $arResult['PRICES']['Розничная']['VALUE'] = intval($arResult['TRUE_PRICES']['PRICE']);
    $arResult['PRICES']['Розничная']['PRINT_VALUE_VAT'] = $arResult['TRUE_PRICES']['PRICE'];
}
if ($arResult['TRUE_PRICES']['PRICE_DISCOUNT']) {
    $arResult['PRICES']['Розничная']['DISCOUNT_VALUE'] = intval($arResult['TRUE_PRICES']['PRICE_DISCOUNT']);
    $arResult['PRICES']['Розничная']['PRINT_DISCOUNT_VALUE_VAT'] = $arResult['TRUE_PRICES']['PRICE_DISCOUNT'];
}

//}

?>
<script>
    window.tags = '<?=$available?>';
</script>