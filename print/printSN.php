<?php
include_once("../libs/dbfunctions.php");
include_once("../class/sidenumber.php");
$dbobject = new dbobject();

// reprocessing SN
$tin = isset($_REQUEST['tin'])?$_REQUEST['tin']:'';


$sql= "SELECT * FROM vehicle_sidenumbers WHERE tax = $tin LIMIT 1";
$vehicle_sidenumbers = $dbobject->db_query($sql);
$data = $vehicle_sidenumbers[0];
// var_dump($data);
?>

<div class="card">
    <div class="card-header">
        <h4 class="page-title .text-primary-d1">
            Side Number Slip
              
        </h4>
    </div>
    <div class="card-body">
        <div class="container px-0">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center text-150">
                            <img src="../img/logo/plateau_logo.jpg" alt="Plateau State Ministry Of Works And Transport" width="100" height="100">
                                <br>
                                <span class="text-default-d3">Plateau State Internal Revenew Service</span>
                            </div>
                        </div>
                    </div>

                    <hr class="row brc-default-l1 mx-n1 mb-4" />

                    <div class="row">
                        <div class="col-sm-6">
                            <div style="color: green; font-weight: bold; font-size: 20px;">
                                <span class="text-sm text-grey-m2 align-middle"><h1>Bearer:</h1></span>
                                <span class="text-600 text-110 text-blue align-middle"><?php echo $data['firstname'];?></span>
                            </div>
                            <hr>
                            <div class="text-grey-m2">
                                <div class="my-1">
                                    <?php echo'Plate Number: '.$data['plate_number'];?>
                                </div>
                                <div class="my-1">
                                    <?php echo'Phone Number: '.$data['mobile'];?>
                                </div>
                                <div class="my-1">
                                    <?php echo'Address: '.$data['address'];?>
                                </div>
                                <div class="my-1">
                                    <?php echo'Tax Identification Number: '.$data['tax'];?>
                                </div>
                                <div class="my-1">
                                    <?php echo'License Operator: '.$data['licence_operators'];?>
                                </div>
                                <div class="my-1"><b class="text-600"><small style="color: blue; font-size: 20px;">Side Number:</small> <?php echo' '.$data['side_number'];?></b></div>
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
