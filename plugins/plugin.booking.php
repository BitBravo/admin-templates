<?php
/* Booking Calendar support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_booking_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_booking_theme_setup', 1 );
	function cars4rent_booking_theme_setup() {
		if (cars4rent_exists_booking()) {
			add_action('cars4rent_action_add_styles',					'cars4rent_booking_frontend_scripts');
		}
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',				'cars4rent_booking_required_plugins' );
		}
	}
}


// Check if Booking Calendar installed and activated
if ( !function_exists( 'cars4rent_exists_booking' ) ) {
	function cars4rent_exists_booking() {
		return function_exists('wp_booking_start_session');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_booking_required_plugins' ) ) {
	function cars4rent_booking_required_plugins($list=array()) {
		if (in_array('booking', cars4rent_storage_get('required_plugins'))) {
			$path = cars4rent_get_file_dir('plugins/install/wp-booking-calendar.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Booking Calendar', 'cars4rent'),
					'slug' 		=> 'wp-booking-calendar',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'cars4rent_booking_frontend_scripts' ) ) {
	function cars4rent_booking_frontend_scripts() {
		if (file_exists(cars4rent_get_file_dir('css/plugin.booking.css')))
			wp_enqueue_style( 'cars4rent-booking-style',  cars4rent_get_file_url('css/plugin.booking.css'), array(), null );
	}
}
?>