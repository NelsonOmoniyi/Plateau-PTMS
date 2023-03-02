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

$immm = 'receipt.png';

$check = $dbobject->db_query("SELECT * FROM $table WHERE portal_id='$id'");
$suffix = "reg";
$payt_type = str_replace('_', ' ', $table);
$payt_type ="$payt_type $suffix";
$payt_type = ucwords($payt_type);

$length = 24;
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
    $cur_date = $check[0]['processed_date'];
}else if($table=='dealership'){
    $owner_name = $check[0]['owner_name'];
    $cur_date = $check[0]['processed_date'];
}else if($table=='transport_companies'){
    $owner_name = $check[0]['owner_name'];
    $cur_date = $check[0]['processed_date'];
}else if($table=='spare_parts'){
    $owner_name = $check[0]['owner_name'];
    $cur_date = $check[0]['processed_date'];
}else if($table=='mech_garrage'){
    $owner_name = $check[0]['owner_name'];
    $cur_date = $check[0]['processed_date'];
}

if($cur_date == ""){
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
$border = "0";

// $month_year = "$month $year";

// $day = date('d');
// $month_year = date('M,Y');

// $pdf = new FPDF();
$pdf = new FPDF('P','mm',array(210,297));
$object = new dbobject();

$pdf->AddPage('L');
$pdf->Image($immm,1,1,295);


$pdf->Ln(25);

$receipt_status = ((check[0]['status']==0)?"Invoice":"Receipt");
// title
$pdf->SetTitle('Plateau State Ministry Of Transport '.$receipt_status);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(10,70,100);

$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(30,10,$pay_status,$border);
$pdf->Cell(80,10,'',$border);
$pdf->Cell(30,10,$pay_status,$border);
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,10,'Owners Name:',$border);
$pdf->Cell(30,10,$owner_name,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Owners Name:',$border);
$pdf->Cell(30,10,$owner_name,$border);

$pdf->Ln();
$pdf->Cell(30,10,'Address:',$border);
$pdf->Cell(30,10,custom_echo($address,$length),$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Address:',$border);
$pdf->Cell(30,10,custom_echo($address,$lengths),$border);

$pdf->Ln();
$pdf->Cell(30,10,'CAC:',$border);
$pdf->Cell(30,10,$check[0]['cac_reg_no'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'CAC:',$border);
$pdf->Cell(30,10,$check[0]['cac_reg_no'],$border);
$pdf->Ln();
$pdf->Cell(30,10,'Portal ID:',$border);
$pdf->Cell(30,10,$check[0]['portal_id'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Portal ID:',$border);
$pdf->Cell(30,10,$check[0]['portal_id'],$border);

$pdf->Ln();
$pdf->Cell(30,10,'Payment Type:',$border);
$pdf->Cell(30,10,custom_echo($payt_type,$length),$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Payment Type:',$border);
$pdf->Cell(30,10,custom_echo($payt_type,$length),$border);
$pdf->Ln();
$pdf->Cell(30,10,'Amount Paid:',$border);
$pdf->Cell(30,10,custom_echo($money,$length),$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Amount Paid:',$border);
$pdf->Cell(30,10,custom_echo($money,$length),$border);
$pdf->Ln();
$pdf->Cell(30,10,'TIN:',$border);
$pdf->Cell(30,10,$check[0]['tin'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'TIN:',$border);
$pdf->Cell(30,10,$check[0]['tin'],$border);
$pdf->Ln();
$pdf->Cell(30,10,'Phone Number:',$border);
$pdf->Cell(30,10,$check[0]['phone'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Phone Number:',$border);
$pdf->Cell(30,10,$check[0]['phone'],$border);
$pdf->Ln();
$pdf->Cell(30,10,'Date Issued:',$border);
$pdf->Cell(30,10,$created,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Date Issued:',$border);
$pdf->Cell(30,10,$created,$border);
$pdf->Ln();



function RotatedText($x,$y,$txt,$angle,$pdf)
{
    //Text rotated around its origin
   
    $pdf->Rotate($angle,$x,$y);
    $pdf->SetFont('Arial','',60);
    $pdf->Text($x,$y,$txt);
    
    $pdf->Rotate(0);
}
$pdf->Ln(40);
RotatedText(110,175,$created,90,$pdf);

// return the generated output
$pdf->Output();





?>