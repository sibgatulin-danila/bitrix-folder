var TNameplateData = Backbone.Model.extend({

    setSelection: function (key, val, toggleVal) {

        var currSelection = _.clone(this.get("SELECTION"));
        if (toggleVal) {
            if (currSelection[key] == val) {
                delete currSelection[key];
            } else
                currSelection[key] = val;

            this.set("SELECTION", currSelection);
            return true;
        } else {
            if (currSelection[key] != val) {
                currSelection[key] = val;

                this.set("SELECTION", currSelection);
                return true;
            }
        }
        return false;
    }
});


var TNameplateView = Backbone.View.extend({
    events: {
        "keyup .b_nameplate_option_text": "changeText",
        "click .b_nameplate_option_metall": "changeMetall",
        "click .b_nameplate_option_font": "changeFont",
        "click .b_nameplate_option_plaiting": "changePlaiting",
        "click .b_nameplate_option_covering": "changeCovering",
        "click .b_nameplate_option_production_time": "changeTimeProduction",
        "click .b_nameplate_add_to_cart": "addToCart",
        "click .b_nameplate_add_to_wish_list": "addToWishList",
        "click .nameplate_share_button": "clickShare",
        "click .b_nameplate_option_metall_question": "showModalMetall",
        "click .b_nameplate_option_bottom": "showModalPlaiting",
        "click .b_nameplate_modal": "closeModal",
        "click": function () {

            this.$(".b_nameplate_modal").hide();
        }
    },
    initialize: function () {
        var self = this;
        this.reloadTime = null;
        this.coveringTemplate = _.template($("#b_covering_tpl").html());
        if (this.$(".b_nameplate_option_text").val() != "")
            this.model.setSelection('TEXT', this.$(".b_nameplate_option_text").val());
        else {
            setTimeout(function () {
                self._showBubble();
            }, 2000);
        }

        this.model.on('change:SELECTION', this.renderImg, this);
        this._renderCovering();
        this.renderImg();


        if (!this.share) {

            this.share = new Ya.share({
                element: 'yashare',
                elementStyle: {
                    'type': 'button',
                    'border': true,
                    'quickServices': ['vkontakte', 'facebook', 'twitter']
                },
                title: 'Именная подвеска по вашему дизайну',
                link: 'http://poisondrop.ru/namenecklaceconstructor/?' + this.getUrlParams(),
                image: 'http://poisondrop.ru/' + this.getImgUrl(),
                description: 'Именная подвеска по вашему дизайну'
            });

        }


    },
    changeText: function (e) {
        this.model.setSelection('TEXT', $(e.currentTarget).val());
        if ($(e.currentTarget).val() == "")
            ;// this._showBubble();
        else
            this._hideBubble();

    },
    changeMetall: function (e) {
        var $currentTarget = $(e.currentTarget);
        this.model.setSelection('COVERING_ID', "");
        if (this.model.setSelection('METALL_ID', +$currentTarget.data('id'))) {
            this._checkOption($currentTarget);
            this._renderCovering();
            if ($currentTarget.data('id') == 10750) {
                $(".b_nameplate_option_covering[data-id=10731]").trigger('click');
            }
        }
    },
    changeFont: function (e) {
        var $currentTarget = $(e.currentTarget);

        if (this.model.setSelection('FONT_ID', +$currentTarget.data('id'))) {
            this._checkOption($currentTarget);
        }
    },
    changePlaiting: function (e) {
        var $currentTarget = $(e.currentTarget);

        if (this.model.setSelection('PLAITING_ID', +$currentTarget.data('id'))) {
            this._checkOption($currentTarget);
        }
    },
    changeCovering: function (e) {
        var $currentTarget = $(e.currentTarget),
            id = $currentTarget.data('id');

        if (this.model.setSelection('COVERING_ID', +$currentTarget.data('id'), this.model.get('SELECTION')['METALL_ID'] == 10750 ? false : true)) {
            this._checkOption($currentTarget, true);
        }

    },
    changeTimeProduction: function (e) {
        var $currentTarget = $(e.currentTarget),
            id = $currentTarget.data('id');
        if (this.model.setSelection('TIME_PRODUCTION', +id)) {
            this._checkOption($currentTarget);
        }
    },
    addToCart: function () {
        var currentSelection = this.model.get('SELECTION'),
            currentMetall = this.model.get('METTALS')[currentSelection.METALL_ID];

        productID = currentMetall.CATALOG_LINK;
        if (this.model.get('SELECTION').TEXT == "") {
            this.$(".b_nameplate_option_text").css({background: '#F8A4C5'});
            setTimeout($.proxy(function () {
                this.$(".b_nameplate_option_text").css({background: ''});
            }, this), 1000);
            return;
        }


        var props = {
            TEXT: {
                "SORT": 10,
                "NAME": "Текст",
                'CODE': 'TEXT',
                'VALUE': this.model.get('SELECTION').TEXT,
                'DISCOUNT_FLAG': $('[name=discount_flag]').val()
            },
            METALL: {"SORT": 20, "NAME": "Металл", 'CODE': 'METALL', 'VALUE': currentMetall.NAME},
            FONT: {
                "SORT": 30,
                "NAME": "Шрифт",
                'CODE': 'FONT',
                'VALUE': this.model.get('FONTS')[currentSelection.FONT_ID].NAME
            },
            PLAITING: {
                "SORT": 40,
                "NAME": "Плетение цепочки",
                'CODE': 'PLAITING',
                'VALUE': this.model.get('PLAITING')[currentSelection.PLAITING_ID].NAME
            },
            TIME_PRODUCTION: {
                "SORT": 45,
                "NAME": "Срок изготовления",
                'CODE': 'TIME_PRODUCTION',
                'VALUE': currentSelection.TIME_PRODUCTION
            },
            METALL_ID: {"SORT": 100, "NAME": "ID Металла", 'CODE': 'METALL_ID', 'VALUE': currentSelection.METALL_ID},
            FONT_ID: {"SORT": 110, "NAME": "ID Шрифта", 'CODE': 'FONT_ID', 'VALUE': currentSelection.FONT_ID},
            PLAITING_ID: {
                "SORT": 120,
                "NAME": "ID Плетения",
                'CODE': 'PLAITING_ID',
                'VALUE': currentSelection.PLAITING_ID
            }
        };

        if (currentSelection.COVERING_ID) {
            props.COVERING = {
                "SORT": 50,
                "NAME": "Покрытие/Цвет",
                'CODE': 'COVERING',
                'VALUE': this.model.get('COVERING')[currentSelection.COVERING_ID].NAME
            };
            props.COVERING_ID = {
                "SORT": 130,
                "NAME": "ID Покрытия/Цвета",
                'CODE': 'COVERING_ID',
                'VALUE': currentSelection.COVERING_ID
            };
        }

        if (isFinite(productID))
            topBasketEvents.trigger('updateBasket', {ID: productID, O: 'ADD_TO_BASKET', BASKET_PROPS: props});
    },
    addToWishList: function (e) {
        var self = this;
        currentSelection = this.model.get('SELECTION'),
            console.log(currentSelection);
            currentMetall = this.model.get('METTALS')[currentSelection.METALL_ID];
        productID = currentMetall.CATALOG_LINK;
        if (this.model.get('SELECTION').TEXT.length > 0) {
            if (!authorized) {
                $(".b_head_content_caption.b_auth_caption").trigger('click');
                return false;
            }

            $.getJSON('/rest/', {
                f: 'add_to_wishlist',
                ID: productID,
                'DATA': this.model.get('SELECTION')
            }, function (response) {
                $(".b_wishlist-counter").text(response.CNT);
                $(".b_nameplate_add_to_wish_list").toggleClass('b_nameplate_inwishlist');
            });
        } else {
            this.$(".b_nameplate_option_text").css({background: '#F8A4C5'});
            setTimeout($.proxy(function () {
                this.$(".b_nameplate_option_text").css({background: ''});
            }, this), 1000);
            return false;
        }
    },
    closeModal: function (e) {
        $(e.currentTarget).hide();
    },
    showModalMetall: function () {
        this.$(".b_nameplate_modal").hide();
        this.$(".b_nameplate_metall-modal").show();
        return false;
    },
    showModalPlaiting: function () {
        this.$(".b_nameplate_modal").hide();
        this.$(".b_nameplate_plaiting-modal").show();
        return false;
    },
    clickShare: function () {
        var self = this;
        $("#yashare").toggleClass('visible');
    },
    _checkOption: function ($target, toggle) {
        if (toggle)
            $target.toggleClass('b_nameplate_option_val-active').siblings().removeClass("b_nameplate_option_val-active");
        else
            $target.addClass('b_nameplate_option_val-active').siblings().removeClass("b_nameplate_option_val-active");
    },
    _renderCovering: function () {

        this.$(".b_nameplate_option_coverings").html(this.coveringTemplate(this.model.toJSON()));
    },
    _showBubble: function () {
        this.$(".b_nameplate_option_text_buble").show();
    },
    _hideBubble: function () {
        this.$(".b_nameplate_option_text_buble").hide();
    },
    renderImg: function () {
        clearTimeout(this.reloadTime);
        this.setPrice();
        if (this.model.get('SELECTION').TEXT.length > 0) {
            this.$("#loaderImage").show();
            this.reloadTime = setTimeout($.proxy(function () {
                this.$(".b_nameplate_preview_img").show().attr("src", this.getImgUrl());
            }, this), 500);


        } else {
            this.$(".b_nameplate_preview_img").hide();
        }

        if (this.share) {
            var src_i = 'http://poisondrop.ru' + this.getImgUrl() + '&scale=1';

            this.share.updateShareLink('http://poisondrop.ru/namenecklaceconstructor/?' + this.getUrlParams(), 'Именная подвеска по вашему дизайну', {
                facebook: {
                    image: src_i
                },
                vkontakte: {
                    image: src_i
                }
            });
        }

    },
    setPrice: function () {
        var selection = this.model.get('SELECTION'),
            price = +this.model.get('METTALS')[selection.METALL_ID].MIN_PRICE,
            full_price = +this.model.get('METTALS')[selection.METALL_ID].FULL_PRICE;
        var discountFlag = $('[name=discount_flag]').val();
        if (selection.METALL_ID == 10743) {

            if (selection.COVERING_ID) {
                if (discountFlag == 'Y') {
                    price += 1200;
                } else {
                    price += 1500;
                }
                full_price += 1500;
            }
        }
        if (selection.TIME_PRODUCTION == 3) {
            if (discountFlag == 'Y') {
                price += 1500;
            } else {
                price += 1500;
            }
            full_price += 1500;
        }
        this.$(".b_nameplate_price_val.discount").text(price);
        this.$(".b_nameplate_price_val.full_price").text(full_price);

    },
    getImgUrl: function () {
        var selection = this.model.get('SELECTION');

        var imgUrl = '/rest/np/img.php?text=' + encodeURIComponent(selection.TEXT) + '&m=' + selection.METALL_ID + '&f=' + selection.FONT_ID + '&p=' + selection.PLAITING_ID + (selection.COVERING_ID ? '&c=' + selection.COVERING_ID : "");
        //$(".bb1").attr('content','http://poisondrop.ru'+imgUrl);
        //$(".bb2").attr('href','http://poisondrop.ru'+imgUrl);
        return imgUrl;
    },
    getUrlParams: function () {
        var selection = this.model.get('SELECTION');
        return 'text=' + encodeURIComponent(selection.TEXT) + '&m=' + selection.METALL_ID + '&f=' + selection.FONT_ID + '&p=' + selection.PLAITING_ID + (selection.COVERING_ID ? '&c=' + selection.COVERING_ID : "");
    }
});


$(function () {
    var $rootEl = $(".b_nameplate_content");
    if ($rootEl.length) {
        new TNameplateView({el: $rootEl, model: new TNameplateData(arResult)});
    }

});