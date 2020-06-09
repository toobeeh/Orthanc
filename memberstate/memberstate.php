<?php

    // sets the state of a member so searching, waiting or playing
    // processes a $playerstatus json string
    include '/home/pi/Webroot/Orthanc/db.php';

    if(!isset($playerstatus)) return;
    $status = json_decode($playerstatus);

    // verify member
    $member = getMemberJSON($status->PlayerMember->UserID);
    if($member === false) {
        $jsonOutput = '{"Status":"No valid member", "Member":' . json_encode($status->PlayerMember) . ', "Status":' . json_encode($status->Status) . '}'; 
        return; 
    }

    // check status
    if(!($status->Status == "playing" || $status->Status == "searching" || $status->Status == "waiting")) {
        $jsonOutput = '{"Status":"No valid status", "Member":' . json_encode($status->PlayerMember) . ', "Status":' . json_encode($status->Status) .'}'; 
        return;
    }

    if( $status->Status == "searching") $filename = 'statusMember' . $status->PlayerMember->UserID . '.json';
    if( $status->Status == "waiting") $filename = 'statusMember' . $status->PlayerMember->UserID . 'Lobby' . $status->LobbyID . '.json';
    if( $status->Status == "playing") $filename = 'statusMember' . $status->PlayerMember->UserID . 'Lobby' . $status->LobbyID . 'Player' . $status->LobbyPlayerID. '.json';


    file_put_contents($filename, json_encode($status));
    rename($filename, $guildDirectory . 'OnlinePlayers/' . $filename);

    $jsonOutput = '{"Status":"Valid", "Member":' . json_encode($status->PlayerMember) . ', "Status":' . json_encode($status->Status) .'}'; 

?>