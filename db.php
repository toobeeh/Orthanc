<?php 
// Functions to access palantir DB (SQLite3)
// statements are prepared to prevent sql injection

// -------------------------------------
//              Table: Members
// -------------------------------------

// Check if members have row with login
function getMemberJSON($_login){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('SELECT * FROM Members WHERE Login = ?');
    $_sql->bindParam(1, $_login);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) return $_row['Member'];
    else return false;
}

// Set member Json (for example to add new guild)
function setMemberJSON($_login, $_json){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('UPDATE Members SET Member = ? WHERE Login = ?');
    $_sql->bindParam(1, $_json);
    $_sql->bindParam(2, $_login);
    $_result = $_sql->execute();
    return $_result;
}

// -------------------------------------
//              Table: GuildLobbies
// -------------------------------------

// Get guild lobbies for a guild token
// Table is read-only, as only palantir bot collects lobby data and generates a guild lobby array
function getGuildLobbiesJSON($_guildID){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('SELECT * FROM GuildLobbies WHERE GuildID = ?');
    $_sql->bindParam(1, $_guildID);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) return $_row['Lobbies'];
    else return false;
}

// -------------------------------------
//              Table: Palantiri
// -------------------------------------

// Get palantir from obersve token
// Table is read-only, as only palantir bot listens to discord commands to modify settings
function getPalantirJSON($_observeToken){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('SELECT * FROM Palantiri WHERE Token = ?');
    $_sql->bindParam(1, $_observeToken);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) return $_row['Palantir'];
    else return false;
}

// -------------------------------------
//              Table: Lobbies
// -------------------------------------

// Find a lobby by a lobby key
function getLobbyJSONByKey($_lobbyKey){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('SELECT * FROM Lobbies WHERE Lobby LIKE ?');
    $_lobbyKey = "%" . $_lobbyKey . "%";
    $_sql->bindParam(1, $_lobbyKey);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) return $_row['Lobby'];
    else return false;
}

// Find a lobby by a lobby id
function getLobbyJSONByID($_lobbyID){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('SELECT * FROM Lobbies WHERE LobbyID = ?');
    $_sql->bindParam(1, $_lobbyID);
    $_result = $_sql->execute();
    if($_row = $_result->fetchArray()) return $_row['Lobby'];
    else return false;
}

// update the lobby data for a lobby id
function updateLobbyJSON($_lobbyID, $_lobbyJson){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('UPDATE Lobbies SET Lobby = ? WHERE LobbyID = ?');
    $_sql->bindParam(1, $_lobbyJson);
    $_sql->bindParam(2, $_lobbyID);
    $_result = $_sql->execute();
    return $_result;
}

// add a new lobby
function addLobby($_lobbyID, $_lobbyJson){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('INSERT INTO Lobbies VALUES (?, ?)');
    $_sql->bindParam(1, $_lobbyID);
    $_sql->bindParam(2, $_lobbyJson);
    $_result = $_sql->execute();
    return $_result;
}

// -------------------------------------
//              Table: Reports
// -------------------------------------

function writeReport($_lobbyID, $_reportJson){
    $_db = new SQlite3('/home/pi/Database/palantir.db');
    $_sql = $_db->prepare('REPLACE INTO Reports VALUES(?, ?)');
    $_sql->bindParam(1, $_lobbyID);
    $_sql->bindParam(2, $_reportJson);
    $_result = $_sql->execute();
    return $_result;
}


?>