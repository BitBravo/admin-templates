$(document).on('ready', function() {
 $('.owl-carousel').owlCarousel({
    loop:true,
    margin:0,
    autoplay: true,
    autoplayTimeout: 5000,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
})


});