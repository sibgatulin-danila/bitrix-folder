<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?if ($arParams['TYPE'] == 'DETAIL'):?>
    <link rel="stylesheet" href="/js/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="<?=$this->GetFolder().'/style.css'?>">
    <script src="/js/owl-carousel/owl.carousel.js"></script>
    <script src="<?=$this->GetFolder().'/script.js'?>"></script>
<?endif?>
<?if (count($arResult['ITEMS']) > 0):?>
    <div class="rec_title">
        <div class="title_line"></div>
        <span><?=$arResult['TITLE']?></span>
    </div>
    <div class="recommended_list">
        <?$counter = 0?>
        <?foreach ($arResult['ITEMS'] as $item):?>
            <?if ($counter ==60) break;?>
            <div class="recommended_item <?if($counter == 5) echo 'last_rec'?>">
                <a data-recommendation="<?=$item['ID']?>" class="rec_pic" href="<?=$item['URL']?>" onmousedown="try { rrApi.recomMouseDown(<?=$item['ID']?>, {methodName: <?=$arResult['ACTION']?>}) } catch(e) {}"><img src="<?=$item['PIC']?>" alt=""/></a>
                <a data-recommendation="<?=$item['ID']?>" class="rec_brand" href="<?=$item['URL']?>" onmousedown="try { rrApi.recomMouseDown(<?=$item['ID']?>, {methodName: <?=$arResult['ACTION']?>}) } catch(e) {}"><?=$item['DESIGNER']?></a>
                <a data-recommendation="<?=$item['ID']?>" class="rec_link" href="<?=$item['URL']?>" onmousedown="try { rrApi.recomMouseDown(<?=$item['ID']?>, {methodName: <?=$arResult['ACTION']?>}) } catch(e) {}"><?=$item['NAME']?></a>
                <div class="rec_prices">
                    <?if ($item['DISCOUNT'] == 'Y'):?>
                        <span class="rec_old_price"><?=$item['PRICE_FORMATED']?><span class="rubSymbol">a</span></span>
                        <span class="rec_new_price"><?=$item['DISCOUNT_PRICE_FORMATED']?><span class="rubSymbol">a</span></span>
                    <?else:?>
                        <span class="rec_price"><?=$item['PRICE_FORMATED']?><span class="rubSymbol">a</span></span>
                    <?endif?>
                </div>
                <?if ($arParams['TYPE'] == 'BASKET'):?>
                    <div class="b_product_addtocart us-none" data-id="<?=$item['ID']?>" onmousedown="try { rrApi.addToBasket(<?=$item['ID']?>, {methodName: 'RelatedItems'}) } catch(e) {}" onclick="ga('send', 'event', 'user', 'clicked button v korzinu');">В корзину</div>
                <?endif?>
            </div>
            <?$counter++?>
        <?endforeach?>
    </div>
<?endif?>
<input type="hidden" name="count_recs" value="<?=count($arResult['ITEMS'])?>">
<?if ($arParams['TYPE'] == 'DETAIL' && count($arResult['ITEMS']) <= 0):?>
    <div style="/*position: relative*/"><?$APPLICATION->IncludeComponent("pd:rr.widget", ".default", array('TYPE' => 'MAIN'), false);?></div>
<?endif?>