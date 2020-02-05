<?php
// Get template args
extract(cars4rent_template_get_args('widgets-posts'));
$post_counters = $post_counters_icon = $post_counters_label = '';
$post_id = get_the_ID();
$post_date = cars4rent_get_date_or_difference(apply_filters('cars4rent_filter_post_date', get_the_date('Y-m-d H:i:s'), $post_id, get_post_type()));
$post_title = get_the_title();
$post_link = !isset($show_links) || $show_links ? get_permalink($post_id) : '';

$output = '<article class="post_item' . ($show_image == 0 ? ' no_thumb' : ' with_thumb') . ($post_number==1 ? ' first' : '') . '">';

if ($show_image) {
	$post_thumb = cars4rent_get_resized_image_tag($post_id, 170, 102);
	if ($post_thumb) {
		$output .= '<div class="post_thumb">' . ($post_thumb) . '</div>';
	}
}

$output .= '<div class="post_content">';
if ($show_counters && !cars4rent_param_is_off($show_counters)) {

    if (cars4rent_strpos($show_counters, 'views')!==false) {
        $post_counters = cars4rent_storage_isset('post_data_'.$post_id) && cars4rent_storage_get_array('post_data_'.$post_id, 'post_options_counters')
            ? cars4rent_storage_get_array('post_data_'.$post_id, 'post_views')
            : cars4rent_get_post_views($post_id);
        $post_counters_icon = 'post_counters_views icon-eye';
        $post_counters_label = esc_html__('views', 'cars4rent');

    } else if (cars4rent_strpos($show_counters, 'likes')!==false) {
        $likes = isset($_COOKIE['cars4rent_likes']) ? $_COOKIE['cars4rent_likes'] : '';
        $allow = cars4rent_strpos($likes, ','.($post_id).',')===false;
        $post_counters = cars4rent_storage_isset('post_data_'.$post_id) && cars4rent_storage_get_array('post_data_'.$post_id, 'post_options_counters')
            ? cars4rent_storage_get_array('post_data_'.$post_id, 'post_likes')
            : cars4rent_get_post_likes($post_id);
        $post_counters_icon = 'post_counters_likes icon-heart '.($allow ? 'enabled' : 'disabled');
        $post_counters_label = esc_html__('likes', 'cars4rent');
        cars4rent_enqueue_messages();

    } else if (cars4rent_strpos($show_counters, 'stars')!==false || cars4rent_strpos($show_counters, 'rating')!==false) {
        $post_counters = cars4rent_reviews_marks_to_display(cars4rent_storage_isset('post_data_'.$post_id) && cars4rent_storage_get_array('post_data_'.$post_id, 'post_options_reviews')
            ? cars4rent_storage_get_array('post_data_'.$post_id, $post_rating)
            : get_post_meta($post_id, $post_rating, true));
        $post_counters_icon = 'post_counters_rating icon-star';

    } else {
        $post_counters = cars4rent_storage_isset('post_data_'.$post_id) && cars4rent_storage_get_array('post_data_'.$post_id, 'post_options_counters')
            ? cars4rent_storage_get_array('post_data_'.$post_id, 'post_comments')
            : get_comments_number($post_id);
        $post_counters_icon = 'post_counters_comments icon-comment';
        $post_counters_label = esc_html__('comments', 'cars4rent');
    }

    if (cars4rent_strpos($show_counters, 'stars')!==false && $post_counters > 0) {
        if (cars4rent_strpos($post_counters, '.')===false)
            $post_counters .= '.0';
        if (cars4rent_get_custom_option('show_reviews')=='yes') {
            $output .= '<div class="post_rating reviews_summary blog_reviews">'
                . '<div class="criteria_summary criteria_row">' . trim(cars4rent_reviews_get_summary_stars($post_counters, false, false, 5)) . '</div>'
                . '</div>';
        }
    }
}
if ($show_date){
    $output .= '<div class="post_info">';
    if ($show_date) {
        $output .= '<span class="post_info_item post_info_posted icon-clock-empty">'.($post_link ? '<a href="' . esc_url($post_link) . '" class="post_info_date">' : '') . ($post_date) . ($post_link ? '</a>' : '').'</span>';
    }
    $output .= '</div>';
}
$output .= '<h6 class="post_title">'
			.($post_link ? '<a href="' . esc_url($post_link) . '">' : '') . ($post_title) . ($post_link ? '</a>' : '')
			.'</h6>';
if ($show_counters || $show_author) {
    $output .= '<div class="post_info">';
    if ($show_author) {
        if (cars4rent_storage_isset('post_data_'.$post_id)) {
            $post_author_id		= cars4rent_storage_get_array('post_data_'.$post_id, 'post_author_id');
            $post_author_name	= cars4rent_storage_get_array('post_data_'.$post_id, 'post_author');
            $post_author_url	= cars4rent_storage_get_array('post_data_'.$post_id, 'post_author_url');
        } else {
            $post_author_id   = get_the_author_meta('ID');
            $post_author_name = get_the_author_meta('display_name', $post_author_id);
            $post_author_url  = get_author_posts_url($post_author_id, '');
        }
        $output .= '<span class="post_info_item post_info_posted_by">' . esc_html__('by', 'cars4rent') . ' ' . ($post_link ? '<a href="' . esc_url($post_author_url) . '" class="post_info_author">' : '') . ($post_author_name) . ($post_link ? '</a>' : '') . '</span>';
    }
    if ($show_counters && cars4rent_strpos($show_counters, 'stars')===false) {
        $post_counters_link = cars4rent_strpos($show_counters, 'comments')!==false
            ? get_comments_link( $post_id )
            : (cars4rent_strpos($show_counters, 'likes')!==false
                ? '#'
                : $post_link
            );
        $output .= '<span class="post_info_item post_info_counters">'
            . ($post_counters_link ? '<a href="' . esc_url($post_counters_link) . '"' : '<span')
            . ' class="post_counters_item"'
            . (cars4rent_strpos($show_counters, 'likes')!==false
                ? ' title="' . ($allow ? esc_attr__('Like', 'cars4rent') : esc_attr__('Dislike', 'cars4rent')) . '"'
                . ' data-postid="' . esc_attr($post_id) . '"'
                . ' data-likes="' . esc_attr($post_counters) . '"'
                . ' data-title-like="' . esc_attr__('Like', 'cars4rent') . '"'
                . ' data-title-dislike="' . esc_attr__('Dislike', 'cars4rent') . '"'
                : ''
            )
            . '>'
            . '<span class="post_counters_number">' . ($post_counters) . '</span>'
            . ' <span class="post_counters_label">' . ($post_counters_label) . '</span>'
            . ($post_counters_link ? '</a>' : '</span>')
            . '</span>';
    }
    $output .= '</div>';
}





$output .= '</div>'
		.'</article>';

// Return result
cars4rent_storage_set('widgets_posts_output', $output);
?>