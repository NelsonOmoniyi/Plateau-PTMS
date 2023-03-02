<?php
include_once("../libs/dbfunctions.php");
include_once("../class/menu.php");
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
    <h4 class="modal-title" style="font-weight:bold">Verify TIN</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Sidenumber.verTIN">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">

       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Tax Identification Number <span class="text-danger">*</span></label>
                    <input type="text" name="tax" class="form-control" placeholder="" maxlength="12">
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Plate Number<span class="text-danger">*</span></label>
                    <input type="text" name="plate" class="form-control" required>
                </div>
           </div>
       </div>
       
       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord();" class="btn btn-primary mb-1">Submit</button>
        
    </form>
    <p>I dont have a Tax Identification Number! <small><a class="btn btn-sm btn-warning" onclick="regTin()">Generate TIN</a></small></p>
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
        
            var title = JSON.stringify(re.title);
            var firstname = JSON.stringify(re.firstname);
            var middlename = JSON.stringify(re.middleName);
            var surname = JSON.stringify(re.surname);
            var mobile = JSON.stringify(re.mobile);
            var address = JSON.stringify(re.address);
            var tin = JSON.stringify(re.tin);
            // veh details
            var make = JSON.stringify(re.make);
            var taxPayer = JSON.stringify(re.taxPayer);
            var model = JSON.stringify(re.model);
            var color = JSON.stringify(re.color);
            var chasis = JSON.stringify(re.chasis);
            var plate = JSON.stringify(re.plate);

            if(re.response_code == 200){
                $("#save_facility").text("Processed");
                    $("#save_facility").prop('disabled',true);
                    $("#err").css('color','green')
                    $("#err").html(re.response_message)
                    setTimeout(() => {
                       confirmationModal(title, firstname, middlename, surname, mobile, address, tin, make, taxPayer, model, color, chasis, plate);
                    getpage('sidenumber_list.php','page'); 
                    }, 1000);
                }else{
                    $("#save_facility").text("Submit");
                    $("#save_facility").prop('disabled', false);
                    $("#err").css('color','red')
                    $("#err").html(re.response_message)
                    $("#warning").val("0");
                }
        },error: function(re){
                $("#err").css('color', 'red');
                $("#save_facility").prop('disabled', false);
                $("#err").html("Could not connect to server");
                $("#save_facility").text("Submit");
            }
        });
    }
    function confirmationModal(title, firstname, middlename, surname, mobile, address, tin, make, taxPayer, model, color, chasis, plate)
   {
    setTimeout(() => {
        getModal('setup/sidenumber_setup.php?title='+title+'&firstname='+firstname+'&middlename='+middlename+'&surname='+surname+'&mobile='+mobile+'&address='+address+'&tin='+tin+'&make='+make+'&taxPayer='+taxPayer+'&model='+model+'&color='+color+'&chasis='+chasis+'&plate='+plate,'modal_div');
    }, 2000);
   }
   function regTin(){
    getModal('generate_tinc.php', 'modal_div');
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


    
</script>
