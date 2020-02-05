// JavaScript Document

$(document).on('ready', function() {
    "use strict";
    if ($(window).width() >= "768") {
        $(".header, .header-1").sticky({ topSpacing: 0 });
    }

});
