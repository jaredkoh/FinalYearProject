<?php
$servername = "mysql.hostinger.co.uk";
$username = "u697075009_user";
$password = "yTanyR";
$dbname = "u697075009_data";
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
  echo 'UPDATE ALL GOOD. Your new url is http://finalyearproject.pe.hu/' .$key ;

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$myfile = fopen("$key", "w") or die("Unable to open file!");
$myfileToRead = fopen("check.php", "r") or die("Unable to open file!");
$txt = fread($myfileToRead,filesize("check.php"));
fclose($myfileToRead);

fwrite($myfile, $txt);
fclose($myfile);

$conn->close();
?>