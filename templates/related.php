<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'cars4rent_template_related_theme_setup' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_template_related_theme_setup', 1 );
	function cars4rent_template_related_theme_setup() {
		cars4rent_add_template(array(
			'layout' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => esc_html__('Related posts /no columns/', 'cars4rent'),
			'thumb_title'  => esc_html__('Medium square image (crop)R', 'cars4rent'),
			'w'		 => 370,
			'h'		 => 192
		));
		cars4rent_add_template(array(
			'layout' => 'related_2',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => esc_html__('Related posts /2 columns/', 'cars4rent'),
			'thumb_title'  => esc_html__('Medium square image (crop)R', 'cars4rent'),
			'w'		 => 370,
			'h'		 => 192
		));
		cars4rent_add_template(array(
			'layout' => 'related_3',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => esc_html__('Related posts /3 columns/', 'cars4rent'),
			'thumb_title'  => esc_html__('Medium square image (crop)R', 'cars4rent'),
			'w'		 => 370,
			'h'		 => 192
		));
		cars4rent_add_template(array(
			'layout' => 'related_4',
			'template' => 'related',
			'mode'   => 'blog',
			'need_columns' => true,
			'need_terms' => true,
			'title'  => esc_html__('Related posts /4 columns/', 'cars4rent'),
			'thumb_title'  => esc_html__('Medium square image (crop)R', 'cars4rent'),
			'w'		 => 370,
			'h'		 => 192
		));
	}
}

// Template output
if ( !function_exists( 'cars4rent_template_related_output' ) ) {
	function cars4rent_template_related_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = cars4rent_in_shortcode_blogger(true) ? 'div' : 'article';
		if ($columns > 1) {
			?><div class="<?php echo 'column-1_'.esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
		<<?php cars4rent_show_layout($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['number']); ?>">

			<div class="post_content">
				<?php if ($post_data['post_video'] || $post_data['post_thumb'] || $post_data['post_gallery']) { ?>
				<div class="post_featured">
					<?php
					cars4rent_template_set_args('post-featured', array(
						'post_options' => $post_options,
						'post_data' => $post_data
					));
					get_template_part(cars4rent_get_file_slug('templates/_parts/post-featured.php'));
					?>
				</div>
				<?php }
				if ($post_data['post_type']=='cars') {
					$post_meta = get_post_meta($post_data['post_id'], 'cars4rent_cars_data', true);
					?>
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
					<?php
				} else {
					if ($show_title) { ?>
						<div class="post_content_wrap">
							<?php

							if (!isset($post_options['links']) || $post_options['links']) {
								?><h5 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php cars4rent_show_layout($post_data['post_title']); ?></a></h5><?php
							} else {
								?><h5 class="post_title"><?php cars4rent_show_layout($post_data['post_title']); ?></h5><?php
							}


							if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
								?><div class="post_info post_info_tags"><?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></div><?php
							}
							?>
						</div>
					<?php }
				}
				?>
			</div>	<!-- /.post_content -->
		</<?php cars4rent_show_layout($tag); ?>>	<!-- /.post_item -->
		<?php
		if ($columns > 1) {
			?></div><?php
		}
	}
}
?>