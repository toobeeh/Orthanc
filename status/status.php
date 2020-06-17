<?php

// verifies a token and member string and searches for fitting lobbies

include '/home/pi/Webroot/Orthanc/verify/verify.php';

$verify = json_decode($jsonOutput);
print_r($jsonOutput);
if(!$verify->Valid) {
    $lobbies = '{"Status":"Unauthorized status request", "Verify": '.$jsonOutput.'}';
    return;
}

$guildLobbies = getGuildLobbiesJSON($verify->AuthGuildID);
if($guildLobbies === false) $guildLobbies = "[]";

$lobbies = '{"Status": "Successful status request", "Lobbies":'.$guildLobbies.', "Verify":'.$jsonOutput.'}';

?>