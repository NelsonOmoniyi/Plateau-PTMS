<?php
include_once("../libs/dbfunctions.php");
include_once("../class/menu.php");
$dbobject = new dbobject();
    // var_dump($_REQUEST);
    $title = isset($_REQUEST['title'])?$_REQUEST['title']:'';
    $firstname = isset($_REQUEST['firstname'])?$_REQUEST['firstname']:'';
    $middlename = isset($_REQUEST['middlename'])?$_REQUEST['middlename']:'';
    $surname = isset($_REQUEST['surname'])?$_REQUEST['surname']:'';
    $mobile = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'';
    $address = isset($_REQUEST['address'])?$_REQUEST['address']:'';
    $tin = isset($_REQUEST['tin'])?$_REQUEST['tin']:'';

    // veh details
    $make = isset($_REQUEST['make'])?$_REQUEST['make']:'';
    $taxPayer = isset($_REQUEST['taxPayer'])?$_REQUEST['taxPayer']:'';
    $model = isset($_REQUEST['model'])?$_REQUEST['model']:'';
    $color = isset($_REQUEST['color'])?$_REQUEST['color']:'';
    $chasis = isset($_REQUEST['chasis'])?$_REQUEST['chasis']:'';
    $plate = isset($_REQUEST['plate'])?$_REQUEST['plate']:'';

    
        // var_dump($res);
    // echo "$title ";
    // echo "$firstname ";
    // echo "$middlename ";
    // echo "$surname ";
    // echo "$mobile ";
    // echo "$address ";
    // echo "$tin ";


if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation = 'edit';
    
}else
{
    $operation = 'new';
}

?>

<div class="modal-header" id="modal">
    <h4 class="modal-title" style="font-weight:bold">Side Number Setup</h4>
    
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>

<div class="modal-body m-3 ">
<p>Please Complete Any Missing Field!</p>
<hr>
<h5>Biodata</h5>
<hr>
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Sidenumber.AuthData">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
     
       <div class="row">
            
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control"value=<?php echo $title ?> required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                    <input type="text" name="firstname" id="first" class="form-control" value=<?php echo $firstname ?> required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Middle Name <span class="text-danger">*</span></label>
                    <input type="text" name="middlename" id="middle" class="form-control" value=<?php echo $middlename ?> required>
                </div>
           </div>
       </div>
       <div class="row">
           
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Surname <span class="text-danger">*</span></label>
                    <input type="text" name="surname" id="surname" class="form-control" value=<?php echo $surname ?> required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <input type="text" name="mobile" id="mobile" class="form-control" value=<?php echo $mobile ?> required>
                </div>
           </div>
           <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" id="address" class="form-control" value=<?php echo $address ?> required>
                </div>
           </div>
       </div>
       <div class="row">
        <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">Tax Identification Number <span class="text-danger">*</span></label>
                    <input type="text" name="tin" id="tin" class="form-control" value=<?php echo $tin ?> required>
                </div>
        </div>
       </div>
       <div class="row">
        <div class="col-12">
        <h5>Vehicle Information</h5>
        <hr>
        </div>
       
       </div>
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Chasis Number<span class="text-danger">*</span></label>
                    <input type="text" name="chasis" id="chasis" class="form-control" value=<?php echo $chasis ?> required>
                </div>
           </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Vehicle Model <span class="text-danger">*</span></label>
                    <input type="text" name="model" id="model" class="form-control" value=<?php echo $model ?> required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Vehicle Make<span class="text-danger">*</span></label>
                    <input type="text" name="make" id="make" class="form-control" value=<?php echo $make ?> required>
                </div>
           </div>
       </div>
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Vehicle Color <span class="text-danger">*</span></label>
                    <input type="text" name="color" id="color" class="form-control" value=<?php echo $color ?> required>
                </div>
           </div>
           
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Plate Number<span class="text-danger">*</span></label>
                    <input type="text" name="plate" id="platenumber" class="form-control" value=<?php echo $plate ?>  required>
                </div>
           </div>
           <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Trade Union Reg<span class="text-danger">*</span></label>
                    <select name="vehicle_typeid" class="form-control" id="vcat" required >
                        <option value="">::SELECT VEHICLE TYPE::</option>
                        <?php
                            $sql = "SELECT * FROM `vehicle_type` ORDER BY vehicle_name ASC";
                            $res = $dbobject->db_query($sql);
                            foreach($res as $val)
                            {
                                echo "<option id='".$val['track']."' value='".$val['id']."'>".$val['vehicle_name']."</option>";
                            }
                        ?>
                    </select>
                </div>
           </div>
           
           <!-- select -->
           
       </div>

        <div class="row">
           <!-- select -->
           
           <div class="col-sm-12" id="RC">
                
           </div>
       </div>

       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord();" class="btn btn-primary mb-1">Submit</button>
        
    </form>
</div>
<script>


       
        function saveRecord()
    {
        
        $("#save_facility").text("Loading......");
        var dd = $("#form1").serialize();
        $.post("utilities.php",dd,function(re)
        {
            $("#save_facility").text("Save");
            
            console.log(re);
            var redirect = re.redirect_url;
            if(re.response_code == 200)
                { 
                    $("#save_facility").text("Please Wait...... ");
                    $("#save_facility").attr('disabled',true);
                    $("#err").css('color','green')
                    $("#err").html(re.message)

                    PrintPage(redirect);
                }
            else
                {

                    $("#err").css('color','red')
                    $("#err").html(re.message)
                    $("#warning").val("0");
                }
        },'json')
   

    }
    function PrintPage(redirect) {
   
   window.open(redirect, '_blank');
   
}
    
   function printSDN(tin)
   {  
        setTimeout(() => {
            window.open('./print/printSN.php?tin='+JSON.stringify(tin), '_blank');
        }, 2000);
   }
    function fetchLga(el)
    {
        getRegions(el);
        $("#lga-fds").html("<option>Loading Lga</option>");
        $.post("utilities.php",{op:'Church.getLga',state:el},function(re){
            $("#lga-fds").empty();
            $("#lga-fds").html(re.state);
            
        },'json');
    //        $.blockUI();
    }
    function getRegions(state_id)
    {
        $("#church_region_select").html("<option>Loading....</option>");
        $.post("utilities.php",{op:'Church.getRegions',state:state_id},function(re){
            $("#church_region_select").empty();
            $("#church_region_select").html(re);
            
        });
    }
    
    function fetchAccName(acc_no)
    {
        if(acc_no.length == 10)
            {
                var account  = acc_no;
                var bnk_code = $("#bank_name").val();
                $("#acc_name").text("Verifying account number....");
                $("#account_name").val("");
                $.post("utilities.php",{op:"Church.getAccountName",account_no:account,bank_code:bnk_code},function(res){
                    
                    $("#acc_name").text(res);
                    $("#account_name").val(res);
                });
            }else{
                $("#acc_name").text("Account Number must be 10 digits");
            }
        
    }
    function display_icon(ee)
    {
        $("#icon-display").html(`<i class="${ee}"></i>`);
    }

    function getModal(url,div)
    {
    //        alert('dfd');
        $('#'+div).html("<h2>Loading....</h2>");
    //        $('#'+div).block({ message: null });
        $.post(url,{},function(re){
            $('#'+div).html(re);
        })
    }

    setTimeout(() => {

        if ($("#platenumber").val() == " " || $("#platenumber").val() == "null" || $("#platenumber").val() == "") {
            console.log('Plate Number Field Does NOT Contain Data');
        } else {
            $("#platenumber").attr('readonly', true)
        }
        if ($("#first").val() == " " || $("#first").val() == "null" || $("#first").val() == "") {
            console.log('First Name Field Does NOT Contain Data');
        } else {
            $("#first").attr('readonly', true)
        }
        if ($("#address").val() == " " || $("#address").val() == "null" || $("#address").val() == "") {
            console.log('Address Field Does NOT Contain Data');
        } else {
            $("#address").attr('readonly', true)
        }
        if ($("#title").val() == " " || $("#title").val() == "null" || $("#title").val() == "") {
            console.log('Title Field Does NOT Contain Data');
        } else {
            $("#title").attr('readonly', true)
        }
        if ($("#surname").val() == " " || $("#surname").val() == "null" || $("#surname").val() == "") {
            console.log('Surname Field Does NOT Contain Data');
        } else {
            $("#surname").attr('readonly', true)
        }
        if ($("#mobile").val() == " " || $("#mobile").val() == "null" || $("#mobile").val() == "") {
            console.log('Mobile Number Field Does NOT Contain Data');
        } else {
            $("#mobile").attr('readonly', true)
        }
        if ($("#tin").val() == " " || $("#tin").val() == "null" || $("#tin").val() == "") {
            console.log('TIN Field Does NOT Contain Data');
        } else {
            $("#tin").attr('readonly', true)
        }
        if ($("#middle").val() == " " || $("#middle").val() == "null" || $("#middle").val() == "") {
            console.log('Middle Name Field Does NOT Contain Data');
        } else {
            $("#middle").attr('readonly', true)
        }
        if ($("#chasis").val() == " " || $("#chasis").val() == "null" || $("#chasis").val() == "") {
            console.log('Chasis Field Does NOT Contain Data');
        } else {
            $("#chasis").attr('readonly', true)
        }
        if ($("#model").val() == " " || $("#model").val() == "null" || $("#model").val() == "") {
            console.log('Model Field Does NOT Contain Data');
        } else {
            $("#model").attr('readonly', true)
        }
        if ($("#make").val() == " " || $("#make").val() == "null" || $("#make").val() == "") {
            console.log('Make Field Does NOT Contain Data');
        } else {
            $("#make").attr('readonly', true)
        }
        if ($("#color").val() == " " || $("#color").val() == "null" || $("#color").val() == "") {
            console.log('Color Field Does NOT Contain Data');
        } else {
            $("#color").attr('readonly', true)
        }
    }, 1000);

    $( document ).ready(function() {
        $("#vcat").change(function() {
            var val = $("#vcat").val();
            var op = "Sidenumber";
            $.ajax({
                'type':'POST',
                'url': 'utilities.php?op=Sidenumber.ajax&id='+val,        
                'cache': false,
                'dataType':'json',
                'contentType': false,
                'processData': false,
                'data': '',        
                'success': function (response) {
                    console.log(response.data);
                    var info = response.data;
                        if (info == '') {
                            var html = '';
                            html += '<div class="form-group">';
                            html += '<label class="form-label">Registration Center<span class="text-danger">*</span></label>';
                            html += '<select name="licence_operators" class="form-control">';
                            html += '<option value="">::SELECT REGISTRATION CENTER::</option>';
                            html += '<?php
                                    $sql = "SELECT * FROM licence_operators";
                                    $result = $dbobject->db_query($sql);
                                    foreach($result as $row)
                                    {
                                        // echo "<option id=\'$idi\' value=\'$offen\' >$offen</option>";
                                        echo "<option value=\'$row[short_code]\'>$row[name](".$row[short_code].")</option>";
                                    }
                            ?>
                            ';
                            html += '</select>';
                            html += '</div>';

                            $('#RC').html(html);
                        } else {
                            
                        
                            var html = '';
                            html += '<div class="form-group">';
                            html += '<label class="form-label">Registration Center<span class="text-danger">*</span></label>';
                            html += '<select name="licence_operators" class="form-control">';
                            html += '<option value="">::SELECT REGISTRATION CENTER::</option>';
                            html += '<?php
                                    $sql = "SELECT * FROM licence_operators WHERE track = 'GN'";
                                    $result = $dbobject->db_query($sql);
                                    foreach($result as $row)
                                    {
                                        // echo "<option id=\'$idi\' value=\'$offen\' >$offen</option>";
                                        echo "<option value=\'$row[short_code]\'>$row[name](".$row[short_code].")</option>";
                                    }
                            ?>
                            ';
                            html += '</select>';
                            html += '</div>';

                            $('#RC').html(html);
                        }     
                }
            });
            
        });
    });
    
    
</script>
