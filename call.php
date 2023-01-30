<?php

    $MDA_ID = '128';
    $MDA_re_id = substr(str_shuffle(base64_encode(openssl_random_pseudo_bytes(32))), 0, 10);
    
    $item = 'Driving School Registration';
    $tin ='23017766191';
    $amount = '2000';
    $call_back = "https://techhost7x.accessng.com/plateau_transport/";
    $item_code = '4296';

    $curl = curl_init();
        
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://3.9.45.117/OpenPaymentsApi/initialize_transaction',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
                "mda_id": "'.$MDA_ID.'",
                "mda_reference": "'.$MDA_re_id.'",
                "tax_item":"'.$item.'",
                "tin": "'.$tin.'",
                "amount":"'.$amount.'",
                "callback_url": "'.$call_back.'",
                "tax_code": "'.$item_code.'"
                }',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    print_r($response);
    // $Array = json_decode($response, true);
    // var_dump($Array);
      
        
        // $status = $Array['status'];
        // $billing_ref = $Array['billing_reference'];
        // $mda_ref = $Array['mda_reference'];
        // $amount = $Array['amount'];
        // $message = $Array['message'];
        // $call_back = 'driving_school_payment.php';

   
    // $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //     CURLOPT_URL => 'https://payments.psirs.gov.ng/monnify/initialize_transaction',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS =>'{ 
    //         "brn":"N-BRN1001539028",
    //         "callback_url":"'.$call_back.'"
    //     }
    //     ',
    //     CURLOPT_HTTPHEADER => array(
    //         'Content-Type: application/json'
    //     ),
    //     ));

    //     $res = curl_exec($curl);
    //     $err = curl_error($curl);
    //     $obj = json_decode($res, true);

        
    //     var_dump($obj)
 ?>