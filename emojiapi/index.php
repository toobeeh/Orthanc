<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('max_execution_time', '300'); 
function getAvailableEmojiID($_name){
    $_db = new SQlite3('/home/pi/Database/emojis.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');
    $_sql = $_db->prepare('SELECT * FROM Emojis WHERE Name = ? ORDER BY ID DESC');
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
        $_db->close();
    }
}
function getAll($_name){
    $_db = new SQlite3('/home/pi/Database/emojis.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');
    if(is_string($_name) && strlen($_name) > 0){
        $_sql = $_db->prepare('SELECT * FROM Emojis WHERE Name like ?');
        $_name = "%" . $_name . "%";
        $_sql->bindParam(1, $_name);
    }
    else {
        $_sql = $_db->prepare('SELECT * FROM Emojis');
    }
    $_result = $_sql->execute();
    $_return = "[";
    while($_row = $_result->fetchArray()) 
        $_return = $_return . '{"name":"' . $_row["Name"] . "-" . $_row["ID"] . '", "url":"' . $_row["URL"] . '"},';
    $_db->close();
    $_return = substr($_return, 0, -1) . "]";
    return $_return;
}
if(isset($_GET["add"])){
    if(isset($_GET["anim"])) $resUrl = "https://discords.com/api-v2/emoji/search?type=animated&query=" . $_GET["add"];
    else $resUrl = "https://discords.com/api-v2/emoji/search?type=static&query=" . $_GET["add"];
    $ch = curl_init($resUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    // $regex = '/<p[^>]*class=[^>]*"pack-description[^>]*"[^>]*>(.+?)<\/p>.*?https:([^?]+)\?.*?<img/m';
    // preg_match_all($regex, $response, $matches, PREG_SET_ORDER, 0);
    // foreach ($matches as $match) {
    //     addEmoji($match[1], "https:" . $match[2]);
    // }
    $json_response = json_decode($response);
    $pages = $json_response->pages;
    $count = 0;
    for($page = 1; $page++; $page <= $pages){
        $pageurl = $ch . "&page=" . $page;
        $emojipage = curl_exec($pgeurl);
        foreach(json_decode($emojipage)->emojis as $emoji){
            if(isset($_GET["anim"])) $emourl = "https://cdn.discordapp.com/emojis/" . $emoji->id . ".gif";
            else $emourl = "https://cdn.discordapp.com/emojis/" . $emoji->id . ".png";
            addEmoji($emoji->name, $emourl);
            $count++;
        }
    }
    file_put_contents("all.json", getAll(""));
    echo $count;
}
else if(isset($_GET["get"])){
    echo getAll($_GET["get"]);
}
else {
    $em = getAll($_GET["show"]);
    foreach(json_decode($em) as $emoji){
        echo "<span><img height='20px' src='" . $emoji->url . "'>" . $emoji->name . "</span>";
    }
}

?>
