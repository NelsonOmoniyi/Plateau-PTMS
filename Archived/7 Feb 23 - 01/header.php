<?php 
header("Cache-Control: no-cache;no-store, must-revalidate"); 
?>
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Favicon icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="img/icons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="img/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="img/icons/favicon-16x16.png">
	<!-- <link rel="manifest" href="img/icons//site.webmanifest"> -->
	<!-- <link rel="shortcut icon" type="image/png" href="./img/favicon.png"> -->

	<!-- All CSS -->
	<!-- fontAwesome -->
	<link rel="stylesheet" href="./css/all.min.css">
	<!-- 7 stroke icon -->
	<link rel="stylesheet" href="./css/pe-icon-7-stroke.css">
	<!-- Roysha icon -->
	<link rel="stylesheet" href="./css/roysha-icons.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/owl.carousel.min.css">
	<link rel="stylesheet" href="./css/jquery.fancybox.min.css">
	<link rel="stylesheet" href="./css/nice-select.css">
	<link rel="stylesheet" href="./css/style.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">

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

		.navbar-toggler-icon{
			background: !important;
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

<body class="demo-page">

	<!-- Preloader -->
	<div id="preloader">
		<div id="status"></div>
	</div>


				<!-- <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="background:rgba(58,129,81,0.5)!important;">
				<a class="navbar-brand" href="#"><img src="img/logo/plateau_logo.jpg" alt="" class="img-fluid rounded-circle" style="height:80px; min-width:80px;" /></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon text-light"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNavDropdown">
					<ul class="navbar-nav">
					<li class="nav-item active">
						<a class="nav-link text-light" href="index.php">Home <span class="sr-only">(current)</span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-light" href="index.php#services">Services</a>
					</li>
					<li class="nav-item">
						<a class="nav-link text-light" href="#">Contact Us</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link text-success" href="login.html" id="navbarDropdownMenuLink" style="background-color:white; border-radius:5px;" aria-haspopup="true" aria-expanded="false">
						Login
						</a>
						
					</li>
					</ul>
				</div>
				</nav> -->
			
<!-- 
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="img/logo/plateau_logo.jpg" alt="" class="img-fluid rounded-circle" style="max-width:50px" /> PSMOT</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php#services">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.html">Login</a>
        </li>
       
      </ul>
    </div>
  </div>
</nav>
 -->


<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="img/logo/plateau_logo.jpg" alt="" class="img-fluid rounded-circle" style="max-width:50px" /></a>
    <button class="navbar-toggler btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="border: 2px solid green;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Plateau State Ministry of Transport</a>
        </li>
        
      </ul>
	  <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php#services">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.html">Login</a>
        </li>
		
       
      </ul>
    </div>
  </div>
</nav>
	<!-- Header start -->
	
	<div class="sticky-top bg-white hidden-spacer"> </div>
	<!-- Header end -->