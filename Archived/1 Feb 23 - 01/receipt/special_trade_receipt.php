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

$immm = 'special_trade_receipt.png';

$check = $dbobject->db_query("SELECT * FROM $table WHERE portal_id='$id'");
$suffix = "reg";
$payt_type = str_replace('_', ' ', $table);
$payt_type ="$payt_type $suffix";
$payt_type = ucwords($payt_type);

$length = 16;
$lengths = 40;
function custom_echo($x, $length) {
    if (strlen($x) <= $length) {
       return $x;
    }
    return substr($x,0,$length) . '...';
 }
 
 $address = $check[0]['address'];

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

$pay_status = (($check[0]['status']==0)?"UNPAID":"PAID");
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

$pdf->SetFont('Arial', 'B', 20);
$pdf->Ln(40);
$pdf->SetTextColor(10,70,100);
$pdf->SetX(19);
$pdf->Cell(102,0,$pay_status,0,1,'L');

// title
$pdf->SetTitle('Plateau State Ministry Of Transport');
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(5,120,180);

// owners name
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(16);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(104,0,custom_echo($owner_name,$length),0,1,'C');


//address
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(8);
$pdf->SetTextColor(10,70,100);
// $pdf->MultiCell(114,0,$check[0]['address'],0,1,'C');
$pdf->SetX(49);
$pdf->MultiCell(40,5,custom_echo($address,$length),0,'L');

//CAC Reg No
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(7);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(100,0,$check[0]['cac_reg_no'],0,1,'C');

//Portal ID
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(107,0,$check[0]['portal_id'],0,1,'C');

//Payment type
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(7);
$pdf->SetTextColor(10,70,100);
$pdf->SetX(49);
$pdf->MultiCell(40,6,custom_echo($payt_type,$length),0,'L');

//Amount
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(6);
$pdf->SetTextColor(10,70,100);
$pdf->SetX(49);
$pdf->Cell(40,1,custom_echo($money,$length),0,'L');


//TIN
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(7);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(102,7,$check[0]['tin'],0,1,'C');

// Mobile Number
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(5);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(101,1,$check[0]['phone'],0,1,'C');

//Date
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(8);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(103,1,$created,0,1,'C');

$pdf->SetFont('Arial', 'B', 20);
$pdf->Ln(-85);
$pdf->SetTextColor(10,70,100);
$pdf->SetX(120);
$pdf->Cell(102,0,$pay_status,0,1,'L');

// Name right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(15);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,0,$owner_name,0,1,'C');

// Address right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(6);
$pdf->SetTextColor(10,70,100);
// $pdf->Cell(307,0,$check[0]['address'],0,1,'C');
$pdf->SetX(147);
$pdf->MultiCell(72,6,custom_echo($address,$lengths),0,'L');

// CAC Reg No right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(296,0,$check[0]['cac_reg_no'],0,1,'C');

// Portal ID right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(303,0,$check[0]['portal_id'],0,1,'C');

// Payment type right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(8);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(303,1,$payt_type,0,1,'C');

// Amount right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(301,0,$money,0,1,'C');

// TIN right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(299,0,$check[0]['tin'],0,1,'C');

// Phone right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(299,0,$check[0]['phone'],0,1,'C');

// Date right
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(9);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(300,0,$created,0,1,'C');

function RotatedText($x,$y,$txt,$angle,$pdf)
{
    //Text rotated around its origin
   
    $pdf->Rotate($angle,$x,$y);
    $pdf->SetFont('Arial','',60);
    $pdf->Text($x,$y,$txt);
    
    $pdf->Rotate(0);
}
$pdf->Ln(50);
RotatedText(110,185,$created,90,$pdf);

// return the generated output
$pdf->Output();





?>