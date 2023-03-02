<?php
ob_end_clean();
include_once('../libs/dbfunctions.php');
include_once('../fpdf/fpdf.php');
$dbobject = new dbobject();

$id  = isset($_REQUEST['pid'])? $_REQUEST['pid'] :'';
$table  = isset($_REQUEST['table'])? $_REQUEST['table'] :'';

// if($table=='dealership'){
//     $immm = '../img/certificates/Dealership Certificate.jpeg';
// }else{
//     $immm = '../img/certificates/Auto Mech and Tech Certificate.jpeg';
// }

$immm = 'image/special_trade_receipt.jpg';

$check = $dbobject->db_query("SELECT * FROM $table WHERE portal_id='$id'");
$suffix = "reg";
$payt_type = str_replace('_', ' ', $table);
$payt_type ="$payt_type $suffix";
$payt_type = ucwords($payt_type);

$amount = $dbobject->getitemlabel('payment_category', 'id', $check[0]['item_code'], 'amount');
$amount = number_format($amount,2);

$status = (($check[0]['processed_date']==0)?"Payment Pending":"$amount $status_suffix");
if($table=='driving_sch_form'){
    $owner_name = $check[0]['school_name'];
    $cur_date = $check[0]['record_date'];
}else if($table=='dealership'){
    $owner_name = $check[0]['owner_name'];
    $cur_date = $check[0]['created'];
}else if($table=='transport_companies'){
    $owner_name = $check[0]['owner_name'];
    $cur_date = $check[0]['created'];
}else if($table=='spare_parts'){
    $owner_name = $check[0]['owner_name'];
    $cur_date = $check[0]['created'];
}else if($table=='mech_garrage'){
    $owner_name = $check[0]['owner_name'];
    $cur_date = $check[0]['created'];
}

$new = explode(" ",$cur_date);
$ddd = date("M jS, Y", strtotime($new[0]));
$new = explode(" ",$ddd);
$month =  $new[0];
$date =  $new[1];
$year = $new[2];
$created = "$date $month $year";
$status_suffix = "Naira";
$money = "$amount $status_suffix";

// $month_year = "$month $year";

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

// owners name
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(56);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(112,0,$owner_name,0,1,'C');

//address
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(86,0,$check[0]['address'],0,1,'C');

//CAC Reg No
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(60,0,$check[0]['cac_reg_no'],0,1,'C');

//Portal ID
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(89,0,$check[0]['portal_id'],0,1,'C');

//Payment type
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(102,0,$payt_type,0,1,'C');

//Amount
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(108,0,($check[0]['status']==0)?"$money (unpaid)":$money,0,1,'C');

//TIN
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(59,0,$check[0]['tin'],0,1,'C');

// Mobile Number
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(100,0,$check[0]['phone'],0,1,'C');

//Amount
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(90,0,$created,0,1,'C');

// Name right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(-70);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(305,0,$owner_name,0,1,'C');

// Address right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(287,0,$check[0]['address'],0,1,'C');

// CAC Reg No right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(261,0,$check[0]['cac_reg_no'],0,1,'C');

// Portal ID right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(288,0,$check[0]['portal_id'],0,1,'C');

// Payment type right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(8);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(303,0,$payt_type,0,1,'C');

// Amount right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(310,0,($check[0]['status']==0)?"$money (unpaid)":$money,0,1,'C');

// TIN right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(260,0,$check[0]['tin'],0,1,'C');

// Phone right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(301,0,$check[0]['phone'],0,1,'C');

// Date right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(290,0,$created,0,1,'C');

function RotatedText($x,$y,$txt,$angle,$pdf)
{
    //Text rotated around its origin
   
    $pdf->Rotate($angle,$x,$y);
    $pdf->SetFont('Arial','',60);
    $pdf->Text($x,$y,$txt);
    
    $pdf->Rotate(0);
}
$pdf->Ln(60);
RotatedText(110,190,$created,90,$pdf);

// return the generated output
$pdf->Output();





?>