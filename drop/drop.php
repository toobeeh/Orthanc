
<?php 
    include '/home/pi/Webroot/Orthanc/db.php';
    if(getMemberJSON($login) != false){
        $result = getNextDrop();
    }
    else $result = '{"Valid":false, "Login": ' . $login . '"}';
?>