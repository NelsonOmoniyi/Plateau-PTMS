<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();

// $sql3 = "SELECT * FROM region";
// $region = $dbobject->db_query($sql3);

if(isset($_GET['op']))
{
    $sql = "SELECT * FROM log_details WHERE log_id = '$_REQUEST[id]'";
    $log = $dbobject->db_query($sql);
    $operation = "edit";
}
else
{
    $operation ="new";
}

?>
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold"><?php echo "Update made by ".$_REQUEST['username']." on ".date("F jS, Y",strtotime($_REQUEST['created'])); ?> </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
<table class="table">
    <thead>
        <tr><th>Field Name</th><th>Previous Data</th><th>Changed to</th></tr>
    </thead>
    <tbody>
        <?php
        foreach($log as $row)
        {
        ?>
            <tr>
                <td><?php echo $row['field_name']; ?></td>
                <td><?php echo $row['previous_data']; ?></td>
                <td><?php echo $row['current_data']; ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
</div>

<link rel="stylesheet" href="css/bootstrap-tagsinput.css" />
<script src="js/bootstrap-tagsinput.js"></script>
<script src="js/jquery.uploadfile.min.js"></script>
<script src="js/enjoyhint.js"></script>
<link rel="stylesheet" href="css/uploadfile.css">
<script>
//    var enjoyhint_script_steps1 = [
//        {
//            'click #modal_div' : 'Fill the form correctly and click the save button',
//        }
//    ];
     
    runTour(menuStates.branchSetup);
    function getlocation(val)
    {
        $.post("utilities.php",{op:"Branch.getLocation",region:val},function(ww){
            $("#state_id").html(ww)
        })
    }
    function displayVal(v)
    {
        $("#showval").text(v);
    }
    
    
    
    function saveRecord()
    {
        

        $("#defaultModalPrimary").block();
        var wer = $("#settings_forms").serialize();
        console.log(wer)
//        return true;
        $.post('utilities.php',wer,function(rr){
             $("#defaultModalPrimary").unblock();
            
             if(rr.response_code == 0)
                {
                    menuStates.branch.isSaved = 1;
                    swal({
                        text:rr.response_message,
                        icon:"success"
                    }).then((rs)=>{
                        getpage('branch_list.php','page');
                        $("#defaultModalPrimary").modal('hide');
                        
                    })
                    
                }else{
                    $(".server_message").text(rr.response_message);
                }
        },'json')
    }
</script>
<style>
    .ajax-upload-dragdrop, .ajax-file-upload-filename, .ajax-file-upload-statusbar{
                width: auto !important;
            }
    .label-info {
                background-color: #5bc0de;
            }
            .label {
                display: inline;
                padding: .2em .6em .3em;
                font-size: 75%;
                font-weight: 700;
                line-height: 1;
                color: #fff;
                text-align: center;
                white-space: nowrap;
                vertical-align: baseline;
                border-radius: .25em;
            }
            .bootstrap-tagsinput{
              width:100%;  
            }
            .bootstrap-tagsinput input {
                width:inherit;  
            }s
</style>