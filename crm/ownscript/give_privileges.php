<?php
	mysql_connect('localhost','root','') or die("Server couldn't connect!!");
	mysql_select_db('jccrm') or die("Database couldn't connect!!");
	
	//mysql_connect('localhost','jclifesc_hrmERP','h[)RNx~0W*DQ') or die("Server couldn't connect!!");
	//mysql_select_db('jclifesc_hrmERP') or die("Database couldn't connect!!");
	
	$userStatement = "SELECT user_id,designation_id FROM employee_personaldetail WHERE designation_id>4 AND designation_id<9";
	$userExecution = mysql_query($userStatement) or die('Statement error!!');
	while($data=mysql_fetch_assoc($userExecution)) {
		$addStatement = "INSERT INTO crm_userprivileges (user_id, module_id, section_id) VALUES 
						(".$data['user_id'].",'1','1'),(".$data['user_id'].",'1','2'),(".$data['user_id'].",'1','3'),(".$data['user_id'].",'1','5'),
						(".$data['user_id'].",'2','1'),(".$data['user_id'].",'1','3'),
						(".$data['user_id'].",'4','1'),(".$data['user_id'].",'4','3')
						";
		$addExecution = mysql_query($addStatement) or die('Add Statement error!!');
		
		if($data['designation_id']<8) {
			$addStatement1 = "INSERT INTO crm_userprivileges (user_id, module_id, section_id) VALUES 
							 (".$data['user_id'].",'2','2'),(".$data['user_id'].",'4','2'),(".$data['user_id'].",'6','3')
							 ";
			$addExecution1 = mysql_query($addStatement1) or die('Add1 Statement error!!');
		}
	}
?>