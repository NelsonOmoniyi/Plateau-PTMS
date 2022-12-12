<?php
include_once("../libs/dbfunctions.php");
include_once("../class/menu.php");
$dbobject = new dbobject();

$sql = "SELECT * FROM font_awsome ORDER BY code ";
$fonts = $dbobject->db_query($sql);


if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $operation = 'edit';
    $menu_id = $_REQUEST['menu_id'];
    $sql_menu = "SELECT * FROM menu WHERE menu_id = '$menu_id' LIMIT 1";
    $menu = $dbobject->db_query($sql_menu);
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
    <h4 class="modal-title" style="font-weight:bold">Menu Setup</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Menu.saveMenu">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       <input type="hidden" name="id" value="<?php echo $menu_id; ?>">
       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Menu Name</label>
                    <input type="text" autocomplete="off" name="menu_name" onkeyup="validateCode(this.value)" value="<?php echo $menu[0]['menu_name']; ?>"  class="form-control" required/>
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Menu URL</label>
                    <input type="text" name="menu_url" class="form-control" value="<?php echo $menu[0]['menu_url']; ?>" placeholder="" required>
                </div>
           </div>
           
       </div>
        <?php
            $rr = new menu();
            $p = $rr->loadParentMenu("");
        ?>
         <div class="row">
            <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Set Parent Menu</label>
                    <select name="parent_id" class="form-control" required>
                       <option hidden value="">Select a parent menu</option>
                       <option value="#" <?php echo ($menu[0]['parent_id'] == "#")?"selected":""; ?> >:: This menu is a parent menu::</option>
                       <?php
                        
                        if($p['response_code'] == "0")
                        {
                            foreach($p['data'] as $key)
                            {
                                $selected = ($key[0] == $menu[0]['parent_id'])?"selected":"";
                        ?>
                                <option <?php echo $selected; ?> value="<?php echo $key[0]; ?>"><?php echo $key[1]; ?></option>
                        <?php
                            }
                        }
                        ?>
                       
                    </select>
                </div>
           </div> 
           <div class="col-sm-6">
                  <label for="">Menu icon</label>
                  <select name="icon" onchange="display_icon(this.value)" id="icon" class="form-control">
                       <option value="">::SELECT ICON::</option>
                       <?php
                            foreach($fonts as $row)
                            {
                                $selected = ($cat[0]['icon'] == $row['code'])?"selected":"";
                                echo "<option $selected value='".$row['code']."'>".str_replace("fa fa-","",$row['code'])."</option>";
                            }
                        ?>
                   </select>
           </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div id="icon-display" align="center" style="font-size:20px">
                        <?php echo "<i class='fa ".$menu[0]['icon']."'></i>"; ?>
                    </div>
            </div>
        </div>
        
        
       
       <div id="err"></div>
        <button id="save_facility" onclick="saveRecord()" class="btn btn-primary mb-1">Submit</button>
        
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
            if(re.response_code == 0)
                {
                    $("#save_facility").attr("readonly", true);
                    $("#err").css('color','green')
                    $("#err").html(re.response_message)
                    getpage('menu_list.php','page');
                    
                }
            else
                {
                     $("#err").css('color','red')
                    $("#err").html(re.response_message)
                    $("#warning").val("0");
                    
                }
                
        },'json')
    }
    
//    function automatic()
//    {
//        if($("#auto").is(':checked'))
//        {
//            $("#auto_val").val(1)
//        }else{
//             $("#auto_val").val(0)
//        }
//    }
//    
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
</script>