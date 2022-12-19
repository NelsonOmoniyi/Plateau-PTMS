<?php
header("Access-Control-Allow-Origin: *");
require('../../libs/dbfunctions.php');
require('../route.php');
$API = new API();
$dbobject   = new dbobject();
$request     = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
$data        = json_decode(file_get_contents("php://input"),true);
$headers		= formatHeader($_SERVER);
$index       = count($request) - 1;

$endpoint=  $request[$index];

$ip = ['::1'];
$REMOTE_IP = $headers['REMOTE_ADDR'];

if (in_array($REMOTE_IP, $ip)) {
   if($endpoint == "anpr") 
   {
      if($_SERVER['REQUEST_METHOD']!=='POST')
      {
         echo json_encode(array('response_code'=>'401', 'response_message'=>'INVALID HTTP METHOD. VALID METHOD IS POST'));
      }
      else
      {
         $status = $API->anpr($data);
         logInputs('Data Received @ '.date("Y-m-d H:i:s"),$data,"Data Received");
         echo $status;
      }
   }else {
      echo json_encode(array('response_code'=>'410', 'response_message'=>''.$endpoint.' Doesnt Exists!'));
   }
} else {
   echo json_encode(array('response_code'=>'411', 'response_message'=>'Access Denied!'));
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
?>