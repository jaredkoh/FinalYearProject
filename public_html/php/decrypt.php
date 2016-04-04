<?php
$data = $_POST['data'];
$key = $_POST['key'];
openssl_decrypt($data,$decrypted,$key); 
?>
    
<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FinalYearProject</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/main.css" rel="stylesheet">
  <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB10_ZYaNx56uCzjseUi8OmeaQj1GH7q4c"></script>
  <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
  <script src ="../js/mainscript" type="text/javascript"></script>
  <script src = "../js/ui.js" type="text/javascript"></script>

</head>
<body>
  <div class = "urlform" >
    <h1> Thanks For Using :) </h1>
    <button type="button" class="btn btn-success" style="float:right" id="go">Go</button>
    <div style="overflow: hidden; padding-right: .5em;">
      <input type="url" class="form-control" name="urllink" id="form" value="$decrypted"/>
    </div>​

  </div>
  <div class ="videobackground">
    <video autoplay loop width="100%" class="bgvid">
      <source src="../Productive-Morning/MP4/Productive-Morning.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.
      <source src="../Productive-Morning/WEBM/Productive-Morning.webm" type="video/webm" />Your browser does not support the video tag. I suggest you upgrade your browser.
    </video>
    <div class="poster hidden">
      <img src="../Productive-Morning/snapshots/Productive-Morning.jpg" alt=""> />
    </div>
  </div>

</body>
</html>
