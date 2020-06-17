<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['playerStatus'])) $playerstatus = $_POST['playerStatus'];
    if(isset($_POST['session'])) $session = $_POST['session'];

    include 'memberstate.php';

    echo $jsonOutput;   
?>