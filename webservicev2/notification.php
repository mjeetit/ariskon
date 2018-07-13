<?php
header('Content-type: application/json');
$mode = isset($_REQUEST['Mode'])?$_REQUEST['Mode']:'';
$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
include 'common_class.php';
class Notification extends commonclass{
    public function NotificationList($user_id){
	      $returndata = array();
		  $execute = $this->query("SELECT NT.*,UT.first_name FROM app_notifications NT INNER JOIN employee_personaldetail UT ON NT.user_id=UT.user_id WHERE end_date>=CURDATE() AND find_in_set(".$user_id.",NT.visible_userid)");
	      $notifications = $this->fetchAll($execute);
		  foreach($notifications as $notification){
				$data['Notification'] = $notification['notific_desc'];
				$data['BY'] = $notification['first_name'];
				$data['Start'] = $notification['start_date'];
				$data['End'] = $notification['end_date'];
				$data['NI'] = $notification['notification_id'];
				$returndata[] = $data;
	  		}
		  $execute = $this->query("SELECT NT.*,UT.first_name FROM notification NT LEFT JOIN employee_personaldetail UT ON NT.created_by=UT.user_id WHERE (NT.created_by='".$user_id."' OR find_in_set(".$user_id.",NT.user_id)) AND NT.notification_date > CURDATE( ) - INTERVAL 7 DAY");
	      $notifications1 = $this->fetchAll($execute);
		  foreach($notifications1 as $notification){
			$data['Notification'] = str_replace('&nbsp;','',strip_tags($notification['description']));
			$data['BY'] = ($notification['created_by']==1)?'Admin':$notification['first_name'];
			$data['Start'] = $notification['notification_date'];
			$data['End'] = $notification['notification_date'];
			$data['NI'] = $notification['notification_id'];
			$returndata[] = $data;
	     }
		 if(date('d')>15 && date('d')<30){
	      $execute = $this->query("SELECT COUNT( 1 ) AS CNT FROM app_tourplan WHERE user_id ='".$user_id."' AND DATE_FORMAT( tour_date,  '%Y-%m' ) = DATE_FORMAT( DATE_ADD( CURDATE( ) , INTERVAL 1 MONTH ) ,  '%Y-%m' )");
	       $result = $this->fetchRow($execute);
			if($result['CNT']<=0){
				 $data['Notification'] = 'The next month tour plan not submitted yet.Kindly submit your tour plan.';
				$data['BY'] = 'Admin';
				$data['End'] = date('d-m-Y');
				$returndata[] = $data;
			 }
	  }
	  
	     if(date('d')>20 && date('d')<30){
			  $execute = $this->query("SELECT COUNT( 1 ) AS CNT FROM app_tourplan WHERE user_id ='".$user_id."' AND DATE_FORMAT( tour_date,  '%Y-%m' ) = DATE_FORMAT( DATE_ADD( CURDATE( ) , INTERVAL 1 MONTH ) ,  '%Y-%m' ) AND status='1'");
				   $result = $this->fetchRow($execute);
				  if($result['CNT']>0){
					 $data['Notification'] = 'Your next month tour plan has been approved';
					$data['BY'] = 'Admin';
					$data['End'] = date('d-m-Y');
					$returndata[] = $data;
				  }
	  }
	  
	  return $returndata;
  }
  public function Tourplanlist($user_id){
         $returndata = array();
		 $belists = $this->getBElist($user_id);
		 $belist = $this->getsinglrArr($belists,'user_id',array());
		 $execute = $this->query("SELECT ED.first_name,ED.last_name,TP.status FROM employee_personaldetail ED LEFT JOIN app_tourplan TP ON TP.user_id=ED.user_id WHERE DATE_FORMAT( TP.tour_date,  '%Y-%m' ) = DATE_FORMAT( DATE_ADD( CURDATE( ) , INTERVAL 1 MONTH ) ,  '%Y-%m' ) AND TP.user_id IN('".implode("','",$belist)."')");
	      $tourlist = $this->fetchAll($execute);
		   foreach($tourlist as $result){
		      $data['Emp'] = $result['first_name'].' '.$result['last_name'];
			 if($result['status']=='1'){
				$data['Status'] = 'Approved';
			 }elseif($result['status']=='0'){
				$data['Status'] = 'Pending';
			 }else{
				$data['Status'] = 'Not Submit';
			 }
			 $returndata[] = $data;
		   }
		return $returndata;    
  } 
  
  public function AddNotification($requestdata){
        $alllist = array();
	    $notification_text = isset($requestdata['notification_text'])?$requestdata['notification_text']:'';
		$cb_zbmcheck = isset($requestdata['cb_zbmcheck'])?$requestdata['cb_zbmcheck']:'';
		$cb_rbmcheck = isset($requestdata['cb_rbmcheck'])?$requestdata['cb_rbmcheck']:'';
		$cb_abmcheck = isset($requestdata['cb_abmcheck'])?$requestdata['cb_abmcheck']:'';
		$cb_becheck = isset($requestdata['cb_becheck'])?$requestdata['cb_becheck']:'';
		$user_id = isset($requestdata['user_id'])?$requestdata['user_id']:''; 
		if($cb_becheck=="1"){
			$allusers = $this->getBElist($user_id);$user_id = isset($requestdata['user_id'])?$requestdata['user_id']:'';
			$alllist = $this->getsinglrArr($allusers,'user_id',array());
		}
		if($cb_abmcheck=="1"){
			$allusers = $this->getABMlist($user_id);
			$alllist = $this->getsinglrArr($allusers,'user_id',$alllist);
		}
		if($cb_rbmcheck=="1"){
			$allusers = $this->getRBMlist($user_id);
			$alllist = $this->getsinglrArr($allusers,'user_id',$alllist);
		}
		if($cb_zbmcheck=="1"){
			$allusers = $this->getZBMlist($user_id);
			$alllist = $this->getsinglrArr($allusers,'user_id',$alllist);
		}
		
		$execute = $this->query("INSERT INTO app_notifications SET notific_desc='".$notification_text."',user_id='".$user_id."',start_date=CURDATE(),end_date=DATE_ADD( CURDATE( ) , INTERVAL 3 DAY ) ,visible_userid='".implode(',',$alllist)."'");
  }
   public function AddNotification_new($requestdata){
        $alllist = array();
	    $notification_text = isset($requestdata['notification_text'])?$requestdata['notification_text']:'';
		$cb_zbmcheck = isset($requestdata['cb_zbmcheck'])?$requestdata['cb_zbmcheck']:'';
		$cb_rbmcheck = isset($requestdata['cb_rbmcheck'])?$requestdata['cb_rbmcheck']:'';
		$cb_abmcheck = isset($requestdata['cb_abmcheck'])?$requestdata['cb_abmcheck']:'';
		$cb_becheck = isset($requestdata['cb_becheck'])?$requestdata['cb_becheck']:'';
		$user_id = isset($requestdata['user_id'])?$requestdata['user_id']:'';
		
		$dataArr = json_decode($requestdata['users']);
		foreach($dataArr as $user){
		  $alllist[] = $user->users;
		}
		$execute = $this->query("INSERT INTO app_notifications SET notific_desc='".$notification_text."',user_id='".$user_id."',start_date=CURDATE(),end_date=DATE_ADD( CURDATE( ) , INTERVAL 3 DAY ) ,visible_userid='".implode(',',$alllist)."'");
  }
  public function getallUsers($request){
  			$user_id = isset($request['user_id'])?$request['user_id']:''; 
			$allbe = $this->getBElist($user_id);
			//$alllist = $this->getsinglrArr($allbe,'user_id',array());
			$allabm = $this->getABMlist($user_id);
			//$alllist = $this->getsinglrArr($allusers,'user_id',$alllist);
			$allrbm = $this->getRBMlist($user_id);
			//$alllist = $this->getsinglrArr($allusers,'user_id',$alllist);
			$allzbm = $this->getZBMlist($user_id);
			//$alllist = $this->getsinglrArr($allusers,'user_id',$alllist);echo '<pre>';print_r($allusers);die;
			$alluser = array_merge($allbe,$allabm,$allrbm,$allzbm);
			$alllist = $this->getsinglrArr($alluser,'user_id',array());
			$returndata = array();
			$execute = $this->query("SELECT UD.user_id FROM employee_personaldetail UD INNER JOIN employee_personaldetail UD1 ON UD.parent_id=UD1.parent_id AND UD.designation_id=UD1.designation_id WHERE UD1.user_id='".$user_id."' AND UD.user_id!='".$user_id."'");
			 $employeelist = $this->fetchAll($execute);
			 $allusers = $this->getsinglrArr($employeelist,'user_id',$alllist);
			 $execute = $this->query("SELECT UD.user_id,UD.first_name,UD.last_name,DT.designation_code FROM employee_personaldetail UD LEFT JOIN designation DT ON UD.designation_id=DT.designation_id WHERE UD.user_id IN(".implode(',',$allusers).")");
			 $employeelists = $this->fetchAll($execute);
			 foreach($employeelists as $employeelist){
			   $User['User'] = $employeelist['user_id'];
			   $User['Name'] = $employeelist['designation_code'].'-'.$employeelist['first_name'].' '.$employeelist['last_name'];
			   $returndata[] = $User;
			 }
		   $User['User'] = 44;
		   $User['Name'] = 'HO';
		   $returndata[] = $User;
		return $returndata;	
			
  }
  public function NotificationSent($user_id){
	      $returndata = array();
		  $execute = $this->query("SELECT NT.*,UT.first_name FROM app_notifications NT INNER JOIN employee_personaldetail UT ON NT.user_id=UT.user_id WHERE NT.user_id='".$user_id."' ORDER BY start_date DESC");
	      $notifications = $this->fetchAll($execute);
		  foreach($notifications as $notification){
				$data['Notification'] = $notification['notific_desc'];
				$data['BY'] = $this->SentTo($notification['visible_userid']);
				$data['Start'] = $notification['start_date'];
				$data['End'] = $notification['start_date'];
				$returndata[] = $data;
	  		}
		  /*$execute = $this->query("SELECT NT.*,UT.first_name FROM notification NT INNER JOIN employee_personaldetail UT ON NT.created_by=UT.user_id WHERE NT.created_by='".$user_id."'");
	      $notifications1 = $this->fetchAll($execute);
		  foreach($notifications1 as $notification){
			$data['Notification'] = str_replace('&nbsp;','',strip_tags($notification['description']));
			$data['BY'] = $notification['first_name'];
			$data['Start'] = $notification['notification_date'];
			$data['End'] = $notification['notification_date'];
			$returndata[] = $data;
	     }*/
	  return $returndata;
  }
  public function SentTo($sentto){
	      $returndata = array();
		  $execute = $this->query("SELECT UT.first_name,DT.designation_code FROM employee_personaldetail UT INNER JOIN designation DT ON DT.designation_id=UT.designation_id WHERE UT.user_id IN(".$sentto.") ORDER BY designation_code ASC");
	      $senttousers = $this->fetchAll($execute);
		  $data = array();
		  foreach($senttousers as $senttouser){
				$data[] = $senttouser['designation_code'];
	  		}
	  return implode(',',$data);
  }
  public function NotificationReply($requestdata){
        $reply_text = isset($requestdata['reply_text'])?$requestdata['reply_text']:'';
		$notification_id = isset($requestdata['notification_id'])?$requestdata['notification_id']:'';
		$user_id = isset($requestdata['user_id'])?$requestdata['user_id']:'';
		$execute = $this->query("SELECT * FROM app_notifications WHERE notification_id='".$notification_id."'");
	    $notfidetail = $this->fetchRow($execute);
		$execute = $this->query("INSERT INTO app_notifications SET parent_id='".$notification_id."',notific_desc='".$reply_text."',user_id='".$user_id."',start_date=CURDATE(),end_date=DATE_ADD( CURDATE( ) , INTERVAL 3 DAY ) ,visible_userid='".$notfidetail['user_id']."'");
		
  }
  public function getEmaildata($user_id){
     $execute = $this->query("SELECT email,email_password FROM employee_personaldetail WHERE user_id='".$user_id."'");
	 return $this->fetchRow($execute);
  }
}
$nObject = new Notification();
switch($mode){
     case 'NotificationList':
		$newdata = $nObject->NotificationList($user_id);
	 break;
	  case 'Tourplanlist':
			 $newdata = $nObject->Tourplanlist($user_id);
	  break;
	  case 'AddNotification':
		   $newdata = $nObject->AddNotification($_REQUEST);
	 break;
	 case 'GetAllUsers':
		   $newdata = $nObject->getallUsers($_REQUEST);
	 break;
	 case 'NotificationSent':
	      $newdata = $nObject->NotificationSent($user_id);
	 break;
	 case 'ReplyNotification':
	      $newdata = $nObject->NotificationReply($_REQUEST);
	 break;
	 case 'Notificationcount':
	      $newdata = $nObject->NotificationList($user_id);
		  $emaildata = $nObject->getEmaildata($user_id);
		  echo json_encode(array('success'=>'YES','message'=>count($newdata),'emaillink'=>'http://webmail1.jclifecare.com/jclhrm.php?webemail='.$emaildata['email'].'&&webpass='.base64_encode($emaildata['email_password']).'')); exit;
	 break;
	
}
if(!empty($newdata)){
  echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
}else{
  echo json_encode(array('success'=>'NO','message'=>'There is no notifications yet')); exit;
}
?>