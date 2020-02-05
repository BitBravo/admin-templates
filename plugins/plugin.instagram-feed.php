<?php
/* Instagram Feed support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_instagram_feed_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_instagram_feed_theme_setup', 1 );
	function cars4rent_instagram_feed_theme_setup() {
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',					'cars4rent_instagram_feed_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'cars4rent_exists_instagram_feed' ) ) {
	function cars4rent_exists_instagram_feed() {
		return defined('SBIVER');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_instagram_feed_required_plugins' ) ) {
	function cars4rent_instagram_feed_required_plugins($list=array()) {
		if (in_array('instagram_feed', cars4rent_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Feed', 'cars4rent'),
					'slug' 		=> 'instagram-feed',
					'required' 	=> false
				);
		return $list;
	}
}
?>