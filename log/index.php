<head><title>PTR Log</title></head>
<body>
<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(!isset($_GET['auth'])||$_GET['auth'] != "supersecret") {
        die("<h2>401: Unauthorized</h2>");
    }
    
    if(!isset($_GET['update'])){
        echo "<h2>Plantir Bot Log</h2><h3> Refreshed at:  " . date("Y-m-d H:i:s") . "</h3><a href='/Orthanc/log/?auth=supersecret&update'><button>Pull & Restart PTR</button></a><br>";
        $file = file("/home/pi/palantirOutput.log");
        $file = array_reverse($file);
        foreach($file as $f){
            echo $f."<br />";
        }
        echo "<script>setInterval(()=>{if(window.scrollY == 0)location.reload();},2000);</script>";
    }
    else{
        echo "> " . shell_exec("service palantir stop") . "<br>";
        echo "> " . shell_exec(">/home/pi/palantirOutput.log") . "<br>";
        echo "> " . shell_exec("git -C /home/pi/Palantir/ pull") . "<br>";
        echo "> " . shell_exec("service palantir start") . "<br>";
        echo "<a href='/Orthanc/log/?auth=supersecret'><button>Show log</button></a>";
    }
    
?>
</body>