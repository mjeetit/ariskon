<?php
class commonclass{
     public function getChilduser($userids,$count){
	  $execute = $this->query("SELECT EL.user_id FROM employee_personaldetail EL
   		   WHERE EL.parent_id IN('".implode("','",$userids)."') AND EL.delete_status='0' AND designation_id IN(8,7,6,5)");
	   $userid = $this->fetchAll($execute);
	   $userids = $this->getsinglrArr($userid,'user_id',$userids);
	   if($count<4){
	   		$count = $count+1;
		    return $this->getChilduser($userids,$count);
	   }else{
	     return $userids;
	   }
	 }
	 public function getParentuser($userids,$count){
	  $execute = $this->query("SELECT EL.parent_id FROM employee_personaldetail EL WHERE EL.user_id IN('".implode("','",$userids)."') AND EL.delete_status='0' AND designation_id IN(8,7,6,5)");
	   $userid = $this->fetchAll($execute);
	   $userids = $this->getsinglrArr($userid,'parent_id',$userids);
	   if($count<4){
	   		$count = $count+1;
		    return $this->getParentuser($userids,$count);
	   }else{
	     return $userids;
	   }
	 }
	 public function getDesignation($user_id){
		$execute = $this->query("SELECT designation_id FROM employee_personaldetail EL WHERE EL.user_id='".$user_id."'");
	    $result = $this->fetchRow($execute);
	    return $result['designation_id'];
	 }
	
	 public function getBElist($user_id){
	   $designation = $this->getDesignation($user_id);
	   $childusers = $this->getChilduser(array($user_id),1);
		$belist = array();
		if($designation==8){
		   return array();
		}
		$execute = $this->query("SELECT EL.*,DG.designation_code,EH.headquater_id FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id WHERE EL.user_id IN(".implode(',',$childusers).") AND EL.designation_id=8 AND EL.delete_status='0' GROUP BY EL.user_id ORDER BY EL.first_name ASC");
	    $belist = $this->fetchAll($execute);
	    return $belist;
	 }
	public function getABMlist($user_id){
	    $designation = $this->getDesignation($user_id);
		$abmlist = array();
		if($designation==7){
		   return array();
		}
		if($designation<7){
		   $childusers = $this->getChilduser(array($user_id),1);
		}else{
		   $childusers = $this->getParentuser(array($user_id),1);
		}
		$execute = $this->query("SELECT EL.*,DG.designation_code,EH.headquater_id FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id WHERE EL.user_id IN(".implode(',',$childusers).") AND EL.designation_id=7 AND EL.delete_status='0' GROUP BY EL.user_id ORDER BY EL.first_name ASC");
	    $abmlist = $this->fetchAll($execute);
		 return $abmlist;
	}
	public function getRBMlist($user_id){
		$designation = $this->getDesignation($user_id);
		$rbmlist = array();
		if($designation==6){
		   return array();
		}
		if($designation<6){
		   $childusers = $this->getChilduser(array($user_id),1);
		}else{
		   $childusers = $this->getParentuser(array($user_id),1);
		}
		$execute = $this->query("SELECT EL.*,DG.designation_code,EH.headquater_id FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id WHERE EL.user_id IN(".implode(',',$childusers).") AND EL.designation_id=6 AND EL.delete_status='0' GROUP BY EL.user_id ORDER BY EL.first_name ASC");
	    $rbmlist = $this->fetchAll($execute);
		 return $rbmlist;
	}
	public function getZBMlist($user_id){
	    $designation = $this->getDesignation($user_id);
		$zbmlist = array();
		if($designation==5){
		   return array();
		}
		$childusers = $this->getParentuser(array($user_id),1);
		$execute = $this->query("SELECT EL.*,DG.designation_code,EH.headquater_id FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id WHERE EL.user_id IN(".implode(',',$childusers).") AND EL.designation_id=5 AND EL.delete_status='0' GROUP BY EL.user_id ORDER BY EL.first_name ASC");
	    $zbmlist = $this->fetchAll($execute);
		 return $zbmlist;
	}
	public function lastReportingDate($user_id){
		$execute = $this->query("SELECT MAX(call_date) as last_reportin FROM (SELECT MAX(call_date) AS call_date FROM app_doctor_visit WHERE user_id='".$user_id."'
				UNION
				SELECT MAX(call_date) AS call_date FROM app_chemist_visit WHERE user_id='".$user_id."'
				UNION
				SELECT MAX(meeting_date) AS call_date FROM app_meeting WHERE user_id='".$user_id."') AS max_call_date"); 
		$result = $this->fetchRow($execute);
		if(!empty($result) && strtotime($result['last_reportin'])<=strtotime(date('Y-m-d'))){
		   return $result['last_reportin'];
		}else{
		  return '0000-00-00';
		}
	
	}
	
	public function getdetail($user_id){
   		$execute = $this->query("SELECT EL.*,EP.designation_id,ER.region_name,EA.area_name,EH.headquater_name,EC.city_name,EP.first_name,EP.last_name,EP.email,EP.contact_number,EP.doj FROM employee_personaldetail EP 
   		   LEFT JOIN emp_locations EL ON EP.user_id=EL.user_id
		   LEFT JOIN region ER ON ER.region_id=EL.region_id
		   LEFT JOIN `area` EA ON EA.area_id=EL.area_id
		   LEFT JOIN headquater EH ON EH.headquater_id=EL.headquater_id
		   LEFT JOIN city EC ON EC.city_id=EL.city_id
		   WHERE EL.user_id='".$user_id."'"); 
	$result = $this->fetchRow($execute);
	$data = array();
	$data['User'] = ($result['user_id']>0)?$result['user_id']:'0';
	$data['Designation'] = ($result['designation_id']==40)?5:$result['designation_id'];
	$data['Name'] = $result['first_name'].' '.$result['last_name'];
	$data['Phone'] = $result['contact_number'];
	$data['Email'] = $result['email'];
	$data['Zone']  =  $result['zone_id'];
	$data['Region']= $result['region_id'];
	$data['Area']  = $result['area_id'];
	$data['Headquater'] = $result['headquater_id'];
	$data['City'] = $result['city_id'];
	$data['doj']  = $result['doj'];
	$data['LastReport'] = $this->lastReportingDate($data['User']);
	$data['AutoSync'] = 0;
   return $data;	
}
	public function getCondHeadquaterList($user_id){
	     $onclause = '';
		 switch($this->getDesignation($user_id)){
		   case 8:
		        $onclause = 'EL.headquater_id=HQ.headquater_id';
		   break;
		   case 7:
		        $onclause = 'EL.area_id=HQ.area_id';
		   break;
		   case 6:
		   		$onclause = 'EL.region_id=HQ.region_id';
		   break;
		   case 5:
		   		$onclause = 'EL.zone_id=HQ.zone_id';
		   break;
		 }
		 
		 $execute = $this->query("SELECT HQ.headquater_id FROM emp_locations EL INNER JOIN headquater HQ ON ".$onclause." WHERE EL.user_id='".$user_id."'");
	    $headquaters = $this->fetchAll($execute);
		$hq = array();
		foreach($headquaters as $headquater){
		  $hq[] = $headquater['headquater_id']; 
		}
		$execute = $this->query("SELECT EL.headquater_id FROM emp_locations EL INNER JOIN employee_personaldetail UD ON UD.user_id=EL.user_id WHERE UD.parent_id='".$user_id."'");
	    $childheadquaters = $this->fetchAll($execute);
		foreach($childheadquaters as $childheadquater){
		  $hq[] = $childheadquater['headquater_id']; 
		}
		return $hq;
	}
	 public function query($query){
	   $execute = mysql_query($query);
	   return $execute;
	 }
	 public function fetchAll($execute){
	   $return_array = array();
	   while($data = mysql_fetch_assoc($execute)){
	     $return_array[] = $data;
	   }
	   return $return_array;
	 }
	 public function fetchRow($execute){
	   $data = mysql_fetch_assoc($execute);
	   return $data;
	 }
	 public function getsinglrArr($array,$niddle,$userids){
	   $filterarra = array();
	   foreach($array as $niddlearr){
	      if($niddlearr[$niddle]>0){
	      	$userids[] = $niddlearr[$niddle];
		  } 
	   }
	   return array_unique($userids);
	 }
 
}
?>