<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use Xwilarg\Discord\OAuth2;
$secret = trim(file_get_contents("/home/pi/oauth2Secret.txt"));
$oauth2 = new OAuth2("715874397025468417", $secret, "https://tobeh.host/Orthanc/auth");

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
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://tobeh.host/profile-contest/style.css">
    </head>
    <body>
        <div id="maincontent">
            <h1><?php echo $return ? "Welcome back, " . $username : "Hello there, " . $username; ?> </h1>
            <div class="roundbtn" style="font-size:0.8em" id="viewinfo">
                <?php if($return): ?>
                <br>
                Found your palantir account!<br><br>
                No worries, your Discord authorization is not used for any other purposes than the login.<br><br>
                <?php else: ?>
                    <br>A new account was created!<br><br>
                    By proceeding, you will be logged in on skribbl.<br><br>
                    If you do so, you agree to the <a target="_blank" href="https://typo.rip/privacy"> Data Usage </a> of Palantir.<br><br>
                <?php endif; ?>
            </div>
        </div>
        <div class="wobblebox" id="submitInteraction" 
            onclick="window.location.href = '<?php echo  "https://skribbl.io/?login=" . $login ?>'">
            <h2><span>Proceed</span><span style="font-size: .5em;">
                <?php echo $login ? "and log in on skribbl" : "create account & log in on skribbl"; ?> 
            </span></h2>
            <svg viewBox="0 0 142.91 68.86">
                <path d=""></path>
            </svg>
        </div>
    </body>
</html>