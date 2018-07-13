<?php
header('Content-type: application/json');
$parent = isset($_REQUEST['user'])?$_REQUEST['user']:0;
$empid = isset($_REQUEST['empid'])?$_REQUEST['empid']:0;
$patchid = isset($_REQUEST['patchid'])?$_REQUEST['patchid']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
$taskmode = isset($_REQUEST['taskmode'])?$_REQUEST['taskmode']:'';
include 'config.php';
   		$patchlistofemp = array();
		if($taskmode=='DoctorVisit'){
		  $patchlistofemp = doctorlist($parent,$empid,$patchid);
		 }
		 if($taskmode=='ChemistVisit'){
		  $patchlistofemp = chemistlist($parent,$empid,$patchid);
		 }
		 if($taskmode=='StockistVisit'){
		  $patchlistofemp = stockistlist($parent,$empid,$patchid);
		 }
		 foreach($patchlistofemp as $patches){
			$data = array();
			$data['Doctor'] 	  = $patches['doctor_id'];
			$data['Name']   = $patches['doctor_name'];
			$data['Code'] 	  = $patches['doctor_code'];
			$newdata[] 	  = $data;
		  }
	if(!empty($newdata)){
	   echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
	}else{
	    echo json_encode(array('success'=>'NO','message'=>'These is no Patch Found!')); exit;
	}
 
 function doctorlist($param,$empid,$patchid){
    //$where = getconditionBasedBEHeadquater($empid,$designation,$patch_id);
    $sql = "SELECT DT.doctor_id,DT.doctor_code,DT.doctor_name,PT.patch_id FROM crm_doctors AS DT INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id WHERE PT.patch_id='".$patchid."' ORDER BY DT.doctor_name ASC";
	$execute = mysql_query($sql);
	while($data = mysql_fetch_assoc($execute)){
	   $result[] = $data;
	}
   return $result;
}
function chemistlist($param,$empid,$patchid){
	$sql = "SELECT CT.chemist_id AS doctor_id,CT.legacy_code AS doctor_code,CT.chemist_name AS doctor_name,PT.patch_id FROM crm_chemists CT 
   		   INNER JOIN crm_doctor_chemists DCT ON DCT.chemist_id=CT.chemist_id
		   INNER JOIN crm_doctors DT ON DT.doctor_id=DCT.doctor_id 
		   INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id 
		   INNER JOIN emp_locations EL ON EL.headquater_id=PT.headquater_id  WHERE PT.patch_id='".$patchid."'";

	$execute = mysql_query($sql);
	while($data = mysql_fetch_assoc($execute)){
	   $result[] = $data;
	}
   return $result;
}
function stockistlist($param,$empid,$patchid){
    $sql = "SELECT CT.chemist_id as doctor_id,CT.legacy_code AS doctor_code,PT.patch_id,ST.stockist_name as doctor_name FROM crm_chemists CT 
           INNER JOIN crm_chemist_stockists ST ON ST.chemist_id=CT.chemist_id
   		   INNER JOIN crm_doctor_chemists DCT ON DCT.chemist_id=CT.chemist_id
		   INNER JOIN crm_doctors DT ON DT.doctor_id=DCT.doctor_id 
		   INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id 
		   INNER JOIN emp_locations EL ON EL.headquater_id=PT.headquater_id  WHERE PT.patch_id='".$patchid."'";
		   
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