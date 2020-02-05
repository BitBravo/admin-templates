<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_booked_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_booked_theme_setup', 1 );
	function cars4rent_booked_theme_setup() {
		// Register shortcode in the shortcodes list
		if (cars4rent_exists_booked()) {
			add_action('cars4rent_action_add_styles', 					'cars4rent_booked_frontend_scripts');
		}
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',				'cars4rent_booked_required_plugins' );
		}
	}
}


// Check if plugin installed and activated
if ( !function_exists( 'cars4rent_exists_booked' ) ) {
	function cars4rent_exists_booked() {
		return class_exists('booked_plugin');
	}
}


// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_booked_required_plugins' ) ) {
	//Handler of add_filter('cars4rent_filter_required_plugins',    'cars4rent_booked_required_plugins');
	function cars4rent_booked_required_plugins($list=array()) {
		if (in_array('booked', (array)cars4rent_storage_get('required_plugins'))) {
			$path = cars4rent_get_file_dir('plugins/install/booked.zip');
			if (!empty($path) && file_exists($path)) {
				$list[] = array(
					'name'         => esc_html__('Booked', 'cars4rent'),
					'slug'         => 'booked',
                    'version'  => '2.2.5',
					'source'    => $path,
					'required'     => false
				);
			}
			$path = cars4rent_get_file_dir( 'plugins/install/booked-calendar-feeds.zip' );
			if ( !empty($path) && file_exists($path) ) {
				$list[] = array(
					'name'     => esc_html__( 'Booked Calendar Feeds', 'cars4rent' ),
					'slug'     => 'booked-calendar-feeds',
					'source'   => $path,
					'version'  => '1.1.5',
					'required' => false,
				);
			}
			$path = cars4rent_get_file_dir( 'plugins/install/booked-frontend-agents.zip' );
			if ( !empty($path) && file_exists($path) ) {
				$list[] = array(
					'name'     => esc_html__( 'Booked Front-End Agents', 'cars4rent' ),
					'slug'     => 'booked-frontend-agents',
					'source'   => $path,
					'version'  => '1.1.15',
					'required' => false,
				);
			}
		}
		return $list;
	}
}


// Enqueue custom styles
if ( !function_exists( 'cars4rent_booked_frontend_scripts' ) ) {
	function cars4rent_booked_frontend_scripts() {
		if (file_exists(cars4rent_get_file_dir('css/plugin.booked.css')))
			wp_enqueue_style( 'cars4rent-booked-style',  cars4rent_get_file_url('css/plugin.booked.css'), array(), null );
	}
}

// Lists
//------------------------------------------------------------------------

// Return booked calendars list, prepended inherit (if need)
if ( !function_exists( 'cars4rent_get_list_booked_calendars' ) ) {
	function cars4rent_get_list_booked_calendars($prepend_inherit=false) {
		return cars4rent_exists_booked() ? cars4rent_get_list_terms($prepend_inherit, 'booked_custom_calendars') : array();
	}
}
?>