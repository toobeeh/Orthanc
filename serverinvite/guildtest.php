<?php $url = 'https://discordapp.com/api/users/334048043638849536';

$ch = curl_init();
$token = file_get_contents("/home/pi/palantirToken.txt")
curl_setopt_array($ch, array(
    CURLOPT_URL            => $url, 
    CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . $token)
));
$response = curl_exec($ch);
curl_close($ch); 
var_dump($response);?>