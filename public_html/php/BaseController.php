<?php
include "../scripts/simple_html_dom.php" ;
include "../php/DataBaseHandling.php";

//DUPLICATING HTML FILE ONTO OWN SERVER
function duplicateHtml($link){
	$htmlFileName = "index.html";
	$html=fopen($htmlFileName, "w+");
	fwrite($html , file_get_contents("$link"));
	fclose($html);
	return $htmlFileName;
}

//CREATING PATHS FOR OTHER FILES FOUND IN HTML
function downloadAndCreateImage($link){
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
}
// MAIN SCRIPT THAT RUNS FOR MOST ATT
function runScript($textToInsert){
	$conn = openConnection();
	//SEARCHING DATABASE WITH KEY TO GET LONGLINK
	$key = substr($_SERVER[REQUEST_URI],1,5);
	$link = selectDataFromDatabase($key, $conn);
	$htmlFileName = duplicateHtml($link);
	downloadAndCreateImage($link);
	$contents = file_get_contents("$htmlFileName");
	$newContent = preg_replace("</body>", $textToInsert."</body", $contents);
	file_put_contents($htmlFileName, $newContent);

	//REDIRECTING TO LONG LINK
	header("access-control-allow-origin: *");
	header("Location:".$htmlFileName, true, 303);
	die();
}

function runCryptographyScript(){
		$conn = openConnection();
		//SEARCHING DATABASE WITH KEY TO GET LONGLINK
		$key = substr($_SERVER[REQUEST_URI],1,5);
		$link = selectArrayFromDatabase($key, $conn);

		if($_GET["pass"] === "123456"){
				$link = (string)$link[0];
		}
		else{
			$link = (string)$link[1];
		}

		 $htmlFileName = duplicateHtml($link);
		 downloadAndCreateImage($link);
		 $contents = file_get_contents("$htmlFileName");
		$newContent = preg_replace("</body>", $textToInsert."</body", $contents);
		file_put_contents($htmlFileName, $newContent);
		//REDIRECTING TO LONG LINK
		header("access-control-allow-origin: *");
		header("Location:".$htmlFileName, true, 303);

		die();

}

?>
