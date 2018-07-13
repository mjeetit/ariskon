<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class RoiList extends commonclass{
    public function getResponse($request){
	     switch($request['Mode']){
		    case 'RequestList':
			   $response = $this->ROIRequestedList($request);
			break;
			case 'RequestDetail':
			   $response = $this->ROIdetails($request);
			break;
			case 'ROIAMount':
			  $response = $this->ROIAMount($request);
			break;
		 }
		return $response; 
	}
	
	public function ROIRequestedList($request){
	
	    $headquaterlist = $this->getCondHeadquaterList($request['user_id']);
		if(in_array(1,$headquaterlist)){
		  //$headquaterlist = array(1,3);
		}
		
		$execute = $this->query("SELECT CA.*,CD.doctor_name,EP.first_name,EP.last_name,CA.doctor_id,HQ.headquater_name 
						FROM crm_appointments AS CA
						INNER JOIN crm_doctors CD ON CD.doctor_id= CA.doctor_id
						INNER JOIN headquater HQ ON HQ.headquater_id= CD.headquater_id
						LEFT JOIN employee_personaldetail EP ON (CA.requested_by=EP.user_id)
						WHERE CA.isActive='1' AND CA.isDelete='0' AND CD.headquater_id IN(".implode(',',$headquaterlist).") AND CA.created_date > DATE_SUB(NOW(), INTERVAL 24 MONTH) GROUP BY CA.appointment_code");
		   
		$roilists = $this->fetchAll($execute);
		$newdata = array();
	  	
		foreach($roilists as $data){
		  $roimonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y'))); 
		  $execute = $this->query("SELECT COUNT(1) AS CNT FROM crm_roi WHERE doctor_id='".$data['doctor_id']."' AND date_format(roi_month,'%Y-%m') = '".date('Y-m',strtotime($roimonth))."'");
		  $getROICont = $this->fetchRow($execute);
		  if($getROICont['CNT']<=0){
			  $records['CRM']  = $data['appointment_code'];
			  $records['HQ'] = $data['headquater_name'];
			  $records['Month'] =  $data['created_date'];
			  $records['Expense'] = $data['expense_cost'];
			  $records['Doctor'] = $data['doctor_name'];
			  $records['CRMID']  = $data['appointment_id'];
			  $records['roiAmount']  = ($data['roiAmount']>0)?$data['roiAmount']:0;
			  $records['roiApproval']  = ($data['roiApproval']>0)?$data['roiApproval']:0;
			  $records['roi_month']  = ($data['roi_month']!='')?$data['roi_month']:date('F Y',strtotime($roimonth));
			  $records['Emp']  = $data['first_name'].' '.$data['last_name'];
			  $records['roi_status']  = 'Not Submitted';
			  
			  $newdata[] = $records;
		  }
		}
	   if(!empty($newdata)){
	      return array('success'=>'YES','message'=>$newdata);  
	   }else{
	     return array('success'=>'NO','message'=>'There is no record found!');
	   }					
	}
	
	public function ROIdetails($request){	   
		$execute = $this->query("SELECT CAP.*,CP.product_name,CP.mrp_incl_vat FROM crm_appointment_potential_months CAM
				INNER JOIN crm_appointment_potential_month_products CAP ON CAP.potential_month_id = CAM.potential_month_id
				INNER JOIN crm_products CP ON CP.product_id = CAP.product_id WHERE CAM.appointment_id IN(".$request['apointment_id'].") GROUP BY CP.product_id");
		   
		$roilists = $this->fetchAll($execute);
		$newdata = array();
	  	
		foreach($roilists as $data){
			$records['Product']  = $data['product_id'];
			$records['ProductName'] = $data['product_name'];
			$records['ProductPrice'] =  $data['mrp_incl_vat'];
			$newdata[] = $records;
		}
	   if(!empty($newdata)){
	      return array('success'=>'YES','message'=>$newdata);  
	   }else{
	     return array('success'=>'NO','message'=>'There is no record found!');
	   }					
	
	}
	
	public function ROIAMount($request){
	    $headquaterlist = $this->getCondHeadquaterList($request['user_id']);
		$execute = $this->query("SELECT CA.*,CD.doctor_name,EP.first_name,EP.last_name,CA.doctor_id,SUM(ROI.roi_total_amount) AS roiAmount,ROI.roi_month,HQ.headquater_name
						FROM crm_appointments AS CA
						INNER JOIN crm_roi ROI ON ROI.doctor_id=CA.doctor_id 
						INNER JOIN crm_doctors CD ON CD.doctor_id= CA.doctor_id
						INNER JOIN headquater HQ ON HQ.headquater_id= CD.headquater_id
						LEFT JOIN employee_personaldetail EP ON CA.requested_by=EP.user_id
						WHERE CA.isActive='1' AND CA.isDelete='0' AND CD.headquater_id IN(".implode(',',$headquaterlist).") AND CA.created_date > DATE_SUB(NOW(), INTERVAL 12 MONTH) AND roi_total_amount>0 AND roi_month>'2016-01-01' GROUP BY CA.doctor_id");
		   
		$roilists = $this->fetchAll($execute);
		$newdata = array();
	  	
		foreach($roilists as $data){
				$records['CRM']  = $data['appointment_code'];
				$records['HQ'] = $data['headquater_name'];
				$records['Month'] =  $data['created_date'];//$data['apointment_code'];
				$records['Expense'] = $data['expense_cost'];
				$records['Doctor'] = $data['doctor_name'];
				$records['CRMID']  = $data['appointment_id'];
				$records['roiAmount']  = ($data['roiAmount']>0)?$data['roiAmount']:0;
				$records['roiApproval']  = ($data['roiApproval']>0)?$data['roiApproval']:0;
				$records['roi_month']  = ($data['roi_month']!='')?date('F Y',strtotime($data['roi_month'])):'0000-00-00';
				$records['Emp']  = $data['first_name'].' '.$data['last_name'];
				$newdata[] = $records;
		  
		}
	   if(!empty($newdata)){
	      return array('success'=>'YES','message'=>$newdata);  
	   }else{
	     return array('success'=>'NO','message'=>'There is no record found!');
	   }					
	}
}

$obj = new RoiList();
$response = $obj->getResponse($_REQUEST);
echo json_encode($response);exit;
?>