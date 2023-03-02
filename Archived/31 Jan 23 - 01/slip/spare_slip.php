<?php
ob_end_clean();
include_once('../libs/dbfunctions.php');
include_once('../fpdf/fpdf.php');
$dbobject = new dbobject();


$ip = ['13.41.180.63','41.242.60.178'];
$REMOTE_IP = $_SERVER['REMOTE_ADDR'];
if (in_array($REMOTE_IP, $ip)) {

}else{
    die("Unauthorized Access: Access Denied ".$REMOTE_IP);
}

$id  = isset($_REQUEST['pid'])? $_REQUEST['pid'] :'';
$table  = isset($_REQUEST['tbl'])? $_REQUEST['tbl'] :'';
$status = isset($_REQUEST['state'])? $_REQUEST['state']: '';
$expDate = date('Y-m-d', strtotime(' + 1 years'));
$processedDate = date('Y-m-d H:i:s');
// echo"PID:::$id::::::::::::::::::::table::$table::::::::::::::::::exp::$expDate::::::::::::::::::::::Process::$processedDate";

$sql = "UPDATE tb_payment_confirmation SET trans_status = '1' WHERE payment_code = '$id' AND trans_status = '0'";
$exec = $dbobject->db_query($sql, false);
if ($exec > 0) {
    $sql2 = "UPDATE $table SET status = '1', expiry_date = '$expDate', processed_date = '$processedDate' WHERE portal_id = '$id' AND status = '0'";
    $exec = $dbobject->db_query($sql2, false);
    if ($exec > 0) { 
    
        // return json_encode(array('response_code'=>'200', 'response_message'=>'Success', "pid"=>$pid, "tin"=>$result[0]['tin']));
    } else {
        echo json_encode(array("response_code"=>288,"response_message"=>'An Unknown Error Occured'));
    }
    } else {
    // echo json_encode(array("response_code"=>289,"response_message"=>'An Unknown Error Occured'));
    }

$immm = '../img/self_service_printout/self_service.png';

// $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
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
$processed = $sql[0]['processed_date'];
$suffix = "";
$payt_type = str_replace('_', ' ', $table);
$payt_type ="$payt_type $suffix";
$payt_type = ucwords($payt_type);
if($table=='driving_sch_form'){
    $name = $sql[0]['school_name'];
}else if($table=='dealership'){
    $name = $sql[0]['business_name'];
}else if($table=='transport_companies'){
    $name = $sql[0]['business_name'];
}else if($table=='spare_parts'){
    $name = $sql[0]['business_name'];
}else if($table=='mech_garrage'){
    $name = $sql[0]['business_name'];
}

$length = 20;
$lengths = 40;
function custom_echo($x, $length) {
    if (strlen($x) <= $length) {
       return $x;
    }
    return substr($x,0,$length) . '...';
 }

$font_size = 10;
$sqlp = $dbobject->db_query("SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id'");
// var_dump($cac);
$total_amount = $dbobject->getitemlabel('payment_category', 'id', $sql[0]['item_code'], 'amount');
$now = date("Y-m-d");

$new = explode(" ",$processed);
$ddd = date("M jS, Y", strtotime($new[0]));
$new = explode(" ",$ddd);
$month =  $new[0];
$date =  $new[1];
$year = $new[2];
$created = "$date $month $year";
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

$pdf->SetFont('Arial', 'B', 20);
$pdf->Ln(40);
$pdf->SetTextColor(10,70,100);
$pdf->SetX(19);
$pdf->Cell(102,0,'PAID',0,1,'L');

$pdf->SetFont('Arial', 'B', 20);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->SetX(19);
$pdf->Cell(221,0,'PAID',0,1,'C');

// name
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(14);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(308,0,$name,0,1,'C');


$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(63);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(113,-142,$name,0,1,'C');

// address
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(85);
$pdf->SetTextColor(10,70,100);
// $pdf->Cell(300,1,$address,0,1,'L');
$pdf->SetX(146);
$pdf->MultiCell(56,6,custom_echo($address,40),0,'L');

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-11);
$pdf->SetTextColor(10,70,100);
$pdf->SetX(49);
$pdf->MultiCell(41,5,custom_echo($address,$length),0,'L');
// $pdf->MultiCell(0,-185,$address,0,'L');

// cac
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(12);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(297,1,$cac,0,1,'C');

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(103,0,$cac,0,1,'C');

// portal ID
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(15);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(302,1,$portal_id,0,1,'C');

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-7);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(107,0,$portal_id,0,1,'C');

// Payment Type
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(14);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(304,1,$payt_type,0,1,'C');

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-5);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(108,0,$payt_type,0,1,'C');  


// Amount Paid
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(13);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(296,2,number_format($total_amount)." Naira",0,1,'C');

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(101,1,number_format($total_amount)." Naira",0,1,'C');

// TIN
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(11);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(298,5,$tin,0,1,'C');

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(103,0,$tin,0,1,'C');

// Phone Number
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(296,6,$phone,0,1,'C');

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(101,0,$phone,0,1,'C');

// Date
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(295,4,$now,0,1,'C');

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-6);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(100,0,$now,0,1,'C');

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
