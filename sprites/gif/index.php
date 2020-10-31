<?php 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
include '/home/pi/Webroot/Orthanc/db.php';
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sprites</title>
    <link rel="stylesheet" type="text/css" href="/Orthanc/popup.css">
    <link rel="shortcut icon" type="image/x-icon" href="/Orthanc/favicon.ico">
    <style>
        .flexcenter{
                    justify-content: center;
                    flex-wrap:wrap;
                }
        body{width:calc(100% - 4em);
        }

        .sprite{
            text-align:center;
            margin:1.5em;
            padding:2em;
            border-radius:.5em;
            border: 2px solid #7289da;
        }

        a{
            text-decoration:none;
            color:white;
        }

    </style>
</head>
<body style="image-rendering:pixelated">
<h1>
        <a href="/Orthanc/typo/"><button type="button" class="skribbl" id="">Add Typo</button></a>
        <a href="/Orthanc/"><button type="button " class="skribbl active" id="">Manual</button></a>
        <a href="/Orthanc/bot/"><button type="button" class="skribbl" id="">Admin</button></a>
        <a href="https://github.com/toobeeh/"><button type="button" class="skribbl" id="">GitHub</button></a>
    </h1>
    <br>
    <div class="flexcenter flexrow">
        <a href="/Orthanc/"><button type="button" class="skribbl" id="">Typo</button></a>
        <a href="/Orthanc/sprites/gif/"><button type="button" class="skribbl active" id="">Sprites</button></a>
        <a href="/Orthanc/sprites/cabin/"><button type="button " class="skribbl" id="">Cabin</button></a>
    </div>
    <h2>Click a Sprite to try it out!</h2>
    <div class = 'flexcenter flexrow'>
    <a href= <?php echo isset($_GET["price"]) ? "/Orthanc/sprites/gif/" : "/Orthanc/sprites/gif/?price"; ?> >
    <button>Order by <?php echo isset($_GET["price"]) ? " ID" : " price"; ?> </button>
    </a>
    </div>
    <div class = 'flexcenter flexrow'>
<?php

function endsWith($haystack, $needle) {
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}

$gifs = array_diff(scandir("."), array('..', '.'));
foreach($gifs as $gif){
    if($gif != "drop.gif" && endsWith($gif,".gif")) {
        $sprite = getSpriteByGifName($gif);
        echo "<div class='sprite flexcol flexcenter' style='order:" 
            . (isset($_GET["price"]) ? $sprite['Cost'] : $sprite['ID']) ."'><a href='/Orthanc/sprites/cabin/?sprite=" 
            . $sprite['ID'] . "'><h2>"."#". $sprite['ID'] 
            ."</h2> <img style='width:100%' src='".$gif."'><h2>" 
            . $sprite['Name'] . "</h2><h3>Costs: "
            . $sprite['Cost'] . " Bubbles</h2>" . "</a></div>";
    }
}

?>
    </div>
</body>
</html>