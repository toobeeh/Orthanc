<?php 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
$guild = $_GET["guild"];
$url = 'https://discordapp.com/api/guilds/' . $guild;

$ch = curl_init();
$token = file_get_contents("/home/pi/palantirToken.txt");
curl_setopt_array($ch, array(
    CURLOPT_URL            => $url, 
    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . $token)
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$response = curl_exec($ch);
$apiGuild = json_decode($response);
curl_close($ch); 
echo print_r($apiGuild);
echo "<img src=\"https://cdn.discordapp.com/icons/" . $apiGuild->id . "/" . $apiGuild->icon . ".png\">" ?>