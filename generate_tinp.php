<?php
include_once("libs/dbfunctions.php");
include_once("class/menu.php");
$dbobject = new dbobject();

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation = 'edit';
    
}else
{
    $operation = 'new';
}

?>

<div class="modal-header" id="modal">
    <h4 class="modal-title" style="font-weight:bold">Get Your Tax Identification Number Now!!</h4>
   
</div>

<div class="modal-body m-3 ">
<hr>
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Sidenumber.AuthData">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
     
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Plate Number<span class="text-danger">*</span></label>
                    <input type="text" name="plate" id="platenumber" class="form-control" value=<?php echo $plate ?>  required>
                </div>
           </div>
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
       </div>
       <div class="row">
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Middle Name <span class="text-danger">*</span></label>
                    <input type="text" name="middlename" id="middle" class="form-control" value=<?php echo $middlename ?> required>
                </div>
           </div>
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
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" id="address" class="form-control" value=<?php echo $address ?> required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Tax Identification Number <span class="text-danger">*</span></label>
                    <input type="text" name="tin" id="tin" class="form-control" value=<?php echo $tin ?> required>
                </div>
           </div>
           <!-- select -->
           
       </div>

        <div class="row">
           <!-- select -->
           <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Vehicle Category<span class="text-danger">*</span></label>
                    <select name="vehicle_typeid" class="form-control" required>
                        <option value="">::SELECT VEHICLE TYPE::</option>
                        <?php
                            $sql = "SELECT * FROM `vehicle_type` ORDER BY vehicle_name ASC";
                            $res = $dbobject->db_query($sql);
                            foreach($res as $val)
                            {
                                echo "<option value='".$val[id]."'>".$val[vehicle_name]."</option>";
                            }
                        ?>
                    </select>
                </div>
           </div>
           <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Registration Center<span class="text-danger">*</span></label>
                    <select name="licence_operators" class="form-control">
                    <option value="">::SELECT REGISTRATION CENTER::</option>
                    <?php
                        $sql = "SELECT * FROM licence_operators ";
                        $result = $dbobject->db_query($sql);
                        foreach($result as $row)
                        {
                            echo "<option value='".$row[short_code]."'>".$row[name]."</option>";
                        }
                    ?>
                    </select>
                </div>
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
            // console.log(re);
          
            if(re.response_code == 0)
                { 
                    $("#save_facility").text("Please Wait...... ");
                    $("#save_facility").attr('disabled',true);
                    $("#err").css('color','green')
                    $("#err").html(re.response_message)
                    setTimeout(() => {
                        printSDN(re.tin);
                    getpage('sidenumber_list.php','page'); 
                    }, 1000);
                    
                }
            else
                {

                    $("#err").css('color','red')
                    $("#err").html(re.response_message)
                    $("#warning").val("0");
                }
                
        },'json')
   

    }
    
   function printSDN(tin)
   {  
        setTimeout(() => {
            window.open('setup/printSN.php?tin='+JSON.stringify(tin), '_blank');
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

        if ($("#platenumber").val() == " " || $("#platenumber").val() == "null") {
            console.log('Plate Number Field Does NOT Contain Data');
        } else {
            $("#platenumber").attr('readonly', true)
        }
        if ($("#first").val() == " " || $("#first").val() == "null") {
            console.log('First Name Field Does NOT Contain Data');
        } else {
            $("#first").attr('readonly', true)
        }
        if ($("#address").val() == " " || $("#address").val() == "null") {
            console.log('Address Field Does NOT Contain Data');
        } else {
            $("#address").attr('readonly', true)
        }
        if ($("#title").val() == " " || $("#title").val() == "null") {
            console.log('Title Field Does NOT Contain Data');
        } else {
            $("#title").attr('readonly', true)
        }
        if ($("#surname").val() == " " || $("#surname").val() == "null") {
            console.log('Surname Field Does NOT Contain Data');
        } else {
            $("#surname").attr('readonly', true)
        }
        if ($("#mobile").val() == " " || $("#mobile").val() == "null") {
            console.log('Mobile Number Field Does NOT Contain Data');
        } else {
            $("#mobile").attr('readonly', true)
        }
        if ($("#tin").val() == " " || $("#tin").val() == "null") {
            console.log('TIN Field Does NOT Contain Data');
        } else {
            $("#tin").attr('readonly', true)
        }
        if ($("#middle").val() == " " || $("#middle").val() == "null") {
            console.log('Middle Name Field Does NOT Contain Data');
        } else {
            $("#middle").attr('readonly', true)
        }
        if ($("#chasis").val() == " " || $("#chasis").val() == "null") {
            console.log('Chasis Field Does NOT Contain Data');
        } else {
            $("#chasis").attr('readonly', true)
        }
        if ($("#model").val() == " " || $("#model").val() == "null") {
            console.log('Model Field Does NOT Contain Data');
        } else {
            $("#model").attr('readonly', true)
        }
        if ($("#make").val() == " " || $("#make").val() == "null") {
            console.log('Make Field Does NOT Contain Data');
        } else {
            $("#make").attr('readonly', true)
        }
        if ($("#color").val() == " " || $("#color").val() == "null") {
            console.log('Color Field Does NOT Contain Data');
        } else {
            $("#color").attr('readonly', true)
        }
    }, 1000);
    
</script>
