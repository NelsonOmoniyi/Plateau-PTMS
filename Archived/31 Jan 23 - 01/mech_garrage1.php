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
                    <h4 class="">Mechanic Garrage Registration or Renewal?</h4>
                    <hr>
                </div>
            </div>
        </div>

          <div class="col-sm-6">
            <a href="mech_garrage_payment.php" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="">Mechanic Garrage Registration</h5>
                  <hr>
                  <p class="card-text">Pay For Your Mechanic Garrage Licence</p>
                </div>
              </div>
            </a>
          </div>


          <div class="col-sm-6">
            <a href="renewmcg.php" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Mechanic Garrage Licence Renewal</h5>
                  <hr>
                  <p class="card-text">Renew Your Mechanic Garrage Licence</p>
                </div>
              </div>
            </a>
          </div>

        </div>
      </div>
</section>



<?php require('footer.php'); ?>