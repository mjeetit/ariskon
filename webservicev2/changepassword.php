<?php
header('Content-type: application/json');
$user_id 		= isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$oldpass 		= isset($_REQUEST['oldpass'])?$_REQUEST['oldpass']:'';
$newpass 		= isset($_REQUEST['newpass'])?$_REQUEST['newpass']:'';
//$emei_number 	= isset($_REQUEST['emei_number'])?$_REQUEST['emei_number']:0;
include 'config.php';

if($oldpass!='' && $newpass!=''){
    $sql = "SELECT * FROM app_userdetails WHERE user_id='".$user_id."'";
	$execute = mysql_query($sql);
	$detail = mysql_fetch_assoc($execute);
	if($detail['personal_pass']!=trim($oldpass)){
	  echo json_encode(array('success'=>'NO','message'=>'Old password mismatch!')); exit;
	}else{
	  $update = "UPDATE app_userdetails SET personal_pass='".$newpass."' WHERE user_id='".$user_id."'";
	  mysql_query($update);
	  echo json_encode(array('success'=>'YES','message'=>'Password has been updated successfully!')); exit;
	}
}else{
     echo json_encode(array('success'=>'NO','message'=>'Old Password or new password can not be blank!')); exit;
}
?>