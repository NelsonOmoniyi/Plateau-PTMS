<?php
include_once("recievePayment.php");
class DrivingSchool extends dbobject
{
    public function DSL($data)
    {
        $table_name    = "driving_sch_form";
        $primary_key   = "portal_id";
        $columner = array(
            array( 'db' => 'portal_id', 'dt' => 0 ),
            array( 'db' => 'school_name',  'dt' => 1, 'formatter' => function($d,$row){
                if ($row['status'] > 0) {
                    if ($row['approved'] > 0) {
                        // return  "$d | <button class='btn btn-primary btn-sm' onclick=\"PrintC('".$row['portal_id']."')\"  href='javascript:void(0)'><i class='fa fa-print'></i> Print Certificate</button>";
                        return "$d | <a href='certificate/spd_certificate.php?id=".$row['portal_id']."&table=driving_sch_form' class='btn btn-primary btn btn-sm' target='_blank'><i class='fa fa-print'></i> Print Certificate</a>";
                    } else {
                        return  ''.$d.' | <a class="btn btn-primary btn-sm" onclick="getModal(\'setup/preview_driving_school.php?id='.$row['portal_id'].'&table=driving_sch_form\',\'modal_div\')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Approve</a>';
                    } 
                } else {
                    return  $d;
                }
            }),
            array( 'db' => 'address',  'dt' => 2 ),
            array( 'db' => 'email_add',  'dt' => 3),
            array( 'db' => 'status',  'dt' => 4, 'formatter'=>function($d,$row){
                if ($_SESSION['role_id_sess'] != '001') {
                    if($d>0){
                        return "Paid | <a href='receipt/special_trade_receipt.php?pid=".$row['portal_id']."&table=driving_sch_form' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                    }else{
                        return "Not Paid | <a href='receipt/special_trade_receipt.php?pid=".$row['portal_id']."&table=driving_sch_form' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                    }
                   
                } else {
                   if($d>0){
                        return "Paid | <a href='receipt/special_trade_receipt.php?pid=".$row['portal_id']."&table=driving_sch_form' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                    }else{
                        return "Not Paid | <a href='receipt/special_trade_receipt.php?pid=".$row['portal_id']."&table=driving_sch_form' target='_blank' class='btn btn-primary btn-sm'><i class='fa fa-print'></i> Print Receipt</a>";
                    }
                }
            }),
            array( 'db' => 'expiry_date', 'dt' => 5, 'formatter'=> function($d, $row){

                $created = $row['created'];
                if (date('Y-m-d') > $d) {
                    return  "Expired ($d)";
                } else {
                    return  "Expiring [$d]";
                }
            }),
            array( 'db' => 'approved')

        );
        $filter = "";
        
        // var_dump($data);
        $datatableEngine = new engine();
    
        echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key); 
    }

    public function verdTIN($data){
        // var_dump($data);
        // exit;
        $tin = $data['tax'];
        $pay = new Payment();
        $validation = $this->validate($data,
            array(
                'tax' =>'required'
            ),
            array('tax'=>'Tax Identification Number')
            );
        if(!$validation['error'])
        {
            // $res = $pay->ValTIN($tin);
            $resArr = json_decode($pay->ValTIN($tin), TRUE);
            // return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Tax Identification Number'));
            if ($resArr['status'] == 'success') {
        
                    $title= $resArr['title'];
                    $firstName = $resArr['first_name'];
                    $midName = $resArr['middle_name'];
                    $Surname = $resArr['surname'];
                    $tin = $resArr['tin'];
                    $name = $resArr['name'];
                    $mobile = $resArr['phoneNumber'];
                    $address = $resArr['address'];
                    $message = $resArr['message'];
                    $type = $resArr['account_type'];
                    $status = $resArr['status'];
                    $message = $resArr['message'];


                    $sql = "INSERT INTO tin_table (tax_number, phone, address, type, status,firstname, middlename, surname, message, name) VALUES ('$tin', '$mobile', '$address', '$type', '$status', '$firstName', '$midName', '$Surname', '$message', '$name')";
                    $check = $this->db_query($sql,false);
                
                return json_encode(array('response_code'=>'200', 'response_message'=>'Valid Tax Identification Number. Please wait loading driving school setup','title'=>$title,'firstname'=>$firstName,'middleName'=>$midName,'surname'=>$Surname,'tin'=>$tin,'mobile'=>$mobile,'address'=>$address ));
            
            }
            else{
                // var_dump($message);
                return json_encode(array('response_code'=>'109', 'response_message'=>"TIN Not Found!"));
            }
        }else{
            return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
        }
    }

    public function saveDSD($data)
    {
            //   var_dump($data);
            //   exit;
        if($data['operation'] == "new")
        {
            $validation = $this->validate($data,
                array(
                    'tin'=>'required',
                    'school_name'=>'required',
                    'address'=>'required',
                    'email_add'=> 'required',
                    'phone'=>'required',
                    'proprietor_name'=>'required',
                    'cac_reg_no'=>'required',
                    'category'=>'required',
                    'classroom_accomodation'=>'required',
                    'classrooms'=>'required',
                    'amn'=>'required',
                    'agreement' => 'required',

                    'first_aid'=>'required',
                    'drops'=>'required',
                    'drawings'=>'required',
                    'charts'=> 'required',
                    'diagrams'=>'required',
                    'licence_required'=>'required',
                    'magnetic_bound'=>'required',
                    'overhead_projector'=>'required',
                    'highway_code'=>'required',
                    'road_traffic_regulations'=>'required',
                    'course_syllabus'=>'required',
                    'work_benches' => 'required'
                ),
                array(
                    'tin'=>'Tax Identification Number',
                    'school_name'=>'School Name / Center',
                    'address'=>'School Address',
                    'proprietor_name'=>'Proprietors Name',
                    'cac_reg_no'=>'CAC Reg Number',
                    'email_add'=>'Email Address',
                    'classroom_accomodation'=>'Classroom Accomodation Quantity',
                    'classrooms'=>'Classrooms Quantity',
                    'phone'=>'Phoine Number',
                    'category'=>'School Category',
                    'amn'=>'Membership Number',
                    'agreement'=>'Agreement',

                    'first_aid'=>'First Aid',
                    'drops'=>'Drops',
                    'drawings'=>'Drawings',
                    'charts'=> 'Charts',
                    'diagrams'=>'Diagrams',
                    'licence_required'=>'Licence Required',
                    'magnetic_bound'=>'Magnetic Bound',
                    'overhead_projector'=>'Overhead Project',
                    'highway_code'=>'Highway Code',
                    'road_traffic_regulations'=>'Road Traffic Regulation',
                    'course_syllabus'=>'Course Syllabus',
                    'work_benches' => 'Work Benches'
                )
            );
            if(!$validation['error'])
            {
                
                $pending = 0;
                $processed = 1;
                $tin = $data['tin'];
                $name = $data['school_name'];
                $address = $data['address'];
                $email = $data['email_add'];
                $phone = $data['phone'];
                $Prop_name = $data['proprietor_name'];
                $CAC = $data['cac_reg_no'];
                $category = $data['category'];
                $classroom_accomodation = $data['classroom_accomodation'];
                $classroom = $data['classrooms'];
                $membership_num = $data['amn'];
                $up = $data['up'];
                $agreement = $data['agreement'];
                $payment_category = $data['cat'];
                $amount = $data['amount'];
                $portal_id = $data['port'];
                $trans_code = "DSL";
                $item_code = $data['item_code'];
                $officer = $_SESSION['username_sess'];
                
                $expDate = date('Y-m-d', strtotime(' + 1 years'));
                $date = date("Y-m-d H:i:s");
                    $first_aid = $data['first_aid'];
                    $drops = $data['drops'];
                    $drawings = $data['drawings'];
                    $charts = $data['charts'];
                    $diagrams = $data['diagrams'];
                    $licence_req = $data['licence_required'];
                    $magnetic_bound = $data['magnetic_bound'];
                    $overhead_projector = $data['overhead_projector'];
                    $highway_code = $data['highway_code'];
                    $road_traffic_regulations = $data['road_traffic_regulations'];
                    $course_syllabus = $data['course_syllabus'];
                    $work_benches = $data['work_benches'];


                    $file_data = $data['_files'];
                    $ff   = $this->saveImage($file_data,"uploads/","");
                    $ff   = json_decode($ff,true);
                    $full_path = $ff['data'];

                $date = date("Y-m-d H:i:s");
                // $Confirm_pin = uniqid('DS');
                

                // $sql   = "SELECT * FROM tb_payment_confirmation WHERE tin = '$tin' AND trans_desc_code = '$trans_code' LIMIT 1";
                // $result = $this->db_query($sql);
                // $Tstatus = $result[0]['trans_status'];
                // $paid = count($result);
                // if(!$paid > 0){
                    $insert2 = "INSERT INTO tb_payment_confirmation (payment_code, trans_desc, trans_amount, trans_status, officer,trans_desc_code, tin, item_code) VALUES ('$portal_id', 'Driving School Registration', '$amount', '$pending', '$officer', '$trans_code', '$tin', '$item_code')";
                   
                    $check = $this->db_query($insert2,false);
                    $count = count($check);
                    // var_dump($count);
                    // echo $count;
                      
                    if ($count > 0) {
                        
                        $insert = "INSERT INTO driving_sch_form (portal_id, school_name, address, proprietor_name, cac_reg_no, category, classroom_accomodation, classrooms, membership_num, passport, agreement, email_add, phone, tin, status, record_date, first_aid, drops, drawings, charts, diagrams, licence_required, magnetic_bound, overhead_projector, highway_code, road_traffic_regulations, course_syllabus, work_benches, created, expiry_date,item_code)
                        VALUES ('$portal_id', '$name', '$address', '$Prop_name', '$CAC', '$category', '$classroom_accomodation', '$classroom', '$membership_num', '$full_path', '$agreement','$email','$phone', '$tin', '$pending', '$date', '$first_aid', '$drops', '$drawings', '$charts', '$diagrams', '$licence_req', '$magnetic_bound', '$overhead_projector', '$highway_code', '$road_traffic_regulations', '$course_syllabus', '$work_benches', '$date', '$expDate','$item_code')";
                        file_put_contents("insert.txt",$insert);
                        $resultDS = $this->db_query($insert, false);
                        $count = count($resultDS);
                        // var_dump($count);
                        // exit;
                        if($count > 0)
                        {
                            
                            return json_encode(array('response_code'=>0,'response_message'=>'Registration Successfully', 'port_id'=>$portal_id)); 
                        
                        }else
                        {
                            return json_encode(array('response_code'=>47,'response_message'=>'Registration Failed, Try Again Later'));
                        }
                    } else {
                        return json_encode(array('response_code'=>900,'response_message'=>'Registration Failed, Try Again Later'));
                    }
                    
                // }else{
                //     if ( $Tstatus == $processed ) { 
                //         $sql   = "SELECT * FROM driving_sch_form WHERE tin = '$tin' LIMIT 1";
                //         $result = $this->db_query($sql);
                //         $portal_id = $result[0]['portal_id'];
                //         return json_encode(array('response_code'=>0,'response_message'=>'Registration Successfully', 'port_id'=>$portal_id));
                //     }else{
                //         return json_encode(array('response_code'=>407,'response_message'=>'This TIN '.$tin.' Have Been Used And Payment For This Driving School Licence Have Not Been Made! <a href="https://techhost7x.accessng.com/plateau_transport/driving_school.php">Kindly Click On This Link To Make Payment</a>'));
                //     }
                // }
            }else{
                return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
            }
        } 
    }

    function saveImage($data,$path,$image_id="")
    {
        $_FILES = $data;
            //        var_dump($_FILES);
        if (
            !isset($_FILES['upfile']['error']) ||
            is_array($_FILES['upfile']['error'])
        ) {
            return json_encode(array('response_code'=>'0','response_mesage'=>'Invalid parameter.'));
        }

        // Check $_FILES['upfile']['error'] value.
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return json_encode(array('response_code'=>'0','response_mesage'=>'No file sent.'));
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return json_encode(array('response_code'=>'74','response_mesage'=>'Exceeded filesize limit.'));
            default:
                return json_encode(array('response_code'=>'74','response_mesage'=>'Unknown errors.'));
        }

        // You should also check filesize here.
        if ($_FILES['upfile']['size'] > 1000000) {
            return json_encode(array('response_code'=>'74','response_mesage'=>'Exceeded filesize limit.'));
        }

        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        //    $finfo = new finfo(FILEINFO_MIME_TYPE);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // var_dump($finfo);
        // echo $ext." == hello guys";
        if (false === $ext = array_search(
            finfo_file($finfo,$_FILES['upfile']['tmp_name']),
            array(
                'jpg' => 'image/jpg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png'
            ),
            true)) {
            return json_encode(array('response_code'=>'74','response_mesage'=>'Invalid file format.'));
        }

        // You should name it uniquely.
        // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
        // On this example, obtain safe unique name from its binary data.
        $email = ($image_id == "")?date('mdhis'):$image_id;

        //@@@@@@@@@@@@@@@@@@@@@@@
        
        if (!move_uploaded_file($_FILES['upfile']['tmp_name'],sprintf($path.'%s.%s',$email,$ext))) {
            return json_encode(array('response_code'=>'50','response_mesage'=>'Failed to move uploaded file.'));
        }
        $full_path = $path.$email.'.'.$ext;
        
        unlink($_FILES['upfile']['tmp_name']);
        return json_encode(array('response_code'=>'0','response_message'=>'success','data'=>$full_path));
    }

}



?>