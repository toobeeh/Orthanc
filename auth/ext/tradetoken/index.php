<?php 
    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: GET, POST');

    header("Access-Control-Allow-Headers: X-Requested-With");
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['accessToken'])) $token = $_POST['accessToken'];

    include 'token.php';

    echo $valid;
?>