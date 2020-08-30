<?php

    // checks if a drop is available atm
    if(isset($_POST['login'])) $login = $_POST['login'];

    $result = "";

    include 'drop.php';

    echo $result; 

?>