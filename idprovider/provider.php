<?php 

    // processes a lobby $key, if not present generate a $id 

    include '/home/pi/Webroot/Orthanc/db.php';

    if(!isset($member) || !isset($key)) {
        if(!isset($member) && !isset($key) && isset($id)){
            $result= '{"Valid":true, "Lobby":' . getLobbyJSONByID($id) . '}';
        }
        else $result = '{"Valid": false, "Key":null, "Member":null}';
        return;
    }

    // verify member
    $sender = json_decode($member);
    $authenticatedMember = getMemberJSON($sender->UserLogin);
    if($authenticatedMember === false) {
        $result = '{"Valid": false, "Member":' . $member . '}';
        return;
    }

    // if id is not set, search for lobbies with same key
    if(!isset($id)){

        $existing = getLobbyJSONByKey($key);

        // If no lobby with that key is found add new lobby
        if($existing === false){
            $id =  str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
            if(!isset($description)) $description = "";
            $lobby = '{"ID":"' . $id . '", "Key":"' . $key . '", "Description": "' . $description . '"}';
            addLobby($id, $lobby);
            $result = '{"Valid": true, "Member":' . json_encode($member) . ', "Lobby":' . $lobby . '}';
            return;
        }
        // if a lobby with that key is found, return lobby
        else{
            $result = '{"Valid": true, "Member":' . json_encode($member) . ', "Lobby":' . $existing . '}';
            $lobbyObj = json_decode($existing);
            // if lobby has no description and description is given, set description
            if($lobbyObj->Description == "" && isset($description)) {
                $lobbyObj->Description = $description;
                updateLobbyJSON($lobbyObj->ID, json_encode($lobbyObj));
                $result = getLobbyJSONByKey($key);
            }
            return;
        }
    }
    // if the id is known and the key has changed, update the key
    else{
        $desc = getDescriptionByID($id);
        $lobby = '{"ID":"' . $id . '", "Key":"' . $key . '", "Description": "' . $desc . '"}';
        updateLobbyJSON($id, $lobby);
        $result = '{"Valid": true, "Member":' . json_encode($member) . ', "Lobby":' . $lobby . '}';
        return;
    }
?>