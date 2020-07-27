<?

class CPoisonUtils
{


    public static function getCityByIP()
    {

        if (2 == 2) {

            $ip = $_SERVER['REMOTE_ADDR'];
            $ch = curl_init();
            /* curl_setopt($ch, CURLOPT_URL, 'http://ipgeobase.ru:7020/geo?ip='.$ip);
             curl_setopt($ch, CURLOPT_HEADER, false);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_TIMEOUT, 3);
             curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
             curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Bot');
             $data = curl_exec($ch);

             $city = ( !curl_errno($ch) && $xml = simplexml_load_string($data) ) ? strval($xml->ip->city) : false;

             curl_close($ch);    */
            $data = file_get_contents('http://ipgeobase.ru:7020/geo?ip=' . $ip);
            $city = ($xml = simplexml_load_string($data)) ? strval($xml->ip->city) : false;
            CModule::IncludeModule('sale');
            if (strval($city) == 'Москва') {
                $city = 'Москва (в пределах МКАД)';
            }

            $rsLocation = CSaleLocation::GetList(
                array(),
                array("LID" => LANGUAGE_ID, 'CITY_NAME' => strval($city)),
                false,
                false,
                array()
            );
            if ($location = $rsLocation->Fetch())
                $arrLocation = array('COUNTRY_CODE' => strval($xml->ip->country), 'LOCATION_CODE' => $location['ID']);
            else
                $arrLocation = array('COUNTRY_CODE' => 'RU', 'LOCATION_CODE' => 2443);

            $_SESSION['IP_CITY'] = $arrLocation;
        } else {
            $arrLocation = $_SESSION['IP_CITY'];
        }

        return $arrLocation;
    }


    public static function getFirstThumb($id)
    {
        if (CModule::IncludeModule('iblock') && !empty($id)) {
            if ($e = CIBlockElement::GetByID($id)->GetNext())
                if ($e['IBLOCK_ID'] == 11) {
                    if ($offer = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $e['IBLOCK_ID'], 'ID' => $id), false, array('nTopCount' => 1), array('PROPERTY_CML2_LINK'))->GetNext()) {
                        $id = $offer['PROPERTY_CML2_LINK_VALUE'];
                    }
                }
            if ($product = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 1, 'ID' => $id), false, array('nTopCount' => 1), array('PROPERTY_PHOTOGALLERY'))->GetNext()) {
                if (!empty($product['PROPERTY_PHOTOGALLERY_VALUE'])) {
                    $rsPhotoGallery = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_CODE' => 'photogallery', 'SECTION_ID' => $product['PROPERTY_PHOTOGALLERY_VALUE']), false, array('nTopCount' => 1), array('PROPERTY_REAL_PICTURE'));
                    if ($galleryItem = $rsPhotoGallery->GetNext()) {
                        $thumb = CFile::ResizeImageGet($galleryItem['PROPERTY_REAL_PICTURE_VALUE'], array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        return $thumb['src'];
                    }
                }
            }
        }
        return '';
    }


    public static function getFirstImg($id)
    {
        if (CModule::IncludeModule('iblock') && !empty($id)) {
            if ($e = CIBlockElement::GetByID($id)->GetNext())
                if ($e['IBLOCK_ID'] == 11) {
                    if ($offer = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $e['IBLOCK_ID'], 'ID' => $id), false, array('nTopCount' => 1), array('PROPERTY_CML2_LINK'))->GetNext()) {
                        $id = $offer['PROPERTY_CML2_LINK_VALUE'];
                    }
                }
            if ($product = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 1, 'ID' => $id), false, array('nTopCount' => 1), array('PROPERTY_PHOTOGALLERY'))->GetNext()) {
                if (!empty($product['PROPERTY_PHOTOGALLERY_VALUE'])) {
                    $rsPhotoGallery = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_CODE' => 'photogallery', 'SECTION_ID' => $product['PROPERTY_PHOTOGALLERY_VALUE']), false, array('nTopCount' => 1), array('PROPERTY_REAL_PICTURE'));
                    if ($galleryItem = $rsPhotoGallery->GetNext()) {
                        $thumb = CFile::ResizeImageGet($galleryItem['PROPERTY_REAL_PICTURE_VALUE'], array('width' => 300, 'height' => 300), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        return $thumb['src'];
                    }
                }
            }
        }
        return '';
    }


    public static function getFirstPic($id)
    {
        if (CModule::IncludeModule('iblock') && !empty($id)) {
            if ($e = CIBlockElement::GetByID($id)->GetNext())
                if ($e['IBLOCK_ID'] == 11) {
                    if ($offer = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $e['IBLOCK_ID'], 'ID' => $id), false, array('nTopCount' => 1), array('PROPERTY_CML2_LINK'))->GetNext()) {
                        $id = $offer['PROPERTY_CML2_LINK_VALUE'];
                    }
                }
            if ($product = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 1, 'ID' => $id), false, array('nTopCount' => 1), array('PROPERTY_PHOTOGALLERY'))->GetNext()) {
                if (!empty($product['PROPERTY_PHOTOGALLERY_VALUE'])) {
                    $rsPhotoGallery = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_CODE' => 'photogallery', 'SECTION_ID' => $product['PROPERTY_PHOTOGALLERY_VALUE']), false, array('nTopCount' => 1), array('PROPERTY_REAL_PICTURE'));
                    if ($galleryItem = $rsPhotoGallery->GetNext()) {
                        $thumb = CFile::ResizeImageGet($galleryItem['PROPERTY_REAL_PICTURE_VALUE'], array('width' => 500, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        return $thumb['src'];
                    }
                }
            }
        }
        return '';
    }

    public static function getUsersWish()
    {

        $cache = new CPHPCache();
        $cacheTime = 200;
        $cachePath = "userwish_tmp";
        $cacheid = "userwish_tmp";
        if ($cache->InitCache($cacheTime, $cacheid, $cachePath)) {
            $arResult = $cache->GetVars();
        } else {
            CModule::IncludeModule('catalog');
            CModule::IncludeModule('sale');
            $rsUser = CUser::GetList(($by = "id"), ($order = "desc"), array('!UF_WISHLIST' => false), array('SELECT' => array('UF_WISHLIST')));
            $arResult = array();
            $arWLProductsIds = array();
            while ($bxUser = $rsUser->GetNext()) {
                $arWL = explode(',', $bxUser['UF_WISHLIST']);
                $arWLProductsIds = array_merge($arWLProductsIds, (array)$arWL);
                //$bxUser['EMAIL'] = 'trubchik@yandex.ru';
                foreach ($arWL as $pid)
                    $arResult[$pid][$bxUser['ID']] = array('NAME' => $bxUser['LAST_NAME'] . ' ' . $bxUser['NAME'], 'EMAIL' => $bxUser['EMAIL']);
            }
            $rsBasket = CSaleBasket::GetList(array("ID" => "ASC"), array("LID" => SITE_ID, "ORDER_ID" => false), false, false);
            while ($basket = $rsBasket->Fetch()) {
                if ($arFUser = CSaleUser::GetList(array('ID' => $basket['FUSER_ID'])))
                    $user = CUser::GetByID($arFUser['USER_ID'])->GetNext();
                if (empty($user['ID'])) {
                    continue;
                }
                //$user['EMAIL'] = 'trubchik@yandex.ru';
                $arResult[$basket['PRODUCT_ID']][$user['ID']] = array('NAME' => $user['LAST_NAME'] . ' ' . $user['NAME'], 'EMAIL' => $user['EMAIL']);
            }
            $cache->StartDataCache($cacheTime, $cacheid, $cachePath);
            $cache->EndDataCache($arResult);
        }

        return $arResult;
    }

    function getImg($id)
    {
        if (CModule::IncludeModule('iblock') && !empty($id)) {
            if ($e = CIBlockElement::GetByID($id)->GetNext())
                if ($e['IBLOCK_ID'] == 11) {
                    if ($offer = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $e['IBLOCK_ID'], 'ID' => $id), false, array('nTopCount' => 1), array('PROPERTY_CML2_LINK'))->GetNext()) {
                        $id = $offer['PROPERTY_CML2_LINK_VALUE'];
                    }
                }
            if ($product = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 1, 'ID' => $id), false, array('nTopCount' => 1), array('PROPERTY_PHOTOGALLERY'))->GetNext()) {
                if (!empty($product['PROPERTY_PHOTOGALLERY_VALUE'])) {
                    $rsPhotoGallery = CIBlockElement::GetList(array('SORT' => 'ASC'), array('IBLOCK_CODE' => 'photogallery', 'SECTION_ID' => $product['PROPERTY_PHOTOGALLERY_VALUE']), false, array('nTopCount' => 1), array('PROPERTY_REAL_PICTURE'));
                    if ($galleryItem = $rsPhotoGallery->GetNext()) {
                        $thumb = CFile::ResizeImageGet($galleryItem['PROPERTY_REAL_PICTURE_VALUE'], array('width' => 166, 'height' => 166), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        return $thumb['src'];
                    }
                }
            }
        }
        return '';
    }


    public static function getNameplateImg($orderID = "NULL", $scale = 2)
    {
        $arNameplateProducts = array(37940,37941,37942);
        //$arNameplateProducts = array(7356, 7357, 7500);
        $arRes = array();
        if (CModule::IncludeModule('sale')) {
            $dbBasketItems = CSaleBasket::GetList(
                array("ID" => "DESC"),
                array("FUSER_ID" => CSaleBasket::GetBasketUserID(),
                    "PRODUCT_ID" => $arNameplateProducts,
                    "LID" => SITE_ID,
                    "ORDER_ID" => $orderID,
                    'CAN_BUY' => 'Y',
                    "DELAY" => "N",
                    'SUBSCRIBE' => 'N'),
                false, false, array('ID', 'PRODUCT_ID'));

            while ($arBasket = $dbBasketItems->Fetch()) {
                $arProps = array();
                $dbProp = CSaleBasket::GetPropsList(Array("SORT" => "ASC", "ID" => "ASC"), Array("BASKET_ID" => $arBasket["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")));
                while ($arProp = $dbProp->GetNext()) {
                    $arProps[] = $arProp;
                }

                //print_r($arProps);
                if (!empty($arProps)) {
                    $arNameplateParamsMap = array('NP_TEXT' => 'text', 'NP_COLOR' => 'c', 'NP_FONT' => 'f', 'NP_CHAIN' => 'ct');
                    $arNameplateProps = array();
                    foreach ($arProps as $prop) {
                        if ($prop['CODE'] == 'NP_TEXT') {
                            $imgText = $prop['VALUE'];
                        }

                        if ($prop['CODE'] == 'NP_CHAIN' && !in_array($prop['VALUE'], array('c1', 'c2'))) {
                            if ($prop['VALUE'] == 'якорное')
                                $prop['VALUE'] = 'c1';
                            else
                                $prop['VALUE'] = 'c2';
                        }


                        if (in_array($prop['CODE'], array('NP_TEXT', 'NP_COLOR', 'NP_FONT', 'NP_CHAIN'))) {
                            $arNameplateProps[] = $arNameplateParamsMap[$prop['CODE']] . '=' . urlencode($prop['VALUE']);
                        }
                    }
                }
                if (!empty($arNameplateProps)) {
                    $arRes[$arBasket['PRODUCT_ID']] = '/namenecklaceconstructor/gentext.php?' . implode('&', $arNameplateProps) . '&scale=' . $scale;
                }
            }


        }
        return $arRes;
    }

    public static function getNameplateUrl($props)
    {
        $arUrlParams = array();
        foreach ($props as $prop) {
            if ($prop['CODE'] == 'NP_COLOR') {
                $arUrlParams[] = 'c=' . $prop['VALUE'];
            }
            if ($prop['CODE'] == 'NP_FONT') {
                $arUrlParams[] = 'f=' . $prop['VALUE'];
            }
            if ($prop['CODE'] == 'NP_TEXT') {
                $arUrlParams[] = 'text=' . $prop['VALUE'];
            }
            if ($prop['CODE'] == 'NP_FONT') {
                $arUrlParams[] = 'f=' . $prop['VALUE'];
            }

            if ($prop['CODE'] == 'NP_CHAIN') {
                $arUrlParams[] = 'ct=' . ($prop['VALUE'] == 'якорное' ? 'c1' : 'c2');
            }

        }
        return '/namenecklaceconstructor/?' . implode('&', $arUrlParams);
    }

    public static function getNPImg($props, $scale = 2)
    {
        $arPropMap = array('TEXT' => 'text', 'METALL_ID' => 'm', 'FONT_ID' => 'f', 'PLAITING_ID' => 'p', 'COVERING_ID' => 'c', 'TIME_PRODUCTION' => 't');
        $arUrlComponents = array();
        foreach ($props as $prop) {
            if (in_array($prop['CODE'], array('TEXT', 'METALL_ID', 'FONT_ID', 'PLAITING_ID', 'COVERING_ID', 'TIME_PRODUCTION'))) {
                if (!empty($prop['VALUE']))
                    $arUrlComponents[] = $arPropMap[$prop['CODE']] . '=' . urlencode($prop['VALUE']);
            }
        }
        return '/rest/np/img.php?' . implode('&', $arUrlComponents) . '&scale=' . $scale;
    }

    public static function getNPURL($props)
    {
        $arPropMap = array('TEXT' => 'text', 'METALL_ID' => 'm', 'FONT_ID' => 'f', 'PLAITING_ID' => 'p', 'COVERING_ID' => 'c', 'TIME_PRODUCTION' => 't');
        $arUrlComponents = array();
        foreach ($props as $prop) {
            if (in_array($prop['CODE'], array('TEXT', 'METALL_ID', 'FONT_ID', 'PLAITING_ID', 'COVERING_ID', 'TIME_PRODUCTION'))) {
                if (!empty($prop['VALUE']))
                    $arUrlComponents[] = $arPropMap[$prop['CODE']] . '=' . urlencode($prop['VALUE']);
            }
        }
        return '/namenecklaceconstructor/?' . implode('&', $arUrlComponents);
    }

    public static function GetHelpers($sid = '')
    {
        $arResult = array('COLORS' => array(), 'DESIGNERS' => array());
        $cacheHelpers = new CPHPCache();
        $cacheid = 'helpers_' . $sid;
        $cachePath = '/helpers/';
        $cacheTime = 1600;

        if ($cacheHelpers->InitCache($cacheTime, $cacheid, $cachePath)) {
            $arResult = $cacheHelpers->GetVars();
        } else {

            if (CModule::IncludeModule('iblock')) {

                $colorFilter = array('IBLOCK_CODE' => 'colors', 'ACTIVE' => 'Y');
                $filterDesigner = array('IBLOCK_CODE' => 'designers', 'ACTIVE' => 'Y');
                $filterSizes = array('IBLOCK_CODE' => 'ring_sizes', 'ACTIVE' => 'Y');
                if (!empty($sid)) {
                    $arff = array('IBLOCK_ID' => 1, 'ACTIVE' => 'Y');
                    if ($sid == 'sale') {
                        $arff['!PROPERTY_DISCOUNT'] = false;
                    } else {
                        $arff['SECTION_CODE'] = $sid;
                    }

                    $rsColorGrouped = CIBlockElement::GetList(array(), $arff, array('PROPERTY_COLOR'), false);
                    $arGroupedColors = array();
                    while ($c = $rsColorGrouped->GetNext()) {
                        $arGroupedColors[] = $c['PROPERTY_COLOR_VALUE'];
                    }
                    if (!empty($arGroupedColors))
                        $colorFilter['ID'] = $arGroupedColors;

                    $rsDesignerGrouped = CIBlockElement::GetList(array(), $arff, array('PROPERTY_DESIGNER'), false);
                    $arGroupedDesigner = array();
                    while ($d = $rsDesignerGrouped->GetNext()) {
                        $arGroupedDesigner[] = $d['PROPERTY_DESIGNER_VALUE'];
                    }
                    if (!empty($arGroupedDesigner))
                        $filterDesigner['ID'] = $arGroupedDesigner;

                }

                $rsColors = CIBlockElement::GetList(array('SORT' => 'ASC'), $colorFilter, false, false);
                while ($color = $rsColors->GetNext()) {
                    $arResult['COLORS'][] = array('ID' => $color['ID'], 'PIC' => CFile::GetPath($color['PREVIEW_PICTURE']));
                }

                $rsDesigners = CIBlockElement::GetList(array('NAME' => 'ASC'), $filterDesigner, false, false);
                while ($designer = $rsDesigners->GetNext()) {
                    $arResult['DESIGNERS'][] = array('ID' => $designer['ID'], 'NAME' => $designer['NAME']);
                }

                $rsSizes = CIBlockElement::GetList(array('NAME' => 'ASC'), $filterSizes, false, false);
                while ($size = $rsSizes->GetNext()) {
                    $arResult['SIZES'][] = array('ID' => $size['ID'], 'NAME' => $size['NAME']);
                }

            }
            $cacheHelpers->StartDataCache($cacheTime, $cacheid, $cachePath);
            $cacheHelpers->EndDataCache($arResult);
        }
        return $arResult;
    }


}

?>