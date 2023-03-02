<?php 
include_once("recievePayment.php");

class Sidenumber extends dbobject{

public function verTIN($data){
    $tin = $data['tax'];
    $plate = $data['plate'];
    $validation = $this->validate($data,
            array(
                'tax' =>'required',
                'plate' => 'required'
            ),
            array('tax'=>'Tax Identification Number', 'plate'=>'Plate Number')
            );
        if(!$validation['error'])
        {
            // call Validate TIN
            $pay = new Payment();
            $res = $pay->ValTIN($tin);
           
            // $resp = $pay->verPN($plate);
            // $respArr = json_decode($resp, TRUE);

            $resArr = json_decode($res, TRUE);
            // print_r($respArr);
            // exit;
            if ($resArr['status'] == 'success') {
                $sql = "SELECT * FROM plate WHERE plate = '$plate'";
                $result = $this->db_query($sql);
                $make = $result[0]['veh_make'];
                $chasis = $result[0]['chasis'];
                $taxPayer = $tin;
                $color = $result[0]['veh_color'];
                $model = $result[0]['veh_model'];
                // if ($respArr['status'] == 'success') {
                //     // veh details
                //     $make = $respArr['data']['vehicleMake'];
                //     $chasis = $respArr['data']['chassisNumber'];
                //     $taxPayer = $respArr['data']['taxPayer'];
                //     $color = $respArr['data']['vehicleColor'];
                //     $model = $respArr['data']['vehicleModel'];

                    $title= $resArr['title'];
                    $firstName = $resArr['first_name'];
                    $midName = $resArr['middle_name'];
                    $Surname = $resArr['surname'];
                    $tin = $resArr['tin'];
                    $mobile = $resArr['phoneNumber'];
                    $address = $resArr['address'];
                    $message = $resArr['message'];
                    return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Tax Identification Number And Plate Number','title'=>$title,'firstname'=>$firstName,'middleName'=>$midName,'surname'=>$Surname,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address, 'make'=>$make, 'chasis'=>$chasis, 'taxPayer'=>$taxPayer, 'color'=>$color, 'model'=>$model, 'plate'=>$plate, ));
                // }else{
                //     return json_encode(array('response_code'=>'109', 'response_message'=>"Plate Number Not Found!"));
                // }
            }
            else{
                // var_dump($message);
                return json_encode(array('response_code'=>'109', 'response_message'=>"TIN Not Found!"));
            }
        }else {
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    
}

public function AuthData($data){
        $tin = $data['tin'];
        $firstName = $data['firstname'];
        $middlename = $data['middlename'];
        $surname = $data['surname'];
        $mobile = $data['mobile'];
        $address = $data['address'];
        $title = $data['title'];
        $plate = $data['plate'];
        $vehType_id = $data['vehicle_typeid'];
        $LO = $data['licence_operators'];
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $processedDate = date('Y-m-d H:i:s');
        $pending = 0;
        $processed = 1;
        // veh details
        $plate = $data['plate'];
        $chasis = $data['chasis'];
        $model = $data['model'];
        $make = $data['make'];
        $color = $data['color'];

        $payment = new Payment();
        $ip = $_SERVER['REMOTE_ADDR'];
        $officer = $_SESSION['username_sess'];
        $MDA_ID = "128";
        $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
        $date = date("Y-m-d H:i:s");
        $item = "Vehicle side number registration";
        $item_code = $vehType_id;
        $call_back = "https://techhost7x.accessng.com/plateau_transport/print/printSN.php?tin=$tin";
        $pending = 0;
        $sql = "SELECT * FROM `vehicle_type` WHERE id ='$vehType_id'";
        $res = $this->db_query($sql);
        $veh_name = $res[0]['vehicle_name'];
        $price = $res[0]['reg'];
        $renewal_amount = $res[0]['renew'];

        $ThreeCharacter = strtoupper($res[0]['short_code']);
        $SDNI = 'PTA/'.$ThreeCharacter.'/'.substr($LO, 0, 3).'';
        $Side_Number = $this->getnextid($SDNI);
        $PaddedSideNumber = $this->paddZeros($Side_Number, 5);
        $SDN = $SDNI.$PaddedSideNumber;

    if ($tin == 'null' || $firstname == 'null' || $middlename =='null' || $surname == 'null' || $mobile == 'null' || $address == 'null' || $title == 'null' || $plate == 'null' || $chasis == 'null' || $model == 'null' || $make == 'null' || $color == 'null') {
        return json_encode(array('response_code'=> 501, 'response_message' => 'Field Cannot Contain NULL As Value'));
    } else {
        $validation = $this->validate($data,
            array(
                'tin' =>'required',
                'firstname' =>'required',
                'middlename' =>'required',
                'mobile' => 'required',
                'surname' => 'required',
                'address' => 'required',
                'title' => 'required',
                'vehicle_typeid' => 'required',
                'licence_operators' => 'required',

                'plate' => 'required',
                'chasis' => 'required',
                'model' => 'required',
                'make' => 'required',
                'color' => 'required'
            ),
            array('tin'=>'Tax Identification Number','firstname'=>'First Name','middlename'=>'Middle Name','mobile'=>'Mobile Number','surname'=>'Surname','address'=>'Address','title'=>'Title','vehicle_typeid'=>'Vehicle Category', 'licence_operators'=> 'Licence Operator', 'plate'=>'Plate Number', 'chasis'=>'Chasis Number', 'model'=>'Vehicle Model', 'make'=>'Vehicle Make', 'color'=>'Vehicle Color')
            );
        if(!$validation['error'])
        {
            if($data['operation'] == "new")
            {
                $sql   = "SELECT * FROM vehicle_sidenumbers WHERE tax = '$tin' AND plate_number = '$plate' LIMIT 1";
                $result = $this->db_query($sql);
                if($result > 0){
                    if ($result[0]['status'] == '1') {
                        return json_encode(array("response_code"=>502,"response_message"=>'A SideNumber For This TIN Have Been Generated')); 
                    } else {
                        // initialize payment
                       
                        $res = $payment->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $price, $call_back, $item_code);
                        $Array = json_decode($res, true);
                        
                        if($Array['status']=="failed"){
                            return json_encode(array("status"=>$Array['status'],"message"=>$Array['message']));
                        }else{
                        $status = $Array['status'];
                        $billing_ref = $Array['billing_reference'];
                        $mda_ref = $Array['mda_reference'];
                        $amount = $Array['amount'];
                        $message = $Array['message'];
                        $call_back = "https://techhost7x.accessng.com/plateau_transport/print/printSN.php?tin=$tin";
                        }

                        $monify_res = $this->initMonifyPayment($call_back, $billing_ref);
                        $obj = json_decode($monify_res, true);
                        $redirect_url = $obj['redirect_to_url'];
                        $call_back_url = $obj['callback_url'];
                        $message = $obj['message'];

                        $sql = "INSERT INTO transaction_table (portal_id,tin, trans_type, transaction_desc, transaction_amount, payment_mode, posted_ip, created, posted_user, payment_gateway, is_processed,trans_query_id) values ('$SDN','$tin','$item','Registration', '$amount', 'CARD', '$ip','$date', '$officer', 'MONIFY', '1','$billing_ref')";
                        $info = $this->db_query($sql,false);

                        return json_encode(array("response_code"=>"200","redirect_url"=>$redirect_url,"callback_url"=>$call_back_url,"message"=>$message));
                        // ------------------------------------------------------------------------
                        // update sidenumber status to processed
                     
                        $sql2 = "UPDATE vehicle_sidenumbers SET status = '$processed', expiry_date = '$expDate', processed_date = '$processedDate' WHERE tax = '$tin' AND status = '$pending'";
                        $exec = $this->db_query($sql2, false);
                        

                        $sqlSdate0 = "SELECT created,side_number,amount,payment_pin FROM vehicle_sidenumbers WHERE side_number = '$SDN'";
                        $resultDate0 = $this->db_query($sqlSdate0);
                        $startDate0 = $resultDate0[0]['created'];
                        $side = $resultDate0[0]['side_number'];
                        $amount = $resultDate0[0]['amount'];
                        // $expiryDate0 = date('Y-m-d', strtotime('+1 year', strtotime($startDate0)));

                        $sqlTT = "insert into sidenumber_transaction (sidenumber,initial_payment,renewal_fee,initial_payment_date,expiry_date,renewal_date,payment_pin)
                        values('$side','$amount','$renewal_amount', NOW(),'$expDate','$expDate',NULL)";

                        $Stransac = $this->db_query($sqlTT);

                        file_put_contents('validate-tin.txt', '@'.$sqlTT.date("Y-m-d H:i:s").PHP_EOL, FILE_APPEND | LOCK_EX);
                        

                        if ($exec > 0) {
                            
                            return json_encode(array("response_code"=>0,"response_message"=>'Success', "tin"=>$tin));
                            } else {
                            return json_encode(array("response_code"=>288,"response_message"=>'An Unknown Error Occured'));
                        }
                        
                    }
                }else{
                   $sql = "INSERT INTO vehicle_sidenumbers (id,firstname,tax,middlename,surname,mobile,address,vehicle_typeid,licence_operators,side_number,created,status,plate_number, vehicle_make, chasis_number, vehicle_color,vehicle_model, payment_pin, used_pin,amount, pin_generation_date, NIN,processed_date, expiry_date, issue_date, print_count, comments, pictures, confirmed_review, lga)values(NULL,'$firstName','$tin','$middlename', '$surname','$mobile','$address','$vehType_id','$LO','$SDN','$date', '$pending','$plate', '$make','$chasis', '$color', '$model', ' ','0', '$price', ' ', ' ', ' ', ' ', '$date','0', ' ', ' ', '0', ' ')";
                    $exec = $this->db_query($sql,false);
                    // var_dump($sql);
                    if($exec > 0 ){
                        // initailize payment 
                        $res = $payment->intializeTransOP($MDA_ID, $MDA_re_id, $item, $tin, $price, $call_back, $item_code);
                        $Array = json_decode($res, true);
                        
                        if($Array['status']=="failed"){
                            return json_encode(array("status"=>$Array['status'],"message"=>$Array['message']));
                        }else{
                        $status = $Array['status'];
                        $billing_ref = $Array['billing_reference'];
                        $mda_ref = $Array['mda_reference'];
                        $amount = $Array['amount'];
                        $message = $Array['message'];
                        $call_back = "https://techhost7x.accessng.com/plateau_transport/print/printSN.php?tin=$tin";
                        }

                        $monify_res = $this->initMonifyPayment($call_back, $billing_ref);
                        $obj = json_decode($monify_res, true);
                        $redirect_url = $obj['redirect_to_url'];
                        $call_back_url = $obj['callback_url'];
                        $message = $obj['message'];
                        // ------------------------------------------------------------------------
                        return json_encode(array("response_code"=>"200","redirect_url"=>$redirect_url,"callback_url"=>$call_back_url,"message"=>$message));

                        // update sidenumber status to processed
                       
                    }else{
                        return json_encode(array("response_code"=>503,"response_message"=>'An Error Occured !'));
                    } 
                }
            } 
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
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

public function SideNum_list($data){
    // return a list of generated sidenumbers
		$table_name    = "vehicle_sidenumbers";
		$primary_key   = "id";
		$columner = array(
			array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'firstname',  'dt' => 1 ),
			array( 'db' => 'chasis_number',  'dt' => 2 ),
			array( 'db' => 'tax',  'dt' => 3),
			array( 'db' => 'side_number',   'dt' => 4),
            array( 'db' => 'plate_number',   'dt' => 5, 'formatter' => function($d,$row){
                if ($row['side_number'] == NULL) {
                    return '';
                }else{
                    return $d;
                }
            }),
			array( 'db' => 'created',   'dt' => 6),
			array( 'db' => 'issue_date',   'dt' => 7, 'formatter'=> function($d, $row){
                if ($d == '') {
                    return "Pending";
                } else {
                    return $d;
                }
                
            }),
			array( 'db' => 'vehicle_typeid',   'dt' => 8, 'formatter' => function($d,$row){
                $sqlV = "SELECT vehicle_name FROM vehicle_type WHERE id = '$d' LIMIT 1;";
                // file_put_contents("List.txt",$sqlV);
                $resultV = $this->db_query($sqlV);
                if($resultV > 0){
                    return "".$resultV[0]['vehicle_name']."";
                }else{
                    return "$d";
                }
            }),
            array( 'db' => 'licence_operators', 'dt' => 9),
            array( 'db' => 'status',   'dt' => 10, 'formatter' => function($d,$row){
                if ($_SESSION['role_id_sess'] != '001') {
                    if (!$d > 0) {
                        return  "Pending Payment";
                    } else { 
                        return  "PAID | <a href='print/printSN.php?tin=".$row['tax']."' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Sidenumber</a>";
                    }
                } else {
                    if (!$d > 0) {
                        return  "Not Paid";
                    } else {
                        return  "Paid";
                    }
                }
            }),
            array( 'db' => 'expiry_date', 'dt' => 11, 'formatter'=> function($d, $row){

                $created = $row['created'];
                // return  $d;
                if (date('Y-m-d') > $d) {
                    // Edited to suite
                    return  'Expired  | <a class="btn btn-primary btn-sm" onclick="getModal(\'setup/preview_side_number.php?id='.$row['side_number'].'\',\'modal_div\')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Renew</a>';
                    // Editing ends
                } else {
                    return  "Not Expired";
                }
            })
			);
		$filter = "";
        
        // var_dump($data);
        $datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);

    
}

public function myTax($data){
       
        $table_name    = "tax";
		$primary_key   = "sidenumber_id";
		$columner = array(
			array( 'db' => 'id', 'dt' => 0 ),
			array( 'db' => 'firstname', 'dt' => 1 ),
			array( 'db' => 'surname', 'dt' => 2 ),
			array( 'db' => 'middlename', 'dt' => 3),
			array( 'db' => 'msisdn', 'dt' => 4),
			array( 'db' => 'dateofbirth', 'dt' => 5 ),
			array( 'db' => 'gender', 'dt' => 6 ),
			array( 'db' => 'state', 'dt' => 7 ),
            array( 'db' => 'lga', 'dt' => 8),
			array( 'db' => 'passport', 'dt' => 9)
		);
		
        // $filter = "";
       
        $filter = " AND sidenumber_id='".$data['id']."'";
            
        $datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
}


public function saveData($data)
{

    $comments = $data['comments'];
    $id = $data['sidenumber'];
    $LO = $data['LicenseOp'];
    $VT = $data['VehicleTYP'];
    $LOL = array_splice($LO,2);
    $ThreeCharacter = substr($LO, 1, 3);
    $processed = 1;
    $declined = 2;
    $pending = 0;
    $SDNI = ''.$ThreeCharacter.'/'.$VT.'';
    $print_count = 0;
    $PC = $print_count = $print_count + 1;
    // PIN

    $pin = $data['pin'];

    $renewal_price = "";
    $renewal_date = " ";
    $now = date();
    $currentDateTime = date('H:i:s');
    $year = date('Y') - 1;
    $end = mktime(0, 0, 0, 12, 31, $year);
    $yearEnd = date('Y-m-d', strtotime('12/31'));
    // $expiry_date = new \DateTime('last day of December this year');

    $Side_Number = $this->getnextid(''.$SDNI.'');
   $PaddedSideNumber = $this->paddZeros($Side_Number, 4);
        
                    
    $file_data = $data['_files'];
    $ff   = $this->saveMerchantImage($file_data,"uploads/","");
    $ff   = json_decode($ff,true);
    // var_dump($ff);
    
    // exit;
    if($ff['response_code'] == "0"){
        $full_path = $ff['data'];
        
        if($data['declined'] == 2){

            $DSQL = "UPDATE vehicle_sidenumbers SET comments = '$comments', status = '2', pictures = '$full_path' WHERE id = '$id'";
            $count = $this->db_query($DSQL,false);
            if($count == 1)
                {
                    return json_encode(array("response_code"=>0,"response_message"=>'Process Declined... <br/> Please Contact An Admin To Review It.'));
                } 
                else
                {
                    return json_encode(array("response_code"=>78,"response_message"=>'Process Failed!'));
                }
        }else{
            $UpSQLP = "UPDATE tb_payment_confirmation SET trans_status = '$processed', trans_used_date = CURDATE() ,apprved_trans = '$processed' WHERE trans_ref = '$id'";
                // file_put_contents('nelson.txt',$UpSQLP);
            $count = $this->db_query($UpSQLP,false); 
            // var_dump($UpSQLP);
            // exit;
            if($count == 1)
            {
                $UpSQL = "UPDATE vehicle_sidenumbers SET comments = '$comments', status = '$processed', pictures = '$full_path', side_number = '$SDNI/$PaddedSideNumber', issue_date = CURDATE() WHERE id = '$id'";
                
                $count = $this->db_query($UpSQL,false); 
                if($count == 1)
                {
                    $sqlSdate0 = "SELECT created,side_number,amount,payment_pin FROM vehicle_sidenumbers WHERE side_number = '$SDNI/$PaddedSideNumber'";
                        $resultDate0 = $this->db_query($sqlSdate0);
                        $startDate0 = $resultDate0[0]['created'];
                        $side = $resultDate0[0]['side_number'];
                        $amount = $resultDate0[0]['amount'];
                        $pin = $resultDate0[0]['payment_pin'];
                        $expiryDate0 = date('Y-m-d', strtotime('+1 year', strtotime($startDate0)));

                    $sqlTT = "insert into sidenumber_transaction (id,sidenumber,initial_payment,renewal_fee,initial_payment_date,expiry_date,renewal_date,payment_pin)
                    values(NULL,'$ThreeCharacter/$VT/$PaddedSideNumber','$amount','$renewal_price', NOW(),'$expiryDate','$renewal_date','$pin')";

                    $Stransac = $this->db_query($sqlTT);

                    return json_encode(array("response_code"=>0,"message"=>'Side-Number Generated Successfully',"id"=>$id));
                } 
                else
                {
                    return json_encode(array("response_code"=>78,"response_message"=>'Failed To Generate SN'));
                }
            }else{
                $checkState = "SELECT * FROM vehicle_sidenumbers WHERE id = '$id'";
                $res = $this->db_query($checkState);
                $status = $res[0]['status'];
                if ($status == 0) {
                    $UpSQL = "UPDATE vehicle_sidenumbers SET comments = '$comments', status = '$processed', pictures = '$full_path', side_number = '$SDNI/$PaddedSideNumber', issue_date = CURDATE() WHERE id = '$id'";
                
                    $count = $this->db_query($UpSQL,false); 
                    if($count == 1)
                    {
                        $sqlSdate0 = "SELECT created,side_number,amount,payment_pin FROM vehicle_sidenumbers WHERE side_number = '$SDNI/$PaddedSideNumber'";
                            $resultDate0 = $this->db_query($sqlSdate0);
                            $startDate0 = $resultDate0[0]['created'];
                            $side = $resultDate0[0]['side_number'];
                            $amount = $resultDate0[0]['amount'];
                            $pin = $resultDate0[0]['payment_pin'];
                            $expiryDate0 = date('Y-m-d', strtotime('+1 year', strtotime($startDate0)));

                        $sqlTT = "insert into sidenumber_transaction (id,sidenumber,initial_payment,renewal_fee,initial_payment_date,expiry_date,renewal_date,payment_pin)
                        values(NULL,'$ThreeCharacter/$VT/$PaddedSideNumber','$amount','$renewal_price', NOW(),'$expiryDate','$renewal_date','$pin')";

                        $Stransac = $this->db_query($sqlTT);

                        return json_encode(array("response_code"=>0,"message"=>'Side-Number Generated Successfully',"id"=>$id));
                    } 
                    else
                    {
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed To Generate SN'));
                    }
                } else {
                    return json_encode(array("response_code"=>0,"message"=>'Side-Number Generated Successfully',"id"=>$id));
                }
                
            }
        }
        
    }
    else
    {
        $full_path = $ff['data'];
        
        if($data['declined'] == 2){

            $DSQL = "UPDATE vehicle_sidenumbers SET comments = '$comments', status = '2', pictures = '$full_path' WHERE id = '$id'";
            //  file_put_contents("decline.txt",$DSQL);
            //         exit;
            $count = $this->db_query($DSQL,false);
            //  var_dump($count);
            //  exit;
            if($count == 1)
                {
                    return json_encode(array("response_code"=>0,"response_message"=>'Process Declined... <br/> Please Contact An Admin To Review It.'));
                } 
                else
                {
                    return json_encode(array("response_code"=>78,"response_message"=>'Process Failed!'));
                }
        }else{
        $UpSQL = "UPDATE vehicle_sidenumbers SET comments = '$comments', status = '$processed', pictures = '$full_path', side_number = '$ThreeCharacter/$VT/$PaddedSideNumber', issue_date = CURDATE(), print_count = '$PC' WHERE id = '$id'";
        // file_put_contents("printD.txt",$UpSQL);
        // var_dump($UpSQL);
        // exit;
            $count = $this->db_query($UpSQL,false); 
            if($count == 1)
                {
                    $sqlSdate = "SELECT created,side_number,amount,payment_pin FROM vehicle_sidenumbers WHERE side_number = '$SDNI/$PaddedSideNumber'";
                        $resultDate = $this->db_query($sqlSdate);
                        $startDate = $resultDate[0]['created'];
                        $side = $resultDate[0]['side_number'];
                        $amount = $resultDate[0]['amount'];
                        $pin = $resultDate[0]['payment_pin'];
                        // print_r(" 2 ".$side.", ".$amount);
                        // exit;
                        $expiryDate = date('Y-m-d', strtotime('+1 year', strtotime($startDate)));

                    $sqlTT = "insert into sidenumber_transaction (id,sidenumber,initial_payment,renewal_fee,initial_payment_date,expiry_date,renewal_date,payment_pin)
                     values(NULL,'$side','$amount','$renewal_price', NOW(),'$expiryDate','$renewal_date', '$pin')";

                    $Stransac = $this->db_query($sqlTT);
                    
                    // var_dump(''.$ThreeCharacter.'/'.$VT.'/'.$PaddedSideNumber);
                    // var_dump($price);
                    // var_dump($renewal_price);
                    // var_dump($futureDate);
                    // exit;

                    return json_encode(array("response_code"=>0,"message"=>'Side-Number Generated Successfully',"id"=>$id));
                } 
                else
                {
                    return json_encode(array("response_code"=>78,"response_message"=>'Failed To Generate SN'));
                }
        }
        // return json_encode(array('Nelson'=>'Gotcha', 'response_code'=>'71','response_mesage'=>$ff['response_message']));
    }
                    
                   
}

public function renew($data){
    // var_dump($data);
    $sidenumber = $data['sidenumber'];
    // $pin = $data['renewal_pin'];
    $validation = $this->validate($data,
        array(
            'sidenumber' =>'required',
            // 'renewal_pin' =>'required'
        ),
        array(
            'sidenumber'=>'Side Number'
            // 'renewal_pin'=>'Pin'
        ));

        if(!$validation['error'])
        {

            $sqlSdate = "SELECT * FROM vehicle_sidenumbers WHERE side_number = '$sidenumber' LIMIT 1";
            $resultDate = $this->db_query($sqlSdate);
            $amounts = $resultDate[0]['amount'];
            $id = $resultDate[0]['id'];
            $firstname = $resultDate[0]['firstname'];
            $middlename = $resultDate[0]['middlename'];
            $surname = $resultDate[0]['surname'];
            $plate = $resultDate[0]['plate_number'];
            $tin = $resultDate[0]['tax'];
            $fullname = "$firstname $middlename $surname";
            $vehicle_typeid = $resultDate[0]['vehicle_typeid'];
            $amount = $this->getitemlabel('vehicle_type', 'id', $vehicle_typeid, 'renew');
            // var_dump($sqlSdate);
            // exit;
            $count = count($resultDate);
            if ($count > 0) {
                $startDate = date('Y-m-d');
                $expiryDate = date('Y-m-d', strtotime('+1 year', strtotime($startDate)));
                $renewSql = "UPDATE sidenumber_transaction SET renewal_fee = '$amount', renewal_date = CURDATE(), expiry_date = '$expiryDate' WHERE sidenumber = '$sidenumber'";
                    // var_dump($renewSql);
                    // exit;
                    $counta = $this->db_query($renewSql,false);
                    $count = count($counta);
                    // var_dump($renewSql);
                if($count > 0)
                {
                    $renewSql = "UPDATE vehicle_sidenumbers SET used_pin = '0', expiry_date = '$expiryDate' WHERE side_number = '$sidenumber'";
                
                    $this->db_query($renewSql,false);

                    
                    $renewSql0 = "INSERT into table_payment_renewal (portal_id,plate,trans_desc,trans_desc_code,trans_amount,trans_status,trans_processed_date,client_fullname,tin,item_code,expiry_date,renewal_date) VALUES ('$sidenumber','$plate','Renewal','SNR','$amount','1',CURDATE(),'$fullname','$tin','$vehicle_typeid','$expiryDate','$expiryDate')";
                    $this->db_query($renewSql0,false);
                    return json_encode(array('response_code'=>0,'response_message'=>'Renewal Successful'));
                }else{
                        return json_encode(array("response_code"=>78, "response_message"=>'Side Number Renewal Process Failed!!'));
                }
            }else{
                return json_encode(array("response_code"=>99,"response_message"=>'Invalid Side Number or Renewal Pin'));
            }
        }     

}

public function ajax($data){
    $id = $data['id'];
    $sql = "SELECT * FROM vehicle_type WHERE id = '$id'";
    $res = $this->db_query($sql);
    $res = $res[0]['track'];
    
    return json_encode(array("data"=>$res));

} 

public function Report($data){
	$table_name    = "vehicle_sidenumbers";
    $primary_key   = "id";
    $columner = array(
        array( 'db' => 'id', 'dt' => 0 ),
        array( 'db' => 'firstname',  'dt' => 1 ),
        array( 'db' => 'side_number',  'dt' => 2 ),
        array( 'db' => 'tax',  'dt' => 3),
        array( 'db' => 'plate_number',   'dt' => 4),
        array( 'db' => 'issue_date',   'dt' => 5),
        array( 'db' => 'expiry_date',   'dt' => 6),
        array( 'db' => 'amount',   'dt' => 7),
        array( 'db' => 'vehicle_typeid',   'dt' => 8, 'formatter' => function($d,$row){
            $sqlV = "SELECT vehicle_name FROM vehicle_type WHERE id = '$d' LIMIT 1;";
           
            $resultV = $this->db_query($sqlV);
            if($resultV > 0){
                return "".$resultV[0]['vehicle_name']."";
            }else{
                return "$d";
            }
        }),
        array( 'db' => 'licence_operators', 'dt' => 9),
        array( 'db' => 'status',   'dt' => 10, 'formatter' => function($d,$row){
            if ($_SESSION['role_id_sess'] != '001') {
                if (!$d > 0) {
                    return  "Not Paid";
                } else {
                    return  "Paid | <button onclick=\"getModal()\" class='btn btn-success' href='javascript:void(0)'>Print Side Number</button>";
                }
            } else {
                if (!$d > 0) {
                return  "Pending Payment";
                } else {
                    return  "PAID ";
                }
            }
        })
        );
    $filter = "";
    
    // var_dump($data);
    $datatableEngine = new engine();

    echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);


    
}

}



?>