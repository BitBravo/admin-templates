$(document).on('ready', function() {

    $(".testimonial").owlCarousel({

        navigation: true, // Show next and prev buttons
        slideSpeed: 3000,
        paginationSpeed: 400,
        singleItem: true,
        pagination: false,
        autoPlay: true,
        navigationText: ["<i class=' icon-back-8'></i>", "<i class=' icon-next-11'></i>"],
        addClassActive: true
        

        // "singleItem:true" is a shortcut for:
        // items : 1, 
        // itemsDesktop : false,
        // itemsDesktopSmall : false,
        // itemsTablet: false,
        // itemsMobile : false

    });

});





