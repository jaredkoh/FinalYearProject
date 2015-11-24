
  var map= google.maps.Map($("#googleMap"));
  alert('yoyoyoy');
  while(getUrlVars()["lat"] !== null && getUrlVars()["long"] !== null){
    pan(lat, long , map);
  }



function pan(lat , long , map) {
  var panPoint = new google.maps.LatLng(lat , long);
  map.panTo(panPoint);
}
function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
    vars[key] = value;
  });
  return vars;
}
