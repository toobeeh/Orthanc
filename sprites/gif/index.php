<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sprites</title>
    <link rel="stylesheet" type="text/css" href="/Orthanc/popup.css">
    <style>
        .flexcenter{
                    justify-content: center;
                }
        body{width:100%}

    </style>
</head>
<body>

    <h1>Listing all available Sprites</h1>
    <div class = 'flexcenter flexrow'>
<?php
function endsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

$gifs = array_diff(scandir("."), array('..', '.'));
foreach($gifs as $gif){
    if($gif != "drop.gif" && endsWith($gif,".gif")) echo "<div><img src='".$gif."'><h2>" . str_replace(".gif","",$gif) . "</h2></div>";
}

?>
    </div>
</body>
</html>