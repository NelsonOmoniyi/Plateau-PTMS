<?php
include_once("../libs/dbfunctions.php");
include_once("../class/menu.php");
$dbobject = new dbobject();

$title = isset($_REQUEST['title'])?$_REQUEST['title']:'';
$firstname = isset($_REQUEST['firstname'])?$_REQUEST['firstname']:'';
$middlename = isset($_REQUEST['middlename'])?$_REQUEST['middlename']:'';
$surname = isset($_REQUEST['surname'])?$_REQUEST['surname']:'';
$mobile = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'';
$address = isset($_REQUEST['address'])?$_REQUEST['address']:'';
$tin = isset($_REQUEST['tin'])?$_REQUEST['tin']:'';
$name = isset($_REQUEST['name'])?$_REQUEST['name']:'';
$item = isset($_REQUEST['item'])?$_REQUEST['item']:'';
$price = isset($_REQUEST['price'])?$_REQUEST['price']:'';
// echo $item;
if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation = 'edit';
}else
{
    $operation = 'new';
}
$form_id_pre = "TU";
$current_timestamp = time();
$portal_id = $form_id_pre.$current_timestamp;
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">Transport Union Setup</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="TPU.saveTU">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       <input type="hidden" name="cat" id="name" value="">
       <input type="hidden" name="amount" id="amount" value="">
       <input type="hidden" name="item_code" id="code" value="">
       <input type="hidden" name="port" value="<?php echo $portal_id; ?>">
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Tax Number</label>
                    <?php echo "<input type='text' name='tin' class='form-control' value=$tin readonly>" ?>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Business Name</label>
                    <input type="text" name="business_name" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Registered Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo $address ;?>" placeholder="" required>
                </div>
           </div>
       </div>
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Owner Name</label>
                    <input type="text" name="owner_name" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">CAC Registration No:</label>
                    <input type="text" name="cac_reg_no" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Owner Email Address</label>
                    <input type="email" name="email_add" class="form-control" required/>
                </div>
           </div>           
       </div>
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Owner Phone Number</label>
                    <?php echo "<input type='text' name='phone' class='form-control' value=$mobile readonly>" ?>
                </div>
           </div>
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Garrage Capacity</label>
                    <input type="number" name="cap" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Passport Upload</label>
                    <input type="file" name="upload" id="upload" class="form-control" placeholder="" required>
                </div>
           </div>
        </div>

        <div class="row">
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Declaration <span class="text-danger">*</span></label>
                    <p><span><input type="radio" name="agreement" class="form-control-sm" placeholder="" value="1" required></span> I agree that the information provided in this application is true and binding on me.</p>
                </div>
           </div>
       </div>
       

       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord()" class="btn btn-primary mb-1">Submit</button>
        
    </form>
</div>
<script>
    $(document).ready(function () {
        var price = toNumberString(<?php echo $price ?>);
        // var code = toString();
        // console.log(code);
        $('#name').val(<?php echo $name ?>);        
        $('#amount').val(price);
        $('#code').val(<?php echo $item ?>);        
    });
    function saveRecord()
    {
        $("#save_facility").text("Loading......");
        var dd = $("#form1").serialize();
        $.post("utilities.php",dd,function(re)
        {
            $("#save_facility").text("Save");
            // console.log(re);
            if(re.response_code == 0)
                {
                    $("#save_facility").text("Please Wait ........");
                    $("#save_facility").prop('disabled',true);
                    $("#err").css('color','green')
                    $("#err").html(re.response_message)
                    d = re.port_id;
                    // console.log(d);
                    setTimeout(() => {                       
                        PrintPage(d);
                    }, 1000); 
                  
                    $('#defaultModalPrimary').modal('hide');
                        $("#defaultModallarge").modal('hide');
                }
            else
                {
                    $("#err").css('color','red')
                    $("#err").html(re.response_message)
                    $("#warning").val("0");
                }
        },'json')
    }
    function PrintPage(d) {
            // window.alert(d)
            // window.open('print/transport_union.php?id='+d, '_blank');
            // getpage('trans_union_list.php','page');
		
	}


    function display_icon(ee)
    {
        $("#icon-display").html(`<i class="${ee}"></i>`);
    }
    function toNumberString(num) { 
        if (Number.isInteger(num)) { 
            return num + ".00"
        } else {
            return num.toString(); 
        }
    }
</script>