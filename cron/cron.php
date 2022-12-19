<?php 

ini_set("max_execution_time", 0);
ini_set("memory_limit", "1000M");

include_once('../libs/dbfunctions.php');
include_once('../class/recievePayment.php');

function pick(){
    $dbobject = new dbobject();
    $pending = "0";
    $sql = "SELECT * FROM anpr WHERE status = '$pending' LIMIT 50";
    $res = $dbobject->db_query($sql, TRUE);

    foreach ($res['0'] as $value) {
        $id = $value['id'];
        $data = $dbobject->db_query("UPDATE anpr SET status = '1' WHERE id = '$id'");
    }
    
    $status = validate_insert($res['0']);
    // $resArr = json_decode($plate, TRUE);
    if ($status === '0') {
        return "Success";
    }
}

function validate_insert($data){
    $dbobject = new dbobject();
    $PAYMENT = new Payment();

    

    foreach ($data as $value) {
       
        $plate = $data['plate'];
        $image = $data['image'];
        $id = $data['id'];
        $date = $data['created'];
        $res = $PAYMENT->verPN($plate);
        $resArr = json_decode($res, TRUE);

        $make = $resArr['data']['vehicleMake'];
        $chasis = $resArr['data']['chassisNumber'];
        $taxPayer = $resArr['data']['taxPayer'];
        $color = $resArr['data']['vehicleColor'];
        $model = $resArr['data']['vehicleModel'];
        $phone = $resArr['data']['PhoneNumber'];
        $expDate = $resArr['data']['Expirydate'];
        $status = $resArr['data']['PlateNumber'];

        $sql = "INSERT INTO plate (Name, chasis, veh_make, veh_color, veh_model,phone, expiry_date, status, plate, image, trackID) VALUES ('$taxPayer', '$chasis', '$make', '$color', '$model', '$phone', '$expDate', '$status', '$plate', '$image', '$id')";
        $check = $this->db_query($sql, false);

        $data = $dbobject->db_query("UPDATE anpr SET status = '2' WHERE id = '$id'");
    } 
  return "0";
}








?>