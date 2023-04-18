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
                // $checkExp = $this->getitemlabel('tb_payment_confirmation', 'payment_code', $id, 'expiry_date');
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

        $MDA_ID = "128";
        $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
        $call_back = "https://techhost7x.accessng.com/plateau_transport/RenewcallBack.php";
        $item = "Driving School Renewal";

        $res = $this->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code);
        $Array = json_decode($res, true);
        file_put_contents('initTranzact_log.txt', json_encode($Array, JSON_PRETTY_PRINT) . PHP_EOL);
        // print_r($Array);
        if (!$Array['status'] == "success") {
            return json_encode(array("response_code" => 202, "response_message" => "Could not generate BRN for this record please try again", "message" => $Array['message']));
        }else if ($Array['status'] == "success") {
            $status = $Array['status'];
            $billing_ref = $Array['billing_reference'];
            $mda_ref = $Array['mda_reference'];
            $amount = $Array['amount'];
            $message = $Array['message'];
            

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date,billing_ref,mda_ref,t_table) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null','$billing_ref','$mda_ref','driving_sch_form')";
        $result = $this->db_query($sql, false);

        $sqlInsert = "INSERT INTO transaction_table (portal_id,tin, trans_type, transaction_desc, transaction_amount, payment_mode, posted_ip, created, posted_user, payment_gateway, is_processed,trans_query_id) values ('$id','$tin','$item','Driving school renewal', '$amount', 'CARD', '$ip','$date', '$officer', 'MONIFY', '0','$billing_ref')";
        $this->db_query($sqlInsert, false);
        if ($result > 0) { 

            $sql2 = "UPDATE driving_sch_form SET billing_ref = '$billing_ref' WHERE portal_id = '$id'";
             $this->db_query($sql2, false);
            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
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

        $MDA_ID = "128";
        $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
        $call_back = "https://techhost7x.accessng.com/plateau_transport/RenewcallBack.php";
        $item = "Transport Company Renewal";

        $res = $this->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code);
        $Array = json_decode($res, true);
        file_put_contents('initTranzact_log.txt', json_encode($Array, JSON_PRETTY_PRINT) . PHP_EOL);
        // print_r($Array);
        if (!$Array['status'] == "success") {
            return json_encode(array("response_code" => 202, "response_message" => "Could not generate BRN for this record please try again", "message" => $Array['message']));
        }else if ($Array['status'] == "success") {
            $status = $Array['status'];
            $billing_ref = $Array['billing_reference'];
            $mda_ref = $Array['mda_reference'];
            $amount = $Array['amount'];
            $message = $Array['message'];
        

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date,billing_ref,mda_ref,t_table) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null','$billing_ref','$mda_ref','transport_companies')";
        $result = $this->db_query($sql, false);


        $sqlInsert = "INSERT INTO transaction_table (portal_id,tin, trans_type, transaction_desc, transaction_amount, payment_mode, posted_ip, created, posted_user, payment_gateway, is_processed,trans_query_id) values ('$id','$tin','$item','Transport Company Renewal', '$amount', 'CARD', '$ip','$date', '$officer', 'MONIFY', '0','$billing_ref')";
        $this->db_query($sqlInsert, false);

        if ($result > 0) { 

            $sql2 = "UPDATE transport_companies SET billing_ref = '$billing_ref' WHERE portal_id = '$id'";
             $this->db_query($sql2, false);

            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        
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

        $MDA_ID = "128";
        $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
        $call_back = "https://techhost7x.accessng.com/plateau_transport/RenewcallBack.php";
        $item = "Spare Part Renewal";

        $res = $this->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code);
        $Array = json_decode($res, true);
        file_put_contents('initTranzact_log.txt', json_encode($Array, JSON_PRETTY_PRINT) . PHP_EOL);
        // print_r($Array);
        if (!$Array['status'] == "success") {
            return json_encode(array("response_code" => 202, "response_message" => "Could not generate BRN for this record please try again", "message" => $Array['message']));
        }else if ($Array['status'] == "success") {
            $status = $Array['status'];
            $billing_ref = $Array['billing_reference'];
            $mda_ref = $Array['mda_reference'];
            $amount = $Array['amount'];
            $message = $Array['message'];

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date,billing_ref,mda_ref,t_table) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null','$billing_ref','$mda_ref','driving_sch_form')";
        $result = $this->db_query($sql, false);
        $sqlInsert = "INSERT INTO transaction_table (portal_id,tin, trans_type, transaction_desc, transaction_amount, payment_mode, posted_ip, created, posted_user, payment_gateway, is_processed,trans_query_id) values ('$id','$tin','$item','Spare Part Renewal', '$amount', 'CARD', '$ip','$date', '$officer', 'MONIFY', '0','$billing_ref')";
        $this->db_query($sqlInsert, false);

        if ($result > 0) { 

            $sql2 = "UPDATE driving_sch_form SET billing_ref = '$billing_ref' WHERE portal_id = '$id'";
             $this->db_query($sql2, false);
            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        
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

        $MDA_ID = "128";
        $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
        $call_back = "https://techhost7x.accessng.com/plateau_transport/RenewcallBack.php";
        $item = "Mechanic Garage Renewal";

        $res = $this->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code);
        $Array = json_decode($res, true);
        file_put_contents('initTranzact_log.txt', json_encode($Array, JSON_PRETTY_PRINT) . PHP_EOL);

        if (!$Array['status'] == "success") {
            return json_encode(array("response_code" => 202, "response_message" => "Could not generate BRN for this record please try again", "message" => $Array['message']));
        }else if ($Array['status'] == "success") {
            $status = $Array['status'];
            $billing_ref = $Array['billing_reference'];
            $mda_ref = $Array['mda_reference'];
            $amount = $Array['amount'];
            $message = $Array['message'];

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date,billing_ref,mda_ref,t_table) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null','$billing_ref','$mda_ref','mech_garrage')";
        $result = $this->db_query($sql, false);

        $sqlInsert = "INSERT INTO transaction_table (portal_id,tin, trans_type, transaction_desc, transaction_amount, payment_mode, posted_ip, created, posted_user, payment_gateway, is_processed,trans_query_id) values ('$id','$tin','$item','Mechanic Garage Renewal', '$amount', 'CARD', '$ip','$date', '$officer', 'MONIFY', '0','$billing_ref')";
        $this->db_query($sqlInsert, false);

        if ($result > 0) { 
            $sql2 = "UPDATE mech_garrage SET billing_ref = '$billing_ref' WHERE portal_id = '$id'";
             $this->db_query($sql2, false);

            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        
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

        $MDA_ID = "128";
        $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
        $call_back = "https://techhost7x.accessng.com/plateau_transport/RenewcallBack.php";
        $item = "Dealership Renewal";

        $res = $this->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code);
        $Array = json_decode($res, true);
        file_put_contents('initTranzact_log.txt', json_encode($Array, JSON_PRETTY_PRINT) . PHP_EOL);
        // print_r($Array);
        if (!$Array['status'] == "success") {
            return json_encode(array("response_code" => 202, "response_message" => "Could not generate BRN for this record please try again", "message" => $Array['message']));
        }else if ($Array['status'] == "success") {
            $status = $Array['status'];
            $billing_ref = $Array['billing_reference'];
            $mda_ref = $Array['mda_reference'];
            $amount = $Array['amount'];
            $message = $Array['message'];

        $sql = "INSERT INTO table_payment_renewal (id, portal_id, plate, trans_desc, trans_desc_code, trans_amount, trans_status, renewal_date, client_fullname, officer, bank_code, tin, item_code, expiry_date,billing_ref,mda_ref,t_table) VALUES ('$dbid','$id', 'null','$desc', '$desc_code', '$amount', '$pending', '$date', 'null', '$officer', 'Monify', '$tin', '$item_code', 'null','$billing_ref','$mda_ref','dealership')";
        $result = $this->db_query($sql, false);

        $sqlInsert = "INSERT INTO transaction_table (portal_id,tin, trans_type, transaction_desc, transaction_amount, payment_mode, posted_ip, created, posted_user, payment_gateway, is_processed,trans_query_id) values ('$id','$tin','$item','Dealership renewal', '$amount', 'CARD', '$ip','$date', '$officer', 'MONIFY', '0','$billing_ref')";
        $this->db_query($sqlInsert, false);
      
        $sql2 = "UPDATE dealership SET billing_ref = '$billing_ref' WHERE portal_id = '$id'";
             $this->db_query($sql2, false);
        if ($result > 0) { 
            return json_encode(array('response_code'=>'200', 'response_message'=>'Proceed To Make Payment', "tinforP"=>$id, "status"=>'renew'));   
        } else {
            return json_encode(array('response_code'=>1,'response_message'=>'Renewal Not Successful, Try Again Later')); 
        }
        
    }
    }

    public function RenewInitPay($data){
        $pid = $data['tinforP'];
        $table = $data['table'];
        $MDA_ID = "128";
        $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);

        $sql = "SELECT * FROM table_payment_renewal WHERE portal_id = '$pid'";
        $result = $this->db_query($sql);
        $item = $result[0]['trans_desc'];
        $amount = $result[0]['trans_amount'];
        $item_code = $result[0]['item_code'];
        $portal_id = $result[0]['portal_id'];
        $tin = $result[0]['tin'];
        $brn = $result[0]['billing_ref'];
        $callBack = "https://techhost7x.accessng.com/slip/special_trade_receipt.php?brn=$brn";

        $monify_res = $this->initMonifyPayment($callBack, $brn);

        $obj = json_decode($monify_res, true);
        $redirect_url = $obj['redirect_to_url'];
        $call_back_url = $obj['callback_url'];
        $message = $obj['message'];
        return json_encode(array("response_code" => "200", "redirect_url" => $redirect_url, "callback_url" => $call_back_url, "message" => $message));

    }

    private function initMonifyPayment($call_back, $brn) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://payments.psirs.gov.ng/monnify/initialize_transaction',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{ 
            "brn":"'.$brn.'",
            "callback_url":"'.$call_back.'"
        }
        ',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if($err){
            $errorMsg['status_msg']="fail";
            $errorMsg['status']=false;
            $errorMsg['msg']= 'Curl returned error: ' . $err;
            $this->response = array("status" => "failure", "message" => $errorMsg['msg'], "error" => $errorMsg);
        }
        $serverJSON = json_decode($response, true);

        //LOG THE RESPONSE
        $retval_string = @implode(",", $serverJSON);
        $response = trim($response);
        $retval_string = trim($retval_string);
        //log the response
        // $payload_data = date('Y-m-d H:i:s') . " >>>>  PSIRS Call => ::|". " URL: $url " . " |:: Status Code: $code | Error No.: $curl_errno | $dataSet | DATA: $response | Retval: $retval_string\n\n";
        // file_put_contents($this->logger_filename, $payload_data.PHP_EOL , FILE_APPEND | LOCK_EX);

        return $response;

        curl_close($curl);
    }
}


?>