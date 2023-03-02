<?php
include_once("recievePayment.php");
class Spare extends dbobject
{
    
    public function spare_list($data)
    {
        $table_name    = "spare_parts";
            $primary_key   = "portal_id";
            $columner = array(
                array( 'db' => 'portal_id', 'dt' => 0 ),
                array( 'db' => 'tin',  'dt' => 1 ),
                array( 'db' => 'business_name', 'dt' => 2, 'formatter'=>function($d, $row){
                    if ($row['status'] > 0) {
                        if ($row['approved'] > 0) {

                            return "$d | <a href='certificate/spd_certificate.php?id=".$row['portal_id']."&table=spare_parts' class='btn btn-primary btn btn-sm' target='_blank'><i class='fa fa-print'></i> Print Certificate</a>";
                        } else {
                            return  ''.$d.' |
                            <a class="btn btn-primary btn-sm" onclick="getModal(\'setup/preview_spare_part.php?id='.$row['portal_id'].'&table=spare_parts\',\'modal_div\')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Approve</a>
                            ';
                        } 
                    } else {
                        return  $d;
                    }
                }),
                array( 'db' => 'address', 'dt' => 3 ),
                array( 'db' => 'owner_name',  'dt' => 4 ),
                array( 'db' => 'cac_reg_no', 'dt' => 5 ),
                array( 'db' => 'email_add',  'dt' => 6 ),
                array( 'db' => 'phone',  'dt' => 7 ),
                array( 'db' => 'cap',  'dt' => 8 ),
                array( 'db' => 'created', 'dt' => 9 ),
                array( 'db' => 'status', 'dt' => 10, 'formatter'=> function($d, $row){
                    if ($_SESSION['role_id_sess'] != '001') {
                        if($d>0){
                            return "Paid | <a href='receipt/special_trade_receipt.php?pid=".$row['portal_id']."&table=spare_parts' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                        }else{
                            return "Not Paid | <a href='receipt/special_trade_receipt.php?pid=".$row['portal_id']."&table=spare_parts' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                        }
                    } else {
                        if($d>0){
                            return "Paid | <a href='receipt/special_trade_receipt.php?pid=".$row['portal_id']."&table=spare_parts' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                        }else{
                            return "Not Paid | <a href='receipt/special_trade_receipt.php?pid=".$row['portal_id']."&table=spare_parts' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                        }
                    }
                }),
                array( 'db' => 'expiry_date', 'dt' => 11, 'formatter'=> function($d, $row){

                    $exp = $row['expiry_date'];
                    // return  $d;
                    if (date('Y-m-d') > $d) {
                            return  "Expired ";
                    } else {
                        return  "Not Expired";
                    }
                }),
                array( 'db' => 'approved')
            );
            $filter = "";
            
            // var_dump($data);
            $datatableEngine = new engine();
        
            echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key); 
    }
    public function verTIN($data){
        $tin = $data['tax'];
        $item = $data['cat'];
        // var_dump($item);
        // exit;
        $validation = $this->validate($data,
        array(
            'tax' =>'required',
            'cat' => 'required'
        ),
        array('tax'=>'Tax Identification Number', 'cat'=>'Category')
        );
        if(!$validation['error'])
        {
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
        
                $title= $resArr['title'];
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
                    $check = $this->db_query($sql,false);

                return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Tax Identification Number. Please wait loading spare part dealership setup','title'=>$title,'firstname'=>$firstName,'middleName'=>$midName,'surname'=>$Surname,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'id'=>$item, 'name'=>$itemName, 'price'=>$itemPrice));
            
            }
            else{
                // var_dump($message);
                return json_encode(array('response_code'=>'109', 'response_message'=>"TIN Not Found!"));
            }
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
        
    }
    public function saveSpare($data)
    {
        
        if($data['operation'] == "new")
        {
            $validation = $this->validate($data,
            array(
                'tin'=>'required',
                'business_name'=>'required',
                'address'=>'required',
                'email_add'=> 'required',
                'phone'=>'required',
                'owner_name'=>'required',
                'cac_reg_no'=>'required',
                'cap'=>'required',
                'agreement' => 'required'
            ),
            array(
                'tin'=>'Tax Identification Number',
                'business_name'=>'School Name / Center',
                'address'=>'Business Address',
                'owner_name'=>'Owners Name',
                'cac_reg_no'=>'CAC Reg Number',
                'email_add'=>'Email Address',
                'phone'=>'Phone Number',
                'cap'=>'Garrage Capacity',
                'agreement'=>'Agreement')
            );
            if(!$validation['error'])
            {
                $pending = 0;
                $processed = 1;
                $tin = $data['tin'];
                $name = $data['business_name'];
                $address = $data['address'];
                $email = $data['email_add'];
                $phone = $data['phone'];
                $owner_name = $data['owner_name'];
                $CAC = $data['cac_reg_no'];
                $capacity = $data['cap'];
                $up = $data['upload'];
                $agreement = $data['agreement'];
                $payment_category = $data['cat'];
                $amount = $data['amount'];
                $portal_id = $data['port'];
                $trans_code = "SPD";
                $item_code = $data['item_code'];
                $officer = $_SESSION['username_sess'];
                $now = date('Y-m-d H:i:s');

                $file_data = $data['_files'];
                $ff   = $this->saveImage($file_data,"uploads/","");
                $ff   = json_decode($ff,true);
                $full_path = $ff['data'];
                $expDate = date('Y-m-d', strtotime(' + 1 years'));

                // $sql   = "SELECT * FROM tb_payment_confirmation WHERE tin = '$tin' AND trans_desc_code = '$trans_code' LIMIT 1";
                // $result = $this->db_query($sql);
                // $Tstatus = $result[0]['trans_status'];
                // $paid = count($result);
                

                // if(!$paid > 0){
                    $insert2 = "INSERT INTO tb_payment_confirmation (payment_code, trans_desc, trans_amount, trans_status, officer,client_fullname,trans_desc_code, tin, item_code) VALUES ('$portal_id', 'Spare Parts Dealership Registration', '$amount', '$pending', '$officer','$name','$trans_code', '$tin', '$item_code')";
                    $check = $this->db_query($insert2,false);
                    $count = count($check);
                    
                    if ($count > 0) {
                        $insert = "INSERT INTO spare_parts (portal_id, business_name, address, owner_name, cac_reg_no, cap, email_add, phone, tin, status, created, passport, expiry_date, item_code)
                        VALUES ('$portal_id', '$name', '$address', '$owner_name', '$CAC', '$capacity','$email','$phone', '$tin', '$pending', '$now', '$full_path', '$expDate', '$item_code')";
                        $resultDS = $this->db_query($insert, false);
                        $count = count($resultDS);

                        // var_dump($count);
                        // exit;
                        if($count = 1)
                        {
                            return json_encode(array('response_code'=>0,'response_message'=>'Registration Successfully', 'port_id'=>$portal_id)); 
                          
                        }else
                        {
                            return json_encode(array('response_code'=>47,'response_message'=>'Registration Failed, Try Again Later'));
                        }
                    } else {
                        return json_encode(array('response_code'=>900,'response_message'=>'Registration Failed, Try Again Later'));
                    }
                    
                // }else{
                //     if ( $Tstatus == $processed ) { 
                //         $sql   = "SELECT * FROM spare_parts WHERE tin = '$tin' LIMIT 1";
                //         $result = $this->db_query($sql);
                //         $portal_id = $result[0]['portal_id'];
                //         return json_encode(array('response_code'=>0,'response_message'=>'Registration Successfully', 'port_id'=>$portal_id));
                //     }else{
                //         return json_encode(array('response_code'=>407,'response_message'=>'Payment For This Licence Have Not Been Made! <a href="https://techhost7x.accessng.com/plateau_transport/spare_parts1.php">Kindly Click On This Link To Make Payment</a>'));
                //     }
                // }
            }else{
                return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
            }
        }
    }
    public function validatePID($data){
        $PID = $data['portal_id'];
        
        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$PID' AND trans_status = '0'";
        $result = $this->db_query($sql);
        if ($result == NULL) {
            return json_encode(array('response_code'=>'99', 'response_message'=>'Invalid Portal ID'));
        } else {
           
            $sql = "SELECT * FROM spare_parts WHERE portal_id = '$PID'";
            $result = $this->db_query($sql);
            $count = count($result);
            $res = $result[0];
            // var_dump($res);
            // exit;
            if ($count > 0) {
                
                $name = $res['business_name'];
                $owner_name = $res['owner_name'];
                $email = $res['email_add'];
                $tin = $res['tin'];
                $mobile = $res['phone'];
                $address = $res['address'];
                
                return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Tax Identification Number','name'=>$name,'owner_name'=>$owner_name,'email'=>$email,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'port'=>$PID)); 
            } else {
                return json_encode(array('response_code'=>'909', 'response_message'=>'Incomplete Spare Parts Dealership Licence Process, Please Contact An Administrator'));
            }
            
        }
      
    }
    public function checkDetails($data){
        $trans_code = "SPD";
        $validation = $this->validate($data,
        array(
            'name' =>'required',
            'owner_name' =>'required',
            'email'=> 'required',
            'phoneNumber' =>'required',
            'tinval'=> 'required'
        ),
        array('name'=>'Business Name','titlename'=>'Title','owner_name'=>'Owner Name', 'email'=>'Email Address', 'phoneNumber'=>'Phone Number', 'tinval'=>'Tax Identification Number')
        );
        $created = date("Y-m-d H:i:s");
        if ($validation['error']) {
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        } else {
            $sql = "UPDATE transaction_table SET firstname = '$data[first_name]', middlename = '$data[middle_name]', surname = '$data[surname]', mobilenumber = '$data[phoneNumber]', created = '$created', trans_type = '$trans_code' WHERE tin = '$data[tinval]'";
            $count = $this->db_query($sql,false); 
            $exec = count($count);
            if($exec > 0 ){
                return json_encode(array("response_code"=>200,"response_message"=>'Succcess', "pid"=>$data['port']));
            }else{
                return json_encode(array("response_code"=>409,"response_message"=>'AN ERROR OCCURED! Pls Try Again Later'));
            }
        }
    }

    function saveImage($data,$path,$image_id="")
    {
        $_FILES = $data;
            //        var_dump($_FILES);
        if (
            !isset($_FILES['upfile']['error']) ||
            is_array($_FILES['upfile']['error'])
        ) {
            return json_encode(array('response_code'=>'0','response_mesage'=>'Invalid parameter.'));
        }

        // Check $_FILES['upfile']['error'] value.
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return json_encode(array('response_code'=>'0','response_mesage'=>'No file sent.'));
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return json_encode(array('response_code'=>'74','response_mesage'=>'Exceeded filesize limit.'));
            default:
                return json_encode(array('response_code'=>'74','response_mesage'=>'Unknown errors.'));
        }

        // You should also check filesize here.
        if ($_FILES['upfile']['size'] > 1000000) {
            return json_encode(array('response_code'=>'74','response_mesage'=>'Exceeded filesize limit.'));
        }

        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        //    $finfo = new finfo(FILEINFO_MIME_TYPE);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // var_dump($finfo);
        // echo $ext." == hello guys";
        if (false === $ext = array_search(
            finfo_file($finfo,$_FILES['upfile']['tmp_name']),
            array(
                'jpg' => 'image/jpg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ),
            true)) {
            return json_encode(array('response_code'=>'74','response_mesage'=>'Invalid file format.'));
        }

        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $email = ($image_id == "")?date('mdhis'):$image_id;

        //@@@@@@@@@@@@@@@@@@@@@@@
        
        if (!move_uploaded_file($_FILES['upfile']['tmp_name'],sprintf($path.'%s.%s',$email,$ext))) {
            return json_encode(array('response_code'=>'50','response_mesage'=>'Failed to move uploaded file.'));
        }
        $full_path = $path.$email.'.'.$ext;
        
        unlink($_FILES['upfile']['tmp_name']);
        return json_encode(array('response_code'=>'0','response_message'=>'success','data'=>$full_path));
    }

}

?>

