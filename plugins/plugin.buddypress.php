<?php
/* BuddyPress support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_buddypress_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_buddypress_theme_setup', 1 );
	function cars4rent_buddypress_theme_setup() {
		if (cars4rent_exists_buddypress()) {
			// Add custom styles for Buddy & BBPress
			add_action( 'cars4rent_action_add_styles', 				'cars4rent_buddypress_frontend_scripts' );
		}
		if (cars4rent_is_buddypress_page()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('cars4rent_filter_get_blog_type',				'cars4rent_buddypress_get_blog_type', 9, 2);
			add_filter('cars4rent_filter_get_blog_title',			'cars4rent_buddypress_get_blog_title', 9, 2);
			add_filter('cars4rent_filter_get_stream_page_title',		'cars4rent_buddypress_get_stream_page_title', 9, 2);
			add_filter('cars4rent_filter_get_stream_page_link',		'cars4rent_buddypress_get_stream_page_link', 9, 2);
			add_filter('cars4rent_filter_get_stream_page_id',		'cars4rent_buddypress_get_stream_page_id', 9, 2);
			add_filter('cars4rent_filter_detect_inheritance_key',	'cars4rent_buddypress_detect_inheritance_key', 9, 1);
		}
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',				'cars4rent_buddypress_required_plugins' );
		}
	}
}
if ( !function_exists( 'cars4rent_buddypress_settings_theme_setup2' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_buddypress_settings_theme_setup2', 3 );
	function cars4rent_buddypress_settings_theme_setup2() {
		if (cars4rent_exists_buddypress()) {
			cars4rent_add_theme_inheritance( array('buddypress' => array(
				'stream_template' => 'buddypress',
				'single_template' => '',
				'taxonomy' => array(),
				'taxonomy_tags' => array(),
				'post_type' => array('forum', 'topic', 'reply'),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if BuddyPress and/or BBPress installed and activated
if ( !function_exists( 'cars4rent_exists_buddypress' ) ) {
	function cars4rent_exists_buddypress() {
		return class_exists( 'BuddyPress' ) || class_exists( 'bbPress' );
	}
}

// Check if current page is BuddyPress and/or BBPress page
if ( !function_exists( 'cars4rent_is_buddypress_page' ) ) {
	function cars4rent_is_buddypress_page() {
		$is = false;
		if ( cars4rent_exists_buddypress() ) {
			$is = in_array(cars4rent_storage_get('page_template'), array('buddypress'));
			if (!$is && cars4rent_storage_empty('pre_query') )
				$is = (function_exists('is_buddypress') && is_buddypress())
						||
						(function_exists('is_bbpress') && is_bbpress());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'cars4rent_buddypress_detect_inheritance_key' ) ) {
	function cars4rent_buddypress_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return cars4rent_is_buddypress_page() ? 'buddypress' : $key;
	}
}

// Filter to detect current page slug
if ( !function_exists( 'cars4rent_buddypress_get_blog_type' ) ) {
	function cars4rent_buddypress_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->get('post_type')=='forum' || get_query_var('post_type')=='forum')
			$page = 'buddypress_forum';
		else if ($query && $query->get('post_type')=='topic' || get_query_var('post_type')=='topic')
			$page = 'buddypress_topic';
		else if ($query && $query->get('post_type')=='reply' || get_query_var('post_type')=='reply')
			$page = 'buddypress_reply';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'cars4rent_buddypress_get_blog_title' ) ) {
	function cars4rent_buddypress_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( cars4rent_strpos($page, 'buddypress')!==false ) {
			if ( $page == 'buddypress_forum' || $page == 'buddypress_topic' || $page == 'buddypress_reply' ) {
				$title = cars4rent_get_post_title();
			} else {
				$title = esc_html__('Forums', 'cars4rent');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'cars4rent_buddypress_get_stream_page_title' ) ) {
	function cars4rent_buddypress_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (cars4rent_strpos($page, 'buddypress')!==false) {
			// Page exists at root slug path, so use its permalink
			$page = bbp_get_page_by_path( bbp_get_root_slug() );
			if ( !empty( $page ) )
				$title = get_the_title( $page->ID );
			else
				$title = esc_html__('Forums', 'cars4rent');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'cars4rent_buddypress_get_stream_page_id' ) ) {
	function cars4rent_buddypress_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (cars4rent_strpos($page, 'buddypress')!==false) {
			// Page exists at root slug path, so use its permalink
			$page = bbp_get_page_by_path( bbp_get_root_slug() );
			if ( !empty( $page ) ) $id = $page->ID;
		}
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'cars4rent_buddypress_get_stream_page_link' ) ) {
	function cars4rent_buddypress_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (cars4rent_strpos($page, 'buddypress')!==false) {
			// Page exists at root slug path, so use its permalink
			$page = bbp_get_page_by_path( bbp_get_root_slug() );
			if ( !empty( $page ) )
				$url = get_permalink( $page->ID );
			else
				$url = get_post_type_archive_link( bbp_get_forum_post_type() );
		}
		return $url;
	}
}


// Enqueue BuddyPress and/or BBPress custom styles
if ( !function_exists( 'cars4rent_buddypress_frontend_scripts' ) ) {
	function cars4rent_buddypress_frontend_scripts() {
		if (file_exists(cars4rent_get_file_dir('css/plugin.buddypress.css')))
			wp_enqueue_style( 'cars4rent-buddypress-style',  cars4rent_get_file_url('css/plugin.buddypress.css'), array(), null );
	}
}


// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_buddypress_required_plugins' ) ) {
	function cars4rent_buddypress_required_plugins($list=array()) {
		if (in_array('buddypress', cars4rent_storage_get('required_plugins'))) {
			$list[] = array(
					'name' 		=> 'BuddyPress',
					'slug' 		=> 'buddypress',
					'required' 	=> false
					);
			$list[] = array(
					'name' 		=> 'bbPress',
					'slug' 		=> 'bbpress',
					'required' 	=> false
					);
		}
		return $list;
	}
}
?>