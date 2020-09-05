<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(!isset($_GET['auth'])||$_GET['auth'] != "supersecret") {
        die("<h2>401: Unauthorized</h2>");
    }
    
    echo "<h2>Plantir Bot Log</h2><h3> Refreshed:" . date("Y-m-d H:i:s") . "</h3><br>";
    $file = file("/home/pi/palantirOutput.log");
    $file = array_reverse($file);
    foreach($file as $f){
        echo $f."<br />";
    }
    echo "<script>setInterval(()=>{if(window.scrollY == 0)location.reload();},2000);</script>";

?>