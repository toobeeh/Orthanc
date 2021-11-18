<?php $url = 'https://discordapp.com/api/guilds/779435254225698827/members';

$ch = curl_init();
$token = file_get_contents("/home/pi/palantirToken.txt");
$f = fopen('request.txt', 'w');
curl_setopt_array($ch, array(
    CURLOPT_URL            => $url, 
    CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . $token), 
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_VERBOSE        => 1,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_STDERR         => $f,
));
$response = curl_exec($ch);
fclose($f);
curl_close($ch); 
echo $response;?>