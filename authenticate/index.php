<?php 
    if(isset($_GET['ObserveToken'])) $ token = $_GET['ObserveToken'];

    include verify.php;

    echo $jsonOutput;   
?>