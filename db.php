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

// Check if members has row with login
function addMember($_login, $_username, $_id){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('INSERT INTO Members VALUES(?, ?, 0, 0, 0, 0, null, null, null, null)');
    $_sql->bindParam(1, $_login);
    $json = '{"UserID":"' . $_id . '","UserName":"' . $_username . '","UserLogin":"' . $_login . '","Guilds":[]}';
    $_sql->bindParam(2, $json);
    $_result = $_sql->execute();
    $_db->close();
}

// Get login by id
function getMemberLogin($_id){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT Login FROM "Members" WHERE json_extract(Member, "$.UserID") LIKE ?');
    $_sql->bindParam(1, $_id);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = $_row['Login'];
    else $_return = false;
    $_db->close();
    return $_return;
}

// Get login by token
function getMemberLoginByToken($_token){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT Login FROM "AccessTokens" WHERE AccessToken LIKE ? AND CreatedAt > date("now", "-7 day")');
    $_sql->bindParam(1, $_token);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = $_row['Login'];
    else $_return = false;
    $_db->close();
    return $_return;
}

// Get token by login
function getAccessTokenByLogin($login){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare('SELECT AccessToken FROM "AccessTokens" WHERE Login LIKE ? AND CreatedAt > date("now", "-7 day")');
    $_sql->bindParam(1, $login);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) $_return = $_row['AccessToken'];
    else $_return = false;
    $_db->close();
    return $_return;
}

// create a new access token for a login
function createAccessToken($_login){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    do{
        $token = random_str();
    }
    while(getMemberLoginByToken($token));

    $_sql = $_db->prepare("DELETE FROM AccessTokens WHERE Login = ?");
    $_sql->bindParam(1, $_login);
    $_sql->execute();
    
    $_sql = $_db->prepare('INSERT INTO AccessTokens (Login, AccessToken) VALUES (?, ?)');
    $_sql->bindParam(1, $_login);
    $_sql->bindParam(2, $token);
    $_result = $_sql->execute();
    $_db->close();
    return $token;
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
    while($row = $res->fetchArray()) array_push($images, json_encode($row));
    return "[" . implode(",", $images) . "]";
}
function setSubmissionVotes($login, $vote1, $vote2){
    $_db = new SQlite3('/home/pi/Database/contest.db');
    $_db->busyTimeout(1000);
    $_db->exec('PRAGMA journal_mode = wal;');

    $_sql = $_db->prepare("REPLACE INTO Votes VALUES(?,?)");
    $_sql->bindParam(1, $login);
    $_sql->bindParam(2, $vote1);
    $res = $_sql->execute();

    $_sql = $_db->prepare("REPLACE INTO Votes VALUES(?,?)");
    $login = $login . "0";
    $_sql->bindParam(1, $login);
    $_sql->bindParam(2, $vote2);
    $res = $_sql->execute();
}

/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 *
 * This function uses type hints now (PHP 7+ only), but it was originally
 * written for PHP 5 as well.
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

?>