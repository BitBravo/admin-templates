$(document).on('ready', function() {
    $("#datepicker").datepicker({
        //showOn: "button",
        //buttonImage: "images/calendar.gif",
        //buttonImageOnly: true,
        //buttonText: "Select date"
    });
    $('#ui-datepicker-div').before('<div class="default-skin"></div>');
    $('#ui-datepicker-div').appendTo('.default-skin').contents();
});
