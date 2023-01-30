<?php require('./header.php'); 

include_once("./libs/dbfunctions.php");
include_once("./class/recievePayment.php");
$dbobject = new dbobject();
$payment = new Payment();

$sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$pid'";
$result = $this->db_query($sql);
$item = $result[0]['trans_desc'];
$amount = $result[0]['trans_amount'];
$item_code = $result[0]['item_code'];
$portal_id = $result[0]['payment_code'];
$tin = $result[0]['tin'];

?>

<section class="banner v10">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <center>
                            <p style="text-align:center;">
                            <center><img src="check.png" style="max-height:270px; width:105px"></center>
                            </p>
                            <h2 style="text-align: center; font-family: Arial, Helvetica, sans-serif;">Success</h2>
                            <table
                                style="border-collapse: collapse; border-width: 1px 0; border: 1px solid #ccc; width:60%;">
                                <tr>
                                    <th
                                        style="padding:15px; border:1px solid #ccc; font-size: 13px; font-family:Arial, Helvetica, sans-serif; text-align: left; background:#f4f7f9; border-collapse: collapse;border-width: 1px 0; color:#6c757d;">
                                        NAME: </th>
                                    <td style="padding:15px; border:1px solid #ccc;background:#f4f7f9; color:#6c757d;">
                                        '.strtoupper($fullname).' </td>
                                </tr>
                                <tr>
                                    <th
                                        style="padding:15px; border:1px solid #ccc; font-size: 13px; font-family:Arial, Helvetica, sans-serif; text-align: left; color:#6c757d;">
                                        EMAIL: </th>
                                    <td
                                        style="padding:15px; border:1px solid #ccc; font-size: 13px; font-family:Arial, Helvetica, sans-serif; text-align: left; color:#6c757d;">
                                        '.strtolower($email).'</td>
                                </tr>
                                <tr>
                                    <th
                                        style="padding:15px; border:1px solid #ccc; font-size: 13px; font-family:Arial, Helvetica, sans-serif; text-align: left;background:#f4f7f9; color:#6c757d;">
                                        DEPARTMENT: </th>
                                    <td style="padding:15px; border:1px solid #ccc;background:#f4f7f9; color:#6c757d;">
                                        '.strtoupper($dept_name).'</td>
                                </tr>
                                <tr>
                                    <th
                                        style="padding:15px; border:1px solid #ccc; font-size: 13px; font-family:Arial, Helvetica, sans-serif; text-align: left; color:#6c757d; color:#6c757d;">
                                        TRANSACTION ID:</th>
                                    <td style="padding:15px; border:1px solid #ccc; color:#6c757d;">' .
                                        substr($MerchantTransID, 9) . '</td>
                                </tr>
                                <tr>
                                    <td colspan="2"
                                        style="padding:15px; border:1px solid #ccc; font-size: 13px; font-family:Arial, Helvetica, sans-serif; text-align: center; background:#f4f7f9; color:#6c757d;">
                                        ' . $obj->data->response_message . '! Please try again!</td>
                                </tr>

                            </table>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>


</script>
<?php require('footer.php'); ?>