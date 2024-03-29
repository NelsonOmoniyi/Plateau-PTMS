<?php
include_once("../libs/dbfunctions.php");
include_once("../class/sidenumber.php");
$dbobject = new dbobject();

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    
    $operation = 'edit';
}
else
{
    $operation = 'new';
}

$tin = isset($_REQUEST['tin'])?$_REQUEST['tin']:'';
$name = isset($_REQUEST['name'])?$_REQUEST['name']:'';
$website = isset($_REQUEST['website'])?$_REQUEST['website']:'';
$industry = isset($_REQUEST['industry'])?$_REQUEST['industry']:'';
$email = isset($_REQUEST['email'])?$_REQUEST['email']:'';
$phone = isset($_REQUEST['phone'])?$_REQUEST['phone']:'';
$address = isset($_REQUEST['address'])?$_REQUEST['address']:'';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Tax Identification Number</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/offence.css">
    </head>
    <body>
    <div class="page-content container" style='width:70%;'>
        <div class="page-header text-blue-d2">
            <h4 class="page-title .text-primary-d1">
            Tax Identification Number
            </h4>
            <div class="page-tools">
                <div class="action-buttons">
                    <a class="btn bg-white btn-light mx-1px text-95" href="#" data-title="Print" onclick='print();'>
                        <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                        Print
                    </a>
                </div>
            </div>
        </div>

        <div class="container px-0">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-center text-150">
                                <img src="../img/logo/plateau_logo.jpg" alt="Plateau State Ministry Of Works And Transport" width="100" height="100">
                                <br>
                                <span class="text-default-d3">Plateau State Ministry Of Works And Transport</span>
                            </div>
                        </div>
                    </div>

                    <hr class="row brc-default-l1 mx-n1 mb-4" />

                    <div class="row">
                        <div class="col-sm-6">
                            <div style="color: green; font-weight: bold;">
                            <span class="text-sm text-grey-m2 align-middle"><h1>Bearer:</h1></span>
                                <span class="text-600 text-110 text-blue align-middle"><?php echo $name ;?></span>
                            </div>
                            <hr>
                            <div class="text-grey-m2">
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Address :</small>
                                    <?php echo' '.$address ;?>
                                </div>
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Phone Number: </small>
                                    <?php echo''.$phone ;?>
                                </div>
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Website :</small>
                                    <?php echo' '.$website ;?>
                                </div>

                                <div class="my-1"><small style="color: blue; font-size: 20px;">Email Address :</small>
                                    <?php echo' '.$email ;?>
                                </div>
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Industry :</small>
                                    <?php echo' '.$industry ;?>
                                </div>
                                <div class="my-1"><small style="color: blue; font-size: 20px;">Tax Identification Number :</small>
                                    <?php echo' '.$tin ;?>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                        <hr />
                        <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                            Extra note such as company or payment information...
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" async defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js">

        <script>
            function print() {
                window.print();
                return false;
            }
        </script>
    </body>
</html>