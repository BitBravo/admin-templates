<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'cars4rent_template_header_1_theme_setup' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_template_header_1_theme_setup', 1 );
	function cars4rent_template_header_1_theme_setup() {
		cars4rent_add_template(array(
			'layout' => 'header_1',
			'mode'   => 'header',
			'title'  => esc_html__('Header 1', 'cars4rent'),
			'icon'   => cars4rent_get_file_url('templates/headers/images/1.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'cars4rent_template_header_1_output' ) ) {
	function cars4rent_template_header_1_output($post_options, $post_data) {

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background-image: url('.esc_url($header_image).')"' 
				: '';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_1 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_1 top_panel_position_<?php echo esc_attr(cars4rent_get_custom_option('top_panel_position')); ?>">
			
			<div class="top_panel_middle" <?php cars4rent_show_layout($header_css); ?>>
				<div class="content_wrap">
						<div class="contact_logo">
							<?php cars4rent_show_logo(); ?>
						</div>

							<div class="contact_area">
									<?php
									cars4rent_template_set_args('top-panel-top', array(
										'top_panel_top_components' => array('contact_info', 'login')
									));
									get_template_part(cars4rent_get_file_slug('templates/headers/_parts/top-panel-top.php'));
									?>
							</div>

				</div>
			</div>

			<div class="top_panel_bottom">
				<div class="content_wrap clearfix">
					<nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(cars4rent_get_theme_option('menu_hover')); ?>">
						<?php
						$menu_main = cars4rent_get_nav_menu('menu_main');
						if (empty($menu_main)) $menu_main = cars4rent_get_nav_menu();
                        cars4rent_show_layout($menu_main);
						?>
					</nav>
					<?php if (cars4rent_get_custom_option('show_search')=='yes' && function_exists('cars4rent_sc_search')) cars4rent_show_layout(cars4rent_sc_search(array("style"=>cars4rent_get_theme_option('search_style')))); ?>
				</div>
			</div>

			</div>
		</header>

		<?php
		cars4rent_storage_set('header_mobile', array(
			 'open_hours' => false,
			 'login' => false,
			 'socials' => false,
			 'bookmarks' => false,
			 'contact_address' => false,
			 'contact_phone_email' => false,
			 'woo_cart' => false,
			 'search' => false
			)
		);
	}
}
?>