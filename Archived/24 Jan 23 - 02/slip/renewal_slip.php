<?php
ob_end_clean();
include_once('../libs/dbfunctions.php');
include_once('../fpdf/fpdf.php');
$dbobject = new dbobject();
$ip = ['13.41.180.63'];
$REMOTE_IP = $_SERVER['REMOTE_ADDR'];
if (in_array($REMOTE_IP, $ip)) {

}else{
    die("Unauthorized Access: Access Denied");
}

$id  = isset($_REQUEST['pid'])? $_REQUEST['pid'] :'';
$table  = isset($_REQUEST['tbl'])? $_REQUEST['tbl'] :'';
$processed = 1;
$expDate = date('Y-m-d', strtotime(' + 1 years'));
$pending = 0;
$payt_type = str_replace();

$sql1 = "UPDATE table_payment_renewal SET trans_status = '$processed', expiry_date = '$expDate' WHERE portal_id = '$id' AND trans_status = '$pending'";

$res = $dbobject->db_query($sql1, false);
if ($res > 0) {
    $sql2 = "UPDATE $table SET status = '1' expiry_date = '$expDate' WHERE portal_id = '$id'";
    $exec = $dbobject->db_query($sql2, false);
    
} else {
    echo json_encode(array("response_code"=>289,"response_message"=>'An Unknown Error Occured'));
}

$immm = '../img/self_service_printout/self_service.jpg';

// echo $id;
$sql = $dbobject->db_query("SELECT * FROM $table WHERE portal_id = '$id'");
$portal_id = $sql[0]['portal_id'];
$name = $sql[0]['business_name'];
$address = $sql[0]['address'];
$category = $sql[0]['category'];
$date = $sql[0]['created'];
$phone = $sql[0]['phone'];
$tin = $sql[0]['tin'];
$cac = $sql[0]['cac_reg_no'];
$suffix = "";
$payt_type = str_replace('_', ' ', $table);
$payt_type ="$payt_type $suffix";
$payt_type = ucwords($payt_type);
if($table=='driving_sch_form'){
    $name = $sql[0]['business_name'];
}else if($table=='dealership'){
    $name = $sql[0]['owner_name'];
}else if($table=='transport_companies'){
    $name = $sql[0]['business_name'];
}else if($table=='spare_parts'){
    $name = $sql[0]['business_name'];
}else if($table=='mech_garrage'){
    $name = $sql[0]['business_name'];
}


$font_size = 10;
$sqlp = $dbobject->db_query("SELECT * FROM table_payment_renewal WHERE portal_id = '$id'");
$total_amount = $sqlp[0]['trans_amount'];
$now = date("Y-m-d");


$pdf = new FPDF('P','mm',array(210,297));
$object = new dbobject();

$pdf->AddPage('L');
$pdf->Image($immm,1,1,295);

// title
$pdf->SetTitle('Plateau State Ministry Of Transport');
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(5,120,180);

// name
$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(298,-47,$name,0,1,'C');


$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(110,-142,$name,0,1,'C');

// address
$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,1,$address,0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(100,-185,$address,0,1,'C');

// cac
$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,40,$cac,0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(100,-222,$cac,0,1,'C');

// portal ID
$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,75,$portal_id,0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(100,-260,$portal_id,0,1,'C');

// Payment Type

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(335,115,$payt_type,0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(110,-298.5,$payt_type,0,1,'C');


// Amount Paid
$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(163);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,0,number_format($total_amount)." Naira",0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(-4.5);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(110,0,number_format($total_amount)." Naira",0,1,'C');

// TIN
$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(12);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,5,$tin,0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(-6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(110,0,$tin,0,1,'C');

// Phone Number
$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,5,$phone,0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(-6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(110,0,$phone,0,1,'C');

// Date
$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,5,$now,0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(-6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(110,0,$now,0,1,'C');
// return the generated output
$pdf->Output();



?>
