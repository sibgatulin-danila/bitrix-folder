var TProductListItem = Backbone.Model.extend({});

var TProductListItemView = Backbone.View.extend({
    events: {
        "touchstart": "touchStart",
        "touchmove": "touchMove",
        "click .b_product_item_thumb a": "showProductDetail",
        "click .recommended_item a": "showProductDetail",
        "touchend .b_product_item_thumb a": "showProductDetail",
        // "touchstart .b_product_item_thumb a":"startTouchDetail",
        "click .b_product_item_name": "showProductDetail",
        "click .wish_logo": "addToWishListNew",
        "touchend .b_product_item_name": "showProductDetail"
        //"mouseenter .b_product_item_thumb": "showWish",
        //"mouseleave .b_product_item_thumb": "hideWish"
        //  "touchstart .b_product_item_name":"startTouchDetail",
        // "touchend .b_product_item_name":"endTouchDetail"
    },
    initialize: function () {
        this.isTouchStartMove = false;
        this.detail_loading = false;
        this._sectionLocation = location.href;
    },
    touchStart: function () {
        this.isTouchStartMove = false;
    },
    touchMove: function () {
        this.isTouchStartMove = true;
    },
    startTouchDetail: function (e) {
        this.startPosY = $(document).scrollTop();
    },
    endTouchDetail: function (e) {
        if ($(document).scrollTop() - this.startPosY < 30) {
            setTimeout($.proxy(function () {
                this.showProductDetail();
            }, this), 100);
        }
        return false;
    },
    showWish: function(e) {
        var wish = $(this.el).find('.wish_logo');
        //     $(wish).show();
        // console.log(w);
    },
    hideWish: function(e) {
        var wish = $(this.el).find('.wish_logo');
        //var wishAc = $(this.el).find('.wish_logo.active');
        // $(wish).hide();
        //$(wishAc).css('display','block');
        // console.log(w);
    },
    addToWishListNew:function(e) {
        var id = $(this.el).find('.wish_logo').data('id');

        if(WL_IsExistProduct(id)) {
            WL_Delete(id);
            $(".wish_logo[data-id="+id+"]").removeClass("active");
        } else {
            function WL_Add(product_id)
            {
                $wlc = getCookie('wishlist');
                if($wlc==undefined) {
                    $wlc = '';
                }
                $ids = $wlc.split('|');
                $ids.push(id);
                $ids = $ids.join('|');
                deleteCookie('wishlist');
                setCookie('wishlist',$ids, {path: '/'});
                WL_Counter();
            }
            WL_Add(id);
            $(".wish_logo[data-id="+id+"]").addClass("active");
        }

    },
    showProductDetail: function (e) {
        if (this.isTouchStartMove)
            return false;

        if (this.detail_loading)
            return false;

        if (this.$el.hasClass("b_product_item-nameplate"))
            return true;



        this.detail_loading = true;
        var self = this;
        var $thisEl = this.$el;
        var href = this.$(".b_product_item_name").attr('href'),
            elTop = this.$el.offset().top,
            elLeft = this.$el.offset().left,
            $elInsertBefore = null,
            $elDetail = null;
        var currentRec = $('.currentRec').attr('href');

        $(window).on('popstate', function(e) {
            self.closeProductDetail();
        });

        if (currentRec !== undefined) {
            var currentID = $('.currentRec').data('recommendation');
            href = currentRec;
            $('.currentRec').removeClass('currentRec');
        } else {
            var currentID = this.$(".b_product_item_name").parents('.b_product_item').data('json');
        }
        if ($(".b_product_item_detail").length) {
            $elDetail = $(".b_product_item_detail");
        } else
            $elDetail = $('<div class="b_product_item_detail">\
                              <div  class="b_product_item_detail_shadowbar-top"></div><div  class="b_product_item_detail_shadowbar-bottom"></div>\
                               <div class="b_product_item_detail_content">\
                               </div>\
                               </div>');

        this.detailView = $elDetail;
        this.$el.nextAll('.b_product_item, .section_banner').each(function () {
            if ($(this).offset().top > elTop) {
                $elInsertBefore = $(this);
                return false;
            }
        });

        $(".product_item_loader").remove();
        $thisEl.append('<div class="product_item_loader"></div>');
        if (currentRec === undefined) {
            var isiPad = navigator.userAgent.match(/iPad/i) != null;
            $.scrollTo($thisEl.offset().top + 360, 500, {
                axis: 'y', onAfter: function () {
                    $.get(href + '?AJAX_ID=product', function (html) {
                        if ($elInsertBefore)
                            $elInsertBefore.before($elDetail);
                        else
                            $(".b_product_item:last", $thisEl.parent()).after($elDetail);


                        $(".b_product_item-open").not($thisEl).removeClass("b_product_item-open").find(".b_product_item_detail_pointer").remove();
                        $thisEl.append('<div class="b_product_item_detail_pointer"></div>').addClass("b_product_item-open");
                        $(".product_item_loader").remove();

                        $elDetail.find(".b_product_item_detail_content").html(html).css({
                            'height': 0,
                            'overflow': 'hidden',
                            'z-index': 1,
                        }).animate({height: '875px'}, 400, function () {
                            $(this).css({'overflow': 'visible'});
                        });
                        $.scrollTo($thisEl.offset().top + 360, 0, {axis: 'y'});

                        var $b_product = $(".b_product", $elDetail);
                        var productItem = new ProductItem($b_product.data('json'));
                        var productItemView = new ProductItemView({el: $b_product, model: productItem});
                        if (window.yaCounter21794221 && typeof window.yaCounter21794221.hit == "function") {
                            window.yaCounter21794221.hit(productItem.get("DETAIL_URL"), productItem.get("NAME"), '');
                        }

                        if (ga && typeof ga == "function") {

                            ga('send', 'pageview', {
                                'page': productItem.get("DETAIL_URL"),
                                'title': productItem.get("NAME")
                            });
                        }

                        productItemView.on("doClose", self.closeProductDetail, self);

                        if (cuSel)
                            cuSel({changedEl: ".styled", visRows: 13, scrollArrows: true});
                        try {
                            history.pushState(null, null, href);
                        } catch (e) {
                        }
                        self.detail_loading = false;
                        $.ajax({
                            url: '/catalog/recommendation.php',
                            method: 'get',
                            dataTypes: 'html',
                            data: {'ID': currentID},
                            success: function(html){
                                $('.b_product').after(html);
                                $('.recommended_list').owlCarousel({
                                        'items': 5,
                                        'navigation': true,
                                        'scrollPerPage': true,
                                        'navigationText': false,
                                        'itemsCustom': [[0,2],[480,3],[720,4],[960,5]],
                                        'pagination': true,
                                        'slideSpeed': 1000
                                    }
                                );

                                if (isiPad) {
                                    $('.rec_ajax').css('overflow', 'hidden');
                                }


                                var TRecListItemView = Backbone.View.extend({
                                    events: {
                                        "touchstart": "touchStart",
                                        "touchmove": "touchMove",
                                        "click .recommended_item a": "alert"
                                    },
                                    alert: function() {
                                        alert(321);
                                    }
                                });
                                $('.recommended_item a').on('click', function(e) {
                                    e.preventDefault();
                                    $(this).addClass('currentRec');
                                    var rec = new TProductListItemView;
                                    rec.showProductDetail();
                                });
                            }
                        });
                    });

                }
            });
        } else {
            $.get(href + '?AJAX_ID=product', function (html) {
                $(".product_item_loader").remove();
                $('.b_product').remove();

                /*$elDetail.find(".b_product_item_detail_content").html(html);*/
                $('.rec_ajax').before(html);
                var curTop = $('.b_product_item-open').offset().top;
                var body = $("html, body");
                $(body).animate({scrollTop:curTop + 370}, '500', 'swing');

                var $b_product = $(".b_product", $elDetail);
                var productItem = new ProductItem($b_product.data('json'));
                var productItemView = new ProductItemView({el: $b_product, model: productItem});
                if (window.yaCounter21794221 && typeof window.yaCounter21794221.hit == "function") {
                    window.yaCounter21794221.hit(productItem.get("DETAIL_URL"), productItem.get("NAME"), '');
                }

                if (ga && typeof ga == "function") {
                    console.log(productItem.get("DETAIL_URL"));
                    console.log(productItem.get("NAME"));
                    ga('send', 'pageview', {
                        'page': productItem.get("DETAIL_URL"),
                        'title': productItem.get("NAME")
                    });
                }

                productItemView.on("doClose", self.closeProductDetail, self);

                if (cuSel)
                    cuSel({changedEl: ".styled", visRows: 13, scrollArrows: true});
                try {
                    history.pushState(null, null, href);
                } catch (e) {
                }
                self.detail_loading = false;

            });
        }

        return false;
    },
    closeProductDetail: function () {
        if (this.detailView) {
            this.detailView.find(".b_product_item_detail_content").css({'overflow': 'hidden'}).animate({'height': 0}, 300, $.proxy(function () {
                //this.$el.removeClass("b_product_item-open");
                $('.b_product_item-open').removeClass('b_product_item-open');
                this.detailView.remove();

                if (this._sectionLocation) {
                    try {
                        history.pushState(null, null, this._sectionLocation);
                    } catch (e) {
                    }
                }
            }, this));
        }
    }
});


function refreshProduct() {

}

var ua = navigator.userAgent.toLowerCase();

var isOpera = (ua.indexOf('opera') > -1);
var isIE = (!isOpera && ua.indexOf('msie') > -1);

var loadproducts = 0;
var flagPopup = $.cookie("flagPopup");

function getDocumentHeight() {
    return Math.max(document.compatMode != 'CSS1Compat' ? document.body.scrollHeight : document.documentElement.scrollHeight, getViewportHeight());
}

function getViewportHeight() {
    return ((document.compatMode || isIE) && !isOpera) ? (document.compatMode == 'CSS1Compat') ? document.documentElement.clientHeight : document.body.clientHeight : (document.parentWindow || document.defaultView).innerHeight;
}
var scrollPos = 0;

var documentHeight = 0;

var lastHeigh = 0;

var page = 1;

var baselineFlag = false;

var percents = [25, 50, 75, 100];

var part1 = false;
var part2 = false;
var part3 = false;
var part4 = false;


function getPart(lastHeight, currentHeight) {
    var height = currentHeight + 450 - lastHeight - $(window).height();
    var stepLength = height / 4;
    var arLengths = [stepLength + lastHeight, stepLength * 2 + lastHeight, stepLength * 3 + lastHeigh, height];
    return arLengths;
}

$(document).ready(function () {
    documentHeight = getDocumentHeight();
    $(window).scroll(function () {
        scrollPos = $(window).scrollTop();
        if (baselineFlag == false) {
            baselineFlag = true;
            ga('send', 'event', 'scroll depth', 'page' + page, 'Baseline');
            ga('testTracker.send', 'event', 'scroll depth', 'page' + page, 'Baseline');
        }
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            if (part4 == false) {
                ga('send', 'event', 'scroll depth', 'page' + page, '100%');
                ga('testTracker.send', 'event', 'scroll depth', 'page' + page, '100%');
                part4 = true;
            }
        }
        if (baselineFlag == true) {
            var arLengths = getPart(lastHeigh, documentHeight);
            for (i = 0; i < arLengths.length; i++) {
                switch (i) {
                    case 0:
                        if (parseInt(scrollPos) >= parseInt(arLengths[i]) && part1 == false) {
                            ga('send', 'event', 'scroll depth', 'page' + page, '25%');
                            ga('testTracker.send', 'event', 'scroll depth', 'page' + page, '25%');
                            part1 = true;
                            break;
                        }
                        break;
                    case 1:
                        if (parseInt(scrollPos) >= parseInt(arLengths[i]) && part2 == false) {
                            ga('send', 'event', 'scroll depth', 'page' + page, '50%');
                            ga('testTracker.send', 'event', 'scroll depth', 'page' + page, '50%');
                            part2 = true;
                            break;
                        }
                        break;
                    case 2:
                        if (parseInt(scrollPos) >= parseInt(arLengths[i]) && part3 == false) {
                            ga('send', 'event', 'scroll depth', 'page' + page, '75%');
                            ga('testTracker.send', 'event', 'scroll depth', 'page' + page, '75%');
                            part3 = true;
                            break;
                        }
                        break;
                }
            }
        }
    });
});



$(function () {

    function modifyNextLink() {
        var last = $('.b_product_item[data-last]').data('last');
        var link = $('.nav_next');
        var href = link.attr('href');
        href = href + '&last=' + last;
        link.attr('href', href);
        loadproducts++;
        if (loadproducts == 3 && flagPopup != 'Y') {
            $('[name=new_subscr]').val('scroll');
            $('.b_subscribe_popup_shadow').fadeIn(1000);
            $('.b_subscribe_popup').fadeIn(1000);
            $.cookie("flagPopup", 'Y', {expires: 7, path: '/'});
        }
    }

    modifyNextLink();

    if (!window.Mobi) {
        $.ias({
            container: ".b_catalog_section",
            item: ".b_product_item, .b_product_item_detail",
            pagination: ".blog-page-navigation",
            next: ".nav_next",
            triggerPageThreshold: 100,
            thresholdMargin: -450,
            loader: "<img class='page_nav_loader' src='/i/ajaxloader.gif'/>",
            history: false,
            onRenderComplete: function (items) {
                _.each(items, function (item) {
                    new TProductListItemView({el: item, model: new TProductListItem($(this).data('json'))});
                });
                if (part4 != true) {
                    ga('send', 'event', 'scroll depth', 'page' + page, '100%');
                    ga('testTracker.send', 'event', 'scroll depth', 'page' + page, '100%');
                    part4 = true;
                }
                modifyNextLink();
                baselineFlag = false;
                lastHeigh = $(window).scrollTop();
                page++;
                part1 = false;
                part2 = false;
                part3 = false;
                part4 = false;
                documentHeight = getDocumentHeight();
            }
        });
    }

    $(".b_product_item").each(function () {
        new TProductListItemView({el: this, model: new TProductListItem($(this).data('json'))});
    });


});
