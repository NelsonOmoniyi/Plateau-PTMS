<?php
include_once("recievePayment.php");
class Offences extends dbobject
{
    public function list($data){
        $table_name    = "tb_offences_payment";
		$primary_key   = "offence_id";
		$columner = array(
			array( 'db' => 'offence_id', 'dt' => 0 ),
			array( 'db' => 'name', 'dt' => 1 ),
			array( 'db' => 'address',  'dt' => 2 ),
			array( 'db' => 'veh_make', 'dt' => 3),
			array( 'db' => 'tin',  'dt' => 4 ),
			array( 'db' => 'phone_number', 'dt' => 5 ),
			array( 'db' => 'id_mark',  'dt' => 6 ),
			array( 'db' => 'chasis_no', 'dt' => 7 ),
			array( 'db' => 'offences',  'dt' => 8 ),
			array( 'db' => 'total_amount', 'dt' => 9 ),
			array( 'db' => 'created', 'dt' => 10 )
			);
		$filter = "";
        
        $datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
    }

    public function SelectOFF($data){
       
        $offences = $data['offences'];
        $plate = $data['platef12'];
        $tin = $data['tinf'];

        $validation = $this->validate($data,
            array('tinf' =>'required','platef12' =>'required'),
            array('tinf'=>'Tax Identification Number','platef12'=>'Plate Number'));
        if(!$validation['error'])
        {
            if (!$plate == "") {
                if ($offences == NULL ) {
                    return json_encode(array("response_code"=>'50', "response_message"=>"The Offences Field Cannot Be Empty."));
                } else {
                    $items = array();
                    foreach($offences as $id) {
                    $items[] = $id;
                    }
        
                    $result = max(array_count_values($items));
                    if($result > 1) {
                        return json_encode(array("response_code"=>'51', "response_message"=>"Duplicated Records Are Not Allowed."));
                        // print_r("Duplicated Records Where Found!");
                    }else {
                        $tot = array();
                        foreach($items as $value) {
                            $sql = "SELECT * FROM offences WHERE id = '$value'";
                            $res = $this->db_query($sql);
                            $price = $res[0]['prices'];
                            $tot[] = $price;
                        }
                        $total = array_sum($tot);
                    // plate
                        $verifyPlateNumber = new Payment();
                        $res = $verifyPlateNumber->verPN($plate);
                        $resArr = json_decode($res, TRUE);
                       
                        // var_dump($res);
                        // exit;
                        // tin
                        $confTIN = new Payment();
                        $rest = $confTIN->ValTIN($tin);
                        $restArr = json_decode($rest, TRUE);

                        if ($resArr['status'] == 'success') {
                            // veh details
                            $make = $resArr['data']['vehicleMake'];
                            $chasis = $resArr['data']['chassisNumber'];
                            $taxPayer = $resArr['data']['taxPayer'];
                            $color = $resArr['data']['vehicleColor'];
                            $model = $resArr['data']['vehicleModel'];
                            if ($restArr['status'] == 'success') {
                                $phone = $restArr['phoneNumber'];
                                $address = $restArr['address'];
                                
                                return json_encode(array('response_code'=>'200', 'response_message'=>'Details Validated Successfully', "total"=>$total, 'make'=>$make, 'chasis'=>$chasis, 'taxPayer'=>$taxPayer, 'color'=>$color, 'model'=>$model, 'plate'=>$plate, "ids"=>$items, 'tin'=>$tin, 'phone'=>$phone, 'address'=>$address));
                            } else {
                                return json_encode(array('response_code'=>'109', 'response_message'=>"Tax Identification Number Not Found!"));
                            }                            
                        }else{
                            return json_encode(array('response_code'=>'10911', 'response_message'=>"Plate Number Not Found!"));
                        }
                    }
                }
            } else {
                return json_encode(array("response_code"=>'52', "response_message"=>"Plate Number Field Is Required."));
            }
        }
        else {
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));  
        }
    }

    public function saveData($data){
        $pending = 0;
        $processed = 1;
        $offences = json_decode($data['arr'], true);
        $tin = $data['tin'];
        $name = $data['name'];
        $address = $data['address'];
        $veh_make = $data['vehmake'];
        $veh_type = $data['vehtype'];
        $veh_cat = $data['vehcat'];
        $payment_category = 'Offences';
        $officer = 'Self Service';
        $station = 'Self Service Portal';
        $date = date("Y-m-d H:i:s");
        $total_price = $data['amount'];
        $offence_count = count($offences);
        $chasis_number = $data['chasis'];
        $plate_number = $data['plateNN'];
        $phone_number = $data['phone'];
        $ref_id_pre = "RTO";
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $processedDate = date('Y-m-d H:i:s');
        // $current_timestamp = time();
        $date = date("Y-m-d H:i:s");
        $officer = $_SESSION['username_sess'];
		$ip = $_SERVER['REMOTE_ADDR'];
        $offence_id = $ref_id_pre.$data['offid'];

        // var_dump($offences);
        // exit;
        $insert = "INSERT INTO tb_offences_payment (offence_id, name, address, veh_make, veh_type, station, category, username, created, offences, total_amount, chasis_no,id_mark, phone_number, tin)
        VALUES ('$offence_id', '$name', '$address', '$veh_make', '$veh_type', '$station', '$payment_category', '$officer', '$date', '$offence_count', '$total_price','$chasis_number', '$plate_number', '$phone_number', '$tin')";
     //    echo $insert;
        $count = $this->db_query($insert,false);
        
        if (count($count) > 0) {
            
            foreach ($offences as $value) {
                $sql = "SELECT * FROM offences WHERE id = '$value'";
                    $exec = $this->db_query($sql);
                    
                    $res = $exec[0];
                    $nameO = $res['offences'];
                    $trans_desc_code = $res['offence_code'];
                    $amount = $res['prices'];
                    $itemcode = $res['id'];

                $insert3 = "INSERT INTO tb_payment_confirmation (payment_code, trans_desc, trans_desc_code, trans_amount, trans_status, officer, offence_id, station, tin, item_code, plate) VALUES ('$offence_id','$nameO', '$trans_desc_code', '$amount', '$pending', '$officer', '$offence_id','$station', '$tin', '$itemcode', '$plate_number')";
                $count = $this->db_query($insert3,false);
                if (count($count) != '1') {
                    return json_encode(array("response_code"=>2009,"response_message"=>'An Unknown Error Occured'));
                }

            }
            $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$offence_id'";
            $exec = $this->db_query($sql);
            foreach ($exec as $value) {
                $offence_id = $value['offence_id'];
                $offence = $value['trans_desc'];
                $amount = $value['trans_amount'];
                $tin = $value['tin'];
                $item_code = $value['item_code'];

                // initialize payment for all items.

            }
            $sql = "INSERT INTO transaction_table (portal_id,tin, transaction_desc, transaction_amount, payment_mode, posted_ip, created, posted_user, payment_gateway, is_processed)values('$offence_id','$tin','RTO','Road Traffic Offences', '$amount', 'CARD', '$ip','$date', '$officer', 'MONIFY', '1')";
            $this->db_query($sql,false);

            $sql = "UPDATE tb_payment_confirmation SET trans_status = '$processed' WHERE offence_id = '$offence_id' AND trans_status = '$pending'";
            $exec = $this->db_query($sql, false);
            // echo $sql;
            if ($exec > 0) {
                $sql2 = "UPDATE tb_offences_payment SET status = '$processed', expiry_date = '$expDate', processed_date = '$processedDate' WHERE offence_id = '$offence_id' AND status = '$pending'";
                $exec = $this->db_query($sql2, false);
                if ($exec > 0) {
                    return json_encode(array("response_code"=>200,"response_message"=>'Success', "reference_code"=> $offence_id)); 
                } else {
                    return json_encode(array("response_code"=>288,"response_message"=>'An Unknown Error Occured'));
                }
            } else {
                return json_encode(array("response_code"=>289,"response_message"=>'An Unknown Error Occured'));
            }
        }else{
            return json_encode(array("response_code"=>0,"response_message"=>'An Error Occured, Please Try Again Later'));
        }
    }

    public function validatePID($data){
        
        $ref = $data['portal_id'];
        $tin = $data['tin'];
        $pending = '0';
        $processed = '1';
        $validation = $this->validate($data,
        array(
            'tin'=>'required',
            'portal_id'=>'required'
        ),
        array('tin'=>'Tax Identification Number', 'portal_id'=>'Portal ID'));
        if(!$validation['error']){
            $sql = "SELECT * FROM `tb_offences_payment` WHERE offence_id = '$ref' AND status = '$pending'";
                $exec = $this->db_query($sql);
                $resP = $exec[0];
                if (!$resP > 0 ) {
                    return json_encode(array("response_code"=>201,"response_message"=>"Invalid Reference ID"));
                } else {
                    // var_dump($res);
                    // exit;
                    $pay = new Payment();
                    $res = $pay->ValTIN($tin);
                    $resArr = json_decode($res, TRUE);
                    if ($resArr['status'] == 'success') {
    
                        $tin = $resArr['tin'];
                       $address = $resP["address"];
                       $vehmake = $resP["veh_make"];
                       $vehtype = $resP["veh_type"];
                       $plate = $resP["id_mark"];
                       $category = $resP["category"];
                       $chasis = $resP["chasis_no"];
                       $offence_count = $resP["offences"];
                       $total_amount = $resP["total_amount"];

                        return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Tax Identification Number','plate'=>$plate,'vehmake'=>$vehmake,'vehtype'=>$vehtype,'tin'=>$tin,'chasis'=>$chasis,'address'=>$address, 'count'=>$offence_count, 'categ'=>$category, 'price'=>$total_amount, 'ref'=>$ref));
                    
                    }
                    else{
                        return json_encode(array('response_code'=>'109', 'response_message'=>"TIN Not Found!"));
                    }
                }
                
        }else
        {
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }

    public function save_initPay($data){
        $ref = $data['ref'];
        $plate = $data['plate'];
        $make = $data['vehmake'];
        $type = $data['vehtype'];
        $chasis = $data['chasis'];
        $address = $data['address'];
        $count = $data['count'];
        $category = $data['categ'];
        $totPrice = $data['price'];
        $tin = $data['tinval'];
        $processed = '1';
        $pending = '0';
        $expDate = date('Y-m-d', strtotime(' + 1 years'));
        $processedDate = date('Y-m-d H:i:s');

        $sql = "SELECT * FROM tb_payment_confirmation WHERE payment_code = '$ref'";
        $exec = $this->db_query($sql);
        foreach ($exec as $value) {
            $offence_id = $value['offence_id'];
            $offence = $value['trans_desc'];
            $amount = $value['trans_amount'];
            $tin = $value['tin'];
            $item_code = $value['item_code'];

            // initialize payment for all items.

        }
        $sql = "UPDATE tb_payment_confirmation SET trans_status = '$processed' WHERE offence_id = '$ref' AND trans_status = '$pending'";
        $exec = $this->db_query($sql, false);
        if ($exec > 0) {
            $sql2 = "UPDATE tb_offences_payment SET status = '$processed', expiry_date = '$expDate', processed_date = '$processedDate' WHERE offence_id = '$ref' AND status = '$pending'";
            $exec = $this->db_query($sql2, false);
            if ($exec > 0) {
                return json_encode(array("response_code"=>200,"response_message"=>'Success', "reference_code"=> $offence_id)); 
            } else {
                return json_encode(array("response_code"=>290,"response_message"=>'An Unknown Error Occured'));
            }
        } else {
            return json_encode(array("response_code"=>291,"response_message"=>'An Unknown Error Occured'));
        }
        
       
        
    }
}


?>