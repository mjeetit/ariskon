<?php
header('Content-type: application/json');
$mode = isset($_REQUEST['Mode'])?$_REQUEST['Mode']:'';
$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
switch($mode){
     case 'NotificationList':
		$notifications = notifications();
	 foreach($notifications as $notification){
	  	$data['Notification'] = $notification['notific_desc'];
	  	$data['BY'] = $notification['first_name'];
		$data['Start'] = $notification['start_date'];
		$data['End'] = $notification['end_date'];
		$newdata[] = $data;
	  }
	 break;
	 case 'NotificationSave':
		$regions = getRegion($parent);
	 foreach($regions as $region){
	  	$data['Region'] = $region['region_id'];
		$data['Zone'] = $region['zone_id'];
		$data['Region'] = $region['region_id'];
	  	$data['RegionName'] = $region['region_name'];
		$data['Location'] = $region['location_code'];
		$newdata[] = $data;
	  }
	 break;
	
}
if(!empty($newdata)){
  echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
}else{
  echo json_encode(array('success'=>'NO','message'=>'There is no notifications yet')); exit;
}

function notifications($param=false){
   $sql = "SELECT NT.*,UT.first_name FROM app_notifications NT INNER JOIN employee_personaldetail UT ON NT.user_id=UT.user_id WHERE 1";
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
?>