<?php
include_once("../libs/dbfunctions.php");
include_once('../fpdf/fpdf.php');
include_once("../class/sidenumber.php");
$dbobject = new dbobject();

// reprocessing SN
$ip = ['::1','13.41.180.63','41.242.60.178'];
$REMOTE_IP = $_SERVER['REMOTE_ADDR'];
if (in_array($REMOTE_IP, $ip)) {

}else{
    die("Unauthorized Access: Access Denied");
}
$tin = isset($_REQUEST['tin'])?$_REQUEST['tin']:'';
$processedDate = date('Y-m-d H:i:s');
$expDate = date('Y-m-d', strtotime(' + 1 years'));

$sql2 = "UPDATE vehicle_sidenumbers SET status = '1', expiry_date = '$expDate', processed_date = '$processedDate' WHERE tax = '$tin' AND status = '0'";
$exec = $dbobject->db_query($sql2, false);

$check = $dbobject->db_query("SELECT * FROM vehicle_sidenumbers WHERE tax='$tin'");
$SDN = $check[0]['side_number'];
$vehType_id = $check[0]['vehicle_typeid'];
$sql = "SELECT * FROM `vehicle_type` WHERE id ='$vehType_id'";
$res = $dbobject->db_query($sql);
$veh_name = $res[0]['vehicle_name'];
$price = $res[0]['reg'];
$renewal_amount = $res[0]['renew'];

$sqlSdate0 = "SELECT created,side_number,amount,payment_pin,status FROM vehicle_sidenumbers WHERE side_number = '$SDN'";
$resultDate0 = $dbobject->db_query($sqlSdate0);
$startDate0 = $resultDate0[0]['created'];
$side = $resultDate0[0]['side_number'];
$amount = $resultDate0[0]['amount'];
// $expiryDate0 = date('Y-m-d', strtotime('+1 year', strtotime($startDate0)));

$pay_status = (($check[0]['status']==0)?"UNPAID":"PAID");

$sqlTT = "insert into sidenumber_transaction (id,sidenumber,initial_payment,renewal_fee,initial_payment_date,expiry_date,renewal_date,payment_pin)
values(NULL,'$side','$amount','$renewal_amount', NOW(),'$expDate','$expDate','$pin')";

$Stransac = $dbobject->db_query($sqlTT);

if ($exec > 0) {
    
        // return json_encode(array("response_code"=>0,"response_message"=>'Success', "tin"=>$tin));
    } else {
        echo json_encode(array("response_code"=>288,"response_message"=>'An Unknown Error Occured'));
}

function custom_echo($x, $length) {
    if (strlen($x) <= $length) {
       return $x;
    }
    return substr($x,0,$length) . '...';
 }

$sql= "SELECT * FROM vehicle_sidenumbers WHERE tax = $tin LIMIT 1";
$vehicle_sidenumbers = $dbobject->db_query($sql);
$data = $vehicle_sidenumbers[0];

$surname = $data['surname'];
$firstname = $data['firstname'];
$fullname = "$surname $firstname";
$font_size = 10;

$new = explode(" ",$data['issue_date']);
$ddd = date("M jS, Y", strtotime($new[0]));
$new = explode(" ",$ddd);
$month =  $new[0];
$date =  $new[1];
$year = $new[2];
$created = "$date $month $year";
$border = 0;
$length = 24;
$lengths = 35;

$Enew = explode(" ",$data['expiry_date']);
$Eddd = date("M jS, Y", strtotime($Enew[0]));
$Enew = explode(" ",$Eddd);
$Emonth =  $Enew[0];
$Edate =  $Enew[1];
$Eyear = $Enew[2];
$expiring = "$Edate $Emonth $Eyear";

$immm = 'print.png';

$pdf = new FPDF('P','mm',array(210,297));
$pdf->AddPage('L');
$pdf->Image($immm,1,1,295);

// title
$pdf->SetTitle('Plateau State Ministry Of Transport');
$pdf->SetFont('Arial','',$font_size);
$pdf->SetTextColor(10,70,100);



$pdf->Ln(50);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(30,10,$pay_status,$border);
$pdf->Cell(80,10,'',$border);
$pdf->Cell(30,10,$pay_status,$border);
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,10,'Owners Name:',$border);
$pdf->Cell(30,10,$fullname,$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Owners Name:',$border);
$pdf->Cell(30,10,$fullname,$border);

$pdf->Ln();
$pdf->Cell(30,10,'Address:',$border);
$pdf->Cell(30,10,custom_echo($data['address'],$length),$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Address:',$border);
$pdf->Cell(30,10,custom_echo($data['address'],$lengths),$border);

$pdf->Ln();
$pdf->Cell(30,10,'Registration No:',$border);
$pdf->Cell(30,10,$data['side_number'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Registration No:',$border);
$pdf->Cell(30,10,$data['side_number'],$border);
$pdf->Ln();
$pdf->Cell(30,10,'Chasis No:',$border);
$pdf->Cell(30,10,$data['chasis_number'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Chasis No:',$border);
$pdf->Cell(30,10,$data['chasis_number'],$border);

$pdf->Ln();
$pdf->Cell(30,10,'Vehicle Make:',$border);
$pdf->Cell(30,10,$data['vehicle_make'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Vehicle Make:',$border);
$pdf->Cell(30,10,$data['vehicle_make'],$border);
$pdf->Ln();
$pdf->Cell(30,10,'Vehicle Type:',$border);
$pdf->Cell(30,10,$data['vehicle_model'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Vehicle Type:',$border);
$pdf->Cell(30,10,$data['vehicle_model'],$border);
$pdf->Ln();
$pdf->Cell(30,10,'Vehicle Color:',$border);
$pdf->Cell(30,10,$data['vehicle_color'],$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'Vehicle Color',$border);
$pdf->Cell(30,10,$data['vehicle_color'],$border);
$pdf->Ln();
$pdf->Cell(30,10,'License Fee:',$border);
$pdf->Cell(30,10,number_format($data['amount']),$border);
$pdf->Cell(50,10,'',$border);
$pdf->Cell(30,10,'License Fee:',$border);
$pdf->Cell(30,10,number_format($data['amount']),$border);
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
$pdf->Ln(60);
RotatedText(110,190,$created,90,$pdf);
$pdf->Output();
?>

