<?php 
    include '/home/pi/Webroot/Orthanc/db.php';
    if(getMemberJSON($login) != false && isset($dropID) && isset($lobbyKey) && isset($lobbyPlayerID)){
        $result = claimDrop($dropID,$lobbyKey,$lobbyPlayerID);
    }
    else $result = '{"Valid":false, "Login: "' . $login . '"}';
?>