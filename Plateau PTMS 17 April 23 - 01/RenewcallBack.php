<?php
include( 'libs/dbfunctions.php' );
$dbobject  = new dbobject();

$ip = ['13.41.180.63','41.242.60.178','65.61.149.231','192.168.80.72','192.168.1.194'];
$REMOTE_IP = $_SERVER['REMOTE_ADDR'];
if (in_array($REMOTE_IP, $ip)) {

}else{
    file_put_contents('deniedIPS.txt', json_encode('Accessed @ '.date('Y-m-d H:i:s').' '.$REMOTE_IP, JSON_PRETTY_PRINT) . PHP_EOL);
    die("Unauthorized Access: Access Denied ".$REMOTE_IP);
}


$d_coded = json_decode( file_get_contents( 'php://input' ), true );
$rawResp = file_get_contents( 'php://input' );
$filename = 'CallBack_Logs';

if ( !file_exists( $filename ) ) {
    mkdir( $filename, 0777, true );
} 

file_put_contents( $filename.'/monnify_Response.txt', json_encode( $d_coded, JSON_PRETTY_PRINT ).PHP_EOL);

$billing_ref = $d_coded['billing_reference'];
$processedDate = date('Y-m-d H:i:s');
$expDate = date('Y-m-d', strtotime(' + 1 years'));
$table = $dbobject->getitemlabel('table_payment_renewal', 'billing_ref', $billing_ref, 't_table');
$PID = $dbobject->getitemlabel('table_payment_renewal', 'billing_ref', $billing_ref, 'payment_code');
$sql = "UPDATE table_payment_renewal SET trans_status = '1', trans_processed_date = '$processedDate', expiry_date = '$expDate', WHERE billing_ref = '$billing_ref' AND trans_status = '0'";
$exec = $dbobject->db_query($sql, false);
if ($exec > 0) {
    $sql1 = "UPDATE transaction_table SET is_processed = '1', response_message = '$rawResp' WHERE trans_query_id = '$billing_ref' AND is_processed = '0'";
    $dbobject->db_query($sql1, false);

    $sql2 = "UPDATE $table SET processed_date = '$processedDate', status = '1', expiry_date = '$expDate' WHERE portal_id = '$PID'";
    $dbobject->db_query($sql2, false);

    echo json_encode(array("response_code"=>200,"response_message"=>'Response recieved successfully'));
}else{
    echo json_encode(array("response_code"=>202,"response_message"=>'An Unknown Error Occured'));
}
?>