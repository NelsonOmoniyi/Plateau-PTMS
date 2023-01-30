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
$sql = "SELECT * FROM payment_category WHERE code = 'DS'";
$res = $dbobject->db_query($sql);
// var_dump($res);
?>

<div class="modal-header" id="modal">
    <h4 class="modal-title" style="font-weight:bold">Verify Tax Identification Number</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Dealers.verify_TIN">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">

       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Tax Identification Number <span class="text-danger">*</span></label>
                    <input type="number" name="tax" class="form-control" placeholder="" maxlength="12">
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">C. A. C Number<span class="text-danger">*</span></label>
                    <input type="text" name="cac" autocomplete="off" class="form-control" required>
                </div>
           </div>
        </div>
        <div class="row">
           <div class="col-md-6">
                <label>Spare-Parts Dealership Category</label>
                
                <select name="cat" class="form-control" required>
                    <option hidden value="">:: SELECT DEALERSHIP CATEGORY::</option>
                    <?php
                        foreach ($res as $value) {
                            echo '<option value="'.$value['id'].'">'.$value['item'].'</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <br>
       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord();" class="btn btn-primary mb-1">Submit</button>
        
    </form>
    <p>I dont have a Tax Identification Number! <small><a class="btn btn-warning" onclick="regTin()">Generate Tin</a></small></p>
</div>
<script>

       
    function saveRecord()
    {
        
        $("#save_facility").text("Loading......");
        var dd = $("#form1").serialize();
        $.post("utilities.php",dd,function(re)
        {
            $("#save_facility").text("Save");
            
            var title = JSON.stringify(re.title);
            var firstname = JSON.stringify(re.firstname);
            var middlename = JSON.stringify(re.middleName);
            var surname = JSON.stringify(re.surname);
            var mobile = JSON.stringify(re.mobile);
            var address = JSON.stringify(re.address);
            var tin = JSON.stringify(re.tin);

            var name = JSON.stringify(re.name);
            var item = JSON.stringify(re.id);
            var price = JSON.stringify(re.price);
            console.log(re);

            if(re.response_code == 200)
                {$("#save_facility").text("Please Wait ........");
                    $("#save_facility").prop('disabled',true);
                    $("#err").css('color','green')
                    $("#err").html(re.response_message)
                    setTimeout(() => {
                        // $('#defaultModalPrimary').modal('hide');
                        // $("#defaultModallarge").modal('hide');
                       confirmationModal(title, firstname, middlename, surname, mobile, address, tin, name, item, price);
                    //getpage('tcp_list.php','page'); 
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
    function confirmationModal(title, firstname, middlename, surname, mobile, address, tin, name, item, price)
   {
    setTimeout(() => {
        getModal('setup/dealers_setup.php?title='+title+'&firstname='+firstname+'&middlename='+middlename+'&surname='+surname+'&mobile='+mobile+'&address='+address+'&tin='+tin+'&name='+name+'&item='+item+'&price='+price,'modal_div');
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
