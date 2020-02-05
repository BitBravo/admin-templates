<?php
/* Donations support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('cars4rent_trx_donations_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_trx_donations_theme_setup', 1 );
	function cars4rent_trx_donations_theme_setup() {

		// Register shortcode in the shortcodes list
		if (cars4rent_exists_trx_donations()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('cars4rent_filter_get_blog_type',			'cars4rent_trx_donations_get_blog_type', 9, 2);
			add_filter('cars4rent_filter_get_blog_title',		'cars4rent_trx_donations_get_blog_title', 9, 2);
			add_filter('cars4rent_filter_get_current_taxonomy',	'cars4rent_trx_donations_get_current_taxonomy', 9, 2);
			add_filter('cars4rent_filter_is_taxonomy',			'cars4rent_trx_donations_is_taxonomy', 9, 2);
			add_filter('cars4rent_filter_get_stream_page_title',	'cars4rent_trx_donations_get_stream_page_title', 9, 2);
			add_filter('cars4rent_filter_get_stream_page_link',	'cars4rent_trx_donations_get_stream_page_link', 9, 2);
			add_filter('cars4rent_filter_get_stream_page_id',	'cars4rent_trx_donations_get_stream_page_id', 9, 2);
			add_filter('cars4rent_filter_query_add_filters',		'cars4rent_trx_donations_query_add_filters', 9, 2);
			add_filter('cars4rent_filter_detect_inheritance_key','cars4rent_trx_donations_detect_inheritance_key', 9, 1);
			add_filter('cars4rent_filter_list_post_types',		'cars4rent_trx_donations_list_post_types');
		}
		if (is_admin()) {
			add_filter( 'cars4rent_filter_required_plugins',				'cars4rent_trx_donations_required_plugins' );
		}
	}
}

if ( !function_exists( 'cars4rent_trx_donations_settings_theme_setup2' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_trx_donations_settings_theme_setup2', 3 );
	function cars4rent_trx_donations_settings_theme_setup2() {
		// Add Donations post type and taxonomy into theme inheritance list
		if (cars4rent_exists_trx_donations()) {
			cars4rent_add_theme_inheritance( array('donations' => array(
				'stream_template' => 'blog-donations',
				'single_template' => 'single-donation',
				'taxonomy' => array(TRX_DONATIONS::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(TRX_DONATIONS::POST_TYPE),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if Donations installed and activated
if ( !function_exists( 'cars4rent_exists_trx_donations' ) ) {
	function cars4rent_exists_trx_donations() {
		return class_exists('TRX_DONATIONS');
	}
}


// Return true, if current page is donations page
if ( !function_exists( 'cars4rent_is_trx_donations_page' ) ) {
	function cars4rent_is_trx_donations_page() {
		$is = false;
		if (cars4rent_exists_trx_donations()) {
			$is = in_array(cars4rent_storage_get('page_template'), array('blog-donations', 'single-donation'));
			if (!$is) {
				if (!cars4rent_storage_empty('pre_query'))
					$is = (cars4rent_storage_call_obj_method('pre_query', 'is_single') && cars4rent_storage_call_obj_method('pre_query', 'get', 'post_type') == TRX_DONATIONS::POST_TYPE) 
							|| cars4rent_storage_call_obj_method('pre_query', 'is_post_type_archive', TRX_DONATIONS::POST_TYPE) 
							|| cars4rent_storage_call_obj_method('pre_query', 'is_tax', TRX_DONATIONS::TAXONOMY);
				else
					$is = (is_single() && get_query_var('post_type') == TRX_DONATIONS::POST_TYPE) 
							|| is_post_type_archive(TRX_DONATIONS::POST_TYPE) 
							|| is_tax(TRX_DONATIONS::TAXONOMY);
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'cars4rent_trx_donations_detect_inheritance_key' ) ) {
	function cars4rent_trx_donations_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return cars4rent_is_trx_donations_page() ? 'donations' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'cars4rent_trx_donations_get_blog_type' ) ) {
	function cars4rent_trx_donations_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax(TRX_DONATIONS::TAXONOMY) || is_tax(TRX_DONATIONS::TAXONOMY))
			$page = 'donations_category';
		else if ($query && $query->get('post_type')==TRX_DONATIONS::POST_TYPE || get_query_var('post_type')==TRX_DONATIONS::POST_TYPE)
			$page = $query && $query->is_single() || is_single() ? 'donations_item' : 'donations';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'cars4rent_trx_donations_get_blog_title' ) ) {
	function cars4rent_trx_donations_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( cars4rent_strpos($page, 'donations')!==false ) {
			if ( $page == 'donations_category' ) {
				$term = get_term_by( 'slug', get_query_var( TRX_DONATIONS::TAXONOMY ), TRX_DONATIONS::TAXONOMY, OBJECT);
				$title = $term->name;
			} else if ( $page == 'donations_item' ) {
				$title = cars4rent_get_post_title();
			} else {
				$title = esc_html__('All donations', 'cars4rent');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'cars4rent_trx_donations_get_stream_page_title' ) ) {
	function cars4rent_trx_donations_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (cars4rent_strpos($page, 'donations')!==false) {
			if (($page_id = cars4rent_trx_donations_get_stream_page_id(0, $page=='donations' ? 'blog-donations' : $page)) > 0)
				$title = cars4rent_get_post_title($page_id);
			else
				$title = esc_html__('All donations', 'cars4rent');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'cars4rent_trx_donations_get_stream_page_id' ) ) {
	function cars4rent_trx_donations_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (cars4rent_strpos($page, 'donations')!==false) $id = cars4rent_get_template_page_id('blog-donations');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'cars4rent_trx_donations_get_stream_page_link' ) ) {
	function cars4rent_trx_donations_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (cars4rent_strpos($page, 'donations')!==false) {
			$id = cars4rent_get_template_page_id('blog-donations');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'cars4rent_trx_donations_get_current_taxonomy' ) ) {
	function cars4rent_trx_donations_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( cars4rent_strpos($page, 'donations')!==false ) {
			$tax = TRX_DONATIONS::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'cars4rent_trx_donations_is_taxonomy' ) ) {
	function cars4rent_trx_donations_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get(TRX_DONATIONS::TAXONOMY)!='' || is_tax(TRX_DONATIONS::TAXONOMY) ? TRX_DONATIONS::TAXONOMY : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'cars4rent_trx_donations_query_add_filters' ) ) {
	function cars4rent_trx_donations_query_add_filters($args, $filter) {
		if ($filter == 'donations') {
			$args['post_type'] = TRX_DONATIONS::POST_TYPE;
		}
		return $args;
	}
}

// Add custom post type to the list
if ( !function_exists( 'cars4rent_trx_donations_list_post_types' ) ) {
	function cars4rent_trx_donations_list_post_types($list) {
		$list[TRX_DONATIONS::POST_TYPE] = esc_html__('Donations', 'cars4rent');
		return $list;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'cars4rent_trx_donations_required_plugins' ) ) {
	function cars4rent_trx_donations_required_plugins($list=array()) {
		if (in_array('trx_donations', cars4rent_storage_get('required_plugins'))) {
			$path = cars4rent_get_file_dir('plugins/install/trx_donations.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Donations', 'cars4rent'),
					'slug' 		=> 'trx_donations',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}
?>