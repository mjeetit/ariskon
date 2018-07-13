<?php

class DashboardManager extends Zend_Custom

{
    public $_getData = array();
  	public function getSalaryHistory(){
	    $select = $this->_db->select()
		 				->from(array('SH'=>'salary_history'),array('*'))
						->where("SH.user_id='".$_SESSION['AdminLoginID']."'")
						->order("release_date DESC")
						->limit(5);
	 $result = $this->getAdapter()->fetchAll($select);
	 return $result; 
    }

	public function getLast5Userdetail(){
	  $select = $this->_db->select()
		 				->from(array('DT'=>'employee_personaldetail'),array('CONCAT(first_name," ",last_name) as name','employee_code'))
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=DT.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=DT.department_id",array('department_name'))
						->order("user_id DESC")
						->limit(5);
		//echo $select->__toString();die;
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	public function getLast5leaveRequest(){
	 $where = '1';
	  
	 if($_SESSION['AdminLoginID']!=1){
	     $where .=" AND approval_user_id='".$_SESSION['AdminLoginID']."'";
	 }
     $select = $this->_db->select()
	  			->from(array('ELA'=>'emp_leave_approval'),array('DISTINCT(user_id)'))
				->where($where);
	 $approvaluserlist = $this->getAdapter()->fetchAll($select);
	 $count =0;
	 foreach($approvaluserlist as $request){
	   $chiledauthority = $this->getLeavePreviusAuthorityForDashboard($request['user_id']);
           $childauth = '';
	 if($chiledauthority && $chiledauthority!=',0' && $chiledauthority!=',' && $chiledauthority!=''){
	    $childauth =" AND LR.approved_approval LIKE '%".$chiledauthority."'";
	 } 
	    $select = $this->_db->select()
	  			->from(array('LR'=>'leaverequests'),array('*'))
				->joinleft(array('ED'=>'employee_personaldetail'),"ED.user_id=LR.user_id",array('first_name','last_name','employee_code','user_id'))
				->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
				->where("ED.user_id='".$request['user_id']."'".$childauth."")
				->order("LR.request_id DESC");
                              //if($request['user_id']==32){
				//echo $select->__tostring();die;
                              //}
	     $result =  $this->getAdapter()->fetchAll($select);
		 if(!empty($result) && count($result)>1){
		    foreach($result as $request){
			  $leaverequest[] = $request;
			  $count++;
			  if($count==5){
			    return  $leaverequest;
			  }
			}
		 }elseif(!empty($result)){
		     $leaverequest[] = $result[0]; 
			 $count++;
			 if($count==5){
			    return  $leaverequest;
			  }
		 } 
	 }//print_r($leaverequest);die;
	 return $leaverequest;
	}
     public function getLeavePreviusAuthority($user_id){
        $select = $this->_db->select()
	  			->from(array('EA'=>'emp_leave_approval'),array('position'))
				->joinleft(array('EAC'=>'emp_leave_approval'),"EAC.user_id=EA.user_id",array('approval_user_id as Childposition'))
				->where("EA.approval_user_id='".$_SESSION['AdminLoginID']."' AND EA.position>=EAC.position AND EA.user_id='".$user_id."'")
				->limit(1);
				//echo $select->__toString();die;
	     $result =  $this->getAdapter()->fetchRow($select);//print_r($result);die;
		if($result['position']==1){
		   return false;
		}else{
		  return ','.$result['Childposition'];
		}
  } 
  
  public function getLeavePreviusAuthorityForDashboard($user_id){
        $select = $this->_db->select()
	  			->from(array('EA'=>'emp_leave_approval'),array('position','COUNT(1) as CNT'))
				->joinleft(array('EAC'=>'emp_leave_approval'),"EAC.user_id=EA.user_id",array('approval_user_id as Childposition'))
				->where("EA.approval_user_id='".$_SESSION['AdminLoginID']."' AND EA.position>EAC.position AND EA.user_id='".$user_id."'")
                                ->order("EAC.position DESC")
				->limit(1);
				//echo $select->__toString();die;
	     $result =  $this->getAdapter()->fetchRow($select);
              if(empty($result)){
              $select = $this->_db->select()
	  			->from(array('EA'=>'emp_leave_approval'),array('position','COUNT(1) as CNT'))
				->joinleft(array('EAC'=>'emp_leave_approval'),"EAC.user_id=EA.user_id",array('approval_user_id as Childposition'))
				->where("EA.approval_user_id='".$_SESSION['AdminLoginID']."' AND EA.position>=EAC.position AND EA.user_id='".$user_id."'")
                                ->order("EAC.position DESC")
				->limit(1);
				//echo $select->__toString();die;
	     $result =  $this->getAdapter()->fetchRow($select);

              }
            // if($user_id==3){
              //  print_r($result);die;
           //  }
		if($result['position']==1 || $result['CNT']==1){
		   return false;
		}else{
		  return ','.$result['Childposition'];
		} 
  }
  
  public function CheckForeignsession($userdata){
      		$select = $this->_db->select()
									 ->from(array('UD'=>'users'),array('*'))
									 ->where("UD.user_id='".base64_decode($userdata['tocken'])."'");
			 $uservalue = $this->getAdapter()->fetchRow($select);
			 //echo $select->__toString();die;
			if(!empty($uservalue)){ 
    		$_SESSION['AdminLoginID']     = $uservalue['user_id'];
			$_SESSION['AdminLevelID']     = $uservalue['level_id'];
			$_SESSION['AdminUserType']    = $uservalue['user_type'];
			if($_SESSION['AdminLoginID']==1){
			  $table = 'admin_detail';
			}else{
			  $table = 'employee_personaldetail';
			}
			$select = $this->_db->select()
								 ->from($table,array('*'))
								 ->where("user_id='".$uservalue['user_id']."'");
								// echo $select->__toString();die;
			$result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
			$_SESSION['AdminName']        = $result['first_name'];
			$_SESSION['AdminBunit']    	  = $result['bunit_id'];
			$_SESSION['AdminDesignation'] = $result['designation_id'];
			$_SESSION['AdminDepartment']  = $result['department_id'];
			$_SESSION['LastLogin']  	  = $result['last_login'];
			$_SESSION['LastLoginIP']  	  = $result['last_login_ip'];
			/*********************************************************************************
			 below line is to set session variable for current root module either HRM(main) or crm or reporting by jm on 13072018
			**********************************************************************************/
			$_SESSION['ParentTab']  	  = "REPORTING";
			
			$this->_db->update($table,array('last_login'=>new Zend_Db_Expr('NOW()'),'last_login_ip'=>$_SERVER['REMOTE_ADDR']),"user_id='".$_SESSION['AdminLoginID']."'");
		}	
  }
	 
}

?>