<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);
    $guildDirectory = '/home/pi/JsonShared/';

    // processes a lobby $key, if not present generate a $id 

    if(!isset($member)) {
        $result = '{"Valid": false, "Member":null}';
        return;
    }

    if(!isset($key)) {
        $result = '{"Valid": false, "Key":null}';
        return;
    }

    // verify member
    $members = json_decode(file_get_contents($guildDirectory . 'members.json'));
    $sender = json_decode($member);

    $authenticatedMember;
    foreach($members as $savedMember){
        if ($savedMember->UserID == $sender->UserID && $savedMember->UserLogin == $sender->UserLogin) $authenticatedMember = $savedMember;
    }

    if(!isset($authenticatedMember)) {
        $result = '{"Valid": false, "Member":' . json_decode($member) . '}';
        return;
    }

    // if id is not set, search for lobbies with same key
    if(!isset($id)){
        $lobbies = json_decode(file_get_contents( $guildDirectory . "lobbies.json"));

        foreach($lobbies as $lobby){
            if ($lobby->Key == $key) $id=$lobby->ID;
        }

        // if no lobby with same key is present, generate new id
        if(!isset($id)) $id =  str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
        $jsonLobby = json_decode('{"ID":"' . $id . '", "Key":"' . $key . '"}');
        array_push($lobbies, $jsonLobby);
        file_put_contents("lobbies.json", json_encode($lobbies));
        rename("lobbies.json", $guildDirectory . "lobbies.json");
        $result = '{"Valid": true, "Member":' . json_encode($member) . ', "Lobby":' . json_encode($jsonLobby) . '}';
    }
    else{
        $lobbies = json_decode(file_get_contents($guildDirectory . "lobbies.json"));

        $match;
        foreach($lobbies as $lobby){
            if ($lobby->ID == $id) {$lobby->Key = $key; $match = $lobby;}
        }
        file_put_contents("lobbies.json", json_encode($lobbies));
        rename("lobbies.json", $guildDirectory . "lobbies.json");
        $result = '{"Valid": true, "Member":' . json_encode($member) . ', "Lobby":' . json_encode($match) . '}';
    }
?>