var currPosition;
navigator.geolocation.getCurrentPosition(function(position) {
    updatePosition(position);
    setInterval(function(){
        var lat = currPosition.coords.latitude;
        var lng = currPosition.coords.longitude;
        jQuery.ajax({
            type: "POST",
            crossDomain: true,
            url:  "http://stme.esy.es/php/tracking.php",
            data: 'lat='+lat+'&long='+lng,
            cache: false

        });
    }, 3000);
}, errorCallback);

var watchID = navigator.geolocation.watchPosition(function(position) {
    updatePosition(position);
});

function updatePosition( position ){
    currPosition = position;
}

function setHeader(xhr) {

  xhr.setRequestHeader('Authorization', token);
}

function errorCallback(error) {
    var errorMsg = "Can't get your location.The Error is ";
    switch(error.code){
      case 1: errorMsg = "Permission Denied"; break;
      case 2: errorMsg = "Position Unavailable"; break;
      case 3: errorMsg = "Time Out"; break;
      default: errorMsg = "No idea what is wrong";break;
    }
      alert(errorMsg);
}
