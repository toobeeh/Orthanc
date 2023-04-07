<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$api_key = file_get_contents('/home/pi/pastebin_apikey');
$api_url = 'https://pastebin.com/api/api_post.php';

$code = $_POST['theme'];

// Set the parameters for the API request
$params = array(
  'api_dev_key' => $api_key,
  'api_option' => 'paste',
  'api_paste_code' => $code,
);

// Use cURL to make the API request
$curl = curl_init($api_url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

// Display the link to the uploaded code
if (strpos($response, 'https://pastebin.com/') === 0) {
  echo '{link:"' . $response . '"}';
} else {
    die();
}

?>
