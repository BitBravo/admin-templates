<?php
/* Gutenberg support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_gutenberg_theme_setup')) {
    add_action( 'cars4rent_action_before_init_theme', 'cars4rent_gutenberg_theme_setup', 1 );
    function cars4rent_gutenberg_theme_setup() {
        if (is_admin()) {
            add_filter( 'cars4rent_filter_required_plugins', 'cars4rent_gutenberg_required_plugins' );
        }
    }
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'cars4rent_exists_gutenberg' ) ) {
    function cars4rent_exists_gutenberg() {
        return function_exists( 'the_gutenberg_project' ) && function_exists( 'register_block_type' );
    }
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_gutenberg_required_plugins' ) ) {
    function cars4rent_gutenberg_required_plugins($list=array()) {
        if (in_array('gutenberg', (array)cars4rent_storage_get('required_plugins')))
            $list[] = array(
                    'name'         => esc_html__('Gutenberg', 'cars4rent'),
                    'slug'         => 'gutenberg',
                    'required'     => false
                );
        return $list;
    }
}