<?
/*if (!$_GET['sales'] && $arResult['CAN_BUY'] != 'Y') {
    header('Location: http://'.$_SERVER['SERVER_NAME'].$arResult['SECTION']['SECTION_PAGE_URL'].'?sales='.$arResult['ID'].'&clear_cache=Y');
    exit;
}*/
?>

<? if ($arParams['HIDE_TITLE'] != 'Y'): ?>
    <h1 class="b_site_content_bar">
        <b><?= $arResult['NAME'] ?><?= !empty($arResult['DISPLAY_PROPERTIES']['DESIGNER']['VALUE']) ? ' / ' . strip_tags($arResult['DISPLAY_PROPERTIES']['DESIGNER']['DISPLAY_VALUE']) : '' ?></b>
    </h1>
<? endif ?>
<?
    /*if ($arResult['OFFERS']['0']['ID']) {
        $id = $arResult['OFFERS']['0']['ID'];
    } else {*/
        $id = $arResult['ID'];
    //}
?>
<div class="remove_input"></div>
<div class="b_product<? if ($arParams['HIDE_TITLE'] == 'Y'): ?> b_product-embeded<? endif ?> clearfix" itemscope
     itemtype="http://schema.org/Product" data-json='<?= htmlspecialchars($arResult['JS_DATA'], ENT_COMPAT) ?>'>
<div class="b_product_controls<?= !isset($arParams['IS_AJAX']) ? ' b_product_controls-fixed' : '' ?>">
    <? if (isset($arParams['IS_AJAX'])): ?>
        <div class="b_product_controls_close us-none"></div>
    <? endif ?>
    <div class="b_product_controls_share us-none">
        <div class="b_product_share_button"></div>
        <div id="yashare" data-yashareL10n="ru" data-yashareQuickServices="vkontakte,facebook,twitter"
             data-yashareTheme="icon"></div>
    </div>
    <? if ($USER->IsAdmin() || CSite::InGroup(array(1, 19))): ?>
        <a href="/staff/?edit=Y&CODE=<?= $arResult['ID'] ?>" class="b_product_controls_edit"></a>
    <? endif ?>
    <? if ($USER->IsAdmin()): ?>
        <div class="b_product_controls_tags">
            <div class="tag_button"></div>
            <form name="tags_edit" class="edit_tags">
                <ul id="myTags">
                    <? foreach ($arResult['TAGS_LIST']['THIS'] as $tag): ?>
                        <li><?= $tag ?></li>
                    <? endforeach ?>
                </ul>
                <div class="btn_tags_save site_button">Сохранить</div>
                <input type="hidden" name="elementID" value="<?= $arResult['ID'] ?>">
            </form>
        </div>
    <? /*<script src="/js/jquery.min.js"></script>*/ ?>
        <script src="/js/jquery-ui.min.js"></script>
        <script src="/js/tag-it.min.js"></script>
        <style>
            /*Теги*/
            .edit_tags {
                font-family: 'didact_gothicregular', Arial, Tahoma !important;
            }

            .b_product_controls {
                z-index: 1001;
            }

            .remove_input {
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1000;
                display: none;
            }

            .b_product_controls_tags {
                width: 24px;
                height: 29px;
                position: relative;
                margin-top: 20px;
            }

            .tag_button {
                width: 24px;
                height: 29px;
                background: url(/images/tag.png) no-repeat;
                cursor: pointer;
            }

            .edit_tags {
                width: 720px;
                background-color: #25261F;
                vertical-align: top;
                padding: 13px 13px 8px;
                right: 32px;
                top: -20px;
                position: absolute;
                display: none;
            }

            .btn_tags_save {
                display: inline-block;
                background-color: #ed1d6b;
                color: #ffffff;
                vertical-align: top;
            }

            .btn_tags_save:hover {
                background-color: #EC3478;
            }

            #myTags {
                margin: 0;
                display: inline-block;
                min-width: 585px;
                max-width: 585px;
                margin-right: 10px;
            }

            .ui-icon.ui-icon-close {
                background: url(/images/close.png) no-repeat 50% 50% !important;
            }

            .tagit-label {
                color: #ffffff !important;
                font-weight: normal !important;
                padding: 3px 5px 0px 3px !important;
                display: inline-block;
                font-family: 'didact_gothicregular', Arial, Tahoma !important;
            }

            .ui-corner-all {
                font-family: 'didact_gothicregular', Arial, Tahoma !important;
                color: #2a3114 !important;
            }

            .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
                border: none !important;
                border-radius: 0 !important;
                background-image: none !important;
                background-color: #7f9141 !important;
            }

            ul.tagit li.tagit-choice .tagit-close {
                right: 4px !important;
            }

            /* .ui-state-focus {
              background: #7f9141 !important;
              background: none !important;
              border: none !important;
              border-radius: 0 !important;
              color: #2a3114 !important;
              padding: 5px !important;
            } */
        </style>
        <script>

        </script>
    <? endif ?>
    <? if ($USER->IsAdmin() || CSite::InGroup(array(1, 19, 20))): ?>
        <div class="b_product_controls_hd"
             onclick="var w=700, h = 500, left = (screen.width/2)-(w/2); var top = (screen.height/2)-(h/2);window.open('/catalog/gallery.php?galleryId=<?= $arResult['PROPERTIES']['PHOTOGALLERY']['VALUE'] ?>','HD','resizable=yes,scrollbars=yes,width='+w+', height='+h+', top='+top+', left='+left);"></div>
    <? endif ?>
</div>

<div class="b_product_detail">
    <script type="text/javascript">
        window.product_id = '<?=$arResult["ID"]?>';
        if(WL_IsExistProduct(window.product_id)) {
            $(".b_product_addtowishlist").addClass("in_wl");
          }
          else {
            $(".b_product_addtowishlist").removeClass("in_wl");
          }
    </script>
    <div class="b_product_detail_inner">
        <? if ($arResult['PROPERTIES']['EXCLUSIVE']['VALUE'] == 'yes'): ?>
            <span class="b_product_exclusive"><?= GetMessage('EXCLUSIVE') ?></span>
        <? elseif ($arResult['PROPERTIES']['IN_ACTION']['VALUE'] == 'yes'): ?>
            <span class="b_product_inaction"><?= GetMessage('IN_ACTION') ?></span>
        <?endif ?>

        <? if (!empty($arResult['PROPERTIES']['DESIGNER']['VALUE'])): ?>
            <div class="b_product_designer"><?= $arResult['DISPLAY_PROPERTIES']['DESIGNER']['DISPLAY_VALUE'] ?>
                <img class="b_product_designer_popup_show no-mobile" valign="middle" src="/i/i.png?1"/>
            </div>
        <? endif ?>
        <div class="b_product_name" itemprop="name"><?= $arResult['~NAME'] ?></div>

        <div class="b_price_line">
            <div itemprop="b_product_offers" class="b_product_offers" itemscope itemtype="http://schema.org/Offer">
                <? if (is_array($arResult["PRICES"])) {
                    $arPrice = array_shift($arResult["PRICES"]);
                    ?>
                    <meta itemprop="priceCurrency" content="RUB"/>
                    <? if ($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]): ?>
                        <span class="b_product_priceold color_lime"><s><?= $arPrice["PRINT_VALUE_VAT"] ?></s><span
                                class="rubSymbol">a</span></span>
                        <span class="b_product_price color_pink"
                              itemprop="price"><?= $arPrice["PRINT_DISCOUNT_VALUE_VAT"] ?><span
                                class="rubSymbol">a</span></span>
                    <? else: ?>
                        <span class="b_product_price color_lime" itemprop="price"><?= $arPrice["PRINT_VALUE_VAT"] ?>
                            <span class="rubSymbol">a</span></span>
                    <?endif ?>
                <? } ?>

            </div>
            <? if (!empty($arResult['GLOBAL_PRICING'])): ?>
                <div class="b_product_gpricing no-mobile">
                    <div class="b_product_gpricing-title">
                        <img src="<?= SITE_TEMPLATE_PATH ?>/images/global_pricing-n.png"/>

                        <div class="b_product_gpricing-popup htc">
                            <div class="b_product_gpricing-popup_arr"></div>
                            Цена этого украшения не <br>превышает его стоимость <br>в
                            магазинах <?= $arResult['GLOBAL_PRICING']['COUNTRY'] ?>.
                            <? if (!empty($arResult['GLOBAL_PRICING']['HREF'])): ?>
                                <br><a href="<?= $arResult['GLOBAL_PRICING']['HREF'] ?>" target="_blank"
                                       class="b_product_gpricing-popup_check color_pink">Проверить?</a>
                            <? endif ?>
                        </div>

                    </div>

                </div>
            <? endif ?>
        </div>
        <? include('tabs.php'); ?>

        <div class="b_product_detail_bottom">
            <div class="b_product_code"><?= GetMessage('PRODUCT_CODE') ?>
                : <?= str_pad($arResult['PROPERTIES']['PRODUCT_CODE']['VALUE'], 6, 0, STR_PAD_LEFT) ?></div>
            <? if (!empty($arResult['OFFERS'])): ?>
                <? if (count($arResult['OFFERS']) == 1): ?>
                    <?= GetMessage('OFFER_SIZE') ?>: <?= strip_tags($arResult['OFFERS'][0]['DISPLAY_PROPERTIES']['SIZE_RINGS']['DISPLAY_VALUE']) ?>
                <? else: ?>
                    <div class="b_product_offers clearfix">
                        <div class="select_wrap b_product_offerslist">
                            <select class="styled">
                                <option value="0"><?= GetMessage('OFFER_SIZE') ?></option>
                                <? foreach ($arResult['OFFERS'] as $offer): ?>
                                    <option
                                        value="<?= $offer['ID'] ?>"><?= strip_tags($offer['DISPLAY_PROPERTIES']['SIZE_RINGS']['DISPLAY_VALUE']) ?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                        <div class="b_product_findsize_title">Узнать свой размер</div>
                    </div>
                <?endif ?>
                <? $canBuy = true; ?>
            <?
            else:

                //if ($arResult['CAN_BUY'] == 'Y')
                if ($arResult['PROPERTY_IN_ARCHIVE_VALUE'] != 'yes')
                    $canBuy = true;
            endif;
            ?>
            <div class="b_product_buttons clearfix">
                <? if ($canBuy): ?>
                    <?if ($arResult['CAN_BUY'] == 'Y'):?>
                        <div class="b_product_addtocart us-none" onmousedown="try { rrApi.addToBasket(<?=$id?>) } catch(e) {}"
                             onclick="ga('send', 'event', 'user', 'clicked button v korzinu');">В корзину
                        </div>

                    <?endif?>
                    <?if ($arResult['CAN_BUY'] != 'Y'):?>
                        <div class="color_lime" style="display: inline-block; padding: 8px 20px 0 0; float: left">СКОРО</div>
                    <?endif?>
                    <div class="b_product_addtowishlist us-none">В избранное</div>
                <? else: ?>
                    <div class="b_product_sold color_pink" style='line-height:14px'>ПРОДАНО <br />К сожалению этот товар больше <br />не вернется в продажу</div>
                <?endif ?>
            </div>
        </div>
    </div>

</div>


<div class="b_product_thumbs">
    <? foreach ($arResult["MORE_PHOTO"] as $i => $photo): ?>
        <div class="b_product_thumb<? if (!$i): ?> b_product_thumb-active<? endif ?>"
             data-detail-src="<?= $photo['PICTURE'] ?>"
             data-real-src="<?= $photo['REAL'] ?>"><img src="<?= $photo['THUMB'] ?>"/></div>
    <? endforeach ?>
</div>
<div class="b_product_photo">
    <? if ($arResult['PROPERTIES']['LOGO_MAG']['VALUE']): ?>
        <?if ($arResult['PROPERTIES']['LINKLABEL']['VALUE']):?>
            <a href="<?=$arResult['PROPERTIES']['LINKLABEL']['VALUE']?>" style="position:absolute;z-index:5;right:0;width:135px;height:115px;" >
                <img src="<?= $arResult['PROPERTIES']['LOGO_MAG']['VALUE'] ?>" alt="">
            </a>
        <?else:?>
            <img src="<?= $arResult['PROPERTIES']['LOGO_MAG']['VALUE'] ?>"
                 style="position:absolute;z-index:5;right:0;width:135px;height:115px;" alt="">
        <?endif?>
    <? endif ?>
    <div class="image-preloader"></div>
    <? if (is_array($arResult['MORE_PHOTO'])): ?>
        <img class="b_product_photo_img lupa"
             itemprop="image"
             src="<?= $arResult["MORE_PHOTO"][0]['PICTURE'] ?>"
             alt="<?= $arResult["NAME"] ?>"
             title="<?= $arResult["NAME"] ?>"
             data-iddqd="small_image"
             data-zoom-image="<?= $arResult["MORE_PHOTO"][0]['REAL'] ?>"/>
    <? endif; ?>
</div>

<div class="b_product_size_info_modal">
    <div class="b_product_size_info_modal_inner">
        <?= $arResult['DETAIL_SIZE'] ?>
    </div>
    <i class="b_product_size_info_modal_close"></i>
</div>


<div class="b_product_designer_popup  htc">
    <div class="b_product_designer_popup_arr"></div>
    <? if (!empty($arResult['DESIGNER']['PREVIEW_PICTURE'])): ?>
        <img src="<?= $arResult['DESIGNER']['PREVIEW_PICTURE'] ?>" class="b_product_designer_popup-img"/>
    <? endif ?>
    <div>
        <div class="b_product_designer_popup-name"><?= $arResult['DESIGNER']['NAME'] ?></div>
        <?= $arResult['DESIGNER']['PREVIEW_TEXT'] ?>
    </div>

    <div class="b_product_designer_popup-close"><img src="<?= SITE_TEMPLATE_PATH ?>/images/close.png" width="15"/></div>
</div>

</div>
<script type="text/javascript">
    var google_tag_params = {
        ecomm_prodid: <?=$arResult['ID']?>,
        ecomm_pagetype: 'product',
        ecomm_totalvalue: <?if ($arResult['CATALOG_PRICE_1']) {echo $arResult['CATALOG_PRICE_1'];} else {echo $arResult['TRUE_PRICES']['PRICE'];}?>
    };
</script>
<? if ($USER->IsAdmin()): ?>
    <link rel="stylesheet" type="text/css"
          href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
    <link href="/css/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="/css/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<? endif ?>
<meta name="og:image" content="<?= $arResult["MORE_PHOTO"]["0"]["REAL"] ?>"/>
<link rel="image_src" href="<?= $arResult["MORE_PHOTO"]["0"]["REAL"] ?>"/>

<script type="text/javascript">
    rrApiOnReady.push(function() {
      try{ rrApi.view(<?=$id?>); } catch(e) {}
   })
</script>
<?if ($_REQUEST['AJAX_ID'] != 'product'):?>
    <div style="/*position: relative*/"><?$APPLICATION->IncludeComponent("pd:rr.widget", ".default", array('TYPE' => 'DETAIL', 'ID' => $arResult['ID']), false);?></div>
<?endif?>