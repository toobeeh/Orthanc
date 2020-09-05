<head><title>PTR Log</title></head>
<body>
<?php

    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    if(!isset($_GET['auth'])||$_GET['auth'] != "supersecret") {
        die("<h2>401: Unauthorized</h2>");
    }
    
    if(isset($_GET['updateptr'])){
        echo "<h2>Plantir Update Result</h2><h3> Updated at:  " . date("Y-m-d H:i:s") . "</h3>";
        echo "> Stopping service... " . shell_exec("sudo service palantir stop 2>&1") . "<br>";
        echo "> Emptying log... " . shell_exec("sudo truncate -s0 /home/pi/palantirOutput.log 2>&1") . "<br>";
        echo "> Pulling from git... " . shell_exec("sudo git -C /home/pi/Palantir/ pull 2>&1") . "<br>";
        echo "> Starting service..." . shell_exec("sudo service palantir start 2>&1") . "<br>";
        echo "<br><a href='/Orthanc/log/?auth=supersecret'><button>Show palantir log</button></a>";
    }
    if(isset($_GET['updateohc'])){
        echo "<h2>Orthanc Update Result</h2><h3> Updated at:  " . date("Y-m-d H:i:s") . "</h3>";
        echo "> Pulling from git... " . shell_exec("sudo git -C /home/pi/Webroot/Orthanc/ pull 2>&1") . "<br>";
        echo "<br><a href='/Orthanc/log/?auth=supersecret'><button>Show palantir log</button></a>";
    }
    else{
        echo "<h2>Plantir Bot Dashboard</h2><h3> Refreshed at:  " . date("Y-m-d H:i:s") 
            . "</h3><a href='/Orthanc/log/?auth=supersecret&updateptr'><button>Pull & Restart Palantir</button></a><br><a href='/Orthanc/log/?auth=supersecret&updateohc'><button>Pull Orthanc</button></a><br><br>";
        $file = file("/home/pi/palantirOutput.log");
        $file = array_reverse($file);
        foreach($file as $f){
            echo $f."<br />";
        }
        echo "<script>setInterval(()=>{if(window.scrollY == 0)location.reload();},2000);</script>";
    }
    
?>
</body>