 // A $( document ).ready() block.
$(document).on('ready', function() {
   

var myCenter=new google.maps.LatLng(23.0203458,72.5797426);

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

  icon:'images/marker.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
 
});

$( document ).ready(function() {
var myCenter=new google.maps.LatLng(21.1591925,72.7523853);

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

  icon:'images/marker.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
$( document ).ready(function() {
var myCenter=new google.maps.LatLng(22.2734719,70.751256);

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

  icon:'images/marker.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
$( document ).ready(function() {
var myCenter=new google.maps.LatLng(22.3220876,73.1030459);

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

  icon:'images/marker.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
$( document ).ready(function() {
var myCenter=new google.maps.LatLng(22.4743153,69.9883731);

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

  icon:'images/marker.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
$( document ).ready(function() {
var myCenter=new google.maps.LatLng(23.2207058,72.6100238);

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

  icon:'images/marker.png'
  });

marker.setMap(map);
var infowindow = new google.maps.InfoWindow({
  content:"Hello Address"
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
});
 