<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_mailchimp_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_mailchimp_theme_setup', 1 );
	function cars4rent_mailchimp_theme_setup() {
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',					'cars4rent_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'cars4rent_exists_mailchimp' ) ) {
	function cars4rent_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_mailchimp_required_plugins' ) ) {
	function cars4rent_mailchimp_required_plugins($list=array()) {
		if (in_array('mailchimp', cars4rent_storage_get('required_plugins')))
			$list[] = array(
				'name' 		=> esc_html__('MailChimp for WP', 'cars4rent'),
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}
?>