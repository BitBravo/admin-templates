<?php
/* Calculated fields form support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_calcfields_form_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_calcfields_form_theme_setup', 1 );
	function cars4rent_calcfields_form_theme_setup() {
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',				'cars4rent_calcfields_form_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'cars4rent_exists_calcfields_form' ) ) {
	function cars4rent_exists_calcfields_form() {
		return defined('CP_SCHEME');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_calcfields_form_required_plugins' ) ) {
	function cars4rent_calcfields_form_required_plugins($list=array()) {
		if (in_array('calcfields', cars4rent_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Calculated Fields Form', 'cars4rent'),
					'slug' 		=> 'calculated-fields-form',
					'required' 	=> false
					);
		return $list;
	}
}

// Remove jquery_ui from frontend
if ( !function_exists( 'cars4rent_calcfields_form_frontend_scripts' ) ) {
	function cars4rent_calcfields_form_frontend_scripts() {
		global $wp_styles;
		$wp_styles->done[] = 'cpcff_jquery_ui';
	}
}
?>