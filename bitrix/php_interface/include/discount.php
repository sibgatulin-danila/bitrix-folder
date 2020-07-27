<?php
//Временная скидка для заказов 1+1=3

/*AddEventHandler("sale", "OnBeforeOrderAdd", "OnBeforeOrderAdd");

function OnBeforeOrderAdd(&$arFields)
{
    $arBasket = array();
    $dbBasketItems = CSaleBasket::GetList(
        array(
            "NAME" => "ASC",
            "ID" => "ASC"
        ),
        array(
            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
            "LID" => SITE_ID,
            "ORDER_ID" => "NULL"
        ),
        false,
        false,
        array()
    );

    $quantity = 0;
    $discount_count = 0;
    $arPrices = array();
    $discountPrice = 0;
    $arAllPrices = array();

    while ($obBasket = $dbBasketItems->Fetch()) {
        $arBasket[$obBasket['ID']] = $obBasket;
        if ($obBasket['PRODUCT_ID'] != 4181 && $obBasket['NAME'] != 'Помощь детям') {
            $quantity += intval($obBasket['QUANTITY']);
            for ($p = 0; $p < intval($obBasket['QUANTITY']); $p++ ) {
                $arPrices[] = $obBasket['PRICE'];
            }
            $arAllPrices[$obBasket['ID']] = $obBasket['PRICE'];
            $discountPrice += $obBasket['DISCOUNT_PRICE'];
            $arBasket[$obBasket['ID']]['NEW_QUANTITY'] = $obBasket['QUANTITY'];
        }
    }
    if ($quantity > 0) {
        $discount_count = intval($quantity/3);
    }

    sort($arPrices);
    for ($i = 0; $i < $discount_count; $i++) {
        foreach ($arAllPrices as $key => $val) {
            $productID = array_search($arPrices[$i],$arAllPrices);
            if (intval($arBasket[$productID]['QUANTITY']) > 0 && $val == $arPrices[$i]) {
                file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test/quantity.txt', print_r($arBasket[$productID], 1));
                $arBasket[$productID]['QUANTITY']--;


                $arAdd = array(
                    'PRICE' => 0,
                    'DISCOUNT_PRICE' => floatval($arBasket[$productID]['PRICE']) + floatval($arBasket[$productID]['DISCOUNT_PRICE']),
                    'CALLBACK_FUNC' => '',
                    'ORDER_CALLBACK_FUNC ' => '',
                    'PRODUCT_PROVIDER_CLASS' => '',
                    'CURRENCY' => 'RUB',
                    'DISCOUNT_VALUE' => 100,
                    'LID' => SITE_ID
                );

                file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test/quantity.txt', print_r($arAdd, 1), FILE_APPEND);

                CSaleBasket::Update($arBasket[$productID]['ID'],$arAdd);
                break;
            }
        }
    }
}*/
AddEventHandler("sale", "OnOrderUpdate", "OnOrderUpdate");

function OnOrderUpdate($ID, $arFields) {
    /*if ($ID == 6954 || $ID == 6946) {*/
        $now = date('d.m.Y H:i:s');
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/orderUpdate.txt', $ID."\n", FILE_APPEND);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/orderUpdate.txt', $now."\n", FILE_APPEND);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/orderUpdate.txt', print_r($arFields, 1), FILE_APPEND);
    //}

}