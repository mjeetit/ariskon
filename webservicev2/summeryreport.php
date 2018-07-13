<?php
header('Content-type: application/json');
$mode 			= isset($_REQUEST['mode'])?$_REQUEST['mode']:'Test';
$user_id 		= isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
include 'config.php';
include 'common_class.php';
class SummaryReport  extends commonclass{
   public function getEmplist($request){
         $designation = $this->getDesignation($request['user_id']);
	     $childusers = $this->getChilduser(array($request['user_id']),1);//print_r($childusers);die;
		 $execute = $this->query("SELECT ED.user_id,ED.first_name,ED.last_name,DT.designation_code FROM employee_personaldetail ED LEFT JOIN designation DT ON DT.designation_id=ED.designation_id WHERE ED.user_id IN('".implode("','",$childusers)."') AND ED.designation_id>".$designation." GROUP BY ED.user_id ORDER BY ED.first_name");
	      $emplists = $this->fetchAll($execute);
		  $list = array();
		  foreach($emplists as $emplist){
		     $data['User'] =   $emplist['user_id'];
			 $data['Name'] =   $emplist['designation_code'].'-'.$emplist['first_name'].' '.$emplist['last_name'];
			 $list[] = $data;
		  }
		  return $list;
   }
   public function getPatchList($request){
         $where = '';
		 $designation = $this->getDesignation($request['user_id']);
	     
		 if($request['empid']>0 && $designation==8){
		  $where = " AND EL.user_id='".$request['empid']."'";
		 }else{
		   $childusers = $this->getChilduser(array($request['empid']),1);
		   $where = " AND EL.user_id IN('".implode("','",$childusers)."')";
		 }
		 if($request['headquaterid']>0){
		    $where .= " AND EL.headquater_id='".$request['headquaterid']."'";
		 }
		 $execute = $this->query("SELECT PT.patch_id,PT.patchcode,PT.patch_name,PT.city_id,PT.headquater_id FROM patchcodes AS PT INNER JOIN emp_locations EL ON EL.headquater_id=PT.headquater_id  WHERE 1".$where." GROUP BY PT.patch_id");
	      $patchcodes = $this->fetchAll($execute);
		  $list = array();
		  foreach($patchcodes as $patches){
				$data['Patch'] 	  = $patches['patch_id'];
				$data['Name']   = $patches['patch_name'];
				$data['Code'] 	  = $patches['patchcode'];
			 	$list[] = $data;
		  }
		  return $list;
   }
   public function getHeadquaters($request){
         $designation = $this->getDesignation($request['user_id']);
	     $childusers = $this->getChilduser(array($request['user_id']),1);
		 
		  $execute = $this->query("SELECT * FROM headquater AS HT INNER JOIN emp_locations EL ON EL.headquater_id=HT.headquater_id  WHERE EL.user_id IN('".implode("','",$childusers)."')  GROUP BY HT.headquater_id");
	      $headquaers = $this->fetchAll($execute);
		  $list = array();
		  foreach($headquaers as $headquater){
				$data['Headquater'] = $headquater['headquater_id'];
				$data['Name']     	= $headquater['headquater_name'];
			 	$list[] = $data;
		  }
		  return $list;
   }
   public function getReportList($request){
      $designation = $this->getDesignation($request['user_id']);
	  $childusers = $this->getChilduser(array($request['user_id']),1);
	 $filter = '';
	  if($request['empid']>0){
		  $filter = " AND ED.user_id='".$request['empid']."'";
	  }
	  $execute = $this->query("SELECT ED.user_id,ED.first_name,ED.last_name,DT.designation_code FROM employee_personaldetail ED LEFT JOIN designation DT ON DT.designation_id=ED.designation_id WHERE ED.user_id IN('".implode("','",$childusers)."') AND ED.designation_id>".$designation."".$filter." GROUP BY ED.user_id ORDER BY ED.first_name");
		$emplists = $this->fetchAll($execute);
		$list = array();
		foreach($emplists as $emplist){
			  $data['Name'] =   'Name: '.$emplist['designation_code'].'-'.$emplist['first_name'].' '.$emplist['last_name'];
			  $data['Date'] =  'Call Month: '.date('Y-m-d');
			  $calldata = $this->getCalldetails($emplist['user_id'],$request);
			  $data['Doctor']     	= 'Doctor Call: '.$calldata['Doctor'].' AVG: '.$calldata['DAV'];
			  $data['Chemist']     	= 'Chemist Call: '.$calldata['Chemist'].' AVG: '.$calldata['CAV'];
			  $data['Stockist']     = 'Stockist Call: '.$calldata['Stockist'].' AVG: '.$calldata['SAV']; 
			 $list[] = $data;
		}
		return $list;
   }
   public function getCalldetails($user_id,$request){
	   $filter = '';
	   $filter1 = '';
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
		  $filter = " AND DATE(ADV.call_date)>='".$start_date."' AND DATE(ADV.call_date)<='".$end_date."'"; 
		}
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
		  $filter1 = " AND DATE(ASV.date_added)>='".$request['start_date']."' AND DATE(ASV.date_added)<='".$request['end_date']."'"; 
		}
      $execute = $this->query("SELECT * FROM app_doctor_visit AS ADV WHERE (ADV.user_id='".$user_id."' OR ADV.abm_visit='".$user_id."' OR ADV.rbm_visit='".$user_id."' OR ADV.zbm_visit='".$user_id."') AND MONTH(ADV.call_date)='".date('m')."'".$filter."");
	  $doctors = $this->fetchAll($execute);
	  foreach($doctors as $doctor){
	     $datecount[$doctor['call_date']] = 1;
	  }
	  
	  $execute = $this->query("SELECT * FROM app_chemist_visit AS ADV WHERE (ADV.user_id='".$user_id."' OR ADV.abm_visit='".$user_id."' OR ADV.rbm_visit='".$user_id."' OR ADV.zbm_visit='".$user_id."') AND MONTH(ADV.call_date)='".date('m')."'".$filter."");
	  $chemist = $this->fetchAll($execute);
	  foreach($chemist as $chm){
	     $chemcount[$chm['call_date']] = 1;
	  }
	  $execute = $this->query("SELECT * FROM app_stockist_visit AS ADV WHERE (ADV.user_id='".$user_id."' OR ADV.abm_visit='".$user_id."' OR ADV.rbm_visit='".$user_id."' OR ADV.zbm_visit='".$user_id."')  AND MONTH(ADV.date_added)='".date('m')."'".$filter1."");
	  $stockist = $this->fetchAll($execute);
	  foreach($stockist as $stc){
	     $stccount[date('d-m-Y',strtotime($stc['date_added']))] = 1;
	  }
	  return array('Doctor'=>count($doctors),'Chemist'=>count($chemist),'Stockist'=>count($stockist),'DAV'=>number_format(count($doctors)/count($datecount),1),'CAV'=>number_format(count($chemist)/count($chemcount),1),'SAV'=>number_format(count($stockist)/count($stccount),1));
   }

}
$obj = new SummaryReport();
$retundata = array();
switch($mode){
   case 'EMP':
       $retundata = $obj->getEmplist($_REQUEST);
   break;
   case 'Patch':
       $retundata = $obj->getPatchList($_REQUEST);
   break;
   case 'Visitto':
       $retundata = $obj->getDoctorChemistStockist($_REQUEST);
   break;
   case 'Headquater':
       $retundata = $obj->getHeadquaters($_REQUEST);
   break;
   case 'Report':
   	   $retundata = $obj->getReportList($_REQUEST);
   break;
}
if(!empty($retundata)){
  echo json_encode(array('success'=>'YES','message'=>$retundata)); exit;
}else{
  echo json_encode(array('success'=>'NO','message'=>'No Record Found!')); exit;
}
?>