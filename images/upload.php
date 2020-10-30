<?php 
$image = $_POST["image"];
$name = $_POST["name"];
$name = $name . microtime();

echo $name;
$ifp = fopen( "img.png", 'wb' ); 

// split the string on commas
// $data[ 0 ] == "data:image/png;base64"
// $data[ 1 ] == <actual base64 string>
$data = explode( ',', $name );

// we could add validation here with ensuring count( $data ) > 1
fwrite( $ifp, base64_decode( $data[ 1 ] ) );

// clean up the file resource
fclose( $ifp ); 