<?php

    // checks if a drop is available atm
    if(isset($_POST['login'])) $login = $_POST['login'];
    if(isset($_POST['dropID'])) $dropID = $_POST['dropID'];
    if(isset($_POST['lobbyPlayerID'])) $lobbyPlayerID = $_POST['lobbyPlayerID'];
    if(isset($_POST['lobbyKey'])) $lobbyKey = $_POST['lobbyKey'];

    $result = "";

    include 'claim.php';

    echo $result; 

?>