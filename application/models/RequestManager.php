<?php
class RequestManager extends Zend_Custom
{
	private $_leaveRequest 		= "leaverequests";
	private $_requestApproval	= "leaverequestapprovals";
	private $_requesttype 		= "leaverequesttype";
	private $_leavestatus		= "leavestatus";
	private $_leaveapproval		= "leaveapprovals";
	private $_leavedistribution = "leavedistributions";
	private $_leavetype			= "leavetypes";
	private $_personaldetail	= "employee_personaldetail";
	private $_userleaves		= "emp_leaves";
	
	public function getLeaveLists($data=array()){
	    $user_id = (isset($data['user_id'])) ? trim($data['user_id']) : trim($_SESSION['AdminLoginID']);
		
		$select =  $this->_db->select()
	     				->from(array('LR'=>$this->_leaveRequest),array('*'))
						->joinleft(array('LS'=>$this->_leavestatus),'LS.status_id=LR.request_status',array('LS.status_name'))
						->where("LR.user_id=".$user_id);
		//echo $select->__tostring();die;
		$result = $this->getAdapter()->fetchAll($select);	
		return $result;
	 }
	 
	public function getLeaveNotificationLists($data=array()){
	    $user_id = (isset($data['user_id'])) ? trim($data['user_id']) : trim($_SESSION['AdminLoginID']);
		
		$select =  $this->_db->select()
	     				->from(array('LRA'=>$this->_requestApproval),array('LRA.approval_staus','LRA.read_status'))
						->joininner(array('LR'=>$this->_leaveRequest),'LR.request_id=LRA.request_id',array('*'))
						->joininner(array('UDT'=>$this->_personaldetail),'UDT.user_id=LR.user_id',array('Name'=>'CONCAT(UDT.first_name," ",UDT.last_name)'))
						->joinleft(array('LST'=>$this->_leavestatus),'LST.status_id=LRA.approval_staus',array('LST.status_name'))
						->where("LRA.approval_user_id=".$user_id); //echo $select->__tostring();die;
		$info = array();
		foreach($this->getAdapter()->fetchAll($select) as $key=>$row) {
			$info[$key]['Token'] 	= $row['request_id'];
			$info[$key]['Name'] 	= $row['Name'];
			$info[$key]['Request']  = $row['request_date'];
			$info[$key]['From'] 	= $row['leave_from'];
			$info[$key]['To'] 		= $row['leave_to'];
			$info[$key]['Days'] 	= $row['total_days'];
			$info[$key]['Approval']	= $this->getApprovalUser(array('RequestID'=>$row['request_id']));
			$info[$key]['Rejected'] = $this->getRejectedUser(array('RequestID'=>$row['request_id']));
			$info[$key]['Status'] 	= $row['status_name'];
		}
			
		return $info;
	}
	
	public function getApprovalUser($data=array()) {
		$RequestID = (isset($data['RequestID'])) ? trim($data['RequestID']) : 0;
		$user_id   = (isset($data['user_id']))   ? trim($data['user_id'])   : trim($_SESSION['AdminLoginID']);
		
		$select =  $this->_db->select()
	     				->from(array('LRA'=>$this->_requestApproval),'')
						->joininner(array('UDT'=>$this->_personaldetail),'UDT.user_id=LRA.approval_user_id',array('Name'=>'CONCAT(UDT.first_name," ",UDT.last_name)'))
						->where("LRA.request_id=".$RequestID)
						->where("LRA.approval_staus=5")
						->where("LRA.approval_user_id!=".$user_id); //echo $select->__tostring();die;	
						
		return $this->getAdapter()->fetchAll($select);
	}
	
	public function getRejectedUser($data=array()) {
		$RequestID = (isset($data['RequestID'])) ? trim($data['RequestID']) : 0;
		$user_id   = (isset($data['user_id']))   ? trim($data['user_id'])   : trim($_SESSION['AdminLoginID']);
		
		$select =  $this->_db->select()
	     				->from(array('LRA'=>$this->_requestApproval),'')
						->joininner(array('UDT'=>$this->_personaldetail),'UDT.user_id=LRA.approval_user_id',array('Name'=>'CONCAT(UDT.first_name," ",UDT.last_name)'))
						->where("LRA.request_id=".$RequestID)
						->where("LRA.approval_staus=4")
						->where("LRA.approval_user_id!=".$user_id); //echo $select->__tostring();die;	
						
		return $this->getAdapter()->fetchAll($select);
	}
	 
	public function getLeaveRequestDetail($data=array()){
	    $request_id = (isset($data['typeID'])) ? trim($data['typeID']) : 0;
		$user_id    = (isset($data['user_id']))? trim($data['user_id']): trim($_SESSION['AdminLoginID']);
		
		$select =  $this->_db->select()
	     				->from(array('LR'=>$this->_leaveRequest),array('*'))
						->joininner(array('LRA'=>$this->_requestApproval),'LRA.request_id=LR.request_id AND LRA.approval_user_id='.$user_id,array('LRA.approval_staus'))
						->joininner(array('UDT'=>$this->_personaldetail),'UDT.user_id=LR.user_id',array('Name'=>'CONCAT(UDT.first_name," ",UDT.last_name)'))
						->joinleft(array('LST'=>$this->_leavestatus),'LST.status_id=LRA.approval_staus',array('LST.status_name'))
						->where("LR.request_id=".$request_id);
		//echo $select->__tostring();die;
		$result = $this->getAdapter()->fetchRow($select);	
		return $result;
	}
	
	public function getRequestedLeaveTypes($data=array()){
	    $request_id = (isset($data['typeID'])) ? trim($data['typeID']) : 0;
		$user_id    = (isset($data['user_id']))? trim($data['user_id']): trim($_SESSION['AdminLoginID']);
		
		$select = $this->_db->select()->from(array('LRT'=>$this->_requesttype),'*')->where('LRT.request_id='.$request_id);//echo $select->__tostring();die;
		$result = $this->getAdapter()->fetchAll($select);
		$restLeaves = array();
		if(count($result) > 0) {
			foreach($result as $row) {
				$restLeaves[$row['leave_typeID']] = $row['no_of_days'];
			}
		}
		return $restLeaves;
	}
	
	public function replyLeaveRequest(){
		$parent_id = trim($this->_getData['token']);
		$user_id   = (isset($this->_getData['user_id'])) ? trim($this->_getData['user_id']) : trim($_SESSION['AdminLoginID']);
		
		$this->_db->insert($this->_leaveRequest,array_filter(array('parent_id'	 => $parent_id,
			  													 	'user_id'	 => $user_id,
																 	'contents'	 => $this->_getData['contents'],
																 	'request_ip' => $_SERVER['REMOTE_ADDR'])));
	}
	
	public function getLeaveType($data=array()) {
		$userID = (isset($data['UserID'])) ? trim($data['UserID']) : trim($_SESSION['AdminLoginID']);
		$select = $this->_db->select()
							->from(array('UDT'=>$this->_personaldetail),'')
							->joininner(array('LDT'=>$this->_leavedistribution),'LDT.designation_id=UDT.designation_id AND LDT.leave_no>0','')
							->joininner(array('LT'=>$this->_leavetype),'LT.typeID=LDT.leave_type_id',array('LT.typeID','LT.typeName'))
							->where('UDT.user_id='.$userID);
		//echo $select->__tostring();die;
		$result = $this->getAdapter()->fetchAll($select);
		
		if(count($result) <= 0) {
			$select1 = $this->_db->select()
							->from(array('LDT'=>$this->_leavedistribution),'')
							->joininner(array('LT'=>$this->_leavetype),'LT.typeID=LDT.leave_type_id',array('LT.typeID','LT.typeName'))
							->where('LDT.designation_id=0')
							->where('LDT.leave_no>0');
			//echo $select1->__tostring();die;
			$result = $this->getAdapter()->fetchAll($select1);
		}
		return $result;
	}
	
	public function getRestLeaveOfUser($data=array()) {
		$userID = (isset($data['UserID'])) ? trim($data['UserID']) : trim($_SESSION['AdminLoginID']);
		$select = $this->_db->select()->from(array('UDT'=>$this->_userleaves),'*')->where('UDT.user_id='.$userID);//echo $select->__tostring();die;
		$result = $this->getAdapter()->fetchAll($select);
		$restLeaves = array();
		if(count($result) > 0) {
			foreach($result as $row) {
				$restLeaves[$row['leave_id']] = $row['no_of_leave'];
			}
		}
		return $restLeaves;
	}
	
	public function addLeaveRequest(){
		$user_id   = (isset($this->_getData['user_id'])) ? trim($this->_getData['user_id']) : trim($_SESSION['AdminLoginID']);
		$totalDays = (strtotime($this->_getData['leave_to']) - strtotime($this->_getData['leave_from'])) / (60 * 60 * 24) + 1;
		if($this->_getData['halfday_time']==1){
		     $time =0.5;
		}else{
		  $time = 0;
		}
		// Get Approval Nnumber Details
		$approval_no = $this->getLeaveapprovalNumber(array('UserID'=>$user_id));

		$this->_db->insert($this->_leaveRequest,array_filter(array('user_id'	 => $user_id,
																 	'leave_from' => $this->_getData['leave_from'],
																 	'leave_to'	 => $this->_getData['leave_to'],
																 	'total_days' => ($totalDays-$time),
																 	'subject'	 => $this->_getData['subject'],
																 	'contents'	 => $this->_getData['contents'],
																 	'request_by' => $_SESSION['AdminLoginID'],
																 	'request_ip' => $_SERVER['REMOTE_ADDR'])));
		$requestID = $this->_db->lastInsertId();
		
		// Add Levae Type Details
		$leaveIDs = explode(',',$this->_getData['loop']);
		$emailleave = '';
		$leave  = array('1'=>'Casual Leave','2'=>'sick leave','3'=>'Privillage Leave');
		foreach($leaveIDs as $ID) {
			if(trim($this->_getData['leaveDays_'.$ID]) > 0) {
				$this->_db->insert($this->_requesttype,array_filter(array('request_id'=>$requestID,'leave_typeID'=>$ID,'no_of_days'=>$this->_getData['leaveDays_'.$ID],'user_by'=>$user_id)));
				$emailleave .= $leave[$ID].'['.$this->_getData['leaveDays_'.$ID].'],';
			}
		}
		   $this->MailForLeaverequest($requestID,$emailleave);
		  //$this->sendMailLeaverequest(1);
		//Get Approval User Details
		$USID = $user_id;
		for($i=0;$i<$approval_no;$i++) {
			$reportee = $this->getReporteeUser(array('UserID'=>$USID));
			if($reportee['parent_id'] > 0) {
				$this->_db->insert($this->_requestApproval,array_filter(array('request_id'=>$requestID,'approval_user_id'=>$reportee['parent_id'])));
			}
			$USID = $reportee['parent_id'];
		}
	}
	
	public function getLeaveapprovalNumber ($data=array()) {
		$userID = (isset($data['UserID'])) ? trim($data['UserID']) : '0';
		$select = $this->_db->select()
							->from(array('UDT'=>$this->_personaldetail),'')
							->joininner(array('LAT'=>'leaveapprovals'),'LAT.designation_id=UDT.designation_id',array('LAT.approval_no'))
							->where('UDT.user_id='.$userID)
							->limit(1);
		//echo $select->__tostring();
		$result = $this->getAdapter()->fetchRow($select);
		$approvalNo = trim($result['approval_no']);
		if($approvalNo <= 0) {
			$select1 = $this->_db->select()
							->from(array('LAT'=>$this->_leaveapproval),array('LAT.approval_no'))
							->where('LAT.designation_id=0')
							->limit(1);
			//echo $select1->__tostring();
			$result1 = $this->getAdapter()->fetchRow($select1);
			$approvalNo = trim($result1['approval_no']);
		}
		return $approvalNo;
	}
	
	public function getReporteeUser($data=array()) {
		$userID = (isset($data['UserID'])) ? trim($data['UserID']) : '0';
		$select = $this->_db->select()->from(array('ULAT'=>$this->_personaldetail),array('ULAT.parent_id'))->where('ULAT.user_id='.$userID); //echo $select->__tostring();
		$result = $this->getAdapter()->fetchRow($select);
		return $result;
	}
	
	/**
	 * This function update user leave request and leave transactions
	 * return TRUE / FALSE
	 **/
	public function setStatus($data){
		$token = (isset($data['token'])) ? $data['token'] : '0';
		$type  = (isset($data['type']))  ? $data['type']  : '0';
		$remark= (isset($data['remarks'])) ? $data['remarks']    : '';
		$user  = (isset($data['User']))    ? trim($data['User']) : trim($_SESSION['AdminLoginID']);
		
		if($token > 0) {
			$update = $this->_db->update($this->_requestApproval,array('approval_staus'=>$type,'remarks'=>$remark,'approval_date'=>date('Y-m-d h:i:s'),'approval_ip'=>$_SERVER['REMOTE_ADDR'],'read_status'=>'1'),array('request_id=?'=>$token,'approval_user_id=?'=>$user));
			
			$reqDetail = $this->getLeaveRequestDetail(array('typeID'=>$token,'user_id'=>$user));
			$stat  = ($type==5) ? 'approved_approval' : 'rejected_approval';
			$valu  = $reqDetail[$stat]+1;
			
			$update1 = ($type==4) ? $this->_db->update($this->_leaveRequest,array($stat=>$valu,'request_status'=>4),array('request_id=?'=>$token)) : $this->_db->update($this->_leaveRequest,array($stat=>$valu),array('request_id=?'=>$token));
			
			if(($type==5) && ($reqDetail['required_approval']==$reqDetail['approved_approval']+1) && ($reqDetail['rejected_approval']==0)) {
				$leavetypes = $this->getRequestedLeaveType(array('RequestID'=>$token));
				$userleaves = $this->getRestLeaveOfUser(array('UserID'=>$reqDetail['user_id']));
				if(count($leavetypes) > 0) {
					foreach($leavetypes as $leavetype) {
						if(isset($userleaves[$leavetype['leave_typeID']])) {
							$value = ($userleaves[$leavetype['leave_typeID']])-($leavetype['no_of_days']);
							$this->_db->update($this->_userleaves,array('no_of_leave'=>$value),array('user_id=?'=>$reqDetail['user_id'],'leave_id=?'=>$leavetype['leave_typeID']));
						}
					}
				}
			}
			
			return ($update) ? TRUE : FALSE;
		}
		else {
			return FALSE;
		}
	}
	
	public function getRequestedLeaveType($data=array()) {
		$RequestID = (isset($data['RequestID'])) ? trim($data['RequestID']) : 0;
		$user_id   = (isset($data['user_id']))   ? trim($data['user_id'])   : trim($_SESSION['AdminLoginID']);
		
		$select =  $this->_db->select()
	     				->from(array('LRA'=>$this->_requesttype),'*')
						->where("LRA.request_id=".$RequestID); //echo $select->__tostring();die;	
						
		return $this->getAdapter()->fetchAll($select);
	}
	
	public function getLoanRequest(){
	    $select =  $this->_db->select()
	     				->from(array('LR'=>'loan_request'),array('*'))
						->where("LR.user_id='".$_SESSION['AdminLoginID']."'");
		$result = $this->getAdapter()->fetchAll($select);	
		return $result;
	 }
	
	public function ApplyLoan(){
	  $select =  $this->_db->select()
	     				->from(array('EPD'=>'employee_personaldetail'),array('*'))
						->joinleft(array('PEPD'=>'employee_personaldetail'),"PEPD.user_id=EPD.user_id",array('parent_mail'=>'email'))
						->where("EPD.user_id='".$_SESSION['AdminLoginID']."'");
		$result = $this->getAdapter()->fetchRow($select);				
	   $data_arr = array('user_id'=>$_SESSION['AdminLoginID'],'loan_amount'=>$this->_getData['loan_amount'],'apply_date'=>new Zend_Db_Expr('NOW()'),'status'=>1,'request_to'=>$result['parent_id']);
	   $this->_db->insert('loan_request',array_filter($data_arr));
	}
   public function getRequestedLeaveDetail(){
   
      $where = '1';
	  
	 if($_SESSION['AdminLoginID']!=1){
	     $where .=" AND approval_user_id='".$_SESSION['AdminLoginID']."'";
	 }
     $select = $this->_db->select()
	  			->from(array('ELA'=>'emp_leave_approval'),array('DISTINCT(user_id)'))
				->where($where);
	 $approvaluserlist = $this->getAdapter()->fetchAll($select);
	 $leaverequest = array();
	 foreach($approvaluserlist as $request){
	   $chiledauthority = $this->getLeavePreviusAuthority($request['user_id']);
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
				->order("leave_from DESC");
                              //if($request['user_id']==32){
				//echo $select->__tostring();die;
                              //}
	     $result =  $this->getAdapter()->fetchAll($select);
		 
		 if(!empty($result) && count($result)>1){
		    foreach($result as $request){
			  $leaverequest[] = $request;
			}
		 }elseif(!empty($result)){
		     $leaverequest[] = $result[0]; 
		 } 
	 }//print_r($leaverequest);die;
	 $leavdate = array();
	 foreach($leaverequest as $key=>$leavereques){
	   $leavdate[$key] = $leavereques['leave_from'];
	 }
	 array_multisort($leavdate,SORT_DESC,$leaverequest);
	 return $leaverequest;
	}
	
   public function getRequestDetailOfUser(){
        $select =  $this->_db->select()
	     				->from(array('LR'=>'leaverequests'),array('*'))
						->joinleft(array('LRT'=>'leaverequesttype'),"LRT.request_id=LR.request_id",array('*'))
						->where("LR.request_id='".$this->_getData['request_id']."'");
		$result = $this->getAdapter()->fetchAll($select);
		foreach($result as $detail){
		   $recdata['subject'] = $detail['subject'];
		   $recdata['contents'] = $detail['contents'];
		   $recdata['leave_from'] = $detail['leave_from'];
		   $recdata['leave_to'] = $detail['leave_to'];
		   $recdata['approved_approval'] = $detail['approved_approval'];
		   $recdata['rejected_approval'] = $detail['rejected_approval'];
		   $recdata['leave_types'][$detail['leave_typeID']] = $detail['no_of_days'];
		}
		return $recdata;	
   }
   public function ApproveRejectLeave(){
         if(in_array($_SESSION['AdminLoginID'],explode(',',$this->_getData['approved_approval']))){
	        $approveby = $this->_getData['approved_approval'];
	        //$rejectby = $this->_getData['rejected_approval'];
	    }else{
		    $approveby = $this->_getData['approved_approval'].','.$_SESSION['AdminLoginID'];
	        //$rejectby = $this->_getData['rejected_approval'].','.$_SESSION['AdminLoginID'];
         }
		 if(in_array($_SESSION['AdminLoginID'],explode(',',$this->_getData['rejected_approval']))){
	        //$approveby = $this->_getData['approved_approval'];
	        $rejectby = $this->_getData['rejected_approval'];
	    }else{
		   // $approveby = $this->_getData['approved_approval'].','.$_SESSION['AdminLoginID'];
	        $rejectby = $this->_getData['rejected_approval'].','.$_SESSION['AdminLoginID'];
         }
	      $auth = $this->getFinalApprovalAuth();
          if($auth || $_SESSION['AdminLoginID']==1){
		        $data = array('approved_approval'=>$approveby,'remarks'=>$this->_getData['remarks'],'final_approval'=>'1');
			    $datareject = array('rejected_approval'=>$rejectby,'remarks'=>$this->_getData['remarks']);
				$this->sendMailLeaverequest(2);
			// $totalleave1 =
			$leavestypes = $this->getOriginalLeaveTypes();//print_r($this->_getData);die; 
			$empleaves = $this->getRestLeaveOfUser($this->_getData);
			foreach($leavestypes as $leave){
			   $leve_id = $leave['typeID'];
			   $restleve = $empleaves[$leve_id]-$this->_getData['leaveDays_'.$leve_id];
			   $this->_db->update('emp_leaves',array('no_of_leave'=>$restleve),"user_id='".$this->_getData['UserID']."' AND leave_id='".$leve_id."'");
			}
			 //$this->_db->update('emp_leaves',array(),"");  
		  }else{
		    $data = array('approved_approval'=>$approveby,'remarks'=>$this->_getData['remarks']); 
		    $datareject = array('rejected_approval'=>$rejectby,'remarks'=>$this->_getData['remarks']);
			$this->sendMailLeaverequest(2);
		  }
		  
		if(!empty($this->_getData['Approve'])){
		    $this->_db->update("leaverequests",$data,"request_id='".$this->_getData['request_id']."'");
			$_SESSION[SUCCESS_MSG] = 'Leave Approved Successfully';
		}elseif(!empty($this->_getData['Reject'])){
		   $this->_db->update("leaverequests",$datareject,"request_id='".$this->_getData['request_id']."'");
                   $this->sendRejectLeaveMail();
		   $_SESSION[SUCCESS_MSG] = 'Leave Rejected Successfully';
		}  
   }
   public function getOriginalLeaveTypes(){
      $select =  $this->_db->select()
	     				->from(array('LR'=>'leavetypes'),array('*'));
	  $result = $this->getAdapter()->fetchAll($select);
		return $result;
   }
    public function LeaveApprovedByUsers($user_id){
    $list = '';
    if(!empty($user_id)){
	  $explodeusers = explode(',',$user_id);
	  foreach($explodeusers as $user){
	       $select = $this->_db->select()
	  			->from(array('ED'=>'employee_personaldetail'),array(''))
				->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_code'))
				->where("ED.user_id='".$user."'");
	     $result =  $this->getAdapter()->fetchRow($select);
		 if(!empty($result)){
		    $list .= $result['designation_code'].',';
		 }
	  }
	 
	} 
	 return $list;
  }
  public function getFinalApprovalAuth(){
	     $select = $this->_db->select()
	  			->from(array('ELA'=>'emp_leave_approval'),array('*'))
				->where("ELA.user_id='".$this->_getData['UserID']."'")
				->order("ELA.position DESC")
				->limit(1);
	   $result =  $this->getAdapter()->fetchRow($select);
	if($result['approval_user_id']==$_SESSION['AdminLoginID'] || $result['approval_user_id']==$this->_getData['receiver']){
	  return true;
	}else{
	  return false; 
	}
  }
   public function getLeavePreviusAuthority($user_id){
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
  public function sendMailLeaverequest($id){
	   switch($id){
	       case 1:
                     $select = $this->_db->select()
                            ->from(array('ELA'=>'emp_leave_approval'),array(''))
                            ->joinleft(array('EPD'=>'employee_personaldetail'),"ELA.approval_user_id=EPD.user_id",array('EPD.email AS Appemail','CONCAT(EPD.first_name," ",EPD.last_name) AS Receiver'))
                            ->joinleft(array('AEPD'=>'employee_personaldetail'),"AEPD.user_id=ELA.user_id",array('AEPD.email as Senderemail','CONCAT(AEPD.first_name," ",AEPD.last_name) AS Sender'))
                            ->where("ELA.user_id='".$_SESSION['AdminLoginID']."'")
                            ->order("ELA.position ASC")
                            ->limit(1);
                    //echo $select->__toString();die;
             $result =  $this->getAdapter()->fetchRow($select);
                Bootstrap::$Mail->_DataArray = array('SenderEmail'	=>$result['Senderemail'],
		   										'SenderName'	=>$result['Sender'],
												'ReceiverEmail'	=>$result['Appemail'],
												'ReceiverName'	=>$result['Receiver'],
                                                                                                'CC'	=>  array('info@jclifecare.com'),
												'Subject'		=>$this->_getData['subject'],
												'Body'			=>$this->_getData['contents'],
												'Attachment'	=>'');
		        Bootstrap::$Mail->SetEmailData();
		     break;
		   case 2:
                          $select = $this->_db->select()
                                                    ->from(array('LR'=>'leaverequests'),array('*'))
                                                    ->joinleft(array('EPD'=>'employee_personaldetail'),"EPD.user_id=LR.user_id",array('EPD.email AS Ruser','CONCAT(EPD.first_name," ",EPD.last_name) AS Rname'))
                                                    ->where("request_id='".$this->_getData['request_id']."'");
                         $leavedetail =  $this->getAdapter()->fetchRow($select);
                         $cc[] =  'info@jclifecare.com';
                         $cc[] =  $leavedetail['Ruser'];
                         $select = $this->_db->select()
	  			->from(array('EA'=>'emp_leave_approval'),array(''))
				->joinleft(array('EAC'=>'emp_leave_approval'),"EAC.user_id=EA.user_id",array(''))
                                 ->joinleft(array('AEPD'=>'employee_personaldetail'),"AEPD.user_id=EAC.approval_user_id",array('AEPD.email as receiveremail','CONCAT(AEPD.first_name," ",AEPD.last_name) AS Sender'))
				->where("EA.approval_user_id='".$_SESSION['AdminLoginID']."' AND EA.position>EAC.position AND EA.user_id='".$leavedetail['user_id']."'")
                                ->order("EAC.position DESC")
				->limit(1);
				//echo $select->__toString();//die;
                        $previusapproval =  $this->getAdapter()->fetchRow($select);
                        if(!empty($previusapproval['receiveremail'])){
                             $cc[] =  $leavedetail['receiveremail'];
                        }

                        $select = $this->_db->select()
	  			->from(array('EA'=>'emp_leave_approval'),array(''))
				->joinleft(array('EAC'=>'emp_leave_approval'),"EAC.user_id=EA.user_id",array(''))
                                 ->joinleft(array('AEPD'=>'employee_personaldetail'),"AEPD.user_id=EAC.approval_user_id",array('AEPD.email as receiveremail','CONCAT(AEPD.first_name," ",AEPD.last_name) AS Sender'))
				->where("EA.approval_user_id='".$_SESSION['AdminLoginID']."' AND EA.position < EAC.position AND EA.user_id='".$leavedetail['user_id']."'")
                                ->order("EAC.position DESC")
				->limit(1);
				//echo $select->__toString();die;
                        $nextapproval =  $this->getAdapter()->fetchRow($select);
                         if(!empty($previusapproval['receiveremail'])){
                             $cc[] =  $nextapproval['receiveremail'];
                        }
			
                 Bootstrap::$Mail->_DataArray = array('SenderEmail'	=>$result['Senderemail'],
                                                    'SenderName'	=>$result['Sender'],
                                                    'ReceiverEmail'	=>$result['Appemail'],
                                                    'ReceiverName'	=>$result['Receiver'],
                                                    'CC'	=>  $cc,
                                                    'Subject'		=>$this->_getData['subject'],
                                                    'Body'	 => ($this->_getData['remarks']!='')?$this->_getData['remarks']:$this->_getData['contents'],
                                                    'Attachment'	=>'');//print_r( Bootstrap::$Mail->_DataArray);die;
		        Bootstrap::$Mail->SetEmailData();
		   	 break;
		   case 3:
                        $select = $this->_db->select()
								->from(array('LR'=>'leaverequests'),array('*'))
								->where("request_id='".$this->_getData['request_id']."'");
			 $leavedetail =  $this->getAdapter()->fetchRow($select);
			 $select = $this->_db->select()
								->from(array('ELA'=>'emp_leave_approval'),array(''))
								->joinleft(array('EPD'=>'employee_personaldetail'),"EPD.user_id='".$_SESSION['AdminLoginID']."'",array('EPD.email AS Appemail','CONCAT(EPD.first_name," ",EPD.last_name) AS Sender'))
								->joinleft(array('AEPD'=>'employee_personaldetail'),"AEPD.user_id=ELA.user_id",array('AEPD.email','CONCAT(AEPD.first_name," ",AEPD.last_name) AS Sender'))
								->where("ELA.user_id='".$_SESSION['AdminLoginID']."'")
								->order("ELA.position ASC")
								->limit(1);
                    //echo $select->__toString();die;
                 $result =  $this->getAdapter()->fetchRow($select);
                         Bootstrap::$Mail->_DataArray = array('SenderEmail'	=>$result['Senderemail'],
                                                    'SenderName'	=>$result['Sender'],
                                                    'ReceiverEmail'	=>$result['Appemail'],
                                                    'ReceiverName'	=>$result['Receiver'],
                                                    'CC'	=>  array('info@jclifecare.com'),
                                                    'Subject'		=>$this->_getData['subject'],
                                                    'Body'	 => ($this->_getData['remarks']!='')?$this->_getData['remarks']:$this->_getData['contents'],
                                                    'Attachment'	=>'');
		        Bootstrap::$Mail->SetEmailData();
		      break;	 
	   }
	   
	    Bootstrap::$Mail->_DataArray = array('SenderEmail'	=>$result['Senderemail'],
                                                    'SenderName'	=>$result['Sender'],
                                                    'ReceiverEmail'	=>'info@jclifecare.com',
                                                    'ReceiverName'	=>'Administrator',
                                                    'Subject'		=>$this->_getData['subject'],
                                                    'Body'	 => ($this->_getData['remarks']!='')?$this->_getData['remarks']:$this->_getData['contents'],
                                                    'Attachment'	=>'');
	   Bootstrap::$Mail->SetEmailData();
  }
  
  public function MailForLeaverequest($requestID,$emailleave){
       $select = $this->_db->select()
                            ->from(array('ELA'=>'employee_personaldetail'),array('email','CONCAT(first_name," ",last_name) AS Sender','*'))
							->joininner(array('DG'=>'designation'),'DG.designation_id=ELA.designation_id',array('designation_name'))
							->joinleft(array('EL'=>'emp_locations'),'EL.user_id=ELA.user_id',array(''))
							->joinleft(array('HQ'=>'headquater'),'HQ.headquater_id=EL.headquater_id',array('headquater_name'))
                            ->where("ELA.user_id='".$_SESSION['AdminLoginID']."'");
                    //echo $select->__toString();die;
       $senderdetail =  $this->getAdapter()->fetchRow($select);
       
	   $select = $this->_db->select()
                            ->from(array('ELA'=>'emp_leave_approval'),array('user_id'))
                            ->joinleft(array('EPD'=>'employee_personaldetail'),"ELA.approval_user_id=EPD.user_id",array('EPD.email AS Appemail','CONCAT(EPD.first_name," ",EPD.last_name) AS Receiver'))
                            ->where("ELA.user_id='".$_SESSION['AdminLoginID']."'")
                            ->order("ELA.position ASC");
                    //echo $select->__toString();die;
       $receivers =  $this->getAdapter()->fetchAll($select);
	   $ccarray = array();
	   for($i=0;$i<count($receivers);$i++){
	      if($receivers[$i]['Appemail']!=''){
		  $ccarray[] = $receivers[$i]['Appemail'];}
	   }
	   $select = $this->_db->select()
                            ->from(array('ELA'=>'employee_personaldetail'),array('ELA.user_id','ELA.email as pemail','CONCAT(ELA.first_name," ",ELA.last_name) AS Receiver'))
                            ->joininner(array('EPD'=>'employee_personaldetail'),"ELA.user_id=EPD.parent_id",array(''))
                            ->where("EPD.user_id='".$_SESSION['AdminLoginID']."'");
                    //echo $select->__toString();die;
       $parentreceiver =  $this->getAdapter()->fetchRow($select);
	   $receiveremail  = (!empty($receivers[0]['Appemail']))?$receivers[0]['Appemail']:$parentreceiver['pemail'];
	   $receivername  = (!empty($receivers[0]['Receiver']))?$receivers[0]['Receiver']:$parentreceiver['Receiver'];
	   unset($ccarray[0]);
	   
	   $template = '<p>Name:[NAME]</p>
					<p>Designation:[DES]</p>
					<p>Headquater:[HQ]</p>
					<p>Leave Type: [LEAVETYPE]</p>
					<p>Has applied for leave from [FROM] to [TO].</p>
					<p>Msg:[MSG].</p>
					<p>&nbsp;</p>
					<p>[APPROVE] or [REJECT]</p>';
		$searchArr = array('[NAME]','[DES]','[HQ]','[LEAVETYPE]','[FROM]','[TO]','[APPROVE]','[REJECT]','[MSG]');
		$receiver_userid = ($receivers[0]['Appemail']['user_id']>0)?$receivers[0]['Appemail']['user_id']:$parentreceiver['user_id'];
		$approvelink = '<a href="'.Bootstrap::$baseUrl.'Requestmanager/acceptrejectbymail?type=1&sender='.$senderdetail['user_id'].'&receiver='.$receiver_userid.'&id='.$requestID.'">Accept</a>';
		
		$rejectlink = '<a href="'.Bootstrap::$baseUrl.'Requestmanager/acceptrejectbymail?type=0&sender='.$senderdetail['user_id'].'&receiver='.$receiver_userid.'&id='.$requestID.'">Reject</a>';
		
		$replace = array($senderdetail['Sender'],$senderdetail['designation_name'],$senderdetail['headquater_name'],$emailleave,$this->_getData['leave_from'],$this->_getData['leave_to'],$approvelink,$rejectlink,$this->_getData['contents']);	
		
		$emailBody = str_replace($searchArr,$replace,$template);		
	   //print_r($emailBody);die;
		Bootstrap::$Mail->_DataArray = array('SenderEmail'	=>$senderdetail['email'],
										'SenderName'	=>$senderdetail['Sender'],
										'ReceiverEmail'	=>$receiveremail,//'asanjeevsoftdot@gmail.com',
										'ReceiverName'	=>$receivername,
										 'CC'	=>  $ccarray,
										'Subject'		=>'Leave request|'.$this->_getData['subject'],
										'Body'			=>$emailBody,
										'Attachment'	=>'');
										//print_r(Bootstrap::$Mail->_DataArray);die;
		Bootstrap::$Mail->SetEmailData();
		return true;
  }

  public function sendRejectLeaveMail(){
        $select = $this->_db->select()
                            ->from(array('ELA'=>'employee_personaldetail'),array('ELA.email as Senderemail','CONCAT(ELA.first_name," ",ELA.last_name) AS Sender'))
                            ->where("ELA.user_id='".$_SESSION['AdminLoginID']."'");

         $senders =  $this->getAdapter()->fetchRow($select);

        $select = $this->_db->select()
                            ->from(array('LR'=>'leaverequests'),array('*'))
                            ->joinleft(array('EPD'=>'employee_personaldetail'),"LR.user_id=EPD.user_id",array('EPD.email AS Appemail','CONCAT(EPD.first_name," ",EPD.last_name) AS Receiver'))
                            ->where("request_id='". $this->_getData['request_id']."'");

         $receiovers =  $this->getAdapter()->fetchRow($select);

          Bootstrap::$Mail->_DataArray = array('SenderEmail'	=>$senders['Senderemail'],
                                                'SenderName'	=>$senders['Sender'],
                                                'ReceiverEmail'	=>$receiovers['Appemail'],
                                                'ReceiverName'	=>$receiovers['Receiver'],
                                                'CC'            =>  array('info@jclifecare.com'),
                                                'Subject'	=>$this->_getData['subject'],
                                                'Body'           => ($this->_getData['remarks']!='')?$this->_getData['remarks']:$this->_getData['contents'],
                                                'Attachment'	=>'');
            Bootstrap::$Mail->SetEmailData();
  }
  
  public function EmailApproveRejectLeave(){ 
      	 $this->_getData['UserID'] = $this->_getData['sender'];
		 //$this->_getData['user_id'] = $this->_getData['sender'];
         $select = $this->_db->select()
                            ->from(array('LR'=>'leaverequests'),array('*'))
                            ->where("request_id='". $this->_getData['id']."'");

         $receiovers =  $this->getAdapter()->fetchRow($select);
		 
		 $select = $this->_db->select()
                            ->from(array('LT'=>'leaverequesttype'),array('*'))
                            ->where("request_id='". $this->_getData['id']."'");

         $leavereuested =  $this->getAdapter()->fetchAll($select);
		 
		 $leaves = array();
		 foreach($leavereuested as $leavesr){
		     $leaves[$leavesr['leave_typeID']] = $leavesr['no_of_days'];
		 }
        
         if(in_array($this->_getData['receiver'],explode(',',$receiovers['approved_approval'])) && $this->_getData['type']==1){
	        $approveby = $this->_getData['receiver'];
	    }else{
		    $approveby = $receiovers['approved_approval'].','.$this->_getData['receiver'];
         }
		 if(in_array($this->_getData['receiver'],explode(',',$receiovers['rejected_approval'])) && $this->_getData['type']==0){
	        $rejectby = $receiovers['rejected_approval'];
	    }else{
	        $rejectby = $receiovers['rejected_approval'].','.$this->_getData['receiver'];
         }
		 
	      $auth = $this->getFinalApprovalAuth();
          if($auth || $this->_getData['receiver']==1 || $this->_getData['receiver']==44  || $this->_getData['receiver']==139){
		        $data = array('approved_approval'=>$approveby,'remarks'=>$this->_getData['remarks'],'final_approval'=>'1');
			    $datareject = array('rejected_approval'=>$rejectby,'remarks'=>$this->_getData['remarks']);
			//$this->sendMailLeaverequest(2);
			// $totalleave1 =
			$leavestypes = $this->getOriginalLeaveTypes();//print_r($this->_getData);die; 
			$empleaves = $this->getRestLeaveOfUser($this->_getData);
			
			foreach($leavestypes as $leave){
			   $leve_id = $leave['typeID'];
			   $restleve = $empleaves[$leve_id]-$leaves[$leve_id];
			   //$this->_db->update('emp_leaves',array('no_of_leave'=>$restleve),"user_id='".$this->_getData['UserID']."' AND leave_id='".$leve_id."'");
			}
			
			 //$this->_db->update('emp_leaves',array(),"");  
		  }else{
		    $data = array('approved_approval'=>$approveby,'remarks'=>$this->_getData['remarks']); 
		    $datareject = array('rejected_approval'=>$rejectby,'remarks'=>$this->_getData['remarks']);
			//$this->sendMailLeaverequest(2);
		  }
		 print_r($this->_getData);die; 
		if(!empty($this->_getData['type']) || $this->_getData['type']==1){
		    $this->_db->update("leaverequests",$data,"request_id='".$this->_getData['id']."'");
			$_SESSION[SUCCESS_MSG] = 'Leave Approved Successfully';
		}elseif(!empty($this->_getData['type']) || $this->_getData['type']==1){
		   $this->_db->update("leaverequests",$datareject,"request_id='".$this->_getData['id']."'");
           //$this->sendRejectLeaveMail();
		   $_SESSION[SUCCESS_MSG] = 'Leave Rejected Successfully';
		}  
   }
}
?>