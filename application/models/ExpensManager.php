<?php
class ExpensManager extends Zend_Custom
{
  public $_getData = array();
  
  public function getExpenseList(){
      $column = array('UD.first_name','sum(expense_amount) as Amount','sum(approve_amount) as Approve','sum(fare) AS Fare','sum(mixed_amount) AS Mixed','date_format(expense_date,"%M-%Y") as Month','EE.user_id');
      $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),$column)
				->joinleft(array('UD'=>'employee_personaldetail'),"UD.user_id=EE.user_id",array())
				->where("date_format(expense_date,'%Y-%m')<= '".date('Y-m')."' AND EE.user_id='".$_SESSION['AdminLoginID']."'")
				->group("EE.user_id")
				->group("date_format(expense_date,'%M-%Y')");
	   return $this->getAdapter()->fetchAll($select);
    }
	
  public function getCurrentExpenseList(){
     if(!empty($this->_getData['month'])){
	     $where = " AND date_format(expense_date,'%M-%Y')= '".$this->_getData['month']."'";
	 }else{
	     $where = " AND date_format(expense_date,'%M-%Y')= '".date('F-Y')."'";
	 }
	  $filterparam = ''; 
	  if(!empty($this->_getData['filter_head'])){
	    $filterparam  .= " AND EE.head_id='".$this->_getData['filter_head']."'";  
	  }
	  if(!empty($this->_getData['filter_date'])){
	    $filterparam  .= " AND EE.expense_date='".$this->_getData['filter_date']."'";  
	  }
          if(!empty($this->_getData['filter_date']) && !empty($this->_getData['filter_date1'])){
	    $filterparam  .= " AND EE.expense_date BETWEEN '".$this->_getData['filter_date']."' AND '".$this->_getData['filter_date1']."'";
	  }
      $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('*'))
				->joinleft(array('EH'=>'expense_head'),"EH.head_id=EE.head_id",array('head_name'))
				->where("user_id='".$_SESSION['AdminLoginID']."'".$where.$filterparam);
                                    //echo $select->__toString();die;
	   return $this->getAdapter()->fetchAll($select);
  }
  public function addExpense(){
     $check = $this->checkOnetimeExpense($this->_getData['expense_date'],$this->_getData['head_id']);
	 $this->_getData['date_added'] = date('Y-m-d h:i:s');
	 if($check){
     $this->insertInToTable('emp_expenses',array($this->_getData));
	 }else{
	   $_SESSION[ERROR_MSG] = 'This Is one time Expense. And Already added in Selected month';
	 }
	if(date('d')==30){
	    $this->sendMailExpenserequest(1);
	}
  }
   public function getCurrentExpenseForApproval(){
      $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('*'))
				->joinleft(array('EH'=>'expense_head'),"EE.head_id=EH.head_id",array('head_name'))
				->where("user_id='".$this->_getData['user_id']."' AND date_format(expense_date,'%M-%Y')= '".$this->_getData['month']."'");
	   return $this->getAdapter()->fetchAll($select);
  }
  public function ApproveExpense(){
        $auth = $this->getExpenseFinalApprovalAuth();//echo "<pre>";print_r($this->_getData);die;
      foreach($this->_getData['expense_id11'] as $key=>$expenseid){
	      //$approveby = $this->_getData['approve_by'][$key].','.$_SESSION['AdminLoginID'];
		  if(in_array($_SESSION['AdminLoginID'],explode(',',$this->_getData['approve_by'][$expenseid]))){
	        $approveby = $this->_getData['approve_by'][$expenseid];
		  }else{
		    $approveby = $this->_getData['approve_by'][$expenseid].','.$_SESSION['AdminLoginID']; 
		  }
	      if($auth || $_SESSION['AdminLoginID']==1){ //print_r($this->_getData['approve_amount'][$expenseid]);die;
		     $data = array('approve_amount'=>$this->_getData['approve_amount'][$expenseid],'remarks'=>$this->_getData['remarks'][$expenseid],'approve_by'=>$approveby,'approve_status'=>'1');
		  }else{
		    $data = array('approve_amount'=>$this->_getData['approve_amount'][$expenseid],'remarks'=>$this->_getData['remarks'][$expenseid],'approve_by'=>$approveby);
		  }
		  if(($this->_getData['emp_amount'][$expenseid] + $this->_getData['emp_fare'][$expenseid] + $this->_getData['emp_mixedamount'][$expenseid])>=$this->_getData['approve_amount'][$expenseid]){
                                 $this->_db->update("emp_expenses",$data,"expense_id='".$expenseid."'");
								 $this->setExpenseLog($data,$expenseid);
		  } 
	  }
  }
  public function userExpenseHead(){
     if(!empty($this->_getData['month'])){
	     $where = " AND date_format(expense_date,'%M-%Y')= '".$this->_getData['month']."'";
	   }else{
	     $where = " AND date_format(expense_date,'%M-%Y')= '".date('F-Y')."'";
	   }
      $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('head_id'))
				->joinleft(array('EH'=>'expense_head'),"EH.head_id=EE.head_id",array('head_name'))
				->where("user_id='".$_SESSION['AdminLoginID']."' AND EH.no_of_times=2".$where);
				//echo $select->__toString();die;
	   $headid = $this->getAdapter()->fetchAll($select);
           $headonetime = '';
	   if(!empty($headid)){
	       foreach($headid as $oneshead){
		        $head[] = $oneshead['head_id'];
		   }
		  $headonetime = " AND EH.head_id NOT IN(".implode(',',$head).")"; 
	   }
           if(date('d')>=25){
              $no_of_times = " AND EH.no_of_times IN(1,2)";
           }else{
             $no_of_times = " AND EH.no_of_times IN(1)";
           }
	   
      $select = $this->_db->select()
	  			->from(array('EEA'=>'emp_expense_amount'),array('*'))
				->joinleft(array('EH'=>'expense_head'),"EEA.head_id=EH.head_id",array('head_name'))
				->where("user_id='".$_SESSION['AdminLoginID']."'".$no_of_times.$headonetime."");
				//echo $select->__toString();die;
	   return $this->getAdapter()->fetchAll($select);
  }
  public function ExpenseRequestList(){
     $where = '1';
	 $filterparam = '';
	 if(!empty($this->_getData['user_id'])){
	   $filterparam  .= " AND EE.user_id='".$this->_getData['user_id']."'";
	 }
	 if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
	   $filterparam  .= " AND date_format(EE.expense_date,'%M-%Y') BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
	 }
	 if($_SESSION['AdminLoginID']!=44 && $_SESSION['AdminLoginID']!=293 && $_SESSION['AdminLoginID']!=1){
	     $where .=" AND approval_user_id='".$_SESSION['AdminLoginID']."'";
	 }
     $select = $this->_db->select()
	  			->from(array('EA'=>'expense_approval'),array('DISTINCT(user_id)'))
				->where($where);
	 $approvaluserlist = $this->getAdapter()->fetchAll($select);//print_r( $approvaluserlist);die;
	   foreach($approvaluserlist as $users){
	     $chiledauthority = $this->getPreviusAuthority($users['user_id']);
              
	    $childauth = '';
             if($chiledauthority && $chiledauthority!=',0' && $_SESSION['AdminLoginID']!=1){
			$childauth =" AND EE.approve_by LIKE '%".$chiledauthority."'";
		 }
	     $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('SUM(expense_amount) AS EXPAMT','SUM(approve_amount)  AS APPAMT','SUM(fare)  AS FARE','sum(mixed_amount) AS Mixed','approve_by','approve_status','expense_id','date_format(expense_date,"%M-%Y") AS EXPMONTH'))
				->joinleft(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",array('first_name','last_name','employee_code','user_id'))
				->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name','designation_code'))
				->joinleft(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array('(SELECT HQ.headquater_name FROM headquater HQ WHERE HQ.headquater_id=EL.headquater_id) AS headquater_name'))
				->where("ED.user_id='".$users['user_id']."' AND ED.delete_status='0'  AND expense_date>DATE_SUB(CURDATE(), INTERVAL 10 MONTH) AND expense_date>'2015-01-31'".$childauth."".$filterparam)
				->group("ED.user_id")
				->group("date_format(expense_date,'%Y-%m')")
				->order("ED.employee_code ASC")
				->order("date_format(expense_date,'%Y-%m') ASC"); 
             //if($_SESSION['AdminLoginID']==1){
               // echo $select->__toString();die;
            // }
	     $results =  $this->getAdapter()->fetchAll($select);
		 if(!empty($results)){
		   foreach($results as $result){
		     $expenselist[] = $result; 
			}
		 } 
	   } //print_r($expenselist);die;
	   return  $expenselist;
  }
  public function ExpenseApprovedByUsers($user_id){
    $list = '';
    if(!empty($user_id)){
	  $explodeusers = explode(',',$user_id);
	  foreach($explodeusers as $user){
	       $select = $this->_db->select()
	  			->from(array('ED'=>'employee_personaldetail'),array(''))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_code'))
				->where("ED.user_id='".$user."'");
	     $result =  $this->getAdapter()->fetchRow($select);
		 
		 if(!empty($result) || $user==1){
		    if($user==1){
		    	$list .= 'SA,';
			}else{
				$list .= $result['designation_code'].',';
			}
		 }
	  }
	 
	} 
	 return $list;
  }
  public function getExpenseFinalApprovalAuth(){
	     $select = $this->_db->select()
	  			->from(array('EA'=>'expense_approval'),array('*'))
				->where("EA.user_id='".$this->_getData['user_id']."'")
				->order("EA.position DESC")
				->limit(1);
	   $result =  $this->getAdapter()->fetchRow($select);
	if($result['approval_user_id']==$_SESSION['AdminLoginID']){
	  return true;
	}else{
	  return false; 
	}
  }
  public function getPreviusAuthority($user_id){
             $select = $this->_db->select()
	  			->from(array('EA'=>'expense_approval'),array('position'))
				->joinleft(array('EAC'=>'expense_approval'),"EAC.user_id=EA.user_id",array('approval_user_id as Childposition'))
				->where("EA.approval_user_id='".$_SESSION['AdminLoginID']."' AND EA.position>EAC.position AND EA.user_id='".$user_id."'")
                                ->order("EAC.position DESC")
				->limit(1);
	     $result =  $this->getAdapter()->fetchRow($select);
             if(empty($result)){
                  $select = $this->_db->select()
	  			->from(array('EA'=>'expense_approval'),array('position'))
				->joinleft(array('EAC'=>'expense_approval'),"EAC.user_id=EA.user_id",array('approval_user_id as Childposition'))
				->where("EA.approval_user_id='".$_SESSION['AdminLoginID']."' AND EA.position>=EAC.position AND EA.user_id='".$user_id."'")
                                ->order("EAC.position DESC")
				->limit(1);
	      $result =  $this->getAdapter()->fetchRow($select);
             }
            // if($user_id==38){
             //print_r($result);die;
            //}
             if($result['position']==1){
		   return '';
		}else{
		  return ','.$result['Childposition'];
		} 
  } 
 
  public function getExpenseUsersListForFilter(){
        $where = 1;
	  if($_SESSION['AdminLoginID']!=1){
	    $where .= " AND EA.approval_user_id='".$_SESSION['AdminLoginID']."'";
	  }
      $select = $this->_db->select()
	  			->from(array('EA'=>'expense_approval'),array('*'))
				->joinleft(array('UD'=>'employee_personaldetail'),"EA.user_id=UD.user_id",array('first_name','last_name','CONCAT(first_name," ",last_name) AS name','employee_code'))
				->where($where)
				->where("UD.delete_status='0'")
				->group('EA.user_id');
				//echo $select->__toString();die;
	 $result =  $this->getAdapter()->fetchAll($select);
	 $option = array();
	/* foreach($result as $user){
	   $option[$user['user_id']] = $user['first_name'].' '.$user['last_name']; 
	 }*/
	 return $result;
  }
   public function sendMailExpenserequest($id){
	   switch($id){
	       case 1:
                     $select = $this->_db->select()
                            ->from(array('EA'=>'expense_approval'),array(''))
                            ->joinleft(array('EPD'=>'employee_personaldetail'),"EA.approval_user_id=EPD.user_id",array('EPD.email AS Appemail','CONCAT(EPD.first_name," ",EPD.last_name) AS Receiver'))
                            ->joinleft(array('AEPD'=>'employee_personaldetail'),"AEPD.user_id=EA.user_id",array('AEPD.email','CONCAT(AEPD.first_name," ",AEPD.last_name) AS Sender'))
                            ->where("EA.user_id='".$_SESSION['AdminLoginID']."'")
                            ->order("EA.position ASC")
                            ->limit(1);
                    //echo $select->__toString();die;
             $result =  $this->getAdapter()->fetchRow($select);
                Bootstrap::$Mail->_DataArray = array('SenderEmail'	=>'testerp@jclifecare.com',
		   										'SenderName'	=>$result['Sender'],
												'ReceiverEmail'	=>'testerp@jclifecare.com',
												'ReceiverName'	=>$result['Receiver'],
												'Subject'		=>'Expense Request',
												'Body'			=>'You New Have Expense Request',
												'Attachment'	=>'');
		        Bootstrap::$Mail->SetEmailData();
		     break;
		   case 2:
		   	 break;
		   case 3:
		      break;	 
	   }
  } 
  public function setExpenseLog($data,$expense_id){
      $this->_db->insert('emp_expenses_log',array('expense_id'=>$expense_id,'approve_by'=>$_SESSION['AdminLoginID'],'approve_amount'=>$data['approve_amount']));
	  return;
  }
  public function getExpenseLog($expense_id){
                     $select = $this->_db->select()
                            ->from(array('EA'=>'emp_expenses_log'),array('*'))
							->where("expense_id='".$expense_id."'");
					  $results =  $this->getAdapter()->fetchAll($select);
			$list = '';		  
			foreach($results as $result){
			   $approved_by = $this->ExpenseApprovedByUsers($result['approve_by']);
			   if($approved_by=='ABM,'){
			   	 $list .= 'A-'.$result['approve_amount'].'('.date('d-m-Y',strtotime($result['approve_date'])).')<br>';
			   }elseif($approved_by=='ZBM,'){
			       $list .= 'Z-'.$result['approve_amount'].'('.date('d-m-Y',strtotime($result['approve_date'])).')<br>';
			   }elseif($approved_by=='BH,'){
			        $list .= 'B-'.$result['approve_amount'].'('.date('d-m-Y',strtotime($result['approve_date'])).')<br>';
			   }else{
			   		 $list .= 'SA-'.$result['approve_amount'].'('.date('d-m-Y',strtotime($result['approve_date'])).')<br>';
			   }
			}
		return $list;			  		
  }
  public function checkOnetimeExpense($date,$head_id){
  
     $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('COUNT(1) AS CNT'))
				->joininner(array('EH'=>'expense_head'),"EH.head_id=EE.head_id",array(''))
				->where("EE.user_id='".$_SESSION['AdminLoginID']."' AND EH.no_of_times=2 AND date_format(EE.expense_date,'%Y-%m')='".date('Y-m',strtotime($date))."' AND EH.head_id='".$head_id."'");
				//echo $select->__toString();die;
	   $headid = $this->getAdapter()->fetchRow($select);
	   if($headid['CNT']>0){
			return false;
	   }else{
			return true;
	   }
  } 
  
  public function ExportExpenseApproved(){
     $where = '1';
	 $filterparam = '';
	 if(!empty($this->_getData['user_id'])){
	   $filterparam  .= " AND EE.user_id='".$this->_getData['user_id']."'";
	 }
	 if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
	   $filterparam  .= " AND date_format(EE.expense_date,'%M-%Y') BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
	 }
	 if($_SESSION['AdminLoginID']!=44 && $_SESSION['AdminLoginID']!=1){
	     $where .=" AND approval_user_id='".$_SESSION['AdminLoginID']."'";
	 }

	     $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('SUM(expense_amount) AS EXPAMT','SUM(approve_amount)  AS APPAMT','SUM(fare)  AS FARE','sum(mixed_amount) AS Mixed','approve_by','date_format(expense_date,"%M-%Y") AS EXPMONTH'))
				->joinleft(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",array('first_name','last_name','employee_code','user_id'))
				->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name','designation_code'))
				->joinleft(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array('(SELECT HQ.headquater_name FROM headquater HQ WHERE HQ.headquater_id=EL.headquater_id) AS headquater_name'))
				->where("ED.delete_status='0' AND salary_status='0' AND approve_status='1'")
				->group("ED.user_id")
				->group("date_format(expense_date,'%Y-%m')")
				->group("approve_status")
				->order("date_format(expense_date,'%Y-%m') ASC")
				->order("approve_status")
				->order("ED.employee_code");
		 $expenselists = $this->getAdapter()->fetchAll($select);
		$_nxtcol   = "\t";
		$_nxtrow  = "\n";
		$Header .= "\"Employee Name \"".$_nxtcol.
						"\"Employee Code\"".$_nxtcol.
						"\"Designation \"".$_nxtcol.
						"\"Headquater \"".$_nxtcol.
						"\"Eexpense Amount \"".$_nxtcol.
						"\"Fare \"".$_nxtcol.
						"\"Mixed Exp. Amount \"".$_nxtcol.
						"\"Total \"".$_nxtcol.
						"\"Approved\"".$_nxtrow.$_nxtrow;
		 foreach($expenselists as $expense){
		    $Header .= "\"" . str_replace( "\"", "\"\"",$expense['first_name']." ".$expense['last_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expense['employee_code']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expense['designation_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expense['headquater_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expense['EXPAMT']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expense['FARE']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expense['Mixed']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",($expense['EXPAMT']+ $expense['FARE']+$expense['Mixed'])) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expense['APPAMT']) . "\"" . $_nxtcol;
			$Header .="\n";
		 }	
		header("Content-type: application/xls");
        header("Content-Disposition: attachment; filename=ExpenseList.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $Header;
        exit();	
  } 
  public function getSalariedExpense(){
          $object = new SalaryManager();
	      $select = $this->_db->select()
							->from(array('SL'=>'salary_list'),array('amount','date'))
							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=SL.user_id",array('first_name','last_name','employee_code','user_id'))
							->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name','designation_code'))
							->joinleft(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array('(SELECT HQ.headquater_name FROM headquater HQ WHERE HQ.headquater_id=EL.headquater_id) AS headquater_name'))
							->where("date='".$object->FromDate."' AND salary_processed='0' AND salaryhead_id=3");
							//echo $select->__toString();die;
		  $expenseAmount = $this->getAdapter()->fetchAll($select);
		  return $expenseAmount;
  }
   public function UpdateSalaryExpense(){
    foreach($this->_getData['user_id'] as $users_id){
	     $this->_db->update('salary_list',array('amount'=>$this->_getData['amount'][$users_id]),"salary_processed='0' AND salaryhead_id=3 AND date='".$this->_getData['salary_date']."' AND user_id='".$users_id."'");
		 //echo "<pre>";print_r($this->_getData['amount'][$users_id]);die;
	}
	$_SESSION[SUCCESS_MSG] = 'Expense Updated successfully!';
  }
  
   public function AllExpenseReques(){
     $where = '1';
	 $filterparam = '';
	 if(!empty($this->_getData['user_id'])){
	   $filterparam  .= " AND EE.user_id='".$this->_getData['user_id']."'";
	 }
	 if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
	   $filterparam  .= " AND EE.expense_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
	 }
	 /*if($_SESSION['AdminLoginID']!=44 && $_SESSION['AdminLoginID']!=1){
	     $where .=" AND approval_user_id='".$_SESSION['AdminLoginID']."'";
	 }*/
	     $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('SUM(expense_amount) AS EXPAMT','SUM(approve_amount)  AS APPAMT','SUM(fare)  AS FARE','sum(mixed_amount) AS Mixed','approve_by','approve_status','date_format(expense_date,"%M-%Y") AS EXPMONTH'))
				->joinleft(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",array('first_name','last_name','employee_code','user_id'))
				->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name','designation_code'))
				->joinleft(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array('(SELECT HQ.headquater_name FROM headquater HQ WHERE HQ.headquater_id=EL.headquater_id) AS headquater_name'))
				->where("ED.delete_status='0'".$filterparam)
				->group("ED.user_id")
				->group("date_format(expense_date,'%Y-%m')")
				->order("ED.employee_code ASC")
				->order("date_format(expense_date,'%Y-%m') ASC"); 
             //if($_SESSION['AdminLoginID']==1){
                //echo $select->__toString();die;
            // }
	     $results =  $this->getAdapter()->fetchAll($select);
	   return  $results;
  }
	public function ExportExExpenseApproved(){
		$where = 'AND 1';
		$filterparam = '';
		if(!empty($this->_getData['user_id'])){
			$filterparam  .= " AND EE.user_id='".$this->_getData['user_id']."'";
		}
		if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
			$filterparam  .= " AND date_format(EE.expense_date,'%M-%Y') BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
		}
		if($_SESSION['AdminLoginID']!=44 && $_SESSION['AdminLoginID']!=1){
			$where .=" AND approval_user_id='".$_SESSION['AdminLoginID']."'";
		}

	    $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('SUM(expense_amount) AS EXPAMT','SUM(approve_amount)  AS APPAMT','SUM(fare)  AS FARE','sum(mixed_amount) AS Mixed','approve_by','date_format(expense_date,"%M-%Y") AS EXPMONTH','expense_date','expense_detail'))
				->joinleft(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",array('first_name','last_name','employee_code','user_id'))
				->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name','designation_code'))
				->joinleft(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array('(SELECT HQ.headquater_name FROM headquater HQ WHERE HQ.headquater_id=EL.headquater_id) AS headquater_name'))
				->joinleft(array('EH'=>'expense_head'),"EE.head_id=EH.head_id",array('head_name'))
				->where("ED.delete_status='1' AND salary_status='0' AND approve_status='1' ".$where.$filterparam."")
				->group("ED.user_id")
				->group("date_format(expense_date,'%Y-%m')")
				->group("approve_status")
				->order("date_format(expense_date,'%Y-%m') ASC")
				->order("approve_status")
				->order("ED.employee_code");
				//echo $select->__toString();die;
		$totalRowData = $this->getAdapter()->fetchAll($select);//print_r($totalRowData);die;
		try{
			if(count($totalRowData)>0){
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();

				// Write Sheet Header
				$headers = array('0'=>'Expense Date','1'=>'Employee Name','2'=>'Employee Code','3'=>'Designation','4'=>'Headquarter','5'=>'Expense Head','6'=>'Expense Detail','7'=>'Expense Amount','8'=>'Fare','9'=>'Mixed Exp. Amount','10'=>'Total','11'=>'Approved');
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A1');

				$styleArray = array(
								'borders' => array(
								'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN
									)
								)
							);

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);

				unset($styleArray);
			
				// Set title row bold

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);

			

				// Setting Auto Width

				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {

					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);

				}

			
	
				// Setting Column Background Color

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->	getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

			

				// Setting Text Alignment Center

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			

				$reportRows = array();
				$i==0;
				foreach($totalRowData as $row)
				{
					$reportRows[$i]['expense_date']=$row['expense_date'];
					$reportRows[$i]['Emp_Name']=$row['first_name']." ".$row['last_name'];
					$reportRows[$i]['Emp_Code']=$row['employee_code'];
					$reportRows[$i]['Designation']=$row['designation_name'];
					$reportRows[$i]['headquater_name']=$row['headquater_name'];
					$reportRows[$i]['head_name']=$row['head_name'];
					$reportRows[$i]['expense_detail']=$row['expense_detail'];
					$reportRows[$i]['expense_amount']=$row['EXPAMT'];
					$reportRows[$i]['fare']=$row['FARE'];
					$reportRows[$i]['mixed_expense_amount']=$row['Mixed'];
					$reportRows[$i]['total']=$row['EXPAMT']+$row['FARE']+$row['Mixed'];
					$reportRows[$i]['approved']=$row['APPAMT'];
					$i++;
				}

				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A'.'2');

				

				// Set autofilter

				// Always include the complete filter range!

				// Excel does support setting only the caption

				// row, but that's not a best practise...

				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column

				$objPHPExcel->getActiveSheet()->setAutoFilter('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');

			

				// Rename sheet

				$objPHPExcel->getActiveSheet()->setTitle('Ex Employee Expense');

			

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);

								

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/vnd.ms-excel');

				header('Content-Disposition: attachment;filename="ex-employeeExpense.xls"');

				header('Cache-Control: max-age=0');

				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

				ob_end_clean();

				$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?

				//$objWriter->save('test.xlsx');  //THIS WORKS

				$objPHPExcel->disconnectWorksheets();

				unset($objPHPExcel);die;

			}else{
				$Header .= 	"\" No Data Found!! \"".$_nxtcol;
			}
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code: '.__LINE__; 
		}	
	}
		
	public function ExAllExpenseReques(){
		
		$where = '1';
		$filterparam = '';
		
		if(!empty($this->_getData['user_id'])){
			$filterparam  .= " AND EE.user_id='".$this->_getData['user_id']."'";
		}
		if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
			$filterparam  .= " AND EE.expense_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
		}
		/*if($_SESSION['AdminLoginID']!=44 && $_SESSION['AdminLoginID']!=1){
			$where .=" AND approval_user_id='".$_SESSION['AdminLoginID']."'";
		}*/
	    $select = $this->_db->select()
				->from(array('EE'=>'emp_expenses'),array('SUM(expense_amount) AS EXPAMT','SUM(approve_amount)  AS APPAMT','SUM(fare)  AS FARE','sum(mixed_amount) AS Mixed','approve_by','date_format(expense_date,"%M-%Y") AS EXPMONTH'))
				->joinleft(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",array('first_name','last_name','employee_code','user_id'))
				->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name','designation_code'))
				->joinleft(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array('(SELECT HQ.headquater_name FROM headquater HQ WHERE HQ.headquater_id=EL.headquater_id) AS headquater_name'))
				->where("ED.delete_status='1'".$filterparam)
				->group("ED.user_id")
				->group("date_format(expense_date,'%Y-%m')")
				->order("ED.employee_code ASC")
				->order("date_format(expense_date,'%Y-%m') ASC"); 
            /* if($_SESSION['AdminLoginID']==1){
                echo $select->__toString();die;
             }*/
	    $results =  $this->getAdapter()->fetchAll($select);
		return  $results;
	}
	
    public function ExgetExpenseUsersListForFilter(){
		$where = 1;
		if($_SESSION['AdminLoginID']!=1){
			$where .= " AND EA.approval_user_id='".$_SESSION['AdminLoginID']."'";
		}
		$select = $this->_db->select()
	  			->from(array('EA'=>'expense_approval'),array('*'))
				->joinleft(array('UD'=>'employee_personaldetail'),"EA.user_id=UD.user_id",array('first_name','last_name','CONCAT(first_name," ",last_name) AS name','employee_code'))
				->where($where)
				->where("UD.delete_status='1'")
				->group('EA.user_id')
				->order("UD.first_name ASC");
				//echo $select->__toString();die;
				
		$result =  $this->getAdapter()->fetchAll($select);
		$option = array();
		/* foreach($result as $user){
		$option[$user['user_id']] = $user['first_name'].' '.$user['last_name']; 
		}*/
		return $result;
	}
	public function deleteExpense($data=array())
	{
		//$month=$data['month']."___";
		//print_r($data);die;
		return $this->_db->delete('emp_expenses',"user_id=$data[user_id] AND expense_id=$data[expense_id]");
		//return $this->_db->delete('emp_expenses',"user_id=$data[user_id] AND expense_date LIKE '$month'");
	}

	public function getEmpDetail($arr=array()){
		//It Will Select All BE of the respective ABM
		$where="";
		if(isset($arr['designation_id']) && $arr['designation_id']!=''){
			$where.=" AND UT.designation_id=".$arr['designation_id']."";			
		}
		if(isset($arr['parent_id']) && $arr['parent_id']!=''){
			$where.=" AND UT.parent_id=".$arr['parent_id']."";
		}
		if(isset($arr['user_id']) && $arr['user_id']!=''){
			$where.=" AND UT.user_id=".$arr['user_id']."";
		}

		$select = $this->_db->select()
	            ->from(array('UT'=>'employee_personaldetail'),array('employee_code as Emp_Code','CONCAT(UT.first_name," ",UT.last_name) as Emp_Name','email','contact_number','doj','dob'))
				
	            ->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name as Designation'))
				
	            ->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array())
				
	            ->joininner(array('EL'=>'emp_locations'),"EL.user_id=UT.user_id",array())
				
				->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('HQ.headquater_name'))
				
				->joinleft(array('RT'=>'region'),"RT.region_id=HQ.region_id",array('RT.region_name'))

				->joinleft(array('ZT'=>'zone'),"ZT.zone_id=HQ.zone_id",array('ZT.zone_name'))
				
				->joininner(array('US'=>'users'),"US.user_id=UT.user_id",array())
	            ->where("`UT`.`delete_status`='0'".$where);
				//echo $select->__toString();die;
		return $this->getAdapter()->fetchAll($select);
	}
}
?>