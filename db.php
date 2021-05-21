<?php 
// Functions to access palantir DB (SQLite3)
// statements are prepared to prevent sql injection

// -------------------------------------
//              Table: Members
// -------------------------------------

// Check if members has row with login
function getMemberJSON($_login){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT * FROM Members WHERE Login = ?');
    $_sql->bindParam(1, $_login);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = $_row['Member'];
    else $_return = false;
    $_db->close();
    return $_return;
}

// Get Member sprite data
function getFullMemberData($_login){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT * FROM Members WHERE Login = ?');
    $_sql->bindParam(1, $_login);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return =  json_encode($_row);
    else $_return = false;
    $_db->close();
    return $_return;
}

// Set member Json (for example to add new guild)
function setMemberJSON($_login, $_json){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('UPDATE Members SET Member = ? WHERE Login = ?');
    $_sql->bindParam(1, $_json);
    $_sql->bindParam(2, $_login);
    $_result = $_sql->execute();
    $_db->close();
    return $_result;
}

// -------------------------------------
//              Table: GuildLobbies
// -------------------------------------

// Get guild lobbies for a guild token
// Table is read-only, as only palantir bot collects lobby data and generates a guild lobby array
function getGuildLobbiesJSON($_guildID){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT * FROM GuildLobbies WHERE GuildID = ?');
    $_sql->bindParam(1, $_guildID);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = $_row['Lobbies'];
    else $_return = false;
    $_db->close();
    return $_return;
}

// -------------------------------------
//              Table: Palantiri
// -------------------------------------

// Get palantir from obersve token
// Table is read-only, as only palantir bot listens to discord commands to modify settings
function getPalantirJSON($_observeToken){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT * FROM Palantiri WHERE Token = ?');
    $_sql->bindParam(1, $_observeToken);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = $_row['Palantir'];
    else $_return = false;
    $_db->close();
    return $_return;
}

// -------------------------------------
//              Table: Lobbies
// -------------------------------------

// Find a lobby by a lobby key
function getLobbyJSONByKey($_lobbyKey){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT * FROM Lobbies WHERE Lobby LIKE ?');
    $_lobbyKey = "%" . $_lobbyKey . "%";
    $_sql->bindParam(1, $_lobbyKey);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = $_row['Lobby'];
    else $_return = false;
    $_db->close();
    return $_return;
}

// Find a lobby by a lobby id
function getLobbyJSONByID($_lobbyID){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT * FROM Lobbies WHERE LobbyID = ?');
    $_sql->bindParam(1, $_lobbyID);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = $_row['Lobby'];
    else $_return = false;
    $_db->close();
    return $_return;
}

// Find a lobby description a lobby id
function getDescriptionByID($_lobbyID){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT * FROM Lobbies WHERE LobbyID = ?');
    $_sql->bindParam(1, $_lobbyID);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = isset(json_decode($_row['Lobby'])->Description) ? json_decode($_row['Lobby'])->Description : "";
    else $_return = '';
    $_db->close();
    return $_return;
}

// update the lobby data for a lobby id
function updateLobbyJSON($_lobbyID, $_lobbyJson){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('UPDATE Lobbies SET Lobby = ? WHERE LobbyID = ?');
    $_sql->bindParam(1, $_lobbyJson);
    $_sql->bindParam(2, $_lobbyID);
    $_result = $_sql->execute();
    $_db->close();
    return $_result;
}

// add a new lobby
function addLobby($_lobbyID, $_lobbyJson){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('INSERT INTO Lobbies VALUES (?, ?)');
    $_sql->bindParam(1, $_lobbyID);
    $_sql->bindParam(2, $_lobbyJson);
    $_result = $_sql->execute();
    $_db->close();
    return $_result;
}

// -------------------------------------
//              Table: Reports
// -------------------------------------

function writeReport($_lobbyID, $_observeToken, $_reportJson){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("REPLACE INTO Reports VALUES(?, ?, ?, datetime('now'))");
    $_sql->bindParam(1, $_lobbyID);
    $_sql->bindParam(2, $_observeToken);
    $_sql->bindParam(3, $_reportJson);
    $_result = $_sql->execute();

    // remove entries older than 20s to avoid big data
    ($_db->prepare("DELETE FROM Reports WHERE Date < datetime('now', '-30 seconds')"))->execute();
    $_db->close();
    return $_result;
}

// -------------------------------------
//              Table: Status
// -------------------------------------

function writeStatus( $_sessionID, $_statusJSON){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("REPLACE INTO Status VALUES(?, ?, datetime('now'))");
    $_sql->bindParam(1, $_sessionID);
    $_sql->bindParam(2, $_statusJSON);
    $_result = $_sql->execute();

    // remove entries older than 30s to avoid big data
    ($_db->prepare("DELETE FROM Status WHERE Date < datetime('now', '-30 seconds')"))->execute();
    $_db->close();
    return $_result;
}

// -------------------------------------
//              Table: Sprites
// -------------------------------------

function getSprites(){
    // get all online sprites
    // sprites are written into the db by palantir. the member table stores the sprites
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    // remove entries older than 10s to avoid big data
    ($_db->prepare("DELETE FROM OnlineSprites WHERE Date < datetime('now', '-10 seconds')"))->execute();

    $_sql = $_db->prepare("SELECT * FROM OnlineSprites");
    $_result = $_sql->execute();
    
    $_return = array();
    while($_row = $_result->fetchArray()) 
        array_push($_return, 
            array("LobbyKey"=>$_row["LobbyKey"],"LobbyPlayerID"=>$_row["LobbyPlayerID"],"Sprite"=>$_row["Sprite"])
        );

    $_db->close();
    return json_encode($_return);
}

function getAvailableSprites(){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("SELECT * FROM Sprites");
    $_result = $_sql->execute();
    
    $_return = array();
    while($_row = $_result->fetchArray()) 
        array_push($_return, 
            array("ID"=>$_row["ID"],"Name"=>$_row["Name"],"URL"=>$_row["URL"],"Cost"=>$_row["Cost"],"Special"=>$_row["Special"],"EventDropID"=>$_row["EventDropID"],"Artist"=>$_row["Artist"])
        );

    $_db->close();
    return json_encode($_return);
}

function getSpriteByGifName($_gif){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_result = $_db->query('SELECT * FROM Sprites WHERE URL LIKE "%'.$_gif.'%" ');
    
    if($_row = $_result->fetchArray())
        $_return = array("ID"=>$_row["ID"],"Name"=>$_row["Name"],"URL"=>$_row["URL"],"Cost"=>$_row["Cost"],"Special"=>$_row["Special"],"EventDropID"=>$_row["EventDropID"]);

    $_db->close();
    return $_return;
}

// -------------------------------------
//              Table: DROP
// -------------------------------------

function getNextDrop(){
    // get a available drop
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    // remove entries older than 1h to avoid big data
    ($_db->prepare("DELETE FROM 'Drop' WHERE ValidFrom < datetime('now', '-3600 seconds')"))->execute();

    $_sql = $_db->prepare("SELECT * FROM 'Drop' WHERE CaughtLobbyKey = ''");
    $_result = $_sql->execute();
    
    $_return = '{"DropID":null}';
    if($_row = $_result->fetchArray()) $_return =  '{"DropID":"'.$_row["DropID"].'","EventDropID":"'.$_row["EventDropID"].'","ValidFrom":"'.$_row["ValidFrom"].'"}';
    $_db->close();
    return $_return;
}

function claimDrop($_dropID, $_lobbyKey, $_lobbyPlayerID, $_login){
    // get a available drop
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("UPDATE 'Drop' SET CaughtLobbyKey = ?, CaughtLobbyPlayerID = ? WHERE DropID = ? AND ValidFrom < datetime('now') AND CaughtLobbyKey = '' AND CaughtLobbyPlayerID = ''");
    $_sql->bindParam(1, $_lobbyKey);
    $_sql->bindParam(2, $_lobbyPlayerID);
    $_sql->bindParam(3, $_dropID);
    $_result = $_sql->execute();
    
    $_return = "";
    if($_db->changes() >0) {

        $_sql = $_db->prepare("SELECT * FROM 'DROP' WHERE DropID = ?");
        $_sql->bindParam(1, $_dropID);
        $result = $_sql->execute();
        $eventDropID = ($result->fetchArray())["EventDropID"];
        if($eventDropID == 0){
            $_sql = $_db->prepare("UPDATE Members SET Drops = Drops + 1 WHERE Login = ?");
            $_sql->bindParam(1, $_login);
            $_sql->execute();
        }
        else{
            $_sql = $_db->prepare("SELECT * FROM EventCredits WHERE Login = ? AND EventDropID = ?");
            $_sql->bindParam(1, $_login);
            $_sql->bindParam(2, $eventDropID);
            $result = $_sql->execute();
            if($result->fetchArray()){
                $_sql = $_db->prepare("UPDATE EventCredits SET Credit = Credit + 1 WHERE Login = ? AND EventDropID = ?");
                $_sql->bindParam(1, $_login);
                $_sql->bindParam(2, $eventDropID);
                $result = $_sql->execute();
            }
            else{
                $_sql = $_db->prepare("INSERT INTO EventCredits VALUES(?, ?, 1)");
                $_sql->bindParam(1, $_login);
                $_sql->bindParam(2, $eventDropID);
                $_sql->execute();
            }
        }

        $_return = '{"Caught":true}';
    }
    else{
        $_sql = $_db->prepare("SELECT * FROM 'Drop' WHERE DropID = ?");
        $_sql->bindParam(1, $_dropID);
        $_result = $_sql->execute();
        if($_row = $_result->fetchArray()) 
            $_return ='{"Caught":false,"DropID":"'.$_row["DropID"].'","CaughtLobbyPlayerID":"'.$_row["CaughtLobbyPlayerID"].'","CaughtLobbyKey":"'.$_row["CaughtLobbyKey"].'"}';
        else $_return = '{"Caught":false}';
    }
    $_db->close();
    return $_return;
}

// -------------------------------------
//              Table: Sprites
// -------------------------------------

function getEventDrops(){
    // get all eventdrops
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("SELECT * FROM EventDrops LEFT JOIN Events ON EventDrops.EventID = Events.EventID");
    $_result = $_sql->execute();
    
    $_return = array();
    while($_row = $_result->fetchArray()) 
        array_push($_return, 
            array("EventDropID"=>$_row["EventDropID"],"EventID"=>$_row["EventID"],"Name"=>$_row["Name"],"URL"=>$_row["URL"],"EventName"=>$_row["EventName"])
        );
    $_db->close();
    return json_encode($_return);
}
function getPalantirSubmission($_login){
    $_db = new SQlite3('/home/pi/Database/contest.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("Select image FROM Submissions Where login = ?");
    $_sql->bindParam(1, $_login);
    $res = $_sql->execute();
    if($row = $res->fetchArray()) return $row["image"];
    else return false;
}
function addPalantirSubmission($_login, $_image){
    $_db = new SQlite3('/home/pi/Database/contest.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("INSERT INTO Submissions values(?, ?)");
    $_sql->bindParam(1, $_login);
    $_sql->bindParam(2, $_image);
    $res = $_sql->execute();
}
function getAllPalantirSubmissions(){
    $_db = new SQlite3('/home/pi/Database/contest.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("SELECT submissions.image AS sub, COUNT(votes.votelogin) AS votes FROM submissions LEFT JOIN votes ON votes.image = submissions.image GROUP BY sub ORDER BY votes");
    $res = $_sql->execute();
    $images = [];
    while($row = $res->fetchArray()) array_push($images, $row);
    return implode(",", $images);
}

?>

<?php
    // $dbs = glob("/home/pi/Webroot/rippro/userdb/*.db");
    // $sum = count($dbs);
    // $count = 0;
    // $words = [];
    // foreach($dbs as $db){
    //     $count++;
    //     echo "Reading " . $db . " - No. " . $count . " of " . $sum . "\n";
    //     try{
    //         $_db = new SQlite3($db);
    //         $_db->busyTimeout(1000);
    //         $_db->exec('PRAGMA journal_mode = wal;');

    //         $_sql = $_db->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name='Drawings'");
    //         if(($_sql->execute())->fetchArray() === false) throw new Exception("No such table: Drawings");

    //         $_sql = $_db->prepare("SELECT json_extract(meta, '$.name') as word, meta FROM Drawings WHERE json_extract(meta, '$.private') = 0 AND json_extract(meta, '$.language') = 'German' AND json_extract(meta, '$.own') = 0");
    //         $_result = $_sql->execute();
    //         while($_row = $_result->fetchArray()) 
    //             //if(!in_array($_row["word"], $words)) array_push($words, $_row["word"]);
    //             if($_row["word"] == "Vertrauen") echo $_row["meta"];
    //         $_db->close();
    //     }
    //     catch (Exception $e) {
    //         echo 'Exception thrown: ',  $e->getMessage(), "\n";
    //     }
    //     //echo "Total " . count($words) . " words in the list yet\n";
    // }
    // file_put_contents("/home/pi/wordlist.txt", implode(",", $words));
    // echo "Done!";
?>