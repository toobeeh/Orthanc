<?php
    
    // processes a $report string containing a lobby and generates result
    //$report = '{   "ID": 1,   "Round": 3,   "ServerID": 334696834322661376,   "Private": false,   "Host": "skribbl.io",   "ObserveToken": "17839118",   "Players": [     {       "Name": "tobeh",       "Score": 245,       "Drawing": true     },     {       "Name": "asdasd",       "Score": 8000     },     {       "Name": "asd",       "Score": 3456     },     {       "Name": "afasf",       "Score": 765,       "Sender": true     },     {       "Name": "tobsgdseh",       "Score": 8234     },     {       "Name": "tobasdeh",       "Score": 345,       "Sender": true     },     {       "Name": "asd",       "Score": 4354     }   ],   "Kicked": [     { "Name": "random" },     { "Name": "random2" }   ] } '
    $result = "";

    // path to report directory
    $path ='/home/pi/JsonShared/';

    // decode lobby string and verify
    $lobby = json_decode($report);
    if(!isset($lobby->ObserveToken) || !isset($lobby->ID)|| !isset($lobby->ServerID)) {
        $result = '{"Status":"Unauthorized report", "Verify": }';
        return;
    }
    $jsonOutput="";
    $token = $lobby->ObserveToken;
    
    include '/home/pi/Webroot/Orthanc/verify/verify.php';

    $authenticatedGuild = json_decode($jsonOutput);
    if(!$authenticatedGuild->Valid) {
        $result = '{"Status":"Unauthorized report", "Verify": '.$jsonOutput.'}';
        return;
    }

    file_put_contents($lobby->ServerID .'reportID' . $lobby->ID . '.json', $report);
    rename('reportID' . $lobby->ID . '.json', $path . 'reportID' . $lobby->ID . '.json');
    $result = '{"Status":"Successful report", "ID":'.$lobby->ID.', "ServerID":"'.$lobby->ServerID.'", "Verify": '.$jsonOutput.'}';
?>