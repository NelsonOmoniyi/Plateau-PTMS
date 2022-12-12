<?php
ob_end_clean();
include_once('../libs/dbfunctions.php');
include_once('../fpdf/fpdf.php');

$pdf = new FPDF();
$object = new dbobject();
$immm = '../img/certificates/Auto Mech and Tech Certificate.jpeg';
$pdf->AddPage();
$pdf->Image($immm,1,1,208);

// title
$pdf->SetTitle('Plateau State Ministry Of Transport');
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(5,120,180);

// name
$pdf->SetFont('Arial', 'B', 20);
$pdf->Ln(57);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(190,0,'Nelson Omoniyi',0,1,'C');

// day
$pdf->Ln(24);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(155,0,'15th',0,1,'C');

// Month and Year
$pdf->Ln(0);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(220,0,'December 2022',0,1,'C');
// $this->SetFont('Times','B',9);
// $this->SetTextColor(0,0,180);
// $this->Cell(0,10,$text,0,0,'R');
// $this->Ln(6); 
// Portal ID
$pdf->Ln(-68);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(330,0,'PID9373836483',0,1,'C');






// return the generated output
$pdf->Output();





?>