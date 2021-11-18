<?php $guild = $_GET["guild"];
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
include __DIR__.'/vendor/autoload.php';
use RestCord\DiscordClient;

$token = file_get_contents("/home/pi/palantirToken.txt");

$discord = new DiscordClient(['token' => $token]); 

var_dump($discord->guild->getGuild(['guild.id' => 715874397025468417]));
?>