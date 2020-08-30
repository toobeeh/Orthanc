<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL); 
    // checks if a drop is available atm
    if(isset($_POST['login'])) $login = $_POST['login'];

    $result = "";

    include 'drop.php';

    echo $result; 

?>