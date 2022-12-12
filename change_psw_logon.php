<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from appstack.bootlab.io/pages-sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jul 2019 15:57:14 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
	<meta name="author" content="Bootlab">

	<title>The Lord's Chosen Charismatic Revival Church</title>
	<link rel="stylesheet" href="css/parsley.css">
	<link rel="preconnect" href="http://fonts.gstatic.com/" crossorigin>
	<link rel="icon" href="img/icon.png" sizes="32x32" />
	<!-- PICK ONE OF THE STYLES BELOW -->
	<!-- <link href="css/classic.css" rel="stylesheet"> -->
	<!-- <link href="css/corporate.css" rel="stylesheet"> -->
	<!-- <link href="css/modern.css" rel="stylesheet"> -->

	<!-- BEGIN SETTINGS -->
	<!-- You can remove this after picking a style -->
	<style>
		body {
			opacity: 0;
		}
	</style>
	<script src="js/settings.js"></script>
	<!-- END SETTINGS -->
<!-- Global site tag (gtag.js) - Google Analytics -->
</head>

<body style="background: #64d18c">
	<main class="main d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row h-100">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2" style="color:#000">Welcome to The Lord's Chosen Charismatic Revival Church</h1>
							<p class="lead">
								Change Password
							</p>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="m-sm-4">
									<div class="text-center">
										<img src="img/icon.png" alt="Chris Wood" class="img-fluid rounded-circle" width="132" height="132" />
									</div>
									<form id="form1">
										<input type="hidden" name="op" value="Users.doPasswordChange">
										<input type="hidden" name="page" value="first_login">
										<input type="hidden" name="username" value="<?php echo $_REQUEST['username']; ?>">
										<div class="form-group">
											<label>Enter current password</label>
											<input class="form-control form-control-lg" type="password"  name="current_password" required placeholder="Enter your current password" />
										</div>
										<div class="form-group">
											<label>Enter new password</label>
											<input class="form-control form-control-lg" type="password"  name="password" required placeholder="Enter your new password" />
										</div>
										<div class="form-group">
											<label>Confirm Password</label>
											<input class="form-control form-control-lg" name="confirm_password" type="password" required placeholder="Confirm your password" />
											<small>
          </small>
										</div>
										<div>

											
										</div>
										<div id="server_mssg"></div>
										<div class="text-center mt-3">
											<a href="javascript:sendLogin('form1')" class="btn btn-lg btn-warning btn-block">Change Password</a>
										</div>
									</form>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</main>

	<!-- <script src="js/app.js"></script> -->
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.blockUI.js"></script>
	<script src="js/parsely.js"></script>
	
	<script src="js/sweet_alerts.js"></script>
	<script src="js/main.js"></script>
	<script>
		function sendLogin(id)
		{
			var forms = $('#'+id);
			forms.parsley().validate();
			if(forms.parsley().isValid())
			{
				var data = $("#"+id).serialize();
				$.post("utilities_default.php",data,function(res)
				{
					var response = JSON.parse(res);
					if(response.response_code == 0)
					{
						$("#server_mssg").html(response.response_message);
//						setTimeout(() => {
//							window.location = 'home.php';
//						}, 2000);
					}
					else
					{
						$("#server_mssg").html(response.response_message);
					}
				});
			}
		}
	</script>
</body>


<!-- Mirrored from appstack.bootlab.io/pages-sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 26 Jul 2019 15:57:14 GMT -->
</html>