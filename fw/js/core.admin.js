/**
 * Cars4Rent Framework: Admin scripts
 *
 * @package	cars4rent
 * @since	cars4rent 1.0
 */

jQuery(document).ready(function() {
	"use strict";
	var type_car = false;
	jQuery("body").on('change',function(){
		jQuery("div[data-vc-shortcode='trx_blogger']").each(function(){
			jQuery(this).find("div[data-vc-shortcode-param-name='style'] select[name='style']").on('change',function(){
				if(jQuery(this).hasClass("caristp_4") || jQuery(this).hasClass("caristp_3") || jQuery(this).hasClass("caristp_2"))
				{
					type_car = true;
				} else {
					type_car = false;
				}
			});
			
			if(type_car){
				jQuery(this).find("select[name='orderby'] option.author_rating").css('display', 'none');
				jQuery(this).find("select[name='orderby'] option.users_rating").css('display', 'none');
			} else {
				jQuery(this).find("select[name='orderby'] option.author_rating").css('display', 'block');
				jQuery(this).find("select[name='orderby'] option.users_rating").css('display', 'block');
			}
		});
	});
	// Refresh categories when post type is changed
	jQuery('.widgets_param_post_type_selector').on('change', function() {
		"use strict";
		var cat_fld = jQuery(this).parent().next().find('select');
		var cat_lbl = jQuery(this).parent().next().find('label');
		cars4rent_admin_fill_categories(this, cat_fld, cat_lbl);
		return false;
	});
	// Set parent course
	jQuery('.cars4rent_course_selector').on('click', function(e) {
		"use strict";
		var id = jQuery(this).data('parent_id');
		if (id > 0) {
			jQuery('select#parent_course').val(id).siblings('input[type="submit"]').trigger('click');
			e.preventDefault();
			return false;
		}
	});
});


// Fill categories in specified field
function cars4rent_admin_fill_categories(fld, cat_fld, cat_lbl) {
	"use strict";
	var cat_value = cars4rent_get_listbox_selected_value(cat_fld.get(0));
	cat_lbl.append('<span class="sc_refresh iconadmin-spin3 animate-spin"></span>');
	var pt = jQuery(fld).val();
	// Prepare data
	var data = {
		action: 'cars4rent_admin_change_post_type',
		nonce: CARS4RENT_STORAGE['ajax_nonce'],
		post_type: pt
	};
	jQuery.post(CARS4RENT_STORAGE['ajax_url'], data, function(response) {
		"use strict";
		var rez = {};
		try {
			rez = JSON.parse(response);
		} catch (e) {
			rez = { error: CARS4RENT_STORAGE['ajax_error'] };
			console.log(response);
		}
		if (rez.error === '') {
			var opt_list = '';
			for (var i in rez.data.ids) {
				opt_list += '<option class="'+rez.data.ids[i]+'" value="'+rez.data.ids[i]+'"'+(rez.data.ids[i]==cat_value ? ' selected="selected"' : '')+'>'+rez.data.titles[i]+'</option>';
			}
			cat_fld.html(opt_list);
			cat_lbl.find('span').remove();
		}
	});
	return false;
}
