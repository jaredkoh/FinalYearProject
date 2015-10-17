<?php
$servername = "mysql.hostinger.co.uk";
$username = "u464162183_user";
$password = "iLY6abhAkI";
$dbname = "u464162183_data";
$key = "";
$char = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//generate random key
//function generateKey($length_of_key){
for ($i = 0 ; $i < 5 ; $i ++){
  $temp = rand(0, 61);
  $key .= $char[$temp];
  }
//}
//checks for duplicate keys in the database
// function checkingDuplication($key){
// $check = "SELECT * FROM DATA WHERE shortlink='$key'";
// $conn = new mysqli($servername, $username, $password, $dbname);
// $result = $conn->query($check);
// if($result->numrows > 0){
//     $key="";
//     generateKey(5);
//     checkingDuplication($key);
//   }
//   else{
//     $key .=".php";
//   }
// }

//generateKey(5);
//checkingDuplication($key);

$key .= '.php';



$longlink = "$_POST[urllink]";

$sql = "INSERT INTO DATA (shortlink,longlink)
VALUES ( '$key', '$longlink')";

if ($conn->query($sql) === TRUE) {
  //echo 'UPDATE ALL GOOD. Your new url is http://individualproject.esy.es/php/' .$key ;
  $userlink = "http://individualproject.esy.es/shorten/".$key;

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$myfile = fopen("$key", "w") or die("Unable to open file!");
$myfileToRead = fopen("../php/check.php", "r") or die("Unable to open file!");
$txt = fread($myfileToRead,filesize("../php/check.php"));
fclose($myfileToRead);

fwrite($myfile, $txt);
fclose($myfile);

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
    <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
    <script src ="../js/mainscript" type="text/javascript"></script>
    <script src = "../js/ui.js" type="text/javascript"></script>

  </head>
  <body>
      <div class = "urlform" >
            <h1> Hello There! </h1>
              <button type="button" class="btn btn-success" style="float:right" id="copy">Copy</button>
              <div style="overflow: hidden; padding-right: .5em;">
                <input type="url" class="form-control" name="urllink" id="form" value=<?php echo $userlink ?>/>
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
  </body>
</html>
