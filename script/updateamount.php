<?php
error_reporting(1);
$con = mysql_connect('localhost','jclifesc_hrmERP','h[)RNx~0W*DQ');
	mysql_select_db('jclifesc_hrmERP',$con);
	
	$sql = "SELECT * FROM employee_personaldetail WHERE user_status='1' AND delete_status='0'";
	$execute = mysql_query($sql);
	while($result = mysql_fetch_assoc($execute)){
	   $sql1 = "SELECT * FROM salary_list WHERE user_id='".$result['user_id']."' AND salaryhead_id=3 AND date='2014-12-02'";
	   $execute1 = mysql_query($sql1);
	   $result1 = mysql_fetch_assoc($execute1);
	   $expense  = array('JC00027'=>'0','JC00028'=>'0','JC00036'=>'7033','JC00037'=>'0','JC00060'=>'7949','JC00062'=>'10869','JC00082'=>'20316','JC00103'=>'26762','JC00107'=>'0','JC00108'=>'0','JC00112'=>'0','JC00113'=>'13626','JC00140'=>'20769','JC00142'=>'10108','JC00143'=>'5400','JC00152'=>'0','JC00148'=>'0','JC00146'=>'0','JC00001'=>'0','JC00135'=>'1751','JC00145'=>'0','JC00147'=>'0','JC00150'=>'0','JC00151'=>'0','JC00153'=>'0','JC00154'=>'0','JC00155'=>'0','JC00156'=>'0','JC00157'=>'0','JC00158'=>'0','JC00012'=>'15063','JC00020'=>'7760','JC00024'=>'10684','JC00030'=>'1000','JC00033'=>'1050','JC00053'=>'4325','JC00076'=>'15902','JC00079'=>'5492','JC00085'=>'3460','JC00088'=>'13352','JC00091'=>'2375','JC00106'=>'8034','JC00111'=>'5580','JC00115'=>'6828','JC00128'=>'5300','JC00130'=>'6125','JC00132'=>'4480','JC00134'=>'1340','JC00137'=>'5100','JC00138'=>'5420','JC00141'=>'1230','JC00144'=>'2980','JC00086'=>'0','JC00109'=>'0','JC00123'=>'0','JC00125'=>'0','JC00129'=>'0','JC00131'=>'0','JC00133'=>'0','JC00139'=>'0');
	   if(!empty($result1)){
		  //$tds  = array('JC00082'=>'526','JC00103'=>'2407','JC00107'=>'1927','JC00108'=>'3568','JC00112'=>'578','JC00001'=>'22000');
		  if($expense[$result['employee_code']]>0){
	       $update = "UPDATE salary_list SET amount='".$expense[$result['employee_code']]."' WHERE user_id='".$result['user_id']."' AND salaryhead_id='".$result1['salaryhead_id']."' AND date='2014-12-02'";
		  // mysql_query($update); 
		   print_r($update);echo '<br>'; 
		  } 
	   }else{
	     if($expense[$result['employee_code']]>0){
			 $insert = "INSERT INTO salary_list SET user_id='".$result['user_id']."',amount='".$expense[$result['employee_code']]."',salaryhead_id=3,salaryheade_type=1,date='2014-12-02'";
			// mysql_query($insert); 
			 print_r($insert);echo '<br>'; 
		 }
	   } 
	}

	
	
	/*$sql = "SELECT * FROM employee_personaldetail WHERE user_status='1' AND delete_status='0'";
	$execute = mysql_query($sql);
	while($result = mysql_fetch_assoc($execute)){
	   $sql1 = "SELECT * FROM employee_salary_amount WHERE user_id='".$result['user_id']."' AND salaryhead_id!=3 ORDER BY salaryhead_id ASC";
	   $execute1 = mysql_query($sql1);
	   while($result1 = mysql_fetch_assoc($execute1)){
	       $update = "UPDATE salary_list SET amount=".$result1['amount']." WHERE user_id='".$result['user_id']."' AND salaryhead_id='".$result1['salaryhead_id']."' AND date='2014-12-02'";
		   mysql_query($update);
		   print_r($update);echo '<br>';
	   }
	}
*/
?>