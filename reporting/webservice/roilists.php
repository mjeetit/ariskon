<?php
header('Content-type: application/json');
$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
$apointment_id = isset($_REQUEST['apointment_id'])?$_REQUEST['apointment_id']:0;
$Mode = isset($_REQUEST['Mode'])?$_REQUEST['Mode']:'RequestList';
include 'config.php';
   switch($Mode){
     case 'RequestList':
	    $where = '';
		$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));
		$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));
	    if($designation==8){
		   $where = " AND EP.user_id='".$user_id."'";
		}
		if($designation==7){
		   $where = " AND EP.parent_id='".$user_id."'";
		}
		if($designation==6){
		   $where = " AND EP.parent_id='".$user_id."'";
		}
		if($designation==5){
		   $where = " AND EP.parent_id='".$user_id."'";
		}
		/*$sql = "SELECT CA.appointment_code,CA.expense_cost,CD.doctor_name,CA.appointment_id,'getRoiAmount(CA.doctor_id)' as roiAmount,
				'getLastRoiApproval(CA.doctor_id)' as roiApproval,ROI.roi_month  FROM crm_appointments CA 
				INNER JOIN crm_doctors CD ON CD.doctor_id= CA.doctor_id
				INNER JOIN employee_personaldetail EP ON CA.be_id=EP.user_id
				LEFT JOIN crm_roi ROI ON ROI.doctor_id=CA.doctor_id AND ROI.roi_month = '".$previousMonth."' 
				WHERE 1".$where." AND DATE(CA.business_audit_date)>'".$financialYear."' AND CA.business_audit_status='1' AND CA.isActive='1' AND CA.isDelete='0' ORDER BY CA.created_date DESC";*/
		$sql = "SELECT CA.appointment_code,CA.expense_cost,CD.doctor_name,CA.appointment_id,getRoiAmount(CA.doctor_id) as roiAmount,
				getLastRoiApproval(CA.doctor_id) as roiApproval,ROI.roi_month  FROM crm_appointments CA 
				INNER JOIN crm_doctors CD ON CD.doctor_id= CA.doctor_id
				INNER JOIN employee_personaldetail EP ON CA.be_id=EP.user_id
				LEFT JOIN crm_roi ROI ON ROI.doctor_id=CA.doctor_id  
				WHERE 1".$where."   ORDER BY CA.created_date DESC";
		$execute = mysql_query($sql);
		$newdata = array();
		while($data = mysql_fetch_assoc($execute)){
		  $records['CRM']  = $data['appointment_code'];
		  $records['Amount'] = $data['expense_cost'];
		  $records['Month'] =  date('Y-m-d');//$data['apointment_code'];
		  $records['Doctor'] = $data['doctor_name'];
		  $records['CRMID']  = $data['appointment_id'];
		  $records['roiAmount']  = ($data['roiAmount']>0)?$data['roiAmount']:0;
		  $records['roiApproval']  = ($data['roiApproval']>0)?$data['roiApproval']:0;
		  $records['roi_month']  = ($data['roi_month']>0)?$data['roi_month']:0;
		  $newdata[] = $records;
		}
	  break;
	 case 'RequestDetail':
	   
		$sql = "SELECT CAP.*,CP.product_name,CP.mrp_incl_vat FROM crm_appointment_potentials CAP 
				INNER JOIN crm_products CP ON CP.product_id = CAP.product_id WHERE CAP.appointment_id='".$apointment_id."' GROUP BY CP.product_id";
		$execute = mysql_query($sql);
		$newdata = array();
		while($data = mysql_fetch_assoc($execute)){
		  $records['Product']  = $data['product_id'];
		  $records['ProductName'] = $data['product_name'];
		  $records['ProductPrice'] =  $data['mrp_incl_vat'];
		  $newdata[] = $records;
		}
	  break; 
  }	  	
	if(!empty($newdata)){
	   echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
	}else{
	    echo json_encode(array('success'=>'NO','message'=>'These is no CRM found for ROI!')); exit;
	}
   
?>