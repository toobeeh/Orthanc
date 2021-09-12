<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_GET["id"]) && isset($_GET["username"])) {
    include '/home/pi/Webroot/Orthanc/db.php';

    $id = $_GET["id"];
    $username = $_GET["username"];
    $login; // find unique login
    do{
        $login = mt_rand(0,999999);
    }
    while(getMemberJSON($login));
    addMember($login, $username, $id);
    header("Location: https://skribbl.io/?login=" . $login);
}
else {
    header("Location: https://skribbl.io/");
}