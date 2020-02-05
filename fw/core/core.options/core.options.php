<?php
/**
 * Cars4Rent Framework: Theme options manager
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'cars4rent_options_theme_setup' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_options_theme_setup' );
	function cars4rent_options_theme_setup() {

		if ( is_admin() ) {
			// Add Theme Options in WP menu
			add_action('admin_menu', 								'cars4rent_options_admin_menu_item');

			if ( cars4rent_options_is_used() ) {

				// Ajax Save and Export Action handler
				add_action('wp_ajax_cars4rent_options_save', 		'cars4rent_options_save');

				// Ajax Import Action handler
				add_action('wp_ajax_cars4rent_options_import',		'cars4rent_options_import');

				// Prepare global variables
				cars4rent_storage_set('to_data', null);
				cars4rent_storage_set('to_delimiter', ',');
				cars4rent_storage_set('to_colorpicker', 'tiny');			// wp - WP colorpicker, custom - internal theme colorpicker, tiny - external script
			}
		}
		
	}
}


// Add 'Theme options' in Admin Interface
if ( !function_exists( 'cars4rent_options_admin_menu_item' ) ) {
	function cars4rent_options_admin_menu_item() {
		cars4rent_admin_add_menu_item('theme', array(
			'page_title' => esc_html__('Global Options', 'cars4rent'),
			'menu_title' => esc_html__('Theme Options', 'cars4rent'),
			'capability' => 'manage_options',
			'menu_slug'  => 'cars4rent_options',
			'callback'   => 'cars4rent_options_page',
			'icon'		 => ''
			)
		);
	}
}



/* Theme options utils
-------------------------------------------------------------------- */

// Check if theme options are now used
if ( !function_exists( 'cars4rent_options_is_used' ) ) {
	function cars4rent_options_is_used() {
		$used = false;

		if (is_admin()) {

			if (isset($_REQUEST['action']) && ($_REQUEST['action']=='cars4rent_options_save' || $_REQUEST['action']=='cars4rent_options_import'))		// AJAX: Save or Import Theme Options
				$used = true;

			else if (isset($_REQUEST['page']) && cars4rent_strpos($_REQUEST['page'], 'cars4rent_options')!==false)																// Edit Theme Options
				$used = true;

			else if (cars4rent_check_admin_page('post-new.php') || cars4rent_check_admin_page('post.php')) {		// Create or Edit Post (page, product, ...)
				$post_type = cars4rent_admin_get_current_post_type();
				if (empty($post_type)) $post_type = 'post';
				$used = cars4rent_get_override_key($post_type, 'post_type')!='';

			} else if (cars4rent_check_admin_page('edit-tags.php') || cars4rent_check_admin_page('term.php')) {		// Edit Taxonomy
				$inheritance = cars4rent_get_theme_inheritance();
				if (!empty($inheritance) && is_array($inheritance)) {
					$post_type = cars4rent_admin_get_current_post_type();
					if (empty($post_type)) $post_type = 'post';
					foreach ($inheritance as $k=>$v) {
						if (!empty($v['taxonomy']) && is_array($v['taxonomy'])) {
							foreach ($v['taxonomy'] as $tax) {
								if ( isset($_REQUEST['taxonomy']) && $_REQUEST['taxonomy']==$tax && in_array($post_type, $v['post_type']) ) {
									$used = true;
									break;
								}
							}
						}
					}
				}

			} else if ( isset($_POST['override_options_taxonomy_nonce']) ) {																				// AJAX: Save taxonomy
				$used = true;
			}

		} else {
			$used = (cars4rent_get_theme_option("allow_editor")=='yes' && 
						(
						(is_single() && current_user_can('edit_posts', get_the_ID())) 
						|| 
						(is_page() && current_user_can('edit_pages', get_the_ID()))
						)
					);
		}
		return apply_filters('cars4rent_filter_theme_options_is_used', $used);
	}
}


// Load all theme options
if ( !function_exists( 'cars4rent_load_main_options' ) ) {
	function cars4rent_load_main_options() {
		$values = get_option(cars4rent_storage_get('options_prefix') . '_options', array());
		$options = cars4rent_storage_get('options');
		if (is_array($options) && count($options) > 0) {
			foreach ($options as $id => $item) {
				if (isset($item['std'])) {
					if (isset($values[$id]))
						$options[$id]['val'] = $values[$id];
					else
						$options[$id]['val'] = $item['std'];
				}
			}
			cars4rent_storage_set('options', $options);
		}
		// Call actions after load options
		do_action('cars4rent_action_load_main_options');
	}
}


// Get custom options arrays (from current category, post, page, shop, event, etc.)
if ( !function_exists( 'cars4rent_load_custom_options' ) ) {
	function cars4rent_load_custom_options() {
		global $wp_query, $post;

		cars4rent_storage_set('custom_options', array());
		cars4rent_storage_set('post_options', array());
		cars4rent_storage_set('taxonomy_options', array());
		cars4rent_storage_set('template_options', array());
		cars4rent_storage_set('theme_options_loaded', false);
		
		if ( is_admin() ) {
			cars4rent_storage_set('theme_options_loaded', true);
			return;
		}

		// This way used then user set options in admin menu (new variant)
		$inheritance_key = cars4rent_detect_inheritance_key();
		if (!empty($inheritance_key)) $inheritance = cars4rent_get_theme_inheritance($inheritance_key);
		$slug = cars4rent_detect_template_slug($inheritance_key);
		if ( !empty($slug) ) {
			$tmp = false;
			if (empty($inheritance['use_options_page']) || $inheritance['use_options_page'])
				$tmp = get_option(cars4rent_storage_get('options_prefix') . '_options_template_'.trim($slug));
			// If settings for current slug not saved - use settings from compatible overriden type
			if ($tmp===false && !empty($inheritance['override'])) {
				$slug = cars4rent_get_template_slug($inheritance['override']);
				if ( !empty($slug) ) $tmp = get_option(cars4rent_storage_get('options_prefix') . '_options_template_'.trim($slug));
			}
			cars4rent_storage_set('template_options', $tmp===false ? array() : $tmp);
		}

		// Load taxonomy and post options
		if (!empty($inheritance_key)) {
			// Load taxonomy options
			if (!empty($inheritance['taxonomy']) && is_array($inheritance['taxonomy'])) {
				foreach ($inheritance['taxonomy'] as $tax) {
					$tax_obj = get_taxonomy($tax);
					$tax_query = !empty($tax_obj->query_var) ? $tax_obj->query_var : $tax;
					if ($tax == 'category' && is_category()) {		// Current page is category's archive (Categories need specific check)
						$tax_id = (int) get_query_var( 'cat' );
						if (empty($tax_id)) $tax_id = get_query_var( 'category_name' );
						cars4rent_storage_set('taxonomy_options', cars4rent_taxonomy_get_inherited_properties('category', $tax_id));
						break;
					} else if ($tax == 'post_tag' && is_tag()) {	// Current page is tag's archive (Tags need specific check)
						$tax_id = get_query_var( $tax_query );
						cars4rent_storage_set('taxonomy_options', cars4rent_taxonomy_get_inherited_properties('post_tag', $tax_id));
						break;
					} else if (is_tax($tax)) {						// Current page is custom taxonomy archive (All rest taxonomies check)
						$tax_id = get_query_var( $tax_query );
						cars4rent_storage_set('taxonomy_options', cars4rent_taxonomy_get_inherited_properties($tax, $tax_id));
						break;
					}
				}
			}
			// Load post options
			if ( is_singular() && (!cars4rent_storage_empty('page_template') || !cars4rent_storage_get('blog_streampage')) ) {
				$post_id = get_the_ID();
				if ( $post_id == 0 && !empty($wp_query->queried_object_id) ) $post_id = $wp_query->queried_object_id;
				cars4rent_storage_set('post_options', get_post_meta($post_id, cars4rent_storage_get('options_prefix') . '_post_options', true));
				if ( !empty($inheritance['post_type']) && !empty($inheritance['taxonomy'])
					&& ( in_array( get_query_var('post_type'), $inheritance['post_type']) 
						|| ( !empty($post->post_type) && in_array( $post->post_type, $inheritance['post_type']) )
						) 
					) {
					$tax_list = array();
					foreach ($inheritance['taxonomy'] as $tax) {
						$tax_terms = cars4rent_get_terms_by_post_id( array(
							'post_id'=>$post_id, 
							'taxonomy'=>$tax
							)
						);
						if (!empty($tax_terms[$tax]->terms)) {
							$tax_list[] = cars4rent_taxonomies_get_inherited_properties($tax, $tax_terms[$tax]);
						}
					}
					if (!empty($tax_list)) {
						$tmp = cars4rent_storage_get('taxonomy_options');
						foreach($tax_list as $tax_options) {
							if (!empty($tax_options) && is_array($tax_options)) {
								foreach($tax_options as $tk=>$tv) {
									if ( !isset($tmp[$tk]) || cars4rent_is_inherit_option($tmp[$tk]) ) {
										$tmp[$tk] = $tv;
									}
								}
							}
						}
						cars4rent_storage_set('taxonomy_options', $tmp);
					}
				}
			}
		}
		
		// Merge Template options with required for current page template
		$layout_name = cars4rent_get_custom_option(is_singular() && !cars4rent_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		if (!cars4rent_storage_empty('registered_templates', $layout_name, 'theme_options')) {
			cars4rent_storage_set('template_options', array_merge(cars4rent_storage_get('template_options'), cars4rent_storage_get_array('registered_templates', $layout_name, 'theme_options')));
		}
		
		do_action('cars4rent_action_load_custom_options');

		cars4rent_storage_set('theme_options_loaded', true);

	}
}


// Get theme setting
if ( !function_exists( 'cars4rent_get_theme_setting' ) ) {
	function cars4rent_get_theme_setting($option_name, $default='') {
		return cars4rent_storage_get_array('settings', $option_name, $default);
	}
}


// Set theme setting
if ( !function_exists( 'cars4rent_set_theme_setting' ) ) {
	function cars4rent_set_theme_setting($option_name, $value) {
		cars4rent_storage_set_array('settings', $option_name, $value);
	}
}


// Get theme option. If not exists - try get site option. If not exist - return default
if ( !function_exists( 'cars4rent_get_theme_option' ) ) {
	function cars4rent_get_theme_option($option_name, $default = false, $options = null) {
		static $last_options = false;
		$val = '';	
		if (is_array($options)) {
			if (isset($option[$option_name])) {
				$val = $option[$option_name]['val'];
			}
		} else if (cars4rent_storage_isset('options', $option_name, 'val')) {
			$val = cars4rent_storage_get_array('options', $option_name, 'val');
		} else {
			if ($last_options===false) $last_options = get_option(cars4rent_storage_get('options_prefix') . '_options', array());
			if (isset($last_options[$option_name])) {
				$val = $last_options[$option_name];
			} else if (cars4rent_storage_isset('options', $option_name, 'std')) {
				$val = cars4rent_storage_get_array('options', $option_name, 'std');
			}
		}
		if ($val === '') {
			if (($val = get_option($option_name, false)) !== false) {
				return $val;
			} else {
				return $default;
			}
		} else {
			return $val;
		}
	}
}


// Return property value from request parameters < post options < category options < theme options
if ( !function_exists( 'cars4rent_get_custom_option' ) ) {
	function cars4rent_get_custom_option($name, $defa=null, $post_id=0, $post_type='post', $tax_id=0, $tax_type='category') {
		if (isset($_GET[$name]))
			$rez = cars4rent_get_value_gp($name);
		else {
			$hash_name = ($name).'_'.($tax_id).'_'.($post_id);
			if (!cars4rent_storage_empty('theme_options_loaded') && cars4rent_storage_isset('custom_options', $hash_name)) {
				$rez = cars4rent_storage_get_array('custom_options', $hash_name);
			} else {
				if ($tax_id > 0) {
					$rez = cars4rent_taxonomy_get_inherited_property($tax_type, $tax_id, $name);
					if ($rez=='') $rez = cars4rent_get_theme_option($name, $defa);
				} else if ($post_id > 0) {
					$rez = cars4rent_get_theme_option($name, $defa);
					$custom_options = get_post_meta($post_id, cars4rent_storage_get('options_prefix') . '_post_options', true);
					if (isset($custom_options[$name]) && !cars4rent_is_inherit_option($custom_options[$name])) {
						$rez = $custom_options[$name];
					} else {
						$terms = array();
						$tax = cars4rent_get_taxonomy_categories_by_post_type($post_type);
						$tax_obj = get_taxonomy($tax);
						$tax_query = !empty($tax_obj->query_var) ? $tax_obj->query_var : $tax;
						if ( ($tax=='category' && is_category()) || ($tax=='post_tag' && is_tag()) || is_tax($tax) ) {		// Current page is taxonomy's archive (Categories and Tags need specific check)
							$terms = array( get_queried_object() );
						} else {
							$taxes = cars4rent_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$tax));
							if (!empty($taxes[$tax]->terms)) {
								$terms = $taxes[$tax]->terms;
							}
						}
						$tmp = '';
						if (!empty($terms)) {
							for ($cc = 0; $cc < count($terms) && (empty($tmp) || cars4rent_is_inherit_option($tmp)); $cc++) {
								$tmp = cars4rent_taxonomy_get_inherited_property($terms[$cc]->taxonomy, $terms[$cc]->term_id, $name);
							}
						}
						if ($tmp!='') $rez = $tmp;
					}
				} else {
					$rez = cars4rent_get_theme_option($name, $defa);
					if (cars4rent_get_theme_option('show_theme_customizer') == 'yes' && cars4rent_get_theme_option('remember_visitors_settings') == 'yes' && function_exists('cars4rent_get_value_gpc')) {
						$tmp = cars4rent_get_value_gpc($name, $rez);
						if (!cars4rent_is_inherit_option($tmp)) {
							$rez = $tmp;
						}
					}
					if (cars4rent_storage_isset('template_options', $name)) {
						 $tmp = cars4rent_storage_get_array('template_options', $name);
						 if (!cars4rent_is_inherit_option($tmp)) $rez = is_array($tmp) ? $tmp[0] : $tmp;
					}
					if (cars4rent_storage_isset('taxonomy_options', $name)) {
						 $tmp = cars4rent_storage_get_array('taxonomy_options', $name);
						 if (!cars4rent_is_inherit_option($tmp)) $rez = $tmp;
					}
					if (cars4rent_storage_isset('post_options', $name)) {
						 $tmp = cars4rent_storage_get_array('post_options', $name);
						 if (!cars4rent_is_inherit_option($tmp)) $rez = is_array($tmp) ? $tmp[0] : $tmp;
					}
				}
				$rez = apply_filters('cars4rent_filter_get_custom_option', $rez, $name);
				if (!cars4rent_storage_empty('theme_options_loaded')) cars4rent_storage_set_array('custom_options', $hash_name, $rez);
			}
		}
		return $rez;
	}
}

// Check params for "on" | "off" | "inherit" values
if (!function_exists('cars4rent_param_is_on')) {
	function cars4rent_param_is_on($prm) {
		return $prm>0 || in_array(cars4rent_strtolower($prm), array('true', 'on', 'yes', 'show'));
	}
}
if (!function_exists('cars4rent_param_is_off')) {
	function cars4rent_param_is_off($prm) {
		return empty($prm) || $prm===0 || in_array(cars4rent_strtolower($prm), array('false', 'off', 'no', 'none', 'hide'));
	}
}
if (!function_exists('cars4rent_param_is_inherit')) {
	function cars4rent_param_is_inherit($prm) {
		return in_array(cars4rent_strtolower($prm), array('inherit', 'default'));
	}
}

// Check option for inherit value
if ( !function_exists( 'cars4rent_is_inherit_option' ) ) {
	function cars4rent_is_inherit_option($value) {
		while (is_array($value) && count($value)>0) {
			foreach ($value as $val) {
				$value = $val;
				break;
			}
		}
		return cars4rent_strtolower($value)=='inherit';	
	}
}

// Return options_param value
if ( !function_exists( 'cars4rent_get_options_param' ) ) {
	function cars4rent_get_options_param($prm) {
		return cars4rent_storage_get_array('options_params', $prm);
	}
}

// Set options_param value
if ( !function_exists( 'cars4rent_set_options_param' ) ) {
	function cars4rent_set_options_param($prm, $val) {
		cars4rent_storage_set_array('options_params', $prm, $val);
	}
}



/* Theme options manager
-------------------------------------------------------------------- */

// Load required styles and scripts for Options Page
if ( !function_exists( 'cars4rent_options_load_scripts' ) ) {
	function cars4rent_options_load_scripts() {
		static $loaded = false;

		if (!$loaded) {
			$loaded = true;

			// Cars4Rent fontello styles
			wp_enqueue_style( 'fontello-admin-style',	cars4rent_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null);
			wp_enqueue_style( 'fontello-style', 			cars4rent_get_file_url('css/fontello/css/fontello.css'), array(), null);
			wp_enqueue_style( 'fontello-animation-style',cars4rent_get_file_url('css/fontello-admin/css/animation.css'), array(), null);
			// Cars4Rent options styles
			wp_enqueue_style('cars4rent-options-style',			cars4rent_get_file_url('core/core.options/css/core.options.css'), array(), null);
			wp_enqueue_style('cars4rent-options-datepicker-style',	cars4rent_get_file_url('core/core.options/css/core.options-datepicker.css'), array(), null);

			// WP core media scripts
			wp_enqueue_media();

			// Color Picker
			wp_enqueue_style( 'wp-color-picker', false, array(), null);
			wp_enqueue_script('wp-color-picker', false, array('jquery'), null, true);
			wp_enqueue_script('cars4rent-colors-script',		cars4rent_get_file_url('js/colorpicker/colors.js'), array('jquery'), null, true );
			wp_enqueue_script('cars4rent-colorpicker-script',	cars4rent_get_file_url('js/colorpicker/jqColorPicker.js'), array('jquery'), null, true );

			// Input masks for text fields
			wp_enqueue_script( 'jquery-input-mask',				cars4rent_get_file_url('core/core.options/js/jquery.maskedinput.min.js'), array('jquery'), null, true );
			// Cars4Rent core scripts
			wp_enqueue_script( 'cars4rent-core-utils-script',		cars4rent_get_file_url('js/core.utils.js'), array(), null, true );
			// Cars4Rent options scripts
			wp_enqueue_script( 'cars4rent-options-script',			cars4rent_get_file_url('core/core.options/js/core.options.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-accordion', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-datepicker'), null, true );
			wp_enqueue_script( 'cars4rent-options-custom-script',	cars4rent_get_file_url('core/core.options/js/core.options-custom.js'), array('cars4rent-options-script'), null, true );
		
			wp_localize_script( 'cars4rent-options-script', 'CARS4RENT_OPTIONS_DATA', cars4rent_storage_get('to_data') );

			cars4rent_enqueue_messages();
			cars4rent_enqueue_popup();
		}
	}
}


// Prepare javascripts global variables
if ( !function_exists( 'cars4rent_options_prepare_scripts' ) ) {
	function cars4rent_options_prepare_scripts($override='') {
		static $prepared = false;
		
		if (!$prepared) {
			$prepared = true;

			if (empty($override)) $override = 'general';

			cars4rent_storage_set_array('js_vars', 'to_delimiter', 	cars4rent_storage_get('to_delimiter'));
			cars4rent_storage_set_array('js_vars', 'to_slug', 		cars4rent_storage_get_array('to_flags', 'slug'));
			cars4rent_storage_set_array('js_vars', 'to_popup', 		cars4rent_get_theme_option('popup_engine'));
			cars4rent_storage_set_array('js_vars', 'to_override',	$override);
			$list = '';
			if (($export_opts = get_option(cars4rent_storage_get('options_prefix') . '_options_export_'.($override), false)) !== false)
				$list = array_keys($export_opts);
			cars4rent_storage_set_array('js_vars', 'to_export_list',	$list);

			cars4rent_storage_set_array2('js_vars', 'to_strings', 'del_item_error',			esc_html__("You can't delete last item! To disable it - just clear value in field.", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'del_item',				esc_html__("Delete item error!", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'recompile_styles',		esc_html__("When saving color schemes and font settings, recompilation of .less files occurs. It may take from 5 to 15 secs dependning on your server's speed and size of .less files.", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'wait',					esc_html__("Please wait a few seconds!", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'reload_page',				esc_html__("After 3 seconds this page will be reloaded.", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'save_options',			esc_html__("Options saved!", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'reset_options',			esc_html__("Options reset!", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'reset_options_confirm',	esc_html__("Do you really want reset all options to default values?", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'reset_options_complete',	esc_html__("Settings are reset to their default values.", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_options_header',	esc_html__("Export options", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_options_error',	esc_html__("Name for options set is not selected! Export cancelled.", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_options_label',	esc_html__("Name for the options set:", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_options_label2',	esc_html__("or select one of exists set (for replace):", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_options_select',	esc_html__("Select set for replace ...", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_empty',			esc_html__("No exported sets for import!", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_options',			esc_html__("Options exported!", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_link',				esc_html__("If need, you can download the configuration file from the following link: %s", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'export_download',			esc_html__("Download theme options settings", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'import_options_label',	esc_html__("or put here previously exported data:", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'import_options_label2',	esc_html__("or select file with saved settings:", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'import_options_header',	esc_html__("Import options", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'import_options_error',	esc_html__("You need select the name for options set or paste import data! Import cancelled.", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'import_options_failed',	esc_html__("Error while import options! Import cancelled.", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'import_options_broken',	esc_html__("Attention! Some options are not imported:", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'import_options',			esc_html__("Options imported!", 'cars4rent'));
			cars4rent_storage_set_array2('js_vars', 'to_strings', 'import_dummy_confirm',	esc_html__("Attention! During the import process, all existing data will be replaced with new.", 'cars4rent'));
		}
	}
}


// Build the Options Page
if ( !function_exists( 'cars4rent_options_page' ) ) {
	function cars4rent_options_page() {

		$page = isset($_REQUEST['page']) ? cars4rent_get_value_gp('page') : '';
		$mode = isset($_REQUEST['subpage']) ? cars4rent_get_value_gp('subpage') : '';
		$override = $slug = '';
		if (!empty($mode)) {
			$inheritance = cars4rent_get_theme_inheritance();
			if (!empty($inheritance) && is_array($inheritance)) {
				foreach ($inheritance as $k=>$v) {
					$tpl = false;
					if (!empty($v['stream_template'])) {
						$cur_slug = cars4rent_get_slug($v['stream_template']);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
					if (!empty($v['single_template'])) {
						$cur_slug = cars4rent_get_slug($v['single_template']);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
					if (!$tpl) {
						$cur_slug = cars4rent_get_slug($k);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
				}
			}
		}

		$custom_options = empty($override) ? false : get_option(cars4rent_storage_get('options_prefix') . '_options'.(!empty($slug) ? '_template_'.trim($slug) : ''));

		cars4rent_options_page_start(array(
			'add_inherit' => !empty($override),
			'subtitle' => empty($slug) 
								? (empty($override) 
									? esc_html__('Global Options', 'cars4rent')
									: '') 
								: cars4rent_strtoproper(str_replace('_', ' ', $slug)) . ' ' . esc_html__('Options', 'cars4rent'),
			'description' => empty($slug) 
								? (empty($override) 
									? wp_kses_data( __("Global settings affect the entire website's display. They can be overriden when editing settings for a certain post type (if select it in the popup menu above) or when editing category or single page/post (affect only on this category/page/post)", 'cars4rent') )
									: '') 
								: wp_kses_data( __('Settings template for a certain post type: affects the display of just one specific post type. They can be overriden when editing categories and/or posts of a certain type', 'cars4rent') ),
			'subpage' => $mode,
			'slug' => $slug,
			'override' => $override
		));

		$to_data = cars4rent_storage_get('to_data');
		if (is_array($to_data) && count($to_data) > 0) {
			foreach ($to_data as $id=>$field) {
				if (!empty($override) && (!isset($field['override']) || !in_array($override, explode(',', $field['override'])))) continue;
				cars4rent_options_show_field( $id, $field, empty($override) ? null : (isset($custom_options[$id]) ? $custom_options[$id] : 'inherit') );
			}
		}
	
		cars4rent_options_page_stop();
	}
}


// Start render the options page (initialize flags)
if ( !function_exists( 'cars4rent_options_page_start' ) ) {
	function cars4rent_options_page_start($args = array()) {
		$to_flags = array_merge(array(
			'data'				=> null,
			'title'				=> esc_html__('Theme Options', 'cars4rent'),	// Theme Options page title
			'subtitle'			=> '',								// Subtitle for top of page
			'description'		=> '',								// Description for top of page
			'icon'				=> 'iconadmin-cog',					// Theme Options page icon
			'nesting'			=> array(),							// Nesting stack for partitions, tabs and groups
			'radio_as_select'	=> false,							// Display options[type="radio"] as options[type="select"]
			'add_inherit'		=> false,							// Add value "Inherit" in all options with lists
			'create_form'		=> true,							// Create tag form or use form from current page
			'buttons'			=> array('save', 'reset', 'import', 'export'),	// Buttons set
			'subpage'			=> '',								// Current options subpage
			'slug'				=> '',								// Slug for save options. If empty - global options
			'override'			=> ''								// Override mode - page|post|category|products-category|...
			), is_array($args) ? $args : array( 'add_inherit' => $args ));
		cars4rent_storage_set('to_flags', $to_flags);
		cars4rent_storage_set('to_data', empty($args['data']) ? cars4rent_storage_get('options') : $args['data']);
		// Load required styles and scripts for Options Page
		cars4rent_options_load_scripts();
		// Prepare javascripts global variables
		cars4rent_options_prepare_scripts($to_flags['override']);
		?>
		<div class="cars4rent_options">
			<?php if ($to_flags['create_form']) { ?>
			<form class="cars4rent_options_form">
			<?php }	?>
				<div class="cars4rent_options_header">
					<div id="cars4rent_options_logo" class="cars4rent_options_logo">
						<span class="<?php echo esc_attr($to_flags['icon']); ?>"></span>
						<h2><?php cars4rent_show_layout($to_flags['title']); ?></h2>
					</div>
					<?php if (in_array('import', $to_flags['buttons'])) { ?>
					<div class="cars4rent_options_button_import"><span class="iconadmin-download"></span><?php esc_html_e('Import', 'cars4rent'); ?></div>
					<?php }	?>
					<?php if (in_array('export', $to_flags['buttons'])) { ?>
					<div class="cars4rent_options_button_export"><span class="iconadmin-upload"></span><?php esc_html_e('Export', 'cars4rent'); ?></div>
					<?php }	?>
					<?php if (in_array('reset', $to_flags['buttons'])) { ?>
					<div class="cars4rent_options_button_reset"><span class="iconadmin-spin3"></span><?php esc_html_e('Reset', 'cars4rent'); ?></div>
					<?php }	?>
					<?php if (in_array('save', $to_flags['buttons'])) { ?>
					<div class="cars4rent_options_button_save"><span class="iconadmin-check"></span><?php esc_html_e('Save', 'cars4rent'); ?></div>
					<?php }	?>
					<div id="cars4rent_options_title" class="cars4rent_options_title">
						<h2><?php echo (!empty($to_flags['create_form']) ? '<a href="#" class="cars4rent_options_override_title">' : '') . trim($to_flags['subtitle']) . ($to_flags['create_form'] ? '</a>' : ''); ?></h2>
						<?php
						if ($to_flags['create_form']) cars4rent_options_show_override_menu($to_flags);
						?>
						<p><?php cars4rent_show_layout($to_flags['description']); ?></p>
					</div>
				</div>
				<div class="cars4rent_options_body">
		<?php
	}
}


// Finish render the options page (close groups, tabs and partitions)
if ( !function_exists( 'cars4rent_options_page_stop' ) ) {
	function cars4rent_options_page_stop() {
		cars4rent_show_layout(cars4rent_options_close_nested_groups('', true));
				?>
				</div> <!-- .cars4rent_options_body -->
				<?php
		if (cars4rent_storage_get_array('to_flags', 'create_form')) {
			?>
			</form>
			<?php
		}
		?>
		</div>	<!-- .cars4rent_options -->
		<?php
	}
}


// Add popup menu with override modes
if ( !function_exists( 'cars4rent_options_show_override_menu' ) ) {
	function cars4rent_options_show_override_menu($to_flags) {
		$menu_url = menu_page_url('cars4rent_options', false);
		// Add submenu items for each inheritance item
		$items = array(
			'00_global' => '<a href="'.esc_url($menu_url).'"'.($to_flags['subpage']=='' ? ' class="selected"' : '').'>'.esc_html__('Global Options', 'cars4rent').'</a>'
		);
		$inheritance = cars4rent_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (isset($v['use_options_page']) && !$v['use_options_page']) continue;
				$tpl = false;
				$title_slug = $slug = cars4rent_get_slug($k);
				$title = cars4rent_strtoproper(str_replace('_', ' ', $title_slug));
				$items_idx = sprintf('%02d', !empty($v['priority']) ? $v['priority'] : 99) . '_' . $title_slug;
				if (!empty($v['stream_template'])) {
					$slug = cars4rent_get_slug($v['stream_template']);
					if (!empty($v['single_template'])) $title = cars4rent_strtoproper(sprintf(esc_html__('%s Stream', 'cars4rent'), $title_slug));
					$items[$items_idx.'_blog'] = '<a href="'.esc_url($menu_url.'&subpage='.$slug).'"'.($to_flags['subpage']==$slug ? ' class="selected"' : '').'>'.esc_html($title).'</a>';
					$tpl = true;
				}
				if (!empty($v['single_template'])) {
					$slug = cars4rent_get_slug($v['single_template']);
					if (!empty($v['stream_template'])) $title = cars4rent_strtoproper(sprintf(esc_html__('%s Single', 'cars4rent'), $title_slug));
					$items[$items_idx.'_single'] = '<a href="'.esc_url($menu_url.'&subpage='.$slug).'"'.($to_flags['subpage']==$slug ? ' class="selected"' : '').'>'.esc_html($title).'</a>';
					$tpl = true;
				}
				if (!$tpl) {
					$items[$items_idx] = '<a href="'.esc_url($menu_url.'&subpage='.$slug).'"'.($to_flags['subpage']==$slug ? ' class="selected"' : '').'>'.esc_html($title).'</a>';
				}
			}
		}
		if (count($items) > 1) {
			echo '<div class="cars4rent_options_override_menu">';
			ksort($items);
			foreach ($items as $item)
				cars4rent_show_layout($item);
			echo '</div>';
		}
	}
}


// Return true if current type is groups type
if ( !function_exists( 'cars4rent_options_is_group' ) ) {
	function cars4rent_options_is_group($type) {
		return in_array($type, array('group', 'toggle', 'accordion', 'tab', 'partition'));
	}
}


// Close nested groups until type
if ( !function_exists( 'cars4rent_options_close_nested_groups' ) ) {
	function cars4rent_options_close_nested_groups($type='', $end=false) {
		$output = '';
		$nesting = cars4rent_storage_get_array('to_flags', 'nesting');
		if ($nesting) {
			for ($i=count($nesting)-1; $i>=0; $i--) {
				$container = array_pop($nesting);
				switch ($container) {
					case 'group':
						$output = '</fieldset>' . ($output);
						break;
					case 'toggle':
						$output = '</div></div>' . ($output);
						break;
					case 'tab':
					case 'partition':
						$output = '</div>' . ($container!=$type || $end ? '</div>' : '') . ($output);
						break;
					case 'accordion':
						$output = '</div></div>' . ($container!=$type || $end ? '</div>' : '') . ($output);
						break;
				}
				if ($type == $container)
					break;
			}
			cars4rent_storage_set_array('to_flags', 'nesting', $nesting);
		}
		return $output;
	}
}


// Collect tabs titles for current tabs or partitions
if ( !function_exists( 'cars4rent_options_collect_tabs' ) ) {
	function cars4rent_options_collect_tabs($type, $id) {
		$start = false;
		$nesting = array();
		$tabs = '';
		$to_data = cars4rent_storage_get('to_data');
		if (is_array($to_data) && count($to_data) > 0) {
			foreach ($to_data as $field_id=>$field) {
				if (!cars4rent_storage_empty('to_flags', 'override') && (empty($field['override']) || !in_array(cars4rent_storage_get_array('to_flags', 'override'), explode(',', $field['override'])))) continue;
				if ($field['type']==$type && !empty($field['start']) && $field['start']==$id)
					$start = true;
				if (!$start) continue;
				if (cars4rent_options_is_group($field['type'])) {
					if (empty($field['start']) && (!in_array($field['type'], array('group', 'toggle')) || !empty($field['end']))) {
						if ($nesting) {
							for ($i = count($nesting)-1; $i>=0; $i--) {
								$container = array_pop($nesting);
								if ($field['type'] == $container) {
									break;
								}
							}
						}
					}
					if (empty($field['end'])) {
						if (!$nesting) {
							if ($field['type']==$type) {
								$tabs .= '<li id="'.esc_attr($field_id).'">'
									. '<a id="'.esc_attr($field_id).'_title"'
										. ' href="#'.esc_attr($field_id).'_content"'
										. (!empty($field['action']) ? ' onclick="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
										. '>'
										. (!empty($field['icon']) ? '<span class="'.esc_attr($field['icon']).'"></span>' : '')
										. ($field['title'])
										. '</a>';
							} else
								break;
						}
						array_push($nesting, $field['type']);
					}
				}
			}
	    }
		return $tabs;
	}
}



// Return menu items list (menu, images or icons)
if ( !function_exists( 'cars4rent_options_menu_list' ) ) {
	function cars4rent_options_menu_list($field, $clone_val) {

		$to_delimiter = cars4rent_storage_get('to_delimiter');

		if ($field['type'] == 'socials') $clone_val = $clone_val['icon'];
		$list = '<div class="cars4rent_options_input_menu '.(empty($field['style']) ? '' : ' cars4rent_options_input_menu_'.esc_attr($field['style'])).'">';
		$caption = '';
		if (is_array($field['options']) && count($field['options']) > 0) {
			foreach ($field['options'] as $key => $item) {
				if (in_array($field['type'], array('list', 'icons', 'socials'))) $key = $item;
				$selected = '';
				if (cars4rent_strpos(($to_delimiter).($clone_val).($to_delimiter), ($to_delimiter).($key).($to_delimiter))!==false) {
					$caption = esc_attr($item);
					$selected = ' cars4rent_options_state_checked';
				}
				$list .= '<span class="cars4rent_options_menuitem' 
					. ($selected) 
					. '" data-value="'.esc_attr($key).'"'
					. '>';
				if (in_array($field['type'], array('list', 'select', 'fonts')))
					$list .= $item;
				else if ($field['type'] == 'icons' || ($field['type'] == 'socials' && $field['style'] == 'icons'))
					$list .= '<span class="'.esc_attr($item).'"></span>';
				else if ($field['type'] == 'images' || ($field['type'] == 'socials' && $field['style'] == 'images'))
					$list .= '<span style="background-image:url('.esc_url($item).')" data-src="'.esc_url($item).'" data-icon="'.esc_attr($key).'" class="cars4rent_options_input_image"></span>';
				$list .= '</span>';
			}
		}
		$list .= '</div>';
		return array($list, $caption);
	}
}


// Return action buttom
if ( !function_exists( 'cars4rent_options_action_button' ) ) {
	function cars4rent_options_action_button($data, $type) {
		$class = ' cars4rent_options_button_'.esc_attr($type).(!empty($data['icon']) ? ' cars4rent_options_button_'.esc_attr($type).'_small' : '');
		$output = '<span class="' 
					. ($type == 'button' ? 'cars4rent_options_input_button'  : 'cars4rent_options_field_'.esc_attr($type))
					. (!empty($data['action']) ? ' cars4rent_options_with_action' : '')
					. (!empty($data['icon']) ? ' '.esc_attr($data['icon']) : '')
					. '"'
					. (!empty($data['icon']) && !empty($data['title']) ? ' title="'.esc_attr($data['title']).'"' : '')
					. (!empty($data['action']) ? ' onclick="cars4rent_options_action_'.esc_attr($data['action']).'(this);return false;"' : '')
					. (!empty($data['type']) ? ' data-type="'.esc_attr($data['type']).'"' : '')
					. (!empty($data['multiple']) ? ' data-multiple="'.esc_attr($data['multiple']).'"' : '')
					. (!empty($data['sizes']) ? ' data-sizes="'.esc_attr($data['sizes']).'"' : '')
					. (!empty($data['linked_field']) ? ' data-linked-field="'.esc_attr($data['linked_field']).'"' : '')
					. (!empty($data['captions']['choose']) ? ' data-caption-choose="'.esc_attr($data['captions']['choose']).'"' : '')
					. (!empty($data['captions']['update']) ? ' data-caption-update="'.esc_attr($data['captions']['update']).'"' : '')
					. '>'
					. ($type == 'button' || (empty($data['icon']) && !empty($data['title'])) ? $data['title'] : '')
					. '</span>';
		return array($output, $class);
	}
}


// Theme options page show option field
if ( !function_exists( 'cars4rent_options_show_field' ) ) {
	function cars4rent_options_show_field($id, $field, $value=null) {
	
		// Set start field value
		if ($value !== null) $field['val'] = $value;
		if (!isset($field['val']) || $field['val']=='') $field['val'] = 'inherit';
		if (!empty($field['subset'])) {
			$sbs = cars4rent_get_theme_option($field['subset'], '', cars4rent_storage_get('to_data'));
			$field['val'] = isset($field['val'][$sbs]) ? $field['val'][$sbs] : '';
		}
		
		if (empty($id))
			$id = 'cars4rent_options_id_'.str_replace('.', '', mt_rand());
		if (!isset($field['title']))
			$field['title'] = '';
		
		// Options delimiter 
		$to_delimiter = cars4rent_storage_get('to_delimiter');
		
		// Divider before field
		$divider = (!isset($field['divider']) && !in_array($field['type'], array('info', 'partition', 'tab', 'toggle'))) || (isset($field['divider']) && $field['divider']) ? ' cars4rent_options_divider' : '';

		// Setup default parameters
		if ($field['type']=='media') {
			if (!isset($field['before'])) $field['before'] = array();
			$field['before'] = array_merge(array(
					'title' => esc_html__('Choose image', 'cars4rent'),
					'action' => 'media_upload',
					'type' => 'image',
					'multiple' => false,
					'sizes' => false,
					'linked_field' => '',
					'captions' => array('choose' => esc_html__( 'Choose image', 'cars4rent'),
										'update' => esc_html__( 'Select image', 'cars4rent')
										)
				), $field['before']);
			if (!isset($field['after'])) $field['after'] = array();
			$field['after'] = array_merge(array(
					'icon'=>'iconadmin-cancel',
					'action'=>'media_reset'
				), $field['after']);
		}
		if ($field['type']=='color' && (cars4rent_storage_get('to_colorpicker')=='tiny' || (isset($field['style']) && $field['style']!='wp'))) {
			if (!isset($field['after'])) $field['after'] = array();
			$field['after'] = array_merge(array(
					'icon'=>'iconadmin-cancel',
					'action'=>'color_reset'
				), $field['after']);
		}

		// Buttons before and after field
		$before = $after = $buttons_classes = '';
		if (!empty($field['before'])) {
			list($before, $class) = cars4rent_options_action_button($field['before'], 'before');
			$buttons_classes .= $class;
		}
		if (!empty($field['after'])) {
			list($after, $class) = cars4rent_options_action_button($field['after'], 'after');
			$buttons_classes .= $class;
		}
		if ( in_array($field['type'], array('list', 'select', 'fonts')) || ($field['type']=='socials' && (empty($field['style']) || $field['style']=='icons')) ) {
			$buttons_classes .= ' cars4rent_options_button_after_small';
		}
	
		// Is it inherit field?
		$inherit = cars4rent_is_inherit_option($field['val']) ? 'inherit' : '';
	
		// Is it cloneable field?
		$cloneable = isset($field['cloneable']) && $field['cloneable'];
	
		// Prepare field
		if (!$cloneable)
			$field['val'] = array($field['val']);
		else {
			if (!is_array($field['val']))
				$field['val'] = array($field['val']);
			else if ($field['type'] == 'socials') {
				if (count($field['val']) > 0) {
					foreach ($field['val'] as $k=>$v) {
						if (!is_array($v)) 
							$field['val'] = array($field['val']);
						break;
					}
				}
			}
		}

		// Field container
		if (cars4rent_options_is_group($field['type'])) {					// Close nested containers
			if (empty($field['start']) && (!in_array($field['type'], array('group', 'toggle')) || !empty($field['end']))) {
				cars4rent_show_layout(cars4rent_options_close_nested_groups($field['type'], !empty($field['end'])));
				if (!empty($field['end'])) {
					return;
				}
			}
		} else {														// Start field layout
			if ($field['type'] != 'hidden') {
				echo '<div class="cars4rent_options_field'
					. ' cars4rent_options_field_' . (in_array($field['type'], array('list','fonts')) ? 'select' : $field['type'])
					. (in_array($field['type'], array('media', 'fonts', 'list', 'select', 'socials', 'date', 'time')) ? ' cars4rent_options_field_text'  : '')
					. ($field['type']=='socials' && !empty($field['style']) && $field['style']=='images' ? ' cars4rent_options_field_images'  : '')
					. ($field['type']=='socials' && (empty($field['style']) || $field['style']=='icons') ? ' cars4rent_options_field_icons'  : '')
					. (isset($field['dir']) && $field['dir']=='vertical' ? ' cars4rent_options_vertical' : '')
					. (!empty($field['multiple']) ? ' cars4rent_options_multiple' : '')
					. (isset($field['size']) ? ' cars4rent_options_size_'.esc_attr($field['size']) : '')
					. (isset($field['class']) ? ' ' . esc_attr($field['class']) : '')
					. (!empty($field['columns']) ? ' cars4rent_options_columns cars4rent_options_columns_'.esc_attr($field['columns']) : '')
					. ($divider)
					. '">'."\n";
				if ( !in_array($field['type'], array('divider'))) {
					echo '<label class="cars4rent_options_field_label'
						. (!cars4rent_storage_empty('to_flags', 'add_inherit') && isset($field['std']) ? ' cars4rent_options_field_label_inherit' : '')
						. '"'
						. (!empty($field['title']) ? ' for="'.esc_attr($id).'"' : '')
						. '>' 
						. ($field['title']) 
						. (!empty($field['info']) && ($fdir=cars4rent_get_file_url('images/to_info/'.cars4rent_esc($id).'.jpg'))!='' 
							? '<a href="'.esc_url($fdir).'" data-rel="popup" target="_blank" class="cars4rent_options_field_label_info iconadmin-info-circled" title="'.esc_html__('More info', 'cars4rent').'"></a>' 
							: '')
						. (!cars4rent_storage_empty('to_flags', 'add_inherit') && isset($field['std']) 
							? '<span id="'.esc_attr($id).'_inherit" class="cars4rent_options_button_inherit'
								.($inherit ? '' : ' cars4rent_options_inherit_off')
								.'" title="' . esc_attr__('Unlock this field', 'cars4rent') . '"></span>' 
							: '')
						. '</label>'
						. "\n";
				}
				if ( !in_array($field['type'], array('info', 'label', 'divider'))) {
					echo '<div class="cars4rent_options_field_content'
						. ($buttons_classes)
						. ($cloneable ? ' cars4rent_options_cloneable_area' : '')
						. '">' . "\n";
				}
			}
		}
	
		// Parse field type
		if (is_array($field['val']) && count($field['val']) > 0) {
		foreach ($field['val'] as $clone_num => $clone_val) {
			
			if ($cloneable) {
				echo '<div class="cars4rent_options_cloneable_item">'
					. '<span class="cars4rent_options_input_button cars4rent_options_clone_button cars4rent_options_clone_button_del">-</span>';
			}
	
			switch ( $field['type'] ) {
		
			case 'group':
				echo '<fieldset id="'.esc_attr($id).'" class="cars4rent_options_container cars4rent_options_group cars4rent_options_content'.esc_attr($divider).'">';
				if (!empty($field['title'])) echo '<legend>'.(!empty($field['icon']) ? '<span class="'.esc_attr($field['icon']).'"></span>' : '').esc_html($field['title']).'</legend>'."\n";
				cars4rent_storage_push_array('to_flags', 'nesting', 'group');
			break;
		
			case 'toggle':
				cars4rent_storage_push_array('to_flags', 'nesting', 'toggle');
				echo '<div id="'.esc_attr($id).'" class="cars4rent_options_container cars4rent_options_toggle'.esc_attr($divider).'">';
				echo '<h3 id="'.esc_attr($id).'_title"'
					. ' class="cars4rent_options_toggle_header'.(empty($field['closed']) ? ' ui-state-active' : '') .'"'
					. (!empty($field['action']) ? ' onclick="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>'
					. (!empty($field['icon']) ? '<span class="cars4rent_options_toggle_header_icon '.esc_attr($field['icon']).'"></span>' : '') 
					. ($field['title'])
					. '<span class="cars4rent_options_toggle_header_marker iconadmin-left-open"></span>'
					. '</h3>'
					. '<div class="cars4rent_options_content cars4rent_options_toggle_content"'.(!empty($field['closed']) ? ' style="display:none;"' : '').'>';
			break;
		
			case 'accordion':
				cars4rent_storage_push_array('to_flags', 'nesting', 'accordion');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="cars4rent_options_container cars4rent_options_accordion'.esc_attr($divider).'">';
				echo '<div id="'.esc_attr($id).'" class="cars4rent_options_accordion_item">'
					. '<h3 id="'.esc_attr($id).'_title"'
					. ' class="cars4rent_options_accordion_header"'
					. (!empty($field['action']) ? ' onclick="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>' 
					. (!empty($field['icon']) ? '<span class="cars4rent_options_accordion_header_icon '.esc_attr($field['icon']).'"></span>' : '') 
					. ($field['title'])
					. '<span class="cars4rent_options_accordion_header_marker iconadmin-left-open"></span>'
					. '</h3>'
					. '<div id="'.esc_attr($id).'_content" class="cars4rent_options_content cars4rent_options_accordion_content">';
			break;
		
			case 'tab':
				cars4rent_storage_push_array('to_flags', 'nesting', 'tab');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="cars4rent_options_container cars4rent_options_tab'.esc_attr($divider).'">'
						. '<ul>' . trim(cars4rent_options_collect_tabs($field['type'], $field['start'])) . '</ul>';
				echo '<div id="'.esc_attr($id).'_content"  class="cars4rent_options_content cars4rent_options_tab_content">';
			break;
		
			case 'partition':
				cars4rent_storage_push_array('to_flags', 'nesting', 'partition');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="cars4rent_options_container cars4rent_options_partition'.esc_attr($divider).'">'
						. '<ul>' . trim(cars4rent_options_collect_tabs($field['type'], $field['start'])) . '</ul>';
				echo '<div id="'.esc_attr($id).'_content" class="cars4rent_options_content cars4rent_options_partition_content">';
			break;
		
			case 'hidden':
				echo '<input class="cars4rent_options_input cars4rent_options_input_hidden" type="hidden"'
					. ' name="'.esc_attr($id).'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '" />';
			break;
	
			case 'date':
				if (isset($field['style']) && $field['style']=='inline') {
					echo '<div class="cars4rent_options_input_date" id="'.esc_attr($id).'_calendar"'
						. ' data-format="' . (!empty($field['format']) ? $field['format'] : 'yy-mm-dd') . '"'
						. ' data-months="' . (!empty($field['months']) ? max(1, min(3, $field['months'])) : 1) . '"'
						. ' data-linked-field="' . (!empty($data['linked_field']) ? $data['linked_field'] : $id) . '"'
						. '></div>'
					. '<input id="'.esc_attr($id).'"'
						. ' data-param="'.esc_attr($id).'"'
						. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
						. ' type="hidden"'
						. ' value="' . esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
						. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
				} else {
					echo '<input class="cars4rent_options_input cars4rent_options_input_date' . (!empty($field['mask']) ? ' cars4rent_options_input_masked' : '') . '"'
						. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
						. ' id="'.esc_attr($id). '"'
						. ' data-param="'.esc_attr($id).'"'
						. ' type="text"'
						. ' value="' . esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. ' data-format="' . (!empty($field['format']) ? $field['format'] : 'yy-mm-dd') . '"'
						. ' data-months="' . (!empty($field['months']) ? max(1, min(3, $field['months'])) : 1) . '"'
						. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
						. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />'
					. ($before)
					. ($after);
				}
			break;
	
			case 'text':
				echo '<input class="cars4rent_options_input cars4rent_options_input_text' . (!empty($field['mask']) ? ' cars4rent_options_input_masked' : '') . '"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
				. ($before)
				. ($after);
			break;
			
			case 'textarea':
				$cols = isset($field['cols']) && $field['cols'] > 10 ? $field['cols'] : '40';
				$rows = isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : '8';
				echo '<textarea class="cars4rent_options_input cars4rent_options_input_textarea"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' cols="'.esc_attr($cols).'"'
					. ' rows="'.esc_attr($rows).'"'
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>'
					. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) 
					. '</textarea>';
			break;
			
			case 'editor':
				$cols = isset($field['cols']) && $field['cols'] > 10 ? $field['cols'] : '40';
				$rows = isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : '10';
				wp_editor( cars4rent_is_inherit_option($clone_val) ? '' : $clone_val, $id . ($cloneable ? '[]' : ''), array(
					'wpautop' => false,
					'textarea_rows' => $rows
				));
			break;
	
			case 'spinner':
				echo '<input class="cars4rent_options_input cars4rent_options_input_spinner' . (!empty($field['mask']) ? ' cars4rent_options_input_masked' : '') 
					. '" name="'.esc_attr($id). ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
					. (isset($field['min']) ? ' data-min="'.esc_attr($field['min']).'"' : '') 
					. (isset($field['max']) ? ' data-max="'.esc_attr($field['max']).'"' : '') 
					. (!empty($field['step']) ? ' data-step="'.esc_attr($field['step']).'"' : '') 
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />' 
					. '<span class="cars4rent_options_arrows"><span class="cars4rent_options_arrow_up iconadmin-up-dir"></span><span class="cars4rent_options_arrow_down iconadmin-down-dir"></span></span>';
			break;
	
			case 'tags':
				if (!cars4rent_is_inherit_option($clone_val)) {
					$tags = explode($to_delimiter, $clone_val);
					if (is_array($tags) && count($tags) > 0) {
						foreach ($tags as $tag) {
							if (empty($tag)) continue;
							echo '<span class="cars4rent_options_tag iconadmin-cancel">'.($tag).'</span>';
						}
					}
				}
				echo '<input class="cars4rent_options_input_tags"'
					. ' type="text"'
					. ' value=""'
					. ' />'
					. '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
						. ' type="hidden"'
						. ' data-param="'.esc_attr($id).'"'
						. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
			break;
			
			case "checkbox": 
				echo '<input type="checkbox" class="cars4rent_options_input cars4rent_options_input_checkbox"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' value="true"'
					. ($clone_val == 'true' ? ' checked="checked"' : '') 
					. (!empty($field['disabled']) ? ' readonly="readonly"' : '') 
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. '<label for="'.esc_attr($id).'" class="' . (!empty($field['disabled']) ? 'cars4rent_options_state_disabled' : '') . ($clone_val=='true' ? ' cars4rent_options_state_checked' : '').'"><span class="cars4rent_options_input_checkbox_image iconadmin-check"></span>' . (!empty($field['label']) ? $field['label'] : $field['title']) . '</label>';
			break;
			
			case "radio":
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						echo '<span class="cars4rent_options_radioitem">'
							.'<input class="cars4rent_options_input cars4rent_options_input_radio" type="radio"'
								. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
								. ' value="'.esc_attr($key) .'"'
								. ($clone_val == $key ? ' checked="checked"' : '') 
								. ' id="'.esc_attr(($id).'_'.($key)).'"'
								. ' />'
								. '<label for="'.esc_attr(($id).'_'.($key)).'"'. ($clone_val == $key ? ' class="cars4rent_options_state_checked"' : '') .'><span class="cars4rent_options_input_radio_image iconadmin-circle-empty'.($clone_val == $key ? ' iconadmin-dot-circled' : '') . '"></span>' . ($title) . '</label></span>';
					}
				}
				echo '<input type="hidden"'
						. ' value="' . esc_attr($clone_val) . '"'
						. ' data-param="' . esc_attr($id) . '"'
						. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
			break;
			
			case "switch":
				$opt = array();
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						$opt[] = array('key'=>$key, 'title'=>$title);
						if (count($opt)==2) break;
					}
				}
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) || empty($clone_val) ? $opt[0]['key'] : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. '<span class="cars4rent_options_switch'.($clone_val==$opt[1]['key'] ? ' cars4rent_options_state_off' : '').'"><span class="cars4rent_options_switch_inner iconadmin-circle"><span class="cars4rent_options_switch_val1" data-value="'.esc_attr($opt[0]['key']).'">'.($opt[0]['title']).'</span><span class="cars4rent_options_switch_val2" data-value="'.esc_attr($opt[1]['key']).'">'.($opt[1]['title']).'</span></span></span>';
			break;
	
			case 'media':
				echo '<input class="cars4rent_options_input cars4rent_options_input_text cars4rent_options_input_media"'
					. ' name="'.esc_attr($id).($cloneable ? '[]' : '').'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"' 
					. (!isset($field['readonly']) || $field['readonly'] ? ' readonly="readonly"' : '') 
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
				. ($before)
				. ($after);
				if (!empty($clone_val) && !cars4rent_is_inherit_option($clone_val)) {
					$info = pathinfo($clone_val);
					$ext = isset($info['extension']) ? $info['extension'] : '';
					echo '<a class="cars4rent_options_image_preview" data-rel="popup" target="_blank" href="'.esc_url($clone_val).'">'
							. (!empty($ext) && cars4rent_strpos('jpg,png,gif', $ext)!==false 
									? '<img src="'.esc_url($clone_val).'" alt="'.esc_attr__('media_image', 'cars4rent').'" />'
									: '<span>'.trim($info['basename']).'</span>'
								)
							. '</a>';
				}
			break;
			
			case 'button':
				list($button, $class) = cars4rent_options_action_button($field, 'button');
				cars4rent_show_layout($button);
			break;
	
			case 'range':
				echo '<div class="cars4rent_options_input_range" data-step="'.(!empty($field['step']) ? $field['step'] : 1).'">';
				echo '<span class="cars4rent_options_range_scale"><span class="cars4rent_options_range_scale_filled"></span></span>';
				if (cars4rent_strpos($clone_val, $to_delimiter)===false)
					$clone_val = max($field['min'], intval($clone_val));
				if (cars4rent_strpos($field['std'], $to_delimiter)!==false && cars4rent_strpos($clone_val, $to_delimiter)===false)
					$clone_val = ($field['min']).','.($clone_val);
				$sliders = explode($to_delimiter, $clone_val);
				foreach($sliders as $s) {
					echo '<span class="cars4rent_options_range_slider"><span class="cars4rent_options_range_slider_value">'.intval($s).'</span><span class="cars4rent_options_range_slider_button"></span></span>';
				}
				echo '<span class="cars4rent_options_range_min">'.($field['min']).'</span><span class="cars4rent_options_range_max">'.($field['max']).'</span>';
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
				echo '</div>';			
			break;
			
			case "checklist":
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						echo '<span class="cars4rent_options_listitem'
							. (cars4rent_strpos(($to_delimiter).($clone_val).($to_delimiter), ($to_delimiter).($key).($to_delimiter))!==false ? ' cars4rent_options_state_checked' : '') . '"'
							. ' data-value="'.esc_attr($key).'"'
							. '>'
							. esc_attr($title)
							. '</span>';
					}
				}
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
			
			case 'fonts':
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) {
						$field['options'][$key] = $key;
					}
				}
			case 'list':
			case 'select':
				if (!isset($field['options']) && !empty($field['from']) && !empty($field['to'])) {
					$field['options'] = array();
					for ($i = $field['from']; $i <= $field['to']; $i+=(!empty($field['step']) ? $field['step'] : 1)) {
						$field['options'][$i] = $i;
					}
				}
				list($list, $caption) = cars4rent_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<input class="cars4rent_options_input cars4rent_options_input_select" type="text" value="'.esc_attr($caption) . '"'
						. ' readonly="readonly"'
						. ' />'
						. ($before)
						. '<span class="cars4rent_options_field_after cars4rent_options_with_action iconadmin-down-open" onclick="cars4rent_options_action_show_menu(this);return false;"></span>';
				}
				cars4rent_show_layout($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
	
			case 'images':
				list($list, $caption) = cars4rent_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<div class="cars4rent_options_caption_image iconadmin-down-open">'
						.'<span style="background-image: url('.esc_url($caption).')"></span>'
						.'</div>';
				}
				cars4rent_show_layout($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
			
			case 'icons':
				if (isset($field['css']) && $field['css']!='' && file_exists($field['css'])) {
					$field['options'] = cars4rent_parse_icons_classes($field['css']);
				}
				list($list, $caption) = cars4rent_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<div class="cars4rent_options_caption_icon iconadmin-down-open"><span class="'.esc_attr($caption).'"></span></div>';
				}
				cars4rent_show_layout($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
	
			case 'socials':
				if (!is_array($clone_val)) $clone_val = array('url'=>'', 'icon'=>'');
				list($list, $caption) = cars4rent_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='icons') {
					list($after, $class) = cars4rent_options_action_button(array(
						'action' => empty($field['style']) || $field['style']=='icons' ? 'select_icon' : '',
						'icon' => (empty($field['style']) || $field['style']=='icons') && !empty($clone_val['icon']) ? $clone_val['icon'] : 'iconadmin-users'
						), 'after');
				} else
					$after = '';
				echo '<input class="cars4rent_options_input cars4rent_options_input_text cars4rent_options_input_socials' 
					. (!empty($field['mask']) ? ' cars4rent_options_input_masked' : '') . '"'
					. ' name="'.esc_attr($id).($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' type="text" value="'. esc_attr(cars4rent_is_inherit_option($clone_val['url']) ? '' : $clone_val['url']) . '"' 
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. ($after);
				if (!empty($field['style']) && $field['style']=='images') {
					echo '<div class="cars4rent_options_caption_image iconadmin-down-open">'
						.'<span style="background-image: url('.esc_url($caption).')"></span>'
						.'</div>';
				}
				cars4rent_show_layout($list);
				echo '<input name="'.esc_attr($id) . '_icon' . ($cloneable ? '[]' : '') .'" type="hidden" value="'. esc_attr(cars4rent_is_inherit_option($clone_val['icon']) ? '' : $clone_val['icon']) . '" />';
			break;
	
			case "color":
				$cp_style = isset($field['style']) ? $field['style'] : cars4rent_storage_get('to_colorpicker');
				echo '<input class="cars4rent_options_input cars4rent_options_input_color cars4rent_options_input_color_'.esc_attr($cp_style).'"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' id="'.esc_attr($id) . '"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' type="text"'
					. ' value="'. esc_attr(cars4rent_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="cars4rent_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. trim($before);
				if ($cp_style=='custom')
					echo '<span class="cars4rent_options_input_colorpicker iColorPicker"></span>';
				else if ($cp_style=='tiny')
					cars4rent_show_layout($after);
			break;   
	
			default:
				if (function_exists('cars4rent_show_custom_field')) {
					cars4rent_show_layout(cars4rent_show_custom_field($id, $field, $clone_val));
				}
			} 
	
			if ($cloneable) {
				echo '<input type="hidden" name="'.esc_attr($id) . '_numbers[]" value="'.esc_attr($clone_num).'" />'
					. '</div>';
			}
		}	
		}
	
		if (!cars4rent_options_is_group($field['type']) && $field['type'] != 'hidden') {
			if ($cloneable) {
				echo '<div class="cars4rent_options_input_button cars4rent_options_clone_button cars4rent_options_clone_button_add">'. esc_html__('+ Add item', 'cars4rent') .'</div>';
			}
			if (!cars4rent_storage_empty('to_flags', 'add_inherit') && isset($field['std']))
				echo  '<div class="cars4rent_options_content_inherit"'.($inherit ? '' : ' style="display:none;"').'><div>'.esc_html__('Inherit', 'cars4rent').'</div><input type="hidden" name="'.esc_attr($id).'_inherit" value="'.esc_attr($inherit).'" /></div>';
			if ( !in_array($field['type'], array('info', 'label', 'divider')))
				echo '</div>';
			if (!empty($field['desc']))
				echo '<div class="cars4rent_options_desc">' . ($field['desc']) .'</div>' . "\n";
			echo '</div>' . "\n";
		}
	}
}


// Ajax Save and Export Action handler
if ( !function_exists( 'cars4rent_options_save' ) ) {
    function cars4rent_options_save() {
		$mode = cars4rent_get_value_gp('mode');
		$override = empty($_POST['override']) ? 'general' : cars4rent_get_value_gp('override');
		$slug = empty($_POST['slug']) ? '' : cars4rent_get_value_gp('slug');
		
		if (!in_array($mode, array('save', 'reset', 'export')) || $override=='customizer')
			return;

		if ( !wp_verify_nonce( cars4rent_get_value_gp('nonce'), admin_url('admin-ajax.php') ) || !current_user_can('manage_options') )
			wp_die();
	

		$options = cars4rent_storage_get('options');
	
		if ($mode == 'save') {
			parse_str($_POST['data'], $post_data);
		} else if ($mode=='export') {
			parse_str($_POST['data'], $post_data);
			if (!cars4rent_storage_empty('post_override_options', 'fields')) {
				$options = cars4rent_array_merge(cars4rent_storage_get('options'), cars4rent_storage_get_array('post_override_options', 'fields'));
			}
		} else
			$post_data = array();
	
		$custom_options = array();
	
		cars4rent_options_merge_new_values($options, $custom_options, $post_data, $mode, $override);
	
		if ($mode=='export') {
			$name  = chop(cars4rent_get_value_gp('name'));
			$name2 = isset($_POST['name2']) ? chop(cars4rent_get_value_gp('name2')) : '';
			$key = $name=='' ? $name2 : $name;
			$export = get_option(cars4rent_storage_get('options_prefix') . '_options_export_'.($override), array());
			$export[$key] = $custom_options;
			if ($name!='' && $name2!='') unset($export[$name2]);
			update_option(cars4rent_storage_get('options_prefix') . '_options_export_'.($override), $export);
			$file = cars4rent_get_file_dir('core/core.options/core.options.txt');
			$url  = cars4rent_get_file_url('core/core.options/core.options.txt');
			$export = serialize($custom_options);
			cars4rent_fpc($file, $export);
			$response = array('error'=>'', 'data'=>$export, 'link'=>$url);
			echo json_encode($response);
		} else {
			update_option(cars4rent_storage_get('options_prefix') . '_options'.(!empty($slug) ? '_template_'.trim($slug) : ''), apply_filters('cars4rent_filter_save_options', $custom_options, $override, $slug));
			if ($override=='general') {
				cars4rent_load_main_options();
			}
		}
		
		wp_die();
	}
}


// Ajax Import Action handler
if ( !function_exists( 'cars4rent_options_import' ) ) {
	function cars4rent_options_import() {

		if ( !wp_verify_nonce( cars4rent_get_value_gp('nonce'), admin_url('admin-ajax.php') ) || !current_user_can('manage_options') )
			wp_die();
	
		$override = $_POST['override']=='' ? 'general' : cars4rent_get_value_gp('override');
		$text = stripslashes(trim(chop($_POST['text'])));
		if (!empty($text)) {
			$opt = cars4rent_unserialize($text);
		} else {
			$key = chop(cars4rent_get_value_gp('name2'));
			$import = get_option(cars4rent_storage_get('options_prefix') . '_options_export_'.($override), array());
			$opt = isset($import[$key]) ? $import[$key] : false;
		}
		$response = array('error'=>$opt===false ? esc_html__('Error while unpack import data!', 'cars4rent') : '', 'data'=>$opt);
		echo json_encode($response);
	
		wp_die();
	}
}

// Merge data from POST and current post/page/category/theme options
if ( !function_exists( 'cars4rent_options_merge_new_values' ) ) {
	function cars4rent_options_merge_new_values(&$post_options, &$custom_options, &$post_data, $mode, $override) {
		$need_save = false;
		if (is_array($post_options) && count($post_options) > 0) {
			foreach ($post_options as $id=>$field) { 
				if ($override!='general' && (!isset($field['override']) || !in_array($override, explode(',', $field['override'])))) continue;
				if (!isset($field['std'])) continue;
				if ($override!='general' && !isset($post_data[$id.'_inherit'])) continue;
				if ($id=='reviews_marks' && $mode=='export') continue;
				$need_save = true;
				if ($mode == 'save' || $mode=='export') {
					if ($override!='general' && cars4rent_is_inherit_option($post_data[$id.'_inherit']))
						$new = '';
					else if (isset($post_data[$id])) {
						// Prepare specific (combined) fields
						if (!empty($field['subset'])) {
							$sbs = $post_data[$field['subset']];
							$field['val'][$sbs] = $post_data[$id];
							$post_data[$id] = $field['val'];
						}   	
						if ($field['type']=='socials') {
							if (!empty($field['cloneable'])) {
								if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
									foreach($post_data[$id] as $k=>$v)
										$post_data[$id][$k] = array('url'=>strip_tags(stripslashes($v)), 'icon'=>stripslashes($post_data[$id.'_icon'][$k]));
								}
							} else {
								$post_data[$id] = array('url'=>strip_tags(stripslashes($post_data[$id])), 'icon'=>stripslashes($post_data[$id.'_icon']));
							}
						} else if (is_array($post_data[$id])) {
							if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
								foreach ($post_data[$id] as $k=>$v)
									$post_data[$id][$k] = strip_tags(stripslashes($v));
							}
						} else {
							$post_data[$id] = stripslashes($post_data[$id]);
							if (empty($field['allow_html'])) 
								$post_data[$id] = strip_tags($post_data[$id]);
							else if (is_array($field['allow_html'])) 
								$post_data[$id] = wp_kses($post_data[$id], $field['allow_html']);
							else if ($field['allow_html']===true && empty($field['allow_js']))
								$post_data[$id] = wp_kses_post($post_data[$id]);
						}
						// Add cloneable index
						if (!empty($field['cloneable'])) {
							$rez = array();
							if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
								foreach ($post_data[$id] as $k=>$v)
									$rez[$post_data[$id.'_numbers'][$k]] = $v;
							}
							$post_data[$id] = $rez;
						}   	
						$new = $post_data[$id];
						// Post type specific data handling
						if ($id == 'reviews_marks' && is_array($new)) {
							$new = join(',', $new);
							if (($avg = cars4rent_reviews_get_average_rating($new)) > 0) {
								$new = cars4rent_reviews_marks_to_save($new);
							}
						} else if ($id == 'reviews_criterias') {
							if (is_array($new) && count($new) > 1) {
								$rez = array();
								foreach ($new as $cr) {
									if (!empty($cr))
										$rez[] = $cr;
								}
								$new = $rez;
							}
						}
					} else
						$new = $field['type'] == 'checkbox' ? 'false' : '';
				} else {
					$new = $field['std'];
				}
				$custom_options[$id] = $new!=='' || $override=='general' ? $new : 'inherit';
			}
	    }
		return $need_save;
	}
}



// Load default theme options
require_once CARS4RENT_THEME_PATH . 'includes/theme.options.php';

// Load inheritance system
require_once CARS4RENT_FW_PATH . 'core/core.options/core.options-inheritance.php';

// Load custom fields
if (is_admin()) {
	require_once CARS4RENT_FW_PATH . 'core/core.options/core.options-custom.php';
}
?>