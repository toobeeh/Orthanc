<?php

    // sets the state of a member so searching, waiting or playing
    // processes a $playerstatus json string

    if(!isset($playerstatus)) return;
    $status = json_encode($playerstatus);

    $guildDirectory = '/home/pi/JsonShared/';
    $members = json_decode(file_get_contents($guildDirectory . 'members.json'));

    $authMember;
    foreach($members as $savedMember){
        if ($savedMember->UserID == $status->Playermember->UserID && $savedMember->UserLogin == $status->Playermember->UserLogin)
            $authMember = $savedMember;
    }

    if(!isset($authMember)) return;
    if(!($status->Status == "playing" || $status->Status == "searching" || $status->Status == "waiting")) return;

    if( $status->Status == "searching") $filename = 'statusMember' . $status->PlayerMember->UserID . '.json';
    else  $filename = 'statusMember' . $status->PlayerMember->UserID . 'Lobby' . $status->lobbyID . '.json';

?>