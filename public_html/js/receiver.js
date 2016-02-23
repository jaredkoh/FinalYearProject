$(document).ready(function(){

  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -34.397, lng: 150.644},
    zoom: 6
  });
});


function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(51.508742,-0.120850),
    zoom:17,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  return mapProp;
}
