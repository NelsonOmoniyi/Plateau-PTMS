<?php
 
include_once('../libs/dbfunctions.php');
include_once('../class/recievePayment.php');

class API extends dbobject{
//    validate Plate
    public function valplate($data){
        
        $sql = "SELECT * FROM plate WHERE plate = '$data'";
        $res = $this->db_query($sql);
        $info = $res[0];
        $veh_make = $info['veh_make'];
        $phone = $info['phone'];
        $platenumber = $info['plate'];
        $status = $info['status'];
        $veh_color = $info['veh_color'];
        $Name = $info['Name'];
        $chasis = $info['chasis'];
        $expiry_date = $info['expiry_date'];
        $veh_model = $info['veh_model'];
        if ($res > 0) {
            return json_encode(array("status"=>$status, "vehColor"=>$veh_color, "name"=>$Name, "chasis"=>$chasis, "vehModel"=>$veh_model, "vehMake"=>$veh_make, "plateNumber"=>$plate, "phoneNumber"=>$phone, "expiry"=>$expiry_date));
           
        } else {
           
            $curl = curl_init();
        
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://mla.plsg.io/api_vehicle_info/'.$data['plateNumber'],
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
    }
    // ====== get offence category =====
    public function get_Offences(){
        $sqlCat    = "SELECT id, offences, prices FROM offences GROUP BY offences";
        $Cat = $this->db_query($sqlCat);
        return json_encode($Cat);
    }
    // ====== get offence category ends =====

    // ====== recieve payment status from payment handler =====
    public function OffenceStatus($data){
        
        $offences = $data['offence'];
        $name = $data['name'];
        $email = $data['email'];
        $phone_number = $data['phone_number'];
        $address = $data['address'];
        $veh_make = $data['veh_make'];
        $veh_type = $data['veh_type'];
        $veh_cat = $data['veh_cat'];
        $chasis_number = $data['chasis_number'];
        $plate_number = $data['plate_number'];
        $trans_send_date = $data['date'];
        $officer = $data['officer_name'];
        $station = $data['station'];
        $offence_count = $data['number_of_offences'];
        $payment_category = $data['payment_category'];
        $total_price = $data['total_price'];
        $tin = $data['tin'];
        $pending = 0;
        $processed = 1;
        $date = date("Y-m-d H:i:s");
        $ref_id_pre = "RTO";
        $current_timestamp = time();
        $offence_id = $ref_id_pre.$current_timestamp;
        
         if(count($data)<=0)
        {
            return json_encode(array("response_code"=>207,"response_message"=>'no record sent'));
        }
        else if ( $offences =='' || $name =='' || $email=='' || $phone_number =='' || $address =='' || $veh_make ==''|| $veh_type =='' || $veh_cat =='' || $chasis_number =='' || $plate_number =='' || $trans_send_date =='' || $officer =='' || $station =='' || $offence_count =='' || $payment_category =='' || $total_price =='') 
        {
            return json_encode(array("response_code"=>203,"response_message"=>'Kindly Send All Required Data'));
        }
        else
        {
            $insert = "INSERT INTO tb_offences_payment (offence_id, name, address, veh_make, veh_type, station, category, username, created, offences, total_amount, chasis_no,id_mark, phone_number, tin)
            VALUES ('$offence_id', '$name', '$address', '$veh_make', '$veh_type', '$station', '$payment_category', '$officer', '$date', '$offence_count', '$total_price','$chasis_number', '$plate_number', '$phone_number', '$tin')";
            $count = $this->db_query($insert,false);
            
            if (count($count) > 0) {
                foreach ($offences as $value) {
                    $nameO = $value['offences'];
                    $id = $value['id'];
                    $sql = "SELECT * FROM offences WHERE id = '$id' LIMIT 1";
                        $exec = $this->db_query($sql);
                        $res = $exec[0];
                        $trans_desc_code = $res['offence_code'];
                        $amount = $res['prices'];
                        $itemcode = $res['id'];
                    $insert3 = "INSERT INTO tb_payment_confirmation (payment_code, trans_desc, trans_desc_code, trans_amount, trans_status, officer, station, offence_id, tin, item_code, plate, bank_code, trans_processed_date) VALUES ('$offence_id','$nameO', '$trans_desc_code', '$amount', '$pending', '$officer','$station', '$offence_id', '$tin', '$itemcode', '$plate_number', 'Offences', '$date')";
                    $count = $this->db_query($insert3,false);
                    
                }
                return json_encode(array("response_code"=>0,"response_message"=>'Success', "reference_code"=> $offence_id));
            }else{
                return json_encode(array("response_code"=>9019,"response_message"=>'An Error Occured, Please Try Again Later'));
            }
        }
    }


  
}
 
 
?>
