<?php
include_once("libs/dbfunctions.php");
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
    <h4 class="modal-title" style="font-weight:bold">Get Tax Identification Number For Your Company!!</h4>
   
</div>

<div class="modal-body m-3 ">
    Register and get a new Tax Identification Number
<hr>
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Generate.genTIN">
     
       <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                    <input type="number" name="mobile" id="mobile" class="form-control" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" id="name" class="form-control" required>
                </div>
           </div>
       </div>
       <div class="row">
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">R.C Number <span class="text-danger">*</span></label>
                    <input type="text" name="rc_number" id="rc" class="form-control" required>
                </div>
           </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">State Of Origin <span class="text-danger">*</span></label>
                    <input type="text" name="soo" id="soo" class="form-control" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Occupation<span class="text-danger">*</span></label>
                    <input type="text" name="occupation" id="occupation" class="form-control" required>
                </div>
           </div>
       </div>
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Date Of Birth <span class="text-danger">*</span></label>
                    <input type="date" name="dob" id="dob" class="form-control" required>
                </div>
           </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Payer Address <span class="text-danger">*</span></label>
                    <input type="text" name="padd" id="padd" class="form-control" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Nationality<span class="text-danger">*</span></label>
                    <input type="text" name="nationality" id="nationality" value="Nigerian" readonly class="form-control" required>
                </div>
           </div>
       </div>
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Business Industry <span class="text-danger">*</span></label>
                    <input type="text" name="bi" id="bi" class="form-control" required>
                </div>
           </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                    <select class="form-control" id="mstatus" name="mstatus" required>
                        <option value="">::Select Marital Status::</option>
                        <option value="Married">Married</option>
                        <option value="Single">Single</option>
                        <option value="Divorced">Divorced</option>
                    </select>
                   
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label"> Employee Count <span class="text-danger"></span></label>
                    <input type="number" name="empcount" id="empcount" class="form-control">
                </div>
           </div>
       </div>
       <div class="row">
            <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Local Government Area <span class="text-danger">*</span></label>
                    <input type="text" name="lga" id="lga" class="form-control" required>
                </div>
           </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label"> Business Location <span class="text-danger">*</span></label>
                    <input type="text" name="blocation" id="blocation" class="form-control" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label"> State <span class="text-danger">*</span></label>
                    <input type="text" name="state" value="Plateau State" readonly id="state" class="form-control" required>
                </div>
           </div>
       </div>
       <div class="row">
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Industry<span class="text-danger">*</span></label>
                    <input type="text" name="industry" id="industry" class="form-control" required>
                </div>
           </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Address <span class="text-danger">*</span></label>
                    <input type="text" name="address" id="address" class="form-control" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Website<span class="text-danger"></span></label>
                    <input type="text" name="website" id="website" class="form-control">
                </div>
           </div>
       </div>
       <div class="row">
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Office Number<span class="text-danger"></span></label>
                    <input type="text" name="onumber" id="onumber" class="form-control">
                </div>
           </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
           </div>
           <div class="col-sm-4">
               <div class="form-group">
                    <label class="form-label">Home Town<span class="text-danger"></span></label>
                    <input type="text" name="hometown" id="hometown" class="form-control">
                </div>
           </div>
       </div>

       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord();" class="btn btn-primary mb-1">Submit</button>
        
    </form>
</div>
<script>
        function saveRecord(){
        
            $("#save_facility").text("Loading please wait...");
            $("#save_facility").prop('disabled', true);

        var dd = $("#form1").serialize();
        $.ajax({
            type: "POST",
            url: "utilities.php",
            data:dd,
            dataType:"json",
            success: function(re){
                $("#save_facility").text("Processed");
                console.log(re);
       
            var name = JSON.stringify(re.name);
            var address = JSON.stringify(re.address);
            var website = JSON.stringify(re.website);
            var phone = JSON.stringify(re.phone);
            var industry = JSON.stringify(re.industry);
            var email = JSON.stringify(re.email);
            var tin = JSON.stringify(re.tin);

            if(re.response_code == 0){ 
                $("#save_facility").text("Processed");
                    $("#save_facility").prop('disabled',true);
                    $("#err").css('color','green')
                    $("#err").html(re.response_message)
                    setTimeout(() => {
                        window.open('print/newTIN_reciept.php?tin='+tin+'&name='+name+'&address='+address+'&website='+website+'&phone='+phone+'&industry='+industry+'&email='+email, '_blank');
                        location.reload();
                    }, 1000);
                }else{
                    $("#save_facility").text("Submit");
                    $("#save_facility").prop('disabled', false);
                    $("#err").css('color','red')
                    $("#err").html(re.response_message)
                    $("#warning").val("0");
                }
        }, error: function(re){
                $("#err").css('color', 'red');
                $("#save_facility").prop('disabled', false);
                $("#err").html("Could not connect to server");
                $("#save_facility").text("Submit");
            }
        });
    }
    
    function fetchLga(el)
    {
        getRegions(el);
        $("#lga-fds").html("<option>Loading Lga</option>");
        $.post("utilities.php",{op:'Church.getLga',state:el},function(re){
            $("#lga-fds").empty();
            $("#lga-fds").html(re.state);
            
        },'json');
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
        $('#'+div).html("<h2>Loading....</h2>");
        $.post(url,{},function(re){
            $('#'+div).html(re);
        })
    }

</script>
