<?php 
$image = $_POST["image"];
$name = $_POST["name"];
$rnd = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
$name = $name . $rnd;

echo $name;
$ifp = fopen( $name . ".png", 'wb' ); 

// split the string on commas
// $data[ 0 ] == "data:image/png;base64"
// $data[ 1 ] == <actual base64 string>
$data = explode( ',', $image );

// we could add validation here with ensuring count( $data ) > 1
fwrite( $ifp, base64_decode( $data[ 1 ] ) );

// clean up the file resource
fclose( $ifp ); 