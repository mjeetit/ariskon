<?php
header('Content-type: application/json');
$parent = isset($_REQUEST['user'])?$_REQUEST['user']:0;
$empid = isset($_REQUEST['empid'])?$_REQUEST['empid']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
		 $patchlistofemp = patchlist($parent,$empid);
		 foreach($patchlistofemp as $patches){
			$data = array();
			$data['Patch'] 	  = $patches['patch_id'];
			$data['Name']   = $patches['patch_name'];
			$data['Code'] 	  = $patches['patchcode'];
			$newdata[] 	  = $data;
	  }
	  
	  if(!empty($newdata)){
	   echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
	}else{
	    echo json_encode(array('success'=>'NO','message'=>'These is no Patch Found!')); exit;
	}
 
 function patchlist($param,$empid){
    $where = getconditionBasedBEHeadquater($empid,$designation,$patch_id);
    $sql = "SELECT PT.patch_id,PT.patchcode,PT.patch_name,PT.city_id,PT.headquater_id FROM patchcodes AS PT INNER JOIN emp_locations EL ON EL.headquater_id=PT.headquater_id  WHERE 1".$where." GROUP BY PT.patch_id";
	$execute = mysql_query($sql);
	while($data = mysql_fetch_assoc($execute)){
	   $result[] = $data;
	}
   return $result;
}
function getconditionBasedBEHeadquater($param=false,$designation,$patch_id){ 
   $sql = "SELECT designation_id FROM employee_personaldetail WHERE user_id='".$param."'";
	$execute = mysql_query($sql);
	$designations = mysql_fetch_assoc($execute);
	
   $where = '';
   if($designations['designation_id']==5){
      if($patch_id>0){
	     $where = " AND PT.patch_id='".$patch_id."'";
		 return $where;
	  }
     $sql = "SELECT ED.user_id,EL.city_id,EL.headquater_id 
	  		  FROM employee_personaldetail ED 
			  INNER JOIN employee_personaldetail PPEL ON PPEL.user_id=ED.parent_id
			  INNER JOIN employee_personaldetail PEL ON PEL.user_id=PPEL.parent_id
			   INNER JOIN employee_personaldetail ZEL ON ZEL.user_id=PEL.parent_id
			  INNER JOIN emp_locations EL ON EL.user_id=ED.user_id WHERE ED.designation_id=8 AND ZEL.user_id='".$param."'"; 
	 $execute = mysql_query($sql);
	  $headquaters = array();
	 while($locations = mysql_fetch_assoc($execute)){
	   $headquaters[] = $locations['headquater_id'];
	 }
	 $where = " AND PT.headquater_id IN(".implode(',',$headquaters).")";
   }
   if($designations['designation_id']==6 || empty($headquaters)){
      $sql = "SELECT ED.user_id,EL.city_id,EL.headquater_id 
	  		  FROM employee_personaldetail ED 
			  INNER JOIN employee_personaldetail PPEL ON PPEL.user_id=ED.parent_id
			  INNER JOIN employee_personaldetail PEL ON PEL.user_id=PPEL.parent_id
			  INNER JOIN emp_locations EL ON EL.user_id=ED.user_id WHERE ED.designation_id=8 AND PEL.user_id='".$param."'";
	 $execute = mysql_query($sql);
	 $headquaters = array();
	 while($locations = mysql_fetch_assoc($execute)){
	   $headquaters[] = $locations['headquater_id'];
	 }
	 $where = " AND PT.headquater_id IN(".implode(',',$headquaters).")";
   }
   if($designations['designation_id']==7 || empty($headquaters)){
     $sql = "SELECT ED.user_id,EL.city_id,EL.headquater_id FROM employee_personaldetail ED INNER JOIN emp_locations EL ON EL.user_id=ED.user_id WHERE ED.parent_id='".$param."'";
	 $execute = mysql_query($sql);
	 $city_ids = array();
	 $headquaters = array();
	 while($locations = mysql_fetch_assoc($execute)){
	   $headquaters[] = $locations['headquater_id'];
	 }
	 $where = " AND PT.headquater_id IN(".implode(',',$headquaters).")";
   }
   
   if($designations['designation_id']==8 || $designations['designation_id']==2){ 
      $sql = "SELECT * FROM emp_locations WHERE user_id='".$param."'"; 
	 $execute = mysql_query($sql);
	 $locations = mysql_fetch_assoc($execute);
	 $where = " AND PT.headquater_id='".$locations['headquater_id']."'";
   }
   return $where;
 }

   
?>