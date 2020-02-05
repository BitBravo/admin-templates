/* global jQuery:false */
/* global CARS4RENT_STORAGE:false */


// Theme-specific first load actions
//==============================================
function cars4rent_theme_ready_actions() {
	"use strict";
	// Put here your init code with theme-specific actions
	// It will be called before core actions
	jQuery('.post_info .post_info_posted .post_info_date').each(function() {
		var val = jQuery(this).html().split(',');
		if (val.length > 1) {
			val[val.length-1] = '<span>' + val[val.length-1] + '</span>';
			jQuery(this).html(val.join(','))
		}
	});


	// Comments
	//==============================================
	// Checkbox with "I agree..."
	if (jQuery('input[type="checkbox"][name="i_agree_privacy_policy"]:not(.inited),input[type="checkbox"][name="gdpr_terms"]:not(.inited),input[type="checkbox"][name="wpgdprc"]:not(.inited)').length > 0) {
		jQuery('input[type="checkbox"][name="i_agree_privacy_policy"]:not(.inited),input[type="checkbox"][name="gdpr_terms"]:not(.inited),input[type="checkbox"][name="wpgdprc"]:not(.inited)')
			.addClass('inited')
			.on('change', function(e) {
				if (jQuery(this).get(0).checked)
					jQuery(this).parents('form').find('button,input[type="submit"],a.sc_emailer_button').removeAttr('disabled');
				else
					jQuery(this).parents('form').find('button,input[type="submit"],a.sc_emailer_button').attr('disabled', 'disabled');
			}).trigger('change');
	}


	if (jQuery('.mc4wp-form input[type="checkbox"][name="AGREE_TO_TERMS"]:not(.go)').length > 0) {
		jQuery('.mc4wp-form input[type="checkbox"][name="AGREE_TO_TERMS"]:not(.go)')
			.addClass('go')
			.on('change', function(e) {
				if (jQuery(this).get(0).checked)
					jQuery(this).parents('.mc4wp-form-fields').find('.sc_button').removeAttr('disabled');
				else
					jQuery(this).parents('.mc4wp-form-fields').find('.sc_button').attr('disabled', 'disabled');
			}).trigger('change');
	}


	// CF7 checkboxes and radio - add class to correct check/uncheck pseudoelement when input at right side of the label
	jQuery( '.wpcf7-checkbox > .wpcf7-list-item > .wpcf7-list-item-label,.wpcf7-radio > .wpcf7-list-item > .wpcf7-list-item-label' ).each(
		function() {
			if (jQuery( this ).next( 'input[type="checkbox"],input[type="radio"]' ).length > 0) {
				jQuery( this ).addClass( 'wpcf7-list-item-right' );
			}
		}
	);
	jQuery( '.wpcf7-checkbox > .wpcf7-list-item > .wpcf7-list-item-label,.wpcf7-radio > .wpcf7-list-item > .wpcf7-list-item-label,.wpcf7-wpgdprc > .wpcf7-list-item > .wpcf7-list-item-label' ).on(
		'click', function() {
			var chk = jQuery( this ).siblings( 'input[type="checkbox"],input[type="radio"]' );
			if (chk.attr( 'type' ) == 'radio') {
				jQuery( this ).parents( '.wpcf7-radio' )
					.find( '.wpcf7-list-item-label' ).removeClass( 'wpcf7-list-item-checked' )
					.find( 'input[type="radio"]' ).each(
					function(){
						this.checked = false;
					}
				);
			}
			if (chk.length > 0) {
				chk.get( 0 ).checked = chk.get( 0 ).checked ? false : true;
				jQuery( this ).toggleClass( 'wpcf7-list-item-checked', chk.get( 0 ).checked );
			}
		}
	);


}




// Theme-specific scroll actions
//==============================================
function cars4rent_theme_scroll_actions() {
	"use strict";
	// Put here your theme-specific code with scroll actions
	// It will be called when page is scrolled (before core actions)
}


// Theme-specific resize actions
//==============================================
function cars4rent_theme_resize_actions() {
	"use strict";
	// Put here your theme-specific code with resize actions
	// It will be called when window is resized (before core actions)
}


// Theme-specific shortcodes init
//=====================================================
function cars4rent_theme_sc_init(cont) {
	"use strict";
	// Put here your theme-specific code to init shortcodes
	// It will be called before core init shortcodes
	// @param cont - jQuery-container with shortcodes (init only inside this container)
}


// Theme-specific post-formats init
//=====================================================
function cars4rent_theme_init_post_formats() {
	"use strict";
	// Put here your theme-specific code to init post-formats
	// It will be called before core init post_formats when page is loaded or after 'Load more' or 'Infinite scroll' actions

	// Tribe Events buttons decoration (add 'sc_button' class)
	jQuery('a.tribe-events-read-more,.tribe-events-button,.tribe-events-nav-previous a,.tribe-events-nav-next a,.tribe-events-widget-link a,.tribe-events-viewmore a')
		.addClass('sc_button sc_button_style_filled');

	// All other buttons decoration (add 'hover' class)
	if (CARS4RENT_STORAGE['button_hover'] && CARS4RENT_STORAGE['button_hover']!='default') {
		jQuery('button:not(.search_submit):not([class*="sc_button_hover_"]),\
				.sc_button:not(.sc_button_style_border):not([class*="sc_button_hover_"]),\
				.woocommerce .button:not([class*="sc_button_hover_"]),.woocommerce-page .button:not([class*="sc_button_hover_"]),\
				#buddypress a.button:not([class*="sc_button_hover_"])'
				).addClass('sc_button_hover_'+CARS4RENT_STORAGE['button_hover']);
		if (CARS4RENT_STORAGE['button_hover']!='arrow')
			jQuery('input[type="submit"]:not([class*="sc_button_hover_"]),\
					input[type="button"]:not([class*="sc_button_hover_"]),\
					.isotope_filters_button,\
					.scroll_to_top:not([class*="sc_button_hover_"]),\
					.sc_slider_prev:not([class*="sc_button_hover_"]),.sc_slider_next:not([class*="sc_button_hover_"]),\
					.tagcloud > a:not([class*="sc_button_hover_"])'
					).addClass('sc_button_hover_'+CARS4RENT_STORAGE['button_hover']);
	}

	// Mark field as 'filled' on keypress
	jQuery('[class*="sc_input_hover_"] input, [class*="sc_input_hover_"] textarea').each(function() {
		if (jQuery(this).val()!='')
			jQuery(this).addClass('filled');
		else
			jQuery(this).removeClass('filled');
	});
	jQuery('[class*="sc_input_hover_"] input, [class*="sc_input_hover_"] textarea').on('blur', function() {
		if (jQuery(this).val()!='')
			jQuery(this).addClass('filled');
		else
			jQuery(this).removeClass('filled');
	});
}


// Theme-specific GoogleMap styles
//=====================================================
function cars4rent_theme_googlemap_styles($styles) {
	"use strict";
	// Put here your theme-specific code to add GoogleMap styles
	// It will be called before GoogleMap init when page is loaded
	$styles['greyscale'] = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#686868"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"administrative.country","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"administrative.country","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"administrative.province","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"administrative.province","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"administrative.locality","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#686868"},{"lightness":20}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#828282"}]},{"featureType":"landscape.man_made","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"landscape.natural.landcover","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"transit","elementType":"geometry.fill","stylers":[{"color":"#686868"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#39393b"},{"lightness":"0"}]},{"featureType":"water","elementType":"labels.text","stylers":[{"visibility":"off"}]}];
	$styles['inverse'] = [
		{ "stylers": [
			{ "invert_lightness": true },
			{ "visibility": "on" }
			]
		}
	];
	$styles['simple'] = [
    	{ stylers: [
        	{ hue: "#00ffe6" },
            { saturation: -20 }
			]
		},
		{ featureType: "road",
          elementType: "geometry",
          stylers: [
			{ lightness: 100 },
           	{ visibility: "simplified" }
            ]
		},
		{ featureType: "road",
          elementType: "labels",
          stylers: [
          	{ visibility: "off" }
            ]
		}
	];
	$styles['apple'] = [
		{ "featureType": "landscape.man_made",
		  "elementType": "geometry",
		  "stylers": [
			{"color":"#f7f1df"}
			]
		},
		{ "featureType": "landscape.natural",
		  "elementType": "geometry",
		  "stylers": [
		  	{"color":"#d0e3b4"}
			]
		},
		{ "featureType": "landscape.natural.terrain",
		  "elementType": "geometry",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "poi",
		  "elementType": "labels",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "poi.business",
		  "elementType": "all",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "poi.medical",
		  "elementType": "geometry",
		  "stylers": [
		  	{"color":"#fbd3da"}
			]
		},
		{ "featureType": "poi.park",
		  "elementType": "geometry",
		  "stylers": [
		  	{"color":"#bde6ab"}
			]
		},
		{ "featureType": "road",
		  "elementType": "geometry.stroke",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "road",
		  "elementType": "labels",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "road.highway",
		  "elementType": "geometry.fill",
		  "stylers": [
		  	{"color":"#ffe15f"}
			]
		},
		{ "featureType": "road.highway",
		  "elementType":"geometry.stroke",
		  "stylers": [
		  	{"color":"#efd151"}
		  	]
		},
		{ "featureType": "road.arterial",
		  "elementType": "geometry.fill",
		  "stylers": [
		  	{"color":"#ffffff"}
			]
		},
		{ "featureType": "road.local",
		  "elementType": "geometry.fill",
		  "stylers": [
		  	{"color":"black"}
			]
		},
		{ "featureType": "transit.station.airport",
		  "elementType": "geometry.fill",
		  "stylers": [
		  	{"color":"#cfb2db"}
			]
		},
		{ "featureType": "water",
		  "elementType": "geometry",
		  "stylers": [
		  	{"color":"#a2daf2"}
			]
		}
	];
	return $styles;
}
