<?php 

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL); 
    include '/home/pi/Webroot/Orthanc/db.php';
    if(getMemberJSON($login) != false && isset($dropID) && isset($lobbyKey) && isset($lobbyPlayerID)){
        $result = claimDrop($dropID,$lobbyKey,$lobbyPlayerID);
    }
    else $result = '{"Valid":false, "Login": ' . $login . '"}';
?>