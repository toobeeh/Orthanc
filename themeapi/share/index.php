<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$api_dev_key = file_get_contents('/home/pi/pastebin_apikey');
$api_paste_code = $_POST['theme']; // Get the code from the POST body
$api_paste_private 		= '1'; // 0=public 1=unlisted 2=private
$api_paste_name			= 'Typo Theme Post'; // name or title of your paste
$api_paste_expire_date 		= 'N';
$api_paste_format 		= 'json';
$api_paste_name			= urlencode($api_paste_name);
$api_paste_code			= urlencode($api_paste_code);

$url 				= 'https://pastebin.com/api/api_post.php';
$ch 				= curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=paste&api_user_key='.$api_user_key.'&api_paste_private='.$api_paste_private.'&api_paste_name='.$api_paste_name.'&api_paste_expire_date='.$api_paste_expire_date.'&api_paste_format='.$api_paste_format.'&api_dev_key='.$api_dev_key.'&api_paste_code='.$api_paste_code.'');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_NOBODY, 0);

$response  			= curl_exec($ch);
echo $response;
?>
