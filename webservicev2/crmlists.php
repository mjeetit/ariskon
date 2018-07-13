<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class CRMList extends commonclass{
    public function getResponse($request){
	     $headquaterlist = $this->getCondHeadquaterList($request['user_id']); 
		 
		 $execute = $this->query("SELECT AT.*,DT.doctor_name,ED.first_name,ED.last_name,PT.patch_name,CT.city_name,ED.designation_id
			FROM crm_appointments AT 
			INNER JOIN crm_doctors DT ON DT.doctor_id=AT.doctor_id
			LEFT JOIN employee_personaldetail ED ON ED.user_id=AT.be_id
			INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id
			LEFT JOIN city CT ON CT.city_id=PT.city_id WHERE AT.isDelete='0' AND DT.headquater_id IN(".implode(',',$headquaterlist).") AND AT.created_date > DATE_SUB(NOW(), INTERVAL 24 MONTH) ORDER BY DATE(AT.created_date) DESC");
		$crmlist = $this->fetchAll($execute);
		$newdata = array();
		foreach($crmlist as $data){
			$records['CRM'] = $data['appointment_code'];
			$records['Amount'] = $data['expense_cost'];
			$records['Date'] = date('d-m-Y',strtotime($data['created_date']));
			$records['Doctor'] = $data['doctor_name'];
			$records['Emp'] = $data['first_name'].' '.$data['last_name'];
			$records['Patch'] =  $data['patch_name'];
			$records['City'] =  $data['patch_name'].'('.$data['city_name'].')';
			$records['CRMID'] =  $data['appointment_id'];
			$approval = '';
			$appr_text = array('1'=>'Approved','2'=>'Reject');
			$approval .= 'ABM:-'.(($data['abm_approval']!='0')?$appr_text[$data['abm_approval']]:'Pending');
			$approval .= ', RBM:-'.(($data['rbm_approval']!='0')?$appr_text[$data['rbm_approval']]:'Pending');
			
			$approval .= ', ZBM:-'.(($data['zbm_approval']!='0')?$appr_text[$data['zbm_approval']]:'Pending');
			$records['Approval'] =  $approval;
			$newdata[] = $records;
		}
		if(!empty($newdata)){
	      return array('success'=>'YES','message'=>$newdata);  
	   }else{
	     return array('success'=>'NO','message'=>'There is no record found!');
	   }
	}
}
$obj = new CRMList();
$response = $obj->getResponse($_REQUEST);
echo json_encode($response);exit;
?>