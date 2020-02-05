<?php
/**
 * Cars4Rent Framework: return lists
 *
 * @package cars4rent
 * @since cars4rent 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'cars4rent_get_list_styles' ) ) {
	function cars4rent_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'cars4rent'), $i);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'cars4rent_get_list_margins' ) ) {
	function cars4rent_get_list_margins($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'cars4rent'),
				'tiny'		=> esc_html__('Tiny',		'cars4rent'),
				'small'		=> esc_html__('Small',		'cars4rent'),
				'medium'	=> esc_html__('Medium',		'cars4rent'),
				'large'		=> esc_html__('Large',		'cars4rent'),
				'huge'		=> esc_html__('Huge',		'cars4rent'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'cars4rent'),
				'small-'	=> esc_html__('Small (negative)',	'cars4rent'),
				'medium-'	=> esc_html__('Medium (negative)',	'cars4rent'),
				'large-'	=> esc_html__('Large (negative)',	'cars4rent'),
				'huge-'		=> esc_html__('Huge (negative)',	'cars4rent')
				);
			$list = apply_filters('cars4rent_filter_list_margins', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'cars4rent_get_list_line_styles' ) ) {
	function cars4rent_get_list_line_styles($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'cars4rent'),
				'dashed'=> esc_html__('Dashed', 'cars4rent'),
				'dotted'=> esc_html__('Dotted', 'cars4rent'),
				'double'=> esc_html__('Double', 'cars4rent'),
				'image'	=> esc_html__('Image', 'cars4rent')
				);
			$list = apply_filters('cars4rent_filter_list_line_styles', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'cars4rent_get_list_animations' ) ) {
	function cars4rent_get_list_animations($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'cars4rent'),
				'bounce'		=> esc_html__('Bounce',		'cars4rent'),
				'elastic'		=> esc_html__('Elastic',	'cars4rent'),
				'flash'			=> esc_html__('Flash',		'cars4rent'),
				'flip'			=> esc_html__('Flip',		'cars4rent'),
				'pulse'			=> esc_html__('Pulse',		'cars4rent'),
				'rubberBand'	=> esc_html__('Rubber Band','cars4rent'),
				'shake'			=> esc_html__('Shake',		'cars4rent'),
				'swing'			=> esc_html__('Swing',		'cars4rent'),
				'tada'			=> esc_html__('Tada',		'cars4rent'),
				'wobble'		=> esc_html__('Wobble',		'cars4rent')
				);
			$list = apply_filters('cars4rent_filter_list_animations', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'cars4rent_get_list_animations_in' ) ) {
	function cars4rent_get_list_animations_in($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'cars4rent'),
				'bounceIn'			=> esc_html__('Bounce In',			'cars4rent'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'cars4rent'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'cars4rent'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'cars4rent'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'cars4rent'),
				'elastic'			=> esc_html__('Elastic In',			'cars4rent'),
				'fadeIn'			=> esc_html__('Fade In',			'cars4rent'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'cars4rent'),
				'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'cars4rent'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'cars4rent'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'cars4rent'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'cars4rent'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'cars4rent'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'cars4rent'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'cars4rent'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'cars4rent'),
				'flipInX'			=> esc_html__('Flip In X',			'cars4rent'),
				'flipInY'			=> esc_html__('Flip In Y',			'cars4rent'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'cars4rent'),
				'rotateIn'			=> esc_html__('Rotate In',			'cars4rent'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','cars4rent'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'cars4rent'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'cars4rent'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','cars4rent'),
				'rollIn'			=> esc_html__('Roll In',			'cars4rent'),
				'slideInUp'			=> esc_html__('Slide In Up',		'cars4rent'),
				'slideInDown'		=> esc_html__('Slide In Down',		'cars4rent'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'cars4rent'),
				'slideInRight'		=> esc_html__('Slide In Right',		'cars4rent'),
				'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'cars4rent'),
				'zoomIn'			=> esc_html__('Zoom In',			'cars4rent'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'cars4rent'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'cars4rent'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'cars4rent'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'cars4rent')
				);
			$list = apply_filters('cars4rent_filter_list_animations_in', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'cars4rent_get_list_animations_out' ) ) {
	function cars4rent_get_list_animations_out($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'cars4rent'),
				'bounceOut'			=> esc_html__('Bounce Out',			'cars4rent'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'cars4rent'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',	'cars4rent'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',	'cars4rent'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'cars4rent'),
				'fadeOut'			=> esc_html__('Fade Out',			'cars4rent'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',		'cars4rent'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',	'cars4rent'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'cars4rent'),
				'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','cars4rent'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'cars4rent'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'cars4rent'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'cars4rent'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'cars4rent'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'cars4rent'),
				'flipOutX'			=> esc_html__('Flip Out X',			'cars4rent'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'cars4rent'),
				'hinge'				=> esc_html__('Hinge Out',			'cars4rent'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',	'cars4rent'),
				'rotateOut'			=> esc_html__('Rotate Out',			'cars4rent'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left','cars4rent'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right','cars4rent'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',	'cars4rent'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right','cars4rent'),
				'rollOut'			=> esc_html__('Roll Out',			'cars4rent'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'cars4rent'),
				'slideOutDown'		=> esc_html__('Slide Out Down',		'cars4rent'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',		'cars4rent'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'cars4rent'),
				'zoomOut'			=> esc_html__('Zoom Out',			'cars4rent'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'cars4rent'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',		'cars4rent'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',		'cars4rent'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',		'cars4rent')
				);
			$list = apply_filters('cars4rent_filter_list_animations_out', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('cars4rent_get_animation_classes')) {
	function cars4rent_get_animation_classes($animation, $speed='normal', $loop='none') {
		return cars4rent_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!cars4rent_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of the main menu hover effects
if ( !function_exists( 'cars4rent_get_list_menu_hovers' ) ) {
	function cars4rent_get_list_menu_hovers($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_menu_hovers'))=='') {
			$list = array(
				'fade'			=> esc_html__('Fade',		'cars4rent'),
				'slide_line'	=> esc_html__('Slide Line',	'cars4rent'),
				'slide_box'		=> esc_html__('Slide Box',	'cars4rent'),
				'zoom_line'		=> esc_html__('Zoom Line',	'cars4rent'),
				'path_line'		=> esc_html__('Path Line',	'cars4rent'),
				'roll_down'		=> esc_html__('Roll Down',	'cars4rent'),
				'color_line'	=> esc_html__('Color Line',	'cars4rent'),
				);
			$list = apply_filters('cars4rent_filter_list_menu_hovers', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_menu_hovers', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the button's hover effects
if ( !function_exists( 'cars4rent_get_list_button_hovers' ) ) {
	function cars4rent_get_list_button_hovers($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_button_hovers'))=='') {
			$list = array(
				'default'		=> esc_html__('Default',			'cars4rent'),
				'fade'			=> esc_html__('Fade',				'cars4rent'),
				'slide_left'	=> esc_html__('Slide from Left',	'cars4rent'),
				'slide_top'		=> esc_html__('Slide from Top',		'cars4rent'),
				'arrow'			=> esc_html__('Arrow',				'cars4rent'),
				);
			$list = apply_filters('cars4rent_filter_list_button_hovers', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_button_hovers', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the input field's hover effects
if ( !function_exists( 'cars4rent_get_list_input_hovers' ) ) {
	function cars4rent_get_list_input_hovers($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_input_hovers'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'cars4rent'),
				'accent'	=> esc_html__('Accented',	'cars4rent'),
				'path'		=> esc_html__('Path',		'cars4rent'),
				'jump'		=> esc_html__('Jump',		'cars4rent'),
				'underline'	=> esc_html__('Underline',	'cars4rent'),
				'iconed'	=> esc_html__('Iconed',		'cars4rent'),
				);
			$list = apply_filters('cars4rent_filter_list_input_hovers', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_input_hovers', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the search field's styles
if ( !function_exists( 'cars4rent_get_list_search_styles' ) ) {
	function cars4rent_get_list_search_styles($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_search_styles'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'cars4rent'),
				'fullscreen'=> esc_html__('Fullscreen',	'cars4rent'),
				'slide'		=> esc_html__('Slide',		'cars4rent'),
				'expand'	=> esc_html__('Expand',		'cars4rent'),
				);
			$list = apply_filters('cars4rent_filter_list_search_styles', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_search_styles', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'cars4rent_get_list_categories' ) ) {
	function cars4rent_get_list_categories($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'cars4rent_get_list_terms' ) ) {
	function cars4rent_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = cars4rent_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = cars4rent_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'cars4rent_get_list_posts_types' ) ) {
	function cars4rent_get_list_posts_types($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('cars4rent_filter_list_post_types', array());
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'cars4rent_get_list_posts' ) ) {
	function cars4rent_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = cars4rent_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'cars4rent');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set($hash, $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'cars4rent_get_list_pages' ) ) {
	function cars4rent_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return cars4rent_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'cars4rent_get_list_users' ) ) {
	function cars4rent_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = cars4rent_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'cars4rent');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_users', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'cars4rent_get_list_sliders' ) ) {
	function cars4rent_get_list_sliders($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'cars4rent')
			);
			$list = apply_filters('cars4rent_filter_list_sliders', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'cars4rent_get_list_slider_controls' ) ) {
	function cars4rent_get_list_slider_controls($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'cars4rent'),
				'side'		=> esc_html__('Side', 'cars4rent'),
				'bottom'	=> esc_html__('Bottom', 'cars4rent'),
				'pagination'=> esc_html__('Pagination', 'cars4rent')
				);
			$list = apply_filters('cars4rent_filter_list_slider_controls', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'cars4rent_get_slider_controls_classes' ) ) {
	function cars4rent_get_slider_controls_classes($controls) {
		if (cars4rent_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'cars4rent_get_list_popup_engines' ) ) {
	function cars4rent_get_list_popup_engines($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'cars4rent'),
				"magnific"	=> esc_html__("Magnific popup", 'cars4rent')
				);
			$list = apply_filters('cars4rent_filter_list_popup_engines', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_menus' ) ) {
	function cars4rent_get_list_menus($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'cars4rent');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'cars4rent_get_list_sidebars' ) ) {
	function cars4rent_get_list_sidebars($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_sidebars'))=='') {
			if (($list = cars4rent_storage_get('registered_sidebars'))=='') $list = array();
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'cars4rent_get_list_sidebars_positions' ) ) {
	function cars4rent_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'cars4rent'),
				'left'  => esc_html__('Left',  'cars4rent'),
				'right' => esc_html__('Right', 'cars4rent')
				);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'cars4rent_get_sidebar_class' ) ) {
	function cars4rent_get_sidebar_class() {
		$sb_main = cars4rent_get_custom_option('show_sidebar_main');
		$sb_outer = cars4rent_get_custom_option('show_sidebar_outer');
		return (cars4rent_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (cars4rent_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_body_styles' ) ) {
	function cars4rent_get_list_body_styles($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'cars4rent'),
				'wide'	=> esc_html__('Wide',		'cars4rent')
				);
			if (cars4rent_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'cars4rent');
				$list['fullscreen']	= esc_html__('Fullscreen',	'cars4rent');
			}
			$list = apply_filters('cars4rent_filter_list_body_styles', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_templates' ) ) {
	function cars4rent_get_list_templates($mode='') {
		if (($list = cars4rent_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = cars4rent_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: cars4rent_strtoproper($v['layout'])
										);
				}
			}
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_templates_blog' ) ) {
	function cars4rent_get_list_templates_blog($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_templates_blog'))=='') {
			$list = cars4rent_get_list_templates('blog');
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_templates_blogger' ) ) {
	function cars4rent_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_templates_blogger'))=='') {
			$list = cars4rent_array_merge(cars4rent_get_list_templates('blogger'), cars4rent_get_list_templates('blog'));
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_templates_single' ) ) {
	function cars4rent_get_list_templates_single($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_templates_single'))=='') {
			$list = cars4rent_get_list_templates('single');
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_templates_header' ) ) {
	function cars4rent_get_list_templates_header($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_templates_header'))=='') {
			$list = cars4rent_get_list_templates('header');
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_templates_forms' ) ) {
	function cars4rent_get_list_templates_forms($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_templates_forms'))=='') {
			$list = cars4rent_get_list_templates('forms');
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_article_styles' ) ) {
	function cars4rent_get_list_article_styles($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'cars4rent'),
				"stretch" => esc_html__('Stretch', 'cars4rent')
				);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_post_formats_filters' ) ) {
	function cars4rent_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'cars4rent'),
				"thumbs"  => esc_html__('With thumbs', 'cars4rent'),
				"reviews" => esc_html__('With reviews', 'cars4rent'),
				"video"   => esc_html__('With videos', 'cars4rent'),
				"audio"   => esc_html__('With audios', 'cars4rent'),
				"gallery" => esc_html__('With galleries', 'cars4rent')
				);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_portfolio_filters' ) ) {
	function cars4rent_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'cars4rent'),
				"tags"		=> esc_html__('Tags', 'cars4rent'),
				"categories"=> esc_html__('Categories', 'cars4rent')
				);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_hovers' ) ) {
	function cars4rent_get_list_hovers($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'cars4rent');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'cars4rent');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'cars4rent');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'cars4rent');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'cars4rent');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'cars4rent');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'cars4rent');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'cars4rent');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'cars4rent');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'cars4rent');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'cars4rent');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'cars4rent');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'cars4rent');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'cars4rent');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'cars4rent');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'cars4rent');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'cars4rent');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'cars4rent');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'cars4rent');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'cars4rent');
			$list['square effect1']  = esc_html__('Square Effect 1',  'cars4rent');
			$list['square effect2']  = esc_html__('Square Effect 2',  'cars4rent');
			$list['square effect3']  = esc_html__('Square Effect 3',  'cars4rent');
			$list['square effect5']  = esc_html__('Square Effect 5',  'cars4rent');
			$list['square effect6']  = esc_html__('Square Effect 6',  'cars4rent');
			$list['square effect7']  = esc_html__('Square Effect 7',  'cars4rent');
			$list['square effect8']  = esc_html__('Square Effect 8',  'cars4rent');
			$list['square effect9']  = esc_html__('Square Effect 9',  'cars4rent');
			$list['square effect10'] = esc_html__('Square Effect 10',  'cars4rent');
			$list['square effect11'] = esc_html__('Square Effect 11',  'cars4rent');
			$list['square effect12'] = esc_html__('Square Effect 12',  'cars4rent');
			$list['square effect13'] = esc_html__('Square Effect 13',  'cars4rent');
			$list['square effect14'] = esc_html__('Square Effect 14',  'cars4rent');
			$list['square effect15'] = esc_html__('Square Effect 15',  'cars4rent');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'cars4rent');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'cars4rent');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'cars4rent');
			$list['square effect_more']  = esc_html__('Square Effect More',  'cars4rent');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'cars4rent');
			$list['square effect_pull']  = esc_html__('Square Effect Pull',  'cars4rent');
			$list['square effect_slide'] = esc_html__('Square Effect Slide', 'cars4rent');
			$list['square effect_border'] = esc_html__('Square Effect Border', 'cars4rent');
			$list = apply_filters('cars4rent_filter_portfolio_hovers', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'cars4rent_get_list_blog_counters' ) ) {
	function cars4rent_get_list_blog_counters($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'cars4rent'),
				'likes'		=> esc_html__('Likes', 'cars4rent'),
				'rating'	=> esc_html__('Rating', 'cars4rent'),
				'comments'	=> esc_html__('Comments', 'cars4rent')
				);
			$list = apply_filters('cars4rent_filter_list_blog_counters', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'cars4rent_get_list_alter_sizes' ) ) {
	function cars4rent_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'cars4rent'),
					'1_2' => esc_html__('1x2', 'cars4rent'),
					'2_1' => esc_html__('2x1', 'cars4rent'),
					'2_2' => esc_html__('2x2', 'cars4rent'),
					'1_3' => esc_html__('1x3', 'cars4rent'),
					'2_3' => esc_html__('2x3', 'cars4rent'),
					'3_1' => esc_html__('3x1', 'cars4rent'),
					'3_2' => esc_html__('3x2', 'cars4rent'),
					'3_3' => esc_html__('3x3', 'cars4rent')
					);
			$list = apply_filters('cars4rent_filter_portfolio_alter_sizes', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_hovers_directions' ) ) {
	function cars4rent_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'cars4rent'),
				'right_to_left' => esc_html__('Right to Left',  'cars4rent'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'cars4rent'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'cars4rent'),
				'scale_up'      => esc_html__('Scale Up',  'cars4rent'),
				'scale_down'    => esc_html__('Scale Down',  'cars4rent'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'cars4rent'),
				'from_left_and_right' => esc_html__('From Left and Right',  'cars4rent'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'cars4rent')
			);
			$list = apply_filters('cars4rent_filter_portfolio_hovers_directions', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'cars4rent_get_list_label_positions' ) ) {
	function cars4rent_get_list_label_positions($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'cars4rent'),
				'bottom'	=> esc_html__('Bottom',		'cars4rent'),
				'left'		=> esc_html__('Left',		'cars4rent'),
				'over'		=> esc_html__('Over',		'cars4rent')
			);
			$list = apply_filters('cars4rent_filter_label_positions', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'cars4rent_get_list_bg_image_positions' ) ) {
	function cars4rent_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'cars4rent'),
				'center top'   => esc_html__("Center Top", 'cars4rent'),
				'right top'    => esc_html__("Right Top", 'cars4rent'),
				'left center'  => esc_html__("Left Center", 'cars4rent'),
				'center center'=> esc_html__("Center Center", 'cars4rent'),
				'right center' => esc_html__("Right Center", 'cars4rent'),
				'left bottom'  => esc_html__("Left Bottom", 'cars4rent'),
				'center bottom'=> esc_html__("Center Bottom", 'cars4rent'),
				'right bottom' => esc_html__("Right Bottom", 'cars4rent')
			);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'cars4rent_get_list_bg_image_repeats' ) ) {
	function cars4rent_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'cars4rent'),
				'repeat-x'	=> esc_html__('Repeat X', 'cars4rent'),
				'repeat-y'	=> esc_html__('Repeat Y', 'cars4rent'),
				'no-repeat'	=> esc_html__('No Repeat', 'cars4rent')
			);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'cars4rent_get_list_bg_image_attachments' ) ) {
	function cars4rent_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'cars4rent'),
				'fixed'		=> esc_html__('Fixed', 'cars4rent'),
				'local'		=> esc_html__('Local', 'cars4rent')
			);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'cars4rent_get_list_bg_tints' ) ) {
	function cars4rent_get_list_bg_tints($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'cars4rent'),
				'light'	=> esc_html__('Light', 'cars4rent'),
				'dark'	=> esc_html__('Dark', 'cars4rent')
			);
			$list = apply_filters('cars4rent_filter_bg_tints', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_field_types' ) ) {
	function cars4rent_get_list_field_types($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'cars4rent'),
				'textarea' => esc_html__('Text Area','cars4rent'),
				'password' => esc_html__('Password',  'cars4rent'),
				'radio'    => esc_html__('Radio',  'cars4rent'),
				'checkbox' => esc_html__('Checkbox',  'cars4rent'),
				'select'   => esc_html__('Select',  'cars4rent'),
				'date'     => esc_html__('Date','cars4rent'),
				'time'     => esc_html__('Time','cars4rent'),
				'button'   => esc_html__('Button','cars4rent')
			);
			$list = apply_filters('cars4rent_filter_field_types', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'cars4rent_get_list_googlemap_styles' ) ) {
	function cars4rent_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'cars4rent')
			);
			$list = apply_filters('cars4rent_filter_googlemap_styles', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return images list
if (!function_exists('cars4rent_get_list_images')) {
	function cars4rent_get_list_images($folder, $ext='', $only_names=false) {
		return function_exists('trx_utils_get_folder_list') ? trx_utils_get_folder_list($folder, $ext, $only_names) : array();
	}
}

// Return iconed classes list
if ( !function_exists( 'cars4rent_get_list_icons' ) ) {
	function cars4rent_get_list_icons($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_icons'))=='') {
			$list = cars4rent_parse_icons_classes(cars4rent_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'cars4rent_get_list_socials' ) ) {
	function cars4rent_get_list_socials($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_socials'))=='') {
			$list = cars4rent_get_list_images("images/socials", "png");
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'cars4rent_get_list_yesno' ) ) {
	function cars4rent_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'cars4rent'),
			'no'  => esc_html__("No", 'cars4rent')
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'cars4rent_get_list_onoff' ) ) {
	function cars4rent_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'cars4rent'),
			"off" => esc_html__("Off", 'cars4rent')
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'cars4rent_get_list_showhide' ) ) {
	function cars4rent_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'cars4rent'),
			"hide" => esc_html__("Hide", 'cars4rent')
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'cars4rent_get_list_orderings' ) ) {
	function cars4rent_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"desc" => esc_html__("Descending", 'cars4rent'),
			"asc" => esc_html__("Ascending", 'cars4rent'),
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'cars4rent_get_list_directions' ) ) {
	function cars4rent_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'cars4rent'),
			"vertical" => esc_html__("Vertical", 'cars4rent')
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'cars4rent_get_list_shapes' ) ) {
	function cars4rent_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'cars4rent'),
			"square" => esc_html__("Square", 'cars4rent')
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'cars4rent_get_list_sizes' ) ) {
	function cars4rent_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'cars4rent'),
			"small"  => esc_html__("Small", 'cars4rent'),
			"medium" => esc_html__("Medium", 'cars4rent'),
			"large"  => esc_html__("Large", 'cars4rent')
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'cars4rent_get_list_controls' ) ) {
	function cars4rent_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'cars4rent'),
			"side" => esc_html__("Side", 'cars4rent'),
			"bottom" => esc_html__("Bottom", 'cars4rent')
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'cars4rent_get_list_floats' ) ) {
	function cars4rent_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'cars4rent'),
			"left" => esc_html__("Float Left", 'cars4rent'),
			"right" => esc_html__("Float Right", 'cars4rent')
		);
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'cars4rent_get_list_alignments' ) ) {
	function cars4rent_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'cars4rent'),
			"left" => esc_html__("Left", 'cars4rent'),
			"center" => esc_html__("Center", 'cars4rent'),
			"right" => esc_html__("Right", 'cars4rent')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'cars4rent');
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'cars4rent_get_list_hpos' ) ) {
	function cars4rent_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'cars4rent');
		if ($center) $list['center'] = esc_html__("Center", 'cars4rent');
		$list['right'] = esc_html__("Right", 'cars4rent');
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'cars4rent_get_list_vpos' ) ) {
	function cars4rent_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'cars4rent');
		if ($center) $list['center'] = esc_html__("Center", 'cars4rent');
		$list['bottom'] = esc_html__("Bottom", 'cars4rent');
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'cars4rent_get_list_sortings' ) ) {
	function cars4rent_get_list_sortings($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'cars4rent'),
				"title" => esc_html__("Alphabetically", 'cars4rent'),
				"views" => esc_html__("Popular (views count)", 'cars4rent'),
				"comments" => esc_html__("Most commented (comments count)", 'cars4rent'),
				"author_rating" => esc_html__("Author rating", 'cars4rent'),
				"users_rating" => esc_html__("Visitors (users) rating", 'cars4rent'),
				"random" => esc_html__("Random", 'cars4rent')
			);
			$list = apply_filters('cars4rent_filter_list_sortings', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'cars4rent_get_list_columns' ) ) {
	function cars4rent_get_list_columns($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'cars4rent'),
				"1_1" => esc_html__("100%", 'cars4rent'),
				"1_2" => esc_html__("1/2", 'cars4rent'),
				"1_3" => esc_html__("1/3", 'cars4rent'),
				"2_3" => esc_html__("2/3", 'cars4rent'),
				"1_4" => esc_html__("1/4", 'cars4rent'),
				"3_4" => esc_html__("3/4", 'cars4rent'),
				"1_5" => esc_html__("1/5", 'cars4rent'),
				"2_5" => esc_html__("2/5", 'cars4rent'),
				"3_5" => esc_html__("3/5", 'cars4rent'),
				"4_5" => esc_html__("4/5", 'cars4rent'),
				"1_6" => esc_html__("1/6", 'cars4rent'),
				"5_6" => esc_html__("5/6", 'cars4rent'),
				"1_7" => esc_html__("1/7", 'cars4rent'),
				"2_7" => esc_html__("2/7", 'cars4rent'),
				"3_7" => esc_html__("3/7", 'cars4rent'),
				"4_7" => esc_html__("4/7", 'cars4rent'),
				"5_7" => esc_html__("5/7", 'cars4rent'),
				"6_7" => esc_html__("6/7", 'cars4rent'),
				"1_8" => esc_html__("1/8", 'cars4rent'),
				"3_8" => esc_html__("3/8", 'cars4rent'),
				"5_8" => esc_html__("5/8", 'cars4rent'),
				"7_8" => esc_html__("7/8", 'cars4rent'),
				"1_9" => esc_html__("1/9", 'cars4rent'),
				"2_9" => esc_html__("2/9", 'cars4rent'),
				"4_9" => esc_html__("4/9", 'cars4rent'),
				"5_9" => esc_html__("5/9", 'cars4rent'),
				"7_9" => esc_html__("7/9", 'cars4rent'),
				"8_9" => esc_html__("8/9", 'cars4rent'),
				"1_10"=> esc_html__("1/10", 'cars4rent'),
				"3_10"=> esc_html__("3/10", 'cars4rent'),
				"7_10"=> esc_html__("7/10", 'cars4rent'),
				"9_10"=> esc_html__("9/10", 'cars4rent'),
				"1_11"=> esc_html__("1/11", 'cars4rent'),
				"2_11"=> esc_html__("2/11", 'cars4rent'),
				"3_11"=> esc_html__("3/11", 'cars4rent'),
				"4_11"=> esc_html__("4/11", 'cars4rent'),
				"5_11"=> esc_html__("5/11", 'cars4rent'),
				"6_11"=> esc_html__("6/11", 'cars4rent'),
				"7_11"=> esc_html__("7/11", 'cars4rent'),
				"8_11"=> esc_html__("8/11", 'cars4rent'),
				"9_11"=> esc_html__("9/11", 'cars4rent'),
				"10_11"=> esc_html__("10/11", 'cars4rent'),
				"1_12"=> esc_html__("1/12", 'cars4rent'),
				"5_12"=> esc_html__("5/12", 'cars4rent'),
				"7_12"=> esc_html__("7/12", 'cars4rent'),
				"10_12"=> esc_html__("10/12", 'cars4rent'),
				"11_12"=> esc_html__("11/12", 'cars4rent')
			);
			$list = apply_filters('cars4rent_filter_list_columns', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'cars4rent_get_list_dedicated_locations' ) ) {
	function cars4rent_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'cars4rent'),
				"center"  => esc_html__('Above the text of the post', 'cars4rent'),
				"left"    => esc_html__('To the left the text of the post', 'cars4rent'),
				"right"   => esc_html__('To the right the text of the post', 'cars4rent'),
				"alter"   => esc_html__('Alternates for each post', 'cars4rent')
			);
			$list = apply_filters('cars4rent_filter_list_dedicated_locations', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'cars4rent_get_post_format_name' ) ) {
	function cars4rent_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'cars4rent') : esc_html__('galleries', 'cars4rent');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'cars4rent') : esc_html__('videos', 'cars4rent');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'cars4rent') : esc_html__('audios', 'cars4rent');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'cars4rent') : esc_html__('images', 'cars4rent');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'cars4rent') : esc_html__('quotes', 'cars4rent');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'cars4rent') : esc_html__('links', 'cars4rent');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'cars4rent') : esc_html__('statuses', 'cars4rent');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'cars4rent') : esc_html__('asides', 'cars4rent');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'cars4rent') : esc_html__('chats', 'cars4rent');
		else						$name = $single ? esc_html__('standard', 'cars4rent') : esc_html__('standards', 'cars4rent');
		return apply_filters('cars4rent_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'cars4rent_get_post_format_icon' ) ) {
	function cars4rent_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('cars4rent_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'cars4rent_get_list_fonts_styles' ) ) {
	function cars4rent_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','cars4rent'),
				'u' => esc_html__('U', 'cars4rent')
			);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'cars4rent_get_list_fonts' ) ) {
	function cars4rent_get_list_fonts($prepend_inherit=false) {
		if (($list = cars4rent_storage_get('list_fonts'))=='') {
			$list = array();
			$list = cars4rent_array_merge($list, cars4rent_get_list_font_faces());
			$list = cars4rent_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Open Sans' => array('family'=>'sans-serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('cars4rent_filter_list_fonts', $list);
			if (cars4rent_get_theme_setting('use_list_cache')) cars4rent_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? cars4rent_array_merge(array('inherit' => esc_html__("Inherit", 'cars4rent')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'cars4rent_get_list_font_faces' ) ) {
	function cars4rent_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$fonts = cars4rent_storage_get('required_custom_fonts');
		$list = array();
		if (is_array($fonts)) {
			foreach ($fonts as $font) {
				if (($url = cars4rent_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
					$list[sprintf(esc_html__('%s (uploaded font)', 'cars4rent'), $font)] = array('css' => $url);
				}
			}
		}
		return $list;
	}
}
?>