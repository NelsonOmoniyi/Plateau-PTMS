<?php
@session_start();

///////////////////	
//error_reporting(E_ERROR);
ini_set('display_errors', 1);
//error_reporting(E_ALL);
 error_reporting(1);

require_once("dbcnx.inc.php");
require('desencrypt.php');
// echo "sdsfghjk";
require('validation.php');

//use Aws\S3\S3Client;  
//use Aws\Exception\AwsException;
//////////////////////
class dbobject extends validation
{
    private $hex_iv = '00000000000000000000000000000000';             
    private $key = '12kdknfim.dsmoioqw09djjd';
	private $debug = false;
    public $myconn = "";
    function __construct()
    {
        $this->key = hash('sha256', $this->key, true);
    }
	function begin(){
		@mysql_query("BEGIN");
		}
	function commit()
        {
		@mysql_query("COMMIT");
		}
	function rollback(){
		@mysql_query("ROLLBACK");
		}
	public function createAwsS3($key,$secret)
	{
		$s3Client = new S3Client([
			'region' => 'us-east-2',
			'version' => '2006-03-01',
			'credentials' => [
				'key'    => $key,
				'secret' => $secret
			]
			]);
		return $s3Client;
	}
    public function generateCRSFToken($data,$key = "")
    {
        $key    = ($key == "")?date('his'):$key;
        $_SESSION['crypto_key_sess'] = $key; 
        $result = $this->EncryptData($_SESSION['crypto_key_sess'],$data);
        return $result;
    }
    
    public function time_elapsed_string($datetime, $full = false,$type="ago") {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        if($type == "left")
        {
            if($now > $ago)
            {
                return "expired";
            }else
            {
                return $string ? implode(', ', $string) . ' to go' : 'expired';
            }
            
        }else
        {
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }
        
    }
	public function arrayImplode($data = array(), $separated) 
	{
 		$fields = array_keys($data);
 		$values = array_values(array_map('mysql_escape_string', $data));
 		$i = 0;
 		while($fields[$i])
		{
 			if ($i > 0) $string .= $separated;
 			$string .= sprintf("%s = '%s'", $fields[$i], $values[$i]);
 			$i++;
 		}
 		return $string;
	 }
    public function DecryptData($key,$password)
    {
     $desencrypt = new DESEncryption();
     $mmm =$desencrypt->hexToString ($password);
     return strip_tags($desencrypt->des($key, $mmm, 0, 0, null,null));
    }
    function EncryptData($username,$userpassword)
    {
     $desencrypt = new DESEncryption();
     $key = $username;
     $cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null,null);
     $str_cipher_password = $desencrypt->stringToHex ($cipher_password);
     return $str_cipher_password;
    }
	 public function db_query($sql,$object = true)
	 {
		 // if you are performig a UPDATE query; you will need to set $object == false
         file_put_contents('lo.txt',$sql);
		  $cnx = new dbcnx();
          $this->myconn = $cnx->connect();
		 $result = mysqli_query($this->myconn,$sql);
		 $count  = ($object)?mysqli_num_rows($result):mysqli_affected_rows($this->myconn);
		 if($object)
		 {
			 if($count > 0)
			 {
				 $data = array();
				 while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
				 {
					 $data[] = $row;
				 }
				 return $data;
			 }else
			 {
				 return null;
			 }
		 }else
		 {
			 return $count;
		 }
	 }
    
    function doInsert($table,$arr,$exp_arr)
        {
            $patch1  = "(";
            $patch2  = "(";
            $cnx          = new dbcnx();
            $this->myconn = $cnx->connect();
            foreach($arr as $key=>$value)
            {
                if(!in_array($key,$exp_arr))
                {
                    $patch1.= $key.",";
                    $patch2.= "'".mysqli_real_escape_string($this->myconn,$value)."',";
                }
            }
            $patch1 =  substr($patch1,0,-1).")";
            $patch2 =  substr($patch2,0,-1).")";
            $sql = "insert into ".$table." ".$patch1." VALUES ".$patch2;
            file_put_contents('m_query.txt',$sql);
            $num_row = $this->db_query($sql,false);
            return $num_row;
        }
        function doUpdate($table,$arr,$exp_arr,$clause)
        {
            $patch1     = "";
            $key_id     = "";
            $cnx          = new dbcnx();
            $this->myconn = $cnx->connect();
            foreach($arr as $key=>$value)
            {
                if(!in_array($key,$exp_arr))
                {
                    $patch1.= $key."='".mysqli_real_escape_string($this->myconn,$value)."',";
                }
            }
            foreach($clause as $key=>$value)
            {
                $key_id.= " ".$key."='".$value."' AND";
            }
            $key_id  =  substr($key_id,0,-3);
            $patch1  =  substr($patch1,0,-1);
            $sql    = "UPDATE ".$table." SET ".$patch1." WHERE ".$key_id;
            file_put_contents("user_edit.txt",$sql);
            $num_row = $this->db_query($sql,false);
            return $num_row;
        }
	public function insertMysql($table, $data = array()) 
	{
 		$fields = implode(', ', array_keys($data));
 		$values = implode('", "', array_map('mysql_escape_string', $data));
 		$query = sprintf('INSERT INTO %s (%s) VALUES ("%s")', $table, $fields, $values);
 		return $this->queryMysql($query);
 	}
    public function encrypt($data, $secret = "12kdknfim.dsmoioqw09djjd")
    {
        //Generate a key from a hash
        $key = md5(utf8_encode($secret), true);

        //Take first 8 bytes of $key and append them to the end of $key.
        $key .= substr($key, 0, 8);

        //Pad for PKCS7
        $blockSize = mcrypt_get_block_size('tripledes', 'ecb');
        $len = strlen($data);
        $pad = $blockSize - ($len % $blockSize);
        $data .= str_repeat(chr($pad), $pad);

        //Encrypt data
        $encData = mcrypt_encrypt('tripledes', $key, $data, 'ecb');

        return base64_encode($encData);
    }
    public function preEncryptedData($data)
    {
        $reData = array();
        foreach($data as $key => $value)
        {
            $reData[$key] = $this->encrypt($value);
        }
        return $reData;
    }
    public function doApiCall($json_data,$endpoint)
    {
        $arr = $json_data;
        $json_data = $this->preEncryptedData($json_data);
        if($endpoint == "getDisco")
        {
            file_put_contents("enctt.txt",json_encode($json_data));
        }
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        //   CURLOPT_URL => "192.168.5.35/agentOne/api/".$endpoint,
         CURLOPT_URL => "http://agent.vuvaa.com/api/".$endpoint,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_POSTREDIR => 3,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($json_data),
          CURLOPT_HTTPHEADER => array(
            "content-type: application/json",
            "x-api-key: Z802"
          ),
        ));

        $response = curl_exec($curl);
         $err = curl_error($curl);

        curl_close($curl);

        if($err) {
          return "cURL Error #:" . $err;
        } else {
            
          return  $response;
        }
    }
	public function queryMysql($sql) 
	{
 		if ($this->debug === false)
		{
 			try {
 				$result = mysql_query($sql);
 				if ($result === false)
				{
 					throw new Exception('MySQL Query Error: ' . mysql_error());
					//$result = '-1';
 				}
 				return $result;
 			}
 			catch (Exception $e) {
 				return $e->getMessage();
 				//exit();
 			}
 		}
 		else {
 			printf('<textarea>%s</textarea>', $sql);
 		}
 	}
	//////////////////////////////////Generic Script///////////////////////////////////////////////////
	function SaveTransEdit($tbl,$inpFds,$inpFdsVals,$operation)
	{
		$whrcond = 0;
		$resp = 0;
		if($operation == 'new'){
		$query = "insert into ".$tbl." set ";
		$where = "";
		for($i=0;$i<count($inpFds);$i++)
		{	
			$field = explode("-",$inpFds[$i]);
			if($field[1]=='fd')
			{
				$query .= $field[0]."='".$inpFdsVals[$i]."', ";
				//$affected .= $field[0].", ";
				//$updatedVals .= $inpFdsVals[$i]."/";
			}
			elseif($field[1]=='whr' && $whrcond==0)
			{
				$where .= ", ".$field[0]."='".$inpFdsVals[$i]."'";
				$whrcond +=1;
				//$trail_appl = $inpFdsVals[$i];
			}
			elseif($field[1]=='whr' && $whrcond >=1)
			{
				$where .= ", ".$field[0]."='".$inpFdsVals[$i]."'";
				$whrcond +=1;
			}
		}
		$query = rtrim($query,", "); 
		$query_data = $query.$where;
		$query_data .=';';//use to disply sql insert
		$daty = @date('Y-m-d H:i:s');
		$officer = $_SESSION['username_sess'];
		$ip = $_SERVER['REMOTE_ADDR'];
		//$query2 = "insert into audit_trail  values('','$tbl','$trail_appl','$afftd','$intVals','$updatedVals','Edit','$daty','$officer','$ip')";
		if(mysql_query($query_data)or die(mysql_error()))
		{
			$resp += 1;
			//if(mysql_query($query2))	$resp += 1;
			//else	$resp = -2;
			
		}
		else	$resp = -1;
		//if(!mysql_error())*/
		return $resp;
		
		}elseif($operation == 'edit'){
		$query = "update ".$tbl." set ";
		$where = "";
		for($i=0;$i<count($inpFds);$i++)
		{	
			$field = explode("-",$inpFds[$i]);
			if($field[1]=='fd')
			{
				$query .= $field[0]."='".$inpFdsVals[$i]."', ";
				//$affected .= $field[0].", ";
				//$updatedVals .= $inpFdsVals[$i]."/";
			}
			elseif($field[1]=='whr' && $whrcond==0)
			{
				$where .= " where ".$field[0]."='".$inpFdsVals[$i]."'";
				$whrcond +=1;
				//$trail_appl = $inpFdsVals[$i];
			}
			elseif($field[1]=='whr' && $whrcond >=1)
			{
				$where .= " and ".$field[0]."='".$inpFdsVals[$i]."'";
				$whrcond +=1;
			}
		}
		$query = rtrim($query,", ");
		$query_data = $query.$where;
		$query_data .=';';
		//$affected = rtrim($affected,", ");
		//$query1 = "select ".$affected." from ".$tbl.$where.';';
		//echo $query_data;
		//$result1 = mysql_query($query1);
		/*while($row=mysql_fetch_array($result1))
		{
			$fdd = explode(", ",$affected);
			for($t=0;$t<count($fdd);$t++)
			{
				$afftd .= $fdd[$t].'/';
				$intVals .= $row[$fdd[$t]].'/';
			}
		}
*/		$daty = @date('Y-m-d H:i:s');
		$officer = $_SESSION['username_sess'];
		$ip = $_SERVER['REMOTE_ADDR'];
		//$query2 = "insert into audit_trail  values('','$tbl','$trail_appl','$afftd','$intVals','$updatedVals','Edit','$daty','$officer','$ip')";
		if(mysql_query($query_data))//or die(mysql_error()))
		{
			$resp += 1;
			//if(mysql_query($query2))	$resp += 1;
			//else	$resp = -2;
			
		}
		else	$resp = -2;
		//if(!mysql_error())*/
		return $resp;
		
		
		}else{echo 'something went wrong'; exit();}
		
		
	}
///////////////////////////////////////////////////////		
	function exister($table,$field1,$field2,$value1,$value2)		
	{
		// counter function=>to return numbers of rows fetched or found
		function counter($resource)	
		{
			return mysql_num_rows($resource);
		}
		//////////////////////////
		$existed = mysql_query("SELECT * FROM $table WHERE $field1='$value1' and $field2='$value2'")or die('Inavlid Exist Query'. mysql_error());
		$no = counter($existed) ;
		return $no;
	}
	
function getToken()
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://live.moneywaveapi.co/v1/merchant/verify",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => 2,
      CURLOPT_POSTREDIR => 3,
      CURLOPT_CAINFO         => getcwd() ."\DigiCertSHA2HighAssuranceServerCA.crt",
      CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"apiKey\":\"XU3EQVKJFDSSO7N0L67X\",\"secret\":\"2HE0OGIB1OSP8LTO74YC8GUME8DQ5B\"}",
      CURLOPT_HTTPHEADER => array(
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    echo $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return $response;
    }
}
    
function validateAccount($token,$account_number,$bank_code)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://live.moneywaveapi.co/v1/resolve/account",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => 2,
          CURLOPT_POSTREDIR => 3,
          CURLOPT_CAINFO         => getcwd() ."\DigiCertSHA2HighAssuranceServerCA.crt",
          CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"account_number\":\"{$account_number}\",\"bank_code\":\"{$bank_code}\"}",
          CURLOPT_HTTPHEADER => array(
            "content-type: application/json",
            "Authorization: ".$token
          ),
        ));

        $response = curl_exec($curl);
         $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return "cURL Error #:" . $err;
        } else {
          return $response;
        }
    }

function getcheckdetails($user,$password) {
	//echo 'country code : '.$countrycode;
	$desencrypt = new DESEncryption();
	$key = $user; //"mantraa360";
	$cipher_password = $desencrypt->des($key, $password, 1, 0, null,null);
	$str_cipher_password = $desencrypt->stringToHex ($cipher_password);
	
	$label = "";
	$table_filter = " where username='".$user."' and password='".$str_cipher_password."'";
	
	$query = "select * from userdata ".$table_filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_affected_rows();
	//echo ' num rows :'.$numrows;
	$dbobject = new dbobject();
	$no_of_pin_misses = $dbobject->getitemlabel('parameter','parameter_name','no_of_pin_misses','parameter_value');
	$pin_missed = $dbobject->getitemlabel('userdata','username',$user,'pin_missed');
	$override_wh = $dbobject->getitemlabel('userdata','username',$user,'override_wh');
	$extend_wh = $dbobject->getitemlabel('userdata','username',$user,'extend_wh');
	
	if($numrows > 0){
		@ $ddate = date('w');
		$row = mysql_fetch_array($result);
		
		@ $dhrmin = date('Hi');
		$worktime = $dbobject->getitemlabel('parameter','parameter_name','working_hours','parameter_value');
		//echo $dhrmin;
		if($override_wh=='1'){
		$worktime = $extend_wh;
		}
		$worktimesplit = explode("-",$worktime);
		$lowertime = str_replace(":","",$worktimesplit[0]);
		$uppertime = str_replace(":","",$worktimesplit[1]);
		
		$lowerstatus = ($lowertime < $dhrmin)==''?"0":"1";
		$upperstatus = ($dhrmin < $uppertime)==''?"0":"1";
		
		$pass_dateexpire = $row['pass_dateexpire'];
		@$expiration_date = strtotime($pass_dateexpire);
		@$today = date('Y-m-d');
		@$today_date = strtotime($today);
		
		//echo 'exp date: '.$pass_dateexpire.'   -  today date: '.$today;
		//echo 'Change on Logon : '.$row['passchg_logon'];
		
		if($row['user_disabled']=='1'){
			$label = "2";
		}
		else if($row['user_locked']=='1'){
			$label = "3";
		}
		else if($row['day_1']=='0' && $ddate=='0'){
			//You are not allowed to login on Sunday
			$label = "4";
		}
		else if($row['day_2']=='0' && $ddate=='1'){
			//You are not allowed to login on Monday
			$label = "5";
		}
		else if($row['day_3']=='0' && $ddate=='2'){
			//You are not allowed to login on Tuesday
			$label = "6";
		}
		else if($row['day_4']=='0' && $ddate=='3'){
			//You are not allowed to login on Wednesday
			$label = "7";
		}
		else if($row['day_5']=='0' && $ddate=='4'){
			//You are not allowed to login on Thursday
			$label = "8";
		}
		else if($row['day_6']=='0' && $ddate=='5'){
			//You are not allowed to login on Friday
			$label = "9";
		}
		else if($row['day_7']=='0' && $ddate=='6'){
			//You are not allowed to login on Saturday
			$label = "10";
		}
		else if(!(($lowerstatus==1) && ($upperstatus==1))){
			//You are not allowed to login due to working hours violation
			$label = "11";
		}
		else if($expiration_date <=$today_date){
			$label = "13";
		}
		else if($row['passchg_logon']=='1'){
			$label = "14";
		}
        else if($row['login_status']=='1'){
			$label = "15";
		}
        else if($row['user_approved']=='0' && $row['reg_status'] == '1')
        {
            $label = "17";
        }
		else {
			    $label = "1";
                $_SESSION['username_sess']  = $user;
                $_SESSION['role_id_sess']   = $row['role_id'];
                $_SESSION['firstname_sess'] = $row['firstname'];
                $_SESSION['lastname_sess']  = $row['lastname'];
                $_SESSION['super_agent_id'] = $row['super_agent_id'];
                $_SESSION['reg_status']     = $row['reg_status'];
                $_SESSION['approval']       = $row['user_approved'];
                $_SESSION['password']       = $password;
                $_SESSION['last_page_load'] = time();
//            $login_resp = json_decode($dbobject->doApiCall(array("email"=>$_SESSION['username_sess'],"ip"=>$_SERVER['REMOTE_ADDR'],"location"=>"","password"=>$password,"platform"=>"web"),"login"), true );
//            if($login_resp['response_code'] == 002)
//            {
//                $_SESSION['token'] = $login_resp['data']['token'];
//            }
//                $dbobject->doDbTblInsert("userdata",array("login_status"),array("1"));
            $dbobject->resetpinmissed($user);
                $oper="IN";
        }
		
	}else{
		if($no_of_pin_misses==$pin_missed){
			$label = "12";
			$dbobject->updateuserlock($user,'1');
		}else{
            
            $label = "0";
            $dbobject->updatepinmissed($user);
		}		
	}
	   return $label."::||::".(4 - $pin_missed);
	}




///// NEW ADDITIONS

function logaccess ($username,$time,$message) {
	$filename = date("Y-M-d");
	$my_file = "logs/".$filename.'.log';
	$success =  $time.' by '.$username.' --- using  '.$_SERVER['REMOTE_ADDR'].' -- '.$message."\r\n";
	$handle = fopen($my_file, 'a+') or die('Cannot open file:  '.$my_file); //implicitly creates file
	fwrite($handle,$success);
	fclose($handle);
		
}

function getuserip_status($user){
	$date = date("Y-m-d");
	$qr1 = " SELECT AUDIT_IP,AUDIT_USER FROM audit_trail_account WHERE AUDIT_USER = '".$user."' AND  
	SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  AND AUDIT_T_OUT IS NULL  ";
	//echo $qr1;
			$mq1 = mysql_query($qr1);
			$mn1 = mysql_num_rows($mq1);
			$label = 0;
			if($mn1>0){
				$label = $mn1;
			
			}
			return $label;
	
	
}


function doAuditTrai_logout($operation,$user){
			//$count_entry = 0;
			//$user= $_SESSION[username_sess];
			$date = date("Y-m-d");
			$client_ip = $_SERVER['REMOTE_ADDR'];
				   $query = "UPDATE  audit_trail_account SET AUDIT_T_OUT=now() WHERE AUDIT_USER='$user'
				   AND SUBSTR(AUDIT_T_IN, 1, 10) = '$date' ";
				   //echo $query;
				   //$unset = unset($_SESSION['IN']);
				   $result = mysql_query($query);
			       $count_entry = $query;

			return $count_entry;
}



function doAuditTrail($operation){
			//$count_entry = 0;
			$user= $_SESSION[username_sess];
			$client_ip = $_SERVER['REMOTE_ADDR'];

			if($operation=="IN"){
			@$now = date("Y-m-d H:i:s");
			$_SESSION['IN'] = $now;
			$query = " INSERT INTO  audit_trail_account (AUDIT_USER,AUDIT_T_IN,AUDIT_IP)
			  VALUES('$user','$now','$client_ip')";
			//echo $query;
			$result = mysql_query($query);
			//$count_entry = mysql_num_rows($result);
			}
			else
			   if($operation=="0UT"){
				   //echo "innow";
				   $now = $_SESSION['IN'];
				   $query = "UPDATE  audit_trail_account SET AUDIT_T_OUT=now() WHERE AUDIT_USER='$user'
				   AND AUDIT_T_IN='$now'";
				   //echo $query;
				   //$unset = unset($_SESSION['IN']);
				   $result = mysql_query($query);
			       $count_entry = $query;

			}
			return $count_entry;
		}

function getlastlogin($user){
	$date = date("Y-m-d");
	//check to see if the user has logged in to this system or another system
			$qr = " SELECT AUDIT_IP, AUDIT_T_IN FROM audit_trail_account WHERE AUDIT_USER='".$user."' AND 
			        SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  ORDER BY AUDIT_T_IN DESC LIMIT 1  ";
					//echo $qr;
			$mq = mysql_query($qr);
			$mn = mysql_num_rows($mq);
			if($mn>0){
				$rr = mysql_fetch_array($mq);
				$the_ip = $rr["AUDIT_IP"];
				$last_time_in = $rr["AUDIT_T_IN"];			
			}
			return $last_time_in;	
}



	function reset_ip($user)
	{
		
		//check to see if the user has logged in to this system or another system
		$date = date("Y-m-d");
				$qr = " SELECT AUDIT_IP FROM audit_trail_account WHERE AUDIT_USER='".$user."' AND 
						SUBSTR(AUDIT_T_IN, 1, 10)= '$date'  AND AUDIT_T_OUT IS NULL ";
						//echo $qr;
				$mq = mysql_query($qr);
				$mn = mysql_num_rows($mq);
				if($mn>0){
					$rr = mysql_fetch_array($mq);
					$the_ip = $rr["AUDIT_IP"];
					$sys_ip  = $_SERVER['REMOTE_ADDR'];
					//$label = "16";
					$operation="0UT";
					$dbobject = new dbobject();
					$audit = $dbobject->doAuditTrai_logout($operation,$user);
						
				}
	}
		
	function getitemlabel2($tablename,$table_col,$table_val,$table_col2,$table_val2,$ret_val) {
	//echo 'country code : '.$countrycode;
	$label = "";
	$table_filter = " where ".$table_col."='".$table_val."' and ".$table_col2."='".$table_val2."'";

	$query = "select ".$ret_val." from ".$tablename.$table_filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		$row = mysql_fetch_array($result);
		$label = $row[$ret_val];
	}
	return $label;
	}
	function getitemlabel4($tablename,$table_col,$table_val,$table_col2,$table_val2,$table_col3,$table_val3,$table_col4,$table_val4,$ret_val) {
	//echo 'country code : '.$countrycode;
	$label = "";
	$table_filter = " where ".$table_col."='".$table_val."' and ".$table_col2."='".$table_val2."' and ".$table_col3."='".$table_val3."' and ".$table_col4."='".$table_val4."'";

	$query = "select ".$ret_val." from ".$tablename.$table_filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		$row = mysql_fetch_array($result);
		$label = $row[$ret_val];
	}
	return $label;
	}
	
	
	function reset_login_status($user){
	$date = date("Y-m-d");
	$now = $_SESSION['IN'];
	$qr1 = " SELECT AUDIT_IP,AUDIT_USER FROM audit_trail_account WHERE AUDIT_USER = '".$user."' AND  
	AUDIT_T_IN= '$now'  AND AUDIT_T_OUT IS NULL AND AUDIT_IP = '".$_SERVER['REMOTE_ADDR']."' ";
	//echo $qr1;
			$mq1 = mysql_query($qr1);
			$mn1 = mysql_num_rows($mq1);
			$label = 0;
			if($mn1>0){
				$label = $mn1;
			
			}
			return $label;	
}
// END NEW ADDITIONS	


	
	
	
	function accesslog($accessflag,$user){
	$dbobject = new dbobject();
				$logid = $dbobject->paddZeros($dbobject->getnextid("ACCSSLOG"),4);
				$ipaddr = $_SERVER['REMOTE_ADDR'];
				$querylog = "INSERT INTO access_log (logid,accessflag,created,posted_by,posted_ip) VALUES ('$logid','$accessflag',now(),'$user','$ipaddr')";
				@mysql_query($querylog);//or die(mysql_error());
		}
	function updatepinmissed($username){
		$query = "update userdata set pin_missed=pin_missed+1 where username= '$username'";
		//echo $query;
		$resultid = $this->db_query($query,false);
		$numrows = $resultid;
	}
	function resetpinmissed($username){
		$query = "update userdata set pin_missed=0 where username= '$username'";
		//echo $query;
		$resultid = $this->db_query($query,false);
		$numrows = $resultid;
	}
	function updateuserlock($username,$value){
		$query = "update userdata set user_locked='$value', pin_missed = 0 where username= '$username'";
//		echo $query;
		$resultid = $this->db_query($query,false);
		$numrows = $resultid;
	}	
	
	//// select a field from a table
	function getitemlabel($tablename,$table_col,$table_val,$ret_val) {
	$label = "";
	$table_filter = " where ".$table_col."='".$table_val."'";

	$query = "select ".$ret_val." from ".$tablename.$table_filter;
	//echo $query;
	$result = $this->db_query($query);
	$numrows = count($result);
	if($numrows > 0){
//		$row = mysql_fetch_array($result);
        foreach($result as $row)
        {
            $label = $row[$ret_val];
        }
		
	}
	return $label;
	}
	
	function getitemlabelmenu($tablename,$table_col,$table_val,$ret_val) {
	$label = "";
	$table_filter = " where ".$table_col."='".$table_val."'";

	$query = "select ".$ret_val." from ".$tablename.$table_filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_affected_rows();
	if($numrows > 0){
		while($row = mysql_fetch_array($result)){
		$label .= "'".$row[$ret_val]."',";
		}
		$label = rtrim($label,",");
	}
	return $label;
	}
	
	///////////////
	function loadParameters(){
		$label = "";
		$query = "select * from parameter";
		$result = mysql_query($query);
		$numrows = mysql_num_rows($result);
		for($i=0; $i<$numrows; $i++){
			$row = mysql_fetch_array($result);
			$label = $label .'"'.$row["parameter_name"].'"=>"'.$row["parameter_value"]."\", ";
			$_SESSION[$row["parameter_name"]] = $row["parameter_value"];
		}
		return $label;
	}
	//////////
	function getrecordset($tablename,$table_col,$table_val)
	{
		$label = "";
		$table_filter = " where ".$table_col."='".$table_val."'";
	
		$query = "select * from ".$tablename.$table_filter;
		//echo $query;
		$result = mysql_query($query);
		//$numrows = mysql_num_rows($result);
		/*
		if($numrows > 0){
			$row = mysql_fetch_array($result);
			$label = $row[$ret_val];
		}
		*/
		return $result;
	}
	/////////////////
	function getrecordsetdata($query) {
	$query = $query;
	//echo $query;
	$result = mysql_query($query);
	return $result;
	}
	
	  //Role Changer   
   function branch_changer($brac_code)
	{
	$_SESSION[role_id_sess]= $brac_code;
	return 1;
	}
	
	//////////////////
	function getparentmenu($opt) {
	$filter = "";
	$options = "<option value='#'>::: None ::: </option>";
		 /*
		 if($opt!= ""){
		 $filter = "where menu_id='".$opt."' and parent_id='#' "; //" username='$username' and password='$password' ";
		 }else{
		 */
			$filter = "where parent_id='#' or parent_id2='#'  order by menu_order";
		 //}
	$query = "select distinct menu_id, menu_name from menu  ".$filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 if($opt==$row['menu_id']) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
		$filter='';
		}
	}
	return $options;
	}
	function getsubmenu($opt) {
		$filter = "";
		$options = "";
			 if($opt!= ""){
			 $filter = "where parent_id='$opt' order by menu_order"; //" username='$username' and password='$password' ";
			 }
		$query = "select distinct menu_id, menu_name from menu  ".$filter;
		//echo $query;
		$result = mysql_query($query);
		$numrows = mysql_num_rows($result);
		if($numrows > 0){
			for($i=0; $i<$numrows; $i++){
			$row = mysql_fetch_array($result);
			$options = $options."<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
			$filter='';
			}
		}
		return $options;
		}
	////////////////////////////////////
		function reorder_submenu($parent_menu,$sub_menu){
		$num_count = 0;
		$sub_menu_arr = explode(',',$sub_menu);
		for($i=0; $i<sizeof($sub_menu_arr); $i++){
			$query = "update menu set menu_order=$i where menu_id= '$sub_menu_arr[$i]'";
			//echo $query;
			$result = mysql_query($query);
			$num_count+=mysql_affected_rows();
		}
			return $num_count;
		}
		///////////////////////////////////
	function validatepassword($user,$password){
	//echo 'country code : '.$countrycode;
	$desencrypt = new DESEncryption();
	$key = $user; //"mantraa360";
	$cipher_password = $desencrypt->des($key, $password, 1, 0, null,null);
	$str_cipher_password = $desencrypt->stringToHex ($cipher_password);
	
	$label = "";
	$table_filter = " where username='".$user."' and password='".$str_cipher_password."'";

	$query = "select * from userdata".$table_filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0) $label = "1";
	else $label = "-1";	
	
	return $label;
	}
	
	// Change to user profile password
	function doPasswordChange($username,$user_password)
    {
//		auditTrail("update","Change Password","userdata","changepassword.php","username",$username);
			$desencrypt = new DESEncryption();
			$key = $username;
			$cipher_password = $desencrypt->des($key, $user_password, 1, 0, null,null);
			$str_cipher_password = $desencrypt->stringToHex ($cipher_password);
		    $query_data = "update userdata set password='$str_cipher_password' where username= '$username'";
			//echo $query_data;
			$result_data = mysql_query($query_data);
			$count_entry = mysql_affected_rows();
			
			return $count_entry;
	}
	function pick_role($opt) {
	$filter = "";
	$options = "<option value=''>::: Select a Role ::: </option>";
	/*
	if($opt!= ""){
	 $filter = "where role_id='".$opt."'"; //" username='$username' and password='$password' ";
	 }
	 */
	$dbobject = new dbobject();
	$user_role_session = $_SESSION['role_id_sess'];
	//$filter_role_id = $dbobject->getitemlabel('parameter','parameter_name','admin_code','parameter_value');
	//$filteradmin = ($user_role_session == $filter_role_id)?"":" and role_id not in ('".$filter_role_id."')";
	$query = "select distinct role_id, role_name from role where 1=1  ";//.$filteradmin;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 if($opt==$row['role_id']) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[role_id]' $filter >$row[role_name]</option>";
		$filter='';
		}
	}
	return $options;
	}
	////////////////////////
	function doRole($role_id,$role_name,$enable_role){
			$count_entry = 0;
			$query = "select * from role  where role_id='$role_id'";
			//echo $query;
				$result = mysql_query($query);
				$numrows = mysql_num_rows($result);
			if($numrows >=1)
			{
				$query_data ="update role set role_name='$role_name', role_enabled='$enable_role' where role_id='$role_id' ";
				$result_data = mysql_query($query_data);
				$count_entry =mysql_affected_rows();
			}
			else
			{
			$sql = "select * from role  where role_name='$role_name'";
			if($res=mysql_query($sql))
			{
			if(mysql_num_rows($res)>=1)
			{
				$count_entry=-9;
				}			
			else
			{	
				$query_data = "insert into role (role_id,role_name,role_enabled,created) values( '$role_id','$role_name','$enable_role',now())";
				//echo $query_data;
				$result_data = mysql_query($query_data);
				$count_entry = mysql_affected_rows();
			}
			}
		}
			return $count_entry;
		}
		function doUser2($operation,$username,$userpassword,$firstname,$lastname,$email,$phone,$chgpword_logon,$user_locked, $user_disable,$day_1,$day_2,$day_3,$day_4,$day_5,$day_6,$day_7,$override_wh,$extend_wh,$role_id,$role_name, $superagent_id) {
			
		   $desencrypt = new DESEncryption();
		   $count_entry = 0;
		   $key = $username;
		   $cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null,null);
		   $str_cipher_password = $desencrypt->stringToHex ($cipher_password);
		   $today = @date("Y-m-d H:i:s");
		   
		   $query = "select  from userdata where username='$username'";
		   //echo $query;
		   $result = mysql_query($query);
		   $numrows = mysql_num_rows($result);
		   //$operation = $_SESSION['save_user_operation'];
		   //echo $operation.":::".$numrows.":::";
		   if($numrows >=1 && $operation=='new')
		   {
			$count_entry = -9;
		   }
		   $dstta = date('Y-m-d');
		   
		   if($numrows >=1 && $operation !='new')
		   {
			///////////////////////////
			$addquery = $user_locked=='0'?",pin_missed=0":"";
			$query_data ="update userdata set password='$str_cipher_password', role_id='$role_id', firstname='$firstname', lastname='$lastname', email='$email', mobile_phone='$phone', passchg_logon='$chgpword_logon', user_disabled='$user_disable', user_locked='$user_locked', day_1='$day_1', day_2='$day_2', day_3='$day_3', day_4='$day_4', day_5='$day_5', day_6='$day_6', day_7='$day_7', modified=now(), override_wh='$override_wh', extend_wh='$extend_wh',posted_user='$posted_user',last_used_passwords='$LastUsedPassword' where username='$username', super_agent_id = '$superagent_id', reg_status = 0";
			 //echo $query_data;
			
			$result_data = mysql_query($query_data) or die(mysql_error());
			 //echo mysql_error();
			$count_entry = mysql_affected_rows();
		   }
		   if($numrows ==0 && $operation=='new')
		   {
			 /*$pass_expiry_days = $_SESSION['password_expiry_days'];
			 
			 $pass_dateexpire = @date("Y-m-d",strtotime($today."+".$pass_expiry_days."days"));*/
			 $pass_dateexpire = date('Y-m-d',strtotime("+60 days")); 
			 $query_data = "insert into userdata (username,password,role_id, firstname, lastname, email, mobile_phone, passchg_logon, user_disabled, user_locked,day_1,day_2,day_3,day_4,day_5,day_6,day_7,created, modified,override_wh,extend_wh,pass_dateexpire, super_agent_id, reg_status) values( '$username','$str_cipher_password','$role_id', '$firstname','$lastname','$email','$phone','$chgpword_logon','$user_disable','$user_locked','$day_1', '$day_2', '$day_3', '$day_4', '$day_5', '$day_6', '$day_7' , now(), now(), '$override_wh', '$extend_wh', '$pass_dateexpire', '$superagent_id', '0')";
			
			 $result_data = mysql_query($query_data); //or die(mysql_error());
			 $count_entry = mysql_affected_rows();
		   } //End inner else
		   //echo $query_data;
		   return $count_entry;
		  }  
	function doUser($operation,$username,$userpassword,$firstname,$lastname,$email,$phone, $chgpword_logon, $user_locked, $user_disable,$day_1,$day_2,$day_3,$day_4,$day_5,$day_6,$day_7,$override_wh,$extend_wh,$role_id,$role_name,$insurance_coy,$issuer_code,$reg_no,$c_addr,$city,$state,$dob,$sex,$marital_status,$account_no,$account_name,$bank_name,$contact_address,$office_address,$s_agent_id,$office_state,$office_lga,$company_name,$company_address,$rc_number)
	{
				
				$role_name = $this->getitemlabel("role","role_id",$role_id,"role_name");
				$posted_user=$_SESSION['username_sess'];
				$desencrypt = new DESEncryption();
				$count_entry = 0;
				$key = $username;
				$cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null,null);
				$str_cipher_password = $desencrypt->stringToHex ($cipher_password);
				
				$query = "select * from userdata where username='$username'";
				//echo $query;
				$result = mysql_query($query);
				$numrows = mysql_num_rows($result);
				//$operation = $_SESSION['save_user_operation'];
				//echo $operation.":::".$numrows.":::";
				if($numrows >=1 && $operation=='new')
				{
					$count_entry = -9;
					
				}
				if($numrows >=1 && $operation!='new')
				{
						///////////////////////////
						$addquery = $user_locked=='0'?",pin_missed=0":"";
						$query_data ="update userdata set password='$str_cipher_password', role_id='$role_id', role_name = '$role_name', firstname='$firstname', lastname='$lastname', email='$email', mobile_phone='$phone', passchg_logon='$chgpword_logon', user_disabled='$user_disable', user_locked='$user_locked', day_1='$day_1', day_2='$day_2', day_3='$day_3', day_4='$day_4', day_5='$day_5', day_6='$day_6', day_7='$day_7', modified=now(), override_wh='$override_wh', extend_wh='$extend_wh',posted_user='$posted_user', bank_name = '$bank_name', super_agent_id = '$s_agent_id',account_no = '$account_no',account_name = '$account_name',contact_address = '$contact_address',office_address = '$office_address',reg_status = '1',office_state='$office_state',office_lga='$office_lga' $addquery where username='$username'";
						//echo $query_data;
//                    		file_put_contents("june.txt",$query_data);
						$result_data = mysql_query($query_data) or die(mysql_error());
						//echo mysql_error();
						$count_entry = mysql_affected_rows();
                        $_SESSION['reg_status'] = 1;
				}
				//echo "church_id" . $church_id;
				if($numrows ==0 && $operation=='new')
				{
						$pass_expiry_days = $_SESSION['password_expiry_days'];
						$today = @date("Y-m-d");
						$pass_dateexpire = @date("Y-m-d",strtotime($today."+".$pass_expiry_days."days"));
						//echo $branch_id . $branch_name;
						
						 $query_data = "insert into userdata (username,password,role_id,role_name, firstname, lastname, email, mobile_phone, passchg_logon, user_disabled, user_locked,day_1,day_2,day_3,day_4,day_5,day_6,day_7,created, modified,override_wh,extend_wh,pass_dateexpire,posted_user,bank_name,account_no,account_name,super_agent_id,contact_address,office_address,office_state,office_lga,reg_status) values( '$username','$str_cipher_password','$role_id', '$role_name', '$firstname','$lastname','$email','$phone','$chgpword_logon','$user_disable','$user_locked','$day_1', '$day_2', '$day_3', '$day_4', '$day_5', '$day_6', '$day_7' , now(), now(), '$override_wh', '$extend_wh', '$pass_dateexpire', '$posted_user','$bank_name','$account_no','$account_name','$s_agent_id','$contact_address','$office_address','$office_state','$office_lga','1')";
						// $query_data;
						$result_data = mysql_query($query_data); //
						// die(mysql_error());
						if($role_id == 002)
						{
							$this->coperate_data($operation,$role_id,"insert",$company_name,$company_address,$rc_number,$_SESSION['username_sess'],$username);
						}
						
						$count_entry = mysql_affected_rows();
				} 
				//End inner else
				// echo $query_data;
				return $count_entry;
	}		
	function coperate_data($operation,$role_id,$sql,$company_name,$company_address,$rc_number,$officer,$username)
	{
		$sql = "INSERT INTO corperate_data (email,company_name,company_address,rc_number,created,officer) VALUES('$username','$company_name','$company_address','$rc_number',NOW(),'$officer')";
		// echo $sql;
		mysql_query($sql);

	}
	function auditTrail($query,$btn_name,$table,$page_visited,$key = "*",$value = "*")
	{
		
		/// get database name
		$db_name = "";
		$db_sql = "SELECT DATABASE()";
		$result = mysql_query($db_sql);
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$db_name = $row['DATABASE()'];
		}
		///////////
	
		$query_values = array("SELECT","UPDATE","DELETE");
		$table_array  = array();
		$t_sql = "SHOW TABLES";
		$result = mysql_query($t_sql);
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$name = "Tables_in_".db_name;
			$table_array[] = $row['Tables_in_agent_one'];
		}
	
		if(in_array($query,$query_values))
		{
			$officer = $_SESSION['username_sess'];
			$id      = date("ymdhis");
			$sql     = "INSERT INTO audit_trail (id,officer,created,query_type,button_clicked,table_affected,table_key,value,page_visited) VALUES('$id','$officer',NOW(),'$query','$btn_name','$table','$key','$value','$page_visited')";
			mysql_query($sql);
		}else
		{
			die("Accepted values for query are select,update and delete");
		}
		if(!in_array($table,$table_array))
		{
			die("Table name '$table' was not found on your database");
		}
	}
	
		
		function paddZeros($id, $length){
		$data = "";
		$zeros = "";
		 $rem_len = $length - strlen($id);

		if($rem_len > 0){
			for($i=0; $i<$rem_len; $i++){
				$zeros.="0";
			}
			$data = $zeros.$id;
		}else{
			$data = $id;
		}
		return $data;
	}
	
	///////////////////////////////
	function getnextid($tablename){
	//require_once("../../Copy of acomoran/lib/connect.php");
	$id = 0;
	$query = "update gendata set table_id=table_id+1 where table_name= '$tablename'";
	//echo $query;
	$resultid = $this->db_query($query,false);
	// $numrows = mysql_affected_rows();
	//echo 'result '.$resultid;
	if($resultid==0){
		$query_ins = "insert into gendata values ('$tablename', 1)";
		//echo $query_ins;
		$result_ins = $this->db_query($query_ins,false);
		// $numrows = mysql_affected_rows();
	}
	// Get the new id
	$query_sel = "select table_id from gendata where table_name= '$tablename'";
	//echo $query;
	$result_sel = $this->db_query($query_sel);
	// $numrows_sel = mysql_num_rows($result_sel);
		if(count($result_sel)==1){
			// $row = mysql_fetch_array($result_sel);
			$id = $result_sel[0]['table_id'];
			
			//result count when it reaches 
			if($id > 999998){
				$query = "update gendata set table_id=0 where table_name= '$tablename'";
				//echo $query;
				$resultid = $this->db_query($query,false);
			}
		}

	return $id;
	}
	//////////////////////////////////////////
	function getuniqueid($y, $m, $d){
		$month_year = array ('01' => '025',
						'02' => '468',
						'03' => '469',
						'04' => '431',
						'05' => '542',
						'06' => '790',
						'07' => '138',
						'08' => '340',
						'09' => '356',
						'10' => '763',
						'11' => '845',
						'12' => '890');
		$year = array('2009' => '111',
				'2010' => '222',
				'2011' => '333',
				'2012' => '444',
				'2013' => '555',
				'2014' => '777',
				'2015' => '000',
				'2016' => '666',
				'2017' => '999',
				'2018' => '123',
				'2019' => '321',
				'2020' => '431',
				'2021' => '521',
				'2022' => '146',
				'2023' => '246',
				'2024' => '357',
				'2025' => '768',
				'2026' => '430',
				'2027' => '770',
				'2028' => '773',
				'2029' => '873',
				'2030' => '962',
				'2031' => '909',
				'2032' => '830',
				'2033' => '349',
				'2034' => '457',
				'2035' => '248');

		$day = array('01' => '50',
				'02' => '31',
				'03' => '23',
				'04' => '12',
				'05' => '54',
				'06' => '67',
				'07' => '87',
				'08' => '90',
				'09' => '11',
				'10' => '34',
				'11' => '22',
				'12' => '38',
				'13' => '88',
				'14' => '78',
				'15' => '33',
				'16' => '54',
				'17' => '67',
				'18' => '77',
				'19' => '29',
				'20' => '59',
				'21' => '17',
				'22' => '32',
				'23' => '44',
				'24' => '66',
				'25' => '00',
				'26' => '04',
				'27' => '05',
				'28' => '03',
				'29' => '08',
				'30' => '20',
				'31' => '45');

	$unique_id = $year[$y].$month_year[$m].$day[$d];
	return $unique_id;
	}
	//////////////////////////////////////////
	function getuniqueid1($y, $m, $d){
		$month_year = array ('01' => '25',
						'02' => '68',
						'03' => '69',
						'04' => '31',
						'05' => '42',
						'06' => '90',
						'07' => '38',
						'08' => '40',
						'09' => '56',
						'10' => '63',
						'11' => '45',
						'12' => '90');
		$year = array(
				'2012' => '444',
				'2013' => '555',
				'2014' => '777',
				'2015' => '000',
				'2016' => '666',
				'2017' => '999',
				'2018' => '123',
				'2019' => '321',
				'2020' => '431',
				'2021' => '521',
				'2022' => '146',
				'2023' => '246',
				'2024' => '357',
				'2025' => '768',
				'2026' => '430',
				'2027' => '770',
				'2028' => '773',
				'2029' => '873',
				'2030' => '962',
				'2031' => '909',
				'2032' => '830',
				'2033' => '349',
				'2034' => '457',
				'2035' => '888',
				'2036' => '985',
				'2037' => '394',
				'2038' => '125',
				'2039' => '745',
				'2040' => '236');

		$day = array('01' => '50',
				'02' => '31',
				'03' => '23',
				'04' => '12',
				'05' => '54',
				'06' => '67',
				'07' => '87',
				'08' => '90',
				'09' => '11',
				'10' => '34',
				'11' => '22',
				'12' => '38',
				'13' => '88',
				'14' => '78',
				'15' => '33',
				'16' => '54',
				'17' => '67',
				'18' => '77',
				'19' => '29',
				'20' => '59',
				'21' => '17',
				'22' => '32',
				'23' => '44',
				'24' => '66',
				'25' => '00',
				'26' => '04',
				'27' => '05',
				'28' => '03',
				'29' => '08',
				'30' => '20',
				'31' => '45');

	$unique_id = $year[$y].$day[$d];
	return $unique_id;
	}
	
	//////////////////////////////////////////
	function doMenu($menu_id,$menu_name,$menu_url,$parent_menu,$menu_level,$parent_menu2){
			$count_entry = 0;
			$query = "select * from menu  where menu_id='$menu_id'";
			//echo $query;
			$result = mysql_query($query);
			$numrows = mysql_num_rows($result);
			if($numrows >=1){
				 $query_data ="update menu set menu_name='$menu_name', menu_url='$menu_url', parent_id='$parent_menu',  parent_id2='$parent_menu2', menu_level='$menu_level' where menu_id='$menu_id' ";
			//echo $query_data;
			$result_data = mysql_query($query_data);
			$count_entry = mysql_affected_rows();
			}
			else
			{
			$sql="select * from menu  where menu_name='$menu_name'";
			if($res=mysql_query($sql))
			{
				if(mysql_num_rows($res)>=1)
				{
				$count_entry=-9;	
				}
				else if(mysql_num_rows==0)
				{
			 $query_data = "insert into menu (menu_id,menu_name,menu_url,parent_id,parent_id2,menu_level,created) values( '$menu_id','$menu_name','$menu_url','$parent_menu','$parent_menu2','$menu_level',now())";
			//echo $query_data;
			$result_data = mysql_query($query_data);
			$count_entry = mysql_affected_rows();
				}
				else
				{
				$count_entry=-9;	
				}
			}
			}
			return $count_entry;
		}
		/////////////////////////////////////////////////////////
	function getmenu($opt) {
	$filter = "";
	$options = "<option value='#'>::: Select Menu Option ::: </option>";
		 if($opt!= ""){
		 $filter = " and menu_id='".$opt."' "; //" username='$username' and password='$password' ";
		 }
		 $filter .=" order by menu_name ";
		 $dbobject = new dbobject();
	 $user_role_session = $_SESSION['role_id_sess'];
	 //$filter_role_id = $dbobject->getitemlabel('parameter','parameter_name','admin_code','parameter_value');
	 //$filter_menu_id = $dbobject->getitemlabelmenu('parameter','parameter_name','admin_menu_code','parameter_value');
	 //$filteradmin = ($user_role_session == $filter_role_id)?"":" and menu_id not in (".$filter_menu_id.")";
	$query = "select distinct menu_id, menu_name from menu where 1=1 ".$filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = @mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 if($opt==$row['menu_id']) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[menu_id]' $filter >$row[menu_name]</option>";
		$filter='';
		}
	}
	return $options;
	}
	/////////////////////////////////
	function getexistrole($opt) {
	$filter = "";
	$user_role_session = $_SESSION['role_id_sess'];
	//$options = "<option value='#'>::: Select Menu Option ::: </option>";
		 if($opt!= ""){
		 $filter = "where menu_id='".$opt."' "; //" username='$username' and password='$password' ";
		 }
	$query = "select role_id, role_name from role where role_id in (select role_id from menugroup   ".$filter.") and role_id not in(select parameter_value from parameter where parameter_name='$user_role_session' )";
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 //if($opt==$row['role_id']) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[role_id]' $filter >$row[role_name]</option>";
		$filter='';
		}
	}
	return $options;
	}
	///////////////////////////////////////////
	function getnonexistrole($opt) {
	$filter = "";
	$user_role_session = $_SESSION['role_id_sess'];
	//$options = "<option value='#'>::: Select Menu Option ::: </option>";
		 if($opt!= ""){
		 $filter = "where menu_id='".$opt."' "; //" username='$username' and password='$password' ";
		 }
	$query = "select role_id, role_name from role where role_id not in (select role_id from menugroup   ".$filter.") and role_id not in(select parameter_value from parameter where parameter_name='$user_role_session' )";
	
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 //if($opt==$row['role_id']) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[role_id]' $filter >$row[role_name]</option>";
		$filter='';
		}
	}
	return $options;
	}
	
	function doMenuGroup($menu_id,$exist_role){
			$comp_id = "#";
			$count_entry = 0;
			$exist_role_arr = explode(",",$exist_role);
			$role_id = "";
			for($i=0; $i<count($exist_role_arr); $i++){
			$role_id = $role_id."'".$exist_role_arr[$i]."', ";
			}
			$role_id = substr($role_id,0,(strlen($role_id)-2));
			$query_data ="delete from menugroup where role_id not in ($role_id, 001) and menu_id='$menu_id' ";
			//echo $query_data.'<br>';
			$result_data = mysql_query($query_data);
			$count_entry += mysql_affected_rows();

			for($i=0; $i<count($exist_role_arr); $i++){
                echo $query_data_i = "insert into menugroup values ('$exist_role_arr[$i]','$menu_id')";
                //echo $query_data_i.'<br>';
                $result_data_i = mysql_query($query_data_i);
                $count_entry += mysql_affected_rows();
			}

			//echo "Count Entry :: "+$count_entry;
			return $count_entry;
		}
		////////////////////////////////////////////////

		function gettableselect($tablename, $field1, $field2, $opt) {
	$filter = "";
	$options = "<option value=''>::: please select option ::: </option>";
	$query = "select distinct $field1, $field2 from $tablename  ".$filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 if($opt==$row[$field1]) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[$field1]' $filter >$row[$field2]</option>";
		$filter='';
		}
	}
	return $options;
	}
	///////////////////////////////////
	function gettableselect2($tablename, $field1, $field2, $opt,$opt2,$opt3) {
	$filter = "";
	$options = "<option value=''>::: please select option ::: </option>";
	$query = "select distinct $field1, $field2 from $tablename  where $opt2=$opt3".$filter;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 if($opt==$row[$field1]) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[$field1]' $filter >$row[$field2]</option>";
		$filter='';
		}
	}
	return $options;
	}
	///////////////////////////////////
	function gettableselectorder($tablename, $field1, $field2, $opt,$order) {
	$filter = "";
	$order_by = "";
	$options = "<option value=''>::: please select option ::: </option>";
	if($order!='') $order_by = " order by ".$order;
	$query = "select distinct $field1, $field2 from $tablename  ".$filter.$order_by ;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 if($opt==$row[$field1]) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[$field1]' $filter >$row[$field2]</option>";
		$filter='';
		}
	}
	return $options;
 }
	/////////////////////////////////////
	function getdataselect($sql) {
	$filter = "";
	$options = "<option value=''>::: please select option ::: </option>";
	//$query = "select distinct $field1, $field2 from $tablename  ".$filter;
	//echo $sql;
	$result = mysql_query($sql);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		$options = $options."<option value='$row[0]' $filter >$row[1]</option>";
		$filter='';
		}
	}
	return $options;
	}

	
	function getTblField($tablename,$field1,$field2,$field3) {
		$query = "select distinct $field1 from $tablename  where $field2='$field3'";
		//echo $query;
		$result = mysql_query($query);
		$numrows = mysql_num_rows($result);
		if($numrows > 0){
			$row = mysql_fetch_array($result);
			$options = $row[$field1];
		}
		return $options;
	}
	
	function getTblItemList($tablename,$field1) {
	$options = "<option value=''>::: please select option ::: </option>";
		$query = "select distinct $field1 from $tablename";
		//echo $query;
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			$options .= "<option value='$row[$field1]'>$row[$field1]</option>";
		}
		return $options;
	}
	
	function getFormInput($tablename,$field2,$field3,$field4,$field5){
		$query = "select * from $tablename  where $field2='$field3' and $field4='$field5'";
		//echo $query;
		$result = mysql_query($query);
		//$numrows = mysql_num_rows($result);
		/*while($row = mysql_fetch_array($result)){
			$options .= "<input type='checkbox' name='<?php echo $row[$field1]; ?>' id='<?php echo $row[$field1]; ?>'> ".$row[$field]."  &nbsp;&nbsp;&nbsp;&nbsp;".$row[$field1]."<br /><hr></hr>";
		}*/
		return $result;
	}
	
	
	
	function doPasswordChangeExp($username,$user_password, $new_expdate){
				
				$desencrypt = new DESEncryption();
				$count_entry = 0;
				$key = $username;
				$cipher_password = $desencrypt->des($key, $user_password, 1, 0, null,null);
				$str_cipher_password = $desencrypt->stringToHex ($cipher_password);
			$query_data ="update userdata set password='$str_cipher_password', pass_dateexpire='$new_expdate' where username= '$username'";
				//echo $query_data;
				$result_data = mysql_query($query_data);
				$count_entry = mysql_affected_rows();
				
				return $count_entry;
		}
		///////////////////////////////
		// Do password change on logon
		function doPasswordChangeLogon($username,$user_password){
				$desencrypt = new DESEncryption();
				$count_entry = 0;
				$key = $username;
				$cipher_password = $desencrypt->des($key, $user_password, 1, 0, null,null);
				$str_cipher_password = $desencrypt->stringToHex ($cipher_password);
				$query_data ="update userdata set password='$str_cipher_password', passchg_logon='0' where username= '$username'";
				//echo $query_data;
				$result_data = mysql_query($query_data);
				$count_entry = mysql_affected_rows();
				
				return $count_entry;
		}

	
	function getparameter($opt,$parameter_id,$parameter_table,$parameter_col,$val1) {
	$filter = "";
	$options = "<option value=''>::: Select ::: </option>";
		 /*
		 if($opt!= ""){
		 $filter = "where menu_id='".$opt."' and parent_id='#' "; //" username='$username' and password='$password' ";
		 }else{
		 */$filter1 = "";
			if($parameter_id!=''){$filter1= "and  ".$parameter_col." = '$parameter_id' ";}
			$filter = " where 1=1 ";
		 //}
	$query = "select * from ".$parameter_table.$filter.$filter1;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	$filter='';
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 if($opt==$row[$val1]) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[$val1]' $filter >$row[$val1]</option>";
		$filter='';
		}
	}
	return $options;
	}


////////////////////////////////////////////////////////////////BEGIN CodeEngine SAMABOS/////////////////////////////////////////////////////////



			
function getuniqueid2()
{
	$month_year = array ('01' => '025',
						'02' => '468',
						'03' => '469',
						'04' => '431',
						'05' => '542',
						'06' => '790',
						'07' => '138',
						'08' => '340',
						'09' => '356',
						'10' => '763',
						'11' => '845',
						'12' => '890');
		
	$year = array('2009' => '111',
				'2010' => '222',
				'2011' => '333',
				'2012' => '444',
				'2013' => '555',
				'2014' => '777',
				'2015' => '000',
				'2016' => '666',
				'2017' => '999',
				'2018' => '123',
				'2019' => '321',
				'2020' => '431',
				'2021' => '521',
				'2022' => '146',
				'2023' => '246',
				'2024' => '357',
				'2025' => '768',
				'2026' => '430',
				'2027' => '770',
				'2028' => '773',
				'2029' => '873',
				'2030' => '962',
				'2031' => '909',
				'2032' => '830',
				'2033' => '349',
				'2034' => '457',
				'2035' => '248');

	$day = array('01' => '50',
				'02' => '31',
				'03' => '23',
				'04' => '12',
				'05' => '54',
				'06' => '67',
				'07' => '87',
				'08' => '90',
				'09' => '11',
				'10' => '34',
				'11' => '22',
				'12' => '38',
				'13' => '88',
				'14' => '78',
				'15' => '33',
				'16' => '54',
				'17' => '67',
				'18' => '77',
				'19' => '29',
				'20' => '59',
				'21' => '17',
				'22' => '32',
				'23' => '44',
				'24' => '66',
				'25' => '00',
				'26' => '04',
				'27' => '05',
				'28' => '03',
				'29' => '08',
				'30' => '20',
				'31' => '45');
	//////////////--------> get 2day's date		
	$today_date = @date('Y-m-d');
	$date_arr = explode("-",$today_date);
	$unique_id = $year[$date_arr[0]].$month_year[$date_arr[1]].$day[$date_arr[2]];
	return $unique_id;
}
	

function getCustomerDetails($str,$URL)
{	
	
	require "waiseconnectclient.php";
	$wcclient = new wcclient;
	$resp = $wcclient->eHajjWCClientGetPilgrimDetails($str,$URL);
	return $resp;
}



function saveTransEntry($inpFds,$inpFdsVals)
{
		
		$dbobject = new dbobject();
		$daty = @date('Y-m-d H:i:s');
		//if(isset($_SESSION['username_sess']) && !isset($_SESSION['vuvaa_customer_username_sess']))
		//{
		$officer = $_SESSION['username_sess'];
		//}elseif(isset($_SESSION['vuvaa_customer_username_sess']))
		//{
		//	$officer = $_SESSION['vuvaa_customer_username_sess'];
		//}
		$ip = $_SERVER['REMOTE_ADDR'];
		
		////////////////////////////////////////////////////////transaction table/////////////////////////////////////////
		//$itmcd = $_REQUEST['itmcd'];	
		//$merharr = explode('-',$itmcd);
		//$merchant_id = $_SESSION[uniquemID];
		//$col_1 = $_REQUEST['col_1-fd'];
		//if($itmcd=='#')
		//{
			$merchant_id = $_REQUEST['merchant_id-fd'];
			$amount = $_REQUEST['amount'];
			$depositorname = $_REQUEST['depositorname'];
			$portalid = strtoupper($_REQUEST['portalid']);
			$desc = "CASH DEPOSIT ".$depositorname;
			$branch_code = $_SESSION['branch_code_sess'];
			
			$branch_acct = $dbobject->getitemlabel('station','station_code',$branch_code,'station_acct');
			$trans_limit = $dbobject->getitemlabel("userdata","username",$officer,"trans_limit");
			if($amount > $trans_limit){
				$trans_limit_flag = '1';
			}else{
				$trans_limit_flag = '0';
			}
			
		//}else{
		//	$amount = $dbobject->getitemlabel('merchant_item_setup','item_code','ACC-VMCHT'.$itmcd,'item_value');
		//	$desc = "Purchase "."ACC-VMCHT".$itmcd;
		//}
		//$itmdecs = $dbobject->getitemlabel('merchant_item_setup','item_code',$itmcd,'item_name');
		
		$trans_id = $_REQUEST['trans_ext_id-fd'];
		$trans_type = 'MPMT';
		
		
		$query_trans = "INSERT INTO transaction_table SET transaction_id='$trans_id', source_acct='$branch_acct', destination_acct='$merchant_id', transaction_desc='$desc', trans_type = '$trans_type', transaction_amount='$amount', response_code='9', payment_mode='', branch_code ='$branch_code', depositorname='$depositorname', portalid='$portalid', trans_limit_flag='$trans_limit_flag', posted_ip='$ip', created='$daty', posted_user='$officer'";
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		///////////////////////////////////// for transaction extention table///////////////
		$whrcond = 0;
		$resp = 0;
		$query = "insert into transaction_extension set ";
		//$where = "";
		for($i=0;$i<count($inpFds);$i++)
		{	
			$field = explode("-",$inpFds[$i]);
			if($field[1]=='fd')
			{
				$query .= $field[0]."='".$inpFdsVals[$i]."', ";
			}
		}
		$query = rtrim($query,", ");
		$query_transext = $query;
		$query_transext .=';';
		/////////////////////////////////////////////////////end of transaction extention table///////////////////////////		
	
		mysql_query("BEGIN");
		$result_transext = @mysql_query($query_transext);
		$cnt1 = @mysql_affected_rows();
		$result_trans = @mysql_query($query_trans);
		$cnt2 = @mysql_affected_rows();
		
		if($cnt1 > 0 && $cnt2 > 0){
			@mysql_query("COMMIT");
			if($amount <= $trans_limit){
				////////////call webserver here/////////////////////
				$resp = $dbobject->goCallWebServer($branch_acct,$merchant_id,$depositorname,$portalid,$amount,$trans_id);
			}else{
				$resp = "Pending:Awaiting Authorization transaction amount is above your post limit";
				
			}		
		}else{
			mysql_query("ROLLBACK"); 
			$resp = "ERROR:Please contact Customer Care OR refresh and try again";
			$attempt = $dbobject->logaccess ($user,date("Y-m-d H:i:s"),mysql_error());
			}
		return $resp;
		
}


function goCallWebServer($branch_acct,$merchant_id,$depositorname,$portalid,$amount,$trans_id){
		require "waiseconnectclient.php";
		$wcclient = new wcclient;
		$dbobject = new dbobject();
		///////////////////////////////////////////////////////////////////////////////////////////////
		//$officer_branch_code = $dbobject->getitemlabel('userdata','username',$officer,"branch_code");
		$destAccount = $dbobject->getitemlabel('merchant_settlement_account_setup','merchant_id',$merchant_id,'account_no');
		$sourceAccount = $branch_acct;//$dbobject->getitemlabel('station','station_code',$officer_branch_code,'station_acct');
			$wcresp = $wcclient->doDepositWC($depositorname,$portalid,$amount,$sourceAccount,$destAccount,$trans_id);
			//print_r($wcresp);
			$wcresp = explode("~",$wcresp);
			if($wcresp[0]=='000'){
					$updateresp = $dbobject->doDbTblUpdate('transaction_table',array('response_code'),array(0),array('transaction_id'),array($trans_id));
					if($updateresp=='1'){
						$resp = "SUCCESSFUL:Please wait you will be redirected in a moment";
					}else{
						$resp = "ERROR:Updating status from WaiseConnent";
						$attempt = $dbobject->logaccess ($user,date("Y-m-d H:i:s"),mysql_error());
					}
			}else{
					$updateresp = $dbobject->doDbTblUpdate('transaction_table',array('response_code'),array($wcresp[0]),array('transaction_id'),array($trans_id));
					if($updateresp=='1'){
						$resp = "ERROR:".$wcresp[1];//.$wcresp[0];
					}else{
						$resp = "ERROR:Updating status from WaiseConnent";
						$attempt = $dbobject->logaccess ($user,date("Y-m-d H:i:s"),mysql_error());
					}
			}
			return $resp;
		////////////////////////////////////////////////////////////////////////////////////////////////////
	}

function getitemcount($tablename,$table_col,$table_val,$ret_val) {
	$label = "";
	$table_filter = " where ".$table_col."='".$table_val."'";

	 $query = "select Count(".$ret_val.") counter from ".$tablename.$table_filter;
	//echo $query;
    file_put_contents("jude.txt",$query);
	$result = mysql_query($query);//or die(mysql_error());
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		$row = mysql_fetch_array($result);
		$label = $row['counter'];
	}
	return $label;
	}

	

////////////////////////////////////////////////////////////////END CodeEngine SAMABOS///////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////
//////////////////////////Beginning of Isaiah///////////////////////////////
	function getrecordsetArr($tablename,$table_col_arr,$table_val_arr)
	{
		$where_clause = " ";
		for($i=0;$i<count($table_col_arr);$i++)
		{
			$where_clause .=$table_col_arr[$i]."='".$table_val_arr[$i]."' and ";
		}
		
		$where_clause = rtrim($where_clause," and ");
		//echo 'country code : '.$countrycode;
		$label = "";
		$table_filter = " where ".$where_clause;
	
		$query = "select * from ".$tablename.$table_filter;
		//echo $query;
		$result = mysql_query($query);
		return $result;
	}
	
	function getrecordsetArrLim($tablename,$table_col_arr,$table_val_arr,$limval,$orderby_arr,$orderdir)
	{
		$where_clause = " ";
		for($i=0;$i<count($table_col_arr);$i++)
		{
			$where_clause .=$table_col_arr[$i]."='".$table_val_arr[$i]."' and ";
		}
			$table_order='';
			if($orderby_arr!=''){
					for($i=0;$i<count($orderby_arr);$i++)
					{
						$orderby_str .=$orderby_arr[$i].", ";
					}
					
					$orderby_str = rtrim($orderby_str,",");
					$table_order = " ORDERBY ".$orderby_str." ".$orderdir;
			}
		$where_clause = rtrim($where_clause," and ");
		//echo 'country code : '.$countrycode;
		$label = "";
		$table_filter = " where ".$where_clause.$table_order." LIMIT ".$limval;
	
		$query = "select * from ".$tablename.$table_filter;
		//echo $query;
		$result = mysql_query($query);
		return $result;
	}
	function gettableselectrpt($tablename, $field1, $field2, $opt,$opt2) {
	$filter = $opt ;
	$options = "<option value=''>::: please select option ::: </option>";
	$query = "select distinct $field1, $field2 from $tablename  ".$opt2;
	//echo $query;
	$result = mysql_query($query);
	$numrows = mysql_num_rows($result);
	if($numrows > 0){
		for($i=0; $i<$numrows; $i++){
		$row = mysql_fetch_array($result);
		//echo $row['country_code'];
		 if($opt==$row[$field1]) $filter='selected';
		//echo ($opt=='$row["country_code"]'?'selected':'None');
		$options = $options."<option value='$row[$field1]' $filter >$row[$field2]</option>";
		$filter='';
		}
	}
	return $options;
	}
	function getTableSelectArr($tablename,$selarr,$whrarr,$whrvalarr,$order,$orderdir,$opt,$initOpt)
	{
		$filter = $opt;
		$selectVar = " ";
		$whereClause = " where ";
		for($i=0;$i<count($selarr);$i++)
		{
			$selectVar .=$selarr[$i].", ";
			if($i==0){
			$optDisplayVal = $selarr[$i];
			}else
			{
				$optDisplayName .= $row[$selarr[$i]];
			}
		}
		$selectVar = rtrim($selectVar,', ');
		
		for($i=0;$i<count($whrarr);$i++)
		{
			$whereClause .=$whrarr[$i]."='".$whrvalarr[$i]."' and ";
		}
		
		$whereClause = rtrim($whereClause," and ");
		if($order!='')
		{ 
			if($orderdir=='')
			{
				$oderby = 'order by '.$order.' asc';
			}else
			{
				$oderby = 'order by '.$order.' '.$orderdir;
			}
		}
		else $oderby ="";
		$options = "<option value='#'>::: Select ".$initOpt." :::</option>";
		$query = "select distinct $selectVar from $tablename ".$whereClause.$oderby;
		//echo $query.'-'.$opt;
		$result = $this->db_query($query);
		$numrows = count($result);
		if($numrows > 0){
			for($i=0; $i<$numrows; $i++){
			$row = $result;
			for($j=0;$j<count($selarr);$j++)
			{
				if($j>0){
					$optDisplayName .= $row[$selarr[$j]]." ";
				}
			}
			 if($opt==$row[$optDisplayVal]) $filter='selected';
			//echo ($opt=='$row["country_code"]'?'selected':'None');
			$options = $options."<option value='$row[$optDisplayVal]' $filter >$optDisplayName</option>";
			$filter='';
			$optDisplayName ="";
			//echo 'yes'.$optDisplayName;
			//echo $row[$field1];
			}
	}
	
	return $options;
	}
    
    
    
//    function genericState($state)
//    {
//        $sql = "SELECT Lga FROM lga WHERE State ='$state'";
//        $result = mysql_query($sql);
//        $bb = "";
//        while($row = mysql_fetch_array($result))
//        {
//            $bb.="<option>".$row."</option>";
//        }
//    }

	function doDbTblUpdate($tbl,$setFieldArr,$setFieldValArr,$whrFieldArr,$whrFieldValArr)
	{
		if(count($setFieldArr)==count($setFieldValArr) && count($whrFieldArr)==count($whrFieldValArr))
		{
			////////// set clause starts here////////////////////////////////
			for($i=0; $i<count($setFieldArr);$i++)
			{
				$setClause .= $setFieldArr[$i]."='".$setFieldValArr[$i]."', ";
			}
			$setClause = rtrim($setClause,", ");
			//echo $setClause;
			/////////////////////////////////////////////////////////////////
			///////////////where clause starts here/////////////////////////
			for($j=0; $j<count($whrFieldArr);$j++)
			{
				$whrClause .= $whrFieldArr[$j]."='".$whrFieldValArr[$j]."' AND ";
			}
			$whrClause = rtrim($whrClause," AND ");
			// echo $whrClause;
			///////////////////////////////////////////////////////////////
			////////////the complete query/////////////////////////////////
			$query = "UPDATE ".$tbl." SET ".$setClause." WHERE ".$whrClause;
			 //echo $query;
			$result = $this->db_query($query,false);
			if($result>=0)
			{
				$resp = 1;//successful
				return $resp;
			}else
			{
				$resp = 2;//update not successful. Possibly transaction details not available 
				return $resp;
			}
		}else
		{
			$resp = 3; //array count does not match
			return $resp;
		}
		
	}
	
	function getItemLabelArr($tablename,$table_col_arr,$table_val_arr,$ret_val_arr)
	{
		$label = "";
		/////////////////////////////////////////////////////////////////
		////////// select clause starts here////////////////////////////////
		if($ret_val_arr=="*")
		{
			$qquery = "SHOW COLUMNS FROM $tablename ";
			//echo $qquery;
			$result = $this->db_query($qquery);
//			echo mysql_error();
//			while($roww = mysql_fetch_array($result))
            foreach($result as $roww)
      		{
				$selectClause .=$roww[0].", ";
				$ret_val[] = $roww[0];
			}
			$retCount =$ret_val;
			$selectClause = rtrim($selectClause,", ");
		}else
		{
			for($i=0; $i<count($ret_val_arr);$i++)
			{
				$selectClause .=$ret_val_arr[$i].", ";
			}
			$selectClause = rtrim($selectClause,", ");
			$retCount = $ret_val_arr;
			//echo $setClause;
		}
		/////////////////////////////////////////////////////////////////
		///////////////where clause starts here/////////////////////////
		for($j=0; $j<count($table_col_arr);$j++)
		{
			$whrClause .= " AND ".$table_col_arr[$j]."='".$table_val_arr[$j]."' ";
		}
		$whrClause = rtrim($whrClause,", ");
		/////////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////////////
		$table_filter = " where 1=1 ".$whrClause;
	
		$query = "select ".$selectClause." from ".$tablename.$table_filter;
		//echo $query;
		$result = $this->db_query($query);
		$numrows = count($result);
		if($numrows > 0)
		{
			$retValue  = $result;
		}
		return $retValue;
	}
	
	
	function fidelityRespCodes($success)
	{
		switch($success)
		{
		   case "00":
		   return 'Successful Transaction';break;
		   case "01":
		   return 'Failed Transaction';break;
		   case "02":
		   return 'Pending Transaction';break;
		   case "03":
		   return 'Transaction Cancelled';break;
		   case "04":
		   return 'Not Processed';break;
		   case "05":
		   return 'Invalid Merchant';break;
		   case "06":
		   return 'Inactive Merchant';break;
		   case "07":
		   return 'Invalid Order ID';break;
		   case "08":
		   return 'Duplicate Order ID';break;
		   case "09":
		   return 'Invalid Amount';break;
		   default:
				 echo "Transaction Failed Due to UNKNOWN ERROR!!!";break;
		}  
	}
	
//////////////////////////////////MR TURBO////////////////////////////////////////////////////////
	function StrongPasswordChecker($pwd)
	{
		if(strlen($pwd) < 8) 
		{
			$error .= "Password too short! Minimum of 8 Xters Required!<br/>";
		}
		
		if(strlen($pwd) > 20) 
		{
			$error .= "Password too long! Maximum of 20 Xters Required!<br/>";
		}
				
		if(!preg_match("#[0-9]+#", $pwd))
		{
			$error .= "Password must include at least One Number!<br/>";
		}
		
		
		if(!preg_match("#[a-z]+#", $pwd)) 
		{
			$error .= "Password must include at least One SMALL Letter! <br/>";
		}
		
		
		if(!preg_match("#[A-Z]+#", $pwd)) 
		{
			$error .= "Password must include at least one CAPS! <br/>";
		}
		
				
		if(!preg_match("#\W+#", $pwd)) 
		{
			$error .= "Password must include at least One Symbol!<br/>";
		}
		
		if($error)
		{
			$ErrorResp =  $error;
		} 
		else 
		{
			$ErrorResp = '1';
		}	
		return $ErrorResp;
	}
	//////////////////////////////////

		

//////////////////////////////////Kunle Mutual DIP Functions
function doDbTblInsert($tbl,$setFieldArr,$setFieldValArr)
	{
		if(count($setFieldArr)==count($setFieldValArr))
		{
			////////// set clause starts here////////////////////////////////
			for($i=0; $i<count($setFieldArr);$i++)
			{
				$setClause .= $setFieldArr[$i]."='".$setFieldValArr[$i]."', ";
			}
			$setClause = rtrim($setClause,", ");
			//echo $setClause;
			/////////////////////////////////////////////////////////////////
			////////////the complete query/////////////////////////////////
			$query = "INSERT INTO ".$tbl." SET ".$setClause;
			 //echo $query;
			if($result = mysql_query($query))
			{
				$resp = 1;//successful
				return $resp;
			}else
			{
				$resp = 2;//insertion not successful. Possibly transaction details not available 
				return $resp;
			}
		}
		else
		{
			$resp = 3; //array count does not match
			return $resp;
		}
		
	}


function encrypt_password($username,$userpassword)
	{
	$desencrypt = new DESEncryption();
	$key = $username;
	$cipher_password = $desencrypt->des($key, $userpassword, 1, 0, null,null);
	$str_cipher_password = $desencrypt->stringToHex($cipher_password);	
	return $str_cipher_password;
	}


////////////////////////////////////////////////////////
///////////////////////////////////
////Author Isaiah//////////////////



function decrypt_password($username,$pass_crypt)
{
$key = $username;
$desencrypt = new DESEncryption();
$cipher_password = $desencrypt->hexToString($pass_crypt);	
$plain_pass = $desencrypt->des($key, $cipher_password, 0, 1);
return $plain_pass;
}


  function dash_name($role){
  if ($_SESSION[role_id_sess]=='001'){
	 $name=$_SESSION[firtname]. " ". $_SESSION[lastname];  
	  
  }
  }
 
	  
	  
    function logger($mssg)
    {
        $myfile = fopen("logs.txt", "a");
        $txt = "[ ".date("Y/M/d h:i:s")." ] --> ".$mssg."\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }

    public function getCurrentData($table_name,$table_field,$table_id)
    {
        $sql = "SELECT * FROM $table_name WHERE  $table_field = '$table_id' LIMIT 1";
        $result = $this->db_query($sql);
        return $result[0];
    }
	
    public function logData($current_data,$insert_data,Array $option, Array $exempt = [])
    {
        $result       = $this->doInsert("log_table",array("username"=>$_SESSION['username_sess'],"table_name"=>$option['table_name'],"table_id"=>$option['table_id'],"table_alias"=>$option['table_alias'],"created"=>date("Y-m-d h:i:s")),[]);
        $insert_id    = $this->getInsert_id();
        if($result == "1")
        {
            $difference = array_diff($insert_data,$current_data);
            foreach($difference as $key=>$value)
            {
                if(!in_array($key,$exempt))
                {
                    $this->doInsert("log_details",array("log_id"=>$insert_id,"field_name"=>$key,"previous_data"=>$current_data[$key],"current_data"=>$value,"field_alias"=>""),[]);
                }
            }
        }
    }
	public function getInsert_id()
	 {
		 return mysqli_insert_id($this->myconn);
	 }
    
	
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//End Class
?>
