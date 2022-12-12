<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="img/png" href="img/favicon.png">

    <!-- All CSS -->
    <!-- fontAwesome -->
    <link rel="stylesheet" href="css/all.min.css">
    <!-- 7 stroke icon -->
    <link rel="stylesheet" href="css/pe-icon-7-stroke.css">
    <!-- Roysha icon -->
    <link rel="stylesheet" href="css/roysha-icons.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
	  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	  <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
    <!-- custom css if you need -->
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="assets/icon/feather/css/feather.css">

    <link rel="stylesheet" href="assets/css/font-awesome-n.min.css">
    <!-- Color Palette. simple uncommned if you need any color palette -->
    <!-- <link rel="stylesheet" href="css/switcher/css/red.css"> -->
    <style>
      body{
			font-family: 'Roboto Mono', monospace !important;
		}
    </style>
    <title>Plateau State PTMS</title>
  </head>
  <body class="home-one">
    
    <!-- Header start -->
    <header class="header">
      <div class="container d-flex align-items-center">
          <a class="logo" href="index.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="currentColor" class="bi bi-car-front" viewBox="0 0 16 16">
              <path d="M4 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm10 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM6 8a1 1 0 0 0 0 2h4a1 1 0 1 0 0-2H6ZM4.862 4.276 3.906 6.19a.51.51 0 0 0 .497.731c.91-.073 2.35-.17 3.597-.17 1.247 0 2.688.097 3.597.17a.51.51 0 0 0 .497-.731l-.956-1.913A.5.5 0 0 0 10.691 4H5.309a.5.5 0 0 0-.447.276Z"/>
              <path fill-rule="evenodd" d="M2.52 3.515A2.5 2.5 0 0 1 4.82 2h6.362c1 0 1.904.596 2.298 1.515l.792 1.848c.075.175.21.319.38.404.5.25.855.715.965 1.262l.335 1.679c.033.161.049.325.049.49v.413c0 .814-.39 1.543-1 1.997V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.338c-1.292.048-2.745.088-4 .088s-2.708-.04-4-.088V13.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-1.892c-.61-.454-1-1.183-1-1.997v-.413a2.5 2.5 0 0 1 .049-.49l.335-1.68c.11-.546.465-1.012.964-1.261a.807.807 0 0 0 .381-.404l.792-1.848ZM4.82 3a1.5 1.5 0 0 0-1.379.91l-.792 1.847a1.8 1.8 0 0 1-.853.904.807.807 0 0 0-.43.564L1.03 8.904a1.5 1.5 0 0 0-.03.294v.413c0 .796.62 1.448 1.408 1.484 1.555.07 3.786.155 5.592.155 1.806 0 4.037-.084 5.592-.155A1.479 1.479 0 0 0 15 9.611v-.413c0-.099-.01-.197-.03-.294l-.335-1.68a.807.807 0 0 0-.43-.563 1.807 1.807 0 0 1-.853-.904l-.792-1.848A1.5 1.5 0 0 0 11.18 3H4.82Z"/>
            </svg>
          </a>
          <nav class="primary-menu text-md-right">
            <a id="mobile-menu-toggler" href="#"><i class="fas fa-bars"></i></a>                            
            <ul>                
              <li class="current-menu-item"><a href="index.php">Home</a></li>
              <li class=""><a href="#">Offences Payment</a></li>
              <li><a href="login.html" class="login btn btn-outline btn-round"><span class="bh"></span> <span>login</span></a></li>
            </ul>
          </nav>
      </div>
    </header>
    <!-- Header end -->

    
    <!-- Banner section start -->
    <section class="banner v7">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 col-sm-12">
            <div class="ban-content">
              <h1>Kindly Note The Following Before Making Payment:</h1>
              <p>This Payment Is For Offences Only</p>
           
            </div>
          </div>

          <div class="col-lg-6 col-sm-12">
            <aside class="col-md-12">
              <article class="card">
                  <div class="card-body p-5">
                      <!--<form id="form1">-->
                          <div class="form-group">
                              <label for="payment_category">Offence Payment <span class="text-danger">*</span></label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fa fa-list"></i></span>
                                  </div>
                                  <select name="payment_category" id="payment_category" class="form-control">
                                      <option value="">Select Payment Category</option>
                                      <?php
                                          foreach($payment_options as $id=>$name)
                                          {
                                      ?>
                                      <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                      <?php
                                          }
                                      ?>
                                  </select>
                              </div> <!-- input-group.// -->
                          </div> <!-- form-group.// -->
                          <div class="form-group">
                              <label for="customer_name">Name <span class="text-danger">*</span></label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fa fa-user"></i></span>
                                  </div>
                                  <input type="text" id="customer_name" name="customer_name" required class="form-control" placeholder="Enter customer name" value="" title="Customer Name">
                              </div> <!-- input-group.// -->
                          </div> <!-- form-group.// -->
                          <div class="form-group">
                              <label for="customer_phone">Phone Number <span class="text-danger">*</span></label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fa fa-mobile"></i></span>
                                  </div>
                                  <input type="text" id="customer_phone" name="customer_phone" required class="form-control" placeholder="Enter customer phone number" value="" title="Customer Phone Number" maxlength="11">
                              </div> <!-- input-group.// -->
                          </div> <!-- form-group.// -->
                          <div class="form-group">
                              <label for="customer_email">Email Address <span class="text-danger">*</span></label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fa fa-address-book"></i></span>
                                  </div>
                                  <input type="email" id="customer_email" name="customer_email" required class="form-control" placeholder="Enter Customer Email Address" value="" title="Email Address">
                              </div> <!-- input-group.// -->
                          </div> <!-- form-group.// -->
                          <div class="row bdr2 align-items-center">
                              <div class="col-md-8 align-items-center" style="margin-left: 50px;">
                                  <button class="btn btn-primary btn-lg btn-block" type="button"  id="pay"> Pay With Monify <br>&nbsp;</button>
                              </div>
                          </div>
                          <div class="row">&nbsp;&nbsp;</div>
                  </div> <!-- card-body.// -->
              </article> <!-- card.// -->
            </aside> 
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