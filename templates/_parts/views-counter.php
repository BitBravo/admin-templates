<?php 
if (is_singular()) {
	if (cars4rent_get_theme_option('use_ajax_views_counter')=='yes') {
		cars4rent_storage_set_array('js_vars', 'ajax_views_counter', array(
			'post_id' => get_the_ID(),
			'post_views' => cars4rent_get_post_views(get_the_ID())
		));
	} else
		cars4rent_set_post_views(get_the_ID());
}
?>