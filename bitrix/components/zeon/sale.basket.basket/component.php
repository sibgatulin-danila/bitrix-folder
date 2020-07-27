<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


if (!CModule::IncludeModule("sale"))
{
    ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
    return;
}

CModule::IncludeModule('poisondrop');

$arParams["PATH_TO_ORDER"] = Trim($arParams["PATH_TO_ORDER"]);
if (strlen($arParams["PATH_TO_ORDER"]) <= 0)
    $arParams["PATH_TO_ORDER"] = "order.php";

if($arParams["SET_TITLE"] == "Y")
    $APPLICATION->SetTitle(GetMessage("SBB_TITLE"));

if (!isset($arParams["COLUMNS_LIST"]) || !is_array($arParams["COLUMNS_LIST"]) || count($arParams["COLUMNS_LIST"]) <= 0)
    $arParams["COLUMNS_LIST"] = array("NAME", "PRICE", "TYPE", "QUANTITY", "DELETE", "DELAY", "WEIGHT");

$arParams["HIDE_COUPON"] = (($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N");
if (!CModule::IncludeModule("catalog"))
    $arParams["HIDE_COUPON"] = "Y";

if (!isset($arParams['QUANTITY_FLOAT']))
    $arParams['QUANTITY_FLOAT'] = 'N';
$arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] = (($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N");


//$arParams['PRICE_VAT_INCLUDE'] = $arParams['PRICE_VAT_INCLUDE'] == 'N' ? 'N' : 'Y';
$arParams['PRICE_VAT_SHOW_VALUE'] = $arParams['PRICE_VAT_SHOW_VALUE'] == 'N' ? 'N' : 'Y';
$arParams["USE_PREPAYMENT"] = $arParams["USE_PREPAYMENT"] == 'Y' ? 'Y' : 'N';

$arParams["WEIGHT_UNIT"] = htmlspecialcharsbx(COption::GetOptionString('sale', 'weight_unit', "", SITE_ID));
$arParams["WEIGHT_KOEF"] = htmlspecialcharsbx(COption::GetOptionString('sale', 'weight_koef', 1, SITE_ID));

$arResult["WARNING_MESSAGE"] = Array();

$GLOBALS['CATALOG_ONETIME_COUPONS_BASKET'] = null;
$GLOBALS['CATALOG_ONETIME_COUPONS_ORDER']=null;
$basketRestart = false;
if(strlen($_REQUEST['BasketRefresh'])>0) {

    $COUPON = Trim($_REQUEST["COUPON"]);
    if (strlen($COUPON) > 0) {
        CCatalogDiscountCoupon::SetCoupon($COUPON);

    }
    else
        CCatalogDiscountCoupon::ClearCoupon();

}


if ($_GET["AJAX"]=='Y' || (!empty($arParams['AJAX_ID']) && $arParams['AJAX_ID']==$_GET["AJAX_ID"]) && is_numeric($_GET['ID']) && in_array($_GET['O'],array('DELETE','CHANGE_QUANT','ADD_TO_BASKET','ADD_TO_SUBSCRIBE'))) {
    $arParams['AJAX']='Y';
    $arParams['AJAX_ID'] = $_GET["AJAX_ID"];
    $operation = $_GET['O'];
    if($_GET['O']=='ADD_TO_BASKET') {
        $QUANTITY = intval($_GET['VAL']);
        if($QUANTITY <= 1)
            $QUANTITY = 1;
        $product_properties=array();
        if(!empty($_GET['PROPS']) && is_array($_GET['PROPS'])) {
            $product_properties = CIBlockPriceTools::GetOfferProperties(
                $_GET['ID'],
                1,
                $_GET['PROPS']);


        }
        $rewriteFields = array();

        if(!empty($_GET['BASKET_PROPS']) && is_array($_GET['BASKET_PROPS'])) {

            $product_properties = $_GET['BASKET_PROPS'];

            $rsCheckForDelete = CSaleBasket::GetList(array(),
                array("FUSER_ID" => CSaleBasket::GetBasketUserID(),
                    "PRODUCT_ID"=>$_GET['ID'],
                    "LID" => SITE_ID,
                    "ORDER_ID" => "NULL",
                    'CAN_BUY'=>'Y',
                    "DELAY"=>"N",
                    'SUBSCRIBE'=>'N'),
                false,false,array('ID'));
            if($b = $rsCheckForDelete->Fetch()) {
                CSaleBasket::Delete($b['ID']);
            }


            //AddMessage2Log(print_r($product_properties,true));
            $addPrice = 0;
            if($_GET['ID']==7357) {
                if(!empty($product_properties['COVERING_ID'])) {
                    $addPrice+=1500;
                }
            }



            if($product_properties['TIME_PRODUCTION']['VALUE']==3) {
                $addPrice+=1500;
            }
            $discountPrice = 0;
            $arPrice = CPrice::GetBasePrice($_GET['ID']);

            if (trim(strtolower($_GET['BASKET_PROPS']['TEXT']['DISCOUNT_FLAG'])) == 'y') {
                $discountPrice = intval($arPrice['PRICE']+$addPrice)/100*20;
            }
            $truePrice = $arPrice['PRICE']+$addPrice - $discountPrice;

            $arBasketAdd = array('PRODUCT_ID'=>$_GET['ID'],
                'QUANTITY'=>$QUANTITY,
                'PRICE'=>$truePrice,
                'DISCOUNT_PRICE'=>$discountPrice,
                'CURRENCY'=>'RUB',
                "LID" => LANG,
                'NAME'=>'Именная подвеска',
                'PROPS'=>$product_properties
            );

            CSaleBasket::Add($arBasketAdd);
        } else {

            $rsCheckBasketProduct = CSaleBasket::GetList(array(),
                array("FUSER_ID" => CSaleBasket::GetBasketUserID(),
                    "PRODUCT_ID"=>$_GET['ID'],
                    "LID" => SITE_ID,
                    "ORDER_ID" => "NULL",
                    'CAN_BUY'=>'Y',
                    "DELAY"=>"N",
                    'SUBSCRIBE'=>'N'),
                false,false,array('ID','QUANTITY'));
            if($checkBasketProduct = $rsCheckBasketProduct->Fetch()) {

                if($check_product = CCatalogProduct::GetByID($_GET['ID'])) {
                    if($checkBasketProduct['QUANTITY']+$QUANTITY>$check_product['QUANTITY']) {
                        $dontAdd = true;
                    }
                }
            }
            if(!$dontAdd)
                Add2BasketByProductID($_GET['ID'], $QUANTITY,  $rewriteFields,$product_properties);
        }
    } else if($_GET['O']=='ADD_TO_SUBSCRIBE') {
        Add2BasketByProductID($_GET['ID'], 1, array('SUBSCRIBE'=>'Y'), array());
        unset($_SESSION['WISHLIST_CNT']);
    } else {

        $dbBasketItems = CSaleBasket::GetList(
            array("PRICE" => "DESC"),
            array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                'ID'=>$_GET['ID'],
                "ORDER_ID" => "NULL"),
            false,
            false,
            array("ID", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "SUBSCRIBE"));

        if($arBasketItems = $dbBasketItems->Fetch()) {
            $arBasketItems['QUANTITY'] = IntVal($arBasketItems['QUANTITY']);
            if ($operation=='DELETE') {
                if ($arBasketItems["SUBSCRIBE"] == "Y" && is_array($_SESSION["NOTIFY_PRODUCT"][$USER->GetID()])) {
                    unset($_SESSION["NOTIFY_PRODUCT"][$USER->GetID()][$arBasketItems["PRODUCT_ID"]]);
                }
                CSaleBasket::Delete($arBasketItems["ID"]);
            }  elseif ($arBasketItems["DELAY"] == "N" && $arBasketItems["CAN_BUY"] == "Y" && $operation=='CHANGE_QUANT') {

                UnSet($arFields);
                $arFields = array();
                $arFields["QUANTITY"] = is_numeric($_GET['VAL']) && $_GET['VAL']>0?$_GET['VAL']:1;

                if (count($arFields) > 0 &&  ($arBasketItems["QUANTITY"] != $arFields["QUANTITY"]))
                    CSaleBasket::Update($arBasketItems["ID"], $arFields);
            }
        }
        unset($_SESSION["SALE_BASKET_NUM_PRODUCTS"][SITE_ID]);
    }
}

CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), SITE_ID);
CModule::IncludeModule('poisondrop');
$bShowReady = False;
$bShowDelay = False;
$bShowSubscribe = False;
$bShowNotAvail = False;
$allSum = 0;
$allWeight = 0;
$allCurrency = CSaleLang::GetLangCurrency(SITE_ID);
$allVATSum = 0;

$arResult["ITEMS"]["AnDelCanBuy"] = Array();
$arResult["ITEMS"]["DelDelCanBuy"] = Array();
$arResult["ITEMS"]["nAnCanBuy"] = Array();
$arResult["ITEMS"]["ProdSubscribe"] = Array();
$arResult['TOTAL_QUANTITY'] = 0;
$DISCOUNT_PRICE_ALL = 0;

if($_SESSION["SUBSCRIBE_PRODUCT"])
    unset($_SESSION["SUBSCRIBE_PRODUCT"]);

$arBasketItems = $arProductIds = array();
$dbBasketItems = CSaleBasket::GetList(
    array(
        "DATE_INSERT" => "DESC",
        //"ID" => "ASC"
    ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL"
    ),
    false,
    false,
    array("ID", "NAME", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "DETAIL_PAGE_URL", "NOTES", "CURRENCY", "VAT_RATE", "CATALOG_XML_ID", "PRODUCT_XML_ID", "SUBSCRIBE", "DISCOUNT_PRICE", "PRODUCT_PROVIDER_CLASS"));
while ($arItems = $dbBasketItems->GetNext()) {

    $arItems['QUANTITY'] = $arParams['QUANTITY_FLOAT'] == 'Y' ? number_format(DoubleVal($arItems['QUANTITY']), 2, '.', '') : IntVal($arItems['QUANTITY']);

    $arItems["PROPS"] = Array();

    $dbProp = CSaleBasket::GetPropsList(Array("SORT" => "ASC", "ID" => "ASC"), Array("BASKET_ID" => $arItems["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")));
    while($arProp = $dbProp -> GetNext())
        $arItems["PROPS"][] = $arProp;


    $arItems["PRICE_VAT_VALUE"] = (($arItems["PRICE"] / ($arItems["VAT_RATE"] +1)) * $arItems["VAT_RATE"]);
    $arItems["PRICE_FORMATED"] = SaleFormatCurrency($arItems["PRICE"], $arItems["CURRENCY"]);
    $arItems["WEIGHT"] = DoubleVal($arItems["WEIGHT"]);
    $arItems["WEIGHT_FORMATED"] = roundEx(DoubleVal($arItems["WEIGHT"]/$arParams["WEIGHT_KOEF"]), SALE_VALUE_PRECISION)." ".$arParams["WEIGHT_UNIT"];

    if ($arItems["DELAY"] == "N" && $arItems["CAN_BUY"] == "Y")
    {
        $allSum += ($arItems["PRICE"] * $arItems["QUANTITY"]);
        $allWeight += ($arItems["WEIGHT"] * $arItems["QUANTITY"]);
        $allVATSum += roundEx($arItems["PRICE_VAT_VALUE"] * $arItems["QUANTITY"], SALE_VALUE_PRECISION);

    }

    if ($arItems["DELAY"] == "N" && $arItems["CAN_BUY"] == "Y")
    {
        $arItems["OLD_PRICE"] = $arItems["PRICE"] + $arItems["DISCOUNT_PRICE"];

        $bShowReady = True;
        if(DoubleVal($arItems["DISCOUNT_PRICE"]) > 0)
        {
            $arItems["DISCOUNT_PRICE_PERCENT"] = $arItems["DISCOUNT_PRICE"]*100 / ($arItems["DISCOUNT_PRICE"] + $arItems["PRICE"]);
            $arItems["DISCOUNT_PRICE_PERCENT_FORMATED"] = roundEx($arItems["DISCOUNT_PRICE_PERCENT"], SALE_VALUE_PRECISION)."%";
            $arItems["OLD_PRICE"] = $arItems["PRICE"] + $arItems["DISCOUNT_PRICE"];
            $DISCOUNT_PRICE_ALL += $arItems["DISCOUNT_PRICE"] * $arItems["QUANTITY"];
        }

        $arProductIds[] = $arItems['PRODUCT_ID'];


        $arCatalogProduct = CCatalogProduct::GetByID($arItems['PRODUCT_ID']);
        if(in_array($arItems['PRODUCT_ID'],array(7356,7357,7500)))  {
            //$arItems['DETAIL_PAGE_URL'] =  CPoisonUtils::getNameplateUrl($arItems["PROPS"]);
        }


        $arResult['TOTAL_QUANTITY']+=intval($arItems['QUANTITY']);
        $arItems['JS_DATA']= str_replace('\'','"',CUtil::PhpToJSObject(array('ID'=>$arItems['ID'],'QUANTITY'=>$arItems['QUANTITY'],'MAX_QUANTITY'=>$arCatalogProduct['QUANTITY'],'IMG'=>CPoisonUtils::getFirstPic($arItems['PRODUCT_ID']))));
        if($arItems['PRODUCT_ID']==4181) {
            $arItems['SORT'] = 100;
        } else
            $arItems['SORT']= 0;
        $arResult["ITEMS"]["AnDelCanBuy"][] = $arItems;
    }
    elseif ($arItems["DELAY"] == "Y" && $arItems["CAN_BUY"] == "Y")
    {
        $bShowDelay = True;
        $arResult["ITEMS"]["DelDelCanBuy"][] = $arItems;
    }
    elseif ($arItems["CAN_BUY"] == "N" && $arItems["SUBSCRIBE"] == "Y")
    {
        $bShowSubscribe = True;

        $_SESSION["SUBSCRIBE_PRODUCT"][$arItems['PRODUCT_ID']] =  $arItems['PRODUCT_ID'];
        $arResult["ITEMS"]["ProdSubscribe"][] = $arItems;

    }
    else {
        $bShowNotAvail = True;
        $arResult["ITEMS"]["nAnCanBuy"][] = $arItems;
    }

    $arBasketItems[] = $arItems;
}

if(!empty($arProductIds)) {
    CModule::IncludeModule('iblock');

// $arNameplateImgs = CPoisonUtils::getNameplateImg("NULL",2);

    $rsProducts = CIBlockElement::GetList(
        array(),
        array('IBLOCK_TYPE'=>'xmlcatalog','ID'=>$arProductIds),
        false,
        false,
        array('IBLOCK_ID','ID','NAME','DETAIL_PICTURE','PROPERTY_THUMB','PROPERTY_CML2_LINK','PROPERTY_DESIGNER.NAME'));
    while($product = $rsProducts->GetNext()) {
        if(!empty($product['PROPERTY_THUMB_VALUE']))  {
            $thumb = CFile::GetPath($product['PROPERTY_THUMB_VALUE']);
        }
        else {
            $thumb = CPoisonUtils::getFirstThumb($product['ID']);
        }



        if($product['IBLOCK_ID']==11) {
            $rsDesigner = CIBlockElement::GetList(array(),array('IBLOCK_TYPE'=>'xmlcatalog','ID'=>$product['PROPERTY_CML2_LINK_VALUE']),false,array('nTopCount'=>1),array('PROPERTY_DESIGNER.NAME'));
            if($d=$rsDesigner->GetNext()) {
                $product['PROPERTY_DESIGNER_NAME'] = $d['PROPERTY_DESIGNER_NAME'];
            }
        }


        if(!empty($arNameplateImgs[$product['ID']]))
            $thumb = $arNameplateImgs[$product['ID']];

        $arResult['PRODUCTS'][$product['ID']]=array('NAME'=>$product['NAME'],'DESIGNER'=>$product['PROPERTY_DESIGNER_NAME'],'SM_PIC'=>$thumb);
    }

    uasort($arResult["ITEMS"]["AnDelCanBuy"],function($a,$b) {
        return $a['SORT']>$b['SORT']?1:-1;
    });


}



$arResult["ShowReady"] = (($bShowReady)?"Y":"N");
$arResult["ShowDelay"] = (($bShowDelay)?"Y":"N");
$arResult["ShowNotAvail"] = (($bShowNotAvail)?"Y":"N");
$arResult["ShowSubscribe"] = (($bShowSubscribe)?"Y":"N");

$arOrder = array(
    'SITE_ID' => SITE_ID,
    'USER_ID' => $USER->GetID(),
    'ORDER_PRICE' => $allSum,
    'ORDER_WEIGHT' => $allWeight,
    'BASKET_ITEMS' => $arResult["ITEMS"]["AnDelCanBuy"]
);

$arOptions = array(
    'COUNT_DISCOUNT_4_ALL_QUANTITY' => $arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"],
);

$arErrors = array();

CSaleDiscount::DoProcessOrder($arOrder, $arOptions, $arErrors);

$allSum = 0;
$allWeight = 0;
$allVATSum = 0;

$DISCOUNT_PRICE_ALL = 0;
$arResult['DISCOUNT_DIFF'] = 0;
$arResult['TOTAL_PRICE'] = 0;
$arResult['TOTAL_OLD_PRICE'] = 0;
foreach ($arOrder['BASKET_ITEMS'] as &$arOneItem)
{
    $allSum += ($arOneItem["PRICE"] * $arOneItem["QUANTITY"]);
    $allWeight += ($arOneItem["WEIGHT"] * $arOneItem["QUANTITY"]);
    if (array_key_exists('VAT_VALUE', $arOneItem))
        $arOneItem["PRICE_VAT_VALUE"] = $arOneItem["VAT_VALUE"];
    $allVATSum += roundEx($arOneItem["PRICE_VAT_VALUE"] * $arOneItem["QUANTITY"], SALE_VALUE_PRECISION);
    $arOneItem["PRICE_FORMATED"] = SaleFormatCurrency($arOneItem["PRICE"]*$arOneItem["QUANTITY"], $arOneItem["CURRENCY"]);
    $arOneItem["DISCOUNT_PRICE_PERCENT"] = $arOneItem["DISCOUNT_PRICE"]*100 / ($arOneItem["DISCOUNT_PRICE"] + $arOneItem["PRICE"]);
    $arOneItem["DISCOUNT_PRICE_PERCENT_FORMATED"] = roundEx($arOneItem["DISCOUNT_PRICE_PERCENT"], SALE_VALUE_PRECISION)."%";
    $DISCOUNT_PRICE_ALL += $arOneItem["DISCOUNT_PRICE"] * $arOneItem["QUANTITY"];
    if($arOneItem["DISCOUNT_PRICE"]>0) {
        $arOneItem['OLD_PRICE_FORMATED'] = SaleFormatCurrency($arOneItem["OLD_PRICE"] * $arOneItem["QUANTITY"], "RUB");
    }
    $arResult['TOTAL_PRICE'] = $arResult['TOTAL_PRICE'] + ($arOneItem["PRICE"] * $arOneItem["QUANTITY"]);
    $arResult['TOTAL_DISCOUNT'] = $arResult['TOTAL_DISCOUNT'] + ($arOneItem["DISCOUNT_PRICE"] * $arOneItem["QUANTITY"]);
    $arResult['TOTAL_OLD_PRICE'] = $arResult['TOTAL_OLD_PRICE'] + ($arOneItem["OLD_PRICE"] * $arOneItem["QUANTITY"]);
}

$arResult['TOTAL_DISCOUNT_FORMATED'] = SaleFormatCurrency($arResult['TOTAL_DISCOUNT'], 'RUB');
$arResult['TOTAL_PRICE_FORMATED'] = SaleFormatCurrency($arResult['TOTAL_PRICE'], 'RUB');
$arResult['TOTAL_OLD_PRICE_FORMATED'] = SaleFormatCurrency($arResult['TOTAL_OLD_PRICE'], 'RUB');

if (isset($arOneItem))
    unset($arOneItem);

$arResult["ITEMS"]["AnDelCanBuy"] = $arOrder['BASKET_ITEMS'];

$arResult["allSum"] = $allSum;
$arResult["allWeight"] = $allWeight;
$arResult["allWeight_FORMATED"] = roundEx(DoubleVal($allWeight/$arParams["WEIGHT_KOEF"]), SALE_VALUE_PRECISION)." ".$arParams["WEIGHT_UNIT"];
$arResult["allSum_FORMATED"] = SaleFormatCurrency($allSum, $allCurrency);
$arResult["DISCOUNT_PRICE_FORMATED"] = SaleFormatCurrency($arResult["DISCOUNT_PRICE"], $allCurrency);




if ($arParams["HIDE_COUPON"] != "Y")
    $arCoupons = CCatalogDiscountCoupon::GetCoupons();

if (count($arCoupons) > 0)
    $arResult["COUPON"] = htmlspecialcharsbx($arCoupons[0]);

if(count($arBasketItems)<=0)
    $arResult["ERROR_MESSAGE"] = GetMessage("SALE_EMPTY_BASKET");

$arResult["DISCOUNT_PRICE_ALL"] = $DISCOUNT_PRICE_ALL;
$arResult["DISCOUNT_PRICE_ALL_FORMATED"] = SaleFormatCurrency($DISCOUNT_PRICE_ALL, $allCurrency);


$this->IncludeComponentTemplate();

?>
