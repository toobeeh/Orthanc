<?php 

    header("HTTP/1.1 410 Gone");
    exit;

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['accessToken'])) $token = $_POST['accessToken'];

    include 'token.php';

    echo $valid;
?>