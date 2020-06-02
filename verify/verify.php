<?php
    
    // processes a $token number including the ObserveToken
    // outputs a $jsonOutput string including guild information

    // path to guild list
    $guildDirectory = '/home/pi/JsonShared/';

    // Authenticate server by token
    if(isset($token)){
        $authenticatedGuilds = file_get_contents($guildDirectory . "palantiri.json");
        $palantiri = json_decode($authenticatedGuilds);

        $authentificatedPalantir;

        foreach($palantiri as $palantir){
            if($palantir->ObserveToken == $token){
                $authentificatedPalantir = $palantir;
            }
        }

        if(isset($authentificatedPalantir))
            $jsonOutput = '{"Valid": true, "AuthGuildName": "'.$authentificatedPalantir->ServerName.'", "AuthGuildID": "'.$authentificatedPalantir->GuildID.'", "ObserveToken": "'.$authentificatedPalantir->ObserveToken.'"}';
        else $jsonOutput = '{"Valid": false, "AuthGuildName": "", "AuthGuildID": 0, "ObserveToken": "'.$token.'"}';
    }
    else $jsonOutput = '{"Valid": false, "AuthGuildName": "", "AuthGuildID": 0, "ObserveToken": ""}';

?>