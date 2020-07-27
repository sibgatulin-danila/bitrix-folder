<?if (count($arResult['ITEMS']) == 0) echo 'К сожалению по вашему запросу ничего не найдено!';?>
<? if (!empty($arParams['PAGER_TITLE']) && count($arResult["ITEMS"]) > 0): ?>
    <div class="b_site_content_bar"><b><?= $arParams['PAGER_TITLE'] ?></b></div>
<? endif ?>
<div class="b_catalog_section" data-href="<?= $arResult['SECTION_PAGE_URL'] ?>">
    <?
    $c = 0;
    if ($_GET['last'] > 0) {
        $c = intval($_GET['last']);
    }
    $afterLast = 0;
    $count = count($arResult["ITEMS"]);
    foreach ($arResult["ITEMS"] as $cell => $arElement):

        $arElement["DETAIL_PAGE_URL"] = $arParams['PREF'] . $arElement["DETAIL_PAGE_URL"];
        $galleryid = $arElement['PROPERTIES']['PHOTOGALLERY']['VALUE'];
        $row = intval($c / 3) + 1;

        if (isset($arResult['BANNER']) && $arResult['BANNER']['LOC'] == 'left')
            if ($row == $arResult['BANNER']['ROW_NUM']):
                $c += 2;
                ?>
                <div class="section_banner section_banner_left">
                    <a href="<?= $arResult['BANNER']['URL'] ?>"><img src="<?= $arResult['BANNER']['PIC'] ?>"></a><br>
                    <a href="<?= $arResult['BANNER']['URL'] ?>"
                       class="section_banner_name"><?= $arResult['BANNER']['NAME'] ?></a>
                </div>
            <? endif ?>
        <?
        $afterLast++;
        if (($c + 1) % 3 == 0) {
            $afterLast = 0;
            $lastItemCount = ($count + 1) - $c;
        }

        ?>
        <div class="b_product_item<? if ($arElement['ACTIVE'] != 'Y'): ?> b_product_item-noactive<? endif ?><? if ($arElement['IS_NAMEPLATE'] == 'Y'): ?> b_product_item-nameplate<? endif ?>"
             data-json="<?= $arElement['ID'] ?>" <? if ((($c + 1) % 3 == 0) && ($lastItemCount < 3)): ?> data-last="<?= $lastItemCount ?>"<? endif ?>>

            <div class="b_product_item_inner">
                <? if ($arElement['PROPERTIES']['LOGO_MAG']['VALUE']): ?>
                    <?if ($arElement['PROPERTIES']['LINKLABEL']['VALUE']):?>
                        <a href="<?=$arElement['PROPERTIES']['LINKLABEL']['VALUE']?>" style="position:absolute;z-index:2; width:135px;height:115px; right:14px;" class="zhurnal_img">
                            <img src="<?= $arElement['PROPERTIES']['LOGO_MAG']['VALUE'] ?>" alt="">
                        </a>
                    <?else:?>
                        <img src="<?= $arElement['PROPERTIES']['LOGO_MAG']['VALUE'] ?>" alt=""
                             style="position:absolute;z-index:2; width:135px;height:115px; right:14px;" class="zhurnal_img">
                    <?endif?>
                <? endif; ?>
                <div
                    class="b_product_item_thumb<? if (!empty($arElement['LAST_IMG'])): ?> b_product_item_thumb-two<? endif ?>">
                    <?if ($arElement['TRUE_CAN_BUY'] = 'Y' && $arElement['CAN_BUY'] && $arElement['CATALOG_QUANTITY'] >= 0):?>
                        <div class="wish_logo" data-id="<?=$arElement['ID']?>"></div>
                    <?endif;?>
                    <script type="text/javascript">
                        window.product_id_el = '<?=$arElement["ID"]?>';
                        if(WL_IsExistProduct(window.product_id_el)) {
                            $(".wish_logo[data-id=<?=$arElement['ID']?>]").addClass("active");
                        }
                        else {
                            $(".wish_logo[data-id=<?=$arElement['ID']?>]").removeClass("active");
                        }
                    </script>
                    <a href="<?= $arElement["DETAIL_PAGE_URL"] ?>">
                        <img class="b_product_item_thumb_first" src="<?= $arElement["PREVIEW_PICTURE"]["SRC"] ?>" alt=""
                             title="<?= $arElement["NAME"] ?>"/>
                        <? if (!empty($arElement['LAST_IMG'])): ?>
                            <img class="b_product_item_thumb_last" src="<?= $arElement['LAST_IMG'] ?>" alt=""
                                 title="<?= $arElement["NAME"] ?>"/>
                        <? endif ?>
                    </a>
                    <?/*<a href="/catalog/kole/svinka_v_chernoy_kruzhevnoy_yubke_so_zvezdoy/">
                        <img class="b_product_item_thumb_first" src="/upload/iblock/60b/60b27688e13dbe31ab4bcc4262044f69.jpg" alt="" title="Свинка в черной кружевной юбке, со звездой">
                        <img class="b_product_item_thumb_last" src="/upload/iblock/3e9/3e948b3efa0bbb98d0f4dbf10254e387.jpg" alt="" title="Свинка в черной кружевной юбке, со звездой">
                    </a>*/?>
                </div>
                <? if ($arElement['PROPERTIES']['EXCLUSIVE']['VALUE'] == 'yes'): ?>
                    <div class="b_product_item_exclusive">эксклюзив</div>
                <? elseif ($arElement['PROPERTIES']['IN_ACTION']['VALUE'] == 'yes'): ?>
                    <span class="b_product_inaction">акция</span>
                <?endif ?>
                <div class="b_product_item_brand color_dark-green"><a class="b_product_item_name" href="<?= $arElement["DETAIL_PAGE_URL"] ?>"><?=$arElement['DISPLAY_PROPERTIES']['DESIGNER']['LINK_ELEMENT_VALUE'][$arElement['DISPLAY_PROPERTIES']['DESIGNER']['VALUE']]['NAME']?></a></div>
                <a href="<?= $arElement["DETAIL_PAGE_URL"] ?>"
                   class="b_product_item_name color_dark-gray-ligher"><?= $arElement["NAME"] ?></a>
                <? if (($arElement["PRICES"][PRICE_TYPE]["DISCOUNT_VALUE"] < $arElement["PRICES"][PRICE_TYPE]["VALUE"]) || ($arElement['TRUE_PRICES']['PRICE_DISCOUNT'] < $arElement['TRUE_PRICES']['PRICE'])): ?>
                    <div class="b_product_item_price color_lime">
                        <span
                            class="b_product_item_oldprice color_lime">
                            <?
                            if ($arElement['TRUE_PRICES']['PRICE'] /*&& $arElement['TRUE_CAN_BUY'] == 'Y'*/) {
                                echo $arElement['TRUE_PRICES']['PRICE'];
                            } else {
                                echo $arElement["PRICES"][PRICE_TYPE]['PRINT_VALUE_VAT'];
                            }
                            ?>
                            <span class="rubSymbol">a</span></span>
                        <span
                            class="b_product_item_price color_pink">
                            <?
                            if ($arElement['TRUE_PRICES']['PRICE_DISCOUNT'] /*&& $arElement['TRUE_CAN_BUY'] == 'Y'*/) {
                                echo $arElement['TRUE_PRICES']['PRICE_DISCOUNT'];
                            } else {
                                echo $arElement["PRICES"][PRICE_TYPE]['PRINT_DISCOUNT_VALUE_VAT'];
                            }
                            ?>

                            <span class="rubSymbol">a</span></span>
                    </div>
                <? else: ?>
                    <div class="b_product_item_price color_lime">
                        <span>
                            <?if ($arElement['TRUE_PRICES']['PRICE'] /*&& $arElement['TRUE_CAN_BUY'] == 'Y'*/) {
                                echo $arElement['TRUE_PRICES']['PRICE'];
                            } else {
                                echo $arElement["PRICES"][PRICE_TYPE]['PRINT_VALUE_VAT'];
                            } ?>
                            <span class="rubSymbol">a</span></span>
                    </div>
                <?endif ?>
                <?
                $IBLOCK_ID = 1;
                $ID = $arElement['ID'];
                $arInfo = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);
                if (is_array($arInfo)) {
                    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "CATALOG_QUANTITY");
                    $rsOffers = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arInfo['IBLOCK_ID'], 'PROPERTY_' . $arInfo['SKU_PROPERTY_ID'] => $ID), false, false, $arSelect);
                    while ($arOffer = $rsOffers->GetNext()) {
                        $arElement['CATALOG_QUANTITY_PRED'] = $arOffer['CATALOG_QUANTITY'];
                    }
                }
                ?>
                <?
                if ($arElement['PROPERTY_IN_ARCHIVE_VALUE'] != 'yes') {
                    $message = 'СКОРО';
                } else {
                    $message = 'ПРОДАНО';
                }
                ?>
                <?if ($arElement['IS_OFFER'] == 'Y'):?>
                    <? if ($arElement['CATALOG_QUANTITY_PRED'] <= "0" && $arElement['CATALOG_QUANTITY'] <= "0" && $arElement['TRUE_CAN_BUY'] != 'Y' && !$arElement['CAN_BUY']): ?>
                        <div class="b_product_item_sold color_lime"><?=$message?></div>
                    <? endif ?>
                <?else:?>
                    <? if ($arElement['CATALOG_QUANTITY'] <= 0): ?>
                        <div class="b_product_item_sold color_lime"><?=$message?></div>
                    <? endif ?>
                <?endif?>
                <?/*if ($_SERVER['REMOTE_ADDR'] == '37.235.132.223'):?>
                    <?=$arElement["PRICES"][PRICE_TYPE]["DISCOUNT_VALUE"]?><br>
                    <?=$arElement["PRICES"][PRICE_TYPE]["VALUE"]?><br>
                    <?=$arElement['TRUE_CAN_BUY']?><br>
                    <?=$arElement['CAN_BUY']?><br>
                    <?if ($arElement['TRUE_PRICES']['PRICE_DISCOUNT'] < $arElement['TRUE_PRICES']['PRICE']): ?>
                    <<<<
                    <?endif?>
                <?endif*/?>
            </div>
        </div>

        <? if (isset($arResult['BANNER']) && $arResult['BANNER']['LOC'] == 'right'): ?>
        <? if ($row == $arResult['BANNER']['ROW_NUM']): ?>
            <? $c += 2; ?>
            <div class="section_banner" style="margin-right:0">
                <a href="<?= $arResult['BANNER']['URL'] ?>"><img src="<?= $arResult['BANNER']['PIC'] ?>"></a><br>
                <a href="<?= $arResult['BANNER']['URL'] ?>"
                   class="section_banner_name"><?= $arResult['BANNER']['NAME'] ?></a>
            </div>
        <? endif ?>
    <? endif ?>
        <?
        $c++;
        ?>
    <? endforeach; ?>
    <div class="clear"></div>
    <div class="page-navigation">
        <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <br/><?= $arResult["NAV_STRING"] ?>
        <? endif; ?>
    </div>
</div>

<div style="display: none">
    <img src="/i/preloader-15-5.gif"/>
</div>

<script type="text/javascript">
    rrApiOnReady.push(function() {
        try { rrApi.categoryView(<?=$arResult['ID']?>); } catch(e) {}
    })
</script>

<script>

</script>

