<?php

class Response extends dbobject
{
    public function publishResponse($code,$message,$data_array = null,$output_type = "json")
    {
        $output  = array("response_code"=>$code,"response_message"=>$message,"data"=>$data_array);
        if($output_type == "json")
        {
            return json_encode($output);
        }
        else
        {
            return $output;
        } 
    }
}