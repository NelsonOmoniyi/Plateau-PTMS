<?php 

ini_set("max_execution_time", 0);
ini_set("memory_limit", "1000M");

include_once('../libs/dbfunctions.php');
include_once('../class/recievePayment.php');

function pick(){
    $dbobject = new dbobject();
    $pending = "0";
    $sql = "SELECT * FROM anpr WHERE status = '0' LIMIT 10";
    $res = $dbobject->db_query($sql, TRUE);
    // var_dump($res);
    // exit;
    foreach ($res as $value) {
        $id = $value['id'];
        // var_dump($value);
        $data = $dbobject->db_query("UPDATE anpr SET status = '1' WHERE id = '$id'");
    }

// exit;
    $status = validate_insert($res);
    $resArr = json_decode($plate, TRUE);
    if ($status === '0') {
        return "Success";
    }
}

function validate_insert($data){
 
    $dbobject = new dbobject();
    $validate = new Payment();
    foreach ($data as $value) {
       $processed = '1';
        $plate = $value['plate'];
        $image = $value['image'];
        $id = $value['id'];
        $date = $value['created'];
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
        $resArr = json_decode($response, TRUE);
                // var_dump($resArr);
                // exit;
            logInputs('Response @ '.date("Y-m-d H:i:s"),$resArr," Response ");
        if ($resArr['status'] === "Failed") {
            $sql = "INSERT INTO plate (plate, image, trackID, processed) VALUES ('$plate', '$image', '$id', '$processed')";
            
            $check = $dbobject->db_query($sql, false);
            $data = $dbobject->db_query("UPDATE anpr SET status = '2' WHERE id = '$id'");
        } else {
            $make = $resArr['data']['vehicleMake'];
            $chasis = $resArr['data']['chassisNumber'];
            $taxPayer = $resArr['data']['taxPayer'];
            $color = $resArr['data']['vehicleColor'];
            $model = $resArr['data']['vehicleModel'];
            $phone = $resArr['data']['PhoneNumber'];
            $expDate = $resArr['data']['Expirydate'];
            $status = $resArr['data']['PlateNumber'];

            $sql = "INSERT INTO plate (Name, chasis, veh_make, veh_color, veh_model,phone, expiry_date, status, plate, image, trackID, processed) VALUES ('$taxPayer', '$chasis', '$make', '$color', '$model', '$phone', '$expDate', '$status', '$plate', '$image', '$id', '$processed')";
            $check = $dbobject->db_query($sql, false);

            $data = $dbobject->db_query("UPDATE anpr SET status = '2' WHERE id = '$id'");
        }
    } 
  return "0";
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

pick();





?>