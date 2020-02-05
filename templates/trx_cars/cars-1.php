<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'cars4rent_template_cars_1_theme_setup' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_template_cars_1_theme_setup', 1 );
	function cars4rent_template_cars_1_theme_setup() {
		cars4rent_add_template(array(
			'layout' => 'cars-1',
			'template' => 'cars-1',
			'mode'   => 'cars',
			'title'  => esc_html__('Cars /Style 1/', 'cars4rent'),
			'thumb_title'  => esc_html__('Medium square image (crop)C2', 'cars4rent'),
			'w' => 370,
			'h' => 192
		));
	}
}

// Template output
if ( !function_exists( 'cars4rent_template_cars_1_output' ) ) {
	function cars4rent_template_cars_1_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		$post_meta = get_post_meta($post_data['post_id'], 'cars4rent_cars_data', true);
		if (cars4rent_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_cars_item sc_cars_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!cars4rent_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(cars4rent_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<div class="sc_cars_item_avatar post_featured">
					<?php
					cars4rent_template_set_args('post-featured', array(
						'post_options' => $post_options,
						'post_data' => $post_data
					));
					get_template_part(cars4rent_get_file_slug('templates/_parts/post-featured.php'));
					?>
				</div>
				<div class="sc_cars_item_info">
					<div class="main_info">
						<?php
						if ($show_title) {
							if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
								?><h4 class="car_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php cars4rent_show_layout($post_data['post_title']); ?></a></h4><?php
							} else {
								?><h4 class="car_title"><?php cars4rent_show_layout($post_data['post_title']); ?></h4><?php
							}
						}
						?>
						<h6 class="car_classification"><?php cars4rent_show_layout($post_meta['car_classification']); ?></h6>
					</div>

					<div class="car_price icon-tag-1"><span><?php cars4rent_show_layout($post_meta['car_price']); ?></span></div>

					<div class="car_meta"><?php
						if (!empty($post_meta['car_year'])) {
							?><div><?php cars4rent_show_layout($post_meta['car_year']); ?></div><?php
						}
						if (!empty($post_meta['car_gear_type'])) {
							?><div><?php cars4rent_show_layout($post_meta['car_gear_type']); ?></div><?php
						}
						if (($post_meta['car_air_condition'])=='Yes') {
							?><div><?php esc_attr_e('Air condition', 'cars4rent');; ?></div><?php
						}
						?></div><?php

					?>
				</div>
			</div>
		<?php
		if (cars4rent_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>