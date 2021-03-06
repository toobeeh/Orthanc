<?php
    
    // processes a $token number including the ObserveToken and a $member wwhich is compared in the members db
    // outputs a $jsonOutput string including guild information

    include '/home/pi/Webroot/Orthanc/db.php';

    // Authenticate server by token
    if(isset($token) && isset($member)){
        // get matching palantir
        $authentificatedPalantir = getPalantirJSON($token);

        // get matching member
        $authenticatedMember = getMemberJSON((json_decode($member))->UserLogin);

        // any not valid?
        if($authentificatedPalantir === false || $authenticatedMember === false){
            $jsonOutput = '{"Valid": false, "AuthGuildName": "", "AuthGuildID": "0", "ObserveToken": "'.$token.'","Member":'. $member . '}';
            return;
        }

        if(!isset($remove)) $remove = false;
            
        // parse to object
        $authenticatedMember = json_decode($authenticatedMember);
        $authentificatedPalantir = json_decode($authentificatedPalantir);

        // check if guild is new for member
        $has = false;
        foreach($authenticatedMember->Guilds as $guild){
            if($guild->GuildID == $authentificatedPalantir->GuildID) $has = true;
        }

        if($has === false  && $remove === false) {
            array_push($authenticatedMember->Guilds, $authentificatedPalantir);
            $newMemberJson = json_encode($authenticatedMember);
            setMemberJSON($authenticatedMember->UserLogin, $newMemberJson);
        }
        else if($has === true  && $remove === true) {
            $newGuilds = [];
            foreach($authenticatedMember->Guilds as $guild){
                if($guild->GuildID != $authentificatedPalantir->GuildID) array_push($newGuilds, $guild);
            }
            $authenticatedMember->Guilds = $newGuilds;
            $newMemberJson = json_encode($authenticatedMember);
            setMemberJSON($authenticatedMember->UserLogin, $newMemberJson);
        }
        else $newMemberJson = json_encode($authenticatedMember);

        $jsonOutput = '{"Valid": true, "AuthGuildName": "'.$authentificatedPalantir->GuildName.'", "AuthGuildID": "'.$authentificatedPalantir->GuildID.'", "ObserveToken": "'.$token.'", "Member":'.$newMemberJson.'}';
    }
    else $jsonOutput = '{"Valid": false, "AuthGuildName": "", "AuthGuildID": 0, "ObserveToken": "", "Member":null}';

?>