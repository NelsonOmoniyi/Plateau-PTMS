<?php
include_once("libs/dbfunctions.php");
include_once("class/recievePayment.php");
include("header.php");
$dbobject = new dbobject();
$obpayments = new Payment();

?>
    <!-- Header end -->

    
    <section class="banner v7">
      <div class="container">
        <div class="row" style="color: black !important;">

        <div class="col-sm-12">
            <div class="card text-center tile">
              <div class="card-body">
                <h4 class="">Select The Item You Want To Make Payment For!</h4>
                <hr>
              </div>
            </div>
        </div>

          <div class="col-sm-4 ">
            <a href="driving_school.php" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="">Driving School</h5>
                  <hr>
                  <p class="card-text">Begin Registration or Renew Your Driving School Licence</p>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-4">
            <a href="https://google.com" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Mechanic Garrage</h5>
                  <hr>
                  <p class="card-text">Begin Registration or Renew Your Mechanic Garrage Licence</p>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-4">
            <a href="https://google.com" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Spare Part Dealership</h5>
                  <hr>
                  <p class="card-text">Begin Registration or Renew Your Spare Part Dealership Licence</p>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-4">
            <a href="https://google.com" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Transport Companies</h5>
                  <hr>
                  <p class="card-text">Begin Registration or Renew Your Transport Company Licence.</p>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-4">
            <a href="https://google.com" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Transport Union</h5>
                  <hr>
                  <p class="card-text">Begin Registration or Renew Your Transport Union Licence.</p>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-4">
            <a href="https://google.com" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Dealership</h5>
                  <hr>
                  <p class="card-text">Begin Registration or Renew Your Dealership Licence.</p>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-4">
            <a href="https://google.com" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Side-Number</h5>
                  <hr>
                  <p class="card-text">Begin Registration or Renew Your Vehicle Side-Number</p>
                </div>
              </div>
            </a>
          </div>

          <div class="col-sm-4">
            <a href="https://google.com" class="p-4">
              <div class="card text-center tile">
                <div class="card-body">
                  <h5 class="card-title">Road Traffic Offences</h5>
                    <hr>
                </div>
              </div>
            </a>
          </div>


        </div>
      </div>
    </section>



    <!-- Footer strat -->
    <footer class="footer">
      <div class="foo-btm">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <div class="foo-navigation">
                <ul>
                  <li><a href="#">Terms and Conditions</a></li>
                  <li><a href="#">Privacy & Policy</a></li>
                  <li><a href="#">Legal</a></li>
                  <li><a href="#">Notice</a></li>
                </ul>
              </div>
            </div>
            <div class="col-md-6">
             <div class="copyright">Copyright &copy; <a href="accessng.com">Access Solutions LTD</a> - <?php echo date("Y");?></div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- Footer end -->

    <!-- JS -->
    <script src="js1/jquery-3.3.1.min.js"></script>
    <script src="js1/jquery-ui.min.js"></script>
    <script src="js1/bootstrap.min.js"></script>
    <script src="js1/owl.carousel.min.js"></script>
    <script src="js1/owl.carousel2.thumbs.min.js"></script>
    <script src="js1/jquery.countdown.min.js"></script>
    <script src="js1/jquery.fancybox.min.js"></script>
    <script src="js1/jquery.nice-select.min.js"></script>
    <script src="../ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
    <script src="../s.cdpn.io/106949/jquery.onscreen.js"></script>
    <script src="js1/scripts.js"></script>
  </body>
</html>

<script>

function saveRecord()
    {
        
        $("#pay").text("Loading......");
        var dd = $("#form1").serialize();
        $.post("utilities.php",dd,function(re)
        {
            
            // console.log(re);
            if(re.response_code == 200)
                {
                  $("#pay").prop('disabled',true);
                    $("#err").css('color','green')
                    $("#err").text(re.response_message)
                    $("#pay").html('Success')
                }
            else
                {
                    $("#err").css('color','red')
                    $("#err").text(re.response_message)
                    $("#pay").html('Pay With Monify')
                }
                
        },'json')
   

    }

</script>