<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_essgrids_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_essgrids_theme_setup', 1 );
	function cars4rent_essgrids_theme_setup() {
		// Register shortcode in the shortcodes list
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',				'cars4rent_essgrids_required_plugins' );
		}
	}
}


// Check if Ess. Grid installed and activated
if ( !function_exists( 'cars4rent_exists_essgrids' ) ) {
	function cars4rent_exists_essgrids() {
		return defined('EG_PLUGIN_PATH');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_essgrids_required_plugins' ) ) {
    //Handler of add_filter('cars4rent_filter_required_plugins',	'cars4rent_essgrids_required_plugins');
	function cars4rent_essgrids_required_plugins($list=array()) {
		if (in_array('essgrids', cars4rent_storage_get('required_plugins'))) {
			$path = cars4rent_get_file_dir('plugins/install/essential-grid.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Essential Grid', 'cars4rent'),
					'slug' 		=> 'essential-grid',
                    'version'  => '2.3.2',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}
?>