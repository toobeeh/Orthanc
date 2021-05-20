<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(isset($_POST['login'])) $login = $_POST['login'];
    if(isset($_POST['url'])) $url = $_POST['url'];
    // login token set?
    if(!isset($login)){
        $valid = '{"Valid":false}';
    }
    else{
        // include db functions
        include '/home/pi/Webroot/Orthanc/db.php';

        // get matching member from db
        $member = getMemberJSON($login);

        // evaluate member
        if($member !== false) {
            if(getPalantirSubmission($login)) $valid = '{"Valid":true, "Image":"'.getPalantirSubmission($login).'"}';
            else {
                addPalantirSubmission($login, $url);
                $valid = '{"Valid":true, "Image":"'.getPalantirSubmission($login).'"}';
            }
            
        }
        else $valid = '{"Valid":false}';
    }
    echo $valid;
?>