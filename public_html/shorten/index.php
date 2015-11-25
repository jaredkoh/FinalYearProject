<?php
session_start();
include "../php/DataBaseHandling.php";
include "../scripts/simple_html_dom.php" ;


$key = "";
$conn = openConnection();

$longlink = $_POST['urllink'];
$typeOfAttack = $_POST['Type'];



$_SESSION['urllink']=$longlink;
$_SESSION['Type']=$typeOfAttack;

function createFolderAndFile($key){
  if (!is_dir($key)) {
    $full = getcwd();
    $path = substr($full , 0 , strlen($full) - strlen(strrchr($full , "/")));
    mkdir($path."/"."$key" , 0777 , true);
    $myfile = fopen("../$key/get.php", "w") or die("Unable to open file!");
    $myfileToRead = fopen("../php/SelectedController.php", "r") or die("Unable to open file!");
    $txt = fread($myfileToRead,filesize("../php/SelectedController.php"));
    fclose($myfileToRead);

    fwrite($myfile, $txt);
    fclose($myfile);
  }
}

if($typeOfAttack === 'Cryptography'){
  $illlonglink = $_POST['illurlink'];
  $_SESSION['illlonglink'] = $illlonglink;
  $key = generateKey(6,$key);
  $key = checkForDuplicateKeys($key , $conn);
  addDataToDatabase($key , $longlink , $conn);
  addDataToDatabase($key , $illlonglink , $conn);
  // $ssl = generateCrypotgraphyKeys(1024);
  // openssl_pkey_export($ssl,$privateKey);
  // $pubKey = openssl_pkey_get_details($ssl);
  // $pubKey = $pubKey["key"];
  // echo $pubKey;
  // $_SESSION['privateKey'] = $privateKey;
  // $publicKeyTextFile = fopen('publickey.txt' , 'w');
  // fwrite($publicKeyTextFile , "$pubKey");
  // fclose($publicKeyTextFile);

  $userlink = "http://individualproject.esy.es/".$key."/get.php";
  createFolderAndFile($key);
}
else if($typeOfAttack ==="Tracking"){
    $map =    "<div id='map' class='form-control' style='height:500px'></div>";

    $html = file_get_html("http://individualproject.esy.es/shorten/");
    $element = $html->find('div[class=urlform]',0);

    $element->innertext =  $element->innertext.$map;

    $html->save("http://individualproject.esy.es/shorten/");

}
else{
  $key = checkForDuplicateLinks($longlink,$conn);
  if(is_null($key)===TRUE){
    $key = generateKey(6,$key);
    $key = checkForDuplicateKeys($key , $conn);
    addDataToDatabase($key , $longlink , $conn);
    $userlink = "http://individualproject.esy.es/".$key."/get.php";
    createFolderAndFile($key);
  }
  else{
    $userlink = "http://individualproject.esy.es/".$key."/get.php";
  }
}

$conn->close(); ?>


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
      <input type="url" class="form-control" name="urllink" id="form" value=<?php echo $userlink ?>
    </div>​

  </div>
  <div class ="videobackground">
    <video autoplay loop width="100%" class="bgvid">
      <source src="../videos/Hello-World.mp4" type="video/mp4" />Your browser does not support the video tag. I suggest you upgrade your browser.
      <source src="../videos/Hello-World.webm" type="video/webm" />Your browser does not support the video tag. I suggest you upgrade your browser.
    </video>
    <div class="poster hidden">
      <img src="../videos/Hello-World.jpg" alt="">
    </div>
  </div>

  </script>
</body>
</html>
