<?php
ob_end_clean();
include_once('../libs/dbfunctions.php');
include_once('../fpdf/fpdf.php');
$dbobject = new dbobject();

$id  = isset($_REQUEST['id'])? $_REQUEST['id'] :'';
$table  = isset($_REQUEST['table'])? $_REQUEST['table'] :'';

$immm = '../img/self_service_printout/self_service.jpg';

$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
// echo $id;
$sql = $dbobject->db_query("SELECT * FROM spare_parts WHERE portal_id = '$id'");

$portal_id = $sql[0]['portal_id'];
$name = $sql[0]['business_name'];
$address = $sql[0]['address'];
$category = $sql[0]['category'];
$date = $sql[0]['created'];
$phone = $sql[0]['phone'];
$tin = $sql[0]['tin'];
$cac = $sql[0]['cac_reg_no'];

$font_size = 10;
$sqlp = $dbobject->db_query("SELECT * FROM tb_payment_confirmation WHERE payment_code = '$portal_id'");
// var_dump($cac);
$total_amount = $sqlp[0]['trans_amount'];
$now = date("Y-m-d");
// $day = date('d');
// $month_year = date('M,Y');

// $pdf = new FPDF();
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
$pdf->Cell(335,115,"Spare Parts Dealership Registration",0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(110,-298.5,"Spare Parts Dealership",0,1,'C');

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
