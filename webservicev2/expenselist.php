<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class ExpenseList extends commonclass{
   public function getResponse($request){
	     switch($request['Mode']){
		    case 'Pending':
			   $response = $this->getPendingList($request);
			break;
			case 'List':
			   $response = $this->getList($request);
			break;
			default:
			   $response = array('success'=>'NO','message'=>'Invalid action!');
			break;
		 }
		return $response; 
	}
	public function getPendingList($request){
	   $execute = $this->query("SELECT EE.expense_id,EH.head_name,EE.expense_amount,EE.fare,EE.mixed_amount,EE.expense_date,EE.remarks,EE.approve_amount,EE.approve_by FROM emp_expenses EE INNER JOIN expense_head EH ON EE.head_id=EH.head_id  WHERE user_id='".$request['empid']."' ORDER BY expense_date ASC");
		$expenses = $this->fetchAll($execute);
		$returnexpense = array();
		foreach($expenses as $data){
		  if(strpos($data['approve_by'],",".$request['user_id'])===false){
				$dataArr = array();
				$dataArr['Expense'] = $data['expense_id'];
				$dataArr['ExpenseName'] = $data['head_name'];
				$dataArr['Amount']=$data['expense_amount']+$data['fare']+$data['mixed_amount']; 
				$dataArr['Approved'] = $data['approve_amount'];
				$dataArr['Date'] = $data['expense_date'];
				$dataArr['Remark'] = $data['remarks'];
				$returnexpense[] = $dataArr;
			}
		}
		if(!empty($returnexpense)){
		 	return array('success'=>'YES','message'=>$returnexpense); 
	  	}else{
			return array('success'=>'NO','message'=>'No Record Found!');
	  	}
	}
	
	public function getList($request){
	   $execute = $this->query("SELECT EH.head_name,EE.expense_amount,EE.fare,EE.mixed_amount,EE.expense_date,EE.approve_by FROM emp_expenses EE INNER JOIN expense_head EH ON EE.head_id=EH.head_id  WHERE user_id='".$request['user_id']."' AND DATE_FORMAT(expense_date,'%Y-%m')='".date('Y-m')."' ORDER BY expense_id");
		$expenses = $this->fetchAll($execute);
		$returnexpense = array();
		foreach($expenses as $data){
			$dataArr['Expense'] = $data['head_name'];
			$dataArr['Amount']   = $data['expense_amount'] + $data['fare'] + $data['mixed_amount'];
			$dataArr['Date'] = $data['expense_date'];
			$dataArr['Status'] = ($data['approve_status']==0)?$this->getApprovedUserss($data['approve_by']):'Approved';
			$returnexpense[] = $dataArr;
		}
		if(!empty($returnexpense)){
		 	echo json_encode(array('success'=>'YES','message'=>$returnexpense));exit; 
	  	}else{
			echo json_encode(array('success'=>'NO','message'=>'No Record Found!'));exit; 
	  	}
		
	}
	
	public function getApprovedUserss($approvedAppro){
	   $execute = $this->query("SELECT DT.designation_code FROM employee_personaldetail ED INNER JOIN designation DT ON DT.designation_id=ED.designation_id WHERE user_id IN(".substr($approvedAppro,1).") GROUP BY DT.designation_code");
	  $expenses = $this->fetchAll($execute);
	  foreach($expenses as $data){
	    $returnstr .= $date['designation_code']."[Approved],";
	  }
   return ($returnstr)?$returnstr:'Pending';
 }
}
$obj = new ExpenseList();
$response = $obj->getResponse($_REQUEST);
echo json_encode($response);exit;
?>