<?php

// checks a $token string and searches for fitting lobbies

$lobbies = "[]";

// path to report directory
$path ='/home/pi/JsonShared/';

include '/home/pi/Webroot/Orthanc/verify/verify.php';

$authenticatedGuild = json_decode($jsonOutput);
if(!$authenticatedGuild->Valid) {
    $lobbies = '{"Status":"Unauthorized status request", "Verify": '.$jsonOutput.'}';
    return;
}

$files = scandir($path);
foreach($files as $file){
    $parts =explode('/', $file);
    echo end($parts);
    echo $file;
    if(end($parts) == "statusGuild".$authenticatedGuild.'.json') $lobbies = file_get_contents($file);
}

$lobbies = '{"Status": "Successful status request", "Lobbies":'.$lobbies.', "Verify":'.$jsonOutput.'}';
?>