<?php
/**
 * Cars4Rent Framework: messages subsystem
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('cars4rent_messages_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_messages_theme_setup' );
	function cars4rent_messages_theme_setup() {
		// Core messages strings
		add_filter('cars4rent_filter_localize_script', 'cars4rent_messages_localize_script');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('cars4rent_get_error_msg')) {
	function cars4rent_get_error_msg() {
		return cars4rent_storage_get('error_msg');
	}
}

if (!function_exists('cars4rent_set_error_msg')) {
	function cars4rent_set_error_msg($msg) {
		$msg2 = cars4rent_get_error_msg();
		cars4rent_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('cars4rent_get_success_msg')) {
	function cars4rent_get_success_msg() {
		return cars4rent_storage_get('success_msg');
	}
}

if (!function_exists('cars4rent_set_success_msg')) {
	function cars4rent_set_success_msg($msg) {
		$msg2 = cars4rent_get_success_msg();
		cars4rent_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('cars4rent_get_notice_msg')) {
	function cars4rent_get_notice_msg() {
		return cars4rent_storage_get('notice_msg');
	}
}

if (!function_exists('cars4rent_set_notice_msg')) {
	function cars4rent_set_notice_msg($msg) {
		$msg2 = cars4rent_get_notice_msg();
		cars4rent_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('cars4rent_set_system_message')) {
	function cars4rent_set_system_message($msg, $status='info', $hdr='') {
		update_option(cars4rent_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('cars4rent_get_system_message')) {
	function cars4rent_get_system_message($del=false) {
		$msg = get_option(cars4rent_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			cars4rent_del_system_message();
		return $msg;
	}
}

if (!function_exists('cars4rent_del_system_message')) {
	function cars4rent_del_system_message() {
		delete_option(cars4rent_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('cars4rent_messages_localize_script')) {
	function cars4rent_messages_localize_script($vars) {
		$vars['strings'] = array(
			'ajax_error'		=> esc_html__('Invalid server answer', 'cars4rent'),
			'bookmark_add'		=> esc_html__('Add the bookmark', 'cars4rent'),
            'bookmark_added'	=> esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'cars4rent'),
            'bookmark_del'		=> esc_html__('Delete this bookmark', 'cars4rent'),
            'bookmark_title'	=> esc_html__('Enter bookmark title', 'cars4rent'),
            'bookmark_exists'	=> esc_html__('Current page already exists in the bookmarks list', 'cars4rent'),
			'search_error'		=> esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'cars4rent'),
			'email_confirm'		=> esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'cars4rent'),
			'reviews_vote'		=> esc_html__('Thanks for your vote! New average rating is:', 'cars4rent'),
			'reviews_error'		=> esc_html__('Error saving your vote! Please, try again later.', 'cars4rent'),
			'error_like'		=> esc_html__('Error saving your like! Please, try again later.', 'cars4rent'),
			'error_global'		=> esc_html__('Global error text', 'cars4rent'),
			'name_empty'		=> esc_html__('The name can\'t be empty', 'cars4rent'),
			'name_long'			=> esc_html__('Too long name', 'cars4rent'),
			'email_empty'		=> esc_html__('Too short (or empty) email address', 'cars4rent'),
			'email_long'		=> esc_html__('Too long email address', 'cars4rent'),
			'email_not_valid'	=> esc_html__('Invalid email address', 'cars4rent'),
			'subject_empty'		=> esc_html__('The subject can\'t be empty', 'cars4rent'),
			'subject_long'		=> esc_html__('Too long subject', 'cars4rent'),
			'text_empty'		=> esc_html__('The message text can\'t be empty', 'cars4rent'),
			'text_long'			=> esc_html__('Too long message text', 'cars4rent'),
			'send_complete'		=> esc_html__("Send message complete!", 'cars4rent'),
			'send_error'		=> esc_html__('Transmit failed!', 'cars4rent'),
			'geocode_error'			=> esc_html__('Geocode was not successful for the following reason:', 'cars4rent'),
			'googlemap_not_avail'	=> esc_html__('Google map API not available!', 'cars4rent'),
			'editor_save_success'	=> esc_html__("Post content saved!", 'cars4rent'),
			'editor_save_error'		=> esc_html__("Error saving post data!", 'cars4rent'),
			'editor_delete_post'	=> esc_html__("You really want to delete the current post?", 'cars4rent'),
			'editor_delete_post_header'	=> esc_html__("Delete post", 'cars4rent'),
			'editor_delete_success'	=> esc_html__("Post deleted!", 'cars4rent'),
			'editor_delete_error'	=> esc_html__("Error deleting post!", 'cars4rent'),
			'editor_caption_cancel'	=> esc_html__('Cancel', 'cars4rent'),
			'editor_caption_close'	=> esc_html__('Close', 'cars4rent')
			);
		return $vars;
	}
}
?>