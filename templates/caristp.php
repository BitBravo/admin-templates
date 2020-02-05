<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'cars4rent_template_caristp_theme_setup' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_template_caristp_theme_setup', 1 );
	function cars4rent_template_caristp_theme_setup() {
		cars4rent_add_template(array(
			'layout' => 'caristp_2',
			'template' => 'caristp',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Cars /2 columns/ (Only for car)', 'cars4rent'),
			'thumb_title'  => esc_html__('Medium image c', 'cars4rent'),
			'w'		 => 370,
			'h' => 192,
		));
		cars4rent_add_template(array(
			'layout' => 'caristp_3',
			'template' => 'caristp',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Cars /3 columns/ (Only for car)', 'cars4rent'),
			'thumb_title'  => esc_html__('Medium image c', 'cars4rent'),
			'w'		 => 370,
			'h' => 192,
		));
		cars4rent_add_template(array(
			'layout' => 'caristp_4',
			'template' => 'caristp',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Cars /4 columns/ (Only for car)', 'cars4rent'),
			'thumb_title'  => esc_html__('Medium image c', 'cars4rent'),
			'w'		 => 370,
			'h' => 192,
		));
		// Add template specific scripts
		add_action('cars4rent_action_blog_scripts', 'cars4rent_template_caristp_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('cars4rent_template_caristp_add_scripts')) {
	function cars4rent_template_caristp_add_scripts($style) {
		if (in_array(cars4rent_substr($style, 0, 8), array('classic_', 'caristp_'))) {
			wp_enqueue_script( 'isotope', cars4rent_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
		}
	}
}

// Template output
if ( !function_exists( 'cars4rent_template_caristp_output' ) ) {
	function cars4rent_template_caristp_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = cars4rent_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>
					<?php
					if ($post_options['filters'] != '') {
						if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
						else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
					}
					?>">
			<<?php cars4rent_show_layout($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
				 <?php echo ' post_format_'.esc_attr($post_data['post_format']) 
					. ($post_options['number']%2==0 ? ' even' : ' odd') 
					. ($post_options['number']==0 ? ' first' : '') 
					. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
				?>">
				
				<?php if ($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) { ?>
					<div class="post_featured">
						<?php
						cars4rent_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(cars4rent_get_file_slug('templates/_parts/post-featured.php'));
						?>
					</div>
				<?php } ?>

				<div class="post_content isotope_item_content">

					<?php
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
					}
					?>

				</div>				<!-- /.post_content -->
			</<?php cars4rent_show_layout($tag); ?>>	<!-- /.post_item -->
		</div>						<!-- /.isotope_item -->
		<?php
	}
}
?>