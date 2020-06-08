<?php 



function getMemberJSON($_login){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('SELECT * FROM Members WHERE Login = ?');
    $_sql->bindParam(1, $_login);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) return $_row['Member'];
    else return false;
}




?>