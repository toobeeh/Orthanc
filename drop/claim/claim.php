<?php 
    include '/home/pi/Webroot/Orthanc/db.php';
    if(getMemberJSON($login) != false && isset($_dropID) && isset($_lobbyKey) && isset($_lobbyPlayerID)){
        $result = claimDrop($dropID,$lobbyKey,$lobbyPlayerID);
    }
    else $result = '{"Valid":false, "Login: "' . $login . '"}';
?>