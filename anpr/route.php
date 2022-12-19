<?php
 
include_once('../libs/dbfunctions.php');
include_once('../class/recievePayment.php');

class API extends dbobject{

    public function anpr($data){
        $success = array();
        $failed = array();
        $pending = '0';
       $now = date("Y-m-d H:i:s");
       $id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
        foreach ($data as $value) {

            $plate = $value['plate'];
            $image = $value['image'];

            $sql="INSERT INTO anpr(plate, image, created, id, status)VALUES('$plate', '$image', '$now', '$id', '$pending')";
            $res = $this->db_query($sql, false);
            if ($res > 0) {
                $success[] = $plate;
            } else {
                $failed[] = $plate;
            }

        }
        logInputs('Response @ '.date("Y-m-d H:i:s"),$failed," Response ");

        if (empty($failed)) {
            return json_encode(array("response_code"=>'200', "message"=>'Success'));
        } else {
            return json_encode(array("response_code"=>'201', "message"=>'This Plate Numbers Were Not Recieved Successfully', "plate"=> $failed));
        }
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
