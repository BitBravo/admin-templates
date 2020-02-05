$(document).on('ready', function() { 
 $('.ba-gallery').owlCarousel({
    loop:true,
    margin:0,
    nav:false,
    autoplay: true,
    autoplayTimeout: 5000,
    navText:false,
    dots: false,
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