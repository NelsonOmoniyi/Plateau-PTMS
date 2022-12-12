<?php
include_once("libs/dbfunctions.php");
//var_dump($_SESSION);
?>
  <style>
    
    </style>
   <div class="card">
    <div class="card-header">
        <h5 class="card-title">Vehicle List</h5>
        <h6 class="card-subtitle text-muted">The report contains Vehicle Types that have been setup in the system.</h6>
    </div>
    <div class="card-body">
     <div class="row">
         <div class="col-sm-2">
             <a class="btn btn-warning" onclick="getModal('setup/vehicle_setup.php','modal_div')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Add Vehicle Type</a>
         </div>
     </div>
      
        <div id="datatables-basic_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <div class="col-sm-3">
                    <label for=""></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <table id="page_list" class="table table-striped nowrap" style="width:100%" >
                       
                        <thead>
                            <tr role="row" style="background-color: grey; color: white;">
                                <th>S/N</th>
                                <th>Vehicle Name</th>
                                <th>Short Code</th>
                                <th style="width:500px" >Action</th>
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
  var op = "Vehicles.Vehicle_List";
  $(document).ready(function() {
    table = $("#page_list").DataTable({
      processing: true,
      columnDefs: [{
            orderable: false,
            targets: 0
          },
         { width: "3100", targets: "3" }
      ],
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
    
    function deleteVehicle(id)
    {
        let cnf = confirm("Are you sure you want to delete menu?");
        if(cnf == true)
            {
                $.blockUI();
                $.post("utilities.php",{op:"Vehicles.deleteVehicle",vehicle_id:id},function(re){
                    $.unblockUI();
                    alert(re.response_message);
                    getpage('vehicle_type.php',"page");
                },'json')
            }
        
    }
    function getModal(url,div)
    {
        $('#'+div).html("<h2>Loading....</h2>");
//        $('#'+div).block({ message: null });
        $.post(url,{},function(re){
            $('#'+div).html(re);
        })
    }
</script>

