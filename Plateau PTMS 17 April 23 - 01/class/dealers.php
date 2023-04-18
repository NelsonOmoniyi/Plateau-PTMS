<?php
include_once("recievePayment.php");
class Dealers extends dbobject
{

    public function dls_list($data)
    {
        $table_name = "dealership";
        $primary_key = "id";
        $columner = array(
            array('db' => 'id', 'dt' => 0),
            // array( 'db' => 'cat',  'dt' => 1 ),
            // array( 'db' => 'amount',  'dt' => 2 ),
            // array( 'db' => 'item_code',  'dt' => 3 ),
            array(
                'db' => 'passport',
                'dt' => 1,
                'formatter' => function ($d, $row) {
                    $image = (($d == "") ? 'https://us.123rf.com/450wm/anatolir/anatolir2011/anatolir201105528/159470802-jurist-avatar-icon-flat-style.jpg?ver=6' : $d);
                    return '<img src="' . $image . '" class="avatar img-fluid rounded-circle mr-1" alt="Nelson Omoniyi">';
                }
            ),
            array(
                'db' => 'business_name',
                'dt' => 2,
                'formatter' => function ($d, $row) {
                    // $checkStatus = $this->getitemlabel('tb_payment_confirmation', 'payment_code', $row['portal_id'], 'trans_status');
                    // $checkExp = $this->getitemlabel('tb_payment_confirmation', 'payment_code', $row['portal_id'], 'expiry_date');
                    if ($row['expiry_date'] == "not set") {
                        return $d;
                    } else if (date('Y-m-d') > $row['expiry_date']) {
                        return $d;
                    } else {
                        if ($row['status'] > 0) {
                            if ($row['approved'] > 0) {
                                return "$d | <a href='certificate/spd_certificate.php?id=" . $row['portal_id'] . "&table=dealership' class='btn btn-primary btn btn-sm' target='_blank'><i class='fa fa-print'></i> Print Certificate</a>";
                            } else {
                                // added setup/preview_spare_part.php
        
                                return '' . $d . ' | <a class="btn btn-primary btn-sm" onclick="getModal(\'setup/preview_dealership.php?id=' . $row['portal_id'] . '&table=dealership\',\'modal_div\')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Approve</a>';
                            }
                        } else {
                            return $d;
                        }
                    }
                }
            ),
            array('db' => 'owner_name', 'dt' => 3),
            array('db' => 'address', 'dt' => 4),
            array('db' => 'phone', 'dt' => 5),
            array('db' => 'cac_reg_no', 'dt' => 6),
            // array( 'db' => 'portal_id',  'dt' => 6 ),
            array('db' => 'tin', 'dt' => 7),

            array('db' => 'sponsor', 'dt' => 8),
            array('db' => 'license_union', 'dt' => 9),

            array('db' => 'portal_id'),
            array('db' => 'created', 'dt' => 10),
            array(
                'db' => 'status',
                'dt' => 11,
                'formatter' => function ($d, $row) {
                    $checkPayt = $this->getitemlabel('tb_payment_confirmation', 'payment_code', $row['portal_id'], 'trans_status');
                    $checkExp = $this->getitemlabel('tb_payment_confirmation', 'payment_code', $row['portal_id'], 'expiry_date');
                    if ($row['expiry_date'] == "not set") {
                        return "<button class='btn btn-dark btn-sm'>Awaiting Payment</button>";
                    } else if (date('Y-m-d') > $row['expiry_date']) {
                        return "<button class='btn btn-danger btn-sm'>Expired</button>";
                    } else {
                        if ($_SESSION['role_id_sess'] != '001') {
                            if ($row['status'] > 0) {
                                return "Paid | <a href='receipt/special_trade_receipt.php?pid=" . $row['portal_id'] . "&table=dealership' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                            } else {
                                return "Not Paid | <a href='receipt/special_trade_receipt.php?pid=" . $row['portal_id'] . "&table=dealership' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                            }
                        } else {
                            if ($row['status'] > 0) {
                                return "Paid | <a href='receipt/special_trade_receipt.php?pid=" . $row['portal_id'] . "&table=dealership' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                            } else {
                                return "Not Paid | <a href='receipt/special_trade_receipt.php?pid=" . $row['portal_id'] . "&table=dealership' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                            }
                        }
                    }
                }
            ),
            array(
                'db' => 'expiry_date',
                'dt' => 12,
                'formatter' => function ($d, $row) {

                    // $checkExp = $this->getitemlabel('tb_payment_confirmation', 'payment_code', $row['portal_id'], 'expiry_date');
                    // return  $d;
                    if (date('Y-m-d') > $row['expiry_date']) {
                        return "Expired [$row[expiry_date]]";
                    } else {
                        return "Expiring [$row[expiry_date]]";
                    }
                }
            ),
            array('db' => 'approved')

        );
        $filter = "";

        // var_dump($data);
        $datatableEngine = new engine();

        echo $datatableEngine->generic_table($data, $table_name, $columner, $filter, $primary_key);
    }
    public function verify_TIN($data)
    {
        $tin = $data['tax'];
        $item = $data['cat'];
        $cac = $data['cac'];
        // var_dump($item);
        // exit; 
        $validation = $this->validate(
            $data,
            array(
                'tax' => 'required',
                'cat' => 'required'
            ),
            array('tax' => 'Tax Identification Number', 'cat' => 'Category')
        );
        if (!$validation['error']) {
            $pay = new Payment();
            $res = $pay->ValTIN($tin);
            $resArr = json_decode($res, TRUE);
            $sql = "SELECT * FROM payment_category WHERE id = '$item'";
            $response = $this->db_query($sql);
            $arr = $response[0];
            $itemName = $arr['item'];
            $itemPrice = $arr['amount'];
            // var_dump($response[0]);
            // exit;
            if ($resArr['status'] == 'success') {

                $title = $resArr['title'];
                $firstName = $resArr['first_name'];
                $midName = $resArr['middle_name'];
                $Surname = $resArr['surname'];
                $tin = $resArr['tin'];
                $name = $resArr['name'];
                $mobile = $resArr['phoneNumber'];
                $address = $resArr['address'];
                $message = $resArr['message'];
                $type = $resArr['account_type'];
                $status = $resArr['status'];
                $message = $resArr['message'];


                $sql = "INSERT INTO tin_table (tax_number, phone, address, type, status,firstname, middlename, surname, message, name) VALUES ('$tin', '$mobile', '$address', '$type', '$status', '$firstName', '$midName', '$Surname', '$message', '$name')";
                $check = $this->db_query($sql, false);

                return json_encode(array('response_code' => '200', 'response_message' => 'Valid Tax Identification Number. Please wait loading dealership setup', 'title' => $title, 'firstname' => $firstName, 'middleName' => $midName, 'surname' => $Surname, 'tin' => $tin, 'mobile' => $mobile, 'address' => $address, 'id' => $item, 'name' => $itemName, 'price' => $itemPrice, 'cac' => $cac));

            } else {
                // var_dump($message);
                return json_encode(array('response_code' => '109', 'response_message' => "TIN Not Found!"));
            }
        } else {
            return json_encode(array("response_code" => 20, "response_message" => $validation['messages'][0]));
        }
    }
    public function saveDLS($data)
    {
        if ($data['operation'] == "new") {
            $validation = $this->validate(
                $data,
                array(
                    'cat' => 'required',
                    'amount' => 'required',
                    'email' => 'required',
                    'item_code' => 'required',
                    'portal_id' => 'required',
                    'tin' => 'required',
                    'business_name' => 'required',
                    'address' => 'required',
                    'owner_name' => 'required',
                    'cac_reg_no' => 'required',
                    'sponsor' => 'required',
                    'phone' => 'required',
                    'license_category' => 'required',
                    'list_of_apprentice' => 'required',
                    'license_union' => 'required',
                    'mem_no' => 'required',
                    'agreement' => 'required'
                ),
                array(
                    'cat' => 'Category',
                    'amount' => 'Amount',
                    'item_code' => 'Item Code',
                    'email' => 'Email Address',
                    'portal_id' => 'Portal ID',
                    'tin' => 'Tax Identification Number',
                    'business_name' => 'Business Name',
                    'address' => 'Address',
                    'owner_name' => ' Owners Name',
                    'cac_reg_no' => 'CAC Reg Number',
                    'sponsor' => 'Sponsor',
                    'phone' => 'Phone Number',
                    'license_category' => 'Trade License Category',
                    'list_of_apprentice' => 'List of Apprentice',
                    'license_union' => 'Trade License Union',
                    'mem_no' => 'Membership No',
                    'agreement' => 'Agreement'
                )
            );

            if (!$validation['error']) {
                $pending = 0;
                $processed = 1;

                $email = $data['email'];
                $cat = $data['cat'];
                $amount = $data['amount'];
                $item_code = $data['item_code'];
                $portal_id = $data['portal_id'];
                $tin = $data['tin'];
                $business_name = $data['business_name'];
                $address = $data['address'];
                $owner_name = $data['owner_name'];
                $cac_reg_no = $data['cac_reg_no'];
                $sponsor = $data['sponsor'];
                $phone = $data['phone'];
                $license_category = $data['license_category'];
                $list_of_apprentice = $data['list_of_apprentice'];
                $license_union = $data['license_union'];
                $mem_no = $data['mem_no'];
                $agreement = $data['agreement'];
                $up = $data['upload'];
                $trans_code = "DL";
                $item_code = $data['item_code'];
                $officer = $_SESSION['username_sess'];
                $now = date('Y-m-d H:i:s');
                $expDate = date('Y-m-d', strtotime(' + 1 years'));
                $file_data = $data['_files'];
                mkdir("uploads");
                $ff = $this->saveImage($file_data, "uploads/", "");
                $ff = json_decode($ff, true);
                $full_path = $ff['data'];

                $MDA_ID = "128";
                $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
                $call_back = "https://techhost7x.accessng.com/plateau_transport/callBack.php";
                $item = "Dealership Registration";



                $res = $this->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code);
                $Array = json_decode($res, true);
                file_put_contents('initTranzact_log.txt', json_encode($Array, JSON_PRETTY_PRINT) . PHP_EOL);

                if (!$Array['status'] == "success") {
                    return json_encode(array("status" => $Array['status'], "response_message" => "Could not generate BRN for this record please try again", "message" => $Array['message']));

                } else if ($Array['status'] == "success") {
                    $status = $Array['status'];
                    $billing_ref = $Array['billing_reference'];
                    $mda_ref = $Array['mda_reference'];
                    $amount = $Array['amount'];
                    $message = $Array['message'];


                    $insert2 = "INSERT INTO tb_payment_confirmation (billing_ref,mda_ref,payment_code, trans_desc, trans_amount, trans_status, officer,client_fullname,trans_desc_code, tin, item_code, t_table, expiry_date) VALUES ('$billing_ref','$mda_ref','$portal_id', 'Dealership Registration', '$amount', '$pending', '$officer','$business_name', '$trans_code', '$tin', '$item_code', 'dealership', 'not set')";
                    $check = $this->db_query($insert2, false);
                    $count = count($check);
                    if ($count > 0) {

                        $insert = "INSERT INTO dealership (cat,amount,item_code,portal_id,tin,business_name,address,owner_name,cac_reg_no,sponsor,phone,license_category,list_of_apprentice,license_union,mem_no,agreement,status,passport,created,expiry_date,email,amount,billing_ref) VALUES ('$cat','$amount','$item_code','$portal_id','$tin','$business_name','$address','$owner_name','$cac_reg_no','$sponsor','$phone','$license_category','$list_of_apprentice','$license_union','$mem_no','$agreement','$pending','$full_path','$now','not set','$email','$amount','$billing_ref')";
                        $resultDS = $this->db_query($insert);
                        file_put_contents("insert.txt", $insert);
                        $count = count($resultDS);

                        if ($count = 1) {
                            return json_encode(array('response_code' => 0, 'response_message' => 'Registration Successfully', 'port_id' => $portal_id));

                        } else {
                            return json_encode(array('response_code' => 47, 'response_message' => 'Registration Failed, Try Again Later'));
                        }
                    } else {
                        return json_encode(array('response_code' => 900, 'response_message' => 'Registration Failed, Try Again Later'));
                    }

                }
            } else {
                return json_encode(array("response_code" => 20, "response_message" => $validation['messages'][0]));
            }
        }
    }
    public function intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code)
    {
        // var_dump($MDA_ID." --- ".$MDA_re_id." ---- ".$item." --- ".$tin." --- ".$amount." --- ".$call_back." --- ".$item_code);
        // exit;
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://payments.psirs.gov.ng/OpenPaymentsApi/initialize_transaction',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "mda_id": "' . $MDA_ID . '",
                    "mda_reference": "' . $MDA_re_id . '",
                    "tax_item":"' . $item . '",
                    "tin": "' . $tin . '",
                    "amount":"' . $amount . '",
                    "callback_url": "' . $call_back . '",
                    "tax_code": "' . $item_code . '"
                    }',
                CURLOPT_HTTPHEADER => array(
                    ': ',
                    'Content-Type: application/json'
                ),
            )
        );

        $response = curl_exec($curl);
        file_put_contents("generate_mda_id.txt", $response);
        curl_close($curl);
        return $response;

    }


    // self service
    public function validatePID($data)
    {
        $PID = $data['portal_id'];
        $trans_code = "TCP";
        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$PID' AND trans_status = '0'";
        $result = $this->db_query($sql);
        if ($result == NULL) {
            return json_encode(array('response_code' => '99', 'response_message' => 'Invalid Portal ID'));
        } else {
            $sql = "SELECT * FROM dealership WHERE portal_id = '$PID'";
            $result = $this->db_query($sql);
            $count = count($result);
            $res = $result[0];
            // var_dump($res);
            // exit;
            if ($count > 0) {
                $name = $res['business_name'];
                $owner_name = $res['owner_name'];
                $email = $res['email'];
                $tin = $res['tin'];
                $mobile = $res['phone'];
                $address = $res['address'];

                return json_encode(array('response_code' => '200', 'response_message' => 'Valid Tax Identification Number', 'name' => $name, 'owner_name' => $owner_name, 'tin' => $tin, 'mobile' => $mobile, 'email' => $email, 'address' => $address, 'port' => $PID));
            } else {
                return json_encode(array('response_code' => '909', 'response_message' => 'Wrong Portal ID, kindly check the service registered for.'));
            }

        }

    }
    public function checkDetails($data)
    {
        $trans_code = "DL";
        $validation = $this->validate(
            $data,
            array(
                'name' => 'required',
                'owner_name' => 'required',
                'email' => 'required',
                'phoneNumber' => 'required',
                'tinval' => 'required'
            ),
            array('name' => 'Business Name', 'titlename' => 'Title', 'owner_name' => 'Owner Name', 'email' => 'Email Address', 'phoneNumber' => 'Phone Number', 'tinval' => 'Tax Identification Number')
        );
        $created = date("Y-m-d H:i:s");
        if ($validation['error']) {
            return json_encode(array("response_code" => 20, "response_message" => $validation['messages'][0]));
        } else {
            $sql = "UPDATE transaction_table SET firstname = '$data[first_name]', middlename = '$data[middle_name]', surname = '$data[surname]', mobilenumber = '$data[phoneNumber]', created = '$created', trans_type = '$trans_code' WHERE tin = '$data[tinval]'";
            $count = $this->db_query($sql, false);
            $exec = count($count);
            if ($exec > 0) {

                return json_encode(array("response_code" => 200, "response_message" => 'Succcess', "pid" => $data['port']));
            } else {
                return json_encode(array("response_code" => 409, "response_message" => 'AN ERROR OCCURED! Pls Try Again Later'));
            }
        }
    }

    function saveImage($data, $path, $image_id = "")
    {
        $_FILES = $data;
        //        var_dump($_FILES);
        if (
            !isset($_FILES['upfile']['error']) ||
            is_array($_FILES['upfile']['error'])
        ) {
            return json_encode(array('response_code' => '0', 'response_mesage' => 'Invalid parameter.'));
        }

        // Check $_FILES['upfile']['error'] value.
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return json_encode(array('response_code' => '0', 'response_mesage' => 'No file sent.'));
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return json_encode(array('response_code' => '74', 'response_mesage' => 'Exceeded filesize limit.'));
            default:
                return json_encode(array('response_code' => '74', 'response_mesage' => 'Unknown errors.'));
        }

        // You should also check filesize here.
        if ($_FILES['upfile']['size'] > 1000000) {
            return json_encode(array('response_code' => '74', 'response_mesage' => 'Exceeded filesize limit.'));
        }

        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        //    $finfo = new finfo(FILEINFO_MIME_TYPE);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // var_dump($finfo);
        // echo $ext." == hello guys";
        if (
            false === $ext = array_search(
                finfo_file(
                    $finfo,
                    $_FILES['upfile']['tmp_name']
                ),
                array(
                    'jpg' => 'image/jpg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png'
                ),
                true
            )
        ) {
            return json_encode(array('response_code' => '74', 'response_mesage' => 'Invalid file format.'));
        }

        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $email = ($image_id == "") ? date('mdhis') : $image_id;

        //@@@@@@@@@@@@@@@@@@@@@@@

        if (!move_uploaded_file($_FILES['upfile']['tmp_name'], sprintf($path . '%s.%s', $email, $ext))) {
            return json_encode(array('response_code' => '50', 'response_mesage' => 'Failed to move uploaded file.'));
        }
        $full_path = $path . $email . '.' . $ext;

        unlink($_FILES['upfile']['tmp_name']);
        return json_encode(array('response_code' => '0', 'response_message' => 'success', 'data' => $full_path));
    }

}



?>