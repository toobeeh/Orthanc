<?php 

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
$url = 'https://discordapp.com/api/users/334048043638849536';

$ch = curl_init();
$token = file_get_contents("/home/pi/palantirToken.txt");
curl_setopt_array($ch, array(
    CURLOPT_URL            => $url, 
    CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . $token)
));
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
$response = curl_exec($ch);
echo curl_error($ch);
curl_close($ch); 
var_dump($response);?>