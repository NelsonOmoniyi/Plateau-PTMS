<?php 

class Generate extends dbobject{

    public function genTIN($data){
        $name = $data['company_name'];
        $address = $data['blocation'];
        $phone = $data['onumber'];
        $email = $data['email'];
        $industry = $data['industry'];
        $website = $data['website'];

        $validation = $this->validate($data,
            array(
                'mobile'=>'required',
                'title'=>'required',
                'company_name'=>'required',
                'rc_number'=>'required',
                'soo'=> 'required',
                'occupation' => 'required',
                'dob' => 'required',
                'padd' => 'required',
                'nationality' => 'required',
                'bi' => 'required',
                'mstatus' => 'required',
                'lga' => 'required',
                'blocation' => 'required',
                'industry' => 'required',
                'address' => 'required',
                'email' => 'required'
            ),
            array(
            'mobile' => 'Mobile Number',
            'title' => 'Title',
            'company_name' => 'Company Name',
            'rc_number' =>  'RC Number',
            'soo' => 'State Of Origin',
            'occupation' => 'Occupation',
            'dob' => 'Date Of Birth ',
            'padd' => 'Payer Address',
            'nationality' => 'Nationality',
            'bi' => 'Business Industry',
            'mstatus' => 'Marital Status',
            'lga' => 'Local Government Area',
            'blocation' => 'Business Location',
            'industry' => 'Industry',
            'address' => 'Address',
            'email' => 'Email Address'
            ));

            if(!$validation['error']){
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
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }
}
?>