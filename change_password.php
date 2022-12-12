<?php
include_once("libs/dbfunctions.php");
$dbobject = new dbobject();


if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation  = 'edit';
    $id  = $_REQUEST['id'];
    $sql_collection_type = "SELECT * FROM collection_type WHERE id = '$id'";
    $collection_type     = $dbobject->db_query($sql_collection_type);
}else
{
    $operation = 'new';
}
?>
 <link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
<script>
    doOnLoad();
    var myCalendar;
function doOnLoad()
{
   myCalendar = new dhtmlXCalendarObject(["start_date"]);
   myCalendar.hideTime();
}
</script>
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Change Password</h5>
    </div>
    <div class="card-body">
        <form id="form1">
            <input type="hidden" name="op" value="Users.doPasswordChange">
            <input type="hidden" name="page" value="">
            <input type="hidden" name="username" value="<?php echo $_SESSION['username_sess']; ?>">
            <div class="form-group">
                <label>Enter current password</label>
                <input class="form-control form-control-lg" type="password" name="current_password" required placeholder="Enter your current password" />
            </div>
            <div class="form-group">
                <label>Enter new password</label>
                <input class="form-control form-control-lg" type="password" name="password" required placeholder="Enter your new password" />
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input class="form-control form-control-lg" name="confirm_password" type="password" required placeholder="Confirm your password" />
                <small>
                </small>
            </div>
            <div>


            </div>
            <div id="server_mssg"></div>
            <div class="text-center mt-3">
                <a href="javascript:saveRecord()" class="btn btn-lg btn-warning btn-block">Change Password</a>
            </div>
        </form>
    </div>
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
            if(re.response_code == 0)
                {
                    alert(re.response_message);
                    setTimeout(() => {
							window.location = 'logout.php';
						}, 2000);
                }
            else
                alert(re.response_message)
        },'json')
    }
    
            
  
</script>