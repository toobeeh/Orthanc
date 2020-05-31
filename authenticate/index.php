<?php 
    // path to guild list
    $guildDirectory = '/home/pi/JsonShared/';

    // Authenticate server by token
    if(isset($_POST['ObserveToken'])){
        $authenticatedGuilds = file_get_contents($guildDirectory . "palantiri.json");
        $palantiri = json_decode($authenticatedGuilds);

        $authentificatedPalantir;

        foreach($palantiri as $palantir){
            if($palantir->ObserveToken == $_POST['ObserveToken']){
                $authentificatedPalantir = $palantir;
            }
        }

        if(isset($authentificatedPalantir))
            echo '{"Valid": true, "AuthGuildName": "'.$authentificatedPalantir->ServerName.'", "AuthGuildID": '.$authentificatedPalantir->GuildID.', "ObserveToken": "'.$authentificatedPalantir->ObserveToken.'"}';
        else echo '{"Valid": false, "AuthGuildName": "none", "AuthGuildID": 0, "ObserveToken": "' . $_POST['ObserveToken'] . '"}';
    }
    else echo '{"Valid": false, "AuthGuildName": "none", "AuthGuildID": 0, "ObserveToken": "none"}';
?>