<?php
include_once("libs/dbfunctions.php");
include_once("class/recievePayment.php");
include("header.php");
$dbobject = new dbobject();
$obpayments = new Payment();

?>

<section class="banner v7" style="min-height: 550px;">
    <div class="container">
        <div class="row">

            <div class="col-sm-12">
                <div class="bg-light" style="padding:35px;">
                    <h3 class="text-light text-center"
                        style="font-size:18px; font-weight:bolder; color:#395b25 !important;">
                        Spare Parts Dealership Registration or Renewal?</h3>
                </div>

            </div>

            <div class="col-sm-6">
                <a href="spare_parts_payments.php" class="p-4">
                    <div class="bg-light" style="padding:35px;">
                        <h3 class="text-light" style="font-size:18px; font-weight:bolder; color:#395b25 !important;">
                            Spare Parts Dealership Registration</h3>
                        <p style="font-size:13px; color:#8d8ea3;">Pay For Your Spare Parts Dealership Licence</p>
                    </div>

                </a>
            </div>


            <div class="col-sm-6">
                <a href="renewspd.php" class="p-4">
                    <div class="bg-light" style="padding:35px;">
                        <h3 class="text-light" style="font-size:18px; font-weight:bolder; color:#395b25 !important;">
                            Spare Parts Dealership Licence Renewal</h3>
                        <p style="font-size:13px; color:#8d8ea3;">Renew Your Spare Parts Dealership Licence</p>
                    </div>

                </a>
            </div>

        </div>
    </div>
</section>



<?php require('footer.php'); ?>