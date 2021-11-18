<?php $url = 'https://discordapp.com/api/v6/guilds/779435254225698827/members';
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
$ch = curl_init();
$token = file_get_contents("/home/pi/palantirToken.txt");
//echo $token;
curl_setopt_array($ch, array(
    CURLOPT_URL            => $url, 
    CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . $token), 
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_VERBOSE        => 1,
    CURLOPT_SSL_VERIFYPEER => 0
));
$response = curl_exec($ch);
curl_close($ch); 
echo $response;?>