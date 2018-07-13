<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class Authentiction extends commonclass{
   public function login($username,$password,$emei_number,$personal_pass){
      if($username!='' && $password!=''){
	     $sql = "SELECT * FROM users WHERE username='".$username."' AND password='".md5($password)."'";
		 $execute = $this->query($sql);
		 $result = $this->fetchRow($execute);
		 if(!empty($result)){
		     $sqlgetimi = "SELECT * FROM app_userdetails WHERE user_id='".$result['user_id']."'";
			 $executeimi = $this->query($sqlgetimi);
			 $resultapp = $this->fetchRow($executeimi);
			 if(!empty($resultapp)){
		     if($resultapp['reporing_lock']=='1'){
			       return array('success'=>'NO','message'=>'Your Reporting has been Blocked!PLease contact to admin');
			 }
			 elseif(trim($resultapp['emei_number'])!=$emei_number && $resultapp['emei_check']=='1'){
			      return array('success'=>'NO','message'=>'EMEI Number Missmatch!PLease contact to admin');
			 }
			 elseif((trim($resultapp['emei_number'])!=trim($emei_number) && $resultapp['emei_check']=='0') || trim($resultapp['emei_number'])==trim($emei_number)){
				$userlocation = $this->getdetail($result['user_id']);
				if($userlocation['LastReport']=='0000-00-00' OR $userlocation['LastReport']==''){
					$userlocation['LastReport']=$userlocation['doj'];

				}
				$sql = "UPDATE app_userdetails SET emei_number='".$emei_number."',emei_check='1',personal_pass='".$personal_pass."',last_reported='".$userlocation['LastReport']."' WHERE user_id='".$result['user_id']."'";
				$this->query($sql);
				$sql="INSERT INTO login_logout_details (user_id,login_time,login_via) values('".$result['user_id']."','".date('Y-m-d H:i:s',time())."','1')";
				$this->query($sql);
				return array('success'=>'YES','message'=>'Login Successfully')+$userlocation;
			 }
			 else{
			    $userlocation = $this->getdetail($result['user_id']);
				$sql="INSERT INTO login_logout_details (user_id,login_time,login_via) values('".$result['user_id']."','".date('Y-m-d H:i:s',time())."','1')";
				$this->query($sql);
				return array('success'=>'YES','message'=>'Login Successfully')+$userlocation;
			 }
		 }else{
				$userlocation = $this->getdetail($result['user_id']);
				$sql = "INSERT INTO app_userdetails SET user_id='".$result['user_id']."',emei_number='".$emei_number."',install_date=NOW(),personal_pass='".$personal_pass."',last_reported='".$userlocation['LastReport']."'";
				$this->query($sql);
				$sql="INSERT INTO login_logout_details (user_id,login_time,login_via) values('".$result['user_id']."','".date('Y-m-d H:i:s',time())."','1')";
				$this->query($sql);
				return array('success'=>'YES','message'=>'Login Successfully')+$userlocation;
		    }
		 }else{
		   return array('success'=>'NO','message'=>'Invalid username or password');
		 }
	  }
	   else{
	     return array('success'=>'NO','message'=>'Username Or Password empty');
	  }
   }
}
//mysql_query("INSERT INTO app_test SET request_date='".json_encode($_REQUEST)."'");
//echo base64_encode('test');
/*$username = isset($_REQUEST['useracess'])?base64_decode($_REQUEST['useracess']):'';
$password = isset($_REQUEST['userkey'])?base64_decode($_REQUEST['userkey']):'';
$emei_number = isset($_REQUEST['emei_number'])?trim($_REQUEST['emei_number']):'';
$personal_pass = isset($_REQUEST['personal_pass'])?base64_decode(trim($_REQUEST['personal_pass'])):'';*/
$username = isset($_REQUEST['useracess'])?$_REQUEST['useracess']:'';
$password = isset($_REQUEST['userkey'])?$_REQUEST['userkey']:'';
$emei_number = isset($_REQUEST['emei_number'])?trim($_REQUEST['emei_number']):'';
$personal_pass = isset($_REQUEST['personal_pass'])?trim($_REQUEST['personal_pass']):'';

$obj = new Authentiction();
$response = $obj->login($username,$password,$emei_number,$personal_pass);
echo json_encode($response);exit;
?>