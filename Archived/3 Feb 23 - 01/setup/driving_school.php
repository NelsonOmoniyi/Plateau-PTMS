<?php
include_once("../libs/dbfunctions.php");
include_once("../class/menu.php");
$dbobject = new dbobject();

$title = str_replace('"', '', isset($_REQUEST['title'])?$_REQUEST['title']:'');
$firstname = str_replace('"', '', isset($_REQUEST['firstname'])?$_REQUEST['firstname']:'');
$middlename = str_replace('"', '', isset($_REQUEST['middlename'])?$_REQUEST['middlename']:'');
$surname = str_replace('"', '', isset($_REQUEST['surname'])?$_REQUEST['surname']:'');
$mobile = str_replace('"', '', isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'');
$address = str_replace('"', '', isset($_REQUEST['address'])?$_REQUEST['address']:'');
$tin = str_replace('"', '', isset($_REQUEST['tin'])?$_REQUEST['tin']:'');
// echo $tin;
if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation = 'edit';
}else
{
    $operation = 'new';
}
$form_id_pre = "DSL";
$current_timestamp = time();
$portal_id = $form_id_pre.$current_timestamp;
?>
<link rel="stylesheet" href="css/uploadfile.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">Driving School Setup</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="DrivingSchool.saveDSD">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       <input type="hidden" name="cat" value="Driving Schools Registration">
       <input type="hidden" name="amount" value="50,000.00">
       <input type="hidden" name="item_code" value="4295">
       <input type="hidden" name="port" value="<?php echo $portal_id; ?>">
       <div class="row">
            <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Tax Identification Number</label>
                    <?php echo "<input type='text' name='tin' class='form-control' value=$tin readonly>" ?>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">School Name/Center</label>
                    <input type="text" name="school_name" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Registered School Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo $address ;?>" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Proprietor Name</label>
                    <input type="text" name="proprietor_name" class="form-control" required/>
                </div>
           </div>
       </div>
       <div class="row">
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">CAC Registration No:</label>
                    <input type="text" name="cac_reg_no" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Proprietor Email Address</label>
                    <input type="email" name="email_add" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Proprietor Phone Number</label>
                    <input type="text" name="phone" value="<?php echo $mobile; ?>" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Select Category</label>
                    <select name="category" class="form-control" required>
                        <option value="">::SELECT CATEGORY::</option>
                        <option value="A">Category (A)</option>
                        <option value="B">Category (B)</option>
                    </select>
                </div>
           </div>
       </div>

       <!-- add -->
       <div class="row">
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Work Benches:</label>
                    <input type="number" name="work_benches" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Drawings</label>
                    <input type="number" name="drawings" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Drops</label>
                    <input type="number" name="drops" value="" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">First Aid</label>
                    <select name="first_aid" class="form-control" required>
                        <option value="">::SELECT CATEGORY::</option>
                        <option value="yes">Yes </option>
                        <option value="no">NO </option>
                    </select>
                </div>
           </div>
       </div>

       <div class="row">
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Magnetic Bound:</label>
                    <input type="number" name="magnetic_bound" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Diagrams</label>
                    <input type="number" name="diagrams" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Charts</label>
                    <input type="number" name="charts" value="" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Licence Required</label>
                    <select name="licence_required" class="form-control" required>
                        <option value="">::SELECT CATEGORY::</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
           </div>
       </div>

       <div class="row">
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Overhead Projector:</label>
                    <input type="number" name="overhead_projector" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">High Way Code</label>
                    <input type="number" name="highway_code" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Road Traffic Regulations</label>
                    <input type="number" name="road_traffic_regulations" value="" class="form-control" placeholder="" required>
                </div>
           </div>
           <div class="col-sm-3">
               <div class="form-group">
                    <label class="form-label">Course Syllabus</label>
                    <input type="number" name="course_syllabus" value="" class="form-control" placeholder="" required>
                </div>
           </div>
       </div>
       <!-- add -->
       <div class="row">
           <div class="col-sm-3">
                <div class="form-group">
                    <label class="form-label">Class Room Accomodation</label>
                    <select name="classroom_accomodation" class="form-control" required>
                        <option>::SELECT TYPE::</option>
                        <option value="rented">Rented</option>
                        <option value="make-shift">Make Shift</option>
                        <option value="parmanent">Parmanent</option>
                    </select>
                </div>
           </div>
           <div class="col-sm-3">
                <div class="form-group">
                    <label class="form-label">Class Room Quantity</label>
                    <select name="classrooms" class="form-control" required>
                        <option value="">::SELECT QUANTITY::</option>
                        <option value="0-10">0-10</option>
                        <option value="11-20">11-20</option>
                        <option value="21-30">21-30</option>
                        <option value="31-40">31-40</option>
                        <option value="41-50">41-50</option>
                        <option value="51-60">51-60</option>
                        <option value="61-70">61-70</option>
                        <option value="71-80">71-80</option>
                        <option value="81-90">81-90</option>
                        <option value="91-100">91-100</option>
                    </select>
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Association Membership Number</label>
                    <input type="number" name="amn" class="form-control" placeholder="" required>
                </div>
           </div>
        </div>
        <div class="row">
            <div class="col-sm-12" id="add">
                <div class="form-group">
                    <div id="extraupload"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-1 text-right">
                <!-- <label class="form-label">Declaration <span class="text-danger">*</span></label> -->
                <input type="radio" name="agreement" class="form-control-sm" placeholder="" value="1" required>
            </div>
           <div class="col-sm-11">
               <div class="form-group">
                    
                    <p> I agree that the information provided in this application is true and binding on me.</p>
                </div>
           </div>
       </div>
       

       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord()" class="btn btn-primary mb-1">Submit</button>
        
    </form>
</div>
<script src="js/jquery.uploadfile.min.js"></script>
<script>
    var coverImg = $("#extraupload").uploadFile({
            url:"utilities.php",
            fileName:"upfile",
            showPreview:true,
            previewHeight: "100px",
            previewWidth: "100px",
            maxFileCount:2,
            multiple:false,
            allowedTypes:"jpg,png,jpeg",
            maxFileSize:1000000,
            autoSubmit:false,
            returnType:"json",
            onSubmit:function(files)
            {
                $.blockUI({message:"Saving information. Kindly wait.."});
            },
            dynamicFormData: function()
            {
                
                var dd = $("#form1").serialize();
                return dd;
            },
            onSuccess:function(files,data,xhr,pd)
            {
                $.unblockUI();
                $("#save_facility").text("Save");
                if(data.response_code == 0)
                {
                    $('.server_message').css('color','green');
                    $('.server_message').html(data.response_message);
                        d = data.port_id;
                        // console.log(d);
                        getpage('./driving_school_list.php','page');
                        setTimeout(() => {                       
                            PrintPage(d);
                        }, 1000); 
                        $('#defaultModalPrimary').modal('hide');
                        $("#defaultModallarge").modal('hide');
                    // $("#save_facility").attr("data-dismiss","modal");
                }else{
                    
                    $('.server_message').css('color','red');
                    $('.server_message').html(data.response_message);
                    coverImg.reset();
                    $('.ajax-file-upload-red').click();
                }                       
            }
        }
        // 
    );

    function saveRecord()
    {
        if(coverImg.selectedFiles == 0)
        {
               
            $("#save_facility").text("Loading......");
            var dd = $("#form1").serialize();

            $.ajax({
                type: "POST",
                url: "utilities.php",
                data:dd,
                dataType:"json",
                success: function(re){
                    if(re.response_code == 0){
                        $("#save_facility").text("Please Wait ........");
                        $("#save_facility").prop('disabled',true);
                        $("#err").css('color','green')
                        $("#err").html(re.response_message)
                        d = re.port_id;
                        // console.log(d);
                        getpage('./driving_school_list.php','page');
                        setTimeout(() => {                       
                            PrintPage(d); 
                        }, 1000); 
                        $('#defaultModalPrimary').modal('hide');
                        $("#defaultModallarge").modal('hide');
                        
                    }else{
                        $("#err").css('color','red')
                        $("#err").html(re.response_message)
                        $("#warning").val("0");
                        $("#save_facility").text("Submit");
                    }
                },  
            error: function(re){
                $("#err").css('color', 'red');
                $("#save_facility").prop('disabled', false);
                $("#err").html("Could not connect to server");
                $("#save_facility").text("Submit");
            }

        });
        
        }else{
            coverImg.startUpload();
        }
    }
    function PrintPage(d) {
            
            // window.open('./print/printDSL.php?id='+d, '_blank');
            window.open('receipt/special_trade_receipt.php?table=driving_sch_form&pid='+d, '_blank');
           
		
	}


    function display_icon(ee)
    {
        $("#icon-display").html(`<i class="${ee}"></i>`);
    }
</script>