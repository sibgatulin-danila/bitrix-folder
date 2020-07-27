<div class="b_nameplate_w">
    <div class="b_nameplate">
        <div class="b_nameplate_content site_fix_content_center">
            <div class="b_nameplate_options">
                <div class="b_nameplate_option b_nameplate_option-text">
                    <input type="text" class="b_nameplate_option_text" maxlength="10"
                           value="<? if ($arResult['SELECTION']['TEXT']) {
                               echo $arResult['SELECTION']['TEXT'];
                           } else {
                               echo 'Счастье';
                           } ?>"/>

                    <div class="b_nameplate_option_text_buble"></div>
                </div>
                <? /****************************/ ?>
                <div class="b_nameplate_option b_nameplate_option-metall clearfix">
                    <div class="b_nameplate_option_title"><span style="position:relative">Выберите металл<span
                                class="b_nameplate_option_metall_question"></span></span></div>
                    <div class="b_nameplate_option_vals">
                        <?
                        foreach ($arResult['METTALS'] as $id => $metall):?>
                            <?
                            if ($metall['MIN_PRICE_FORMAT'] < $metall['FULL_PRICE']) {
                                $discountFlag = 'Y';
                            }
                            ?>
                            <div
                                class="b_nameplate_option_val b_nameplate_option_metall<? if ($id == $arResult['SELECTION']['METALL_ID']): ?> b_nameplate_option_val-active<? endif ?> us-none"
                                data-id="<?= $id ?>"><?= $metall['NAME'] ?> <span
                                    class="color_lime"><?= $metall['FULL_PRICE'] ?></span></div>
                        <? endforeach ?>
                    </div>
                </div>
                <? /****************************/ ?>
                <? if (!empty($arResult['FONTS'])): ?>
                    <div class="b_nameplate_option b_nameplate_option-font clearfix">
                        <div class="b_nameplate_option_title">Дизайн</div>
                        <div class="b_nameplate_option_vals">
                            <?
                            $i = 0;
                            foreach ($arResult['FONTS'] as $id => $font):?>
                                <div
                                    class="b_nameplate_option_val b_nameplate_option_font<? if ($id == $arResult['SELECTION']['FONT_ID']): ?> b_nameplate_option_val-active<? endif ?><? if ((++$i) % 6 == 0): ?> b_nameplate_option_font_last<? endif ?> us-none"
                                    data-id="<?= $id ?>"><img src="<?= $font['ICON'] ?>" width="36"/></div>
                            <?
                            endforeach?>
                        </div>
                    </div>
                <? endif ?>
                <? /****************************/ ?>
                <? if (!empty($arResult['PLAITING'])): ?>
                    <div class="b_nameplate_option b_nameplate_option-plaiting clearfix">
                        <div class="b_nameplate_option_title">Плетение цепочки <span
                                class="b_nameplate_option_subtitle">(длина 40-45 см)</span></div>
                        <div class="b_nameplate_option_vals">
                            <? foreach ($arResult['PLAITING'] as $id => $plaiting): ?>
                                <div
                                    class="b_nameplate_option_val b_nameplate_option_plaiting<? if ($id == $arResult['SELECTION']['PLAITING_ID']): ?> b_nameplate_option_val-active<? endif ?> us-none"
                                    data-id="<?= $id ?>"><img src="<?= $plaiting['ICON'] ?>"/></div>
                            <? endforeach ?>
                        </div>
                    </div>
                <? endif ?>
                <div class="b_nameplate_metall-modal b_nameplate_modal">
                    Ваша именная подвеска может быть сделана из серебра 925 пробы, либо из золота 585 пробы. На
                    украшении стоит проба, что гарантирует подлинности металла.
                </div>

                <div class="b_nameplate_plaiting-modal b_nameplate_modal">
                    Родиевое покрытие наносится на изделия из серебра для придания украшению блеска и повышения
                    износостойкости.
                    <br><br>
                    <span class="color_pink">Блеск</span><br>
                    Родиевое покрытие придает дополнительный блеск украшению из серебра. Родий никогда не меняет цвет и
                    не тускнеет. Покрытое родием серебряное украшение не окисляется (не темнеет) со временем.
                    <br><br>
                    <span class="color_pink">Износостойкость</span><br>
                    Родий значительно тверже золота, поэтому на нем образуется меньше царапин, а значит, он с меньшим
                    ущербом переносит ежедневное использование.
                    <br><br>
                    <span class="color_pink">Стоимость</span><br>
                    Мы покрываем родием как сам кулон, так и цепочку, что гарантирует неизменный блеск украшения в
                    течение долгого времени. Родий в 10 раз дороже золота, поэтому дополнительное покрытие родием —
                    услуга платная и стоит 1500 руб.
                </div>

                <div class="b_nameplate_option b_nameplate_option_coverings clearfix"></div>
                <div class="b_nameplate_option b_nameplate_option-producttime clearfix">
                    <div class="b_nameplate_price">
                        <? if ($discountFlag == 'Y'): ?>
                            <input type="hidden" name="discount_flag" value="Y"/>
                            <span class="b_nameplate_price_val full_price"></span> <span
                                class="rubSymbol full_price">a</span> <span
                                class="b_nameplate_price_val discount color_pink"></span> <span
                                class="rubSymbol color_pink">a</span>
                        <? else: ?>
                            <span class="b_nameplate_price_val discount"></span> <span class="rubSymbol">a</span>
                        <?endif ?>
                    </div>
                    <div class="b_nameplate_add_to_cart">В корзину</div>
                    <div
                        class="b_nameplate_add_to_wish_list<? if ($arResult['IN_WISHLIST'] == 'Y'): ?> b_nameplate_inwishlist<? endif ?>">В избранное</div>
                    <div class="b_nameplate_option_title" style="text-align:center;margin-top: 10px;">Срок изготовления:
                        5 рабочих дней
                    </div>
                    <div class="b_nameplate_option_vals">
                        <!-- <div style="width:100%" class="b_nameplate_option_val b_nameplate_option_production_time <? if ($arResult['SELECTION']['TIME_PRODUCTION'] == 10): ?> b_nameplate_option_val-active<? endif ?> us-none" data-id="10">5 рабочих дней</div> -->
                        <!-- <div class="b_nameplate_option_val b_nameplate_option_production_time<? if ($arResult['SELECTION']['TIME_PRODUCTION'] == 3): ?> b_nameplate_option_val-active<? endif ?> us-none" data-id="3">3 дня<span class="color_lime"> +1500<span class="rubSymbol">a</span></span> </div> -->
                    </div>
                </div>
            </div>

            <div class="b_nameplate_preview b_nameplate_preview-green ">
                <img class="b_nameplate_preview_img" src="" onload="$('#loaderImage').hide()" style="display: none;"/>

                <div id="loaderImage"></div>
            </div>

            <div class="b_nameplate_buttons">
                <!--         <div class="b_nameplate_price"><span class="b_nameplate_price_val"></span> <span class="rubSymbol">a</span></div>
       <div class="b_nameplate_add_to_cart"></div>
       <div class="b_nameplate_add_to_wish_list<? if ($arResult['IN_WISHLIST'] == 'Y'): ?> b_nameplate_inwishlist<? endif ?>"></div>   -->
                <div class="b_namplate_href">
                    <a href="/quality/" class="tdu" target="_blank">Качество</a><br>
                    <a href="/reviews/" class="tdu" target="_blank">Отзывы</a>
                </div>
            </div>

            <!--      <div class="nameplate_share">

                     <div class="nameplate_share_text">Сомневаетесь?<br>Спросите у друзей!</div>

                     <div class="nameplate_share_button"></div>
                     <div id="yashare" style="margin-top:14px;float:left;border:1px solid #5c6057;height:34px;" data-yashareL10n="ru" data-yashareQuickServices="vkontakte,facebook,twitter" data-yashareTheme="icon"></div>
                     </div> -->

        </div>

    </div>


</div>
<div class="clear"></div>
<div class="b_nameplate_info">
    <?= $arResult['TEXT'] ?>
</div>
<script>
    var arResult = <?=CUtil::PhpToJSObject($arResult)?>;
</script>
<script type="text/template" id="b_covering_tpl">
    <%
    var
    selectedMetall = METTALS[SELECTION . METALL_ID];
 console . log(arResult);
 %>
    <div class="b_nameplate_option_title">
        <% if (selectedMetall . NAME == 'Серебро') { %>
            Добавить покрытие <span class="b_nameplate_option_title-price_color">+1500<span
                    class="rubSymbol">a</span></span>
        <% } else if (selectedMetall . NAME == 'Золото') { %>
            Цвет золота
        <% } %>
    </div>
    <div class="b_nameplate_option_vals ">
        <%


        _ . each(selectedMetall . OFFERS, function (offer) {

            if (offer['PLAITING']['VALUE'] == SELECTION['PLAITING_ID']) {

                %>
                <div style="width:50%"
                     class="b_nameplate_option_val b_nameplate_option_covering<% if (offer . COVERING . VALUE == SELECTION['COVERING_ID']) { %>  b_nameplate_option_val-active<% }%>  us-none"
                     data-id="<%= offer . COVERING . VALUE %>"><%= offer . COVERING . DISPLAY_VALUE %></div>
            <%
            }
 });

        if (selectedMetall . NAME == 'Серебро') {
            %>
            <div class="clear"></div>
            <div class="b_nameplate_option_bottom">Зачем покрывать серебро родием?</div>
        <% } %>
    </div>


</script>

<script>
    var cSpeed = 10;
    var cTotalFrames = 12;
    var cFrameWidth = 32;
    var cImageSrc = '/i/nameplateloader_sprites.png';

    var cImageTimeout = false;
    var cIndex = 0;
    var cXpos = 0;
    var cPreloaderTimeout = false;
    var SECONDS_BETWEEN_FRAMES = 0;
    function startAnimation() {
        document.getElementById('loaderImage').style.backgroundImage = 'url(' + cImageSrc + ')';
        FPS = Math.round(100 / cSpeed);
        SECONDS_BETWEEN_FRAMES = 1 / FPS;
        cPreloaderTimeout = setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES / 1000);

    }

    function continueAnimation() {

        cXpos += cFrameWidth;
        cIndex += 1;
        if (cIndex >= cTotalFrames) {
            cXpos = 0;
            cIndex = 0;
        }

        if (document.getElementById('loaderImage'))
            document.getElementById('loaderImage').style.backgroundPosition = (-cXpos) + 'px 0';

        cPreloaderTimeout = setTimeout('continueAnimation()', SECONDS_BETWEEN_FRAMES * 1000);
    }


    function imageLoader(s, fun) {
        clearTimeout(cImageTimeout);
        cImageTimeout = 0;
        genImage = new Image();
        genImage.onload = function () {
            cImageTimeout = setTimeout(fun, 0)
        };
        genImage.onerror = new Function('alert(\'Could not load the image\')');
        genImage.src = s;
    }
    new imageLoader(cImageSrc, 'startAnimation()');
</script>
<div style="display:none">
    <img src="/i/nameplate/fon-green.jpg" style="display:none;">
    <img src="/i/nameplate/fon-red.jpg" style="display:none;">
    <img src="/i/nameplate/fon-grey.jpg" style="display:none;">
    <img src="/i/nameplate/fon-blue.jpg" style="display:none;">
</div>

<script type="text/javascript">
    $('document').ready(function () {
        $('.b_nameplate_option_text').click(function () {
            if ($('.b_nameplate_option_text').val() == 'Счастье') {
                $('.b_nameplate_option_text').val('');
            }
        });
    });
</script>


