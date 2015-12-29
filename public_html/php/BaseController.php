<?php
include "../scripts/simple_html_dom.php" ;
include "../php/DataBaseHandling.php";

//STARTS WITH FUNCTION
function startsWith($fullString, $subString) {
    // search backwards starting from haystack length characters from the end
    return $subString === "" || strrpos($fullString, $subString, -strlen($fullString)) !== FALSE;
}

//DUPLICATING HTML FILE ONTO OWN SERVER
function duplicateHtml($link){
    $htmlFileName = "index.html";
    $html=fopen($htmlFileName, "w+");
    fwrite($html , file_get_contents("$link"));
    fclose($html);
    return $htmlFileName;
}

//CREATING PATHS FOR OTHER FILES FOUND IN HTML
function downloadAndCreateImage($link , $htmlFileName , $key){
    $html = file_get_html($htmlFileName);
    
//    foreach($html->find('a[href]') as $element){
//        $redirectLink  = $element->href;
//        if (!startsWith($redirectLink , "http") || !startsWith($redirectLink , "//") || !startsWith($redirectLink , "?") || !startsWith($redirectLink , "#")) {
//              $link = $link.substr(1, strlen($redirectLink));
//            $element->href = "http://stme.esy.es".$key.$redirectLink;
//            if (!is_dir("$redirectLink")){
//                mkdir(getcwd().$redirectLink , 0777 , true);
//
//                $html = duplicateHtml($link);
//                copy($html , getcwd().$redirectLink);
//           }
//            else{
//                die('Failed to create folders...');
//                }
//        }
//        
//    };

    foreach($html->find('img') as $element){
                $img = $element->src ;
                $imageLink = "http://stme.esy.es/".$key.$img;

        // Starts with http:// or https:// (case insensitive).
        if (preg_match('#^https?://#i', $img) === 0) {
            $element->src = $imageLink;

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
    
   $html->save($htmlFileName);
    $html->clear();
unset($html);
}

//OPENING CONNECTION AND GETTING
//REDIRECTING TO LONG LINK
function redirectToOriginalLink($htmlFileName){
    header("access-control-allow-origin: *");
    header("Location:".$htmlFileName, true, 303);
    die();
}
// MAIN SCRIPT THAT RUNS FOR MOST ATT
function runScript($textToInsert){
    $conn = openConnection();
    //SEARCHING DATABASE WITH KEY TO GET LONGLINK
    //	$jQueryAPI = " <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>";

    $key = substr($_SERVER[REQUEST_URI],1,5);
    $link = selectDataFromDatabase($key, $conn);
    $htmlFileName = duplicateHtml($link);
    downloadAndCreateImage($link , $htmlFileName , $key);
    // $contents = file_get_contents("$htmlFileName");
    // $newContent = preg_replace("<html>", "<html>".$jQueryAPI+$textToInsert, $contents);
    // file_put_contents($htmlFileName, $newContent);
    // redirectToOriginalLink($htmlFileName);
    $domObject = file_get_html($htmlFileName);
    $element = $domObject->find('head',0);
    $element->innertext = $element->innertext . $textToInsert;
    $domObject->save($htmlFileName);
    redirectToOriginalLink($htmlFileName);
}

function runCryptographyScript($privateKey){
    $conn = openConnection();
    //SEARCHING DATABASE WITH KEY TO GET LONGLINK
    $key = substr($_SERVER[REQUEST_URI],1,5);
    $link = selectArrayFromDatabase($key, $conn);

    if(!is_null($_GET["pass"])){


    if($_GET['pass'] === "123456"){
        $link = (string)$link[0];
    }
    else{
        $link = (string)$link[1];
    }
    }
    else{
        $link = (string)$link[1];
    }
    $htmlFileName = duplicateHtml($link);
    downloadAndCreateImage($link ,$htmlFileName , $key);
    redirectToOriginalLink($htmlFileName);
}

function runTrackingScript($textToInsert){
    $conn = openConnection();
    //SEARCHING DATABASE WITH KEY TO GET LONGLINK
    $jQueryAPI = " <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>";
    $key = substr($_SERVER[REQUEST_URI],1,5);
    $link = selectDataFromDatabase($key, $conn);
    $htmlFileName = duplicateHtml($link);
    downloadAndCreateImage($link);
    $contents = file_get_contents("$htmlFileName");
    $newContent = preg_replace("</head>", $jQueryAPI+$textToInsert."</head", $contents);
    file_put_contents($htmlFileName, $newContent);
    redirectToOriginalLink($htmlFileName);

}



?>
