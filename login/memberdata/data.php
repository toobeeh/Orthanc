<?php 
    // login token set?
    if(!isset($login)){
        $valid = '{"Valid":false}';
        return;
    }

    // include db functions
    include '/home/pi/Webroot/Orthanc/db.php';

    // get matching member from db
    $member = getFullMemberData($login);

    // evaluate member
    if($member !== false) $valid = '{"Valid":true, "MemberData":' . $member . "}";
    else  $valid = '{"Valid":false, "Login: "' . $login . '"}';
?>