<?php
include_once("../libs/dbfunctions.php");
include_once("../class/sidenumber.php");
$dbobject = new dbobject();
$id  = isset($_REQUEST['id'])? $_REQUEST['id'] :'';
$table  = isset($_REQUEST['table'])? $_REQUEST['table'] :'';
// added
$renewSql = "UPDATE dealership SET approved = '1' WHERE portal_id = '$id'";
$dbobject->db_query($renewSql,false);

$check = $dbobject->db_query("SELECT * FROM dealership WHERE portal_id='$id'");
$item_code = $check[0]['item_code'];
$link = $dbobject->getitemlabel('payment_category', 'id', $item_code, 'link');
?>
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">Trade Preview</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3" id="modal">
    <div class="container-fluid">
        <table class="table table-bordered table-striped">
            <tr><th>Business Name</th><td><?php echo $check[0]['business_name'] ?></td></tr>
            <tr><th>Owners Name</th><td><?php echo $check[0]['owner_name'] ?></td></tr>
            <tr><th>Address</th><td><?php echo $check[0]['address'] ?></td></tr>
            <tr><th>Tax Number</th><td><?php echo $check[0]['tin'] ?></td></tr>
            <tr><th>CAC Number</th><td><?php echo $check[0]['cac_reg_no'] ?></td></tr>
            <tr><th>Mobile Number</th><td><?php echo $check[0]['phone'] ?></td></tr>
            <tr><th>Sponsor</th><td><?php echo $check[0]['sponsor'] ?></td></tr>
            <tr><th>License Union</th><td><?php echo $check[0]['license_union'] ?></td></tr>
            <tr><th>Status</th><td><?php if($check[0]['status']>0){echo "Paid";}else{echo "Not Paid";} ?></td></tr>

        </table>
    </div>

</div>
<div class="modal-footer">
<a href="<?php echo "$link?id=$id&table=$table" ?>" target="_blank" class="btn btn-primary">Print</a>
</div>