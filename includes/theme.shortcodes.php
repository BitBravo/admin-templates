<?php
if (!function_exists('cars4rent_theme_shortcodes_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_theme_shortcodes_setup', 1 );
	function cars4rent_theme_shortcodes_setup() {
		add_filter('cars4rent_filter_googlemap_styles', 'cars4rent_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'cars4rent_theme_shortcodes_googlemap_styles' ) ) {
	function cars4rent_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'cars4rent');
		$list['greyscale']	= esc_html__('Greyscale', 'cars4rent');
		$list['inverse']	= esc_html__('Inverse', 'cars4rent');
		$list['apple']		= esc_html__('Apple', 'cars4rent');
		return $list;
	}
}
?>