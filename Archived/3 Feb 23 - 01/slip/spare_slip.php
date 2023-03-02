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


$sql_count = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id' AND trans_status = 1";
$result_count = $dbobject->db_query($sql_count);
$count = count($result_count);
if ($count > 0) {
    die("Your Payment has been confirmed and approved. kindly go to the Nearest Office or Registration center to get your receipt");
}

$sql = "UPDATE tb_payment_confirmation SET trans_status = '1' WHERE payment_code = '$id' AND trans_status = '0'";
$exec = $dbobject->db_query($sql, false);
if ($exec > 0) {
    $sql2 = "UPDATE $table SET status = '1', expiry_date = '$expDate', processed_date = '$processedDate' WHERE portal_id = '$id' AND status = '0'";
    $exec = $dbobject->db_query($sql2, false);
    file_put_contents('update.txt', $sql);
    if ($exec > 0) { 
    
        // return json_encode(array('response_code'=>'200', 'response_message'=>'Success', "pid"=>$pid, "tin"=>$result[0]['tin']));
    } else {
        echo json_encode(array("response_code"=>288,"response_message"=>'An Unknown Error Occured'));
    }
    } else {
    echo json_encode(array("response_code"=>289,"response_message"=>'An Unknown Error Occured'));
    }

$immm = 'receipt.jpg';

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
    $cur_date = $sql[0]['record_date'];
    $name = $sql[0]['school_name'];
}else if($table=='dealership'){
    $cur_date = $sql[0]['created'];
    $name = $sql[0]['business_name'];
}else if($table=='transport_companies'){
    $cur_date = $sql[0]['created'];
    $name = $sql[0]['business_name'];
}else if($table=='spare_parts'){
    $name = $sql[0]['business_name'];
    $cur_date = $sql[0]['created'];
}else if($table=='mech_garrage'){
    $name = $sql[0]['business_name'];
    $cur_date = $sql[0]['created'];
}
$pay_status = (($sql[0]['status']==0)?"UNPAID":"PAID");
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
$pdf->SetTextColor(10,70,100);


$pdf->Ln(35);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(30,10,$pay_status,$border);
$pdf->Cell(80,10,'',$border);
$pdf->Cell(30,10,$pay_status,$border);
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,10,'Owners Name:',$border);
$pdf->Cell(30,10,$name,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Owners Name:',$border);
$pdf->Cell(30,10,$name,$border);

$pdf->Ln();
$pdf->Cell(30,10,'Address:',$border);
$pdf->Cell(30,10,custom_echo($address,$length),$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Address:',$border);
$pdf->Cell(30,10,custom_echo($address,$lengths),$border);

$pdf->Ln();
$pdf->Cell(30,10,'CAC:',$border);
$pdf->Cell(30,10,$cac,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'CAC:',$border);
$pdf->Cell(30,10,$cac,$border);
$pdf->Ln();
$pdf->Cell(30,10,'Portal ID:',$border);
$pdf->Cell(30,10,$portal_id,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Portal ID:',$border);
$pdf->Cell(30,10,$portal_id,$border);

$pdf->Ln();
$pdf->Cell(30,10,'Payment Type:',$border);
$pdf->Cell(30,10,custom_echo($payt_type,$length),$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Payment Type:',$border);
$pdf->Cell(30,10,custom_echo($payt_type,$length),$border);
$pdf->Ln();
$pdf->Cell(30,10,'Amount Paid:',$border);
$pdf->Cell(30,10,number_format($total_amount),$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Amount Paid:',$border);
$pdf->Cell(30,10,number_format($total_amount),$border);
$pdf->Ln();
$pdf->Cell(30,10,'TIN:',$border);
$pdf->Cell(30,10,$tin,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'TIN:',$border);
$pdf->Cell(30,10,$tin,$border);
$pdf->Ln();
$pdf->Cell(30,10,'Phone Number:',$border);
$pdf->Cell(30,10,$phone,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Phone Number:',$border);
$pdf->Cell(30,10,$phone,$border);
$pdf->Ln();
$pdf->Cell(30,10,'Date Issued:',$border);
$pdf->Cell(30,10,$now,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Date Issued:',$border);
$pdf->Cell(30,10,$now,$border);
$pdf->Ln();

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
