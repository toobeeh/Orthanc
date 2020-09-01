<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sprites</title>
    <link rel="stylesheet" type="text/css" href="/Orthanc/popup.css">
    <style>
        .flexcenter{
                    justify-content: center;
                    flex-wrap:wrap;
                }
        body{width:100%;
            padding:2em;
        }

        .sprite{
            margin:1.5em;
            padding:2em;
            border-radius:.5em;
            border: 2px solid #7289da;
        }

    </style>
</head>
<body>

    <h1>Listing all available Sprites</h1>
    <div class = 'flexcenter flexrow'>
<?php


ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
include '/home/pi/Webroot/Orthanc/db.php';

function endsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

$gifs = array_diff(scandir("."), array('..', '.'));
foreach($gifs as $gif){
    if($gif != "drop.gif" && endsWith($gif,".gif")) {
        $sprite = getSpriteByGifName($gif);
        echo "<div class='sprite flexcol flexcenter'><h2>"."#". $sprite['ID'] ."</h2><img style='width:100%' src='".$gif."'><h2>" . $sprite['Name'] . "</h2></div>";
    }
}

?>
    </div>
</body>
</html>