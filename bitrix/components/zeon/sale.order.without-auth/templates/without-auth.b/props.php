<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
include('functions.php');
?>

<?//$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/include_areas/sale_breadcrumb.php',array('STEP'=>'DELIVERY'));?>
  <input type="hidden" name="PERSON_TYPE" value="1">
  <input type="hidden" name="PERSON_TYPE_OLD" value="1">
<input type="hidden" id="COUNTRY_ORDER_PROP_2ORDER_PROP_2" name="COUNTRY_ORDER_PROP_2ORDER_PROP_2" value="24">

<input type="hidden" name="PROFILE_ID" id="PROFILE_ID"  value="<?=intval($checkedprofile)?>" />
<div class="confirm_order">
    <div class="order_group">
        <span class="order_title">Личные данные</span>
        <input type="hidden" name="ORDER_PROP_7"/>
        <div class="order_line_item">
            <label for="">Имя</label>
            <input type="text" placeholder="Имя" autocomplete="off" name="FIELD_NAME" class="required"/>
        </div>
        <?/*<div class="order_line_item">
            <label for="">Фамилия</label>
            <input type="text" placeholder="Фамилия" autocomplete="off" name="FIELD_LAST_NAME" class="required"/>
        </div>*/?>
        <div class="order_line_item">
            <label for="">Телефон</label>
            <input name="ORDER_PROP_18" maxlength="255" autocomplete="off" onclick="yaCounter21794221.reachGoal('TEL');" type="text" placeholder="+7 (___) ___-__-__" class="required"/>
        </div>
        <div class="order_line_item">
            <label for="">E-mail</label>
            <input type="text" placeholder="E-mail" autocomplete="off" name="ORDER_PROP_6" class="required"/>
        </div>
    </div>
    <div class="order_group">
        <span class="order_title">Способ доставки</span>
        <div class="radio_line">
            <input type="radio" name="RB_DELIVERY_ID" id="DELIVERY_ID_3" value="3" class="radio_btn" checked="checked"/>
            <label for="DELIVERY_ID_3" class="lbl_for_radio">Курьером</label>
            <input type="radio" name="RB_DELIVERY_ID" id="DELIVERY_ID_4" value="4" class="radio_btn"/>
            <label for="DELIVERY_ID_4" class="lbl_for_radio">Самовывоз</label>
            <input type="hidden" name="DELIVERY_ID" value="3"/>
        </div>
        <div class="order_line_item" id="city_line">
            <label for="">Город</label>
            <input id="ID_CITY" type="text" placeholder="Город" autocomplete="off" name="ORDER_PROP_2" class=""/>
            <?/*<input id="ID_ORDER_PROP_2" type="hidden" name="ORDER_PROP_2" value="0"/>*/?>
        </div>
        <div class="city_error">Выберите город из списка.</div>
        <div class="order_line_item" id="address_line">
            <label for="">Адрес</label>
            <input id="ID_ADDRESS" type="text" placeholder="Адрес" autocomplete="off" name="ADDRESS"/>
            <input type="hidden" autocomplete="off" name="ORDER_PROP_5"/>
        </div>
        <div class="pickup">
            <a href="https://maps.yandex.ru/?um=4oXRWsvTjOByvRrROghl-Zjpv4fdgqV3&l=map&ll=37.615371%2C55.704524&z=18" target="_blank">м. Тульская, Духовской переулок 17с11 Лофт №1</a>
            <br/>
            <br/>
            с 10.00 до 21.00 в рабочие дни,<br/>
            с 12.00 до 20.00 в выходные.
        </div>
        <div class="order_line_item">
            <textarea name="ORDER_PROP_17" autocomplete="off" placeholder="Комментарий"></textarea>
        </div>
    </div>
    <span class="order_title">Способ оплаты</span>
   <div class="radio_line">
       <input type="hidden" name="PAY_SYSTEM_ID"/>
       <input type="radio" name="RB_PAY_SYSTEM_ID" value="1" id="PAY_SYSTEM_ID_1" class="radio_btn" checked="checked"/>
       <label for="PAY_SYSTEM_ID_1" class="lbl_for_radio">Наличными</label>
       <input type="radio" name="RB_PAY_SYSTEM_ID" value="2" id="PAY_SYSTEM_ID_2"  class="radio_btn"/>
       <label for="PAY_SYSTEM_ID_2" class="lbl_for_radio">Картой на сайте</label>
   </div>
    <input type="submit" class="site_button site_button-green" name="submitbutton" id="submitButtonOrder" value="Отправить заказ" onclick="yaCounter21794221.reachGoal('PAY_ORDER'); return true;">
</div>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<?
$locationValue = !empty($value)?$value:$location['ID'];
$bxLocation = CSaleLocation::GetByID($locationValue);

$GLOBALS["APPLICATION"]->IncludeComponent(
    "zeon:sale.locations",
    "withou-auth",
    array(
        "AJAX_CALL" => "N",
        "COUNTRY_INPUT_NAME" => "COUNTRY_".$arProperties["FIELD_NAME"],
        "REGION_INPUT_NAME" => "REGION_".$arProperties["FIELD_NAME"],
        "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
        "CITY_OUT_LOCATION" => "Y",
        'IN_ORDER'=>'Y',
        "LOCATION_VALUE" =>$bxLocation['ID'],
        "ORDER_PROPS_ID" => $arProperties["ID"],
        "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "cityChange();" : "",
    ),
    null,
    array('HIDE_ICONS' => 'Y')
);
?>
<script type="text/javascript" src="/js/jquery-radiobutton-2.0.js"></script>
<?/*<script type="text/javascript" src="/js/tinyvalidation.js"></script>*/?>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>


<!--<div class="title">Куда доставить?</div>
  <div class="b-delivery" id="sof-prof-div" <?/*if($arResult['POST_PARAMS']['profile_change']=='Y' && $arResult['POST_PARAMS']['PROFILE_ID']>0 ):*/?>style="position:absolute;top:-3000px"<?/*endif*/?>>
 <div class="inner">
  <?/*  PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"], '', $arParams,$arResult['POST_PARAMS']);*/?>
 </div>
</div>
<br/>


<div class="title">Кому доставить?</div>
<div class="b-delivery">
 <div class="inner">
<?/* PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"], 'Кому доставить?', $arParams,$arResult['POST_PARAMS']); */?>
 </div>
</div>
<br>
-->