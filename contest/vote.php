<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['login'])) $_login = $_POST['login'];
    if(isset($_POST['vote1'])) $_vote1 = $_POST['vote1'];
    else $_vote1 = "";
    if(isset($_POST['vote2'])) $_vote2 = $_POST['vote2'];
    else $_vote2 = "";

    // include db functions
    include '/home/pi/Webroot/Orthanc/db.php';
    // login token set?
    if(!isset($_login) || getMemberJSON($_login) === false){
        $valid = 'false';
    }
    else{
        setSubmissionVotes($_login, $_vote1, $_vote2);     
        $valid = "true";   
    }
    echo $valid;
?>