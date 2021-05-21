<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['login'])) $login = $_POST['login'];
    if(isset($_POST['vote1'])) $vote1 = $_POST['vote1'];
    if(isset($_POST['vote1'])) $vote1 = $_POST['vote1'];

    // include db functions
    include '/home/pi/Webroot/Orthanc/db.php';
    // login token set?
    if(!isset($login) || getMemberJSON($login) === false){
        $valid = 'false';
    }
    else{
        setSubmissionVotes($login, $vote1, $vote2);     
        $valid = "true";   
    }
    echo $valid;
?>