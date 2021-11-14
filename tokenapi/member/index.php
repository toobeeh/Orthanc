<?php 
    ini_set('display_errors', 1); 
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);
    include '/home/pi/Webroot/Orthanc/db.php';

    $return = "";
    $delete = isset($_POST['delete']);
    $get = isset($_POST['get']);
    $post = isset($_POST['post']);
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : false;
    $userLogin = getMemberLoginByToken($accessToken);

    if(!$userLogin) {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
    else if($get){
        $user = getFullMemberData($userLogin);
        $member = json_decode($user);
        $return = json_encode(array(
            'UserName' => $member->UserName,
            'UserID' => $member->UserID,
            'Guilds' => $member->Guilds,
            'Bubbles' => $user->Bubbles
        ), JSON_FORCE_OBJECT);
    }

    echo $return;   
?>