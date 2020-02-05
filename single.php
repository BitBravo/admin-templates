<?php
/**
 * Single post
 */
get_header(); 

$single_style = cars4rent_storage_get('single_style');
if (empty($single_style)) $single_style = cars4rent_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	cars4rent_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !cars4rent_param_is_off(cars4rent_get_custom_option('show_sidebar_main')),
			'content' => cars4rent_get_template_property($single_style, 'need_content'),
			'terms_list' => cars4rent_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>