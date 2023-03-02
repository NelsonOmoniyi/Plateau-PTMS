<?php
include_once("../libs/dbfunctions.php");
include_once('../fpdf/fpdf.php');
include_once("../class/sidenumber.php");
$dbobject = new dbobject();

// reprocessing SN
$ip = ['13.41.180.63','41.242.60.178'];
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

$sqlSdate0 = "SELECT created,side_number,amount,payment_pin FROM vehicle_sidenumbers WHERE side_number = '$SDN'";
$resultDate0 = $dbobject->db_query($sqlSdate0);
$startDate0 = $resultDate0[0]['created'];
$side = $resultDate0[0]['side_number'];
$amount = $resultDate0[0]['amount'];
// $expiryDate0 = date('Y-m-d', strtotime('+1 year', strtotime($startDate0)));

$sqlTT = "insert into sidenumber_transaction (id,sidenumber,initial_payment,renewal_fee,initial_payment_date,expiry_date,renewal_date,payment_pin)
values(NULL,'$side','$amount','$renewal_amount', NOW(),'$expDate','$expDate','$pin')";

$Stransac = $dbobject->db_query($sqlTT);

if ($exec > 0) {
    
        // return json_encode(array("response_code"=>0,"response_message"=>'Success', "tin"=>$tin));
    } else {
        // return json_encode(array("response_code"=>288,"response_message"=>'An Unknown Error Occured'));
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


$Enew = explode(" ",$data['expiry_date']);
$Eddd = date("M jS, Y", strtotime($Enew[0]));
$Enew = explode(" ",$Eddd);
$Emonth =  $Enew[0];
$Edate =  $Enew[1];
$Eyear = $Enew[2];
$expiring = "$Edate $Emonth $Eyear";

$immm = 'printSN.png';

$pdf = new FPDF('P','mm',array(210,297));
$pdf->AddPage('L');
$pdf->Image($immm,1,1,295);

// title
$pdf->SetTitle('Plateau State Ministry Of Transport');
$pdf->SetFont('Arial','',$font_size);
$pdf->SetTextColor(5,120,180);

$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(24);
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(110,45,$fullname,0,1,'L');

// address
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-12);
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->MultiCell(40,5,$data['address'],0,'L');

// sidenumber
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln();
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,3,$data['side_number'],0,'L');

// chasis number
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln();
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,22,$data['chasis_number'],0,'L');

// vehicle make
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(18);
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,0,$data['vehicle_make'],0,'L');

// vehicle model
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(7);
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,0,$data['vehicle_model'],0,'L');

// vehicle color
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(10);
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,2,$data['vehicle_color'],0,'L');

// amount
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(28);
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,4,number_format($data['amount'],2),0,'L');

// amount
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(14);
$pdf->SetX(49);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,5,$created,0,'L');


// OWNERS CORNER

// sidenumber
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(-109);
$pdf->SetX(149);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,-1,$data['side_number'],0,'L');

// contact
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(8);
$pdf->SetX(149);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,1,$data['mobile'],0,'L');

// chasis
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(17);
$pdf->SetX(149);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,1,$data['chasis_number'],0,'L');

// vehicle make
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(9);
$pdf->SetX(149);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,2,$data['vehicle_make'],0,'L');

// vehicle model
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(9);
$pdf->SetX(149);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,3,$data['vehicle_model'],0,'L');

//color
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(13);
$pdf->SetX(149);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,3,$data['vehicle_color'],0,'L');

//date
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(16);
$pdf->SetX(149);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,3,$created,0,'L');

//expiring
$pdf->SetFont('Arial', '', $font_size);
$pdf->Ln(8);
$pdf->SetX(149);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(40,3,$expiring,0,'L');

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

