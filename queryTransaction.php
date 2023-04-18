<?php
include('libs/dbfunctions.php');
$dbobject = new dbobject();

$data = json_decode(file_get_contents('php://input'), true);
$status = $data['status'];
$billingReference = $data['billing_reference'];
$MDAReference = $data['mda_reference'];
$amount = $data['amount'];
$message = $data['message'];
$receipt = $data['receipt'];

$sql_count = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id' AND trans_status = 1";
$result_count = $dbobject->db_query($sql_count);
$count = count($result_count);
if ($count > 0) {
    die("Your Payment has been confirmed and approved. kindly go to the Nearest Office or Registration center to get your receipt");
}
?>