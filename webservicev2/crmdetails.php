<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class CRMdetails extends commonclass{
    public function getResponse($request){
	     switch($request['mode']){
		    case 'Detail':
			   $response = $this->crmdetail($request);
			break;
			case 'Product':
			   $response = $this->crmproductlists($request);
			break;
			case 'Update':
			  $response = $this->crmupdate($request);
			break;
		 }
		return $response; 
	}
	public function crmdetail($request){
		$newdata =array();
	  $execute = $this->query("SELECT AT.*,DT.doctor_name,ED.first_name,ED.last_name,PT.patch_name,CT.city_name,CN.type_name
			FROM crm_appointments AT INNER JOIN crm_doctors DT ON DT.doctor_id=AT.doctor_id
			LEFT JOIN employee_personaldetail ED ON ED.user_id=AT.be_id
			INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id
			LEFT JOIN city CT ON CT.city_id=PT.city_id
			LEFT JOIN crm_expense_types CN ON CN.expense_type=AT.expense_type WHERE appointment_id='".$request['crm_id']."'");
		   
		$data = $this->fetchRow($execute);
		$records['CRM'] = $data['appointment_code'];
		$records['Amount'] = $data['expense_cost'];
		$records['Date'] = date('d-m-Y',strtotime($data['created_date']));
		$records['Doctor'] = $data['doctor_name'];
		$records['Emp'] = $data['first_name'].' '.$data['last_name'];
		$records['Favour'] =  $data['favour'];
		$records['Payble'] =  $data['payble'];
		$records['Nature'] =  $data['type_name'];
		$approval = '';
		$appr_text = array('1'=>'Approved','2'=>'Reject');
		if($designation==7  || $designation<7){
		  $approval .= 'ABM:-'.(($data['abm_approval']!='0')?$appr_text[$data['abm_approval']]:'Pending')."\n".$data['abm_comment']."\n";
		}
		if($designation==6 || $designation<6){
		$approval .= 'RBM:-'.(($data['rbm_approval']!='0')?$appr_text[$data['rbm_approval']]:'Pending')."\n".$data['rbm_comment']."\n";
		}
		if($designation==5){
		$approval .= 'ZBM:-'.(($data['zbm_approval']!='0')?$appr_text[$data['zbm_approval']]:'Pending')."\n".$data['zbm_comment'];
		}
		$records['Approval'] =  $approval;
	    $newdata[] = $records;
		if(!empty($newdata)){
	      return array('success'=>'YES','message'=>'Details')+$records;  
	   }else{
	     return array('success'=>'NO','message'=>'There is no record found!');
	   }
	  
	}
	
	public function crmproductlists($request){
	  $execute = $this->query("SELECT CP.product_name,CPMP.product_id FROM crm_appointment_potential_month_products CPMP 
			INNER JOIN crm_appointment_potential_months CAP ON CAP.potential_month_id=CPMP.potential_month_id
			INNER JOIN crm_products CP ON CP.product_id=CPMP.product_id
			WHERE appointment_id='".$request['crm_id']."' GROUP BY CPMP.product_id ORDER BY CAP.month ASC");
		$products = $this->fetchAll($execute);
		$newdata = array();
		foreach($products as $data){ 
		   $$records =array();
			$records['Product'] = $data['product_name'];
			$execute = $this->query("SELECT * FROM crm_appointment_potential_month_products CPMP 
			INNER JOIN crm_appointment_potential_months CAP ON CAP.potential_month_id=CPMP.potential_month_id
			WHERE appointment_id='".$request['crm_id']."' AND CPMP.product_id='".$data['product_id']."'  ORDER BY CAP.month ASC");
 		    $mon_products = $this->fetchAll($execute);
			$i = 1;
			foreach($mon_products as $data1){
				   $records['Month'.$i] = date('F-Y',strtotime($data1['month']));
				   $records['Unit'.$i] = $data1['unit'];
				   $records['Value'.$i] = $data1['value']; 
				   $i++;
		   }
	  $newdata[] = $records;
  	}//echo "<pre>";print_r($newdata);die;
	if(!empty($newdata)){
	  return array('success'=>'YES','message'=>$newdata);  
	}else{
	 return array('success'=>'NO','message'=>'There is no record found!');
	}
		
  }
  
  public function crmupdate($request){
	$comment = isset($request['comment'])?$request['comment']:'';
	$status = isset($request['status'])?$request['status']:'';
	$userid = isset($request['user_id'])?$request['user_id']:'';
	$designation = isset($request['designation'])?$request['designation']:'';
	$crm_id = isset($request['crm_id'])?$request['crm_id']:'';
	$crmstatus = ($status=='Approved')?1:2;
   if($designation==7){
   	 $this->query("UPDATE crm_appointments SET abm_approval='".$crmstatus."',abm_comment='".$comment."',abm_comment_date=NOW(),abm_comment_by='".$userid."',abm_comment_ip='".$_SERVER['REMOTE_ADDR']."' WHERE appointment_id='".$crm_id."'");
   }
   if($designation==6){
   		$sql = $this->query("UPDATE crm_appointments SET rbm_approval='".$crmstatus."',rbm_comment='".$comment."',rbm_comment_date=NOW(),rbm_comment_by='".$userid."',rbm_comment_ip='".$_SERVER['REMOTE_ADDR']."' WHERE appointment_id='".$crm_id."'");
   }
   if($designation==5){
   		$sql = $this->query("UPDATE crm_appointments SET zbm_approval='".$crmstatus."',zbm_comment='".$comment."'zbm_comment_date=NOW(),zbm_comment_by='".$userid."',zbm_comment_ip='".$_SERVER['REMOTE_ADDR']."' WHERE appointment_id='".$crm_id."'");
   }
   echo json_encode(array('success'=>'YES','message'=>'CRM Approved Successfully')); exit;
  }
}
$obj = new CRMdetails();
$response = $obj->getResponse($_REQUEST);
echo json_encode($response);exit;
?>