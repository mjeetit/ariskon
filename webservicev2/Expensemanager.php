<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class ExpenseManager extends commonclass{
    public function getResponse($request){
	     switch($request['mode']){
		    case 'ExpenseRequest':
			   $response = $this->AddExpense($request);
			break;
			case 'CheckExp':
			   $response = $this->CheckExp($request);
			break;
		 }
		return $response; 
	}
	
	public function AddExpense($request){
	     $exp_amount = isset($request['exp_amount'])?$request['exp_amount']:'';
		 $exp_fare = isset($request['exp_fare'])?$request['exp_fare']:'';
		 $exp_destination = isset($request['exp_destination'])?$request['exp_destination']:'';
		 $exp_detail = isset($request['exp_detail'])?$request['exp_detail']:'';
		 $exp_date = isset($request['exp_date'])?date('Y-m-d',strtotime($request['exp_date'])):'';
		 $exp_mixedamount = isset($request['exp_mixedamount'])?$request['exp_mixedamount']:'';
		 $expense_id = isset($request['expense_id'])?$request['expense_id']:'';
		 $user_id = isset($request['user_id'])?$request['user_id']:'';
		 $current_date=date('Y-m-d',time());
		 if(($expense_id==5 || $expense_id==4 || $expense_id==3) && $exp_destination==''){
		   return array('success'=>'NO','message'=>'Destination can not be blank!');
		 }else{
		    $lastexpensedate  = $this->query("SELECT * FROM emp_expenses WHERE user_id='".$request['user_id']."' ORDER BY expense_date DESC LIMIT 1");
		    $lastexpense = $this->fetchRow($lastexpensedate);
		    if(!empty($lastexpense) && $exp_date<$lastexpense['expense_date']){
			   return array('success'=>'NO','message'=>'Your last expense submitted on '.$lastexpense['expense_date'].', you can not submit expense older than this date!');
			}
			if($exp_date>$current_date){
			   return array('success'=>'NO','message'=>'You can not submit expense more than current date!');
			}
			if($this->checkSameExpenseTwise($exp_date,$user_id,$expense_id)){
			    return array('success'=>'NO','message'=>'You have already submited this expense head for '.$exp_date.'');
			}
			$this->query("INSERT INTO emp_expenses SET user_id='".$user_id."',expense_type=1,expense_amount='".$exp_amount."',travel_destination='".$exp_destination."',fare='".$exp_fare."',expense_detail='".$exp_detail."',expense_date='".$exp_date."',head_id='".$expense_id."',mixed_amount='".$exp_mixedamount."',date_added=NOW(),add_via='1'");
		 }
		return array('success'=>'YES','message'=>'Expense Added successfully!'); 
	}
	
	public function CheckExp($request){
	   $execute = $this->query("SELECT EE.*,EP.expense_type,EH.expense_type AS orig_etype,EH.head_name FROM emp_expense_amount EE 
	   		   INNER JOIN employee_personaldetail EP ON EE.user_id=EP.user_id 
			   INNER JOIN expense_head EH ON EH.head_id=EE.head_id WHERE EE.user_id='".$request['user_id']."'");
		   
		$expenses = $this->fetchAll($execute);
		$newdata = array();
		foreach($expenses as $result){
			$data = array();
			$data['Head'] 	  = $result['head_id'];
			$data['Name'] = $result['head_name'];
			$data['Amount'] = $result['expense_amount'];
			$mixed = "0";
			if(($result['orig_etype']==$result['expense_type'] && $result['expense_type']==3) || $result['head_id']==15){
				$mixed = "1";
			}
			$fare = "0";
			if($result['head_id']==3 || $result['head_id']==4 || $result['head_id']==5){
				$fare = "1";
			}
			$data['Mxd'] = $mixed;
			$data['Fare'] = $fare;
			$expensedata[]= $data;
		}
		if(!empty($expensedata)){
		   return array('success'=>'YES','message'=>$expensedata);
		}else{
		  return array('success'=>'NO','message'=>'No record found!');
		}
	}
	public function checkSameExpenseTwise($expense_date,$user_id,$expense_head){
	    $expensecheckQuery = $this->query("SELECT COUNT(1) AS CNT FROM emp_expenses WHERE user_id='".$user_id."' AND expense_date='".$expense_date."' AND head_id='".$expense_head."'");
		$expensecheck = $this->fetchRow($expensecheckQuery);
		if($expensecheck['CNT']>0){
		    return true;
		}else{
		   return false;
		}
	}
}
$obj = new ExpenseManager();
$response = $obj->getResponse($_REQUEST);
echo json_encode($response);exit;	
?>