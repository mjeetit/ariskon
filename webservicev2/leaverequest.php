<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class LeaveRequest extends commonclass{
   public function getResponse($request){
       switch($request['mode']){
	      case 'LeaveApprove':
		    $response = $this->LeaveApprove($request);
		  break;
		  case 'LeaveRequest':
		    $response = $this->AddLeaveRequest($request);
		  break;
		  default:
		    $response = array('success'=>'NO','message'=>'Invalid Action!');
	   }
	  // echo json_encode($response);die;
   }
   
   public function LeaveApprove($request){
			$user_id = isset($request['user_id'])?$request['user_id']:0;
			$leave_id = isset($request['leaveid'])?$request['leaveid']:0;	
			$actionmode = isset($request['actionmode'])?$request['actionmode']:'';	
			$remark = isset($request['remark'])?$request['remark']:'';	
			
			$exceute = $this->query("SELECT * FROM leaverequests WHERE request_id='".$leave_id."'");
			$leaves = $this->fetchAll($exceute);
			if(in_array($user_id,explode(',',$leaves['approved_approval']))){
	        	$approveby = $leaves['approved_approval'];
			}else{
				$approveby =  $leaves['approved_approval'].','.$user_id; 
			 }
			 if(in_array($user_id,explode(',',$leaves['rejected_approval']))){
				$rejectby = $leaves['rejected_approval'];
			}else{
				$rejectby = $leaves['rejected_approval'].','.$user_id;
			 }
			if($actionmode=='Approved'){
			   $this->query("UPDATE leaverequests SET final_approval_auth='1',approved_approval='".$approveby."',remarks='".$remark."' WHERE request_id='".$leave_id."'");
			}elseif($actionmode=='Reject'){
			   $this->query("UPDATE leaverequests SET rejected_approval='".$rejectby."',remarks='".$remark."' WHERE request_id='".$leave_id."'");
			}
		 return array('success'=>'YES','message'=>'Action submitted successfully!');	
   }
   
   public function AddLeaveRequest($request){
         $user_id = isset($request['user_id'])?$request['user_id']:0;
     	 $leve_from = isset($request['leave_from'])?date('Y-m-d',strtotime($request['leave_from'])):'';
	     $leave_to = isset($request['leave_to'])?date('Y-m-d',strtotime($request['leave_to'])):'';
		 $leave = isset($request['type'])?trim($request['type']):'';
		 $leave_subject = isset($request['leave_subject'])?$request['leave_subject']:'';
		 $leave_message = isset($request['leave_message'])?$request['leave_message']:'';
	     $totalDays = (strtotime($leave_to) - strtotime($leve_from)) / (60 * 60 * 24) + 1;
		
		 $requesttypes = array();
		 if($leave=='CL'){
		    $requesttypes[1] = $totalDays;
		 }
		 if($leave=='SL'){
		    $requesttypes[2] = $totalDays;
		 }
		 if($leave=='PL'){
		    $requesttypes[3] = $totalDays;
		 }
		 if(count($requesttypes)>1){
		   echo json_encode(array('success'=>'NO','message'=>'You can not request more than one leave type at a time!'));exit;
		 }
		 if($totalDays>3 && $leave=='CL'){
		    echo json_encode(array('success'=>'NO','message'=>'You can not request casual leave more than 3!'));exit;
		 }
		 $limitdate = date('Y-m-d',mktime(0, 0, 0, date("m"),date("d")-20,  date("Y")));
		
		 if($leve_from< $limitdate){
		   echo json_encode(array('success'=>'NO','message'=>'You can not request leave more than 15 days back!'));exit;
		 }
		 if($leve_from> $leave_to){
		   echo json_encode(array('success'=>'NO','message'=>'To date always greater than from date!'));exit;
		 }
		 $empleave = $this->getEmployeeLeave($user_id);
	     $leaverequested = $this->getLastRequestLeave($user_id,$leve_from);
		  if(!empty($leaverequested)){
			$totalDays = $totalDays +1;
		  }
	  switch($leave){
	  		case 'CL':
			    if($totalDays>3){
				   echo json_encode(array('success'=>'NO','message'=>'You can not request casual leave more than 3!'));exit;
				}
				if($empleave[1]['no_of_leave']<$totalDays){
				 echo json_encode(array('success'=>'NO','message'=>'you can not request this leave more than '.$empleave[1]['no_of_leave'].' because you have not enough leave'));exit;
				}
				break;
			case 'SL':
				if($empleave[2]['no_of_leave']<$totalDays){
				   echo json_encode(array('success'=>'NO','message'=>'you can not request this leave more than '.$empleave[2]['no_of_leave'].' because you have not enough leave'));exit;
				}
				break;
			case 'PL':
				if($empleave[3]['no_of_leave']<$totalDays){
				   echo json_encode(array('success'=>'NO','message'=>'you can not request this leave more than '.$empleave[3]['no_of_leave'].' because you have not enough leave'));exit;
				}
				break;		
	  
	}
	//if(!empty($leaverequested)){
	     switch($leaverequested['leave_typeID']){
	  		case 1:
			    if($leave=='SL' || $leave=='PL'){
				   echo json_encode(array('success'=>'NO','message'=>''.$leave.' request can to be possible. because you have already taken casual leave few days before request date'));exit;
				}
				break;
			case 2:
				 if($leave=='CL' && $totalDays>1){
				   echo json_encode(array('success'=>'NO','message'=>'You have alredy taken sick leave.so can not request casual leave more than 1 again because sunday before from date will count in your leave days and in that case casual leave only one could be possible'));exit;
				}
				break;
			case 3:
			   if($leave=='SL' || $leave=='CL'){
				   echo json_encode(array('success'=>'NO','message'=>''.$leave.' request can to be possible. because you have already taken privillage leave few days before request date'));exit;
				}
				break;		
	  
	  }

	    $this->query("INSERT INTO leaverequests SET user_id='".$user_id."',leave_from='".$leve_from."',leave_to='".$leave_to."',total_days='".$totalDays."',subject='".$leave_message."',contents='".$leave_subject."',request_by='".$user_id."',request_date=NOW(),request_type='1'");
		 $request_id = mysql_insert_id();
		 foreach($requesttypes as $key=>$no_ofleave){
		     $this->query("INSERT INTO leaverequesttype SET request_id='".$request_id."',leave_typeID='".$key."',no_of_days='".$no_ofleave."',user_by='".$user_id."'");
			 
			 $this->query("UPDATE emp_leaves SET no_of_leave=no_of_leave-".$no_ofleave." WHERE user_id='".$user_id."' AND leave_id='".$key."'");
		 }
		
	 	   $emaildatas = $this->sendLeaveEmail($request);
		   $emaildata['Body'] = $leave_subject;
		   $emaildata['Subject'] = $leave_message;
		   $emaildata['ReceiverMail'] = $emaildatas['ReceiverMail'];
		   $emaildata['Receiver'] = $emaildatas['Receiver'];
		   $emaildata['SenderEmail'] = $emaildatas['SenderEmail'];
		   $emaildata['Sender'] = $emaildatas['Sender'];
		   //$this->sendMail($emaildata);
		  echo json_encode(array('success'=>'YES','message'=>'Leave Request Successfully!'));exit;	 	
   }
   
   public function getEmployeeLeave($user_id){
      $exceute = $this->query("SELECT EL.*,LT.typeName FROM emp_leaves EL INNER JOIN leavetypes LT ON LT.typeID=EL.leave_id WHERE EL.user_id='".$user_id."'");
	  $leaves = $this->fetchAll($exceute);
	  $leaveArr = array();
	  foreach($leaves as $leave){
	     $leaveArr[$leave['leave_id']] = $leave;
	  }
	return $leaveArr;
}
function getLastRequestLeave($user_id,$from){
  $this->query("SELECT LR.leave_from,LR.leave_to,LR.total_days,LT.request_id,LT.leave_typeID FROM leaverequests LR INNER JOIN leaverequesttype LT ON LR.request_id=LT.request_id 
  		  WHERE LR.user_id='".$user_id."' AND leave_to>=DATE_SUB('".$from."',INTERVAL 2 DAY)");
	  $leaveArr = $this->fetchRow($exceute);
	return $leaveArr;
  
}
   
   public function sendLeaveEmail($data){
   	  $exceute = $this->query("SELECT EP1.email AS ReceiverMail,EP1.first_name AS Receiver,EP.email AS SenderEmail,EP.first_name Sender FROM employee_personaldetail EP INNER JOIN employee_personaldetail EP1 ON EP.parent_id=EP1.user_id WHERE EP.user_id='".$data['user_id']."'");
	 $emaildata = $this->fetchRow($exceute);
   return $emaildata;
 } 
  
  public function sendMail($maildata=array()){
			require_once('class.phpmailer.php');
			$mail = new PHPMailer();
			date_default_timezone_set("Europe/Amsterdam");
			$mail->CharSet = 'utf-8';
			// START - Mail Function to Send From Server
			$mail->IsMail();
			$mail->IsHTML(true);
			$mail->MsgHTML($maildata['Body']);
			// END - Mail Function to Send From Server
			$mail->Subject = ($maildata['Subject']!='')?$maildata['Subject']:'Leave Request';
			$mail->AddAddress($maildata['ReceiverMail'],$maildata['ReceiverName']);
			$mail->AddCC('info@jclifecare.com', 'Request');
			$mail->SetFrom($maildata['SenderEmail'], $maildata['Sender']);
			$mail->Send();

  }

}
$obj = new LeaveRequest;
$obj->getResponse($_REQUEST);
?>