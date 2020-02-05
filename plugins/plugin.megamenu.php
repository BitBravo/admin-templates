<?php
/* Mega Main Menu support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_megamenu_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_megamenu_theme_setup', 1 );
	function cars4rent_megamenu_theme_setup() {
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',					'cars4rent_megamenu_required_plugins' );
		}
	}
}

// Check if MegaMenu installed and activated
if ( !function_exists( 'cars4rent_exists_megamenu' ) ) {
	function cars4rent_exists_megamenu() {
		return class_exists('mega_main_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_megamenu_required_plugins' ) ) {
	function cars4rent_megamenu_required_plugins($list=array()) {
		if (in_array('mega_main_menu', cars4rent_storage_get('required_plugins'))) {
			$path = cars4rent_get_file_dir('plugins/install/mega_main_menu.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Mega Main Menu', 'cars4rent'),
					'slug' 		=> 'mega_main_menu',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}
?>