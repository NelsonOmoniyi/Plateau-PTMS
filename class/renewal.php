<?php
include_once("recievePayment.php");
class Renewal extends dbobject
{
    // Driving School
    public function confirm_port($data){
        
        $validation = $this->validate($data,
        array(
            'portal_id' =>'required'
        ),
        array('portal_id'=>'Portal ID')
        );

        if(!$validation['error']){
            $id = $data['portal_id'];
            $sql = "SELECT * FROM driving_sch_form WHERE portal_id = '$id'";
            $res = $this->db_query($sql);
            if ($res > 0) {
                // var_dump($res);
                $name = str_replace('"','',$res[0]['school_name']);
                $owner_name = str_replace('"','', $res[0]['proprietor_name']);
                $email = str_replace('"','', $res[0]['email_add']);
                $tin = str_replace('"','', $res[0]['tin']);
                $mobile = str_replace('"','', $res[0]['phone']);
                $address = str_replace('"','', $res[0]['address']);
                $exp = str_replace('"','', $res[0]['expiry_date']);
                $today = date('Y-m-d');
                if ($today > $exp) {
                    return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Portal Identification Number','name'=>$name,'owner_name'=>$owner_name,'email'=>$email,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'port'=>$id, 'exp'=>$exp)); 
                } else {
                    return json_encode(array('response_code'=>'203', 'response_message'=>'Licence Have Not Expired')); 
                }

            } else {
                return json_encode(array('response_code'=>'202', 'response_message'=>'Portal ID Doesnt Exist.'));
            }
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }

    public function check($data){
        // var_dump($data);
        $data = str_replace('"', '', $data);
        $id = $data['port'];
        $desc = $data['description'];
        $desc_code = $data['desc_code'];
        // var_dump($data);
        // exit;
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $date = date("Y-m-d H:i:s");
        $officer = $_SESSION['username_sess'];
		$ip = $_SERVER['REMOTE_ADDR'];

        $dbid = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);

        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id'";
        $trans = $this->db_query($sql);
        $item_code = $trans[0]['item_code'];
        $tin = $trans[0]['tin'];

        $sql = "SELECT * FROM payment_category WHERE id = '$item_code'";
        $result = $this->db_query($sql);
        $amount = $result[0]['amount'];
        $pending = 0;

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null')";
        $result = $this->db_query($sql, false);
        // var_dump($result);
        // exit;
        if ($result > 0) { 
            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        

    }

    // Transport Companies
    public function confirm_portTC($data){
        
        $validation = $this->validate($data,
        array(
            'portal_id' =>'required'
        ),
        array('portal_id'=>'Portal ID')
        );

        if(!$validation['error']){
            $id = $data['portal_id'];
            $sql = "SELECT * FROM transport_companies WHERE portal_id = '$id'";
            $res = $this->db_query($sql);
            if ($res > 0) {
                // var_dump($res);
                $name = str_replace('"','',$res[0]['business_name']);
                $owner_name = str_replace('"','', $res[0]['owner_name']);
                $email = str_replace('"','', $res[0]['email_add']);
                $tin = str_replace('"','', $res[0]['tin']);
                $mobile = str_replace('"','', $res[0]['phone']);
                $address = str_replace('"','', $res[0]['address']);
                $exp = str_replace('"','', $res[0]['expiry_date']);
                $today = date('Y-m-d');
                if ($today > $exp) {
                    return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Portal Identification Number','name'=>$name,'owner_name'=>$owner_name,'email'=>$email,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'port'=>$id, 'exp'=>$exp)); 
                } else {
                    return json_encode(array('response_code'=>'204', 'response_message'=>'Licence Have Not Expired')); 
                }

            } else {
                return json_encode(array('response_code'=>'205', 'response_message'=>'Portal ID Doesnt Exist.'));
            }
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }

    public function checkTC($data){
        // var_dump($data);
        $data = str_replace('"', '', $data);
        $id = $data['port'];
        $desc = $data['description'];
        $desc_code = $data['desc_code'];
        // var_dump($data);
        // exit;
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $date = date("Y-m-d H:i:s");
        $officer = $_SESSION['username_sess'];
		$ip = $_SERVER['REMOTE_ADDR'];

        $dbid = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);

        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id'";
        $trans = $this->db_query($sql);
        $item_code = $trans[0]['item_code'];
        $tin = $trans[0]['tin'];

        $sql = "SELECT * FROM payment_category WHERE id = '$item_code'";
        $result = $this->db_query($sql);
        $amount = $result[0]['amount'];
        $pending = 0;

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null')";
        $result = $this->db_query($sql, false);
        // var_dump($result);
        // exit;
        if ($result > 0) { 
            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        

    }

    // spare parts dealership

    public function confirm_portSP($data){
        
        $validation = $this->validate($data,
        array(
            'portal_id' =>'required'
        ),
        array('portal_id'=>'Portal ID')
        );

        if(!$validation['error']){
            $id = $data['portal_id'];
            $sql = "SELECT * FROM spare_parts WHERE portal_id = '$id'";
            $res = $this->db_query($sql);
            if ($res > 0) {
                // var_dump($res);
                $name = str_replace('"','',$res[0]['business_name']);
                $owner_name = str_replace('"','', $res[0]['owner_name']);
                $email = str_replace('"','', $res[0]['email_add']);
                $tin = str_replace('"','', $res[0]['tin']);
                $mobile = str_replace('"','', $res[0]['phone']);
                $address = str_replace('"','', $res[0]['address']);
                $exp = str_replace('"','', $res[0]['expiry_date']);
                $today = date('Y-m-d');
                if ($today > $exp) {
                    return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Portal Identification Number','name'=>$name,'owner_name'=>$owner_name,'email'=>$email,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'port'=>$id, 'exp'=>$exp)); 
                } else {
                    return json_encode(array('response_code'=>'206', 'response_message'=>'Licence Have Not Expired')); 
                }

            } else {
                return json_encode(array('response_code'=>'207', 'response_message'=>'Portal ID Doesnt Exist.'));
            }
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }

    public function checkSP($data){
        // var_dump($data);
        $data = str_replace('"', '', $data);
        $id = $data['port'];
        $desc = $data['description'];
        $desc_code = $data['desc_code'];
        // var_dump($data);
        // exit;
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $date = date("Y-m-d H:i:s");
        $officer = $_SESSION['username_sess'];
		$ip = $_SERVER['REMOTE_ADDR'];

        $dbid = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);

        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id'";
        $trans = $this->db_query($sql);
        $item_code = $trans[0]['item_code'];
        $tin = $trans[0]['tin'];

        $sql = "SELECT * FROM payment_category WHERE id = '$item_code'";
        $result = $this->db_query($sql);
        $amount = $result[0]['amount'];
        $pending = 0;

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null')";
        $result = $this->db_query($sql, false);
        // var_dump($result);
        // exit;
        if ($result > 0) { 
            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        

    }
    

    // mechanic garage

    public function confirm_portMCG($data){
        
        $validation = $this->validate($data,
        array(
            'portal_id' =>'required'
        ),
        array('portal_id'=>'Portal ID')
        );

        if(!$validation['error']){
            $id = $data['portal_id'];
            $sql = "SELECT * FROM mech_garrage WHERE portal_id = '$id'";
            $res = $this->db_query($sql);
            if ($res > 0) {
                // var_dump($res);
                $name = str_replace('"','',$res[0]['business_name']);
                $owner_name = str_replace('"','', $res[0]['owner_name']);
                $email = str_replace('"','', $res[0]['email_add']);
                $tin = str_replace('"','', $res[0]['tin']);
                $mobile = str_replace('"','', $res[0]['phone']);
                $address = str_replace('"','', $res[0]['address']);
                $exp = str_replace('"','', $res[0]['expiry_date']);
                $today = date('Y-m-d');
                if ($today > $exp) {
                    return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Portal Identification Number','name'=>$name,'owner_name'=>$owner_name,'email'=>$email,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'port'=>$id, 'exp'=>$exp));
                } else {
                    return json_encode(array('response_code'=>'206', 'response_message'=>'Licence Have Not Expired')); 
                }

            } else {
                return json_encode(array('response_code'=>'207', 'response_message'=>'Portal ID Doesnt Exist.'));
            }
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }

    public function checkMCG($data){
        // var_dump($data);
        $data = str_replace('"', '', $data);
        $id = $data['port'];
        $desc = $data['description'];
        $desc_code = $data['desc_code'];
        // var_dump($data);
        // exit;
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $date = date("Y-m-d H:i:s");
        $officer = $_SESSION['username_sess'];
		$ip = $_SERVER['REMOTE_ADDR'];

        $dbid = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);

        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id'";
        $trans = $this->db_query($sql);
        $item_code = $trans[0]['item_code'];
        $tin = $trans[0]['tin'];

        $sql = "SELECT * FROM payment_category WHERE id = '$item_code'";
        $result = $this->db_query($sql);
        $amount = $result[0]['amount'];
        $pending = 0;

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null')";
        $result = $this->db_query($sql, false);
        // var_dump($result);
        // exit;
        if ($result > 0) { 
            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        

    }

    // Auto dealership
    public function confirm_portDLS($data){
    
        $validation = $this->validate($data,
        array(
            'portal_id' =>'required'
        ),
        array('portal_id'=>'Portal ID')
        );

        if(!$validation['error']){
            $id = $data['portal_id'];
            $sql = "SELECT * FROM dealership WHERE portal_id = '$id'";
            $res = $this->db_query($sql);
            if ($res > 0) {
                // var_dump($res);
                $name = str_replace('"','',$res[0]['business_name']);
                $owner_name = str_replace('"','', $res[0]['owner_name']);
                $email = str_replace('"','', $res[0]['email']);
                $tin = str_replace('"','', $res[0]['tin']);
                $mobile = str_replace('"','', $res[0]['phone']);
                $address = str_replace('"','', $res[0]['address']);
                $exp = str_replace('"','', $res[0]['expiry_date']);
                $today = date('Y-m-d');
                if ($today > $exp) {
                    return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Portal Identification Number','name'=>$name,'owner_name'=>$owner_name,'email'=>$email,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'port'=>$id, 'exp'=>$exp)); 
                } else {
                    return json_encode(array('response_code'=>'206', 'response_message'=>'Licence Have Not Expired')); 
                }

            } else {
                return json_encode(array('response_code'=>'207', 'response_message'=>'Portal ID Doesnt Exist.'));
            }
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }

    public function checkDLS($data){
        // var_dump($data);
        $data = str_replace('"', '', $data);
        $id = $data['port'];
        $desc = $data['description'];
        $desc_code = $data['desc_code'];
        // var_dump($data);
        // exit;
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $date = date("Y-m-d H:i:s");
        $officer = $_SESSION['username_sess'];
        $ip = $_SERVER['REMOTE_ADDR'];

        $dbid = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);

        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$id'";
        $trans = $this->db_query($sql);
        $item_code = $trans[0]['item_code'];
        $tin = $trans[0]['tin'];

        $sql = "SELECT * FROM payment_category WHERE id = '$item_code'";
        $result = $this->db_query($sql);
        $amount = $result[0]['amount'];
        $pending = 0;

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null')";
        $result = $this->db_query($sql, false);
        // var_dump($result);
        // exit;
        if ($result > 0) { 
            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        

    }
}



?>