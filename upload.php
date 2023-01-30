<?php
include_once("libs/dbfunctions.php");
header('Content-Type: text/plain; charset=utf-8');
$dboject  = new dbobject();
$email    = $_SESSION['username_sess'];
$church_id   = $_SESSION['church_id_sess'];
$generated_file_name = $email;

// var_dump($_FILES);exit;
if($email == "" || empty($email) || $email == null)
{
    throw new RuntimeException(json_encode(array('response_code'=>'724','response_message'=>'kindly logout and login again.')));
}
try {

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upfile']['error']) ||
        is_array($_FILES['upfile']['error'])
    ) {
        throw new RuntimeException(json_encode(array('response_code'=>'74','response_message'=>'Invalid parameter.')));
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException(json_encode(array('response_code'=>'74','response_message'=>'No file sent.')));
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException(json_encode(array('response_code'=>'74','response_message'=>'Exceeded filesize limit.')));
        default:
            throw new RuntimeException(json_encode(array('response_code'=>'74','response_message'=>'Unknown errors.')));
    }

    // You should also check filesize here.
    if ($_FILES['upfile']['size'] > 1000000) {
        throw new RuntimeException(json_encode(array('response_code'=>'74','response_message'=>'Exceeded filesize limit.')));
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
//    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        finfo_file($finfo,$_FILES['upfile']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        ),
        true
    )) {
        throw new RuntimeException(json_encode(array('response_code'=>'74','response_message'=>'Invalid file format.')));
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.

    if (!move_uploaded_file(
        $_FILES['upfile']['tmp_name'],
        sprintf('img/profile_photo/%s.%s',
            $generated_file_name,
            $ext
        )
    )) {
        throw new RuntimeException(json_encode(array('response_code'=>'50','response_message'=>'Failed to move uploaded file.')));
    }

    echo json_encode(array('response_code'=>'0','response_message'=>'File uploaded successfully.','data'=>array('file'=>$generated_file_name,'ext'=>$ext)));
    
    
} catch (RuntimeException $e) {

    echo $e->getMessage();

}
