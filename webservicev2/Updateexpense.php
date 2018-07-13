<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class UpdateExpense extends commonclass{
   public function getResponse($request){
	     switch($request['Mode']){
		    case 'Update':
			   $response = $this->UpdateExpense($request);
			break;
			case 'Emp':
			   $response = $this->Emplist($request);
			break;
		 }
		return $response; 
	}
	public function UpdateExpense($request){
	   $dataArr = json_decode($request['expense']);
	   $finalstatus = '';
	   $auth = $this->finalApproval($dataArr,$request['user_id']);
	   if($auth){
	     $finalstatus = ",approve_status='1'";
	   }
	   foreach($dataArr as $expensedata){
	     $this->query("UPDATE emp_expenses SET approve_amount='".$expensedata->amount."',remarks='".$expensedata->remark."',approve_by=concat(`approve_by`,',',".$request['user_id'].")".$finalstatus." WHERE expense_id='".$expensedata->expense_id."'");
		 $this->approvelog($expensedata,$request['user_id']);
	   }
	}
	public function finalApproval($request,$user_id){
	  $execute = $this->query("SELECT * FROM expense_approval EA INNER JOIN emp_expenses ER ON ER.user_id=EA.user_id WHERE ER.expense_id='".$request[0]->expense_id."' ORDER BY position DESC LIMIT 1");
		$expenses = $this->fetchRow($execute);
	  if($result['approval_user_id']==$user_id){
		 return true;
	  }else{
		 return false;
	  }
	}
	
	public function approvelog($expensedata,$user_id){
   			$this->query("INSERT INTO emp_expenses_log  SET expense_id='".$expensedata->expense_id."',approve_amount='".$expensedata->amount."',approve_by='".$user_id."' ,approve_date= NOW()");
	}
	
	public function Emplist($request){
	   $chiledUsers = $this->getChilduser(array($request['user_id']),1);
	  $execute = $this->query("SELECT * FROM employee_personaldetail where user_id IN(".implode(',',$chiledUsers).") AND user_id!='".$request['user_id']."'");		   
		$expenses = $this->fetchAll($execute);
		$returnlist = array();
		foreach($expenses as $data){
		     $dataArr = array();
			 $dataArr['User'] = $data['user_id'];
			 $dataArr['Name'] = $data['first_name']." ".$data['last_name'];
			 $returnlist[] = $dataArr;
		}
		
		if(!empty($returnlist)){
		  return array('success'=>'YES','message'=>$returnlist);
		}else{
		  return array('success'=>'NO','message'=>'No record found!!');
		}
	}
}
$obj = new UpdateExpense();
$response = $obj->getResponse($_REQUEST);
echo json_encode($response);exit;
?>