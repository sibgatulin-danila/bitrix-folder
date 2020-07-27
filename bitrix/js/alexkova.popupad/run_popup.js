var kzncPopup = {
    protectTime: 0,
    delayTime: 1,
    imagesPath: '',
    banners: [],
    showBanners: function () {
        for (var key in this.banners) {
            var banner = this.banners[key];
            this.showBanner(banner);
        }
    },
    showBanner: function (banner) {
        var bannerDelay = banner.INFO.SHOW_TIMER;
        var delayTimeFinal;
        if (bannerDelay)
            delayTimeFinal = parseInt(bannerDelay);
        else
            delayTimeFinal = this.delayTime;
        setTimeout(function () {
                if (banner.INFO.SHOW_TYPE == 'icon') {
                    kzncPopup.showBannerIcon(banner);
                } else {
                    kzncPopup.showBannerFancy(banner);
                }
            },
            delayTimeFinal * 1000
        );
    },
    showBannerIcon: function (banner) {
        var iconParent = jQuery('body');
        var bannerStr = "" +
            "<div " +
            "class='kznc-popup-icon' " +
            "id='kznc_popup_icon_" + banner.ID + "' " +
            "style='" + kzncPopup.getIconStyle(banner) + "'" +
            ">"
            + "<div class='kznc-img-container' style='position: relative;'>";

        if (banner.INFO.ICON_SHOW_IMAGE == "Y") {
            bannerStr += kzncPopup.getCloseIcon(banner);
            if (banner.INFO.TARGET)
                target = " target='" + banner.INFO.TARGET + "' ";
            if (banner.INFO.TITLE)
                title = " title='" + banner.INFO.TITLE + "' ";
            bannerStr += "<img " +
            "onclick='kzncPopup.openId(" + banner.ID + ");return false;' " +
            "width='100%' " +
            "height='100%' " +
            "style='cursor: pointer;' " +
            "src='" + banner.INFO.ICON_FILE.SRC + "'" +
            target + title +
            " />";
        }
        else {
            bannerStr += kzncPopup.getCloseIcon(banner);
            var target = '';
            var title = '';
            if (banner.INFO.TARGET)
                target = " target='" + banner.INFO.TARGET + "' ";
            if (banner.INFO.TITLE)
                title = " title='" + banner.INFO.TITLE + "' ";
            if (banner.URL)
                bannerStr += "<a href='" + banner.URL + "' " + target + title + ">";

            bannerStr += "" +
            "<img " +
            "width='100%' " +
            "height='100%' " +
            "style='cursor: pointer;' " +
            "src='" + banner.INFO.ICON_FILE.SRC + "'" +
            " />";
            if (banner.URL)
                bannerStr += "</a>";
        }

        bannerStr += "</div></div>";
        iconParent.append(bannerStr);

        jQuery('#kznc_popup_icon_' + banner.ID).fadeIn(200);
        var bannerProtectTime = parseInt(banner.INFO.SHOW_PER_TIME);
        if (bannerProtectTime)
            kzncPopup.setCookie('KZNC_BANER_ID_SHOWN_' + banner.ID,
                '1', {expires: bannerProtectTime, path: '/'});
    },
    showBannerFancy: function (banner) {
        kzncPopup.open(banner);
        //protect
        if (this.protectTime) {
            kzncPopup.setCookie('KZNC_PROTECT_BANER_SHOW_TIME', '1', {expires: this.protectTime, path: '/'});
        }
        var bannerProtectTime = parseInt(banner.INFO.SHOW_PER_TIME);
        if (bannerProtectTime)
            kzncPopup.setCookie('KZNC_BANER_ID_SHOWN_' + banner.ID,
                '1', {expires: bannerProtectTime, path: '/'});
    },
    getIconStyle: function (banner) {
        var place = banner.INFO.ICON_PLACE;
        var m1 = parseInt(banner.INFO.ICON_MARGIN1);
        var m1Unit = banner.INFO.ICON_MARGIN1_UNIT;
        var m2 = parseInt(banner.INFO.ICON_MARGIN2);
        var m2Unit = banner.INFO.ICON_MARGIN2_UNIT;
        if (m1Unit == 'pc')m1Unit = '%'; else m1Unit = 'px';
        if (m2Unit == 'pc')m2Unit = '%'; else m2Unit = 'px';
        if (!m1) m1 = 0;
        if (!m2) m2 = 0;
        if (!place) place = 'lt';

        var position;
        switch (place) {
            case 'lt':
                position = "top:" + m1 + m1Unit + "; left:" + m2 + m2Unit + ";";
                break;
            case 'lb':
                position = "bottom:" + m1 + m1Unit + "; left:" + m2 + m2Unit + ";";
                break;
            case 'bl':
                position = "bottom:" + m2 + m2Unit + "; left:" + m1 + m1Unit + ";";
                break;
            case 'br':
                position = "bottom:" + m2 + m2Unit + "; right:" + m1 + m1Unit + ";";
                break;
            case 'rt':
                position = "top:" + m1 + m1Unit + "; right:" + m2 + m2Unit + ";";
                break;
            case 'rb':
                position = "bottom:" + m1 + m1Unit + "; right:" + m2 + m2Unit + ";";
                break;
        }
        ;
        var positionType = 'absolute';
        if (banner.INFO.ICON_FIXED == 'Y')
            positionType = 'fixed';
        var blockWidth = parseInt(banner.INFO.ICON_WIDTH);
        var blockHeight = parseInt(banner.INFO.ICON_HEIGHT);
        var blockZIndex = parseInt(banner.INFO.ICON_Z_INDEX);

        if (!blockZIndex)
            blockZIndex = '1000';
        if (blockWidth)
            blockWidth = 'width:' + blockWidth + 'px;';
        else
            blockWidth = '';
        if (blockHeight)
            blockHeight = 'height:' + blockHeight + 'px;';
        else
            blockHeight = '';
        if (!blockWidth && !blockHeight) {
            blockWidth = 'width:80px;';
            blockHeight = 'height: 80px;';
        }

        return "display:none;z-index: " + blockZIndex + "; " + blockWidth + blockHeight + "position:" + positionType + "; " + position;
    },
    getCloseIcon: function (banner) {
        var showClose = banner.INFO.ICON_SHOW_CLOSE;
        if (showClose != "Y")
            return '';
        var place = banner.INFO.ICON_PLACE;
        var closePosition = 'right';
        if (place == 'br' || place == 'rt' || place == 'rb')
            closePosition = 'left';
        return "<img class='kznc-close-icon'" +
            " style='width: 20px;height: 20px;position: absolute;top: -10px;" + closePosition + ": -10px; cursor:pointer;'" +
            " src='" + this.imagesPath + "/close.png'/>";
    },
    open: function (banner) {
        var bannerHtml = this.prepareHtml(banner);
        if (!bannerHtml)
            return false;
        jQuery.fancybox({
            content: bannerHtml,
            padding: 0,
            scrolling: 'no',
            wrapCSS: 'kzncfancy',
            'overlayShow': true,
            'hideOnOverlayClick': false,
            'showCloseButton': true
        });
    },
    openId: function (id) {
        kzncPopup.open(kzncPopup.banners[id]);
    },
    getCookie: function (name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    },
    setCookie: function (name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires == "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    },
    closeIcon: function (obj) {
        if (jQuery.type(obj) != 'object')
            return false;
        obj.closest('.kznc-popup-icon').fadeOut(200);
    },
    prepareHtml:function(banner){
        return "<div class='kznc-popupad-fancybox-banner' id='kznc-popupad-fancybox-banner" + banner.ID +"'>"+banner.HTML+"</div>"
    }
};
if (window.jQuery) {
    jQuery(document).ready(function() {
        jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: '/bitrix/tools/alexkova.popupad/run_popup.php',
            data: {
                backurl: window.location.pathname
            },
            success: function (data) {
                if (typeof data != "object")
                    return;
                kzncPopup.banners = data.BANNERS;
                kzncPopup.delayTime = +data.OPTIONS.DELAY;
                kzncPopup.protectTime = +data.OPTIONS.PROTECT_TIME;
                kzncPopup.imagesPath = data.IMAGES_PATH
                kzncPopup.showBanners();
            }
        });
        jQuery(document).on('click','.kznc-close-icon', function () {
            kzncPopup.closeIcon(jQuery(this));
        })
    });
}
else {
    console.log('alexkova.popupad: jQuery is not defined');
}