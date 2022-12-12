<?php
 
include_once('../lib/dbfunctions.php');

class API extends dbobject{

// autovin    // 
public function check($data){
    $directory = '../Logs/2022/November/';

    if (!is_dir($directory)) {
        exit('Invalid diretory path');
    }

    $files = array();
    foreach (scandir($directory) as $file) {
        if ($file !== '.' && $file !== '..') {
            $files[] = $file;
        }
    }
    // var_dump($files);
    // exit;

    foreach ($files as $value) {
        $curl = curl_init("https://portal.autovin.com.ng/Logs/2022/November/".$value);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        file_put_contents("manny.txt", $data, FILE_APPEND);
        print_r($data); 
    }
    // -----------------------------------------
    // file_put_contents("manny.txt", $data);
    // print_r($data);

    // $file = file_get_contents('./manny.txt');
    //     $info = json_encode($file);
    // $data = json_decode($file, true);
    // // var_dump($data);
    // $arr = array();
    // $result = [];
    // $i = 0;
    // foreach ($data as $value) {
    //     $res = [];
    //     $date = $value['datePay'];
    //     $id = $value['transID'];
    //     array_push($res, $date);
    //     array_push($res, "AVN".$id);

    //     array_push($result, $res);
    // }
    // print_r($result); 
    
    // foreach ($result as $value) {
    //     $query = "UPDATE  tb_payments_data_autovin SET trans_send_date= '$value[0]' WHERE receipt_no = '$value[1]' ";
    //     // var_dump($query);
    // 	 $result = mysql_query($query);
    //      if ($result > 0) {
    //        echo "Success </br>";
    //      } else {
    //         echo "Not Updated";
    //      }
         
      
    // }
    // return json_encode(array("Status"=>"Done"));
}
}
 
 
?>
