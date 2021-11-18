<?php 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
$guild = $_GET["guild"];

$url = 'https://discordapp.com/api/guilds/' . $guild;
$token = file_get_contents("/home/pi/palantirToken.txt");
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL            => $url, 
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . $token)
));
$response = curl_exec($ch);
$apiGuild = json_decode($response);
curl_close($ch); 
echo $response;
echo "<img src=\"https://cdn.discordapp.com/icons/" . $apiGuild->id . "/" . $apiGuild->icon . ".png\">" ?>