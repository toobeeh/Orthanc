<?php 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
// disable cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include '/home/pi/Webroot/Orthanc/db.php';
// if request is not from discord preview bot, instant redirect
if(strpos($_SERVER['HTTP_USER_AGENT'], "Discordbot") == false) header("Location: http://typo.rip#guild?invite=" . $_GET["invite"]); 
// else generate card

$palantir = json_decode(getPalantirJSON($_GET["invite"]));
$count = getConnectedCount($palantir->GuildID);
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
<meta property="og:site_name" content="🔮 <?php echo $apiGuild->name ?> is using Palantir">
<meta property="og:title"  content="🥳 Click here to connect <?php echo $apiGuild->name ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://typo.rip#u" />
<meta property="og:image" content="<?php echo "https://cdn.discordapp.com/icons/" . $apiGuild->id . "/" . $apiGuild->icon . ".png"?>" />
<meta property="og:description" content="Add this server to socialize with <?php echo $count ?> other Typo users 🤩" />
<meta name="theme-color" content="#FF00FF">
<!-- <meta name="twitter:card" content="summary_large_image"> -->
</head>
