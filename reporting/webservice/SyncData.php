<?php
header('Content-type: application/json');
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';
include 'config.php';
switch($mode){
     case 'CheckSync':
	 $user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
	 $emei_number = isset($_REQUEST['emei_number'])?$_REQUEST['emei_number']:'';
	 $recArr = checkFirstLogin($user_id);
	 $d_sync = 'NO';
		$w_sync = 'NO';
		$m_sync = 'NO';
	 if(!empty($recArr)){
	    $syncDate 		=  $recArr['last_sync_date'];
		$dailySync 		=  $recArr['weekly_sync'];
		$monthlySync	=  $recArr['monthly_sync']; 
		$currentDate 	=  date('Y-m-d');
		$lastsync   	= (strtotime($currentDate)-strtotime($syncDate)) / 86400;
		$weeklysync   	= (strtotime($currentDate)-strtotime($dailySync)) / 86400;
		$monthlysync   	= (strtotime($currentDate)-strtotime($monthlySync)) / 86400; 
		
		if($lastsync>=1){
		  $d_sync = 'YES';
		  $sql = "UPDATE app_userdetails SET last_sync_date='".date('Y-m-d')."' WHERE id='".$recArr['id']."'";
     	  $execute = mysql_query($sql);
		}
		if($weeklysync>=7){
		  $w_sync = 'YES';
		  $sql = "UPDATE app_userdetails SET weekly_sync='".date('Y-m-d')."' WHERE id='".$recArr['id']."'";
     	  $execute = mysql_query($sql);
		}
		if($monthlysync>=30){
		  $m_sync = 'YES';
		  $sql = "UPDATE app_userdetails SET monthly_sync='".date('Y-m-d')."' WHERE id='".$recArr['id']."'";
     	  $execute = mysql_query($sql);
		}
	 }
	 echo json_encode(array('success'=>$d_sync,'weekly'=>$w_sync,'monthly'=>$m_sync)); exit;
	break;
}	 


function checkFirstLogin($user_id){
    $sql =  "SELECT * FROM app_userdetails WHERE user_id='".$user_id."'";
	$execute = mysql_query($sql);
	$result = mysql_fetch_assoc($execute);
	return $result;
}

?>