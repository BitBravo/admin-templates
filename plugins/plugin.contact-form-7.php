<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('cars4rent_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'cars4rent_cf7_theme_setup9', 9 );
	function cars4rent_cf7_theme_setup9() {

		if (is_admin()) {
			add_filter( 'cars4rent_filter_tgmpa_required_plugins',	'cars4rent_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_cf7_tgmpa_required_plugins' ) ) {
	function cars4rent_cf7_tgmpa_required_plugins($list=array()) {
		if (cars4rent_storage_isset('required_plugins', 'contact-form-7')) {
			// CF7 plugin
			$list[] = array(
					'name' 		=> cars4rent_storage_get_array('required_plugins', 'contact-form-7'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
			);
			// CF7 extension - datepicker
				$params = array(
					'name' 		=> esc_html__('Contact Form 7 Datepicker', 'cars4rent'),
					'slug' 		=> 'contact-form-7-datepicker',
					'required' 	=> false
				);
				$path = cars4rent_get_file_dir('plugins/install/contact-form-7-datepicker.zip');
				if ($path != '')
					$params['source'] = $path;
				$list[] = $params;
		}
		return $list;
	}
}

// Check if cf7 installed and activated
if ( !function_exists( 'cars4rent_exists_cf7' ) ) {
	function cars4rent_exists_cf7() {
		return class_exists('WPCF7');
	}
}

?>