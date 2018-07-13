<?php
header('Content-type: application/json');
$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
  $where = "1";
    $sql = "SELECT AT.*,DT.doctor_name,ED.first_name,ED.last_name,PT.patch_name,CT.city_name
			FROM crm_appointments AT INNER JOIN crm_doctors DT ON DT.doctor_id=AT.doctor_id
			INNER JOIN employee_personaldetail ED ON ED.user_id=AT.be_id
			INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id
			LEFT JOIN city CT ON CT.city_id=PT.city_id WHERE ".$where;
	$execute = mysql_query($sql);
	$newdata = array();
	while($data = mysql_fetch_assoc($execute)){
	  $records['CRM'] = $data['appointment_code'];
	  $records['Amount'] = $data['expense_cost'];
	  $records['Date'] = date('d-m-Y',strtotime($data['created_date']));
	  $records['Doctor'] = $data['doctor_name'];
	  $records['Emp'] = $data['first_name'].' '.$data['last_name'];
	  $records['Patch'] =  $data['patch_name'];
	  $records['City'] =  $data['city_name'];
	  $newdata[] = $records;
	}
	if(!empty($newdata)){
	   echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
	}else{
	    echo json_encode(array('success'=>'NO','message'=>'These is no CRM found!')); exit;
	}
   
?>