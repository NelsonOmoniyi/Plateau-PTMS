<?php
include_once("libs/dbfunctions.php");
//var_dump($_SESSION);
?>
   <div class="card">
    <div class="card-header">
        <h5 class="card-title">Log List</h5>
        <h6 class="card-subtitle text-muted">The report contains log that have been setup in the system.</h6>
    </div>
    <div class="card-body">
<!--      <a class="btn btn-info" id="branch_btn" onclick="getModal('setup/branch_setup.php','modal_div')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Create New Branch</a>-->
       
       
       
        <div id="datatables-basic_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
<!--
            <div class="row">
                <div class="col-sm-3">
                    <label for="">Create Role</label>
                </div>
            </div>
-->
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <table id="page_list" class="table table-striped " >
                        <thead>
                            <tr role="row">
                                <th>S/N</th>
                                <th>Action By</th>
                                <th>Affected Table</th>
                                <th>Action</th>
                                <th>Affected Record ID</th>
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
<!--<script src="../js/enjoyhint.js"></script>-->
<script>
    
//    var enjoyhint_script_steps3 = [
//        {
//            'next .table-responsive' : 'You can find the record for the branch here',
//        },
//        {
//            'click #006' : 'Next step is to create your Users/Employees. Now click on the "User Setup" button',
//        }
//    ];
//    var enjoyhint_script_steps4 = [
//                {
//                    'click #branch_btn' : 'Click this button to start creating your branch',
//                },
//                
//            ];
    //    runTour(menuStates.branch)             
//    if(never_guess_var_branch == 1)
//        {
//            runTour(enjoyhint_script_steps3)
//        }else{
//            runTour(enjoyhint_script_steps4)
//        }
  var table;
  var editor;
  var op = "Log.logList";
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
          d.table_id = "<?php echo $_REQUEST['table_id']; ?>";
//          d.start_date = $("#start_date").val();
//          d.end_date = $("#end_date").val();
        }
      }
    });
  });

  function do_filter() {
    table.draw();
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