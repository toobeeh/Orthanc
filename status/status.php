<?php

// checks a $token string and searches for fitting lobbies

$lobbies = "[]";

// path to report directory
$path ='/home/pi/JsonShared';

include '/home/pi/Webroot/Orthanc/verify/verify.php';

$authenticatedGuild = json_decode($jsonOutput);
if(!$authenticatedGuild->Valid) {
    $lobbies = '{"Status":"Unauthorized status request", "Verify": '.$jsonOutput.'}';
    return;
}

$files = array_diff( scandir($path,1), array(".", "..") );
foreach($files as $file){
    $name = basename($file);
    echo $name;
    echo "statusGuild".$authenticatedGuild->AuthGuildID.'.json';
    if($name == "statusGuild".number_format($authenticatedGuild->AuthGuildID,0).'.json') $lobbies = file_get_contents($file);
}

$lobbies = '{"Status": "Successful status request", "Lobbies":'.$lobbies.', "Verify":'.$jsonOutput.'}';
?>