<?php
    
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);
    
    // checks if a drop is available atm
    if(isset($_POST['login'])) $login = $_POST['login'];
    if(isset($_POST['dropID'])) $dropID = $_POST['dropID'];
    if(isset($_POST['lobbyPlayerID'])) $lobbyPlayerID = $_POST['lobbyPlayerID'];
    if(isset($_POST['lobbyKey'])) $lobbyKey = $_POST['lobbyKey'];

    $result = "";

    include 'claim.php';

    echo $result; 

?>