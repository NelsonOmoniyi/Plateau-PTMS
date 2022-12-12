<?php
include_once("libs/dbfunctions.php");
$dbobject = new dbobject();
//$icon_json = file_get_contents("../icon.json");
//$icon_arr  = json_decode($icon_json,TRUE);
//foreach($icon_arr as $row)
//{
//    $code = $row['code'];
//    $name = $row['name'];
//    $sql  = "INSERT INTO icons (code,name,created) VALUES('$code','$name',NOW())";
//    $dbobject->db_query($sql,false);
//}
//var_dump($_SESSION);
?>
   <div class="card">
    <div class="card-header">
        <h5 class="card-title">Menu List</h5>
        <h6 class="card-subtitle text-muted">The report contains Menus that have been setup in the system.</h6>
    </div>
    <div class="card-body">
      <a class="btn btn-warning" onclick="getModal('setup/menu_setup.php','modal_div')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Create Menu</a>
        <div id="datatables-basic_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <div class="col-sm-3">
                    <label for=""></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <table id="page_list" class="table table-striped nowrap" >
                        <thead>
                            <tr role="row" style="background-color: grey; color: white;">
                                <th>S/N</th>
<!--                                <th>Menu ID</th>-->
                                <th>Menu Name</th>
                                <th>Menu URL</th>
                                <th>Parent ID</th>
<!--                                <th>Menu Level</th>-->
<!--                                <th>Menu Order</th>-->
                                <th>Menu Icon</th>
                                <th>Action</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script src="../js/sweet_alerts.js"></script>-->
<!--<script src="../js/jquery.blockUI.js"></script>-->
<script>
  var table;
  var editor;
  var op = "Menu.menuList";
  $(document).ready(function() {
    table = $("#page_list").DataTable({
      processing: true,
      columnDefs: [{
        orderable: false,
        targets: 0
      }],
      serverSide: true,
      paging: true,
      oLanguage: {
        sEmptyTable: "No record was found, please try another query"
      },

      ajax: {
        url: "utilities.php",
        type: "POST",
        data: function(d, l) {
          d.op = op;
          d.li = Math.random();
//          d.start_date = $("#start_date").val();
//          d.end_date = $("#end_date").val();
        }
      }
    });
  });

  function do_filter() {
    table.draw();
  }
    
    function deleteMenu(id)
    {
        let cnf = confirm("Are you sure you want to delete menu?");
        if(cnf == true)
            {
                $.blockUI();
                $.post("utilities.php",{op:"Menu.deleteMenu",menu_id:id},function(re){
                    $.unblockUI();
                    alert(re.response_message);
                    getpage('menu_list.php',"page");
                },'json')
            }
        
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