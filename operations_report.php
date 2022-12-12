<?php
include_once("libs/dbfunctions.php");
$dbobject = new dbobject();
$role = $_SESSION['role_id_sess'];
?>
   <div class="card">
    <div class="card-header">
        <h4 class="card-title">REPORT</h4>
        <h6 class="card-subtitle text-muted">The report contains Lists that have been setup in the system.</h6>
    </div>
    <div class="card-body">
        <h5 class="card-title">FINANCIAL TRANSACTIONS REPORT</h5>
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
                                <th>Portal ID</th>
                                <th>Name</th>
                                <th>Transaction Description</th>
                                <th>Transaction Amount</th>
                                <th>Transaction Status</th>
                                <th>Tax Identification Number</th>
                                <th>Officer</th>
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

<script>
  
  var table;
  var editor;
  var op = "Report.operations";
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
                $.post("utilities.php",{op:"Sidenumber.deleteList",menu_id:id},function(re){
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
    function PrintPage(d) {
            // window.alert(d)
            window.open('setup/print.php?id='+d, '_blank');
            // getpage('setup/print.php?id='+d,'page');
            getpage('sidenumber_list.php','page');
		
	}
  
    
</script>