/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var AppCustom = function () {

    // Pickadate picker
    var _componentPickadate = function() {
        if (!$().pickadate) {
            console.warn('Warning - picker.js and/or picker.date.js is not loaded.');
            return;
        }

        // Basic options
        $('.date-picker').pickadate();
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentPickadate();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    AppCustom.init();
});
