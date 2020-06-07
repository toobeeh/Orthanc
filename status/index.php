<?php

    // ini_set('display_errors', 1); 
    // ini_set('display_startup_errors', 1); 
    // error_reporting(E_ALL);

    if(isset($_POST['observeToken'])) $token = $_POST['observeToken'];
    else $token="";
    if(isset($_POST['member'])) $member = $_POST['member'];
    else $member="";

    include 'status.php';

    echo $lobbies;   
?>