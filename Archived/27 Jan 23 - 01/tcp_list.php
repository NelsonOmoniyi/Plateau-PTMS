<?php
include_once("libs/dbfunctions.php");
$dbobject = new dbobject();
$role = $_SESSION['role_id_sess'];
?>
   <div class="card">
    <div class="card-header">
        <h5 class="card-title">Transport Companies</h5>
       
    </div>
    <div class="card-body">
      <?php if($_SESSION['role_id_sess'] != 001){
          echo "<a class='btn btn-warning' onclick=\"getModal('setup/TCPver.php','modal_div')\" data-toggle=\"modal\" data-target=\"#defaultModalPrimary\" href=\"javascript:void(0)\">Transport Companies Registration</a>";
      }else{
      }
      ?>
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
                                <th>Tax Number</th>
                                <th>Business Name</th>
                                <th>Address</th>
                                <th>Owner Name</th>
                                <th>C. A. C Number</th>
                                <th>Email Adress</th>
                                <th>Phone Number</th>
                                <th>Capacity</th>
                                <th>Created</th>
                                <th>Status</th>
                                <th>Action</th>
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
  var op = "TCP.tcp_list";
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
    
 
    function getModal(url,div)
    {
        $('#'+div).html("<h2>Loading....</h2>");
        $.post(url,{},function(re){
            $('#'+div).html(re);
        })
    }
    function PrintPage(d) {
            // window.alert(d)
            // window.open('./print/transport_companies.php?id='+d, '_blank');
            window.open('receipt/special_trade_receipt.php?table=transport_companies&pid='+d, '_blank');
            // getpage('setup/print.php?id='+d,'page');
            getpage('tcp_list.php','page');
		
	}
  function PrintC(d) {
      // window.alert(d)
      window.open('certificate/tcp_certificate.php?id='+d, '_blank');
	}
    
</script>