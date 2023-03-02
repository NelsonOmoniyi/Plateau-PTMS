<?php
include_once("../libs/dbfunctions.php");
include_once("../class/menu.php");
$dbobject = new dbobject();

if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation = 'edit';
    $id = $_REQUEST['id'];
    $sql_ = "SELECT * FROM payment_category WHERE id = '$id' LIMIT 1";
    $exc = $dbobject->db_query($sql_);
}else
{
    $operation = 'new';
}
?>
 <link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<script>
    doOnLoad();
    var myCalendar;
function doOnLoad()
{
   myCalendar = new dhtmlXCalendarObject(["start_date"]);
    myCalendar.setSensitiveRange(null, "<?php echo date('Y-m-d') ?>");
   myCalendar.hideTime();
}
</script>
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">Payment Category Setup</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="paymentCategory.create">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       <input type="hidden" name="id" value="<?php echo $id; ?>">
       <div class="row">
           <div class="col-sm-12">
               <div class="form-group">
                    <label class="form-label">Item</label>
                    <input type="text" autocomplete="off" name="item" value="<?php echo $exc[0]['item'] ?>" placeholder="Item" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-12">
               <div class="form-group">
                    <label class="form-label">Short Code</label>
                    <input type="text" autocomplete="off" name="code" value="<?php echo $exc[0]['code'] ?>" placeholder="Short Code" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-12">
               <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input type="number" autocomplete="off" name="amount" value="<?php echo $exc[0]['amount'] ?>" placeholder="Amount" class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-12">
               <div class="form-group">
                    <label class="form-label">Link</label>
                    <input type="text" autocomplete="off" name="link" value="<?php echo $exc[0]['link'] ?>" placeholder="Link" class="form-control" required/>
                </div>
           </div>
          
           
       </div>
        
       
       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord()" class="btn btn-primary mb-1">Submit</button>
        
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
        
            if(re.response_code == 200){
                    $("#save_facility").text("Processed");
                    $("#save_facility").prop('disabled',true);
                    $("#err").css('color','green')
                    $("#err").html(re.response_message)
                    getpage('payment_category.php','page');
                    
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
    
    function display_icon(ee)
    {
        $("#icon-display").html(`<i class="${ee}"></i>`);
    }
</script>