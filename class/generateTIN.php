<?php 

class Generate extends dbobject{

    public function genTIN($data){
        $name = $data['company_name'];
        $address = $data['blocation'];
        $phone = $data['onumber'];
        $email = $data['email'];
        $industry = $data['industry'];
        $website = $data['website'];

        
        $generate = new Payment();
        $res = $generate->generateTIN($data);
        $arr = json_decode($res, TRUE);

        if ($arr['status'] == 'failure') {
            foreach ($arr['errors'] as $value) {
                return json_encode(array("response_code"=>90,"response_message"=>$value));
            }
        } else {
            return json_encode(array("response_code"=>0,"response_message"=>$arr['message'], "tin"=>$arr['tin'], "name"=>$name, "address"=>$address, "phone"=>$phone, "email"=>$email, "industry"=>$industry, "website"=>$website));
        }
    }
}
?>