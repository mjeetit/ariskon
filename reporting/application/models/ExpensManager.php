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
	      if($auth || $_SESSION['AdminLoginID']==1){
		     $data = array('approve_amount'=>$this->_getData['approve_amount'][$expenseid],'remarks'=>$this->_getData['remarks'][$expenseid],'approve_by'=>$approveby,'approve_status'=>'1');
		  }else{
		    $data = array('approve_amount'=>$this->_getData['approve_amount'][$expenseid],'remarks'=>$this->_getData['remarks'][$expenseid],'approve_by'=>$approveby);
		  }
		  if(($this->_getData['emp_amount'][$expenseid] + $this->_getData['emp_fare'][$expenseid])>=$this->_getData['approve_amount'][$expenseid]){
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
	 if($_SESSION['AdminLoginID']!=44 && $_SESSION['AdminLoginID']!=1){
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
	  			->from(array('EE'=>'emp_expenses'),array('SUM(expense_amount) AS EXPAMT','SUM(approve_amount)  AS APPAMT','SUM(fare)  AS FARE','sum(mixed_amount) AS Mixed','approve_by','date_format(expense_date,"%M-%Y") AS EXPMONTH'))
				->joinleft(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",array('first_name','last_name','employee_code','user_id'))
				->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
				->where("ED.user_id='".$users['user_id']."'".$childauth."".$filterparam)
				->group("ED.user_id")
				->group("date_format(expense_date,'%Y-%m')");
             //if($users['user_id']==38){
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
				->joinleft(array('UD'=>'employee_personaldetail'),"EA.user_id=UD.user_id",array('first_name','last_name'))
				->where($where)
				->group('EA.user_id');
				//echo $select->__toString();die;
	 $result =  $this->getAdapter()->fetchAll($select);
	 $option = array();
	 foreach($result as $user){
	   $option[$user['user_id']] = $user['first_name'].' '.$user['last_name']; 
	 }
	 return $option;
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
   	
}
?>