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
            array("ID"=>$_row["ID"],"Name"=>$_row["Name"],"URL"=>$_row["URL"],"Cost"=>$_row["Cost"],"Special"=>$_row["Special"])
        );

    $_db->close();
    return json_encode($_return);
}

// -------------------------------------
//              Table: Sprites
// -------------------------------------

function getNextDrop(){
    // get a available drop
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    // remove entries older than 1h to avoid big data
    ($_db->prepare("DELETE FROM OnlineSprites WHERE Date < datetime('now', '-3600 seconds')"))->execute();

    $_sql = $_db->prepare("SELECT * FROM 'Drop' WHERE CaughtLobbyKey = ''");
    $_result = $_sql->execute();
    
    $_return = "";
    if($_row = $_result->fetchArray()) $_return =  '{"DropID":"'.$_row["DropID"].'","ValidFrom":"'.$_row["ValidFrom"].'"}';
    $_db->close();
    return json_encode($_return);
}

?>