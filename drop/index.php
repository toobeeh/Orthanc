<?php

    // checks if a drop is available atm
    if(isset($_POST['member'])) $member = $_POST['member'];
    else $member="";
    if(isset($_POST['login'])) $login = $_POST['login'];

    $result = "";

    include 'drop.php';

    echo $result; 

?>