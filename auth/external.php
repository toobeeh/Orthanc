<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use Xwilarg\Discord\OAuth2;
$secret = trim(file_get_contents("/home/pi/oauth2Secret.txt"));
$oauth2 = new OAuth2("715874397025468417", $secret, "https://tobeh.host/Orthanc/auth/external.php");

if ($oauth2->isRedirected() === false) { // Did the client already logged in ?
    // The parameters can be a combination of the following: connections, email, identity or guilds
    // More information about it here: https://discordapp.com/developers/docs/topics/oauth2#shared-resources-oauth2-scopes
    // The others parameters are not available with this library
    $oauth2->startRedirection(['identify']);
} else {
    // We preload the token to see if everything happened without error
    $ok = $oauth2->loadToken();
    if ($ok !== true) {
        // A common error can be to reload the page because the code returned by Discord would still be present in the URL
        // If this happen, isRedirected will return true and we will come here with an invalid code
        // So if there is a problem, we redirect the user to Discord authentification
        $oauth2->startRedirection(['identify']);
    } else {
        // ---------- USER INFORMATION
        $answer = $oauth2->getUserInformation(); // Same as $oauth2->getCustomInformation('users/@me')
        if (array_key_exists("code", $answer)) {
            exit("An error occured: " . $answer["message"]);
        } else {
            $id = $answer["id"];
            $username = $answer["username"];
            include '/home/pi/Webroot/Orthanc/db.php';
            $login = getMemberLogin($id);
            $token = getAccessTokenByLogin($login);
            if(!$token){
                $token = createAccessToken($login);
            }
        }
        $return = $login ? true : false;
        if(!$return){ // find unique login
            do{
                $login = mt_rand(0,999999);
            }
            while(getMemberJSON($login));
            addMember($login, $username, $id);
        }
    }
}
?>
<html>
    <head>
        <style>
            body{
                background:black;
                display:grid;
                place-items:center;
            }
            .lds-heart {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
            transform: rotate(45deg);
            transform-origin: 40px 40px;
            }
            .lds-heart div {
            top: 32px;
            left: 32px;
            position: absolute;
            width: 32px;
            height: 32px;
            background: #fff;
            animation: lds-heart 1.2s infinite cubic-bezier(0.215, 0.61, 0.355, 1);
            }
            .lds-heart div:after,
            .lds-heart div:before {
            content: " ";
            position: absolute;
            display: block;
            width: 32px;
            height: 32px;
            background: #fff;
            }
            .lds-heart div:before {
            left: -24px;
            border-radius: 50% 0 0 50%;
            }
            .lds-heart div:after {
            top: -24px;
            border-radius: 50% 50% 0 0;
            }
            @keyframes lds-heart {
            0% {
                transform: scale(0.95);
            }
            5% {
                transform: scale(1.1);
            }
            39% {
                transform: scale(0.85);
            }
            45% {
                transform: scale(1);
            }
            60% {
                transform: scale(0.95);
            }
            100% {
                transform: scale(0.9);
            }}

        </style>
        <script>
            const accessToken = "<?php echo $token ?>";
            window.opener?.postMessage(accessToken, "*");
            window.close();
        </script>
    </head>
    <body>
        <div class="lds-heart"><div></div></div>
    </body>
</html>