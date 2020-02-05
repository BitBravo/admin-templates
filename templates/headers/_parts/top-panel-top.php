<?php 
// Get template args
extract(cars4rent_template_get_args('top-panel-top'));


?>

<?php
if (in_array('open_hours', $top_panel_top_components) && ($open_hours=trim(cars4rent_get_custom_option('contact_open_hours')))!='') {
	?>
	<div class="top_panel_top_open_hours icon-clock"><?php cars4rent_show_layout(($open_hours)); ?></div>
	<?php
}
?>

<div class="top_panel_top_user_area">
	<?php
	if (in_array('socials', $top_panel_top_components) && cars4rent_get_custom_option('show_socials')=='yes' && function_exists('cars4rent_sc_socials')) {
		?>
		<div class="top_panel_top_socials">
			<?php cars4rent_show_layout(cars4rent_sc_socials(array('size'=>'tiny'))); ?>
		</div>
		<?php
	}

	if (in_array('search', $top_panel_top_components) && cars4rent_get_custom_option('show_search')=='yes' && function_exists('cars4rent_sc_socials')) {
		?>
		<div class="top_panel_top_search"><?php cars4rent_show_layout(cars4rent_sc_search(array(
			"style" => cars4rent_get_theme_option('search_style'),
			'state' => cars4rent_get_theme_option('search_style')=='default' ? 'fixed' : 'closed'))); ?></div>
		<?php
	}

	$menu_user = cars4rent_get_nav_menu('menu_user');
	if (empty($menu_user)) {
		?>
		<ul id="<?php echo (!empty($menu_user_id) ? esc_attr($menu_user_id) : 'menu_user'); ?>" class="menu_user_nav">
		<?php
	} else {
		$menu = cars4rent_substr($menu_user, 0, cars4rent_strlen($menu_user)-5);
		$pos = cars4rent_strpos($menu, '<ul');
		if ($pos!==false && cars4rent_strpos($menu, 'menu_user_nav')===false)
			$menu = cars4rent_substr($menu, 0, $pos+3) . ' class="menu_user_nav"' . cars4rent_substr($menu, $pos+3);
		if (!empty($menu_user_id))
			$menu = cars4rent_set_tag_attrib($menu, '<ul>', 'id', $menu_user_id);
		echo str_replace('class=""', '', $menu);
	}
	

	if (in_array('currency', $top_panel_top_components) && function_exists('cars4rent_is_woocommerce_page') && cars4rent_is_woocommerce_page() && cars4rent_get_custom_option('show_currency')=='yes') {
		?>
		<li class="menu_user_currency">
			<a href="#">$</a>
			<ul>
				<li><a href="#"><b>&#36;</b> <?php esc_html_e('Dollar', 'cars4rent'); ?></a></li>
				<li><a href="#"><b>&euro;</b> <?php esc_html_e('Euro', 'cars4rent'); ?></a></li>
				<li><a href="#"><b>&pound;</b> <?php esc_html_e('Pounds', 'cars4rent'); ?></a></li>
			</ul>
		</li>
		<?php
	}

	if (in_array('language', $top_panel_top_components) && cars4rent_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=1');
		if (!empty($languages) && is_array($languages)) {
			$lang_list = '';
			$lang_active = '';
			foreach ($languages as $lang) {
				$lang_title = esc_attr($lang['translated_name']);
				if ($lang['active']) {
					$lang_active = $lang_title;
				}
				$lang_list .= "\n"
					.'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
						.'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
						. ($lang_title)
					.'</a></li>';
			}
			?>
			<li class="menu_user_language">
				<a href="#"><span><?php cars4rent_show_layout($lang_active); ?></span></a>
				<ul><?php cars4rent_show_layout($lang_list); ?></ul>
			</li>
			<?php
		}
	}

	if (in_array('bookmarks', $top_panel_top_components) && cars4rent_get_custom_option('show_bookmarks')=='yes') {
		// Load core messages
		cars4rent_enqueue_messages();
		?>
		<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php esc_attr_e('Show bookmarks', 'cars4rent'); ?>"><?php esc_html_e('Bookmarks', 'cars4rent'); ?></a>
		<?php 
			$list = cars4rent_get_value_gpc('cars4rent_bookmarks', '');
			if (!empty($list)) $list = json_decode($list, true);
			?>
			<ul class="bookmarks_list">
				<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php esc_attr_e('Add the current page into bookmarks', 'cars4rent'); ?>"><?php esc_html_e('Add bookmark', 'cars4rent'); ?></a></li>
				<?php 
				if (!empty($list) && is_array($list)) {
					foreach ($list as $bm) {
						echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.esc_attr__('Delete this bookmark', 'cars4rent').'"></span></a></li>';
					}
				}
				?>
			</ul>
		</li>
		<?php 
	}

	if (in_array('login', $top_panel_top_components) && cars4rent_get_custom_option('show_login')=='yes') {
		if ( !is_user_logged_in() ) {
			// Load core messages
			cars4rent_enqueue_messages();
			// Load Popup engine
			cars4rent_enqueue_popup();
			// Anyone can register ?
			if ( (int) get_option('users_can_register') > 0) {
				?><li class="menu_user_register"><?php do_action('trx_utils_action_register'); ?></li><?php
			}
			?><li class="menu_user_login"><?php do_action('trx_utils_action_login'); ?></li><?php
		} else {
			$current_user = wp_get_current_user();
			?>
			<li class="menu_user_controls">
				<a href="#"><span class="user_name"><?php cars4rent_show_layout($current_user->display_name); ?></span></a>
			</li>
			<li class="menu_user_logout"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class=""><?php esc_html_e('Logout', 'cars4rent'); ?></a></li>
			<?php 
		}
	}

	if (in_array('cart', $top_panel_top_components) && function_exists('cars4rent_exists_woocommerce') && cars4rent_exists_woocommerce() && (cars4rent_is_woocommerce_page() && cars4rent_get_custom_option('show_cart')=='shop' || cars4rent_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
		?>
		<li class="menu_user_cart">
			<?php get_template_part(cars4rent_get_file_slug('templates/headers/_parts/contact-info-cart.php')); ?>
		</li>
		<?php
	}
	?>

	</ul>

</div>
<?php
if (in_array('contact_info', $top_panel_top_components)) {
	$contact_info=trim(cars4rent_get_custom_option('contact_info'));
	if ($contact_info!=''){
		?>
		<div class="top_panel_top_contact_area">
			<?php cars4rent_show_layout(($contact_info)); ?>
		</div>
		<?php
	}
	$contact_phone=trim(cars4rent_get_custom_option('contact_phone'));
	$contact_phone_label=trim(cars4rent_get_custom_option('contact_phone_label'));
	if ($contact_phone!=''){
		?>
		<div class="top_panel_top_contact_area">
			<span class="contact_phone_label">
				<?php cars4rent_show_layout(($contact_phone_label)); ?>
			</span>
			<span class="contact_phone icon-phone-1">
				<a href="tel:<?php cars4rent_show_layout(($contact_phone)); ?>"><?php cars4rent_show_layout(($contact_phone)); ?></a>
			</span>
		</div>
		<?php
	}

}
?>