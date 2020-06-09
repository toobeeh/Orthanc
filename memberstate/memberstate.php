<?php

    // sets the state of a member so searching, waiting or playing
    // processes a $playerstatus json string
    include '/home/pi/Webroot/Orthanc/db.php';

    if(!isset($playerstatus)) return;
    $status = json_decode($playerstatus);

    // verify member
    $member = getMemberJSON($status->PlayerMember->UserLogin);
    if($member === false) {
        $jsonOutput = '{"Status":"No valid member", "Member":' . json_encode($status->PlayerMember) . ', "Status":' . json_encode($status->Status) . '}'; 
        return; 
    }

    // check status
    if(!($status->Status == "playing" || $status->Status == "searching" || $status->Status == "waiting")) {
        $jsonOutput = '{"Status":"No valid status", "Member":' . json_encode($status->PlayerMember) . ', "Status":' . json_encode($status->Status) .'}'; 
        return;
    }

    writeStatus($playerstatus);

    $jsonOutput = '{"Status":"Valid", "Member":' . json_encode($status->PlayerMember) . ', "Status":' . json_encode($status->Status) .'}'; 

?>