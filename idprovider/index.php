<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['lobbyKey'])) $key = $_POST['lobbyKey'];
    if(isset($_POST['lobbyID'])) $id = $_POST['lobbyID'];
    if(isset($_POST['member'])) $member = $_POST['member'];
    if(isset($_POST['description'])) $description = $_POST['description'];

    include 'provider.php';

    echo $result;
?>