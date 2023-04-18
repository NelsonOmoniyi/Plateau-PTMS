<?php
include_once("../libs/dbfunctions.php");
include_once("../class/sidenumber.php");
$dbobject = new dbobject();
$id  = isset($_REQUEST['id'])? $_REQUEST['id'] :'';
$table  = isset($_REQUEST['table'])? $_REQUEST['table'] :'';

$check = $dbobject->db_query("SELECT * FROM vehicle_sidenumbers WHERE side_number='$id'");
$firstname = $check[0]['firstname'];
$middlename = $check[0]['middlename'];
$surname = $check[0]['surname'];
$fullname = "$firstname $middlename $surname";

// $item_code = $check[0]['item_code'];
$vehicle_typeid = $check[0]['vehicle_typeid'];
$amount = $dbobject->getitemlabel('vehicle_type', 'id', $vehicle_typeid, 'renew');
?>
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">Trade Preview</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3" id="modal">
    <div class="container-fluid">
        <form id="form" onsubmit="return false"> 
        <input type="hidden" name="op" value="Sidenumber.renew">
        <input type="hidden" name="amount" value="<?php echo "".number_format($amount,2).""; ?>">
        <input type="hidden" name="sidenumber" value="<?php echo $id ?>">
        
        <table class="table table-bordered table-striped">
            <tr><th>Fullname</th><td><?php echo $fullname ?></td></tr>
            <tr><th>Mobile Number</th><td><?php echo $check[0]['mobile'] ?></td></tr>
            <tr><th>Address</th><td><?php echo $check[0]['address'] ?></td></tr>
            <tr><th>Vehicle Make</th><td><?php echo $check[0]['vehicle_make'] ?></td></tr>
            <tr><th>Plate Number</th><td><?php echo $check[0]['plate_number'] ?></td></tr>
            <tr><th>Licence Operators</th><td><?php echo $check[0]['licence_operators'] ?></td></tr>
            <tr><th>Vehicle Color</th><td><?php echo $check[0]['vehicle_color'] ?></td></tr>
            <tr><th>Vehicle Model</th><td><?php echo $check[0]['vehicle_model'] ?></td></tr>
            <tr><th>Amount</th><td><?php echo "".number_format($amount,2).""; ?></td></tr>

        </table>
        
    </div>

</div>
<div class="modal-footer">
    <div id="server_mssg"></div>
    <button class="btn btn-primary" id="save_facility" onclick="saveRecord()">Make Payment</button>

</div>
<script>
        function saveRecord(){
        $("#save_facility").text("Loading please wait...");
        $("#save_facility").prop('disabled', true);
        var dd = $("#form").serialize();
        $.ajax({
            type: "POST",
            url: "utilities.php",
            data:dd,
            dataType:"json", 
            success: function(re){
                $("#save_facility").text("Processed");
                console.log(re);
                var redirect = re.redirect_url;
                if(re.response_code == 200 && re.redirect_url == null){ 
                    $("#save_facility").text("Make Payment");
                    $("#save_facility").prop('disabled',false);
                    $("#server_mssg").css('color','red')
                    $("#server_mssg").html("Could not load payment interface. Please try again");

                }else if(re.response_code == 200 && re.redirect_url !== null){
            
                $("#save_facility").text("Processed");
                $("#save_facility").prop('disabled',true);
                getpage('sidenumber_list.php','page');
                $("#defaultModalPrimary").modal('hide');
                PrintPage(redirect);
            }else if(re.status == "failed"){
                $("#save_facility").text("Submit");
                $("#save_facility").prop('disabled', false);
                $("#err").css('color','red');
                $("#err").html(re.message);
                getpage('sidenumber_list.php',"page");
            }else{
                    $("#save_facility").text("Submit");
                    $("#save_facility").prop('disabled', false);
                    $("#server_mssg").css('color','red')
                    $("#server_mssg").html(re.response_message)
                    $("#warning").val("0");
                }
        }, error: function(re){
                $("#server_mssg").css('color', 'red');
                $("#save_facility").prop('disabled', false);
                $("#server_mssg").html("Could not connect to server");
                $("#save_facility").text("Submit");
            }
        });












    }
</script>