<?php 

    $membersPath = "/home/pi/JsonShared/members.json";


    if(!isset($login)){
        $valid = '{"Valid":false}';
        return;
    }

    $validUser;
    $cont = file_get_contents($membersPath);

    $members = json_decode($cont);
    foreach($members as $member){
        if($member->UserLogin == $login)  $validUser = $member;
    }

    if(isset($validUser)) $valid = '{"Valid":true, "Member":' . json_encode($validUser) . "}";
    else  $valid = '{"Valid":false, "Error": "' . json_last_error_msg() . '", "!==NULL": "' . $cont!==NULL . '"}';
?>