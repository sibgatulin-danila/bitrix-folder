$(function() {	
  $('.b_instagram_items').instagramLite({
    clientID: '14f892b79e7e451395a3a7690a66a71f',
    limit:5,
    username: 'poisondropru',
    urls: true,
    success : function() {
         $(".b_instagram_items li").addClass('b_instagram_item');
          $(".b_instagram_scrollable").scrollable({items:'.b_instagram_items',next:null,prev:null}).navigator({navi:'.b_instagram_nav'});
    }
  });
});