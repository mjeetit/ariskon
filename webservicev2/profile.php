<?php
header('Content-type: application/json');
$user_id = isset($_REQUEST['user'])?$_REQUEST['user']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
	
$sql = "SELECT EL.*,LT.typeName FROM emp_leaves EL INNER JOIN leavetypes LT ON LT.typeID=EL.leave_id WHERE EL.user_id='".$user_id."'";
$execute = mysql_query($sql);
 $returnLeave = array();	
  while($data = mysql_fetch_assoc($execute)){
     $dataArr['Type'] = $data['typeName'];
	 $dataArr['Leave'] = $data['no_of_leave'];
	 $returnLeave[] = $dataArr;
  }
  $sql = "SELECT * FROM salary_history SH WHERE SH.user_id='".$user_id."' ORDER BY salary_date DESC LIMIT 5";
  $execute = mysql_query($sql);
  $returnSalary = array();	
  while($data1 = mysql_fetch_assoc($execute)){
     $dataArr1['Month'] =  date('M-Y',strtotime($data1['salary_date']));
	 $dataArr1['Salary'] = $data1['net_amount'];
	 $returnSalary[] = $dataArr1;
  }
 echo json_encode(array('success'=>'YES','message'=>$returnLeave,'message1'=>$returnSalary));exit;  
?>