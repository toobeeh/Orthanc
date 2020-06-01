<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['ObserveToken'])) $token = $_POST['ObserveToken'];
    else $token="";

    include 'status.php';

    echo $lobbies;   
?>