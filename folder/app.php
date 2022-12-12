<?php
header("Access-Control-Allow-Origin: *");
require('../libs/dbfunctions.php');
require('./route.php');
$API = new API();
$dbobject   = new dbobject();
$short_code = "66019";
$senderid = "66019";
$request     = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
$data        = json_decode(file_get_contents("php://input"),true);
$headers		= formatHeader($_SERVER);
$token = $headers['token'];
$index       = count($request) - 1;
$paymentIndex = count($request) -2;

$endpoint=  $request[$index];
$paymentEndpoint = $request[$paymentIndex];
// var_dump($request);
// var_dump($data);
// exit;
$marchantID = ['Nelson'];
$expected_token = hash('sha512',"Plateau State".$marchantID[0]);
// echo $expected_token;
// if ($token != $expected_token) {
//    echo json_encode(array('response_code'=>'500', 'response_message'=>'Access Denied'));
// } else{
   if ($paymentEndpoint == 'payment') {
      // endpoint for payment starts
      if($endpoint == "offences") 
      {
         if($_SERVER['REQUEST_METHOD']!=='POST')
         {
            echo json_encode(array('response_code'=>'401', 'response_message'=>'INVALID HTTP METHOD. VALID METHOD IS POST'));
         }
         else
         {
            $status = $API->OffenceStatus($data);
            logInputs('Data Received @ '.date("Y-m-d H:i:s"),$data,"Offence Status Received");
            echo $status;
            logInputs('Data Sent @ '.date("Y-m-d H:i:s"),$status,"Offence Response Sent");
         }
      }else {
         echo json_encode(array('response_code'=>'410', 'response_message'=>''.$endpoint.' Doesnt Exists!'));
      }
      // endpoints for payments stops.....
   }
   else if($endpoint == "offence_list"){
      if($_SERVER['REQUEST_METHOD']!=='GET')
      {
         echo json_encode(array('response_code'=>'401', 'response_message'=>'INVALID HTTP METHOD. VALID METHOD IS GET'));
      }
      else
      {
         $status = $API->get_Offences($data);
         logInputs('Data Sent @ '.date("Y-m-d H:i:s"),$data,"Offence List Request Recieved");
         echo $status;
         logInputs('Data Sent @ '.date("Y-m-d H:i:s"),$status,"Offence List Response Sent");
      }
   }else if($endpoint == "validate_plate"){
      if($_SERVER['REQUEST_METHOD']!=='GET')
      {
         echo json_encode(array('response_code'=>'401', 'response_message'=>'INVALID HTTP METHOD. VALID METHOD IS GET'));
      }
      else
      {
         $status = $API->valplate($data);
         logInputs('Data Received @ '.date("Y-m-d H:i:s"),$data,"Plate Number Validation Info Received");
         echo $status;
         logInputs('Data Sent @ '.date("Y-m-d H:i:s"),$status,"Plate Number Validation Response Sent");
      }
   }else if($endpoint == "check"){
      if($_SERVER['REQUEST_METHOD']!=='GET')
      {
         echo json_encode(array('response_code'=>'401', 'response_message'=>'INVALID HTTP METHOD. VALID METHOD IS GET'));
      }
      else
      {
         $status = $API->check($data);
         // logInputs('Data Received @ '.date("Y-m-d H:i:s"),$data,"Plate Number Validation Info Received");
         echo $status;
         // logInputs('Data Sent @ '.date("Y-m-d H:i:s"),$status,"Plate Number Validation Response Sent");
      }
   }else{
   
         echo json_encode(array('response_code'=>'409', 'response_message'=>''.$endpoint.' Doesnt Exists!'));
   
   }
// }

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
?>