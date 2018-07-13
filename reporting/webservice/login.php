<?php
header('Content-type: application/json');
$username = isset($_REQUEST['useracess'])?$_REQUEST['useracess']:'';
$password = isset($_REQUEST['userkey'])?$_REQUEST['userkey']:'';
$emei_number = isset($_REQUEST['emei_number'])?$_REQUEST['emei_number']:'';
include 'config.php';
if($username!='' && $password){
	$sql = "SELECT * FROM users WHERE username='".$username."' AND password='".md5($password)."'"; 
	$execute = mysql_query($sql); 
	$result = mysql_fetch_assoc($execute);
	if(!empty($result)){
	    $userlocation = getdetail($result['user_id']);
		 $sql = "INSERT INTO app_userdetails SET user_id='".$result['user_id']."',emei_number='".$emei_number."'";
         $execute = mysql_query($sql);
		$finalresponse = array('success'=>'YES','message'=>'Login Successfully')+$userlocation;
	    echo json_encode($finalresponse);exit;
	}else{
	   echo json_encode(array('success'=>'NO','message'=>'Invalid username or password'));exit;
	} 
}else{
   echo json_encode(array('success'=>'NO','message'=>'Username Or Password empty'));exit;
}

function getdetail($user_id){
   $sql = "SELECT EL.*,EP.designation_id,ER.region_name,EA.area_name,EH.headquater_name,EC.city_name,EP.first_name,EP.last_name,EP.email,EP.contact_number FROM emp_locations EL 
   		   INNER JOIN employee_personaldetail EP ON EP.user_id=EL.user_id
		   INNER JOIN region ER ON ER.region_id=EL.region_id
		   INNER JOIN `area` EA ON EA.area_id=EL.area_id
		   INNER JOIN headquater EH ON EH.headquater_id=EL.headquater_id
		   INNER JOIN city EC ON EC.city_id=EL.city_id
		   WHERE EL.user_id='".$user_id."'"; 
	$execute = mysql_query($sql); 
	$result = mysql_fetch_assoc($execute);
	$data = array();
	$data['User'] = ($result['user_id']>0)?$result['user_id']:'0';
	$data['Designation'] = $result['designation_id'];
	$data['Name'] = $result['first_name'].' '.$result['last_name'];
	$data['Phone'] = $result['contact_number'];
	$data['Email'] = $result['email'];
	$data['Zone'] = $result['zone_id'];
	$data['Region'] = $result['region_id'];
	$data['Area'] = $result['area_id'];
	$data['Headquater'] = $result['headquater_id'];
	$data['City'] = $result['city_id'];
   return $data;	
}

?>