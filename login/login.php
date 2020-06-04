<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(!isset($tag))return;

    file_put_contents('/home/pi/JsonShared/Members/' . $tag .'.login', $tag);
?>