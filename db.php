<?php 
// Functions to access palantir DB (SQLite3)
// statements are prepared to prevent sql injection

// -------------------------------------
//              Table: Members
// -------------------------------------

// Check if members has row with login
function getMemberJSON($_login){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare('SELECT * FROM Members WHERE Login = ?');
    $_sql->bindParam(1, $_login, PDO::PARAM_INT);
    $_result = $_sql->execute();
    if($_row = $sql->fetch()) $_return = $_row['Member'];
    else $_return = false;
    return $_return;
}

// Get Member sprite data
function getFullMemberData($_login){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare('SELECT * FROM Members WHERE Login = ?');
    $_sql->bindParam(1, $_login, PDO::PARAM_INT);
    $_result = $_sql->execute();
    if($_row = $sql->fetch()) $_return = json_encode($_row);
    else $_return = false;
    return $_return;
}

// Check if members has row with login
function addMember($_login, $_username, $_id, $_join){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $palantirJSON = $_join === true ? getPalantirJSON("79177353") : "";

    $_sql = $_db->prepare('INSERT INTO Members VALUES(?, ?, 0, 0, 0, 0, null, null, null, null, "", "")');
    $_sql->bindParam(1, $_login, PDO::PARAM_INT);
    $json = '{"UserID":"' . $_id . '","UserName":"' . $_username . '","UserLogin":"' . $_login . '","Guilds":[' . $palantirJSON . ']}';
    $_sql->bindParam(2, $json, PDO::PARAM_STR);
    $_result = $_sql->execute();
}

// Get login by id
function getMemberLogin($_id){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare('SELECT Login FROM "Members" WHERE json_extract(Member, \'$.UserID\') LIKE ?');
    $id = "%" . $_id . "%";
    $_sql->bindParam(1, $id, PDO::PARAM_STR);
    $_result = $_sql->execute();
    if($_result && $_row = $_sql->fetch()) $_return = $_row['Login'];
    else $_return = false;
    return $_return;
}

// Get login by token
function getMemberLoginByToken($_token){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare('SELECT Login FROM "AccessTokens" WHERE AccessToken = ? AND CreatedAt > DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -356 DAY)');
    $_sql->bindParam(1, $_token, PDO::PARAM_STR);
    $_result = $_sql->execute();
    if($_row = $_sql->fetch()) $_return = $_row['Login'];
    else $_return = false;
    return $_return;
}

// Get token by login
function getAccessTokenByLogin($login){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare('SELECT AccessToken FROM "AccessTokens" WHERE Login = ? AND CreatedAt > DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -356 DAY)');
    $_sql->bindParam(1, $login, PDO::PARAM_INT);
    $_result = $_sql->execute();
    if($_row = $_sql->fetch()) $_return = $_row['AccessToken'];
    else $_return = false;
    return $_return;
}

// create a new access token for a login
function createAccessToken($_login){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    do{
        $token = random_str();
    }
    while(getMemberLoginByToken($token));

    $_sql = $_db->prepare("DELETE FROM AccessTokens WHERE Login = ?");
    $_sql->bindParam(1, $_login, PDO::PARAM_INT);
    $_sql->execute();
    
    $_sql = $_db->prepare('INSERT INTO AccessTokens (Login, AccessToken) VALUES (?, ?)');
    $_sql->bindParam(1, $_login, PDO::PARAM_INT);
    $_sql->bindParam(2, $token, PDO::PARAM_STR);
    $_result = $_sql->execute();
    return $token;
}

// Set member Json (for example to add new guild)
function setMemberJSON($_login, $_json){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare('UPDATE Members SET Member = ? WHERE Login = ?');
    $_sql->bindParam(1, $_json, PDO::PARAM_STR);
    $_sql->bindParam(2, $_login, PDO::PARAM_INT);
    $_result = $_sql->execute();
    return $_result;
}

function getConnectedCount($_guildID){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare('SELECT Count(*) as Count FROM Members WHERE Member LIKE ?');
    $id = "%" . $_guildID . "%";
    $_sql->bindParam(1, $id, PDO::PARAM_STR);
    $_result = $_sql->execute();
    if($_row = $_sql->fetch()) $_return = $_row['Count'];
    else $_return = false;
    return $_return;
}

// -------------------------------------
//              Table: Palantiri
// -------------------------------------

// Get palantir from obersve token
// Table is read-only, as only palantir bot listens to discord commands to modify settings
function getPalantirJSON($_observeToken){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare('SELECT * FROM Palantiri WHERE Token = ?');
    $_sql->bindParam(1, $_observeToken, PDO::PARAM_INT);
    $_result = $_sql->execute();
    if($_row = $_sql->fetch()) $_return = $_row['Palantir'];
    else $_return = false;
    return $_return;
}

// -------------------------------------
//              Table: CONTEST
// -------------------------------------

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


// -------------------------------------
//              Table: Sprites
// -------------------------------------

function getEventDrops(){
    // get all eventdrops
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare("SELECT * FROM EventDrops LEFT JOIN Events ON EventDrops.EventID = Events.EventID");
    $_result = $_sql->execute();
    
    $_return = array();
    while($_row = $_sql->fetch()) 
        array_push($_return, 
            array("EventDropID"=>$_row["EventDropID"],"EventID"=>$_row["EventID"],"Name"=>$_row["Name"],"URL"=>$_row["URL"],"EventName"=>$_row["EventName"])
        );
    $_db->close();
    return json_encode($_return);
}

function getSprites(){
    // get all online sprites
    // sprites are written into the db by palantir. the member table stores the sprites
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    // remove entries older than 10s to avoid big data
    ($_db->prepare("DELETE FROM OnlineSprites WHERE Date < DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -10 SECOND)"))->execute();

    $_sql = $_db->prepare("SELECT * FROM OnlineSprites");
    $_result = $_sql->execute();
    
    $_return = array();
    while($_row = $_sql->fetch()) 
        array_push($_return, 
            array("LobbyKey"=>$_row["LobbyKey"],"LobbyPlayerID"=>$_row["LobbyPlayerID"],"Sprite"=>$_row["Sprite"])
        );

    return json_encode($_return);
}


function getAvailableSprites(){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare("SELECT * FROM Sprites");
    $_result = $_sql->execute();
    
    $_return = array();
    while($_row = $_sql->fetch()) 
        array_push($_return, 
            array("ID"=>$_row["ID"],"Name"=>$_row["Name"],"URL"=>$_row["URL"],"Cost"=>$_row["Cost"],"Special"=>$_row["Special"],"Rainbow"=>$_row["Rainbow"],"EventDropID"=>$_row["EventDropID"],"Artist"=>$_row["Artist"])
        );

    return json_encode($_return);
}

function getScenes(){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare("SELECT * FROM Scenes");
    $_result = $_sql->execute();
    
    $_return = array();
    while($_row = $_sql->fetch()) 
        array_push($_return, 
            array("ID"=>$_row["ID"],"Name"=>$_row["Name"],"URL"=>$_row["URL"],"Artist"=>$_row["Artist"],"Color"=>$_row["Color"], "GuessedColor"=>$_row["GuessedColor"])
        );

    return json_encode($_return);
}

function getSpriteByGifName($_gif){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_result = $_db->query('SELECT * FROM Sprites WHERE URL LIKE ? ');
    $gif = "%" . $_gif . "%";
    $_sql->bindParam(1, $gif);
    
    if($_row = $_sql->fetch())
        $_return = array("ID"=>$_row["ID"],"Name"=>$_row["Name"],"URL"=>$_row["URL"],"Cost"=>$_row["Cost"],"Special"=>$_row["Special"],"EventDropID"=>$_row["EventDropID"]);

    return $_return;
}

// Typoposts db

function logTypoPost($login, $url){
    $_db = new PDO('mysql:host=localhost;dbname=palantir', 'orthanc');

    $_sql = $_db->prepare("INSERT INTO posts VALUES(?,?,?)");
    $_sql->bindParam(1, $url);
    $_sql->bindParam(2, $login);
    $date = date(DATE_RFC2822);
    $_sql->bindParam(3, $date);
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