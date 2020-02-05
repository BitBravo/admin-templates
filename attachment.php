<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move cars4rent_set_post_views to the javascript - counter will work under cache system
	if (cars4rent_get_custom_option('use_ajax_views_counter')=='no') {
		cars4rent_set_post_views(get_the_ID());
	}

	cars4rent_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !cars4rent_param_is_off(cars4rent_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>