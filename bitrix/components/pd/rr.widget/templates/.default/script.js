$(document).ready(function() {
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
    $('.recommended_item  .b_product_addtocart').on('click', function() {
        var ID = $(this).data('id');
        var QUANTITY = 1;
        basketEvents.trigger("refreshBasket",{O:'ADD_TO_BASKET','ID':ID,'VAL':QUANTITY});
    });
});