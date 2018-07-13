<?php
include 'config.php';

     $expensetting = expensesettingid();
	 foreach($expensetting as $expenses){
	    $expensesettingamounts = expenseheadsandamount($expenses['user_id'],$expenses['exp_setting_id']);
		 foreach($expensesettingamounts as $expensesettinS){
		    updateexpenses($expenses['user_id'],$expensesettinS['head_id'],$expensesettinS['expense_amount']);
		}
		//echo "<pre>"; print_r($expensesettingamounts);die;
	 }
	 
	echo "<pre>"; print_r($expensetting);die;


function expensesettingid(){
     $sql = "SELECT ET.user_id,ET.employee_code,EE.exp_setting_id FROM employee_personaldetail ET INNER JOIN expense_setting EE ON EE.department_id=ET.department_id AND EE.designation_id=ET.designation_id ORDER BY ET.user_id";
	 $execute = mysql_query($sql);
	 $return = array();
	while($data = mysql_fetch_assoc($execute)){
		$return[] = $data;
	 }
 return $return;
}
function expenseheadsandamount($user_id,$expsetting_id){
     $sql = "SELECT * FROM expense_template_amount ET WHERE ET.exp_setting_id='".$expsetting_id."'";
	 $execute = mysql_query($sql);
	 $return = array();
	while($data = mysql_fetch_assoc($execute)){
		$return[] = $data;
	 }
 return $return;
}
function updateexpenses($user_id,$head_id,$amount){
    $sql = "UPDATE emp_expense_amount SET expense_amount='".$amount."' WHERE user_id='".$user_id."' AND head_id='".$head_id."'";
	$execute = mysql_query($sql);
	echo $sql.'-'.getoriginalAmount($user_id,$head_id).'-'.$execute.'<br>'; 

}
function getoriginalAmount($user_id,$head_id){
     $sql = "SELECT * FROM emp_expense_amount WHERE user_id='".$user_id."' AND head_id='".$head_id."'";
	 $execute = mysql_query($sql);
	 $data = mysql_fetch_assoc($execute);
	 return $data['expense_amount'];
}
?>