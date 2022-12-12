<?php
ob_end_clean();
include_once('../libs/dbfunctions.php');
include_once('../fpdf/fpdf.php');
$dbobject = new dbobject();

$id  = isset($_REQUEST['id'])? $_REQUEST['id'] :'';
$table  = isset($_REQUEST['table'])? $_REQUEST['table'] :'';

if($table=='dealership'){
    $immm = '../img/certificates/Dealership Certificate.jpeg';
}else{
    $immm = '../img/certificates/Auto Mech and Tech Certificate.jpeg';
}

$check = $dbobject->db_query("SELECT * FROM $table WHERE portal_id='$id'");
$item_code = $check[0]['item_code'];
$item = $dbobject->getitemlabel('payment_category', 'id', $item_code, 'item');
$itemcode = $dbobject->getitemlabel('payment_category', 'id', $item_code, 'code');
$created = $check[0]['created'];

$new = explode(" ",$created);
$ddd = date("M jS, Y", strtotime($new[0]));
$new = explode(" ",$ddd);
$month =  $new[0];
$date =  $new[1];
$year = $new[2];
$month_year = "$month $year";

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
$pdf->SetFont('Arial', 'B', 20);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(265,0,$check[0]['owner_name'],0,1,'C');

// day
$pdf->Ln(31);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(229,0,$date,0,1,'C');

// Month and Year
$pdf->Ln(0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(330,0,$month_year,0,1,'C');
// $this->SetFont('Times','B',9);
// $this->SetTextColor(0,0,180);
// $this->Cell(0,10,$text,0,0,'R');
// $this->Ln(6); 
// Portal ID
$pdf->Ln(-95);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(462,0,$id,0,1,'C');

//category
$pdf->Ln(124);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(10,70,100);
// $pdf->Cell(152,179,$item,0,1,'C');
$pdf->SetX(65);
$pdf->MultiCell(65,3,$item,0);

//category code
$pdf->Ln(-10);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(452,2,$itemcode,0,1,'C');



// return the generated output
$pdf->Output();





?>