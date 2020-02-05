<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('cars4rent_action_theme_styles_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_action_theme_styles_theme_setup', 1 );
	function cars4rent_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('cars4rent_filter_used_fonts',			'cars4rent_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('cars4rent_filter_list_fonts',			'cars4rent_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('cars4rent_action_add_styles',			'cars4rent_action_theme_styles_add_styles');
		// Add theme inline styles
		add_filter('cars4rent_filter_add_styles_inline',		'cars4rent_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('cars4rent_action_add_scripts',			'cars4rent_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_filter('cars4rent_filter_localize_script',		'cars4rent_filter_theme_styles_localize_script');

		// Add theme less files into list for compilation
		add_filter('cars4rent_filter_compile_less',			'cars4rent_filter_theme_styles_compile_less');

		// Add color schemes
		cars4rent_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'cars4rent'),
			
			// Whole block border and background
			'bd_color'				=> '#e2e2e2',
			'bg_color'				=> '#f2f2f2',
			
			// Headers, text and links colors
			'text'					=> '#828282',
			'text_light'			=> '#a1a1a1',
			'text_dark'				=> '#3a3a3a',
			'text_link'				=> '#ef9e36',
			'text_hover'			=> '#e95f2b',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8a8a',
			'input_light'			=> '#acb4b6',
			'input_dark'			=> '#232a34',
			'input_bd_color'		=> '#dddddd',
			'input_bd_hover'		=> '#bbbbbb',
			'input_bg_color'		=> '#f7f7f7',
			'input_bg_hover'		=> '#f0f0f0',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#9c9c9c',
			'alter_light'			=> '#cbcbcb',
			'alter_dark'			=> '#2e2e2e',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f7f7f7',
			'alter_bg_hover'		=> '#f0f0f0',
			)
		);


		// Add color schemes
		cars4rent_add_color_scheme('color_blocks', array(

			'title'					=> esc_html__('Color blocks', 'cars4rent'),
			
			// Whole block border and background
			'bd_color'				=> '#1DB3B6',
			'bg_color'				=> '#20C7CA',

			// Headers, text and links colors
			'text'					=> '#F0F0F0',
			'text_light'			=> '#E0E0E0',
			'text_dark'				=> '#FFFFFF',
			'text_link'				=> '#1D9B9D',
			'text_hover'			=> '#23E8EB',

			// Inverse colors
			'inverse_text'			=> '#F0F0F0',
			'inverse_light'			=> '#E0F0F0',
			'inverse_dark'			=> '#FFFFFF',
			'inverse_link'			=> '#FCFFA3',
			'inverse_hover'			=> '#FFFF00',
		
			// Input fields
			'input_text'			=> '#DADADA',
			'input_light'			=> '#B4B8B8',
			'input_dark'			=> '#FFFFFF',
			'input_bd_color'		=> '#06564E',
			'input_bd_hover'		=> '#017E72',
			'input_bg_color'		=> '#0F7468',
			'input_bg_hover'		=> '#108678',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#DADADA',
			'alter_light'			=> '#B4B8B8',
			'alter_dark'			=> '#FFFFFF',
			'alter_link'			=> '#CAB720',
			'alter_hover'			=> '#998B18',
			'alter_bd_color'		=> '#06564E',
			'alter_bd_hover'		=> '#017E72',
			'alter_bg_color'		=> '#0F7468',
			'alter_bg_hover'		=> '#108678',
			)
		);

		// Add Custom fonts
		cars4rent_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '4.286em',
			'font-weight'	=> '800',
			'font-style'	=> 'i',
			'line-height'	=> '1em',
			'margin-top'	=> '1.125em',
			'margin-bottom'	=> '0.65em'
			)
		);
		cars4rent_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.857em',
			'font-weight'	=> '800',
			'font-style'	=> 'i',
			'line-height'	=> '1em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '1.9em'
			)
		);
		cars4rent_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.143em',
			'font-weight'	=> '800',
			'font-style'	=> 'i',
			'line-height'	=> '1em',
			'margin-top'	=> '2.02em',
			'margin-bottom'	=> '1.25em'
			)
		);
		cars4rent_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.429em',
			'font-weight'	=> '800',
			'font-style'	=> 'i',
			'line-height'	=> '1em',
			'margin-top'	=> '2.15em',
			'margin-bottom'	=> '1.25em'
			)
		);
		cars4rent_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.286em',
			'font-weight'	=> '800',
			'font-style'	=> 'i',
			'line-height'	=> '1.2em',
			'margin-top'	=> '1.35em',
			'margin-bottom'	=> '0.25em'
			)
		);
		cars4rent_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.071em',
			'font-weight'	=> '800',
			'font-style'	=> 'i',
			'line-height'	=> '1.47em',
			'margin-top'	=> '3em',
			'margin-bottom'	=> '2.8em'
			)
		);
		cars4rent_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> 'Open Sans',
			'font-size' 	=> '14px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.715em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		cars4rent_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		cars4rent_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.786em',
			'font-weight'	=> '800',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '',
			'margin-bottom'	=> '2.72em'
			)
		);
		cars4rent_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.929em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '1.8em',
			'margin-bottom'	=> '1.8em'
			)
		);
		cars4rent_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		cars4rent_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1em',
			'margin-top'	=> '3.6em',
			'margin-bottom'	=> '2.1em'
			)
		);
		cars4rent_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '0.857em',
			'font-weight'	=> '800',
			'font-style'	=> 'i',
			'line-height'	=> '1em'
			)
		);
		cars4rent_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'cars4rent'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('cars4rent_filter_theme_styles_used_fonts')) {
	function cars4rent_filter_theme_styles_used_fonts($theme_fonts) {
		$theme_fonts['Open Sans'] = 1;
		return $theme_fonts;
	}
}

// Add theme fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
if (!function_exists('cars4rent_filter_theme_styles_list_fonts')) {
	function cars4rent_filter_theme_styles_list_fonts($list) {
		 Example:
		 if (!isset($list['Open Sans'])) {
				$list['Open Sans'] = array(
					'family' => 'sans-serif',																						// (required) font family
					'link'   => 'Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic',	// (optional) if you use Google font repository
					);
		 }
		return $list;
	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('cars4rent_filter_theme_styles_compile_less')) {
	function cars4rent_filter_theme_styles_compile_less($files) {
		if (file_exists(cars4rent_get_file_dir('css/theme.less'))) {
		 	$files[] = cars4rent_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('cars4rent_action_theme_styles_add_styles')) {
	function cars4rent_action_theme_styles_add_styles() {
		// Add stylesheet files only if LESS supported
		if ( cars4rent_get_theme_setting('less_compiler') != 'no' ) {
			wp_enqueue_style( 'cars4rent-theme-style', cars4rent_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'cars4rent-theme-style', cars4rent_get_inline_css() );
		}
	}
}

// Add theme inline styles
if (!function_exists('cars4rent_filter_theme_styles_add_styles_inline')) {
	function cars4rent_filter_theme_styles_add_styles_inline($custom_style) {
		// Todo: add theme specific styles in the $custom_style to override

		// Submenu width
		$menu_width = cars4rent_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = cars4rent_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = cars4rent_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = cars4rent_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		// Custom css from theme options
		$custom_style .= cars4rent_get_custom_option('custom_css');

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('cars4rent_action_theme_styles_add_scripts')) {
	function cars4rent_action_theme_styles_add_scripts() {
		if (cars4rent_get_theme_option('show_theme_customizer') == 'yes' && file_exists(cars4rent_get_file_dir('js/theme.customizer.js')))
			wp_enqueue_script( 'cars4rent-theme-styles-customizer-script', cars4rent_get_file_url('js/theme.customizer.js'), array(), null );
	}
}

// Add theme scripts inline
if (!function_exists('cars4rent_filter_theme_styles_localize_script')) {
	function cars4rent_filter_theme_styles_localize_script($vars) {
		if (empty($vars['theme_font']))
			$vars['theme_font'] = cars4rent_get_custom_font_settings('p', 'font-family');
		$vars['theme_color'] = cars4rent_get_scheme_color('text_dark');
		$vars['theme_bg_color'] = cars4rent_get_scheme_color('bg_color');
		return $vars;
	}
}
?>