<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule('poisondrop');



switch($arParams['TYPE']) {
    case 'MAIN':
        $title = 'Вам может понравиться';
        $file = 'http://api.retailrocket.ru/api/1.0/Recomendation/ItemsToMain/55d48e356c7d3d3670758abc/';
        $xml = file_get_contents($file);
        $action = 'ItemsToMain';
        break;
    case 'PERSONAL':
        $title = 'Персональные рекомендации';
        if (isset($_COOKIE['rrpusid'])) {
            $file = 'http://api.retailrocket.ru/api/1.0/Recomendation/PersonalRecommendation/55d48e356c7d3d3670758abc/?rrUserId='.$_COOKIE['rrpusid'];
            $xml = file_get_contents($file);
        }
        $action = 'PersonalRecommendation';
        break;
    case 'SEARCH':
        $title = 'Вам может понравиться';
        $file = 'http://api.retailrocket.ru/api/Recomendation/SearchToItems/55d48e356c7d3d3670758abc/?keyword='.$_REQUEST['q'];
        $xml = file_get_contents($file);
        $action = 'SearchToItems';
        global $rrSearch;
        break;
    case '404':
        $title = 'Вам может понравиться';
        $file = 'http://api.retailrocket.ru/api/1.0/Recomendation/ItemsToMain/55d48e356c7d3d3670758abc/';
        $xml = file_get_contents($file);
        $action = 'ItemsToMain';
        break;
    case 'DETAIL':
        $title = 'Вам может понравиться';
        $file = 'http://api.retailrocket.ru/api/1.0/Recomendation/UpSellItemToItems/55d48e356c7d3d3670758abc/'.$arParams['ID'];
        $xml = file_get_contents($file);
        $action = 'UpSellItemToItems';
        break;
    case 'BASKET':
        $title = 'Вам может понравиться';
        global $basketIDs;
        //$file = 'http://api.retailrocket.ru/api/1.0/Recomendation/RelatedItems/55d48e356c7d3d3670758abc/'.$basketIDs;
        $file = 'http://api.retailrocket.ru/api/1.0/Recomendation/CrossSellItemToItems/55d48e356c7d3d3670758abc/'.$basketIDs;
        $xml = file_get_contents($file);
        $action = 'CrossSellItemToItems';
        break;
}

if ($_REQUEST['TEST_RR'] == 'Y') {
    echo '<a href="'.$file.'">Запрос '.$arParams['TYPE'].'</a><br>';
}

$xml = str_replace(array('[', ']'), '', $xml);
$array = explode(',', $xml);
CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');

$catalogPrices = CIBlockPriceTools::GetCatalogPrices(1, array('Розничная'));

$arFilter = array('IBLOCK_ID' => 1, 'ID' => $array, '!ID' => array(4181,7353,11087,11727,24948), 'ACTIVE' => 'Y', 'PROPERTY_IN_ARCHIVE' => false, '>CATALOG_QUANTITY' => 0);

if ($arParams['TYPE'] == 'BASKET') {
    $arFilter['!SECTION_CODE'] = 'koltsa';
}

$dbProducts = CIBlockElement::GetList(
    array(),
    $arFilter,
    false,
    array("nPageSize"=>count($array)),
    array('IBLOCK_ID', 'ID', 'NAME', 'DETAIL_PAGE_URL', 'PROPERTY_DESIGNER', 'PROPERTY_PHOTOGALLERY')
);

while ($obProducts = $dbProducts->Fetch()) {
    $result['ITEMS'][$obProducts['ID']] = $obProducts;
    $designers[$obProducts['PROPERTY_DESIGNER_VALUE']] = $obProducts['PROPERTY_DESIGNER_VALUE'];
    $sections[$obProducts['IBLOCK_SECTION_ID']] = $obProducts['IBLOCK_SECTION_ID'];
}

$dbDesigners = CIBlockELement::GetList(
    array(),
    array('IBLOCK_ID' => 3, 'ID' => $designers),
    false,
    false,
    array('IBLOCK_ID', 'ID', 'NAME')
);

while ($obDesigners = $dbDesigners->Fetch()) {
    $designers[$obDesigners['ID']] = $obDesigners['NAME'];
}

$dbSections = CIBlockSection::GetList(
    array(),
    array('IBLOCK_ID' => 1, 'ID' => $sections),
    false,
    array('IBLOCK_ID', 'ID', 'CODE'),
    false
);

while ($obSections = $dbSections->Fetch()) {
    $sections[$obSections['ID']] = $obSections['CODE'];
}

$dbPic = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array('IBLOCK_ID' => 7, 'SECTION_ID' => $pic),
    false,
    false,
    array('IBLOCK_ID', 'ID')
);


foreach ($result['ITEMS'] as &$item) {
    if ($arParams['TYPE'] == 'SEARCH' && $arParams['SHOW_TEMPLATE'] == 'N') {
        $rrSearch[] = $item['ID'];
    }
    $designer = $item['PROPERTY_DESIGNER_VALUE'];
    $section = $sections[$item['IBLOCK_SECTION_ID']];
    $item = array(
        'ID' => $item['ID'],
        'NAME' => $item['NAME'],
        'DESIGNER' => $designers[$designer],
        'CODE' => $item['CODE'],
        'PRICE' => $price,
        'DISCOUNT' => $discount,
        'URL' => '/catalog/'.$section.'/'.$item['CODE'].'/',
        'SECTION' => $item['IBLOCK_SECTION_ID'],
        'PIC' => CPoisonUtils::getImg($item['ID'])
    );
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/rr.txt', $item['ID'].' - '.$item['NAME']."\n", FILE_APPEND);

    $offersExist = CCatalogSKU::IsExistOffers($item['ID'], 1);
    if ($offersExist) {

        $item['IS_OFFER'] = 'Y';
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
        $item['TRUE_CAN_BUY'] = 'N';
        foreach ($arTrue as $true) {
            if ($true['CAN_BUY']) {
                $item['TRUE_CAN_BUY'] = 'Y';
                break;
            }
        }

        $item['PRICE'] = floatval(str_replace(' ', '', $arTrue[0]['MIN_PRICE']['PRINT_VALUE_VAT']));
        $item['PRICE_FORMATED'] =  CurrencyFormat($item['PRICE'], 'RUB');
        $item['DISCOUNT_PRICE'] = floatval(str_replace(' ', '', $arTrue[0]['MIN_PRICE']['PRINT_DISCOUNT_VALUE_VAT']));
        $item['DISCOUNT_PRICE_FORMATED'] = CurrencyFormat($item['DISCOUNT_PRICE'], 'RUB');
        if (floatval($item['PRICE']) > floatval($item["DISCOUNT_PRICE"])) {
            $item['DISCOUNT'] = 'Y';
        }
        unset($arTrue);
    } else {
        $dbPrice = CPrice::GetBasePrice($item['ID']);
        $item['QUANTITY'] = $dbPrice['PRODUCT_QUANTITY'];
        $item['TRACE'] = $dbPrice['TRACE'];
        $dbDiscounts = CCatalogDiscount::GetDiscountByPrice(
            $dbPrice["ID"],
            $USER->GetUserGroupArray(),
            "N",
            SITE_ID
        );
        $discountPrice = CCatalogProduct::CountPriceWithDiscount(
            $dbPrice["PRICE"],
            $dbPrice["CURRENCY"],
            $dbDiscounts
        );
        $item['PRICE'] = $dbPrice['PRICE'];
        $item['PRICE_FORMATED'] = CurrencyFormat($dbPrice['PRICE'],'RUB');
        $item["DISCOUNT_PRICE"] = $discountPrice;
        $item["DISCOUNT_PRICE_FORMATED"] = CurrencyFormat($discountPrice,'RUB');
        $item['IS_OFFER'] = 'N';
        if (floatval($item['PRICE']) > floatval($item["DISCOUNT_PRICE"])) {
            $item['DISCOUNT'] = 'Y';
        }
        if ($arParams['TYPE'] == 'BASKET') {
            $item['JSON'] = str_replace('\'','"',CUtil::PhpToJSObject(array(
                'ID' => $item['ID'],
                'PRICE' => $item['PRICE'],
                'QUANTITY' => 1,
                'MAX_QUANTITY' => $item['QUANTITY']
            )));
        }
    }

}

foreach ($array as $ar) {
    if ($result['ITEMS'][$ar]) {
        $arResult['ITEMS'][] = $result['ITEMS'][$ar];
    }
}

$arResult['TITLE'] = $title;

if (count($arResult['ITEMS']) == 0) {
    global $showRRsearch;
    $showRRsearch = 'N';
}

$arResult['ACTION'] = "'".$action."'";


if ($arParams['SHOW_TEMPLATE'] != 'N')
$this->IncludeComponentTemplate($componentPage);