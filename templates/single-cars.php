<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'cars4rent_template_single_cars_theme_setup' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_template_single_cars_theme_setup', 1 );
	function cars4rent_template_single_cars_theme_setup() {
		cars4rent_add_template(array(
			'layout' => 'single-cars',
			'mode'   => 'single_cars',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single Team member', 'cars4rent'),
			'thumb_title'  => esc_html__('Large image (crop) C1', 'cars4rent'),
			'w'		 => 470,
			'h'		 => 360
		));
	}
}

// Template output
if ( !function_exists( 'cars4rent_template_single_cars_output' ) ) {
	function cars4rent_template_single_cars_output($post_options, $post_data) {
		$post_data['post_views']++;
		$show_title = cars4rent_get_custom_option('show_post_title')=='yes';
		$title_tag = cars4rent_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';
        $post_meta = get_post_meta($post_data['post_id'], 'cars4rent_cars_data', true);

		cars4rent_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single_cars'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/Article'
				. '">');

		if ($show_title && $post_options['location'] == 'center' && cars4rent_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="headline" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php cars4rent_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(cars4rent_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])
		)) {
			?>
			<div class="columns_wrap">
				<section class="post_featured car_post_featured column-5_12">
					<?php
					if (!empty($post_options['dedicated'])) {
                        cars4rent_show_layout($post_options['dedicated']);
					} else {
						cars4rent_enqueue_popup();
						?>
						<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
							<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php cars4rent_show_layout($post_data['post_thumb']); ?></a>
						</div>
						<?php
					}

					?>
				</section>
				<section class="car_post_description column-7_12">

					<div class="main_info">
						<h2 class="car_title"><?php cars4rent_show_layout($post_data['post_title']); ?></h2>
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

						if (!empty($post_meta['car_option_list'])) {
							?>
							<div class="car_option_list">
								<h5 class="car_option_list_title"><?php esc_attr_e('Included for Free:', 'cars4rent'); ?></h5>
								<div class="car_option_list_text">
								<?php
									$list_options = explode(',', $post_meta['car_option_list']);
									foreach ($list_options as $list_option => $option) {
										echo '<span class="car_option_list_text_item icon-ok">' . trim($option) . '</span>';
									}
								?>
								</div>
							</div>
							<?php
						}
						if (!empty($post_meta['car_brief_info'])) {
						?>
						<div class="cars_brief_info">
							<div class="cars_brief_info_text"><?php echo cars4rent_do_shortcode(trim(($post_meta['car_brief_info']))); ?></div>
						</div>
						<?php
						}
						?>
				</section>
			</div>

			<?php
		}
		

		cars4rent_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="articleBody">');
		
		if ($show_title && $post_options['location'] != 'center' && cars4rent_get_custom_option('show_page_title')=='no') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="name" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php cars4rent_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}
			
		// Post content
		if ($post_data['post_protected']) {
            cars4rent_show_layout($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
            cars4rent_show_layout(cars4rent_gap_wrapper(cars4rent_reviews_wrapper($post_data['post_content'])));
			wp_link_pages( array( 
				'before' => '<nav class="pagination_single"><span class="pager_pages">' . esc_html__( 'Pages:', 'cars4rent' ) . '</span>', 
				'after' => '</nav>',
				'link_before' => '<span class="pager_numbers">',
				'link_after' => '</span>'
				)
			); 
			if ( cars4rent_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags"><?php esc_html_e('Tags:', 'cars4rent'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php 
			}
		} 

		// Prepare args for all rest template parts
		// This parts not pop args from storage!
		cars4rent_template_set_args('single-footer', array(
			'post_options' => $post_options,
			'post_data' => $post_data
		));
			
		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			get_template_part(cars4rent_get_file_slug('templates/_parts/editor-area.php'));
		}

		cars4rent_close_wrapper();
			
		if (!$post_data['post_protected']) {
			get_template_part(cars4rent_get_file_slug('templates/_parts/share.php'));
		}

		cars4rent_close_wrapper();

		if (!$post_data['post_protected']) {
			// Show replated posts
			get_template_part(cars4rent_get_file_slug('templates/_parts/related-posts.php'));
			// Show comments
			if ( comments_open() || get_comments_number() != 0 ) {
				comments_template();
			}
		}

		// Manually pop args from storage
		// after all single footer templates
		cars4rent_template_get_args('single-footer');
	}
}
?>