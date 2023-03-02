<?php

class Report extends dbobject
{
    public function operations($data)
    {
        $table_name    = "tb_payment_confirmation";
        $primary_key   = "id";
        $columner = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'payment_code',  'dt' => 1),
            array( 'db' => 'client_fullname',  'dt' => 2 ),
            array( 'db' => 'trans_desc',  'dt' => 3),
            array( 'db' => 'trans_amount',  'dt' => 4),
            array( 'db' => 'trans_status', 'dt' => 5, 'formatter' => function($d, $row){
                if ($_SESSION['role_id_sess'] != '001') {
                    if (!$d > 0) {
                        return  "Not Paid";
                    } else {
                        return  "PAID ";
                    }
                } else {
                    if (!$d > 0) {
                    return  "Pending Payment";
                    } else {
                        return  "PAID ";
                    }
                }
            }),
            array( 'db' => 'tin',  'dt' => 6),
            array( 'db' => 'officer',  'dt' => 7)

        );
        $filter = "";
        
        // var_dump($data);
        $datatableEngine = new engine();
    
        echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key); 
    }

    public function financial($data)
    {
        $table_name    = "tb_payment_confirmation";
        $primary_key   = "id";
        $columner = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'payment_code',  'dt' => 1),
            array( 'db' => 'client_fullname ',  'dt' => 2 ),
            array( 'db' => 'trans_desc',  'dt' => 3),
            array( 'db' => 'trans_amount',  'dt' => 4),
            array( 'db' => 'trans_status', 'dt' => 5, 'formatter'=>function($d, $row){
                if ($_SESSION['role_id_sess'] != '001') {
                    if (!$d > 0) {
                        return  "Not Paid";
                    } else {
                        return  "PAID ";
                    }
                } else {
                    if (!$d > 0) {
                    return  "Pending Payment";
                    } else {
                        return  "PAID ";
                    }
                }
            }),
            array( 'db' => 'tin',  'dt' => 6),
            array( 'db' => 'officer',  'dt' => 7)

        );
        // $filter = " AND trans_status = '0'";
        
        // var_dump($data);
        $datatableEngine = new engine();
    
        echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key); 
    }
}



?>