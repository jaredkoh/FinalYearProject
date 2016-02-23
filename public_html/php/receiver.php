<?php
include "DataBaseHandling.php";
$conn = openConnection();
$result = getMostRecentCoordinates($conn);
closeConnection($conn);

echo $result;
 ?>
