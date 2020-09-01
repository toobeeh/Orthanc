<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sprites</title>
    <link rel="stylesheet" type="text/css" href="/popup.css">
</head>
<body>

    <h1>Listig all available Sprites<h2>

<?php

$gifs = scandir(".");
foreach($gifs as $gif){
    echo "<div><img src='".$gif."'></div>";
}

?>

</body>
</html>