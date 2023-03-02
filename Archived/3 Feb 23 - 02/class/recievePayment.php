<?php 
 $MDA_ID = "128";
 $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
 
class Payment extends dbobject{

    public function InitPay($data){
        
        $ren = $data['renew'];
        $pid = str_replace('"', '', $data['tinforP']);
        $table = $data['table'];
        $MDA_ID = "128";
        $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
        if ($ren == 'renew') {
            $sql = "SELECT * FROM table_payment_renewal WHERE portal_id = '$pid'";
            $result = $this->db_query($sql);
            $item = $result[0]['trans_desc'];
            $amount = $result[0]['trans_amount'];
            $item_code = $result[0]['item_code'];
            $portal_id = $result[0]['portal_id'];
        }else{
            $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$pid'";
            $result = $this->db_query($sql);
            $item = $result[0]['trans_desc'];
            $amount = $result[0]['trans_amount'];
            $item_code = $result[0]['item_code'];
            $portal_id = $result[0]['payment_code'];
            $tin = $result[0]['tin'];
        }
        
        
        
        $call_back = "https://techhost7x.accessng.com/plateau_transport/slip/special_trade_receipt.php?pid=$pid&&item_code=$item_code&&tbl=$table";
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $processedDate = date('Y-m-d H:i:s');
        $date = date("Y-m-d H:i:s");
        $officer = $_SESSION['username_sess'];
		$ip = $_SERVER['REMOTE_ADDR'];
        // $headers  = $this->formatHeader($_SERVER);
        $pending = 0;
        $processed = 1;
        // initialize Open Payment API.
        $res = $this->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code);
        $Array = json_decode($res, true);
        // print_r($Array);
        if($Array['status']=="failed"){
            return json_encode(array("status"=>$Array['status'],"message"=>$Array['message']));
        }else{
        $status = $Array['status'];
        $billing_ref = $Array['billing_reference'];
        $mda_ref = $Array['mda_reference'];
        $amount = $Array['amount'];
        $message = $Array['message'];
        // print_r($Array);
        $call_back = "https://techhost7x.accessng.com/plateau_transport/slip/special_trade_receipt.php?pid=$pid&&item_code=$item_code&&tbl=$table";
        }
        
        

        // call bcak live url
        // $call_back = "https://techhost7x.accessng.com/plateau_transport/slip/spare_slip.php?pid=$pid&&item_code=$item_code&&tbl=$table";
        // comment to initialize real payment
        // initialize Monify Payment
        $monify_res = $this->initMonifyPayment($call_back, $billing_ref);
        $obj = json_decode($monify_res, true);
        $redirect_url = $obj['redirect_to_url'];
        $call_back_url = $obj['callback_url'];
        $message = $obj['message'];
        // var_dump($obj);
        

        
        if ($ren == 'renew') {
            $sql = "UPDATE table_payment_renewal SET trans_status = '$processed', expiry_date = '$expDate' WHERE portal_id = '$pid' AND trans_status = '$pending'";
            $res = $this->db_query($sql, false);
            if ($res > 0) {
                $sql2 = "UPDATE $table SET expiry_date = '$expDate' WHERE portal_id = '$pid'";
                $exec = $this->db_query($sql2, false);
                return json_encode(array('response_code'=>'200', 'response_message'=>'Success', "pid"=>$pid, "tin"=>$result[0]['tin']));
            } else {
                return json_encode(array("response_code"=>289,"response_message"=>'An Unknown Error Occured'));
            }
        } else {
            $sql = "INSERT INTO transaction_table (portal_id,tin, trans_type, transaction_desc, transaction_amount, payment_mode, posted_ip, created, posted_user, payment_gateway, is_processed,trans_query_id) values ('$portal_id','$tin','$item','Registration', '$amount', 'CARD', '$ip','$date', '$officer', 'MONIFY', '1','$billing_ref')";
            $info = $this->db_query($sql,false);
            
            file_put_contents('validate-tin.txt', '@'.$sql.date("Y-m-d H:i:s").PHP_EOL, FILE_APPEND | LOCK_EX);
            
            return json_encode(array("response_code"=>"200","redirect_url"=>$redirect_url,"callback_url"=>$call_back_url,"message"=>$message));
        
            // if payment is successful
          

            // $sql = "UPDATE tb_payment_confirmation SET trans_status = '$processed' WHERE payment_code = '$pid' AND trans_status = '$pending'";
            // $exec = $this->db_query($sql, false);
            // if ($exec > 0) {
            //     $sql2 = "UPDATE $table SET status = '$processed', expiry_date = '$expDate', processed_date = '$processedDate' WHERE portal_id = '$pid' AND status = '$pending'";
            //     $exec = $this->db_query($sql2, false);
            //     if ($exec > 0) { 
                
            //         return json_encode(array('response_code'=>'200', 'response_message'=>'Success', "pid"=>$pid, "tin"=>$result[0]['tin']));
            //     } else {
            //         return json_encode(array("response_code"=>288,"response_message"=>'An Unknown Error Occured'));
            //     }
            // } else {
            //     return json_encode(array("response_code"=>289,"response_message"=>'An Unknown Error Occured'));
            // }
        }
        
    }


    public function check_number($number) {
      
        $notDig = 'input is not a digit';
        $notConv = 'not starting from 080, 070, 081, 090';
        $not11 = 'not 11 characters';
        $fine = 'Correct';
        $emp = 'empty input';
        
        //Lets really know if the input is not empty, which if it is, return false
        if(!$number) {
           return '100';
        }
     
        //Checking if its really numerics
        elseif(!is_numeric($number)) {
           return '101';
        }
     
        //Checking if number starts with 080, 090, 070 and 081
        elseif(!preg_match('/^080/', $number) and !preg_match('/^070/', $number) and !preg_match('/^090/', $number) and !preg_match('/^081/', $number)) {
           return '102';
        }
     
        //Check if the length is 11 digits
        elseif(strlen($number)!==11) {
           return '103';
        }
     
        //Every requirements are made
        else {
           return '200';
        }
    }

    public function intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $amount, $call_back, $item_code){
        // var_dump($MDA_ID." --- ".$MDA_re_id." ---- ".$item." --- ".$tin." --- ".$amount." --- ".$call_back." --- ".$item_code);
        // exit;
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://payments.psirs.gov.ng/OpenPaymentsApi/initialize_transaction',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
                    "mda_id": "'.$MDA_ID.'",
                    "mda_reference": "'.$MDA_re_id.'",
                    "tax_item":"'.$item.'",
                    "tin": "'.$tin.'",
                    "amount":"'.$amount.'",
                    "callback_url": "'.$call_back.'",
                    "tax_code": "'.$item_code.'"
                    }',
        CURLOPT_HTTPHEADER => array(
            ': ',
            'Content-Type: application/json'
        ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function get_MDA(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://3.9.45.117/mdas/list',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function itemList(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://3.9.45.117/tax_items/list',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res= json_decode($response, true);
        return $res;

    }

    public function confPIDTIN($data){
       
        $PID = $data['portal_id'];

        $validation = $this->validate($data,
            array(
                'portal_id' =>'required'
            ),
            array('portal_id'=>'Portal ID','tin'=>'Tax Identification Number')
            );

        if(!$validation['error']){

            $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$PID'";
            $result = $this->db_query($sql);
            if ($result[0]['trans_status'] == '1') {
                return json_encode(array('response_code'=>'99', 'response_message'=>'Portal ID Have Been Used'));
            } else if ($result == NULL) {
                return json_encode(array('response_code'=>'99', 'response_message'=>'Invalid Portal ID'));
            } else {
                     // call Validate TIN
                $sql = "SELECT * FROM driving_sch_form WHERE portal_id = '$PID'";
                $result = $this->db_query($sql);
                $count = count($result);
                $res = $result[0];
                // var_dump($res);
                // exit;
                if ($count > 0) {
                    $name = $res['school_name'];
                    $owner_name = $res['proprietor_name'];
                    $email = $res['email_add'];
                    $tin = $res['tin'];
                    $mobile = $res['phone'];
                    $address = $res['address'];
                    
                    return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Portal Identification Number','name'=>$name,'owner_name'=>$owner_name,'email'=>$email,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'port'=>$PID)); 
                        
                } else {
                    return json_encode(array('response_code'=>'909', 'response_message'=>'Wrong Portal ID, kindly check the service registered for.'));
                }
                
            }
                
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
        



    }
    // validate TIN
    public function ValTIN($data){

        $sql = "SELECT * FROM tin_table WHERE tax_number = '$data'";
        $res = $this->db_query($sql);
        $info = $res[0];
        $tin = $info['tax_number'];
        $phone = $info['phone'];
        $address = $info['address'];
        $type = $info['type'];
        $status = $info['status'];
        $title = $info['title'];
        $name = $info['name'];
        $firstname = $info['firstname'];
        $middlename = $info['middlename'];
        $surname = $info['surname'];
        $message = $info['message'];
        // file_put_contents('validate-tin.txt', ''.$res.date("Y-m-d H:i:s").PHP_EOL, FILE_APPEND | LOCK_EX);

        if ($res > 0) {
            return json_encode(array("status"=>$status, "message"=>$message, "title"=>$title, "name"=>$name, "first_name"=>$firstname, "middle_name"=>$middlename, "surname"=>$surname, "tin"=>$tin, "phoneNumber"=>$phone, "address"=>$address, "account_type"=>$type,));
            // var_dump($res);
            // exit;
        } else {
           
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://payments.psirs.gov.ng/OpenPaymentsApi/validate_tin/'.$data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            file_put_contents('validate-tin.txt', 'TIN @'.$data. $response.date("Y-m-d H:i:s").PHP_EOL, FILE_APPEND | LOCK_EX);
            return $response;
            // logInputs('TIN @ '.date("Y-m-d H:i:s"),$failed," TIN Response ");
        }   

    }
    // plate number
    public function verPN($data){


        $curl = curl_init();
    
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://mla.plsg.io/plate_number_validity/'.$data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        return $response;
        logInputs('Plate @ '.date("Y-m-d H:i:s"),$response," Plate Number Response ");
        // $resArr = json_decode($response, TRUE);
        // $make = $resArr['data']['vehicleMake'];
        // $chasis = $resArr['data']['chassisNumber'];
        // $taxPayer = $resArr['data']['taxPayer'];
        // $color = $resArr['data']['vehicleColor'];
        // $model = $resArr['data']['vehicleModel'];
        // $phone = $resArr['data']['PhoneNumber'];
        // $expDate = $resArr['data']['Expirydate'];
        // $status = $resArr['data']['PlateNumber'];
        // $plate = $data;

        // $sql = "INSERT INTO plate (Name, chasis, veh_make, veh_color, veh_model,phone, expiry_date, status, plate) VALUES ('$taxPayer', '$chasis', '$make', '$color', '$model', '$phone', '$expDate', '$status', '$plate')";
        // $check = $this->db_query($sql,false);
        
    }

    public function checkTDetails($data){

        $validation = $this->validate($data,
        array(
            'first_name' =>'required',
            'middle_name' =>'required',
            'phoneNumber' =>'required',
            'tinval'=> 'required'
        ),
        array('first_name'=>'First Name','middle_name'=>'Middle Name','surname'=>'Surname','phoneNumber'=>'Phone Number', 'tinval'=>'Tax Identification Number')
        );
        $created = date("Y-m-d H:i:s");
        if ($validation['error']) {
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        } else {

            $sql = "UPDATE transaction_table SET firstname = '$data[first_name]', middlename = '$data[middle_name]', mobilenumber = '$data[phoneNumber]', created = '$created', trans_type = 'DSL' WHERE tin = '$data[tinval]'";
            $count = $this->db_query($sql,false); 
            $exec = count($count);
            if($exec > 0 ){
                return json_encode(array("response_code"=>200,"response_message"=>'Succcess', "port"=>$data['port']));
            }else{
                return json_encode(array("response_code"=>409,"response_message"=>'AN ERROR OCCURED! Pls Try Again Later'));
            }
           
        }
        
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
        $payload_data = date('Y-m-d H:i:s') . " >>>>  PSIRS Call => ::|". " URL: $url " . " |:: Status Code: $code | Error No.: $curl_errno | $dataSet | DATA: $response | Retval: $retval_string\n\n";
        file_put_contents($this->logger_filename, $payload_data.PHP_EOL , FILE_APPEND | LOCK_EX);

        return $response;

        curl_close($curl);
    }

    public function generateTIN($data){
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://payments.psirs.gov.ng/tin/register_user',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "phone": "'.$data['mobile'].'",
            "title": "'.$data['title'].'",
            "company_name": "'.$data['company_name'].'",
            "rc_no": "'.$data['rc_number'].'",
            "occupation": "'.$data['occupation'].'",
            "soo": "'.$data['soo'].'",
            "dob": "'.$data['dob'].'",
            "payer_address": "'.$data['padd'].'",
            "nationality": "'.$data['nationality'].'",
            "business_industry": "'.$data['bi'].'",
            "marital_status": "'.$data['mstatus'].'",
            "employee_count": '.$data['empcount'].',
            "lga": "'.$data['lga'].'",
            "business_location": "'.$data['blocation'].'",
            "state": "'.$data['state'].'",
            "industry": "'.$data['industry'].'",
            "address": "'.$data['address'].'",
            "website": "'.$data['website'].'",
            "email": "'.$data['email'].'",
            "office_number": "'.$data['onumber'].'",
            "home_town": "'.$data['hometown'].'"
            }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);

       
        return $response;      
    }

    function formatHeader($headers)
    {
        foreach($headers as $key => $value) 
        {
            if (substr($key, 0, 5) <> 'HTTP_') 
            {
                continue;
            }
            $header = str_replace(' ', '-', strtolower(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;	
    }

    function logInputs($tag,$details,$folder)
    {
        $target_dir = 'Logs/'.$folder.'/'.date("Y_m")."/";
        if (!file_exists($target_dir)) 
        {
            mkdir($target_dir, 0777, true);
        }
        $det=is_array($details)?json_encode($details):$details;
        $det .= "\r\nHeader sent : \r\n".json_encode(apache_request_headers());
        file_put_contents($target_dir."response_".date('Ymd').".txt",$tag."	@ ".date('H:i:s')."\r\n".$det."\r\n"."=====================================\r\n".PHP_EOL,FILE_APPEND);
    }

}
?>