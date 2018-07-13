<?php
header('Content-type: application/json');
$parent = isset($_REQUEST['user'])?$_REQUEST['user']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
	 $designation_id = getDesignattion($parent);
	 if($designation_id==5 || $designation_id==6 || $designation_id==7){
		 $belists = getBElist($parent,$designation);
		 foreach($belists as $belist){
			$data = array();
			$data['User'] 	  = $belist['user_id'];
			$data['Parent']   = $belist['parent_id'];
			$data['Name'] 	  = $belist['designation_code'].'-'.$belist['first_name'].' '.$belist['last_name'];
			$newdata[] 	  = $data;
		  }
	  }
	 if($designation_id==5 || $designation_id==6){  
	  $abmlists = getABMlist($parent,$designation);
	 foreach($abmlists as $abmlist){
	    $data = array();
	  	$data['User'] 	  = $abmlist['user_id'];
	  	$data['Parent']   = $abmlist['parent_id'];
		$data['Name'] 	  = $abmlist['designation_code'].'-'.$abmlist['first_name'].' '.$abmlist['last_name'];
		$newdata[] 	  = $data;
	  }
	 }
	if($designation_id==5){
	  $rbmlists = getRBMlist($parent,$designation);
	 foreach($rbmlists as $rbmlist){
	    $data = array();
	  	$data['User'] 	  = $rbmlist['user_id'];
	  	$data['Parent']   = $rbmlist['parent_id'];
		$data['Name'] 	  = $rbmlist['designation_code'].'-'.$rbmlist['first_name'].' '.$rbmlist['last_name'];
		$newdata[] 	  = $data;
	  }
	}  
	if(!empty($newdata)){
	   echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
	}else{
	    echo json_encode(array('success'=>'NO','message'=>'These is no Emp Found!')); exit;
	}
	
	
	function getDesignattion($param){
    $sql = "SELECT designation_id,parent_id FROM employee_personaldetail WHERE user_id='".$param."'";
	$execute = mysql_query($sql);
	$descheck = mysql_fetch_assoc($execute);
	$designation = $descheck['designation_id'];
	return $designation;
 }	
 
 function getBElist($param,$designation){
    $where = '';
	$where1 = '';
    $designation = getDesignattion($param);
     $data = array();
	 
    if($designation==5){
	   $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		      INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
			  INNER JOIN  employee_personaldetail REL ON PEL.parent_id=REL.user_id
   		      INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE REL.user_id='".$param."' AND EL.designation_id=8 AND EL.delete_status='0'";
	  $execute = mysql_query($sql);
	  $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
			$user_ids[] = $result['user_id'];
			
	   }
	   if(!empty($user_ids)){
	      $where1 = " AND EL.user_id NOT IN(".implode(',',$user_ids).")";
	   } 
	}
	if($designation==6 || $designation==5){ //RBM
	   $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		      INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
   		      INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE PEL.parent_id='".$param."' AND EL.designation_id=8 AND EL.delete_status='0'".$where1;		  
	   $execute = mysql_query($sql);
	   $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
			$user_ids[] = $result['user_id'];
	   }
	   if(!empty($user_ids)){
	      $where1 = " AND EL.user_id NOT IN(".implode(',',$user_ids).")";
	   } 
			 
	}
	if($designation==7 || $designation==5 || $designation==6){ //abm
	  $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.designation_id=8 AND EL.parent_id='".$param."' AND EL.delete_status='0'".$where1;
	  $execute = mysql_query($sql);
	  $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
			$user_ids[] = $result['user_id'];
	   }
	   if(!empty($user_ids)){
	      $where1 = " AND EL.user_id NOT IN(".implode(',',$user_ids).")";
	   } 
	}
   return $data;
}
function getABMlist($param,$designation){
    $where = '';
	$where1 = '';
    $designation = getDesignattion($param);
     $data = array();
    if($designation==5){
	   $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		      INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
   		      INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE PEL.user_id='".$param."' AND EL.designation_id=7 AND EL.delete_status='0'";
	  $execute = mysql_query($sql);
	  $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
			$user_ids[] = $result['user_id'];
			
	   }
	   if(!empty($user_ids)){
	      $where1 = " AND EL.user_id NOT IN(".implode(',',$user_ids).")";
	   } 
	}
	if($designation==6 || $designation==5){ //RBM
	    $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.designation_id=7 AND EL.parent_id='".$param."' AND EL.delete_status='0'".$where1;
	   $execute = mysql_query($sql);
	   $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
	   }
	}
	if($designation==8){ //abm
	  $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
	   		 INNER JOIN  employee_personaldetail PEL ON PEL.parent_id=EL.user_id
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.designation_id=7 AND PEL.user_id='".$param."' AND EL.delete_status='0'";
	  $execute = mysql_query($sql);
	  $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
			$user_ids[] = $result['user_id'];
	   }
	   if(!empty($user_ids)){
	      $where1 = " AND EL.user_id NOT IN(".implode(',',$user_ids).")";
	   } 
	}
   return $data;
}
function getRBMlist($param,$designation){
    $where = '';
	$where1 = '';
    $designation = getDesignattion($param);
     $data = array();
    if($designation==5){
	   $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		      INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.parent_id='".$param."' AND EL.designation_id=6 AND EL.delete_status='0'";
	  $execute = mysql_query($sql);
	  $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
	   }
	}
	
	if($designation==8){ //BE
	  $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
	   		 INNER JOIN  employee_personaldetail PEL ON PEL.parent_id=EL.user_id
			 INNER JOIN  employee_personaldetail PPEL ON PPEL.parent_id=PEL.user_id
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.designation_id=6 AND PPEL.user_id='".$param."' AND EL.delete_status='0'";
	  $execute = mysql_query($sql);
	  $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
			$user_ids[] = $result['user_id'];
	   }
	   if(!empty($user_ids)){
	      $where1 = " AND EL.user_id NOT IN(".implode(',',$user_ids).")";
	   }
	 }
	 
	if($designation==7 || $designation==8){ //RBM
	   $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
	   		 INNER JOIN  employee_personaldetail PEL ON PEL.parent_id=EL.user_id
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.designation_id=6 AND PEL.user_id='".$param."' AND EL.delete_status='0'".$where1;
	   $execute = mysql_query($sql);
	   $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
	   }
	 }
	
   return $data;
}
   
?>