<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$api_dev_key = file_get_contents('/home/pi/pastebin_apikey');
$api_paste_code = $_POST['theme']; // Get the code from the POST body
$api_paste_private = "0"; // 0 = public, 1 = unlisted, 2 = private
$api_paste_name = "Typo Theme Share"; // Use a default name if no name is specified
$api_paste_expire_date = "N"; // N = never, 10M = 10 minutes, 1H = 1 hour, 1D = 1 day, 1W = 1 week, 2W = 2 weeks, 1M = 1 month
$api_paste_format = ""; // Let Pastebin detect the format automatically

$url = "https://pastebin.com/api/api_post.php";

$data = [
    'api_dev_key' => $api_dev_key,
    'api_paste_code' => $api_paste_code,
    'api_paste_private' => $api_paste_private,
    'api_paste_name' => $api_paste_name,
    'api_paste_expire_date' => $api_paste_expire_date,
    'api_paste_format' => $api_paste_format
];

$options = [
    'http' => [
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo "kek" . $result; // This will output the URL of the newly created paste

?>
