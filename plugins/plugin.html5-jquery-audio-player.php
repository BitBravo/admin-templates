<?php
/* HTML5 jQuery Audio Player support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_html5_jquery_audio_player_theme_setup')) {
    add_action( 'cars4rent_action_before_init_theme', 'cars4rent_html5_jquery_audio_player_theme_setup' );
    function cars4rent_html5_jquery_audio_player_theme_setup() {
        if (cars4rent_exists_html5_jquery_audio_player()) {
			add_action('cars4rent_action_add_styles',					'cars4rent_html5_jquery_audio_player_frontend_scripts' );
        }
        if (is_admin()) {
            add_filter( 'cars4rent_filter_required_plugins',				'cars4rent_html5_jquery_audio_player_required_plugins' );
        }
    }
}

// Check if plugin installed and activated
if ( !function_exists( 'cars4rent_exists_html5_jquery_audio_player' ) ) {
	function cars4rent_exists_html5_jquery_audio_player() {
		return function_exists('hmp_db_create');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_html5_jquery_audio_player_required_plugins' ) ) {
	function cars4rent_html5_jquery_audio_player_required_plugins($list=array()) {
		if (in_array('html5_jquery_audio_player', cars4rent_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('HTML5 jQuery Audio Player', 'cars4rent'),
					'slug' 		=> 'html5-jquery-audio-player',
					'required' 	=> false
				);
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'cars4rent_html5_jquery_audio_player_frontend_scripts' ) ) {
	function cars4rent_html5_jquery_audio_player_frontend_scripts() {
		if (file_exists(cars4rent_get_file_dir('css/plugin.html5-jquery-audio-player.css'))) {
			wp_enqueue_style( 'cars4rent-html5-jquery-audio-player-style',  cars4rent_get_file_url('css/plugin.html5-jquery-audio-player.css'), array(), null );
		}
	}
}
?>