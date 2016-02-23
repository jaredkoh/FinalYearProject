<?php
include "DatabaseHandling.php";

$key = "";
$char = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

$conn = openConnection();



$longlink = "$_POST[urllink]";
$key = checkForDuplicateLinks($longlink,$conn);
if($key === NULL){
  $key = generateKey(6);
  $finalKey = checkForDuplicateKeys($key , $conn);
  addDataToDatabase($key , $longlink ,$conn);
}

$myfile = fopen("$key", "w") or die("Unable to open file!");
$myfileToRead = fopen("check.php", "r") or die("Unable to open file!");
$txt = fread($myfileToRead,filesize("check.php"));
fclose($myfileToRead);

fwrite($myfile, $txt);
fclose($myfile);

$conn->close();

//header( 'Location: http://kclproject.esy.es/shorten/');?>
