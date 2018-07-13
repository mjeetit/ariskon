<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class LeaveList extends commonclass{
   public function getLeaveList($request){
       $this->query("INSERT INTO app_test SET request_date='".json_encode($_REQUEST)."'");
	   switch($request['type']){
	      case 'Pending':
		    $response = $this->getPendinglist($request);
		  break;
		  case 'Detail':
		    $response = $this->getDetaillist($request);
		  break;
		  default:
		    $response = $this->getMyLeave($request);//array('success'=>'NO','message'=>'Invalid Action!');
	   }
	   echo json_encode($response);die;
   }
   
   public function getPendinglist($request){
      $chiledUsers = $this->getChilduser(array($request['user_id']),1);
	   if(!empty($chiledUsers)){
	    $where = "(UD.parent_id='".$request['user_id']."' OR UD.user_id IN(".implode(',',$chiledUsers).")) AND UD.user_id!='".$request['user_id']."'";
		}else{
		   $where = "UD.parent_id='".$request['user_id']."'";
		}
			$exceute = $this->query("SELECT LD.*,UD.first_name,UD.last_name FROM leaverequests LD INNER JOIN employee_personaldetail UD ON UD.user_id=LD.user_id WHERE ".$where." GROUP BY request_id ORDER BY request_id");
			$leaveList = $this->fetchAll($exceute);
			$returnLeave = array();
			foreach($leaveList as $data){
			   $dataArr = array();
			   if(strpos($data['approved_approval'],",".$request['user_id'])===false){
					 $dataArr['Leave'] = $data['request_id'];
					 $dataArr['From'] = $data['leave_from'];
					 $dataArr['To']   = $data['leave_to'];
					 $dataArr['Status'] = ($data['final_approval']=='0')?$this->getApprovedUserss($data['approved_approval']):'Approved';
					 $dataArr['Name'] = $data['first_name'].' '.$data['last_name'];
					 $dataArr['Type'] = $this->leaveType($data['request_id']);
					 $dataArr['Type'] = ($dataArr['Type']!='')?$dataArr['Type']:'Not Available';
					 $returnLeave[] = $dataArr;
				 }
			}
		 if(!empty($returnLeave)){
		   return array('success'=>'YES','message'=>$returnLeave);
		 }else{
		   return array('success'=>'NO','message'=>'No record found!');
		 }	
   }
   
   public function getMyLeave($request){
      		$where = "UD.user_id='".$request['user_id']."'";
		
			$exceute = $this->query("SELECT LD.*,UD.first_name,UD.last_name FROM leaverequests LD INNER JOIN employee_personaldetail UD ON UD.user_id=LD.user_id WHERE ".$where." GROUP BY request_id ORDER BY request_id");
			$leaveList = $this->fetchAll($exceute);
			$returnLeave = array();
			foreach($leaveList as $data){
			   $dataArr = array();
			   //if(strpos($data['approved_approval'],",".$request['user_id'])===false){
					 $dataArr['Leave'] = $data['request_id'];
					 $dataArr['From'] = $data['leave_from'];
					 $dataArr['To']   = $data['leave_to'];
					 $dataArr['Status'] = ($data['final_approval']=='0')?$this->getApprovedUserss($data['approved_approval']):'Approved';
					 $dataArr['Name'] = $data['first_name'].' '.$data['last_name'];
					 $dataArr['Type'] = $this->leaveType($data['request_id']);
					 $returnLeave[] = $dataArr;
				// }
			}
		 if(!empty($returnLeave)){
		   return array('success'=>'YES','message'=>$returnLeave);
		 }else{
		   return array('success'=>'NO','message'=>'No record found!');
		 }	
   }
   
   public function getDetaillist($request){
     $exceute = $this->query("SELECT * FROM leaverequests WHERE request_id='".$request['leave_id']."'");//echo "SELECT * FROM leaverequests WHERE request_id='".$request['leave_id']."'";die;
	 $data = $this->fetchRow($exceute);
     $dataArr = array();
	 $returnLeave = array();
	 if(!empty($data)){
		 $dataArr['From'] = $data['leave_from'];
		 $dataArr['To']   = $data['leave_to'];
		 $dataArr['Type'] =  $this->leaveType($data['request_id']);
		 $dataArr['Msg'] = strip_tags($data['contents']);
		 //$returnLeave[] = $dataArr;
	 		return array('success'=>'YES','message'=>'Record Found')+$dataArr;
		 }else{
		   return array('success'=>'NO','message'=>'No record found!');
		 }	
   }
   
   public function leaveType($request_id){
   	  $exceute = $this->query("SELECT LRT.*,LT.typeName FROM leaverequesttype LRT INNER JOIN leavetypes LT ON LRT.leave_typeID=LT.typeID WHERE LRT.request_id='".$request_id."'");
	 $leavs = $this->fetchAll($exceute);
	 $returnstr = '';
	 foreach($leavs as $data){ 
	 	$returnstr .= $data['typeName']."[".$data['no_of_days']."]";
	 }
   return $returnstr;
 } 
  public function getApprovedUserss($approvedAppro){
     $exceute = $this->query("SELECT DT.designation_code FROM employee_personaldetail ED INNER JOIN designation DT ON DT.designation_id=ED.designation_id WHERE user_id IN(".substr($approvedAppro,1).") GROUP BY DT.designation_code");
	 $leavs = $this->fetchAll($exceute);
	 $returnstr = '';
	 foreach($leavs as $data){
	 	$returnstr .= $data['typeName']."[".$data['no_of_days']."]";
	 }
   return ($returnstr)?$returnstr:'Pending';
 }

}
$obj = new LeaveList;
$obj->getLeaveList($_REQUEST);
?>