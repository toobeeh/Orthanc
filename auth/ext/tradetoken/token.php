<?php 
    // login token set?
    if(!isset($token)){
        $valid = '{"Valid":false}';
        return;
    }

    // include db functions
    include '/home/pi/Webroot/Orthanc/db.php';

    // get matching member from db
    $login = getMemberLoginByToken($token);

    // evaluate member
    if($login !== false) $valid = '{"Valid":true, "Login":' . $login . "}";
    else $valid = '{"Valid":false, "Token: "' . $token . '"}';
?>