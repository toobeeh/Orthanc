<?php 

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




?>