 // A $( document ).ready() block.
$(document).on('ready', function() {
   

var myCenter=new google.maps.LatLng(51.5285582,-0.2417006);

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
var myCenter=new google.maps.LatLng(52.1988241,0.0499476);

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
var myCenter=new google.maps.LatLng(51.4684681,-2.6607489);

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
var myCenter=new google.maps.LatLng(50.9167474,-1.4355123);

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
