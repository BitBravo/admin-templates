function initialize() {
    var e = {
            center: myCenter,
            scrollwheel: !1,
            zoom: 10,
            zoomControl: !1,
            mapTypeControl: !1,
            streetViewControl: !1,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{
                featureType: "landscape",
                stylers: [{
                    saturation: -100
                }, {
                    lightness: 65
                }, {
                    visibility: "on"
                }]
            }, {
                featureType: "poi",
                stylers: [{
                    saturation: -100
                }, {
                    lightness: 51
                }, {
                    visibility: "simplified"
                }]
            }, {
                featureType: "road.highway",
                stylers: [{
                    saturation: -100
                }, {
                    visibility: "simplified"
                }]
            }, {
                featureType: "road.arterial",
                stylers: [{
                    saturation: -100
                }, {
                    lightness: 30
                }, {
                    visibility: "on"
                }]
            }, {
                featureType: "road.local",
                stylers: [{
                    color: "#F2F2F2"
                }, {
                    visibility: "off"
                }]
            }, {
                featureType: "transit",
                stylers: [{
                    saturation: -100
                }, {
                    visibility: "simplified"
                }]
            }, {
                featureType: "administrative.province",
                stylers: [{
                    visibility: "off"
                }]
            }, {
                featureType: "water",
                elementType: "labels",
                stylers: [{
                    visibility: "on"
                }, {
                    color: "#f6f6f6"
                }]
            }, {
                featureType: "water",
                elementType: "geometry",
                stylers: [{
                    color: "#E7E9EA"
                }, {
                    visibility: "on"
                }]
            }]
        },
        i = new google.maps.Map(document.getElementById("googleMap"), e),
        t = new google.maps.Marker({
            position: myCenter,
            icon: "img/map_icon.png",
//            animation: google.maps.Animation.BOUNCE,
        });
    t.setMap(i)
}
var myCenter = new google.maps.LatLng(40.6151988, -74.3447048);
google.maps.event.addDomListener(window, "load", initialize);