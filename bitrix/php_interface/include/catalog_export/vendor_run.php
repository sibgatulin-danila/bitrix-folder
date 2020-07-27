<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

        define("NO_KEEP_STATISTIC", true);
        define("NOT_CHECK_PERMISSIONS",true);
        define("BX_CAT_CRON", true);
        define('NO_AGENT_CHECK', true);
        set_time_limit (0);


        if ((CModule::IncludeModule("catalog"))&&(CModule::IncludeModule("iblock")))
        {
                echo '<pre>';
                $arSelect = Array("ID", "NAME", 'XML_ID', 'IBLOCK_SECTION_ID', "DETAIL_PAGE_URL", 'PROPERTY_PHOTOGALLERY', 'CATALOG_GROUP_1');
                $arFilter = Array("IBLOCK_ID"=>1, "PROPERTY_DESIGNER"=>"9970", "ACTIVE"=>"Y", '>CATALOG_QUANTITY'=>'1');
                $rsElement = CIBlockElement::GetList(Array('CATALOG_PRICE_1'=>'ASC'), $arFilter, false, Array("nPageSize"=>1), $arSelect);
                if($arElement = $rsElement->GetNext()){
                        $rsVendor = CIBlockElement::GetByID('9970');
                        $arVendor = $rsVendor->GetNext();
                        print_r($arVendor); 
                        $rsSection = CIBlockSection::GetByID($arElement['IBLOCK_SECTION_ID']);
                        $arSection = $rsSection->GetNext();
                        
                        $arrSelect = Array("ID", "NAME", 'IBLOCK_SECTION_ID','PROPERTY_REAL_PICTURE');
                        $arrFilter = Array("IBLOCK_ID"=>7, "IBLOCK_SECTION_ID"=>$arElement['PROPERTY_PHOTOGALLERY_VALUE'], "ACTIVE"=>"Y");
                        $rrsElement = CIBlockElement::GetList(Array('ID'=>'ASC'), $arrFilter, false, Array("nPageSize"=>1), $arrSelect); 
                        if($arrElement = $rrsElement->GetNext()){
                                /*$rsFile = CFile::GetByID($arrElement['PROPERTY_REAL_PICTURE_VALUE']);
                                $arFile = $rsFile->GetNext();

                                $file = CFile::ResizeImageGet($arFile, array('width' => $uInfo['W'], 'height' => $uInfo['H']), BX_RESIZE_IMAGE_EXACT, true);
                                    if(empty($file['src'])){
                                       $file['src'] = CFile::GetPath($uInfo['ITEM']);
                                    }
                                return $file['src'];  */                              
                                $gallery = CFile::GetPath($arrElement['PROPERTY_REAL_PICTURE_VALUE']);

                        }
                        $result='<?xml version="1.0" encoding="utf-8"?>
<yml_catalog date="'.date('Y-m-d H:i').'">
        <shop>
                <name>PoisonDrop</name>
                <company>PoisonDrop</company>
                <url>http://poisondrop.ru</url>
                <platform>1C-Bitrix</platform>
                <currencies>
                        <currency id="RUB" rate="1"/>
                </currencies>                                
                <category>
                        <category id="'.$arSection['ID'].'">'.$arSection['NAME'].'</category>
                </category>
                <offers>
                        <offer id="'.$arElement['XML_ID'].'" type="vendor.model" available="true">
                                <url>http://poisondrop.ru/designers/alchemia/</url>
                                <price>'.$arElement['CATALOG_PRICE_1'].'</price>
                                <currencyId>RUB</currencyId>
                                <picture>http://poisondrop.ru'.$gallery.'</picture>
                                <vendor>Alchemia</vendor>
                        </offer> 
                </offers>
        </shop>
</yml_catalog>';
                        file_put_contents('/var/www/prod/bitrix/catalog_export/vendor.xml',$result);
                }

        }
?>

