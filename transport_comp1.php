<?php
include_once("libs/dbfunctions.php");
include_once("class/recievePayment.php");
include("header.php");
$dbobject = new dbobject();
$obpayments = new Payment();

?>

<section class="banner v7" style="height: 550px;">
      <div class="container">
        <div class="row">

        <div class="col-sm-12">
            <div class="card text-center tile">
                <div class="card-body">
                    <h4 class="">Transport Company Registration or Renewal?</h4>
                    <hr>
                </div>
            </div>
        </div>

          <div class="col-sm-6">
            <a href="transport_comp_payment.php" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="">Transport Company Registration</h5>
                  <hr>
                  <p class="card-text">Pay For Your Transport Company Licence</p>
                </div>
              </div>
            </a>
          </div>


          <div class="col-sm-6">
            <a href="renewtcp.php" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Transport Company Licence Renewal</h5>
                  <hr>
                  <p class="card-text">Renew Your Transport Company Licence</p>
                </div>
              </div>
            </a>
          </div>

        </div>
      </div>
</section>



<?php require('footer.php'); ?>