<?php
    
    // processes a $token number including the ObserveToken
    // outputs a $jsonOutput string including guild information

    // path to guild list
    $guildDirectory = '/home/pi/JsonShared/';

    

    // Authenticate server by token
    if(isset($token) && isset($member)){
        $authenticatedGuilds = file_get_contents($guildDirectory . "palantiri.json");
        $palantiri = json_decode($authenticatedGuilds);
        $members = json_decode(file_get_contents($guildDirectory . 'members.json'));
        $sender = json_decode($member);

        $authentificatedPalantir;
        foreach($palantiri as $palantir){
            if($palantir->ObserveToken == $token){
                $authentificatedPalantir = $palantir;
            }
        }

        $authenticatedMember;
        foreach($members as $savedMember){
            if ($savedMember->UserID == $sender->UserID && $savedMember->UserLogin == $sender->UserLogin) $authenticatedMember = $savedMember;
        }

        if(!isset($authentificatedPalantir) || !isset($authenticatedMember)){
            $jsonOutput = '{"Valid": false, "AuthGuildName": "", "AuthGuildID": "0", "ObserveToken": "'.$token.'","Member":null}';
            return;
        }
            
        foreach($members as $savedMember){
            if ($savedMember == $authenticatedMember) {
                $unique = true;
                foreach($savedMember->Guilds as $guild){
                    if($guild == $authentificatedPalantir) $unique = false;
                }
                if($unique) array_push($savedMember->Guilds, $authentificatedPalantir); 
                $authenticatedMember = $savedMember;
            }
        }
        
        file_put_contents($guildDirectory . 'members.json', json_encode($members));
        $jsonOutput = '{"Valid": true, "AuthGuildName": "'.$authentificatedPalantir->GuildName.'", "AuthGuildID": '.$authentificatedPalantir->GuildID.', "ObserveToken": '.$token.', "Member":'.json_encode($authenticatedMember).'}';
    }
    else $jsonOutput = '{"Valid": false, "AuthGuildName": "", "AuthGuildID": 0, "ObserveToken": "", "Member":null}';

?>