<?php
include( 'libs/dbfunctions.php' );
$dbobject  = new dbobject();

$d_coded = json_decode( file_get_contents( 'php://input' ), true );

$filename = 'CallBack_Logs';

if ( !file_exists( $filename ) ) {
    mkdir( $filename, 0777, true );
} 

file_put_contents( $filename.'/monnify_Response.txt', json_encode( $d_coded, JSON_PRETTY_PRINT ).PHP_EOL);

$billing_ref = $d_coded['billing_reference'];
$processedDate = date('Y-m-d H:i:s');
$expDate = date('Y-m-d', strtotime(' + 1 years'));
// $table = $dbobject->getitemlabel('tb_payment_confirmation', 'billing_ref', $billing_ref, 't_table');
$sql = "UPDATE tb_payment_confirmation SET trans_status = '1', trans_processed_date = '$processedDate', expiry_date = '$expDate', WHERE billing_ref = '$billing_ref' AND trans_status = '0'";
$exec = $dbobject->db_query($sql, false);

$renewSql = "UPDATE vehicle_sidenumbers SET expiry_date = '$expDate', status = 1, processed_date = '$processedDate' WHERE brn = '$billing_ref'";
$dbobject->db_query($renewSql, false);

if ($exec > 0) {
    $sql1 = "UPDATE transaction_table SET is_processed = '1' WHERE trans_query_id = '$billing_ref' AND is_processed = '0'";
    $dbobject->db_query($sql1, false);
    echo json_encode(array("response_code"=>200,"response_message"=>'Response recieved successfully'));
}else{
    echo json_encode(array("response_code"=>202,"response_message"=>'An Unknown Error Occured'));
}
?>