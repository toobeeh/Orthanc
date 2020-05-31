<?php 
    // path to guild list
    $guildDirectory = '/home/pi/Palantir/';

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
            echo '{"AuthGuildName": "'.$authentificatedPalantir->ServerName.'", "AuthGuildID": "'.$authentificatedPalantir->GuildID.'", "ObserveToken": "'.$authentificatedPalantir->ObserverToken.'"}';
        else echo '{"AuthGuildName": "none", "AuthGuildID": "none", "ObserveToken": "' . $_POST['ObserveToken'] . '"}';
    }
    else echo '{"AuthGuildName": "none", "AuthGuildID": "none", "ObserveToken": "none"}';
?>