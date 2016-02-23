<?php

include "../php/DataBaseHandling.php";
$conn = openConnection();


$title = $_POST['title'];
$news = $_POST['news'];
$imgsrc = $_POST["imgsrc"];
$header = $_POST['header'];

addtoNewsDatabase($conn , $title , $imgsrc , $news , $header);



header("access-control-allow-origin: *");
header("Location: ".'http://stme.esy.es/news.html', true, 303);
die();