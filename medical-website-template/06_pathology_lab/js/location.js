 // A $( document ).ready() block.
$(document).on('ready', function() {
   

var myCenter=new google.maps.LatLng(34.0201812,-118.6919204);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:9,
  scrollwheel: false,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,

  icon:'images/map-pin.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
 
});

$( document ).ready(function() {
var myCenter=new google.maps.LatLng(41.8333925,-88.0123392);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:9,
  scrollwheel: false,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("googleMap2"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,

  icon:'images/map-pin.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
$( document ).ready(function() {
var myCenter=new google.maps.LatLng(40.705311,-74.2581879);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:9,
  scrollwheel: false,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("googleMap3"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,

  icon:'images/map-pin.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});

$( document ).ready(function() {
var myCenter=new google.maps.LatLng(37.7576793,-122.50764);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:9,
  scrollwheel: false,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("googleMap4"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,

  icon:'images/map-pin.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
$( document ).ready(function() {
var myCenter=new google.maps.LatLng(39.9828671,-83.1309131);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:9,
  scrollwheel: false,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("googleMap5"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,

  icon:'images/map-pin.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
$( document ).ready(function() {
var myCenter=new google.maps.LatLng(39.9828671,-83.1309131);

function initialize()
{
var mapProp = {
  center:myCenter,
  zoom:9,
  scrollwheel: false,
  mapTypeId:google.maps.MapTypeId.ROADMAP
  };

var map=new google.maps.Map(document.getElementById("googleMap6"),mapProp);

var marker=new google.maps.Marker({
  position:myCenter,

  icon:'images/map-pin.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
