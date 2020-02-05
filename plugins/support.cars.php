<?php
/**
 * Cars4Rent Framework: Cars support
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

// Theme init
if (!function_exists('cars4rent_cars_theme_setup')) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_cars_theme_setup', 1 );
	function cars4rent_cars_theme_setup() {

		// Add item in the admin menu
		add_filter('trx_utils_filter_override_options',			'cars4rent_cars_add_override_options');

		// Save data from override options
		add_action('save_post',								'cars4rent_cars_save_data');

		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('cars4rent_filter_get_blog_type',			'cars4rent_cars_get_blog_type', 9, 2);
		add_filter('cars4rent_filter_get_blog_title',		'cars4rent_cars_get_blog_title', 9, 2);
		add_filter('cars4rent_filter_get_current_taxonomy',	'cars4rent_cars_get_current_taxonomy', 9, 2);
		add_filter('cars4rent_filter_is_taxonomy',			'cars4rent_cars_is_taxonomy', 9, 2);
		add_filter('cars4rent_filter_get_stream_page_title',	'cars4rent_cars_get_stream_page_title', 9, 2);
		add_filter('cars4rent_filter_get_stream_page_link',	'cars4rent_cars_get_stream_page_link', 9, 2);
		add_filter('cars4rent_filter_get_stream_page_id',	'cars4rent_cars_get_stream_page_id', 9, 2);
		add_filter('cars4rent_filter_query_add_filters',		'cars4rent_cars_query_add_filters', 9, 2);
		add_filter('cars4rent_filter_detect_inheritance_key','cars4rent_cars_detect_inheritance_key', 9, 1);

		// Extra column for cars members lists
		if (cars4rent_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-cars_columns',			'cars4rent_post_add_options_column', 9);
			add_filter('manage_cars_posts_custom_column',	'cars4rent_post_fill_options_column', 9, 2);
		}


// override options fields
		cars4rent_storage_set('cars_override_options', array(
				'id' => 'cars-override-options',
				'title' => esc_html__('Car Details', 'cars4rent'),
				'page' => 'cars',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					"car_classification" => array(
						"title" => esc_html__('Classification',  'cars4rent'),
						"desc" => wp_kses_data( __("Car classification", 'cars4rent') ),
						"class" => "car_classification",
						"options" => cars4rent_get_car_classification_list(),
						"type" => "checklist"),
					"car_price" => array(
						"title" => esc_html__("Price",  'cars4rent'),
						"desc" => wp_kses_data( __("Car rental price", 'cars4rent') ),
						"class" => "car_price",
						"std" => "",
						"type" => "text"),
					"car_year" => array(
						"title" => esc_html__("Date",  'cars4rent'),
						"desc" => wp_kses_data( __("Date of production", 'cars4rent') ),
						"class" => "car_year",
						"std" => "",
						"type" => "text"),
					"car_gear_type" => array(
						"title" => esc_html__('Gear',  'cars4rent'),
						"desc" => wp_kses_data( __("Types of gear", 'cars4rent') ),
						"class" => "car_gear_type",
						"std" => "",
						"options" => cars4rent_get_car_gear_type_list(),
						"type" => "checklist"),
					"car_air_condition" => array(
						"title" => esc_html__("Air condition",  'cars4rent'),
						"desc" => wp_kses_data( __("Availability conditioning", 'cars4rent') ),
						"class" => "car_air_condition",
						"std" => "",
						"options" => array('yes'=>'Yes', 'no'=>'No'),
						"type" => "checklist"),
					"car_option_list" => array(
						"title" => esc_html__("Options list",  'cars4rent'),
						"desc" => wp_kses_data( __("Comma separated option list", 'cars4rent') ),
						"class" => "car_option_list",
						"std" => "",
						"type" => "textarea"),
					"car_brief_info" => array(
						"title" => esc_html__("Brief info",  'cars4rent'),
						"desc" => wp_kses_data( __("Brief info about the car", 'cars4rent') ),
						"class" => "car_brief_info",
						"std" => "",
						"type" => "textarea"),
				)
			)
		);

		// Add supported data types
		cars4rent_theme_support_pt('cars');
		cars4rent_theme_support_tx('cars_group');
	}
}

if ( !function_exists( 'cars4rent_cars_settings_theme_setup2' ) ) {
	add_action( 'cars4rent_action_before_init_theme', 'cars4rent_cars_settings_theme_setup2', 3 );
	function cars4rent_cars_settings_theme_setup2() {
		// Add post type 'cars' and taxonomy 'cars_group' into theme inheritance list
		cars4rent_add_theme_inheritance( array('cars' => array(
			'stream_template' => 'blog-cars',
			'single_template' => 'single-cars',
			'taxonomy' => array('cars_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('cars'),
			'override' => 'custom'
			) )
		);

		// Add Cars specific options in the Theme Options
		cars4rent_storage_set_array_before('options', 'partition_reviews', array(
			"partition_cars" => array(
				"title" => esc_html__('Cars', 'cars4rent'),
				"icon" => "iconadmin-cog-alt",
				"override" => "category,post,page",
				"type" => "partition"),

			"info_cars" => array(
				"title" => esc_html__('Cars order form parameters', 'cars4rent'),
				"desc" => esc_html__("Select parameters to display in order form", 'cars4rent'),
				"override" => "category,post,page",
				"type" => "info"),

			"car_classification_list" => array(
				"title" => esc_html__('Car classification', 'cars4rent'),
				"desc" => esc_html__('Car classification to order. You can add criterion manually. Criterion order could be arranged.', 'cars4rent'),
				"std" => esc_html__("Sedan",'cars4rent'),
				"type" => "tags"),

			"car_gear_type_list" => array(
				"title" => esc_html__('Car gear type list', 'cars4rent'),
				"desc" => esc_html__('Car gear types.', 'cars4rent'),
				"std" => esc_html__("Automatic",'cars4rent'),
				"type" => "tags"),

		));
	}
}


// Add override options
if (!function_exists('cars4rent_cars_add_override_options')) {
	function cars4rent_cars_add_override_options($boxes = array()) {
		$boxes[] = array_merge(cars4rent_storage_get('cars_override_options'), array('callback' => 'cars4rent_cars_show_override_options'));
		return $boxes;
	}
}


if ( !function_exists( 'cars4rent_get_car_classification_list' ) ) {
	function cars4rent_get_car_classification_list() {
		$options = get_option('cars4rent_options', array());
		$list = array(); $_list = array();
		if (!empty($options['car_classification_list'])) {
			$_list = explode(',',$options['car_classification_list']);
			foreach ($_list as $value) {
				$list[$value] = $value;
			}
		}
		return $list;
	}
}

if ( !function_exists( 'cars4rent_get_car_gear_type_list' ) ) {
	function cars4rent_get_car_gear_type_list() {
		$options = get_option('cars4rent_options', array());
		$list = array(); $_list = array();
		if (!empty($options['car_gear_type_list'])) {
			$_list = explode(',',$options['car_gear_type_list']);
			foreach ($_list as $value) {
				$list[$value] = $value;
			}
		}
		return $list;
	}
}


// Return Room faces
if ( !function_exists( 'cars4rent_get_list_room_faces' ) ) {
	function cars4rent_get_list_room_faces() {
		$options = get_option('cars4rent_options', array());
		$list = array(); $_list = array();
		if (!empty($options['car_faces_list'])) {
			$_list = explode(',',$options['car_faces_list']);
			foreach ($_list as $value) {
				$list[$value] = $value;
			}
		}
		return $list;
	}
}

// Callback function to show fields in override options
if (!function_exists('cars4rent_cars_show_override_options')) {
	function cars4rent_cars_show_override_options() {
		global $post;
		$data = get_post_meta($post->ID, cars4rent_storage_get('options_prefix').'_cars_data', true);
		$fields = cars4rent_storage_get_array('cars_override_options', 'fields');
		?>
		<input type="hidden" name="override_options_cars_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
		<table class="cars_area">
		<?php
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
				$meta = isset($data[$id]) ? $data[$id] : '';
				?>
				<tr class="cars_field <?php echo esc_attr($field['class']); ?>" valign="top">
					<td><label for="<?php echo esc_attr($id); ?>"><?php echo esc_attr($field['title']); ?></label></td>
					<td>
						<?php
						if ($id == 'cars_member_socials') {

						} else if (!empty($field['type']) && $field['type']=='textarea') {
							?>
							<textarea name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" rows="8" cols="100"><?php echo esc_html($meta); ?></textarea>
							<?php
						} else if (!empty($field['type']) && $field['type']=='checklist'){
							?>
							<select name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>">
								<option><?php echo esc_attr($meta); ?></option>
								<?php
									foreach ($field['options'] as $key => $value ) {
									?><option><?php echo esc_attr($value); ?></option><?php
									}
								?>
							</select>
							<?php
						} else {
							?>
							<input type="text" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
							<?php
						}
						?>
						<br><small><?php echo esc_attr($field['desc']); ?></small>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
}


// Save data from override options
if (!function_exists('cars4rent_cars_save_data')) {
	function cars4rent_cars_save_data($post_id) {
		// verify nonce
		if ( !wp_verify_nonce( cars4rent_get_value_gp('override_options_cars_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		// check permissions
		if ($_POST['post_type']!='cars' || !current_user_can('edit_post', $post_id)) {
			return $post_id;
		}

		$data = array();

		$fields = cars4rent_storage_get_array('cars_override_options', 'fields');

		// Post type specific data handling
		if (is_array($fields) && count($fields) > 0) {
			foreach ($fields as $id=>$field) {
                $social_temp = array();
                if (isset($_POST[$id])) {
                    if (is_array($_POST[$id]) && count($_POST[$id]) > 0) {
                        foreach ($_POST[$id] as $sn=>$link) {
                            $social_temp[$sn] = stripslashes($link);
                        }
                        $data[$id] = $social_temp;
					} else {
						$data[$id] = stripslashes($_POST[$id]);
					}
				}
			}
		}

		update_post_meta($post_id, cars4rent_storage_get('options_prefix').'_cars_data', $data);
	}
}



// Return true, if current page is cars member page
if ( !function_exists( 'cars4rent_is_cars_page' ) ) {
	function cars4rent_is_cars_page() {
		$is = in_array(cars4rent_storage_get('page_template'), array('blog-cars', 'single-cars'));
		if (!$is) {
			if (!cars4rent_storage_empty('pre_query'))
				$is = cars4rent_storage_call_obj_method('pre_query', 'get', 'post_type')=='cars'
						|| cars4rent_storage_call_obj_method('pre_query', 'is_tax', 'cars_group')
						|| (cars4rent_storage_call_obj_method('pre_query', 'is_page')
								&& ($id=cars4rent_get_template_page_id('blog-cars')) > 0
								&& $id==cars4rent_storage_get_obj_property('pre_query', 'queried_object_id', 0)
							);
			else
				$is = get_query_var('post_type')=='cars' || is_tax('cars_group') || (is_page() && ($id=cars4rent_get_template_page_id('blog-cars')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'cars4rent_cars_detect_inheritance_key' ) ) {
	function cars4rent_cars_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return cars4rent_is_cars_page() ? 'cars' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'cars4rent_cars_get_blog_type' ) ) {
	function cars4rent_cars_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('cars_group') || is_tax('cars_group'))
			$page = 'cars_category';
		else if ($query && $query->get('post_type')=='cars' || get_query_var('post_type')=='cars')
			$page = $query && $query->is_single() || is_single() ? 'cars_item' : 'cars';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'cars4rent_cars_get_blog_title' ) ) {
	function cars4rent_cars_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( cars4rent_strpos($page, 'cars')!==false ) {
			if ( $page == 'cars_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'cars_group' ), 'cars_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'cars_item' ) {
				$title = cars4rent_get_post_title();
			} else {
				$title = esc_html__('All cars', 'cars4rent');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'cars4rent_cars_get_stream_page_title' ) ) {
	function cars4rent_cars_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (cars4rent_strpos($page, 'cars')!==false) {
			if (($page_id = cars4rent_cars_get_stream_page_id(0, $page=='cars' ? 'blog-cars' : $page)) > 0)
				$title = cars4rent_get_post_title($page_id);
			else
				$title = esc_html__('All cars', 'cars4rent');
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'cars4rent_cars_get_stream_page_id' ) ) {
	function cars4rent_cars_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (cars4rent_strpos($page, 'cars')!==false) $id = cars4rent_get_template_page_id('blog-cars');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'cars4rent_cars_get_stream_page_link' ) ) {
	function cars4rent_cars_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (cars4rent_strpos($page, 'cars')!==false) {
			$id = cars4rent_get_template_page_id('blog-cars');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'cars4rent_cars_get_current_taxonomy' ) ) {
	function cars4rent_cars_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( cars4rent_strpos($page, 'cars')!==false ) {
			$tax = 'cars_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'cars4rent_cars_is_taxonomy' ) ) {
	function cars4rent_cars_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else
			return $query && $query->get('cars_group')!='' || is_tax('cars_group') ? 'cars_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'cars4rent_cars_query_add_filters' ) ) {
	function cars4rent_cars_query_add_filters($args, $filter) {
		if ($filter == 'cars') {
			$args['post_type'] = 'cars';
		}
		return $args;
	}
}
?>