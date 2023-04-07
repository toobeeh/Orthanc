<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include '/home/pi/Webroot/Orthanc/db.php';

$theme = $_POST['theme']; // Get the code from the POST body
$id = createThemeShare($theme);
echo $id;
?>
