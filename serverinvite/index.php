<?php 

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
include '/home/pi/Webroot/Orthanc/db.php';
// if request is not from discord preview bot, instant redirect
//if(strpos($_SERVER['HTTP_USER_AGENT'], "Discordbot") == false) header("Location: http://typo.rip#u"); 
// else generate card

$palantir = json_decode(getPalantirJSON($_GET["invite"]));
$url = 'https://discordapp.com/api/guilds/' . $palantir->GuildID;
$token = file_get_contents("/home/pi/palantirToken.txt");
// get guild from api
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL            => $url, 
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . $token)
));
$response = curl_exec($ch);
$apiGuild = json_decode($response);
?>
<!DOCTYPE html>
<html>
<head>
<title>Connect Server to Palantir</title>
<meta charset="UTF-8">
<meta property="og:site_name" content="typo.rip ⚰ Typo &amp; Palantir">
<meta property="og:title"  content="🔮 <?php echo $apiGuild->name ?> is using Palantir" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://typo.rip#u" />
<meta property="og:image" content="<?php echo "https://cdn.discordapp.com/icons/" . $apiGuild->id . "/" . $apiGuild->icon . ".png"?>" />
<meta property="og:description" content="🔗 Click the link to add <?php echo $apiGuild->name ?>" />
<meta name="theme-color" content="#FF00FF">
<!-- <meta name="twitter:card" content="summary_large_image"> -->
</head>
