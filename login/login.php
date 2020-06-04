<?php 

    $membersPath = "/home/pi/JsonShared/members.json";

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(!isset($login)){
        $valid = '{"Valid":false}';
        return;
    }

    $validUser;

    $members = json_decode(file_get_contents($membersPath));
    foreach($members as $member){
        if($member->UserLogin == $login)  $validUser = $member;
    }

    if(isset($validUser)) $valid = '{"Valid":true, "User":' . json_encode($validUser) . "}";
    else  $valid = '{"Valid":false}';
?>