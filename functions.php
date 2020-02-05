<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'cars4rent_theme_setup' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_theme_setup', 1 );
	function cars4rent_theme_setup() {


		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );

		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));

		// Custom backgrounds setup
		add_theme_support( 'custom-background');

		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') );

		// Autogenerate title tag
		add_theme_support('title-tag');

		// Add user menu
		add_theme_support('nav-menus');

		// WooCommerce Support
		add_theme_support( 'woocommerce' );

		// Gutenberg support
		add_theme_support( 'align-wide' );

		// Register theme menus
		add_filter( 'cars4rent_filter_add_theme_menus',		'cars4rent_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'cars4rent_filter_add_theme_sidebars',	'cars4rent_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'cars4rent_filter_importer_options',		'cars4rent_set_importer_options' );

		// Add theme required plugins
		add_filter( 'cars4rent_filter_required_plugins',		'cars4rent_add_required_plugins' );
		
		// Add preloader styles
		add_filter('cars4rent_filter_add_styles_inline',		'cars4rent_head_add_page_preloader_styles');

		// Init theme after WP is created
		add_action( 'wp',									'cars4rent_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 							'cars4rent_body_classes' );

		add_filter( 'comment_form_fields', 'cars4rent_move_comment_field_to_bottom' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',								'cars4rent_head_add_page_meta', 0);
		add_action('before',								'cars4rent_body_add_gtm');
		add_action('before',								'cars4rent_body_add_toc');
		add_action('before',								'cars4rent_body_add_page_preloader');

		// Add data to the footer (priority 1, because priority 2 used for localize scripts)
		add_action('wp_footer',								'cars4rent_footer_add_views_counter', 1);
		add_action('wp_footer',								'cars4rent_footer_add_theme_customizer', 1);
		add_action('wp_footer',								'cars4rent_footer_add_scroll_to_top', 1);
		add_action('wp_footer',								'cars4rent_footer_add_custom_html', 1);
		add_action('wp_footer',								'cars4rent_footer_add_gtm2', 1);

		// Set list of the theme required plugins
		cars4rent_storage_set('required_plugins', array(
			'booked',
			'contact-form-7',
			'essgrids',
			'revslider',
			'mailchimp',
			'trx_utils',
			'visual_composer',
			'wp-gdpr-compliance'
			)
		);

		// Set list of the theme required custom fonts from folder /css/font-faces
		// Attention! Font's folder must have name equal to the font's name
		cars4rent_storage_set('required_custom_fonts', array(
			'Amadeus'
			)
		);

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'cars4rent_add_theme_menus' ) ) {
	function cars4rent_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'cars4rent_add_theme_sidebars' ) ) {
	function cars4rent_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'cars4rent' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'cars4rent' )
			);
			if (function_exists('cars4rent_exists_woocommerce') && cars4rent_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'cars4rent' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}

if ( !function_exists( 'cars4rent_move_comment_field_to_bottom' ) ) {
	function cars4rent_move_comment_field_to_bottom( $fields ) {
		$comment_field = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $comment_field;
		return $fields;
	}
}



// Add theme required plugins
if ( !function_exists( 'cars4rent_add_required_plugins' ) ) {
	function cars4rent_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('Themerex Utilities', 'cars4rent'),
			'version'	=> '3.4',
			'slug' 		=> 'trx_utils',
			'source'	=> cars4rent_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		$plugins[] = array(
			'name' 		=> esc_html__('Contact Form 7', 'cars4rent'),
			'slug' 		=> 'contact-form-7',
			'required' 	=> false
		);
		$plugins[] = array(
			'name' 		=> esc_html__('Contact Form 7 Datepicker', 'cars4rent'),
			'slug' 		=> 'contact-form-7-datepicker',
			'source'	=> cars4rent_get_file_dir('plugins/install/contact-form-7-datepicker.zip'),
			'required' 	=> false
		);
		$plugins[] = array(
			'name' 		=> esc_html__('WordPress Social Login', 'cars4rent'),
			'slug' 		=> 'wordpress-social-login',
			'required' 	=> false
		);
		return $plugins;
	}
}

if ( !function_exists( 'cars4rent_add_trx_utils' ) ) {
	add_filter( 'trx_utils_active', 'cars4rent_add_trx_utils' );
	function cars4rent_add_trx_utils($enable=true) {
		return true;
	}
}


//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( ! function_exists( 'cars4rent_importer_set_options' ) ) {
	add_filter( 'trx_utils_filter_importer_options', 'cars4rent_importer_set_options', 9 );
	function cars4rent_importer_set_options( $options=array() ) {
		if ( is_array( $options ) ) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Prepare demo data
			if ( is_dir( CARS4RENT_THEME_PATH . 'demo/' ) ) {
				$options['demo_url'] = CARS4RENT_THEME_PATH . 'demo/';
			} else {
				$options['demo_url'] = esc_url( cars4rent_get_protocol().'://demofiles.axiomthemes.com/cars4rent/' ); // Demo-site domain
			}

			// Required plugins
			$options['required_plugins'] =  array(
				'booked',
				'essential-grid',
				'contact-form-7',
				'revslider',
				'mailchimp-for-wp',
				'js_composer',
			);

			$options['theme_slug'] = 'cars4rent';

			// Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
			// Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
			$options['regenerate_thumbnails'] = 3;
			// Default demo
			$options['files']['default']['title'] = esc_html__( 'Cars4Rent Demo', 'cars4rent' );
			$options['files']['default']['domain_dev'] = esc_url(cars4rent_get_protocol().'://cars4rent.axiomthemes.com'); // Developers domain
			$options['files']['default']['domain_demo']= esc_url(cars4rent_get_protocol().'://cars4rent.axiomthemes.com'); // Demo-site domain

		}
		return $options;
	}
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('cars4rent_body_classes') ) {
	function cars4rent_body_classes( $classes ) {

		$classes[] = 'cars4rent_body';
		$classes[] = 'body_style_' . trim(cars4rent_get_custom_option('body_style'));
		$classes[] = 'body_' . (cars4rent_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'article_style_' . trim(cars4rent_get_custom_option('article_style'));
		
		$blog_style = cars4rent_get_custom_option(is_singular() && !cars4rent_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(cars4rent_get_template_name($blog_style));
		
		$body_scheme = cars4rent_get_custom_option('body_scheme');
		if (empty($body_scheme)  || cars4rent_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = cars4rent_get_custom_option('top_panel_position');
		if (!cars4rent_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = cars4rent_get_sidebar_class();

		if (cars4rent_get_custom_option('show_video_bg')=='yes' && (cars4rent_get_custom_option('video_bg_youtube_code')!='' || cars4rent_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (!cars4rent_param_is_off(cars4rent_get_theme_option('page_preloader')))
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('cars4rent_head_add_page_meta')) {
	function cars4rent_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (cars4rent_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('cars4rent_head_add_page_preloader_styles')) {
	function cars4rent_head_add_page_preloader_styles($css) {
		if (($preloader=cars4rent_get_theme_option('page_preloader'))!='none') {
			$image = cars4rent_get_theme_option('page_preloader_image');
			$bg_clr = cars4rent_get_scheme_color('bg_color');
			$link_clr = cars4rent_get_scheme_color('text_link');
			$css .= '
				#page_preloader {
					background-color: '. esc_attr($bg_clr) . ';'
					. ($preloader=='custom' && $image
						? 'background-image:url('.esc_url($image).');'
						: ''
						)
				    . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
		}
		return $css;
	}
}

// Add gtm code to the beginning of the body 
if (!function_exists('cars4rent_body_add_gtm')) {
	function cars4rent_body_add_gtm() {
		cars4rent_show_layout((cars4rent_get_custom_option('gtm_code')));
	}
}

// Add TOC anchors to the beginning of the body 
if (!function_exists('cars4rent_body_add_toc')) {
	function cars4rent_body_add_toc() {
		// Add TOC items 'Home' and "To top"
		if (cars4rent_get_custom_option('menu_toc_home')=='yes' && function_exists('cars4rent_sc_anchor'))
			cars4rent_show_layout(cars4rent_sc_anchor(array(
				'id' => "toc_home",
				'title' => esc_html__('Home', 'cars4rent'),
				'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'cars4rent'),
				'icon' => "icon-home",
				'separator' => "yes",
				'url' => esc_url(home_url('/'))
				)
			)); 
		if (cars4rent_get_custom_option('menu_toc_top')=='yes' && function_exists('cars4rent_sc_anchor'))
			cars4rent_show_layout(cars4rent_sc_anchor(array(
				'id' => "toc_top",
				'title' => esc_html__('To Top', 'cars4rent'),
				'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'cars4rent'),
				'icon' => "icon-double-up",
				'separator' => "yes")
				)); 
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('cars4rent_body_add_page_preloader')) {
	function cars4rent_body_add_page_preloader() {
		if ( ($preloader=cars4rent_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=cars4rent_get_theme_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}


// Return text for the Privacy Policy checkbox
if ( ! function_exists('cars4rent_get_privacy_text' ) ) {
	function cars4rent_get_privacy_text() {
		$page = get_option( 'wp_page_for_privacy_policy' );
		$privacy_text = cars4rent_get_theme_option( 'privacy_text' );
		return apply_filters( 'cars4rent_filter_privacy_text', wp_kses_post(
				$privacy_text
				. ( ! empty( $page ) && ! empty( $privacy_text )
					// Translators: Add url to the Privacy Policy page
					? ' ' . sprintf( __( 'For further details on handling user data, see our %s', 'cars4rent' ),
						'<a href="' . esc_url( get_permalink( $page ) ) . '" target="_blank">'
						. __( 'Privacy Policy', 'cars4rent' )
						. '</a>' )
					: ''
				)
			)
		);
	}
}


// Return template for the single field in the comments
if ( ! function_exists( 'cars4rent_single_comments_field' ) ) {
	function cars4rent_single_comments_field( $args ) {
		$path_height = 'path' == $args['form_style']
			? ( 'text' == $args['field_type'] ? 75 : 190 )
			: 0;
		$html = '<div class="comments_field comments_' . esc_attr( $args['field_name'] ) . '">'
			. ( 'default' == $args['form_style'] && 'checkbox' != $args['field_type']
				? '<label for="' . esc_attr( $args['field_name'] ) . '" class="' . esc_attr( $args['field_req'] ? 'required' : 'optional' ) . '">' . esc_html( $args['field_title'] ) . '</label>'
				: ''
			)
			. '<span class="sc_form_field_wrap">';
		if ( 'text' == $args['field_type'] ) {
			$html .= '<input id="' . esc_attr( $args['field_name'] ) . '" name="' . esc_attr( $args['field_name'] ) . '" type="text"' . ( 'default' == $args['form_style'] ? ' placeholder="' . esc_attr( $args['field_placeholder'] ) . ( $args['field_req'] ? ' *' : '' ) . '"' : '' ) . ' value="' . esc_attr( $args['field_value'] ) . '"' . ( $args['field_req'] ? ' aria-required="true"' : '' ) . ' />';
		} elseif ( 'checkbox' == $args['field_type'] ) {
			$html .= '<input id="' . esc_attr( $args['field_name'] ) . '" name="' . esc_attr( $args['field_name'] ) . '" type="checkbox" value="' . esc_attr( $args['field_value'] ) . '"' . ( $args['field_req'] ? ' aria-required="true"' : '' ) . ' />'
				. ' <label for="' . esc_attr( $args['field_name'] ) . '" class="' . esc_attr( $args['field_req'] ? 'required' : 'optional' ) . '">' . wp_kses_post( $args['field_title'] ) . '</label>';
		} else {
			$html .= '<textarea id="' . esc_attr( $args['field_name'] ) . '" name="' . esc_attr( $args['field_name'] ) . '"' . ( 'default' == $args['form_style'] ? ' placeholder="' . esc_attr( $args['field_placeholder'] ) . ( $args['field_req'] ? ' *' : '' ) . '"' : '' ) . ( $args['field_req'] ? ' aria-required="true"' : '' ) . '></textarea>';
		}
		if ( 'default' != $args['form_style'] ) {
			$html .= '<span class="sc_form_field_hover">'
				. ( 'path' == $args['form_style']
					? '<svg class="sc_form_field_graphic" preserveAspectRatio="none" viewBox="0 0 520 ' . intval( $path_height ) . '" height="100%" width="100%"><path d="m0,0l520,0l0,' . intval( $path_height ) . 'l-520,0l0,-' . intval( $path_height ) . 'z"></svg>'
					: ''
				)
				. ( 'iconed' == $args['form_style']
					? '<i class="sc_form_field_icon ' . esc_attr( $args['field_icon'] ) . '"></i>'
					: ''
				)
				. '<span class="sc_form_field_content" data-content="' . esc_attr( $args['field_title'] ) . '">' . wp_kses_post( $args['field_title'] ) . '</span>'
				. '</span>';
		}
		$html .= '</span></div>';
		return $html;
	}
}


// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('cars4rent_footer_add_views_counter')) {
	function cars4rent_footer_add_views_counter() {
		// Post/Page views counter
		get_template_part(cars4rent_get_file_slug('templates/_parts/views-counter.php'));
	}
}

// Add theme customizer
if (!function_exists('cars4rent_footer_add_theme_customizer')) {
	function cars4rent_footer_add_theme_customizer() {
		// Front customizer
		if (cars4rent_get_custom_option('show_theme_customizer')=='yes') {
			require_once CARS4RENT_FW_PATH . 'core/core.customizer/front.customizer.php';
		}
	}
}

// Add scroll to top button
if (!function_exists('cars4rent_footer_add_scroll_to_top')) {
	function cars4rent_footer_add_scroll_to_top() {
		?><a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'cars4rent'); ?>"></a><?php
	}
}

// Add custom html
if (!function_exists('cars4rent_footer_add_custom_html')) {
	function cars4rent_footer_add_custom_html() {
		?><div class="custom_html_section"><?php
			cars4rent_show_layout((cars4rent_get_custom_option('custom_code')));
		?></div><?php
	}
}

// Add gtm code
if (!function_exists('cars4rent_footer_add_gtm2')) {
	function cars4rent_footer_add_gtm2() {
		cars4rent_show_layout((cars4rent_get_custom_option('gtm_code2')));
	}
}


// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>