<?php
include "../php/DataBaseHandling.php";
$conn = openConnection();
if(!empty($_POST['lat'])&& !empty($_POST['long'])) {
  $lat = $_POST['lat'];
  $long = $_POST['long'];
  addCoordinatesToDatabase($lat , $long , $conn);
}
?>
