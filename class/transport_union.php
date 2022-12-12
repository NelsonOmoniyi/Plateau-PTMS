<?php
include_once("recievePayment.php");
class TPU extends dbobject
{
    
    public function TPU_list($data)
    {
        $table_name    = "transport_union";
            $primary_key   = "portal_id";
            $columner = array(
                array( 'db' => 'portal_id', 'dt' => 0 ),
                array( 'db' => 'tin',  'dt' => 1 ),
                array( 'db' => 'business_name', 'dt' => 2 ),
                array( 'db' => 'address', 'dt' => 3 ),
                array( 'db' => 'owner_name',  'dt' => 4 ),
                array( 'db' => 'cac_reg_no', 'dt' => 5 ),
                array( 'db' => 'email_add',  'dt' => 6 ),
                array( 'db' => 'phone',  'dt' => 7 ),
                array( 'db' => 'cap',  'dt' => 8 ),
                array( 'db' => 'created', 'dt' => 9 ),
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
        $pay = new Payment();
        $res = $pay->ValTIN($tin);
        $resArr = json_decode($res, TRUE);
        $sql = "SELECT * FROM payment_category WHERE id = '$item'";
        $response = $this->db_query($sql);
        $arr = $response[0];
        $itemName = $arr['item'];
        $itemPrice = $arr['price'];
        // var_dump($response[0]);
        // exit;
        if ($resArr['status'] == 'success') {
    
                $title= $resArr['title'];
                $firstName = $resArr['first_name'];
                $midName = $resArr['middle_name'];
                $Surname = $resArr['surname'];
                $tin = $resArr['tin'];
                $mobile = $resArr['phoneNumber'];
                $address = $resArr['address'];
                $message = $resArr['message'];
            return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Tax Identification Number','title'=>$title,'firstname'=>$firstName,'middleName'=>$midName,'surname'=>$Surname,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'id'=>$item, 'name'=>$itemName, 'price'=>$itemPrice));
          
        }
        else{
            // var_dump($message);
            return json_encode(array('response_code'=>'109', 'response_message'=>"TIN Not Found!"));
        }
    }
    public function saveTU($data)
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
                $trans_code = "TU";
                $item_code = $data['item_code'];
                $officer = $_SESSION['username_sess'];
                $now = date('Y-m-d H:i:s');

                $sql   = "SELECT * FROM tb_payment_confirmation WHERE tin = '$tin' AND trans_desc_code = '$trans_code' LIMIT 1";
                $result = $this->db_query($sql);
                $Tstatus = $result[0]['trans_status'];
                $paid = count($result);
                // var_dump($paid);
                // exit;
                if(!$paid > 0){
                    $insert2 = "INSERT INTO tb_payment_confirmation (payment_code, trans_desc, trans_amount, trans_status, officer,trans_desc_code, tin, item_code) VALUES ('$portal_id', '$payment_category', '$amount', '$pending', '$officer', '$trans_code', '$tin', '$item_code')";
                    $check = $this->db_query($insert2,false);
                    $count = count($check);
                    if ($count > 0) {
                        $insert = "INSERT INTO transport_union (portal_id, business_name, address, owner_name, cac_reg_no, cap, email_add, phone, tin, status, created)
                        VALUES ('$portal_id', '$name', '$address', '$owner_name', '$CAC', '$capacity','$email','$phone', '$tin', '$pending', '$now')";
                        $resultDS = $this->db_query($insert);
                        $count = count($resultDS);
                        if($count = 1)
                        {
                            $sql = "INSERT INTO transaction_table (portal_id,tin)values('$portal_id','$tin')";
                            $exec = $this->db_query($sql,false);
                            if($exec > 0 ){
                                // initialize transaction
                            return json_encode(array('response_code'=>0,'response_message'=>'Registration Successfully', 'port_id'=>$portal_id)); 
                            }
                        }else
                        {
                            return json_encode(array('response_code'=>47,'response_message'=>'Registration Failed, Try Again Later'));
                        }
                    } else {
                        return json_encode(array('response_code'=>900,'response_message'=>'Registration Failed, Try Again Later'));
                    }
                    
                }else{
                    if ( !$Tstatus == $processed ) { 
                        $sql   = "SELECT * FROM transport_union WHERE tin = '$tin' LIMIT 1";
                        $result = $this->db_query($sql);
                        $portal_id = $result[0]['portal_id'];
                        return json_encode(array('response_code'=>0,'response_message'=>'Registration Successfully', 'port_id'=>$portal_id));
                    }else{
                        return json_encode(array('response_code'=>407,'response_message'=>'Payment For This Licence Have Not Been Made! <a href="../driving_school.php">Kindly Click On This Link To Make Payment</a>'));
                    }
                }
            }else
            {
                return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
            }
        }
    }
    public function validatePID($data){
        $PID = $data['portal_id'];
        $TIN = $data['tin'];
        $trans_code = "TU";
        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$PID' AND trans_status = '0'";
        $result = $this->db_query($sql);
        if ($result == NULL) {
            return json_encode(array('response_code'=>'99', 'response_message'=>'Invalid Portal ID'));
        } else {
            $sql = "SELECT tin FROM tb_payment_confirmation WHERE payment_code = '$PID'";
            $result = $this->db_query($sql);
            if (!$result > 0) {
                return json_encode(array('response_code'=>'99', 'response_message'=>'Incorrect Tax Identification Number'));
            }else{
                    // call Validate TIN
                
                $sql = "SELECT * FROM transport_union WHERE portal_id = '$PID'";
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
                    
                    return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Tax Identification Number','name'=>$name,'owner_name'=>$owner_name,'email'=>$email,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address));
                } else {
                    return json_encode(array('response_code'=>'909', 'response_message'=>'Incomplete Spare Parts Dealership Licence Process, Please Contact An Administrator'));
                }
            }
            
        }
      
    }
    public function checkDetails($data){
        $trans_code = "TU";
        $validation = $this->validate($data,
        array(
            'name' =>'required',
            'titlename' =>'required',
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
                return json_encode(array("response_code"=>200,"response_message"=>'Succcess', "tin"=>$data['tinval']));
            }else{
                return json_encode(array("response_code"=>409,"response_message"=>'AN ERROR OCCURED! Pls Try Again Later'));
            }
        }
    }
}



?>