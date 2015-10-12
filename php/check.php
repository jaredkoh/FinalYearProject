<?php>

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
$key = substr($actual_link , 23, strlen($actual_link));


$sql = "SELECT longlink FROM DATA WHERE shortlink = "$key";

$result = $conn->query($sql);

function redirect($url, $statusCode = 303)
{
   echo '$reuslt';
   header('Location: ' . $url, true, $statusCode);
   die();
}

redirect($result , true);

?>
