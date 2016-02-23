$(document).ready(function(){

  google.maps.event.addDomListener(window, 'load', initialize);
  var map=new google.maps.Map($("#googleMap"),mapProp);


}

function initialize() {
  var mapProp = {
    center:new google.maps.LatLng(51.508742,-0.120850),
    zoom:17,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };

}
