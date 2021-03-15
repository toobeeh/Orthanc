<?php
function getAvailableEmojiID($_name){
    $_db = new SQlite3('/home/pi/Database/emojis.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');
    $_sql = $_db->prepare('SELECT * FROM Emojis WHERE Name = ? ORDER BY ID DESCENDING');
    $_sql->bindParam(1, $_name);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_id = $_row['ID'] + 1;
    else $_id = 0;
    $_db->close();
    return $_id;
}
function checkDupe($_url){
    $_db = new SQlite3('/home/pi/Database/emojis.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');
    $_sql = $_db->prepare('SELECT * FROM Emojis WHERE URL = ?');
    $_sql->bindParam(1, $_url);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = true;
    else $_return = false;
    $_db->close();
    return $_return;
}
function addEmoji($_name, $_url){
    if(!checkDupe($_url)) {
        $_id = getAvailableEmojiID($_name);
        $_db = new SQlite3('/home/pi/Database/emojis.db');
        $_db->busyTimeout(1000);
        $_db->exec('PRAGMA journal_mode = wal;');
        $_sql = $_db->prepare('INSERT INTO Emojis VALUES(?, ?, ?)');
        $_sql->bindParam(1, $_name);
        $_sql->bindParam(2, $_id);
        $_sql->bindParam(3, $_url);
        $_result = $_sql->execute();
    }
}

$resUrl = "https://api.allorigins.win/get?url=https://discordservers.me/animatedsearch?emoji=" . $_GET["keyword"];
$ch = curl_init($resUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$regex = '/<p[^>]*class=[^>]*"pack-description[^>]*"[^>]*>(.+?)<\/p>.*?https:([^?]+)\?.*?<img/m';
preg_match_all($regex, $response, $matches, PREG_SET_ORDER, 0);
foreach ($matches as $match) {
    addEmoji($match[1], "https:" . $match[2]);
}
?>
