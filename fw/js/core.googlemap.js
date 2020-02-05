function cars4rent_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof CARS4RENT_STORAGE['googlemap_init_obj'] == 'undefined') cars4rent_googlemap_init_styles();
	CARS4RENT_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		CARS4RENT_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true,
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: CARS4RENT_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		cars4rent_googlemap_create(id);

	} catch (e) {
		
		dcl(CARS4RENT_STORAGE['strings']['googlemap_not_avail']);

	};
}

function cars4rent_googlemap_create(id) {
	"use strict";

	// Create map
	CARS4RENT_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(CARS4RENT_STORAGE['googlemap_init_obj'][id].dom, CARS4RENT_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in CARS4RENT_STORAGE['googlemap_init_obj'][id].markers)
		CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	cars4rent_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (CARS4RENT_STORAGE['googlemap_init_obj'][id].map)
			CARS4RENT_STORAGE['googlemap_init_obj'][id].map.setCenter(CARS4RENT_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function cars4rent_googlemap_add_markers(id) {
	"use strict";
	for (var i in CARS4RENT_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (CARS4RENT_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (CARS4RENT_STORAGE['googlemap_init_obj'].geocoder == '') CARS4RENT_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			CARS4RENT_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			CARS4RENT_STORAGE['googlemap_init_obj'].geocoder.geocode({address: CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = CARS4RENT_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					CARS4RENT_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						cars4rent_googlemap_add_markers(id); 
						}, 200);
				} else
					if (false) dcl(CARS4RENT_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: CARS4RENT_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].title;
			CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (CARS4RENT_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				CARS4RENT_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				CARS4RENT_STORAGE['googlemap_init_obj'][id].map.setCenter(CARS4RENT_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in CARS4RENT_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								CARS4RENT_STORAGE['googlemap_init_obj'][id].map,
								CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			CARS4RENT_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function cars4rent_googlemap_refresh() {
	"use strict";
	for (id in CARS4RENT_STORAGE['googlemap_init_obj']) {
		cars4rent_googlemap_create(id);
	}
}

function cars4rent_googlemap_init_styles() {
	"use strict";
	// Init Google map
	CARS4RENT_STORAGE['googlemap_init_obj'] = {};
	CARS4RENT_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.cars4rent_theme_googlemap_styles!==undefined)
		CARS4RENT_STORAGE['googlemap_styles'] = cars4rent_theme_googlemap_styles(CARS4RENT_STORAGE['googlemap_styles']);
}