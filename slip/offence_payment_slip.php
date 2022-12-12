offence_id<?php
ob_end_clean();
include_once('../libs/dbfunctions.php');
include_once('../fpdf/fpdf.php');
$dbobject = new dbobject();

$id  = isset($_REQUEST['id'])? $_REQUEST['id'] :'';
$table  = isset($_REQUEST['table'])? $_REQUEST['table'] :'';

$immm = '../img/self_service_printout/offences.jpg';

$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
// echo $id;
$sql = $dbobject->db_query("SELECT * FROM tb_offences_payment WHERE offence_id = '$id'");
$sql2 = $dbobject->db_query("SELECT * FROM tb_payments_data WHERE offence_id = '$id'");
$a_status = $sql[0]['trans_status'];
$status = $sql2[0]['status'];

$offence_id = $sql[0]['offence_id'];
$name = $sql[0]['name'];
$address = $sql[0]['address'];
$category = $sql[0]['category'];
$date = $sql[0]['created'];
$phone = $sql[0]['phone_number'];
$total_amount = $sql[0]['total_amount'];
$tin = $sql[0]['tin'];
$sql1 = $dbobject->db_query("SELECT trans_desc, trans_amount FROM tb_payment_confirmation WHERE offence_id = '$offence_id'");
$counter = 0;

$font_size = 10;
// $sqlp = $dbobject->db_query("SELECT * FROM tb_payment_confirmation WHERE payment_code = '$portal_id'");
// // var_dump($cac);
// $total_amount = $sqlp[0]['trans_amount'];
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
$pdf->Cell(300,75,$offence_id,0,1,'C');

$pdf->SetFont('Arial', 'B', $font_size);
$pdf->Ln(87);
$pdf->SetTextColor(10,70,100);
$pdf->Cell(100,-260,$offence_id,0,1,'C');

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

<!-- <!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Offence Payment Slip</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/offence.css">
    </head>
    <body>
    <div class="page-content container" style='width:70%;'>
        <div class="page-header text-blue-d2">
            <h1 class="page-title .text-success-d1">
            Payment Reciept
                <small class="page-info">
                    <i class="fa fa-angle-double-right text-80"></i>
                    <?php echo "ID: $offence_id";?>
                </small>
            </h1>
            <div class="page-tools">
                <div class="action-buttons">
                    <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="Print" onclick='print();'>
                        <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                        Print
                    </a>
                </div>
            </div>
        </div>

        <div class="container px-0">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center text-150">
                                <img src="../img/logo/plateau_logo.jpg" alt="Plateau State Ministry Of Transport" width="100" height="100">
                                <br>
                                <span class="text-default-d3">Plateau State Ministry of Works and Transport</span>
                            </div>
                        </div>
                    </div>

                    <hr class="row brc-default-l1 mx-n1 mb-4" />

                    <div class="row">
                        <div class="col-sm-6">
                            <div>
                                <span class="text-sm text-grey-m2 align-middle">Bearer:</span>
                                <span class="text-600 text-110 text-blue align-middle"><?php echo $name;?></span>
                            </div>
                            <div class="text-grey-m2">
                                <div class="my-1">
                                    <?php echo $address;?>
                                </div>
                                <div class="my-1">
                                    <i class="fa fa-phone fa-flip-horizontal text-secondary"></i> 
                                    <b class="text-600"><?php echo $phone; ?></b>
                                </div>
                                <div class="my-1">
                                    <i class="fa-id-card fa-flip-horizontal text-secondary"></i> 
                                    <b class="text-600"><?php echo $tin; ?></b>
                                </div>
                            </div>
                        </div>
                        

                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                            <hr class="d-sm-none" />
                            <div class="text-grey-m2">
                                <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                    Invoice
                                </div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">ID:</span> <?php echo $offence_id; ?></div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Issue Date:</span> <?php echo $date;?></div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Status:</span>  <?php if ($a_status == 1 && $status == 1) {
                                            echo "PAID";
                                        }else{
                                            echo "UNPAID";
                                        }
                                    ?>
                                    </div>
                            </div>
                        </div>
                       
                    </div>

                       
                <div class="table-responsive">
                    <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                        <thead class="bg-none bg-success">
                            <tr class="text-white">
                                <th class="opacity-2">#</th>
                                <th>Offence Description</th>
                                <th width="140">Amount</th>
                            </tr>
                        </thead>

                        <tbody class="text-95 text-secondary-d3">
                            <tr></tr>
                            <?php 
                                foreach ($sql1 as $value) {
                                    $counter ++;
                                   echo '<tr>';
                                   echo '<td>'.$counter.'</td>';
                                   echo '<td>'.$value['trans_desc'].'</td>';
                                   echo '<td class="text-secondary-d2">&#8358;'.$value['trans_amount'].'</td>';
                                   echo '<tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
               

                        <div class="row mt-3">
                            <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                    <div class="text-150 text-success-d3 opacity-2">
                                        Total Amount
                                    </div>
                                    <div class="col-5">
                                        <span class="text-150 text-success-d3 opacity-2"><?php echo '&#8358;'.$total_amount; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                            Extra note such as company or payment information...
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" async defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js">
    </body>
</html> -->