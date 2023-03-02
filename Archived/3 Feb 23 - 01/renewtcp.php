<?php
require('./header.php'); 
include_once("./libs/dbfunctions.php");
include_once("./class/recievePayment.php");
$dbobject = new dbobject();
$payment = new Payment();

$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
$table = isset($_REQUEST['table'])?$_REQUEST['table']:'';


$operation = 'RENEWAL';

$sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id'";
$trans = $dbobject->db_query($sql);
$item_code = $trans[0]['item_code'];
$desc = $result[0]['trans_desc'];
$trans_code = $result[0]['trans_desc_code'];
// $sql = "SELECT * FROM $table WHERE portal_id = '$id'";
// $result = $dbobject->db_query($sql);


$sql = "SELECT * FROM payment_category WHERE id = '$item_code'";
$result = $dbobject->db_query($sql);
$amount = $result[0]['amount'];
// var_dump($result);

?>

    <section class="banner v10">
      <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div id="" class="card-body">
                        <h3 class="text-center">Transport Company Licence Renewal</h3>
                        <hr>
                        <div id="selfservice_stage" class="progress thin">
                            <div id="selfservice_stage_progressbar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                            </div>
                        </div>

                        <!-- STEP ONE -->
                        <!-- <section id="step_one" class="text-center">
                            <p>Renew Your Driving School Licence:<hr> <br> Enter Your Portal ID, Verify the Vehicle Details, Make Online Payment and Generate Receipt.</p>
                            <div>
                                <p class="text">Were You Issued a Portal ID</p>
                                <button onclick="yesIDO();" class="btn btn-success btn-option " type="button">Yes! I have a Portal ID</button>
                                &nbsp;&nbsp;
                                <button onclick="noIDont();" class="btn btn-primary btn-option" type="button">No! I Dont.</button>
                            </div> 
                        </section> -->


                        <section id="step_oner" class="text-center">
                            <form id="form1" onsubmit="return false">
                                <input type="hidden" value="Renewal.confirm_portTC" name="op">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="vehicle_search">Enter Portal ID :</label>
                                        <input class="form-control" id="pid" name="portal_id" placeholder="Portal ID" type="text" required>
                                    </div>
                                    &nbsp; &nbsp;<small class="text-mute" id="err1"></small>
                                </div>
                                <hr>
                                <a href="index.php" class="btn btn-danger">Cancel</a>
                                &nbsp; &nbsp;
                                <button onclick="Nextr();" class="btn btn-success" id="next" type="button">Next</button>
                            </form>
                        </section>

                        <!-- STEP TWO -->
                        <section id="step_twor" class="d-none">
                            <p class="text-center">
                                <strong class="text-underline">Please Note:</strong>
                                <span class="text-mute">You can only edit some fields here, to change any other information that cant be editted, kindly contact or visit <em>Mistry Of Transport</em></span>
                            </p>
                            <form id="form2" onsubmit="return false">
                                <input type="hidden" value="Renewal.checkTC" name="op">
                                <input type="hidden" value="" name="port" id="port">
                                <input type="hidden" value="TC" name="desc_code" id="port">
                                <input type="hidden" value="Transport Coompany Renewal" name="description" id="port">
                                <div class="row ">
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="first_name"> Name :</label>
                                        <input class="form-control inf" id="first_name" name="first_name" type="text" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="middle_name">Proprietor Name :</label>
                                        <input class="form-control inf" id="middle_name" name="middle_name" type="text" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="phoneNumber">Mobile Number :</label>
                                        <input class="form-control inf" id="phoneNumber" name="phoneNumber" type="text" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label control-label-bg" for="address">Address :</label>
                                        <input class="form-control inf" id="address" name="address" type="text" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label control-label-bg" for="exp">Expiry Date :</label>
                                        <input class="form-control inf" id="exp" name="exp" type="text" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label control-label-bg" for="tinval">Tax Identification Number :</label>
                                        <input class="form-control inf" id="tinval" name="tinval" type="text" required>
                                    </div>
                                    &nbsp; &nbsp;<small class="text-mute col-md-12" id="err2"></small>
                                </div>
                                <hr>
                                <a href="index.php" class="btn btn-danger text-white">Cancel</a>
                                &nbsp; &nbsp;
                                <button class="btn btn-success" id="proceedr" type="button">Confirm & Proceed</button>
                            </form>
                        </section>
    
                        <section id="step_threer" class="d-none">
                            <p class="text-center">
                                <strong class="text-underline">Please Note:</strong>
                                You are <strong class="italic">required</strong> to visit the State Ministry Of Transport for your printed documents after successful payment with the payment confirmation receipt.</span>
                            </p>

                            <form id="form3" onsubmit="return false">
                                <input type="hidden" value="Renewal.RenewInitPay" name="op">
                                <input type="hidden"  name="tinforP" id="tinforP">
                                <input type="hidden"  name="renew" id="renew">
                                <input type="hidden"  name="table" value="transport_companies">
                                <div class="row">
                                    &nbsp; &nbsp;<small class="text-mute col-md-12" id="err3"></small>
                                </div>
                                <hr>
                                <a href="index.php" class="btn btn-danger text-white">Cancel</a>
                                &nbsp; &nbsp;
                                <button class="btn btn-success" id="Payr" type="button">Make Payment</button>
                            </form>
                            
                        </section>


                    </div>
                </div>
            </div>
	    </div>
      </div>
    </section>

    <script>


    </script>
<?php require('footer.php'); ?>

