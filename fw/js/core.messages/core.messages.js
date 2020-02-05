// Popup messages
//-----------------------------------------------------------------
jQuery(document).ready(function(){
	"use strict";

	CARS4RENT_STORAGE['message_callback'] = null;
	CARS4RENT_STORAGE['message_timeout'] = 5000;

	jQuery('body').on('click', '#cars4rent_modal_bg,.cars4rent_message .cars4rent_message_close', function (e) {
		"use strict";
		cars4rent_message_destroy();
		if (CARS4RENT_STORAGE['message_callback']) {
			CARS4RENT_STORAGE['message_callback'](0);
			CARS4RENT_STORAGE['message_callback'] = null;
		}
		e.preventDefault();
		return false;
	});
});


// Warning
function cars4rent_message_warning(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var icon = arguments[2] ? arguments[2] : 'cancel';
	var delay = arguments[3] ? arguments[3] : CARS4RENT_STORAGE['message_timeout'];
	return cars4rent_message({
		msg: msg,
		hdr: hdr,
		icon: icon,
		type: 'warning',
		delay: delay,
		buttons: [],
		callback: null
	});
}

// Success
function cars4rent_message_success(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var icon = arguments[2] ? arguments[2] : 'check';
	var delay = arguments[3] ? arguments[3] : CARS4RENT_STORAGE['message_timeout'];
	return cars4rent_message({
		msg: msg,
		hdr: hdr,
		icon: icon,
		type: 'success',
		delay: delay,
		buttons: [],
		callback: null
	});
}

// Info
function cars4rent_message_info(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var icon = arguments[2] ? arguments[2] : 'info';
	var delay = arguments[3] ? arguments[3] : CARS4RENT_STORAGE['message_timeout'];
	return cars4rent_message({
		msg: msg,
		hdr: hdr,
		icon: icon,
		type: 'info',
		delay: delay,
		buttons: [],
		callback: null
	});
}

// Regular
function cars4rent_message_regular(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var icon = arguments[2] ? arguments[2] : 'quote';
	var delay = arguments[3] ? arguments[3] : CARS4RENT_STORAGE['message_timeout'];
	return cars4rent_message({
		msg: msg,
		hdr: hdr,
		icon: icon,
		type: 'regular',
		delay: delay,
		buttons: [],
		callback: null
	});
}

// Confirm dialog
function cars4rent_message_confirm(msg) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var callback = arguments[2] ? arguments[2] : null;
	return cars4rent_message({
		msg: msg,
		hdr: hdr,
		icon: 'help',
		type: 'regular',
		delay: 0,
		buttons: ['Yes', 'No'],
		callback: callback
	});
}

// Modal dialog
function cars4rent_message_dialog(content) {
	"use strict";
	var hdr  = arguments[1] ? arguments[1] : '';
	var init = arguments[2] ? arguments[2] : null;
	var callback = arguments[3] ? arguments[3] : null;
	return cars4rent_message({
		msg: content,
		hdr: hdr,
		icon: '',
		type: 'regular',
		delay: 0,
		buttons: ['Apply', 'Cancel'],
		init: init,
		callback: callback
	});
}

// General message window
function cars4rent_message(opt) {
	"use strict";
	var msg = opt.msg != undefined ? opt.msg : '';
	var hdr  = opt.hdr != undefined ? opt.hdr : '';
	var icon = opt.icon != undefined ? opt.icon : '';
	var type = opt.type != undefined ? opt.type : 'regular';
	var delay = opt.delay != undefined ? opt.delay : CARS4RENT_STORAGE['message_timeout'];
	var buttons = opt.buttons != undefined ? opt.buttons : [];
	var init = opt.init != undefined ? opt.init : null;
	var callback = opt.callback != undefined ? opt.callback : null;
	// Modal bg
	jQuery('#cars4rent_modal_bg').remove();
	jQuery('body').append('<div id="cars4rent_modal_bg"></div>');
	jQuery('#cars4rent_modal_bg').fadeIn();
	// Popup window
	jQuery('.cars4rent_message').remove();
	var html = '<div class="cars4rent_message cars4rent_message_' + type + (buttons.length > 0 ? ' cars4rent_message_dialog' : '') + '">'
		+ '<span class="cars4rent_message_close iconadmin-cancel icon-cancel"></span>'
		+ (icon ? '<span class="cars4rent_message_icon iconadmin-'+icon+' icon-'+icon+'"></span>' : '')
		+ (hdr ? '<h2 class="cars4rent_message_header">'+hdr+'</h2>' : '');
	html += '<div class="cars4rent_message_body">' + msg + '</div>';
	if (buttons.length > 0) {
		html += '<div class="cars4rent_message_buttons">';
		for (var i=0; i<buttons.length; i++) {
			html += '<span class="cars4rent_message_button">'+buttons[i]+'</span>';
		}
		html += '</div>';
	}
	html += '</div>';
	// Add popup to body
	jQuery('body').append(html);
	var popup = jQuery('body .cars4rent_message').eq(0);
	// Prepare callback on buttons click
	if (callback != null) {
		CARS4RENT_STORAGE['message_callback'] = callback;
		jQuery('.cars4rent_message_button').on('click', function(e) {
			"use strict";
			var btn = jQuery(this).index();
			callback(btn+1, popup);
			CARS4RENT_STORAGE['message_callback'] = null;
			cars4rent_message_destroy();
		});
	}
	// Call init function
	if (init != null) init(popup);
	// Show (animate) popup
	var top = jQuery(window).scrollTop();
	jQuery('body .cars4rent_message').animate({top: top+Math.round((jQuery(window).height()-jQuery('.cars4rent_message').height())/2), opacity: 1}, {complete: function () {
	}});
	// Delayed destroy (if need)
	if (delay > 0) {
		setTimeout(function() { cars4rent_message_destroy(); }, delay);
	}
	return popup;
}

// Destroy message window
function cars4rent_message_destroy() {
	"use strict";
	var top = jQuery(window).scrollTop();
	jQuery('#cars4rent_modal_bg').fadeOut();
	jQuery('.cars4rent_message').animate({top: top-jQuery('.cars4rent_message').height(), opacity: 0});
	setTimeout(function() { jQuery('#cars4rent_modal_bg').remove(); jQuery('.cars4rent_message').remove(); }, 500);
}
