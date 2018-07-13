<?php
header('Content-type: application/json');
$mode 			= isset($_REQUEST['mode'])?$_REQUEST['mode']:'Test';
$parent 		= isset($_REQUEST['parent'])?$_REQUEST['parent']:0;
$user_id 		= isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$designation 	= isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
$patch_id 		= isset($_REQUEST['patch_id'])?$_REQUEST['patch_id']:0;
include 'config.php';

function getBElist($parent,$designation,$check=true){
	$childusers = getChildUser(array($parent),1);
	$belist = array();
	if($designation==8 && $check){
	   return array();
	}
	$sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE user_id IN(".implode(',',$childusers).") AND EL.designation_id=8 AND EL.delete_status='0' GROUP BY EL.user_id";
	$execute = mysql_query($sql);
	 while($result = mysql_fetch_assoc($execute)){
			$belist[] = $result;
	 }
	 return $belist;
}

function getABMlist($parent,$designation){
	$abmlist = array();
	if($designation==7){
	   return array();
	}
	if($designation<7){
	   $childusers = getChildUser(array($parent),1);
	}else{
	   $childusers = getParentUser(array($parent),1);
	}
	$sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE user_id IN(".implode(',',$childusers).") AND EL.designation_id=7 AND EL.delete_status='0' GROUP BY EL.user_id";
	$execute = mysql_query($sql);
	 while($result = mysql_fetch_assoc($execute)){
			$abmlist[] = $result;
	 }
	 return $abmlist;
}
function getRBMlist($parent,$designation){
	$rbmlist = array();
	if($designation==6){
	   return array();
	}
	if($designation<6){
	   $childusers = getChildUser(array($parent),1);
	}else{
	   $childusers = getParentUser(array($parent),1);
	}
	$sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE user_id IN(".implode(',',$childusers).") AND EL.designation_id=6 AND EL.delete_status='0' GROUP BY EL.user_id";
	$execute = mysql_query($sql);
	 while($result = mysql_fetch_assoc($execute)){
			$rbmlist[] = $result;
	 }
	 return $rbmlist;
}
function getZBMlist($parent,$designation){
	$rbmlist = array();
	if($designation==5){
	   return array();
	}
	$childusers = getParentUser(array($parent),1);
	$sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE user_id IN(".implode(',',$childusers).") AND EL.designation_id=5 AND EL.delete_status='0' GROUP BY EL.user_id";
	$execute = mysql_query($sql);
	 while($result = mysql_fetch_assoc($execute)){
			$rbmlist[] = $result;
	 }
	 return $rbmlist;
}

function getChildUser($param,$count){
   $sql = "SELECT EL.user_id FROM employee_personaldetail EL
   		   WHERE EL.parent_id IN('".implode("','",$param)."') AND EL.delete_status='0' AND designation_id IN(8,7,6,5)";//print_r( $sql);die;
	  $execute = mysql_query($sql);
	  //$user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$param[] = $result['user_id'];
	   }
	   if($count<4){
	   		$count = $count+1;
		    return getChildUser($param,$count);
	   }else{
	     return $param;
	   }
}
function getParentUser($param,$count){
   $sql = "SELECT EL.parent_id FROM employee_personaldetail EL
   		   WHERE EL.user_id IN('".implode("','",$param)."') AND EL.delete_status='0' AND designation_id IN(8,7,6,5)";//print_r( $sql);die;
	  $execute = mysql_query($sql);
	  //$user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$param[] = $result['parent_id'];
	   }
	   if($count<4){
	   		$count = $count+1;
		    return getParentUser($param,$count);
	   }else{
	     return $param;
	   }
}
function getDesignation($parent){
	$sql = "SELECT designation_id FROM employee_personaldetail EL WHERE EL.user_id='".$parent."'";//
	$execute = mysql_query($sql);
	$result = mysql_fetch_assoc($execute);
	return $result['designation_id'];
}
function headQuatersList($user){
  $designation = getDesignation($user);
  $users = getBElist($user,$designation,false);
  $userlist = array();
  $headquaters = array();
  foreach($users as $user){
    $userlist[] = $user['user_id'];
  }
  $sql = "SELECT * FROM emp_locations WHERE user_id IN(".implode(',',$userlist).")";//print_r( $sql);die;
  $execute = mysql_query($sql);
  while($result = mysql_fetch_assoc($execute)){
  	$headquaters[] = $result['headquater_id'];
  }
  return $headquaters;
}
function getPatchList($param){
    $where = headQuatersList($param);
    $sql = "SELECT PT.patch_id,PT.patchcode,PT.patch_name,PT.city_id,PT.headquater_id FROM patchcodes AS PT  WHERE 1 AND PT.headquater_id IN(".implode(',',$where).") ORDER BY PT.patch_name ASC";
   //print_r($sql);die;
	$execute = mysql_query($sql);
	while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
//$designation = getDesignation(2);
$users = getPatchList(2);
print_r($users);die;
?>