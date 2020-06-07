<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['playerReport'])) $report = $_POST['playerReport'];
    else $report="";
    if(isset($_POST['observeToken'])) $token = $_POST['observeToken'];

    include 'player.php';

    echo $result;   
?>