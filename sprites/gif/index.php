<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sprites</title>
    <link rel="stylesheet" type="text/css" href="/Orthanc/popup.css">
</head>
<body>

    <h1>Listig all available Sprites<h2>

<?php

$gifs = array_diff(scandir("."), array('..', '.'));
foreach($gifs as $gif){
    if($gif != "drop.gif") echo "<div><img src='".$gif."'></div>";
}

?>

</body>
</html>