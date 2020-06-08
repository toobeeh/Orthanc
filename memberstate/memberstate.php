<?php

    // sets the state of a member so searching, waiting or playing
    // processes a $playerstatus json string

    if(!isset($playerstatus)) return;
    $status = json_decode($playerstatus);

    $guildDirectory = '/home/pi/JsonShared/';
    $members = json_decode(file_get_contents($guildDirectory . 'members.json'));

    $authMember;
    foreach($members as $savedMember){
        if ($savedMember->UserID == $status->PlayerMember->UserID && $savedMember->UserLogin == $status->PlayerMember->UserLogin)
            $authMember = $savedMember;
    }

    if(!isset($authMember)) {
        $jsonOutput = '{"Status":"No valid member", "Member":' . json_encode($status->PlayerMember) . ', "Status":' . json_encode($status->Status) . '}'; 
        return; 
    }
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