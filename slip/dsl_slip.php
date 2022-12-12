<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();

$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
// echo $id;
$sql = $dbobject->db_query("SELECT * FROM driving_sch_form WHERE portal_id = '$id'");

$status = $sql[0]['status'];

$portal_id = $sql[0]['portal_id'];
$name = $sql[0]['school_name'];
$address = $sql[0]['address'];
$category = $sql[0]['category'];
$date = $sql[0]['record_date'];
$phone = $sql[0]['phone'];
$tin = $sql[0]['tin'];

$sqlp = $dbobject->db_query("SELECT * FROM tb_payment_confirmation WHERE payment_code = '$portal_id'");

$total_amount = $sqlp[0]['trans_amount'];



?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Payment Confirmation Slip</title>
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
                    <?php echo "Portal ID: $portal_id";?>
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
                                <b class="text-600"> Address -></b><?php echo $address;?>
                                </div>
                                <div class="my-1">
                                    <i class="fa id-card fa-flip-horizontal text-secondary"></i> 
                                    <b class="text-600"> TIN -></b><?php echo $tin; ?>
                                </div>
                                <div class="my-1">
                                    <i class="fa fa-phone fa-flip-horizontal text-secondary"></i> 
                                    <b class="text-600"> Mobile Number -></b><?php echo $phone; ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                            <hr class="d-sm-none" />
                            <div class="text-grey-m2">
                                <div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
                                    Invoice
                                </div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">ID:</span> <?php echo $portal_id; ?></div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Issue Date:</span> <?php echo $date;?></div>

                                <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Status:</span>  <?php if ($status == 1) {
                                            echo "PAID";
                                        }else{
                                            echo "PAID";
                                        }
                                    ?>
                                    </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>

                       
                <div class="table-responsive">
                    <table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
                        <thead class="bg-none bg-success">
                            <tr class="text-white">
                                <th class="opacity-2">#</th>
                                <th>Driving School Registration</th>
                            </tr>
                        </thead>
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

        <script>
            
            
            function print() {
                window.print();
                return false;
            }
            
        </script>
    </body>
</html>