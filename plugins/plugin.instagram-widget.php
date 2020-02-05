<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_instagram_widget_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_instagram_widget_theme_setup', 1 );
	function cars4rent_instagram_widget_theme_setup() {
		if (cars4rent_exists_instagram_widget()) {
			add_action( 'cars4rent_action_add_styles', 						'cars4rent_instagram_widget_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',					'cars4rent_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'cars4rent_exists_instagram_widget' ) ) {
	function cars4rent_exists_instagram_widget() {
		return function_exists('wpiw_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_instagram_widget_required_plugins' ) ) {
	function cars4rent_instagram_widget_required_plugins($list=array()) {
		if (in_array('instagram_widget', cars4rent_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('Instagram Widget', 'cars4rent'),
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'cars4rent_instagram_widget_frontend_scripts' ) ) {
	function cars4rent_instagram_widget_frontend_scripts() {
		if (file_exists(cars4rent_get_file_dir('css/plugin.instagram-widget.css')))
			wp_enqueue_style( 'cars4rent-instagram-widget-style',  cars4rent_get_file_url('css/plugin.instagram-widget.css'), array(), null );
	}
}
?>