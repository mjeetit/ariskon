<?php
header('Content-type: application/json');
$user_id = isset($_REQUEST['user'])?$_REQUEST['user']:0;
include 'config.php';
 
 $sql = "SELECT email,email_password FROM employee_personaldetail WHERE user_id='".$user_id."'";  
 $execute = mysql_query($sql);
 $result = mysql_fetch_assoc($execute);
 $data = array();
   if(!empty($result)){
        $data['Email'] = $result['email'];
		$data['Pass'] = $result['email_password'];
	   echo json_encode(array('success'=>'YES','Email'=>$result['email'],'Pass'=>$result['email_password'])); exit; 
	}else{
	    echo json_encode(array('success'=>'NO','message'=>'These is no detail!')); exit;
	}
?>