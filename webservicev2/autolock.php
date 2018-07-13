<?php
	class Autolock{
		private $conn;
		function __construct() {
			$this->conn = mysqli_connect("localhost","jclifesc_hrmERP","h[)RNx~0W*DQ","jclifesc_hrmERP");
			date_default_timezone_set('Asia/Calcutta'); 	
		}
		public function autolocker(){
			$sql = "SELECT * FROM `app_userdetails` WHERE `lock_action` = '0' OR `check_validation` = '0'";
			$execute =mysqli_query($this->conn, $sql);
			while($result = mysqli_fetch_assoc($execute)){
				if($result['last_lock_date']!='0000-00-00 00:00:00'){
					$lockDate=strtotime($result['last_lock_date']);
					$currentDate=time();
					$diff=$currentDate-$lockDate;
					if($diff>80400){
						$sql="UPDATE `app_userdetails` 
							SET `lock_action` = '1',
								`check_validation` = '1',
								`last_lock_date` = '0000-00-00 00:00:00' 
							WHERE `app_userdetails`.`id` = '".$result['id']."'";
						mysqli_query($this->conn, $sql); 
					}
				}
			}
		}
	}
	$obj= new Autolock();
	$obj->autolocker();
?>