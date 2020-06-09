<?php
    
    // processes a $report string containing a lobby and generates result
    
    $result = "";

    // decode lobby string and verify
    $lobby = json_decode($report);
    if(!isset($lobby->ObserveToken) || !isset($lobby->ID)|| !isset($lobby->GuildID)) {
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

    writeReport($lobby->ID, $report);
    $result = '{"Status":"Successful report", "ID":'.$lobby->ID.', "GuildID":"'.$lobby->GuildID.'", "Verify": '.$jsonOutput.'}';
?>