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
	$key = substr($_SERVER[REQUEST_URI],1,5);
	$link = selectDataFromDatabase($key, $conn);
	$htmlFileName = duplicateHtml($link);
	downloadAndCreateImage($link);
	$contents = file_get_contents("$htmlFileName");
	$newContent = preg_replace("</body>", $textToInsert."</body", $contents);
	file_put_contents($htmlFileName, $newContent);
	redirectToOriginalLink($htmlFileName);

}

function runCryptographyScript($privateKey){
		$conn = openConnection();
		//SEARCHING DATABASE WITH KEY TO GET LONGLINK
		$key = substr($_SERVER[REQUEST_URI],1,5);
		$link = selectArrayFromDatabase($key, $conn);

		if(!is_null($_GET["pass"])){
// $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
// $ciphertext_dec = base64_decode($ciphertext_base64);
// $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
//
//
// # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
// $iv_dec = substr($ciphertext_dec, 0, $iv_size);
//
// # retrieves the cipher text (everything except the $iv_size in the front)
// $ciphertext_dec = substr($ciphertext_dec, $iv_size);
//
// # may remove 00h valued characters from end of plain text
// $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
// 														 $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

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
		 downloadAndCreateImage($link);
		 redirectToOriginalLink($htmlFileName);
	 }

?>
