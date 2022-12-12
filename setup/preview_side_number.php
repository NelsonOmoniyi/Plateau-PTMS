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
        function saveRecord()
    {
        $("#save_facility").text("Loading......");
        var dd = $("#form").serialize();
        $.post("utilities.php",dd,function(re)
        {
            console.log(re);
            $("#save_facility").text("Save");
            if(re.response_code == 0)
                {
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'green','font-weight':'bold'});
                    getpage('sidenumber_list.php','page');
                    setTimeout(()=>{
                        $('#defaultModalPrimary').modal('hide');
                    },1000)
                }
            else
                {
                    $("#server_mssg").text(re.response_message);
                     $("#server_mssg").css({'color':'red','font-weight':'bold'});
                }
                
        },'json');
    }
</script>