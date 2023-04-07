<?php

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $url = "https://pastebin.com/raw/{$id}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);

    if ($output) {
        header('Content-Type: text/plain');
        echo $output;
    } else {
        echo "Error: Unable to retrieve pastebin content.";
    }
} else {
    echo "Error: No pastebin ID specified.";
}