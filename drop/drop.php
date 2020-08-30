
<?php 
    include '/home/pi/Webroot/Orthanc/db.php';
    if(json_decode($valid)->Valid == true){
        $result = getNextDrop();
    }
    else $result = '{"Valid":false, "Login: "' . $login . '"}';
?>