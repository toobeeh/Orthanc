<?php 
    /**
     * Upload a image from typo image post to send on discord
     */

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

    $image = isset($_POST['image']) ? $_POST['image'] : false;

    if(!$userLogin) {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
    else if(!$image){
        header("HTTP/1.1 400 Bad Request");
        exit;
    }
    else {
        // generate random image name
        $name = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
        $file = fopen( "/home/pi/Webroot/files/typoPost/" . $name . ".png", 'wb' ); 
        
        // split data uri and write base64 string
        $data = explode( ',', $image );
        fwrite( $file, base64_decode( $data[ 1 ] ) );
        fclose( $file ); 

        // log post in db
        logTypoPost($userLogin, $name);

        $return = "https://tobeh.host/files/typoPost/" . $name . ".png";
    } 

    echo $return;
