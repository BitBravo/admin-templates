<?php
/**
 * The Sidebar containing the main widget areas.
 */

$sidebar_show   = cars4rent_get_custom_option('show_sidebar_main');
$sidebar_scheme = cars4rent_get_custom_option('sidebar_main_scheme');
$sidebar_name   = cars4rent_get_custom_option('sidebar_main');

if (!cars4rent_param_is_off($sidebar_show) && is_active_sidebar($sidebar_name)) {
	?>
	<div class="sidebar widget_area scheme_<?php echo esc_attr($sidebar_scheme); ?>" role="complementary">
		<div class="sidebar_inner widget_area_inner">
			<?php
			ob_start();
			do_action( 'before_sidebar' );
			if (($reviews_markup = cars4rent_storage_get('reviews_markup')) != '') {
				?><aside class="column-1_1 widget widget_reviews"><?php cars4rent_show_layout($reviews_markup); ?></aside><?php
			}
			cars4rent_storage_set('current_sidebar', 'main');
            if ( is_active_sidebar( $sidebar_name ) ) {
                dynamic_sidebar( $sidebar_name );
            }
			do_action( 'after_sidebar' );
			$out = ob_get_contents();
			ob_end_clean();
			cars4rent_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out));
			?>
		</div>
	</div> <!-- /.sidebar -->
	<?php
}
?>