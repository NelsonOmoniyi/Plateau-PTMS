<?php
include('libs/dbfunctions.php');
$dbobject = new dbobject();

$data = json_decode(file_get_contents('php://input'), true);
$ipLog = $_SERVER['REMOTE_ADDR'];
file_put_contents("ip_logger" . date("Y_M_D") . ".txt", $ipLog." accessed this file". PHP_EOL, FILE_APPEND);

$sendData = json_encode(array('mda_id' => $data['mda_id'], 'mda_reference' => $data['mda_reference'], 'tax_item' => $data['tax_item'], 'tin' => $data['tin'], 'amount' => $data['amount'], 'callback_url' => $data['callback_url'], 'tax_code' => $data['tax_code']));
// print_r($sendData);exit;

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
    CURLOPT_POSTFIELDS => $sendData,
    CURLOPT_HTTPHEADER => array(
        ': ',
        'Content-Type: application/json'
    ),
)
);

$response = curl_exec($curl);
curl_close($curl);

file_put_contents("init_pay_response_" . date("Y_M_D") . ".txt", json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
$Array = json_decode($response, true);
$billing_ref = $Array['billing_reference'];

$sendData_ = json_encode(array('brn' => $billing_ref, 'callback_url' => $data['callback_url']));
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
    CURLOPT_POSTFIELDS => $sendData_,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
)
);

$response_ = curl_exec($curl);
file_put_contents("init_monify_" . date("Y_M_D") . ".txt", json_encode($response_, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);

// echo $response.$response_;
echo json_encode(array('endpoint1' => $response, 'endpoint2' => $response_));

?>