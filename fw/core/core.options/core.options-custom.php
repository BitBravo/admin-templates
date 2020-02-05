<?php
/**
 * Cars4Rent Framework: Theme options custom fields
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'cars4rent_options_custom_theme_setup' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_options_custom_theme_setup' );
	function cars4rent_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'cars4rent_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'cars4rent_options_custom_load_scripts' ) ) {
	function cars4rent_options_custom_load_scripts() {
		wp_enqueue_script( 'cars4rent-options-custom-script',	cars4rent_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'cars4rent_show_custom_field' ) ) {
	function cars4rent_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(cars4rent_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager cars4rent_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'cars4rent') : esc_html__( 'Choose Image', 'cars4rent')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'cars4rent') : esc_html__( 'Choose Image', 'cars4rent')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'cars4rent') : esc_html__( 'Choose Image', 'cars4rent')) . '</a>';
				break;
		}
		return apply_filters('cars4rent_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>