<?php 

    $membersPath = "/home/pi/JsonShared/members.json";


    if(!isset($login)){
        $valid = '{"Valid":false}';
        return;
    }

    // $validUser;
    // $cont = file_get_contents($membersPath);

    // $members = json_decode($cont);
    // foreach($members as $member){
    //     if($member->UserLogin == $login)  $validUser = $member;
    // }

    include '/home/pi/Webroot/Orthanc/db.php';

    $member = getMemberJSON($login);


    if($member !== false) $valid = '{"Valid":true, "Member":' . $member . "}";
    else  $valid = '{"Valid":false, "Error": "' . $_db->lastErrorMsg() . '", "Login: "' . $login . '"}';
?>