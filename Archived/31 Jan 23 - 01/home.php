<?php
require_once('libs/dbfunctions.php');
require_once('class/menu.php');
header("Cache-Control: no-cache;no-store, must-revalidate");
$dbobject = new dbobject();
$menu = new Menu();
$check = $dbobject->db_query("SELECT * FROM userdata WHERE username='$_SESSION[username_sess]'");
$photo = $check[0]['photo'];
$menu_list = $menu->generateMenu($_SESSION['role_id_sess']);
$menu_list = $menu_list['data'];

//Time to time out in seconds
$inact_min = $dbobject->getitemlabel("parameter","parameter_name",'inactivity_time','parameter_value');
//convert by multiplying by 3600
// var_dump($inact_min);	
$inact_val = ($inact_min > 0) ? $inact_min*60*60 : 10*60*60; 

$sql = "SELECT firstname,lastname FROM userdata WHERE username = '$_SESSION[username_sess]' LIMIT 1 ";
$user_det = $dbobject->db_query($sql);

$sqlpending    = "SELECT side_number FROM vehicle_sidenumbers WHERE status = '0' ";
	$result = $dbobject->db_query($sqlpending);
	$count0 = count($result);
	
$sqlprocessed    = "SELECT side_number FROM vehicle_sidenumbers WHERE status = '1' ";
	$result = $dbobject->db_query($sqlprocessed);
	$count1 = count($result);

$sqldeclined    = "SELECT side_number FROM vehicle_sidenumbers WHERE status = '2' ";
	$result = $dbobject->db_query($sqldeclined);
	$count2 = count($result);

$sqlDSL    = "SELECT school_name FROM driving_sch_form WHERE status = '1' ";
	$result = $dbobject->db_query($sqlDSL);
	$count3 = count($result);

	$tp_company = $dbobject->getItemCount("transport_companies","status","1","portal_id");
	$tp_company_unprocessed = $dbobject->getItemCount("transport_companies","status","0","portal_id");

	$driving_school = $dbobject->getItemCount("driving_sch_form","status","1","portal_id");
	$driving_school_unprocessed = $dbobject->getItemCount("driving_sch_form","status","0","portal_id");

	$mechanic_garrage = $dbobject->getItemCount("mech_garrage","status","1","portal_id");
	$mechanic_garrage_unprocessed = $dbobject->getItemCount("mech_garrage","status","0","portal_id");

	$spare_part = $dbobject->getItemCount("spare_parts","status","1","portal_id");
	$spare_part_unprocessed = $dbobject->getItemCount("spare_parts","status","0","portal_id");

	$dealership = $dbobject->getItemCount("dealership","status","1","portal_id");
	$dealership_unprocessed = $dbobject->getItemCount("dealership","status","0","portal_id");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Admin Dashboard">
	<meta name="author" content="Bootlab">

	<title>Admin </title>
	
	<link rel="preconnect" href="http://fonts.gstatic.com/" crossorigin>

	<style>
		body {
			opacity: 0;
		}
		.font-size{
			font-size:24px;
		}
	</style>
	
	<script src="js/jquery.min.js"></script>
	<script src="js/settings.js"></script>
	
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-120946860-6');
</script></head>

<body>
	<div class="wrapper">
		<nav class="sidebar">
			<div class="sidebar-content ">
				<a class="sidebar-brand bg-white" href="home.php">
          <!-- <i class="align-middle text-danger" data-feather="box"></i> -->
		  <img src="img/logo/plateau_logo.jpg" alt="Plateau State Ministry Of Transport" class="img-fluid rounded-circle" style="height:70px;"/>
          <span class="align-middle text-danger" style="font-size: 15px; font-weight:bold;"></span>
        </a>
			<hr style="margin-top:0px;">
				<ul class="sidebar-nav">
					<h3><a href="home.php" class="sidebar-header text-white font-weight-bold">Dashboard</a></h3>
					<hr>
					<?php
                        foreach($menu_list as $row)
                        {
                        ?>
                            <a href="#k<?php echo $row['menu_id']; ?>" data-toggle="collapse" class="sidebar-link">
                                <i class="align-middle" data-feather="sliders"></i> <span class="align-middle"><?php echo $row['menu_name']; ?></span>
                            </a>
						<?php
                            if($row['has_sub_menu'] == true)
                                {
									echo '<ul id="k'.$row['menu_id'].'"  class="sidebar-dropdown list-unstyled collapse">';
									foreach($row['sub_menu'] as $row2)
                                {
                                    if($row2['menu_id'] == "026")
                                {
                              
                        ?>
							<li class="sidebar-item"><a class="sidebar-link" href="javascript:getpage('<?php echo $row2['menu_url']; ?>','page')"><?php echo $row2['name']; ?></a>
							</li>
						<?php

							}
							else
							{

						?>
							<li class="sidebar-item">
                                <a class="sidebar-link" href="javascript:getpage('<?php echo $row2['menu_url']; ?>','page')">
                                <?php echo $row2['name']; ?>
                               	</a>
                            </li>
						<?php
                        }
                        }
                        	echo '</ul>';
                        }
                        ?>
                        <?php
                        }
                        ?>
				</ul>
				<div class="sidebar-bottom d-none d-lg-block">
					<div class="media">
						<img class="rounded-circle mr-3" src="https://us.123rf.com/450wm/anatolir/anatolir2011/anatolir201105528/159470802-jurist-avatar-icon-flat-style.jpg?ver=6" width="40" height="40">
						<div class="media-body">
							<h5 class="mb-1"><?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?></h5>
							<div>
                                <button class="btn btn-danger btn-block" onclick="window.location='index.php'">Logout</button>
                            </div>
						</div>
					</div>
				</div>

			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light bg-white">
				<a class="sidebar-toggle d-flex mr-2">
          <i class="hamburger align-self-center"></i>
        </a>

				<a href="javascript:void(0)" class="d-flex mr-2">
                   <?php $state_loc = ":".$dbobject->getitemlabel('lga','stateid',$_SESSION['state_id_sess'],'State'); ?>
                    Your Role: &nbsp; <span style="font-weight:bold; color:#000"><?php echo $_SESSION['role_id_name'];"</small>"; ?></span>
                </a>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown">
                <i class="align-middle" data-feather="settings"></i>
              </a>

			  <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
                <img src="img/profile_photo/<?php echo $photo; ?>" class="avatar img-fluid rounded-circle mr-1" alt=""/> <span class="text-dark"><?php echo $_SESSION['firstname_sess'].' '.$_SESSION['lastname_sess']; ?></span>
              </a>
							<div class="dropdown-menu dropdown-menu-right">
								<!-- <a class="dropdown-item" href="profile.php"><i class="align-middle mr-1" data-feather="user"></i> Profile</a> -->
								<a class="dropdown-item" href="javascript:getpage('profile.php','page')">Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.php">Sign out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content" id="page">
				<div class="container-fluid p-0">

					<div class="row">
					
						<div class="col-sm-3 d-flex">
							<div class="card flex-fill bg-success">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<!-- <i class="feather-lg text-warning" data-feather="activity"></i> -->
											<i class="fa fa-bus font-size text-light"></i>
										</div>
										<div class="media-body">
											<?php
												echo '<h3 class="mb-2 text-light">'.$tp_company.'</h3>';
											?>
											<!-- <h3 class="mb-2">2.562</h3> -->
											<div class="mb-0 text-light">Processed Transport Company</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 d-flex">
							<div class="card flex-fill bg-primary">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<!-- <i class="feather-lg text-danger" data-feather="x-octagon"></i> -->
											<i class="fa fa-car font-size text-white"></i>
										</div>
										<div class="media-body">
											<?php
												echo '<h3 class="mb-2 text-white">'.$driving_school.'</h3>';
											?>
											<!-- <h3 class="mb-2">2.562</h3> -->
											<div class="mb-0 text-white">Processed Driving School Licence </div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 d-flex">
							<div class="card flex-fill bg-danger">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<!-- <i class="feather-lg text-primary" data-feather="shopping-bag"></i> -->
											<i class="fa fa-wrench font-size text-light"></i>
										</div>
										<div class="media-body">
											<?php
												echo '<h3 class="mb-2 text-light">'.$mechanic_garrage.'</h3>';
											?>
											<div class="mb-0 text-light">Processed Mechanic Garrage</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 d-flex">
							<div class="card flex-fill bg-warning text-light">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<!-- <i class="feather-lg text-info" data-feather="dollar-sign"></i> -->
											<i class="fa fa-cogs font-size text-info text-light"></i>
										</div>
										<div class="media-body">
										<?php
												echo '<h3 class="mb-2 text-light">'.$spare_part.'</h3>';
											?>
											<div class="mb-0">Processed Spare Parts</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3 d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
											<i class="feather-lg text-danger" data-feather="x-octagon"></i>
										</div>
										<div class="media-body">
											<?php
												echo '<h3 class="mb-2">'.$tp_company_unprocessed.'</h3>';
											?>
											<!-- <h3 class="mb-2">2.562</h3> -->
											<div class="mb-0">Unprocessed Transport Company</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
										<i class="feather-lg text-danger" data-feather="x-octagon"></i>
										</div>
										<div class="media-body">
											<?php
												echo '<h3 class="mb-2">'.$driving_school_unprocessed.'</h3>';
											?>
											<div class="mb-0">Unprocessed Driving School Licence</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
										<i class="feather-lg text-danger" data-feather="x-octagon"></i>
										</div>
										<div class="media-body">
										<?php
												echo '<h3 class="mb-2">'.$mechanic_garrage_unprocessed.'</h3>';
											?>
											<div class="mb-0">Unprocessed Mechanic Garrage</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-3 d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
										<i class="feather-lg text-danger" data-feather="x-octagon"></i>
										</div>
										<div class="media-body">
										<?php
												echo '<h3 class="mb-2">'.$spare_part_unprocessed.'</h3>';
											?>
											<div class="mb-0">Unprocessed Spare Parts</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
					<div class="col-sm-6 d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4 bg-light">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
										<i class="feather-lg text-danger" data-feather="x-octagon"></i>
										</div>
										<div class="media-body">
										<?php
												echo '<h3 class="mb-2">'.$dealership.'</h3>';
											?>
											<div class="mb-0">processed Dealership</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 d-flex">
							<div class="card flex-fill">
								<div class="card-body py-4 bg-light">
									<div class="media">
										<div class="d-inline-block mt-2 mr-3">
										<i class="feather-lg text-danger" data-feather="x-octagon"></i>
										</div>
										<div class="media-body">
										<?php
												echo '<h3 class="mb-2">'.$dealership_unprocessed.'</h3>';
											?>
											<div class="mb-0">Unprocessed Dealership</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-12 col-lg-6 d-flex">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<span class="badge badge-primary float-right">Per Month</span>
									<h5 class="card-title mb-0">Offence Count</h5>
								</div>
								<div class="card-body">
									<div class="chart chart-lg">
										<canvas id="chartjs-dashboard-line"></canvas>
									</div>
								</div>
							</div>
						</div>
					<!-- </div>
					<div class="row"> -->
						<div class="col-12 col-lg-6 col-xl-6 d-flex">
							<div class="card flex-fill w-100">
								<div class="card-header">
								<span class="badge badge-primary float-right">Per Month</span>
									<h5 class="card-title mb-0">Offences Generated Revenue</h5>
									
								</div>
								<div class="card-body d-flex w-100">
									<div class="align-self-center chart chart-lg">
										<canvas id="chartjs-dashboard-bar"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-left">
							<!-- <ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="#">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#">Help Center</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#">Terms of Service</a>
								</li>
							</ul> -->
						</div>
						<div class="col-6 text-right">
							<p class="mb-0">
							Copyright © Powered By Access Solutions LTD <?php echo " ".date("Y");?>
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="defaultModalPrimary" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" id="modal_div">
            <div class="modal-header">
            <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit ":""; ?> Setup<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              
            </div>
            
            </div>
        </div>
        </div>
    </div>

	<!-- large modal -->
	<div class="modal fade" id="defaultModallarge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="modal_div2">
            <div class="modal-header">
            <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit ":""; ?> Setup<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              
            </div>
            
            </div>
        </div>
        </div>
    </div>

	
	<script src="js/app.js"></script>
	<script src="js/parsely.js"></script>
	
	<script src="js/sweet_alerts.js"></script>
	<script src="js/main.js"></script>
    <script src="js/jquery.blockUI.js"></script>
	
<?php
$date = DATE("Y-m-d");
$sql1 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '1' AND YEAR(created) = '$date'";
$jan = $dbobject->db_query($sql1);
// var_dump($res);

$sql2 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '2' AND YEAR(created) = '$date'";
$feb = $dbobject->db_query($sql2);
// var_dump($res);

$sql3 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '3' AND YEAR(created) = '$date'";
$mar = $dbobject->db_query($sql3);
// var_dump($res);

$sql4 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '4' AND YEAR(created) = '$date'";
$april = $dbobject->db_query($sql4);
// var_dump($res);

$sql5 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '5' AND YEAR(created) = '$date'";
$may = $dbobject->db_query($sql5);
// var_dump($res);

$sql6 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '6' AND YEAR(created) = '$date'";
$jun = $dbobject->db_query($sql6);
// var_dump($res);

$sql7 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '7' AND YEAR(created) = '$date'";
$july = $dbobject->db_query($sql7);
// var_dump($res);

$sql8 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '8' AND YEAR(created) = '$date'";
$aug = $dbobject->db_query($sql8);
// var_dump($res);

$sql9 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '9' AND YEAR(created) = '$date'";
$sep = $dbobject->db_query($sql9);
// var_dump($res);

$sql10 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '10' AND YEAR(created) = '$date'";
$oct = $dbobject->db_query($sql10);
// var_dump($oct['offence_id']);

$sql11 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '11' AND YEAR(created) = '$date'";
$nov = $dbobject->db_query($sql11);
// var_dump($nov);

$sql12 = "SELECT COUNT(offence_id) as counter FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '12' AND YEAR(created) = '$date'";
$dec = $dbobject->db_query($sql12);
// var_dump($res);

// SUM ===========================================================================================================

$sql1p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '1' AND YEAR(created) = '$date'";
$janp = $dbobject->db_query($sql1p);
// var_dump($res);

$sql2p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '2' AND YEAR(created) = '$date'";
$febp = $dbobject->db_query($sql2p);
// var_dump($res);

$sql3p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '3' AND YEAR(created) = '$date'";
$marp = $dbobject->db_query($sql3p);
// var_dump($res);

$sql4p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '4' AND YEAR(created) = '$date'";
$aprilp = $dbobject->db_query($sql4p);
// var_dump($res);

$sql5p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '5' AND YEAR(created) = '$date'";
$mayp = $dbobject->db_query($sql5p);
// var_dump($res);

$sql6p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '6' AND YEAR(created) = '$date'";
$junp = $dbobject->db_query($sql6p);
// var_dump($res);

$sql7p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '7' AND YEAR(created) = '$date'";
$julyp = $dbobject->db_query($sql7p);
// var_dump($res);

$sql8p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '8' AND YEAR(created) = '$date'";
$augp = $dbobject->db_query($sql8p);
// var_dump($res);

$sql9p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '9' AND YEAR(created) = '$date'";
$sepp = $dbobject->db_query($sql9p);
// var_dump($res);

$sql10p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '10' AND YEAR(created) = '$date'";
$octp = $dbobject->db_query($sql10p);
// var_dump($oct['offence_id']);

$sql11p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '11' AND YEAR(created) = '$date'";
$novp = $dbobject->db_query($sql11p);
// var_dump($nov);

$sql12p = "SELECT SUM(trans_amount) as price FROM tb_payment_confirmation WHERE bank_code = 'Offences' AND Month(created) = '12' AND YEAR(created) = '$date'";
$decp = $dbobject->db_query($sql12p);
// var_dump($res);

?>
	<script>
		$(function() {
			// Bar chart
			new Chart(document.getElementById("chartjs-dashboard-bar"), {
				type: "bar",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Revenue",
						backgroundColor: window.theme.warning,
						borderColor: window.theme.warning,
						hoverBackgroundColor: window.theme.warning,
						hoverBorderColor: window.theme.warning,
						data: [<?php echo $janp[0]['price'] ?>, <?php echo $febp[0]['price'] ?>, <?php echo $marp[0]['price'] ?>, <?php echo $aprilp[0]['price'] ?>, <?php echo $mayp[0]['price'] ?>, <?php echo $junp[0]['price'] ?>, <?php echo $julyp[0]['price'] ?>, <?php echo $augp[0]['price'] ?>, <?php echo $sepp[0]['price'] ?>, <?php echo $octp[0]['price'] ?>, <?php echo $novp[0]['price'] ?>, <?php echo $decp[0]['price'] ?>]
					}]
				},
				options: {
					maintainAspectRatio: true,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								display: true
							},
							stacked: false,
							ticks: {
								stepSize: 500
							}
						}],
						xAxes: [{
							barPercentage: .75,
							categoryPercentage: .5,
							stacked: false,
							gridLines: {
								color: "transparent"
							}
						}]
					}
				}
			});
		});
	</script>

	<script>
		$(function() {
			// Line chart
			new Chart(document.getElementById("chartjs-dashboard-line"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Number Of Offences",
						fill: true,
						backgroundColor: "transparent",
						borderColor: window.theme.primary,
						data: [<?php echo $jan[0]['counter'] ?>, <?php echo $feb[0]['counter'] ?>, <?php echo $mar[0]['counter'] ?>, <?php echo $april[0]['counter'] ?>, <?php echo $may[0]['counter'] ?>, <?php echo $jun[0]['counter'] ?>, <?php echo $july[0]['counter'] ?>, <?php echo $aug[0]['counter'] ?>, <?php echo $sep[0]['counter'] ?>, <?php echo $oct[0]['counter'] ?>, <?php echo $nov[0]['counter'] ?>, <?php echo $dec[0]['counter'] ?>]
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						intersect: false
					},
					hover: {
						intersect: true
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							reverse: true,
							gridLines: {
								color: "rgba(0,0,0,0.05)"
							}
						}],
						yAxes: [{
							ticks: {
								stepSize: 50
							},
							display: true,
							borderDash: [5, 5],
							gridLines: {
								color: "rgba(0,0,0,0)",
								fontColor: "#fff"
							}
						}]
					}
				}
			});
		});
	</script>

</body>

</html>