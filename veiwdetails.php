<?php
include_once("libs/dbfunctions.php");
$dbobject = new dbobject();

$sidenumber_id = isset($_REQUEST['sidenumber_id'])?$_REQUEST['sidenumber_id']:'';
// $sql = "SELECT * FROM nin WHERE sidenumber_id = $sidenumber_id";
// $result = $dbobject->db_query($sql);
// var_dump($sidenumber_id);

$loop =$dbobject->db_query("SELECT * FROM tax WHERE sidenumber_id = $sidenumber_id");
$LL = $loop[0];

?>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">Tax Number Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
          <h6 class="card-subtitle text-muted">The report contains Tax Number Details that have been setup in the system.</h6>
    </div>
    <div class="card-body">
      <div style="padding: 10px;">
          <table class="table table-striped table-bordered table-sm">
              <h4 class="text-center">Tax Number Information</h4>
              <thead class="thead-dark">
                  <tr>
                  <th scope="col" class="thead-light">Label</th>
                  <th scope="col">Values</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                  <td><?php echo "First Name";?></td>
                  <td><?php echo "".ucfirst($LL['firstname'])."</br>";?></td>
                  </tr>
                  <tr>
                  <td><?php echo "Surname";?></td>
                  <td><?php echo "".ucfirst($LL['surname'])."</br>";?></td>
                  </tr>
                  <tr>
                  <td><?php echo "Middle Name";?></td>
                  <td><?php echo "".ucfirst($LL['middlename'])."</br>";?></td>
                  </tr>
                  <tr>
                  <td><?php echo "Phone Number";?></td>
                  <td><?php echo "".ucfirst($LL['msisdn'])."</br>";?></td>
                  </tr>
                  <tr>
                  <td><?php echo "Birth Date";?></td>
                  <td><?php echo "".ucfirst($LL['dateofbirth'])."</br>";?></td>
                  </tr>
                  <tr>
                  <td><?php echo "State";?></td>
                  <td><?php echo "".ucfirst($LL['state'])."</br>";?></td>
                  </tr>
                  <tr>
                  <td><?php echo "L.G.A";?></td>
                  <td><?php echo "".ucfirst($LL['lga'])."</br>";?></td>
                  </tr>
                  <tr>
                  <td><?php echo "Picture";?></td>
                  <td><?php echo "".ucfirst($LL['passport'])."</br>";?></td>
                  </tr>
              </tbody>
          </table>
        </div>
    </div>
</div>


<script>
  var table;
  var editor;
  var op = "Sidenumber.myNIN";
     
  $(document).ready(function() {
    table = $("#page_lists").DataTable({
      processing: true,
      columnDefs: [{
        // autoWidth: true,
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
          d.id= <?php echo $sidenumber_id?>;
//          d.start_date = $("#start_date").val();
//          d.end_date = $("#end_date").val();
        }
      }
    });
  });

  function do_filter() {
    table.draw();
  }
  

    
</script>

