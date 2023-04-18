<?php  
include_once("./libs/dbfunctions.php");
include_once("./class/recievePayment.php");
$dbobject = new dbobject();
$payment = new Payment();

$sql ="SELECT * FROM offences";
$got = $dbobject->db_query($sql);

?>

<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Favicon icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="img/icons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="img/icons//favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="img/icons//favicon-16x16.png">
	<link rel="manifest" href="img/icons//site.webmanifest">
	<!-- <link rel="shortcut icon" type="image/png" href="./img/favicon.png"> -->

	<!-- All CSS -->
	<!-- fontAwesome -->
	<link rel="stylesheet" href="./css/all.min.css">
	<!-- 7 stroke icon -->
	<link rel="stylesheet" href="./css/pe-icon-7-stroke.css">
	<!-- Roysha icon -->
	<link rel="stylesheet" href="./css/roysha-icons.css">

	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/owl.carousel.min.css">
	<link rel="stylesheet" href="./css/jquery.fancybox.min.css">
	<link rel="stylesheet" href="./css/nice-select.css">
	<link rel="stylesheet" href="./css/style.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">

    <!-- cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

	<title>Plateau State PTMS</title>
	<style>
		
		#login{
			border: 1px solid blue;
			padding: 8px 20px 8px 20px;
			text-align: center;
			border-radius: 25px;
		}
		.features {
			box-shadow: 0 0 10px rgba(0, 0, 0, .1);
			overflow: hidden;
		}

		.features .sec-heading {
			margin: 60px auto 60px;
		}

		.single-demo {
			position: relative;
			display: block;
			margin-bottom: 50px;
			padding-bottom: 30px;
			box-shadow: 0 0 5px #ddd;
		}

		.single-demo:hover {
			box-shadow: 0 0 40px #ddd;
		}

		.single-demo--img {
			max-height: 560px;
			overflow: hidden;
		}

		.single-demo span {
			padding: 5px;
			display: block;
			text-align: center;
			border: 1px solid #ddd;
			max-width: 230px;
			border-radius: 5px;
			font-weight: 300;
			text-transform: uppercase;
			font-size: 14px;
			letter-spacing: 0.6px;
			margin: 35px auto 0;
		}

		.icon-list-block {
			padding: 15px 12px;
		}

		.iconBox {
			margin-bottom: 30px;
		}

		.footer h2 {
			color: #fff;
			margin-bottom: 40px;
		}

		.footer .copyright {
			text-align: center;
		}
		body{
			font-family: 'Roboto Mono', monospace !important;
		}
		#tit{
			text-align: center;
		}
	</style>
</head>

<?php require('header.php'); ?>
<!-- Content -->
    <section class="banner v7">
      <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div id="" class="card-body">
                        <h3 class="text-center">Road Traffic Offences</h3>
                        <hr>
                        <div id="selfservice_stage" class="progress thin">
                            <div id="selfservice_stage_progressbar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                            </div>
                        </div>

                        <!-- STEP ONE -->
                        <section id="step_one" class="text-center">
                            <p>To Settle and Pay for Your Road Traffic Offence(s):<hr> <br> Enter the Booking Ticket Reference or Select Applicable Offence(s),<br> Enter Your Personal Details, Make Online Payment and Generate Receipt.
                             </p>
                            <div>
                                <p class="text">Were You Issued a Reference ID?</p>
                                <button onclick="yesIDO();" class="btn btn-success btn-option " type="button">Yes! I have a Reference ID</button>
                                &nbsp;&nbsp;
                                <button onclick="noIDont();" class="btn btn-primary btn-option" type="button">No! I Dont.</button>
                            </div> 
                        </section>

                                <!-- YES I HAVE A BOOKING REFERENCE  -->
                        <section id="step_two" class="d-none">
                            <form id="form1" onsubmit="return false">
                                <input type="hidden" value="Offences.validatePID" name="op">
                                <div class="row ">
                                    <div class="form-group col-md-6 ">
                                        <label class="control-label control-label-bg" for="vehicle_search">Enter Reference ID :</label>
                                        <input class="form-control" id="pid" name="portal_id" placeholder="Reference ID" type="text" required>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <label class="control-label control-label-bg" for="vehicle_search">Enter Tax Identification Number :</label>
                                        <input class="form-control" id="tin" name="tin" placeholder="Tax Identification Number" type="text" required>
                                    </div> -->
                                    &nbsp; &nbsp;<small class="text-mute" id="err1"></small>
                                </div>
                                <hr>
                                <a href="index.php" class="btn btn-danger">Cancel</a>
                                &nbsp; &nbsp;
                                <button onclick="Next();" class="btn btn-success" id="next" type="button">Next</button>
                            </form>
                        </section>

                        <!-- STEP TWO -->
                        <section id="step_three" class="d-none">
                            <p class="text-center">
                                <strong class="text-underline">Please Note:</strong>
                                <span class="text-mute">You can only edit some fields here, to change any other information that cant be editted, kindly contact or visit <em>Ministry Of Transport</em></span>
                            </p>
                            <form id="form2" onsubmit="return false">
                                <input type="hidden" value="" name="ref" id="ref">
                                <input type="hidden" value="Offences.save_initPay" name="op">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="plate"> Plate Number:</label>
                                        <input class="form-control inf" id="plate" name="plate" type="text"  required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="vehmake">Vehicle Make :</label>
                                        <input class="form-control inf" id="vehmake" name="vehmake" type="text"  required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="vehtype">Vehicle Type :</label>
                                        <input class="form-control inf" id="vehtype" name="vehtype" type="text" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="chasis">Chasis Number :</label>
                                        <input class="form-control inf" id="chasis" name="chasis" type="text"  required>
                                    </div>
                                    <div class=" form-group col-md-6">
                                        <label class="control-label control-label-bg" for="address">Address :</label>
                                        <input class="form-control inf" id="address" name="address" type="text" required>
                                    </div>
                                    <div class=" form-group col-md-6">
                                        <label class="control-label control-label-bg" for="count">Number Of Offences :</label>
                                        <input class="form-control inf" id="count" name="count" type="text" required>
                                    </div>
                                    <div class=" form-group col-md-6">
                                        <label class="control-label control-label-bg" for="catego">Category :</label>
                                        <input class="form-control inf" id="catego" name="categ" type="text" required disabled>
                                    </div>
                                    <div class=" form-group col-md-6">
                                        <label class="control-label control-label-bg" for="price">Total Amount :</label>
                                        <input class="form-control inf" id="price" name="price" type="text" required>
                                    </div>
                                    <div class=" form-group col-md-6">
                                        <label class="control-label control-label-bg" for="tinval">Tax Identification Number :</label>
                                        <input class="form-control inf" id="tinval" name="tinval" type="text" required>
                                    </div>
                                    &nbsp; &nbsp;<small class="text-mute col-md-12" id="err2"></small>
                                </div>
                                <hr>
                                <a class="btn btn-danger text-white">Cancel</a>
                                &nbsp; &nbsp;
                                <button class="btn btn-success" id="proceed1" type="button">Confirm & Proceed</button>
                            </form>
                        </section>
    
                        <section id="step_four" class="d-none">
                            <p class="text-center">
                                <strong class="text-underline">Please Note:</strong>
                                You are <strong class="italic">required</strong> to visit the State Ministry Of Transport for your printed documents after successful payment with the payment confirmation receipt.</span>
                            </p>
                            <form id="form3" onsubmit="return false">
                                <input type="hidden" value="Payment.InitPay" name="op">
                                <input type="hidden"  name="tinforP" id="tinforP">
                                <div class="row">
                                    &nbsp; &nbsp;<small class="text-mute col-md-12" id="err3"></small>
                                </div>
                                <hr>
                                <a class="btn btn-danger text-white">Cancel</a>
                                &nbsp; &nbsp;
                                <button class="btn btn-success" id="Pay" type="button">Make Payment</button>
                            </form>
                        </section>

                    <!--  -->
                        <!-- NO I DONT HAVE A BOOKING REFERENCE  -->
                        <section id="step_off1" class="d-none">
                            <form id="form11" onsubmit="return false">
                                <input type="hidden" id="track" value="">
                                <input type="hidden" value="Offences.SelectOFF" name="op">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="platef12">Plate Number :</label>
                                        <input class="form-control" id="platef12" name="platef12" type="text"  required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label control-label-bg" for="tinf">Tax Identification Number :</label>
                                        <input class="form-control" id="tinf" name="tinf" type="text"  required>
                                    </div>
                                    <hr>
                                    <div class="col-md-12" style="text-align: center;">
                                        <div id="newRow" ></div>
                                        <button id="addRow" type="button" class="btn btn-lg btn-warning" required>Add Violation</button>
                                    </div>
                                    &nbsp; &nbsp;<small class="text-mute" id="errOff"></small>
                                </div>
                                <hr>
                                <div  style="text-align: center;">
                                    <a href="index.php" class="btn btn-danger btn-md">Cancel</a>
                                &nbsp; &nbsp;
                                <button onclick="" class="btn btn-success btn-md" id="off1" type="button">Next</button>
                                </div>
                                
                            </form>
                        </section>
                        <!-- Off12 -->
                        <section id="step_off2" class="d-none">
                            <p class="text-center">
                                <span class="text-mute">All The Fields Here Are Required</span>
                            </p>
                            <form id="form12" onsubmit="return false">
                                <input type="hidden" value="Offences.saveData" name="op">
                                <input type="hidden" value="" id="ids" name="arr">
                                <input type="hidden" value="" id="tinff" name="tin">
                                <input type="hidden" value="<?php echo time(); ?>" id="offid" name="offid">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="amount"> Total Amount :</label>
                                        <input class="form-control" id="amount" name="amount" type="text"  required readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="name">Name :</label>
                                        <input class="form-control" id="name" name="name" type="text"  required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="phone">Phone Number :</label>
                                        <input class="form-control" id="phoneD" name="phone" type="text"  required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="address">Address :</label>
                                        <input class="form-control" id="addressD" name="address" type="text" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="nin">NIN :</label>
                                        <input class="form-control" id="ninD" name="nin" type="text" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="vehmake">Vehicle Make :</label>
                                        <input class="form-control" id="vehmakeD" name="vehmake" type="text"  required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="vehtype">Vehicle Type :</label>
                                        <input class="form-control" id="vehtypeD" name="vehtype" type="text"  required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="vehcat">Vehicle Category :</label>
                                        <input class="form-control" id="vehcatD" name="vehcat" type="text" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="chasis">Chasis Number :</label>
                                        <input class="form-control" id="chasisD" name="chasis" type="text" required>
                                    </div>
                                    
                                    <div class="form-group col-md-4">
                                        <label class="control-label control-label-bg" for="plate">Plate Number :</label>
                                        <input class="form-control" id="plateNN" name="plateNN" type="text"  required>
                                    </div>

                                    &nbsp; &nbsp;<small class="text-mute col-md-12" id="err12"></small>
                                </div>
                                <hr>
                                <a class="btn btn-danger text-white">Cancel</a>
                                &nbsp; &nbsp;
                                <button class="btn btn-success" id="offPay" type="button">Confirm & Proceed</button>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
	    </div>
      </div>
    </section>


    

    <footer class="footer">
        <div class="foo-btm">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="copyright">Copyright &copy; <a href="accessng.com"> Powered By Access Solutions LTD </a><?php echo " ".date("Y");?>
                        </div>
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
		<script src="./js/off.js"></script>
		<script src="js1/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

        <script>
// add row
        $("#addRow").click(function () {
                $("#track").val('1');
                var html = '';
                html += '<div class="row" id="row">';
                html += '<div class="col-lg-12">';
                html += '<div id="inputFormRow">';
                html += '<div class="input-group mb-3">';
                html += '<select name="offences[]" id="cart" class="form-control" required>';
                html += '<option hidden value="">:: SELECT OFFENCES ::</option>';
                html += '<?php
                        
                    foreach ($got as $value) {
                        $id = strval($value['id']);
                        $offen = str_replace("'", "", $value['offences']);
                        echo "<option id=\'$idi\' value=\'$id\' >".$offen."</option>";
                        
                    }
                
                ?>
                ';
                html += '</select>';
                html += '<button id="removeRow" type="button" class="btn btn-danger btn-sm">Remove</button>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</div>';

                $('#newRow').append(html);
        });

       // remove row
       $(document).on('click', '#removeRow', function () {
            $(this).closest('#row').remove();
        });
  

        </script>
		</body>

</html>
    


