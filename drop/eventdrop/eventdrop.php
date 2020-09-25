<?php
    // gets all online sprite maps
    include '/home/pi/Webroot/Orthanc/db.php';
    $result = '{"EventDrops" : ' . getEventDrops() . '}";
?>