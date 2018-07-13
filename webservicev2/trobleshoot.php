<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class Trobalsooting extends commonclass{
  public function trobalshoot($request){
     $exceute = $this->query("SELECT * FROM app_userdetails WHERE user_id='".$request['user_id']."'");
	 $data = $this->fetchRow($exceute);
     if($request['buttontext']=='Unlock Reporting?'){
		if($data['reporing_lock']==1){
			echo json_encode(array('success'=>'NO','message'=>'Your reporting still locked! Please contact to your Administrator','reporing_lock'=>'1'));exit;
				 
		}else{
		    echo json_encode(array('success'=>'YES','message'=>'Your reporting has been unlocked. Now you can work with app','reporing_lock'=>'0'));exit;
		}
	 }
	 if($request['buttontext']=='Help?'){
		if($data['reporing_lock']==1){
			echo json_encode(array('success'=>'NO','message'=>'Your reporting still locked! Please contact to your Administrator','reporing_lock'=>'1'));exit;
		}else{
		    echo json_encode(array('success'=>'NO','message'=>'Please contact Admin for user Manual','reporing_lock'=>'1'));exit;
		}
	 }
	 if($request['buttontext']=='Forget Personal Password?'){
		if($data['reporing_lock']==1){
			echo json_encode(array('success'=>'NO','message'=>'Your reporting still locked! Please contact to your Administrator','reporing_lock'=>'1'));exit;
		}else{
		    echo json_encode(array('success'=>'NO','message'=>'Your personal password will be sent to your email!','reporing_lock'=>'1'));exit;
		}
	 }
	 echo json_encode(array('success'=>'NO','message'=>'No action','reporing_lock'=>'1'));exit;
  }
}
$obj = new Trobalsooting();
$obj->trobalshoot($_REQUEST);
?>