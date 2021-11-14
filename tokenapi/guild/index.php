<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);
    include '/home/pi/Webroot/Orthanc/db.php';

    $return = "";
    $delete = isset($_POST['delete']);
    $get = isset($_POST['get']);
    $post = isset($_POST['post']);
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : false;
    $userLogin = getMemberLoginByToken($accessToken);

    $guildToken = isset($_POST['guildToken']) ? $_POST['guildToken'] : false;
    $guild = getPalantirJSON($guildToken);

    if(!$userLogin || !$guild) {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
    else {
        $memberObj = json_decode(getMemberJSON($userLogin));
        $guildObj = json_decode($guild);
        $memberHasGuild = false;
        foreach($memberObj->Guilds as $connectedGuild){
            if($connectedGuild->GuildID == $guildObj->GuildID) $memberHasGuild = true;
        }

        if($post && !$memberHasGuild){
            array_push($memberObj->Guilds, $guildObj);
            setMemberJSON($userLogin, json_encode($memberObj));
            $return = json_encode($memberObj->Guilds);
        }
        else if($delete && $memberHasGuild){
            $filteredGuilds = [];
            foreach($memberObj->Guilds as $connectedGuild){
                if($connectedGuild->GuildID != $guildObj->GuildID) array_push($filteredGuilds, $connectedGuild);
            }
            $memberObj->Guilds = $filteredGuilds;
            setMemberJSON($userLogin, json_encode($memberObj));
            $return = json_encode($memberObj->Guilds);
        }
    } 

    echo $return;   
?>