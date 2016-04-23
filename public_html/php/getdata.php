<?php
require_once "../php/DataBaseHandling.php";
$conn = openConnection();
header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
if(isset($_POST['key']))
{
    $key = $_POST['key'];
    $link = selectArrayFromDatabase($key,$conn);
}
else{
    $link = "http://joke.com";
}

closeConnection($conn);
//echo json_encode(array(
//    'link' => $link[1]),JSON_UNESCAPED_SLASHES);
echo $link[1];
?>