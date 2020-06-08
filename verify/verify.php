<?php
    
    // processes a $token number including the ObserveToken and a $member wwhich is compared in the members db
    // outputs a $jsonOutput string including guild information

    include '/home/pi/Webroot/Orthanc/db.php';

    // Authenticate server by token
    if(isset($token) && isset($member)){
        // get matching palantir
        $authentificatedPalantir = getPalantir($token);

        // get matching member
        $member = json_decode($member);
        $authenticatedMember = getMemberJSON($member->UserLogin);

        // any not valid?
        if($authentificatedPalantir === false || $authenticatedMember === false)){
            $jsonOutput = '{"Valid": false, "AuthGuildName": "", "AuthGuildID": "0", "ObserveToken": "'.$token.'","Member":'. $member . '}';
            return;
        }
            
        // parse to object
        $authenticatedMember = json_decode($authenticatedMember);
        $authentificatedPalantir = json_decode($authentificatedPalantir);

        // check if guild is new for member
        $has = false;
        foreach($authenticatedMember->Guilds as $guild){
            if($guild->ID == $authentificatedPalantir->ID) $has = true;
        }

        if($has === false) {
            array_push($authenticatedMember->Guilds, $authentificatedPalantir);
            $newMemberJson = json_encode($authenticatedMember);
            setMemberJSON($authenticatedMember->UserLogin, $newMemberJson);
        }
        else $newMemberJson = json_encode($authenticatedMember);

        $jsonOutput = '{"Valid": true, "AuthGuildName": "'.$authentificatedPalantir->GuildName.'", "AuthGuildID": "'.$authentificatedPalantir->GuildID.'", "ObserveToken": '.$token.', "Member":'.$newMemberJson.'}';
    }
    else $jsonOutput = '{"Valid": false, "AuthGuildName": "", "AuthGuildID": 0, "ObserveToken": "", "Member":null}';

?>