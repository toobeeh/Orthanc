<?php 

$_db = new SQlite3('/home/pi/Database/palantir.db');

function getMemberJSON($_login){
    $_sql = $_db->prepare('SELECT * FROM Members WHERE Login = ?');
    $_sql->bindParam(1, $login);
    $_result = $sql->execute();
    if($_row = $_result->fetchArray()) return $_row['Member'];
    else return false;
}




?>