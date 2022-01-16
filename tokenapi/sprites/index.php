<?php
    // gets all sprites, drops and scenes
    include '/home/pi/Webroot/Orthanc/db.php';
    echo '{"Scenes": '. getScenes() . ', "Sprites": ' . getAvailableSprites(). ', "Drops": ' . getEventDrops() . "}";
?>