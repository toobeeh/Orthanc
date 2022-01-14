<?php
    // gets all online sprite maps
    include '/home/pi/Webroot/Orthanc/db.php';
    $result = '{"OnlineSprites" : ' . getSprites() . ', "Scenes": '. getScenes() . ', "Sprites": ' . getAvailableSprites(). ', "Drops": ' . getEventDrops() . "}";
?>