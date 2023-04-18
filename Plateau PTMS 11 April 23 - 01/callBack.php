<?php
include( 'libs/dbfunctions.php' );
$dbobject  = new dbobject();

$d_coded = json_decode( file_get_contents( 'php://input' ), true );

// file_put_contents( 'verify_plate_response_data.txt', file_get_contents( 'php://input' ) . ' \n\n\n' . '//////////////////////////////////////////////////////////////////' . '\n\n\n', FILE_APPEND );

$filename = 'CallBack_Logs';

if ( !file_exists( $filename ) ) {
    mkdir( $filename, 0777, true );
}

file_put_contents( $filename.'/callback_Response.txt', json_encode( $d_coded, JSON_PRETTY_PRINT ).PHP_EOL, FILE_APPEND );

?>