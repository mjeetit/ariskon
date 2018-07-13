<?php

class ReportManager extends Zend_Custom

{
	 public $_getData = array();
	 
	 public function getExpenseReport(){
	      $where = 1;
		  $childUser = $this->getChildUsersofApproval('expense_approval');
		  if($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']!='self'){
		     $where .= " AND EE.user_id IN ('".implode(',',$childUser )."')";
		  }elseif($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']=='self'){
		     $where .= " AND EE.user_id='".$_SESSION['AdminLoginID']."'";
		  }
                  $filter = '';
                   if(!empty($this->_getData['user_id'])){
                        $filter .= " AND ED.user_id='".$this->_getData['user_id']."'";
                   }
                   if(!empty($this->_getData['department_id'])){
                        $filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
                   }
                   if(!empty($this->_getData['designation_id'])){
                        $filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
                   }
                   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
                        $filter .= " AND EE.expense_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
                   }
		
		  
			$select = $this->_db->select()
							   ->from(array('EE'=>'emp_expenses'),array('*'))
							    ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('employee_code'))
							   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
								->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
								->where($where.$filter);
								
			$result =  $this->getAdapter()->fetchAll($select);
		return $result;					
	 }
	 public function getLeaveReport(){
	      $where = 1;
		  $childUser = $this->getChildUsersofApproval('emp_leave_approval');
		  if($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']!='self'){
		     $where .= " AND LR.user_id IN ('".implode(',',$childUser )."')";
		  }elseif($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']=='self'){
		     $where .= " AND LR.user_id='".$_SESSION['AdminLoginID']."'";
		  }
		  
		   	   $filter = '';
			   if(!empty($this->_getData['user_id'])){
					$filter .= " AND LR.user_id='".$this->_getData['user_id']."'";
			   }
			   if(!empty($this->_getData['department_id'])){
					$filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
			   } 
			   if(!empty($this->_getData['designation_id'])){
					$filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
			   }
			   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
					$filter .= " AND date(LR.request_date) BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
			   }
	    
			$select = $this->_db->select()
							   ->from(array('LR'=>'leaverequests'),array('*'))
							    ->joininner(array('ED'=>'employee_personaldetail'),"LR.user_id=ED.user_id",array('employee_code'))
							   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
								->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
								->where($where.$filter);
								
			$result =  $this->getAdapter()->fetchAll($select);
		return $result;					
	 }
	 
	  public function getAttandanceReport(){
	      	$where = 1;
		  if($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']!='self'){
		     $where .= " AND ED.parent_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']=='self'){
		     $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
		  }
		 $select = $this->_db->select()
		 				->from(array('AT'=>'attandance'),array('*'))
						->joinleft(array('ED'=>'employee_personaldetail'),"ED.employee_code=AT.employee_code",array())
						->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
						->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))						
						->where($where);
						//echo $select->__toString();die;
	  	$result = $this->getAdapter()->fetchAll($select);
	  return $result;
	 }
	 
	 public function getChildUsersofApproval($table){
	    $select = $this->_db->select()
							   ->from(array('AT'=>$table),array('*'))
							   ->where("approval_user_id='".$_SESSION['AdminLoginID']."'")
							   ->group("user_id");
		$result =  $this->getAdapter()->fetchAll($select);
		if(!empty($result)){
		  foreach($result as $user_id){
		     $ids[] = $user_id['user_id']; 
		  }
		}else{
		  $ids = array();
		}
		return $ids;		   
	 }
        public function ExportExpenseReport(){
           $_nxtcol   = "\t";
	   $_nxtrow  = "\n";

           $filter = '';
           if(!empty($this->_getData['user_id'])){
                $filter .= " AND ED.user_id='".$this->_getData['user_id']."'";
           }
           if(!empty($this->_getData['department_id'])){
                $filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
           }
           if(!empty($this->_getData['designation_id'])){
                $filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
           }
           if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
                $filter .= " AND EE.expense_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
           }
           $select = $this->_db->select()
                                   ->from(array('EE'=>'emp_expenses'),array('*'))
                                   ->joininner(array('EH'=>'expense_head'),"EH.head_id=EE.head_id",array('head_name'))
                                   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('employee_code','first_name','last_name'))
                                   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
                                   ->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
                                   ->where("1".$filter);

	   $result =  $this->getAdapter()->fetchAll($select);
	   $Header .= "\"Employee Name \"".$_nxtcol.
                        "\"Employee Code\"".$_nxtcol.
                        "\"Expense Head \"".$_nxtcol.
                        "\"Travel Destnation \"".$_nxtcol.
                        "\"Description \"".$_nxtcol.
                        "\"Total Expense \"".$_nxtcol.
                        "\"Fare \"".$_nxtcol.
                        "\"Total Approve \"".$_nxtcol.
                        "\"Remarks \"".$_nxtcol.
                        "\"Month Of Expense \"".$_nxtrow.$_nxtrow;
			$tatalexpense = 0;	
			$totalfare = 0;	
			$totalapprove  =0;	
		foreach($result as $record){
		    $Header .= "\"" . str_replace( "\"", "\"\"",$record['first_name'].' '.$record['last_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['employee_code']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",$record['head_name']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",$record['travel_destination']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",$record['expense_detail']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['expense_amount']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",$record['fare']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['approve_amount']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",$record['remarks']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['expense_date']) . "\"" . $_nxtcol;
			$tatalexpense = $tatalexpense + $record['expense_amount'];
			$totalfare = $totalfare + $record['fare'];
			$totalapprove = $totalapprove + $record['approve_amount'];
			$Header .="\n";
		}
		$Header .= "\n\t\t\t\t";
		$Header .= "\"" . str_replace( "\"", "\"\"",'Total') . "\"" . $_nxtcol;
		$Header .= "\"" . str_replace( "\"", "\"\"",$tatalexpense) . "\"" . $_nxtcol;
		$Header .= "\"" . str_replace( "\"", "\"\"",$totalfare) . "\"" . $_nxtcol;
		$Header .= "\"" . str_replace( "\"", "\"\"",$totalapprove) . "\"" . $_nxtcol;
		
	 	header("Content-type: application/xls");
                header("Content-Disposition: attachment; filename=ExpenseList.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $Header;
                exit();
        }
       
	    public function ExportLeaveReport(){
            $_nxtcol   = "\t";
	   $_nxtrow  = "\n"; 

		  $filter = '';
		   if(!empty($this->_getData['user_id'])){
				$filter .= " AND LR.user_id='".$this->_getData['user_id']."'";
		   }
		   if(!empty($this->_getData['department_id'])){
				$filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
		   } 
		   if(!empty($this->_getData['designation_id'])){
				$filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
		   }
		   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
				$filter .= " AND date(LR.request_date) BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
		}

           $select = $this->_db->select()
                                   ->from(array('LR'=>'leaverequests'),array('*'))
                                   ->joininner(array('ED'=>'employee_personaldetail'),"LR.user_id=ED.user_id",array('employee_code','first_name','last_name'))
                                   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
                                   ->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
                                   ->where("1".$filter);
        // echo $select->__toString();die;

	   $result =  $this->getAdapter()->fetchAll($select);
	   $Header .= "\"Employee Name \"".$_nxtcol.
                        "\"Employee Code\"".$_nxtcol.
                        "\"Leave From \"".$_nxtcol.
                        "\"Leave To \"".$_nxtcol.
                        "\"Leave Days\"".$_nxtcol.
                        "\"Request Date \"".$_nxtcol.
                        "\"SL \"".$_nxtcol.
                        "\"CL \"".$_nxtcol.
                        "\"PL \"".$_nxtrow.$_nxtrow;
			$tatalexpense = 0;
			$totalfare = 0;
			$totalapprove  =0;
		foreach($result as $record){
		    $Header .= "\"" . str_replace( "\"", "\"\"",$record['first_name'].' '.$record['last_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['employee_code']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",$record['leave_from']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",$record['leave_to']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['total_days']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",$record['request_date']) . "\"" . $_nxtcol;
			/*$Header .= "\"" . str_replace( "\"", "\"\"",$record['approve_amount']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",'') . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['expense_date']) . "\"" . $_nxtcol;
			$tatalexpense = $tatalexpense + $record['expense_amount'];
			$totalfare = $totalfare + $record['fare'];
			$totalapprove = $totalapprove + $record['approve_amount'];*/
			$Header .="\n";
		}
//		$Header .= "\n\t\t\t\t";
//		$Header .= "\"" . str_replace( "\"", "\"\"",'Total') . "\"" . $_nxtcol;
//		$Header .= "\"" . str_replace( "\"", "\"\"",$tatalexpense) . "\"" . $_nxtcol;
//		$Header .= "\"" . str_replace( "\"", "\"\"",$totalfare) . "\"" . $_nxtcol;
//		$Header .= "\"" . str_replace( "\"", "\"\"",$totalapprove) . "\"" . $_nxtcol;

	 	header("Content-type: application/xls");
                header("Content-Disposition: attachment; filename=ExpenseList.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $Header;
                exit();
        }
}

?>