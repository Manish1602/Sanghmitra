jQuery(document).ready(function(){

  var Swipes = new Swiper('.swiper-container', {
    loop: true,
    autoHeight: true, 
    speed: 1000,
    
    loop: true,
    autoplay:1000,
    autoplayDisableOnInteraction:false,
    
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
    },
  });


});