<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['lobbyReport'])) $report = $_POST['lobbyReport'];
    else $report="";
    if(isset($_POST['member'])) $member = $_POST['member'];
    else $member="";

    include 'report.php';

    echo $result;   



?>