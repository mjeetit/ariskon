<?php
header('Content-type: application/json');
$user_id = isset($_REQUEST['user'])?$_REQUEST['user']:0;
$mode = isset($_REQUEST['Mode'])?$_REQUEST['Mode']:'DoctorVisit';
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
  switch($mode){
   case "DoctorVisit":
    $sql = "SELECT CD.doctor_name,PT.patch_name,ADV.date_added,EP.first_name,EP.last_name,CT.city_name,HQ.headquater_name 
	        FROM app_doctor_visit ADV 
			INNER JOIN crm_doctors CD ON CD.doctor_id=ADV.doctor_id 
			INNER JOIN patchcodes AS PT ON PT.patch_id=CD.patch_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=ADV.user_id
			LEFT JOIN city CT ON CT.city_id=PT.city_id
			LEFT JOIN headquater HQ ON HQ.headquater_id=PT.headquater_id  WHERE ADV.user_id='".$user_id."'";
	$execute = mysql_query($sql);
	$newdata = array();
	while($data = mysql_fetch_assoc($execute)){
	  $records['Doctor'] = $data['doctor_name'];
	  $records['Patch'] =  $data['patch_name'];
	  $records['Emp'] =  $data['first_name'].' '.$data['last_name'];
	  $records['City'] =  $data['city_name'];
	  $records['Headquater'] =  $data['headquater_name'];
	  $records['Date'] =  date('d-m-Y H:i',strtotime($data['date_added']));
	  $newdata[] = $records;
	}
   break;
   case "ChemistVisit":
    $sql = "SELECT CC.chemist_name,PT.patch_name,ACV.date_added,EP.first_name,EP.last_name,CT.city_name,HQ.headquater_name 
	        FROM app_chemist_visit ACV 
			INNER JOIN crm_chemists CC ON CC.chemist_id=ACV.chemist_id
			INNER JOIN crm_doctor_chemists CDC ON CDC.chemist_id=CC.chemist_id
			INNER JOIN crm_doctors CD ON CD.doctor_id=CDC.doctor_id  
			INNER JOIN patchcodes AS PT ON PT.patch_id=CD.patch_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=ACV.user_id
			LEFT JOIN city CT ON CT.city_id=PT.city_id
			LEFT JOIN headquater HQ ON HQ.headquater_id=PT.headquater_id  WHERE ACV.user_id='".$user_id."'";
	$execute = mysql_query($sql);
	$newdata = array();
	while($data = mysql_fetch_assoc($execute)){
	  $records['Chemist'] = $data['chemist_name'];
	  $records['Patch'] =  $data['patch_name'];
	  $records['Emp'] =  $data['first_name'].' '.$data['last_name'];
	  $records['City'] =  $data['city_name'];
	  $records['Headquater'] =  $data['headquater_name'];
	  $records['Date'] =  date('d-m-Y H:i',strtotime($data['date_added']));
	  $newdata[] = $records;
	}
   break;
   case "StockistVisit":
    $sql = "SELECT CC.chemist_name,PT.patch_name,ACV.date_added,EP.first_name,EP.last_name,CT.city_name,HQ.headquater_name 
	        FROM app_chemist_visit ACV 
			INNER JOIN crm_chemists CC ON CC.chemist_id=ACV.chemist_id
			INNER JOIN crm_doctor_chemists CDC ON CDC.chemist_id=CC.chemist_id
			INNER JOIN crm_doctors CD ON CD.doctor_id=CDC.doctor_id  
			INNER JOIN patchcodes AS PT ON PT.patch_id=CD.patch_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=ACV.user_id
			LEFT JOIN city CT ON CT.city_id=PT.city_id
			LEFT JOIN headquater HQ ON HQ.headquater_id=PT.headquater_id  WHERE ACV.user_id='".$user_id."'";
	$execute = mysql_query($sql);
	$newdata = array();
	while($data = mysql_fetch_assoc($execute)){
	  $records['Stockist'] = $data['chemist_name'];
	  $records['Patch'] =  $data['patch_name'];
	  $records['Emp'] =  $data['first_name'].' '.$data['last_name'];
	  $records['City'] =  $data['city_name'];
	  $records['Headquater'] =  $data['headquater_name'];
	  $records['Date'] =  date('d-m-Y H:i',strtotime($data['date_added']));
	  $newdata[] = $records;
	}
   break;	
 }	
	if(!empty($newdata)){
	   echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
	}else{
	    echo json_encode(array('success'=>'NO','message'=>'These is no Report found!')); exit;
	}
   
?>