<?php
include_once("../libs/dbfunctions.php");
include_once("../class/sidenumber.php");
$dbobject = new dbobject();

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    
    $operation = 'edit';
}
else
{
    $operation = 'new';
}

$portal_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
$sql = "SELECT * FROM transport_companies WHERE portal_id = '$portal_id'";
$DSL =$dbobject->db_query($sql);
$LL = $DSL[0];
?>

<div class="card">
    <div class="card-header">
        <h4 class="page-title .text-primary-d1">
        Transport Companies License
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
                                <span class="text-default-d3">Plateau State Ministry Of Works And Transport</span>
                            </div>
                        </div>
                    </div>

                    <hr class="row brc-default-l1 mx-n1 mb-4" />

                    <div class="row">
                        <div class="col-sm-6">
                            <div style="color: green; font-weight: bold;">
                            <span class="text-sm text-grey-m2 align-middle"><h1>Bearer:</h1></span>
                                <span class="text-600 text-110 text-blue align-middle"><?php echo $LL['business_name'];?></span>
                            </div>
                            <hr>
                            <div class="text-grey-m2">
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Address :</small>
                                    <?php echo' '.$LL['address'];?>
                                </div>
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Phone Number: </small>
                                    <?php echo''.$LL['phone'];?>
                                </div>
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Owner Name :</small>
                                    <?php echo' '.$LL['owner_name'];?>
                                </div>
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Tax Identification Number :</small>
                                    <?php echo' '.$LL['tin'];?>
                                </div>
                                <div class="my-1"><b class="text-600"><small style="color: blue; font-size: 20px;">Ref Number:</small> <?php echo' '.$LL['portal_id'];?></b></div>
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