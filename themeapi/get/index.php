<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include '/home/pi/Webroot/Orthanc/db.php';

$id = $_GET['id']; 
$theme = getThemeShare($id);
echo $theme;
?>
