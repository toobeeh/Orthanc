<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['login'])) $login = $_POST['login'];

    include 'login.php';

    echo $valid;

?>