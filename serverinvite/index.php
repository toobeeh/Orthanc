<?php 

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
include '/home/pi/Webroot/Orthanc/db.php';
$palantir = json_decode(getPalantirJSON($_GET["token"]));
if(false == strpos($_SERVER['HTTP_USER_AGENT'], "Discordbot")) header("Location: http://typo.rip#u"); 
?>
    <!DOCTYPE html>
<html>
<head>
<title>Connect Server to Palantir</title>
<meta charset="UTF-8">
<meta property="og:title"  content="🔮 <?php echo $palantir->GuildName ?> uses Palantir" />
<meta property="og:type" content="website" />
<meta property="og:url" content="https://typo.rip#u" />
<meta property="og:image" content="http://my.site.com/images/thumb.png" />
<meta property="og:description" content="🔗 Connect your account to <?php echo $palantir->GuildName ?> by clicking the link" />
<meta name="theme-color" content="#FF0000">
<meta name="twitter:card" content="summary_large_image">
</head>
