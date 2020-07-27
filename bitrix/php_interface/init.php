<?

require_once('include/debug.php');
require_once('include/discount.php');

function preprint($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function preprintjs($arr){
        echo '
                <style>.hide{display: none;}</style>                
        ';
    $time = rand(0, 1000);
    echo '<div class="hide" id="'.$time.'">';
    print_r($arr);
    echo '</div><script>
    console.groupCollapsed("%c%s", "font-size: 10pt", "PHP Debug");
    console.log(document.getElementById('.$time.').innerHTML);
    console.log("••••••••••••••••••••••••••••••••••••••••••••••••••");
    console.dirxml('.json_encode($arr).');
    console.count("Count");
    console.groupEnd();</script>';
}



CModule::IncludeModule('iblock');

AddEventHandler('main', 'OnBeforeUserAdd', 'OnBeforeUserAddHandler'); //Пароль в доп свойство

AddEventHandler('main', 'OnBeforeUserRegister', 'OnBeforeUserUpdateHandler');
AddEventHandler('main', 'OnBeforeUserUpdate', 'OnBeforeUserUpdateHandler');
AddEventHandler('main','OnBeforeProlog', Array('CPoisonDrop', 'OnBeforeProlog'));
AddEventHandler('iblock', 'OnBeforeIBlockSectionUpdate', Array('CPoisonDrop', 'UpdateSection'));

AddEventHandler('iblock', 'OnBeforeIBlockElementAdd', Array('CPoisonDrop', 'UpdateElement'));
AddEventHandler('iblock', 'OnBeforeIBlockElementUpdate', Array('CPoisonDrop', 'UpdateElement'));
AddEventHandler('iblock', 'OnBeforeIBlockElementDelete', Array('CPoisonDrop', 'OnBeforeDelete'));
AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', Array('CPoisonDrop', 'AfterUpdateElement'));
AddEventHandler('iblock', 'OnAfterIBlockElementAdd', Array('CPoisonDrop', 'AddNewElement'));
//AddEventHandler('sale','OnBeforeBasketUpdate',array('CPoisonDrop','EventUpdateBasket'));
//AddEventHandler('sale','OnBeforeBasketAdd',array('CPoisonDrop','EventAddToBasket'));
AddEventHandler('catalog','OnBeforePriceUpdate',array('CPoisonDrop','BeforePriceUpdate'));
AddEventHandler('catalog','OnBeforePriceAdd',array('CPoisonDrop','BeforePriceUpdate'));
AddEventHandler('catalog', 'OnBeforeProductUpdate', Array('CPoisonDrop', 'OnBeforeProductUpdate'));
AddEventHandler('catalog', 'OnBeforeProductAdd', Array('CPoisonDrop', 'OnBeforeProductAdd'));
//AddEventHandler('sale', 'OnOrderNewSendEmail', 'ModifySaleMails');
AddEventHandler('sale', 'OnBeforeSaleSubscribeProduct', 'ModifySaleSubscribeProduct');//kgn

function ModifySaleSubscribeProduct(&$arFields) {
    //kgn получим изображение продукта
    if (CModule::IncludeModule("iblock")) {
        $arCurent = array();
        $dbCurent = CIBlockElement::GetByID($arFields['ID']);
        $obCurent = $dbCurent->GetNextElement();
        $arCurent['FIELDS'] = $obCurent->GetFields();
        $arCurent['PROPERTIES'] = $obCurent->GetProperties();
        if(!empty($arCurent['PROPERTIES']['PHOTOGALLERY']['VALUE'])) {
            if(empty($arCurent['PREVIEW_PICTURE'])) {
                if($albumFirst = CIBlockElement::GetList(
                    array('SORT'=>'ASC'),
                    array('IBLOCK_CODE'=>'photogallery','SECTION_ID'=>$arCurent['PROPERTIES']['PHOTOGALLERY']['VALUE']),
                    false,array('nTopCount'=>1),array('PREVIEW_PICTURE'))
                    ->Fetch()) {
                    $arCurent['PREVIEW_PICTURE'] = $albumFirst['PREVIEW_PICTURE'];
                }
            }
        } else {
            $arCurent['PREVIEW_PICTURE'] = $arCurent['FIELDS']['PREVIEW_PICTURE'];
        }
        $arFields['PIC'] = CFile::GetPath($arCurent['PREVIEW_PICTURE']);
    }
}


function OnBeforeUserUpdateHandler(&$arFields) {
    $arFields["LOGIN"] = $arFields["EMAIL"];
    return $arFields;
}

function OnBeforeUserAddHandler(&$arFields) {
    $arFields['UF_PASS'] = $arFields['PASSWORD'];
}

//Регистрация
AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnAfterUserSimpleRegister", "OnAfterUserRegisterHandler");
function OnAfterUserRegisterHandler(&$arFields) {
    if (intval($arFields["ID"])>0)
    {
        $toSend = Array();
        $toSend["PASSWORD"] = $arFields["CONFIRM_PASSWORD"];
        $toSend["EMAIL"] = $arFields["EMAIL"];
        $toSend["USER_ID"] = $arFields["ID"];
        $toSend["USER_IP"] = $arFields["USER_IP"];
        $toSend["USER_HOST"] = $arFields["USER_HOST"];
        $toSend["LOGIN"] = $arFields["LOGIN"];
        $toSend["NAME"] = (trim ($arFields["NAME"]) == "")? $toSend["NAME"] = htmlspecialchars('<Не указано>'): $arFields["NAME"];
        $toSend["LAST_NAME"] = (trim ($arFields["LAST_NAME"]) == "")? $toSend["LAST_NAME"] = htmlspecialchars('<Не указано>'): $arFields["LAST_NAME"];
        CEvent::SendImmediate ("NEW_USER_WITH_PASS", SITE_ID, $toSend);
    }
    return $arFields;
}



error_reporting(E_ERROR);
ini_set('display_errors', 1);


class CPoisonDrop {

    function OnBeforeProlog() {

        if($_GET['action']=='1500') {
            CModule::IncludeModule('catalog');
            CCatalogDiscountCoupon::SetCoupon('SL-AQBX9-1JA3VMZ');
        }

        if(array_key_exists('action_id',$_GET)) {
            CModule::IncludeModule('catalog');
            $action_id = $_GET['action_id'];
            global $USER;
            $arGroups = $USER->GetUserGroupArray();
            $rsGroup = CGroup::GetByID($action_id, "N");
            if($arGroup = $rsGroup->Fetch()) {
                if(!in_array($action_id,$arGroups)) {
                    $arGroups[] = $action_id;
                    $USER->SetUserGroupArray($arGroups);
                    CUser::SetUserGroup($USER->GetID(), $arGroups);
                }
            }
        }

    }

    function UpdateGalleryElement(&$arFields) {
        CIBlock::ResizePicture($arFields['DETAIL_PICTURE'], array("WIDTH" => 500 , "HEIGHT"=>500,"METHOD" => "resample","COMPRESSION"=>95));
        CIBlock::ResizePicture($arFields['PREVIEW_PICTURE'], array("WIDTH" => 300 , "HEIGHT"=>300,"METHOD" => "resample","COMPRESSION"=>95));
    }

    function UpdateCatalogElement(&$arFields) {
        /* if(!empty($_POST)) {
         if(!empty($arFields['DETAIL_PICTURE']['tmp_name'])) {
           if(!empty($arFields['DETAIL_PICTURE']['tmp_name'])) {
             $DETAILPIC_ID = CFile::SaveFile($arFields['DETAIL_PICTURE'],'iblock');
             $PREVIEWPIC_ID = CFile::CopyFile($DETAILPIC_ID);
             $DETAIL_FILE = CFile::MakeFileArray($DETAILPIC_ID);
             CIBlock::ResizePicture($DETAIL_FILE, array("WIDTH" => 500 ,"METHOD" => "resample","COMPRESSION"=>95));
             if(is_array($DETAIL_FILE))
              $arFields["DETAIL_PICTURE"] = $DETAIL_FILE;

             if(is_array($PREVIEW_FILE))
              $arFields["PREVIEW_PICTURE"] = $PREVIEW_FILE;


            } else {
             if(!empty($arFields['DETAIL_PICTURE']['old_file']) && empty($arFields['PREVIEW_PICTURE']['old_file']))
               $PREVIEWPIC_ID = CFile::CopyFile($arFields['DETAIL_PICTURE']['old_file']);
           }

           $PREVIEW_FILE = CFile::MakeFileArray($PREVIEWPIC_ID);
           CIBlock::ResizePicture($PREVIEW_FILE, array("WIDTH" => 300 ,"METHOD" => "resample","COMPRESSION"=>95));
           if(is_array($PREVIEW_FILE))
             $arFields["PREVIEW_PICTURE"] = $PREVIEW_FILE;

           $THUMB_FILE = CFile::MakeFileArray(CFile::CopyFile($PREVIEWPIC_ID));
           CIBlock::ResizePicture($THUMB_FILE, array("WIDTH" => 80 ,"METHOD" => "resample","COMPRESSION"=>95));
           $arFields["PROPERTY_VALUES"][26] = $THUMB_FILE;

         }

        }  */


        if(!empty($arFields['PROPERTY_VALUES'][27][0])) {

            if($currElement = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>$arFields['IBLOCK_ID'],'ID'=>$arFields['ID']),false,false,array('NAME','DETAIL_PAGE_URL','PROPERTY_THUMB','PROPERTY_DESIGNER.NAME','PROPERTY_DISCOUNT','CATALOG_GROUP_1'))->GetNext()) {

                if(intval($arFields['PROPERTY_VALUES'][27][0])>intval($currElement['PROPERTY_DISCOUNT_ENUM_ID'])) {
                    if($discount = CIBlockProperty::GetPropertyEnum("DISCOUNT", Array(), Array("IBLOCK_ID"=>$arFields['IBLOCK_ID'], "ID"=>$arFields['PROPERTY_VALUES'][27][0]))->GetNext()) {
                        $discountValue = $discount['VALUE'];
                        CModule::IncludeModule('poisondrop');
                        $thumb = CPoisonUtils::getFirstImg($currElement['ID']);


                        $arEventFields = array('NAME'=>$currElement['NAME'],
                            'DESIGNER'=>$currElement['PROPERTY_DESIGNER_NAME'],
                            'URL'=>'http://'.SITE_SERVER_NAME.$currElement['DETAIL_PAGE_URL'],
                            'THUMB'=>'http://'.SITE_SERVER_NAME.$thumb,
                            'PRICE'=>intval($currElement['CATALOG_PRICE_1']).' р.',
                            'DISCOUNT_PRICE'=>intval($currElement['CATALOG_PRICE_1']*((100-intval($discountValue))/100)).' р.');

                        if(CModule::IncludeModule('poisondrop')) {
                            $arUsersWish = CPoisonUtils::getUsersWish();
                            foreach($arUsersWish[$currElement['ID']] as $uw) {
                                $arEventFields['USER']=$uw['NAME'];
                                $arEventFields['EMAIL']=$uw['EMAIL'];
                                $discountEvent = new CEvent();
                                $discountEvent->SendImmediate("DISCOUNT_NOTIFY",SITE_ID,$arEventFields);
                            }

                        }
                    }
                }
            }
        }





        if(empty($arFields['ID']) && $GLOBALS['USER']->GetID()==336 ) {
            if($arFields['IBLOCK_ID']==1) {
                $arFields['ACTIVE']='N';
                $arFields['ACTIVE_FROM']=ConvertDateTime(date('d.m.Y H:i'),CSite::GetDateFormat("FULL"));
            }
        }

        if(!empty($arFields['ID']) && $GLOBALS['USER']->GetID()==336) {

            if(!empty($arFields['NAME'])) {
                $arFields['NAME'] = htmlspecialchars_decode($arFields['NAME']);
            }
            //AddMessage2Log($arFields['NAME']);

            if($currElement = CIBlockElement::GetByID($arFields['ID'])->GetNext()) {
                $arFields['ACTIVE'] = $currElement['ACTIVE'];
                //   $arFields['NAME'] = htmlspecialchars_decode($currElement['NAME']);
            }
        }



        if(!empty($arFields["PROPERTY_VALUES"][13])) {
            if($designer =  CIBlockElement::GetByID($arFields["PROPERTY_VALUES"][13])->GetNext()) {
                $arFields["PROPERTY_VALUES"][34]['VALUE']= $designer['NAME'] ;
            }
        }

    }

    function UpdateSection($arFields) {
        if($arFields["IBLOCK_ID"]==1 && $GLOBALS['USER']->GetID()==336) {
            if($arFields['ID']==431)
                return false;
        }

    }


    function UpdateElement(&$arFields) {

        switch($arFields["IBLOCK_ID"]) {
            case 7:
                return CPoisonDrop::UpdateGalleryElement($arFields);
                break;
            case 1:
            case 11:
                return CPoisonDrop::UpdateCatalogElement($arFields);
                break;
            default:
        }
    }

    function OnBeforeDelete($ID) {

        $rsElement = CIBlockElement::GetList(array(),array('IBLOCK_CODE'=>'catalog','ID'=>$ID),false,false,array('PROPERTY_PHOTOGALLERY'));
        if($e=$rsElement->Fetch()) {
            if(!empty($e['PROPERTY_PHOTOGALLERY_VALUE']))
                CIBlockSection::Delete($e['PROPERTY_PHOTOGALLERY_VALUE']);
        }

    }



    function  AddNewElement(&$arFields) {
        if($arFields["IBLOCK_ID"]==1) {
            if($arFields['ID']>0 && is_numeric($arFields['XML_ID'])) {
                CIBlockElement::SetPropertyValuesEx($arFields['ID'],1,array(33=> str_pad($arFields['XML_ID'],6,0,STR_PAD_LEFT)));
            }
        }
    }





    function BeforePriceUpdate($ID, &$arFields) {

        if(in_array($arFields['PRODUCT_ID'],array(37942,37941)))
            return false;
        $arFields['CATALOG_GROUP_ID'] = 1;
        $arFields['PRICE'] = intval($arFields['PRICE']);
        $arFields['CURRENCY']='RUB';
    }


    function OnBeforeProductUpdate($ID,&$arFields) {


        if($e = CIBlockElement::GetList(array(),array('IBLOCK_ID'=>11,'ID'=>$ID),false,false,array('PROPERTY_CML2_LINK'))->GetNext()) {

            if(!empty($e['PROPERTY_CML2_LINK_VALUE'])) {
                CModule::IncludeModule('sale');
                $arPrices =  CIBlockPriceTools::GetCatalogPrices(1, array(PRICE_TYPE));
                $arOffers = CIBlockPriceTools::GetOffersArray(1,array($e['PROPERTY_CML2_LINK_VALUE']),array(),array(),array('SIZE_RINGS'),0,$arPrices,1,array());
                if(!empty($arOffers)) {
                    $productQuant = 0;
                    foreach($arOffers as $i=>$offer) {
                        if($ID==$offer['ID']) {
                            $productQuant+=intval($arFields['QUANTITY']);
                        } else {
                            if($product = CCatalogProduct::GetList(array(),array('ID'=>$offer['ID']),false,false,array('QUANTITY'))->GetNext()) {
                                $productQuant+=intval($product['QUANTITY']);
                            }
                        }
                    }

                    CCatalogProduct::Update($e['PROPERTY_CML2_LINK_VALUE'], array('QUANTITY'=>$productQuant));
                    CPrice::SetBasePrice($e['PROPERTY_CML2_LINK_VALUE'],$arOffers[0]['CATALOG_PRICE_1'],'RUB');
                }

            }
        }
        return true;
    }



    function OnBeforeProductAdd(&$arFields) {
        $arFields["QUANTITY_TRACE"] = "Y";
        return true;
    }


    function EventAddToBasket(&$arFields) {

        /* if($product = CCatalogProduct::GetByID($arFields['PRODUCT_ID'])) {
        if($arFields['QUANTITY'] > $product['QUANTITY'])
          $arFields['QUANTITY'] = $product['QUANTITY'];
   } */

        //$_SESSION['RESERVED'] = time();
        return true;

    }

    function EventUpdateBasket($ID, &$arFields) {

        //if(!$arFields['ORDER_ID'] && $product = CCatalogProduct::GetByID($arFields['PRODUCT_ID']))
        {
            //  if($arFields['QUANTITY'] > $product['QUANTITY'])
//     $arFields['QUANTITY'] = $product['QUANTITY'];

        }

        return true;


    }
    //Обновление скидок

    function AfterUpdateElement(&$arFields) {
        if ($arFields['IBLOCK_ID'] == 3) {
            if (is_array($arFields['PROPERTY_VALUES'][93][0])) {
                $sale = $arFields['PROPERTY_VALUES'][93][0]['VALUE'];
            } else {
                $sale = $arFields['PROPERTY_VALUES'][93][0];
            }

            $property = CIBlockPropertyEnum::GetByID($sale);

            $propertyXmlID = $property['XML_ID'];
            $db = CIBlockElement::GetList(
                array(),
                array('IBLOCK_ID' => 1, 'PROPERTY_DESIGNER' => $arFields['ID']),
                false,
                false,
                array('IBLOCK_ID', 'ID')
            );
            $arIDs = array();
            while ($ob = $db->Fetch()) {
                $res = CIBlockElement::SetPropertyValuesEx($ob['ID'], 1, array('DISCOUNT' => $propertyXmlID));
            }


        }
    }


}


/*AddEventHandler('sale', 'OnOrderUpdate', 'OnOrderUpdate');
function OnOrderUpdate($ID, $arFields){
    ob_start();?>
    <pre>
    /////ID <br>
        <?print_r($ID);?>
    /////arFields <br>
        <?print_r($arFields);?>
    </pre>
    <? $html = ob_get_contents();
     ob_clean();
     ob_end_clean();
    file_put_contents($_SERVER["DOCUMENT_ROOT"] .'/import/text.txt', $html, FILE_APPEND | LOCK_EX);

}*/



/*function ModifySaleMails($orderID, &$eventName, &$arFields) {
    CModule::IncludeModule('iblock');
    CModule::IncludeModule('poisondrop');
    $arOrder = CSaleOrder::GetByID($orderID);
    $arNameplateOptions   = array();
    if ($arOrder) {
        $dbBasket = CSaleBasket::GetList(($b="NAME"), ($o="ASC"), array("ORDER_ID"=>$arOrder["ID"]));
        while ($arBasket = $dbBasket->Fetch()) {
            $arBasket["NAME"] = htmlspecialcharsEx($arBasket["NAME"]);
            $arBasket["QUANTITY"] = DoubleVal($arBasket["QUANTITY"]);
            $arBasket["PRODUCT_ID"] = $arBasket['PRODUCT_ID'];



            if(in_array($arBasket['PRODUCT_ID'],array(7356,7357)))  {
                $dbProp = CSaleBasket::GetPropsList(Array("SORT" => "ASC", "ID" => "ASC"), Array("BASKET_ID" => $arBasket["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")));
                $arProps = array();
                while($arProp = $dbProp->GetNext()) {
                    $arProps[] = $arProp;
                }
                $arNameplateOptions[$arBasket['PRODUCT_ID']] = array('URL'=>CPoisonUtils::getNPURL($arProps),'IMG'=> CPoisonUtils::getNPImg($arProps));
            }



            $arProductsIds[] = $arBasket['PRODUCT_ID'];
            $arOBasket[] = $arBasket;
        }


        $rsContactPerson = CSaleOrderPropsValue::GetList(array(),array('ORDER_ID'=>$arOrder['ID'],'CODE'=>'CONTACT_PERSON'));

        if($contact = $rsContactPerson->Fetch()) {
            if(empty($contact['VALUE'])) {
                if($user = CUser::GetByID($arOrder['USER_ID'])->GetNext()) {
                    $orderuser=$user['NAME'].' '.$user['LAST_NAME'];
                }
            } else
                $orderuser = $contact['VALUE'];
        }

        if($address = CSaleOrderPropsValue::GetList(array(),array('ORDER_ID'=>$arOrder['ID'],'CODE'=>'ADDRESS'))->Fetch()) {
            $address=$address['VALUE'];
        }

        if($phone = CSaleOrderPropsValue::GetList(array(),array('ORDER_ID'=>$arOrder['ID'],'CODE'=>'PHONE'))->Fetch()) {
            $phone=$phone['VALUE'];
        }
        if($comments = CSaleOrderPropsValue::GetList(array(),array('ORDER_ID'=>$arOrder['ID'],'CODE'=>'LOCATION2'))->Fetch()) {
            $comments=$comments['VALUE'];
        }


        if($location = CSaleOrderPropsValue::GetList(array(),array('ORDER_ID'=>$arOrder['ID'],'CODE'=>'LOCATION'))->Fetch()) {
            $arLocs = CSaleLocation::GetByID($location["VALUE"]);
            $country_name =  $arLocs["COUNTRY_NAME_LANG"];
            $city_name = $arLocs["CITY_NAME_LANG"];
        }



        $arFields['ORDER_USER2']=$orderuser;
        $arResult = Array("ORDER" =>$arOrder,"BASKET_ITEMS" =>$arOBasket);

        //$arNameplateImgs = CPoisonUtils::getNameplateImg($orderID);

        if(!empty($arProductsIds)) {
            $rsProducts = CIBlockElement::GetList(array(),array('IBLOCK_TYPE'=>'xmlcatalog','ID'=>$arProductsIds),false,false,array('IBLOCK_ID','ID','PROPERTY_DESIGNER.NAME','PROPERTY_THUMB','DETAIL_PAGE_URL','PROPERTY_CML2_LINK'));
            while($product = $rsProducts->GetNext()) {
                if(!empty($product['PROPERTY_THUMB_VALUE']))
                    $thumb = CFile::GetPath($product['PROPERTY_THUMB_VALUE']);
                else {
                    $thumb = CPoisonUtils::getFirstThumb($product['ID']);
                }
                if($product['IBLOCK_ID']==11 && !empty($product['PROPERTY_CML2_LINK_VALUE'])) {
                    if($designer = CIBlockElement::GetList(array(),array('IBLOCK_TYPE'=>'xmlcatalog','ID'=>$product['PROPERTY_CML2_LINK_VALUE']),false,false,array('PROPERTY_DESIGNER.NAME'))->GetNext()) {
                        $product['PROPERTY_DESIGNER_NAME'] =  $designer['PROPERTY_DESIGNER_NAME'];
                    }
                }
                if(!empty($arNameplateOptions[$product['ID']])) {
                    $thumb = $arNameplateOptions[$product['ID']]['IMG'];
                    $product['DETAIL_PAGE_URL'] =  $arNameplateOptions[$product['ID']]['URL'];
                }


                $arResult['PRODUCTS'][$product['ID']] = array('DESIGNER'=>$product['PROPERTY_DESIGNER_NAME'],'THUMB'=>$thumb,'URL'=>$product['DETAIL_PAGE_URL']);
            }
        }
    }
    $str = '';

    foreach($arResult['BASKET_ITEMS'] as $item) {
        $str.='
        <div style="background:#f1f0eb;margin-bottom:10px;padding:10px;">
         <table width="100%" cellspacing="0">
		<tr>
		 <td width="100" style="padding-right:15px;" valign="top">'.
            ($item['PRODUCT_ID']==4181?'<img src="http://poisondrop.ru/'.$arResult['PRODUCTS'][$item['PRODUCT_ID']]['THUMB'].'" width="80" />':'<a href="http://poisondrop.ru'.$arResult['PRODUCTS'][$item['PRODUCT_ID']]['URL'].'"><img src="http://poisondrop.ru/'.$arResult['PRODUCTS'][$item['PRODUCT_ID']]['THUMB'].'" width="80" /></a>').'
                 </td>
		 <td>
		   <span style="color:#000;font-size:16px"><b>'.$arResult['PRODUCTS'][$item['PRODUCT_ID']]['DESIGNER'].'</b></span><br/>
		   <span style="font-size:14px;color:#84836f">'.
            ($item['PRODUCT_ID']==4181?$item['NAME']:'<a style="color:#84836f;" href="http://poisondrop.ru'.$arResult['PRODUCTS'][$item['PRODUCT_ID']]['URL'].'">'.$item['NAME'].'</a>').'</span><br/><br/>
		   <span style="color: #84836f;">'.$item['QUANTITY'].'шт.</span>
		  </td>
                  <td width="100" align="center">
                   <span style="color:#7a8d3c;font-size:16px">'.intval($item['PRICE'])*intval($item['QUANTITY']).' р.</span>
                  </td>
		</tr>
	      </table>
              </div>
	   ';
    }

    $str.='<div  style="margin-top:20px;">
      <table cellpadding="5" style="float:right">
       <tbody><tr>
       <td align="right" style="color: #808168;"><span style="font-size:14px;">Доставка:</span></td>
       <td><span style="color:#7B9341;font-size:18px;">'.intval($arResult['ORDER']['PRICE_DELIVERY']).' р.</span></td>
      </tr>
      <tr>
        <td align="right" style="color: #808168;font-size:14px;">
	 <span style="font-size:14px;">ИТОГО: </span>
	 </td>
	<td><span style="color:#7B9341;font-size:20px;">'.intval($arResult['ORDER']['PRICE']).' р.</span></td>
      </tr>
      </tbody>
      </table>
      <div style="clear:both"></div>
     </div>' ;

    $arFields['ORDER_LIST'] = $str;
    $arFields['CONTACTS'] = '<div style="background:#d8d8cc;">
  <div style="padding:15px;">
    <span style="color:#71706a;">Контактное лицо: </span>'.$orderuser.'<br>'.
        (!empty($city_name)?'<span style="color:#71706a;">Город: </span>'.$city_name.'<br>':'').
        (!empty($address)?'<span style="color:#71706a;">Адрес: </span>'.$address.'<br>':'').
        '<span style="color:#71706a;">Телефон: </span><span style="color:#000">'.$phone.'</span>'.
        ($comments?'<br><br><span style="color:#71706a;">Комментарий: </span>'.$comments:'').'
   </div>
 </div>';

}*/

?>