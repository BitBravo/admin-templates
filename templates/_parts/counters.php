<?php
// Get template args
extract(cars4rent_template_get_args('counters'));

$show_all_counters = false;
$counters_tag = is_single() ? 'span' : 'a';

// Views
if ($show_all_counters || cars4rent_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php cars4rent_show_layout($counters_tag); ?> class="post_counters_item post_counters_views" title="<?php echo esc_attr( sprintf(__('View(s) - %s', 'cars4rent'), $post_data['post_views']) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php cars4rent_show_layout($post_data['post_views']); ?></span><?php echo ' '.esc_html__('View(s)', 'cars4rent'); ?></<?php cars4rent_show_layout($counters_tag); ?>>
	<?php
}

// Comments
if ($show_all_counters || cars4rent_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'cars4rent'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php cars4rent_show_layout($post_data['post_comments']); ?></span><?php echo ' '.esc_html__('Comment(s)', 'cars4rent'); ?></a>
	<?php 
}
 
// Rating
$rating = $post_data['post_reviews_'.(cars4rent_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || cars4rent_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php cars4rent_show_layout($counters_tag); ?> class="post_counters_item post_counters_rating" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'cars4rent'), $rating) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php cars4rent_show_layout($rating); ?></span></<?php cars4rent_show_layout($counters_tag); ?>>
	<?php
}

// Likes
if ($show_all_counters || cars4rent_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	cars4rent_enqueue_messages();
	$likes = isset($_COOKIE['cars4rent_likes']) ? $_COOKIE['cars4rent_likes'] : '';
	$allow = cars4rent_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php echo !empty($allow) ? 'enabled' : 'disabled'; ?>" title="<?php echo !empty($allow) ? esc_attr__('Like', 'cars4rent') : esc_attr__('Dislike', 'cars4rent'); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'cars4rent'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'cars4rent'); ?>"><span class="post_counters_number"><?php cars4rent_show_layout($post_data['post_likes']); ?></span><?php echo ' '.esc_html__('Likes', 'cars4rent'); ?></a>
	<?php
}

// Edit page link
if (cars4rent_strpos($post_options['counters'], 'edit')!==false) {
	edit_post_link( esc_html__( 'Edit', 'cars4rent' ), '<span class="post_edit edit-link">', '</span>' );
}

// Markup for search engines
if (is_single() && cars4rent_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(cars4rent_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(cars4rent_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>